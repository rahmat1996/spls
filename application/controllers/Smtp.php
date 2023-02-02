<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Smtp extends MY_Controller
{
    public $data = ['title'=>'SMTPs','menu'=>'smtp','is_edit'=>false];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('smtp_model');
        $this->load->library('form_validation');
        $this->load->helper('form');
    }
    public function index()
    {
        $this->load->view('smtp/index', $this->data);
    }

    // return all data to load with json format.
    public function data()
    {
        echo json_encode($this->smtp_model->get_data());
    }

    // add data
    public function add()
    {
        $this->data['title'] = "Add SMTP";
        if ($this->input->post()) {
            $rules = [
                [
                    'field' => 'name',
                    'label' => 'Name',
                    'rules' => 'trim|required'
                ],
                [
                    'field' => 'host',
                    'label' => 'Host',
                    'rules' => 'trim|required'
                ],
                [
                    'field' => 'username',
                    'label' => 'Username',
                    'rules' => 'trim|required'
                ],
                [
                    'field' => 'password',
                    'label' => 'Password',
                    'rules' => 'trim|required'
                ],
                [
                    'field' => 'port',
                    'label' => 'Port',
                    'rules' => 'trim|required'
                ],
                [
                    'field' => 'secured_by',
                    'label' => 'Secured By',
                    'rules' => 'trim'
                ],
                [
                    'field' => 'send_limit',
                    'label' => 'Send Limit',
                    'rules' => 'trim'
                ],
            ];

            $this->form_validation->set_rules($rules);

            if ($this->form_validation->run()) {
                $insert = $this->smtp_model->insert_data();
                if ($insert) {
                    $this->session->set_flashdata('notif', 'success');
                    $this->session->set_flashdata('notif_msg', 'Data has saved!');
                } else {
                    $this->session->set_flashdata('notif', 'error');
                    $this->session->set_flashdata('notif_msg', 'Data has not saved!');
                }
                redirect('smtp/add');
            }
        }

        $this->load->view('smtp/form', $this->data);
    }

    // edit data.
    public function edit($id = null)
    {
        if (!isset($id)) {
            redirect('smtp');
        }

        $this->data['title'] = "Edit SMTP";
        $this->data['smtp'] = $this->smtp_model->get_by_id($id);
        $this->data['is_edit'] = true;

        if (!$this->data['smtp']) {
            show_404();
        }

        if ($this->input->post()) {
            $rules = [
                [
                    'field' => 'name',
                    'label' => 'Name',
                    'rules' => 'trim|required'
                ],
                [
                    'field' => 'host',
                    'label' => 'Host',
                    'rules' => 'trim|required'
                ],
                [
                    'field' => 'username',
                    'label' => 'Username',
                    'rules' => 'trim|required'
                ],
                [
                    'field' => 'password',
                    'label' => 'Password',
                    'rules' => 'trim'
                ],
                [
                    'field' => 'port',
                    'label' => 'Port',
                    'rules' => 'trim|required'
                ],
                [
                    'field' => 'secured_by',
                    'label' => 'Secured By',
                    'rules' => 'trim'
                ],
                [
                    'field' => 'send_limit',
                    'label' => 'Send Limit',
                    'rules' => 'trim'
                ],
            ];

            $this->form_validation->set_rules($rules);

            if ($this->form_validation->run()) {
                $update = $this->smtp_model->update_data();
                if ($update) {
                    $this->session->set_flashdata('notif', 'success');
                    $this->session->set_flashdata('notif_msg', 'Data has updated!');
                } else {
                    $this->session->set_flashdata('notif', 'error');
                    $this->session->set_flashdata('notif_msg', 'Data has not updated!');
                }
                redirect('smtp');
            }
        }
        $this->load->view('smtp/form', $this->data);
    }

    // delete data.
    public function delete($id = null)
    {
        if (!isset($id)) {
            redirect('smtp');
        }

        $delete = $this->smtp_model->delete_data($id);

        if ($delete) {
            $this->session->set_flashdata('notif', 'success');
            $this->session->set_flashdata('notif_msg', 'Data has deleted!');
        } else {
            $this->session->set_flashdata('notif', 'error');
            $this->session->set_flashdata('notif_msg', 'Data has not deleted!');
        }

        redirect('smtp');
    }
}
