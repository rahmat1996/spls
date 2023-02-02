<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('home_model');
    }

    public function index()
    {
        $this->load->view('home/index');
    }

    public function subscribe($uid=null)
    {
        if (is_null($uid) || empty($uid)) {
            show_404();
        }
        $sub = $this->home_model->update_contact_to_subscribe($uid);
        if ($sub) {
            echo "Congrats! Already Subscribe.";
        } else {
            echo "Sorry, cannot subscribe. Maybe already subscribe before, or something happen on system.";
        }
    }

    public function unsubscribe($uid=null)
    {
        if (is_null($uid) || empty($uid)) {
            show_404();
        }

        $unsub = $this->home_model->update_contact_to_unsubscribe($uid);
        if ($unsub) {
            echo "Congrats! Already Unsubscibe.";
        } else {
            echo "Sorry, cannot unsubscribe. Maybe already unsubscribe before, or something happen on system.";
        }
    }
}
