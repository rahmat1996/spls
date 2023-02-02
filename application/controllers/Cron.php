<?php

defined('BASEPATH') or exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

class Cron extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('cron_model');
    }

    public function index()
    {
        show_404();
    }

    public function execute()
    {
        // set not use time limit or close connection
        ignore_user_abort(true);
        set_time_limit(0);

        // get configuration
        $config = $this->cron_model->get_config();
        if (!$config) {
            exit();
        }

        $smtp_row = $config->smtp_row; // smtp row now.
        $contact_row = $config->contact_row; // contact row now.
        $max_initial_send = $config->max_initial_send; // max send email on unconfirm contact state
        $max_send_mail_perday = $config->max_send_mail_perday; // max send email per day
        $app_name = $config->app_name;
        $url_logo = $config->url_logo;
        $url_subs = $config->url_subscribe;
        $url_unsubs = $config->url_unsubscribe;
        $url_skipad = $config->url_skip_ads;

        // get smtp
        $smtp = $this->cron_model->get_smtp($smtp_row);
        $smtp_count = $this->cron_model->get_count_smtp(); // get count all smtp
        $smtp_limit = $smtp ? $smtp->send_limit : 0;

        if ($smtp_count == 0) {
            exit();
        }

        $smtp_row += 1;
        if ($smtp_row >= $smtp_count) {
            $smtp_row = 0;
        }

        $this->cron_model->update_config(['smtp_row'=>$smtp_row]);

        $contact_count = $this->cron_model->get_count_contact();

        if ($contact_count == 0) {
            exit();
        }

        $contact_sends = []; // array for contact list to send
        $contact_unsubs = []; // array for contact list to unsub

        $suff_contact = false;
        $row = $contact_row;

        while (!$suff_contact) {
            $contacts = $this->cron_model->get_contact($smtp_limit, $row);
            if (count($contacts)>0) {
                foreach ($contacts as $contact) {
                    if ($contact->state == 0) {
                        $log_count = $this->cron_model->get_log_count($contact->id);
                        if ($log_count >= $max_initial_send) {
                            array_push($contact_unsubs, ['id'=>$contact->id,'state'=>2]); // push to unsubs if contact already max initial send
                        } else {
                            array_push($contact_sends, $contact); // push to sends
                        }
                    } else {
                        array_push($contact_sends, $contact); // push to sends
                    }
                }
                // if contact sends array already same or more than smtp limit than stop looping.
                if (count($contact_sends) >= $smtp_limit) {
                    $contact_row += $smtp_limit;
                    break;
                }
                $row += $smtp_limit;
            } else {
                $contact_row = 0;
                $suff_contact = true;
            }
        }

        if (count($contact_unsubs)) {
            $this->cron_model->update_batch_contact_to_unsubscribe($contact_unsubs);
        }

        if (count($contact_sends)) {
            $logs = [];
            $links = $this->cron_model->get_link();
            if (count($links) == 0) {
                exit();
            }

            foreach ($contact_sends as $contact_send) {
                $random = array_rand($links);
                $link = $links[$random];

                $subject = $this->get_subject($link->name);

                $url_subs = $url_subs.'/'.$contact_send->uid;
                $url_unsubs = $url_unsubs.'/'.$contact_send->uid;

                if ($contact_send->state == 1) {
                    $message = $this->get_message_subscriber($link->name, $link->url, $url_logo, $url_skipad, $app_name);
                } else {
                    $message = $this->get_message_common($link->name, $link->url, $url_logo, $url_subs, $url_unsubs, $url_skipad, $app_name);
                }

                $send = $this->send_mail($smtp->name, $smtp->host, $smtp->username, $smtp->password, $smtp->port, $smtp->secured_by, $contact_send->email, $subject, $message);

                $log =
                [
                    'contact_id'=>$contact_send->id,
                    'link_id'=>$link->id,
                    'smtp_id'=>$smtp->id,
                    'date'=>time(),
                    'remarks'=>$send['message'],
                    'state'=>$send['state']
                ];

                array_push($logs, $log);
            }

            if (count($logs)) {
                $this->cron_model->insert_batch_log($logs);
            }
        }

        if ($contact_row >= $max_send_mail_perday) {
            $contact_row = 0;
        }

        $this->cron_model->update_config(['contact_row'=>$contact_row]);
    }

    private function send_mail($name, $host, $username, $password, $port, $secured_by, $address, $subject, $message)
    {
        $mail = new PHPMailer(true);

        $mail->SMTPDebug = false;

        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        $mail->isSMTP();
        $mail->Host = $host;
        $mail->SMTPAuth = true;
        $mail->Username = $username;
        $mail->Password = $password;
        $mail->Port = $port;
        $mail->SMTPSecure = $secured_by;
        $mail->setFrom($username, $name);
        $mail->addAddress($address);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;

        if ($mail->send()) {
            return ['state'=>1,'message'=>''];
        } else {
            return ['state'=>0,'message'=>'Error : '.$mail->ErrorInfo];
        }
    }

    private function get_subject($name)
    {
        return "Your music today is {$name}";
    }

    private function get_message_subscriber($name, $link, $logo_link, $skipad_link, $app_name)
    {
        return "<!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8' />
            <style>
                .page-mail {
                    background: #fff;
                }
        
                .header-mail {
                    border: 1px solid #ccc;
                    padding: 10px;
                    text-align: center;
                }
        
                .body-mail {
                    border: 1px solid #ccc;
                    padding: 10px;
                    border-top: none;
                    text-align: justify;
                }
        
                .footer-mail {
                    border: 1px solid #ccc;
                    padding: 10px;
                    border-top: none;
                    text-align: center;
                }
            </style>
            <title>Email</title>
        </head>
        
        <body>
            <div class='page-mail'>
                <div class='header-mail'>
                    <img src='{$logo_link}'
                        width='180' height='180' alt='logo' />
                        <h3>{$app_name}</h3>
                </div>
                <div class='body-mail'>
                    <p>Have a nice day!</b>
                        <p>Today I want to share the music link for you.</p>
                        <p>The music is titled <b>{$name}</b>.</p>
                        <p>Here's the link to the music.</p>
                        <p><a href='{$link}'>{$link}</a></p>
        
                        <p>I hope you like it.</p>
                        <p>See you again.</p>
        
                        <p>P.S : </p>
        
                        <p>1. The music link is not a malicious link. But when clicked it will direct you to an
                            advertisement page,
                            after passing the ad, you will arrive at the music link.
                            By clicking on the link and also viewing the ad, you have helped me.</p>
        
                        <p>2. If you have problems skipping ads, you can visit this link: <a
                                href='{$skipad_link}'>{$skipad_link}</a></p>
        
                        <p>3. Do not reply to this email, because this email is automated.</p>
                        <p>4. Music links are randomly selected. So there is a possibility to get the same link</p>
                </div>
                <div class='footer-mail'>Generated by {$app_name}</div>
            </div>
        </body>
        
        </html>";
    }

    private function get_message_common($name, $link, $logo_link, $sub_link, $unsub_link, $skipad_link, $app_name)
    {
        return "
        <!DOCTYPE html>
        <html lang='en'>
        
        <head>
            <meta charset='UTF-8' />
            <style>
                .page-mail {
                    background: #fff;
                }
        
                .header-mail {
                    border: 1px solid #ccc;
                    padding: 10px;
                    text-align: center;
                }
        
                .body-mail {
                    border: 1px solid #ccc;
                    padding: 10px;
                    border-top: none;
                    text-align: justify;
                }
        
                .footer-mail {
                    border: 1px solid #ccc;
                    padding: 10px;
                    border-top: none;
                    text-align: center;
                }
            </style>
            <title>Email</title>
        </head>
        
        <body>
            <div class='page-mail'>
                <div class='header-mail'>
                    <img src='{$logo_link}'
                        width='180' height='180' alt='logo' />
                        <h3>{$app_name}</h3>
                </div>
                <div class='body-mail'>
                    <p>Hi, I want to share a music link from my systems randomly.</p>
                    <p>I hope you will like it.</p>
                    <p> This music is titled <b>{$name}</b>.</p>
                    <p>Here's the link to the music.</p>
                    <p><a href='{$link}'>{$link}</a></p>
                    <p>If you wish to be sent a link like this again, you can click the subscribe link below.</p>
                    <p><b>Subscribe link : </b><a href='{$sub_link}'>{$sub_link}</a></p>
        
                    <p>If you don't want to be sent a link like this again, you can click the link below.</p>
                    <p><b>Unsubscribe link : </b><a href='{$unsub_link}'>{$unsub_link}</a></p>
                    <p>If you don't choose, then I will still send this link up to 3 times, including this link.</p>
        
                    <p>Thank you.</p>
        
                    <p>P.S : </p>
        
                    <p>1. The music link is not a malicious link. But when clicked it will direct you to an advertisement page,
                        after passing the ad, you will arrive at the music link.
                        By clicking on the link and also viewing the ad, you have helped me.</p>
        
                    <p>2. If you have problems skipping ads, you can visit this link: <a
                            href='{$skipad_link}'>{$skipad_link}</a></p>
        
                    <p>3. Do not reply to this email, because this email is automated.</p>
                    <p>4. Music links are randomly selected. So there is a possibility to get the same link</p>
                </div>
                <div class='footer-mail'>Generated by {$app_name}</div>
            </div>
        </body>
        
        </html>
        ";
    }
}
