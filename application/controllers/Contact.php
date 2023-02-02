<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Contact extends MY_Controller
{
    public $data = ['title'=>'Contacts','menu'=>'contact','is_edit'=>false];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('contact_model');
        $this->load->library('form_validation');
        $this->load->library('uuid');
        $this->load->helper('form');
    }
    public function index()
    {
        $this->load->view('contact/index', $this->data);
    }

    // return all data to load with json format.
    public function data()
    {
        echo json_encode($this->contact_model->get_data());
    }

    // add data
    public function add()
    {
        $this->data['title'] = "Add Contact";
        if ($this->input->post()) {
            $rules = [
                [
                    'field' => 'email',
                    'label' => 'Email',
                    'rules' => 'trim|valid_email|is_unique[contacts.email]|required'
                ]
            ];

            $this->form_validation->set_rules($rules);

            if ($this->form_validation->run()) {
                $insert = $this->contact_model->insert_data();
                if ($insert) {
                    $this->session->set_flashdata('notif', 'success');
                    $this->session->set_flashdata('notif_msg', 'Data has saved!');
                } else {
                    $this->session->set_flashdata('notif', 'error');
                    $this->session->set_flashdata('notif_msg', 'Data has not saved!');
                }
                redirect('contact/add');
            }
        }

        $this->load->view('contact/form', $this->data);
    }

    // edit data.
    public function edit($id = null)
    {
        if (!isset($id)) {
            redirect('contact');
        }

        $this->data['title'] = "Edit Contact";
        $this->data['contact'] = $this->contact_model->get_by_id($id);
        $this->data['is_edit'] = true;

        if (!$this->data['contact']) {
            show_404();
        }

        if ($this->input->post()) {
            $rules = [
                [
                    'field' => 'email',
                    'label' => 'Email',
                    'rules' => 'trim|valid_email|is_unique[contacts.email]|required'
                ]
            ];

            // check if email same on database, not use rules unique.
            if ($this->input->post('email')) {
                $contact = $this->contact_model->get_by_id($id);
                if ($this->input->post('email') == $contact->email) {
                    $rules[0]['rules'] = 'trim|valid_email|required';
                }
            }

            $this->form_validation->set_rules($rules);

            if ($this->form_validation->run()) {
                $update = $this->contact_model->update_data();
                if ($update) {
                    $this->session->set_flashdata('notif', 'success');
                    $this->session->set_flashdata('notif_msg', 'Data has updated!');
                } else {
                    $this->session->set_flashdata('notif', 'error');
                    $this->session->set_flashdata('notif_msg', 'Data has not updated!');
                }
                redirect('contact');
            }
        }
        $this->load->view('contact/form', $this->data);
    }

    // delete data.
    public function delete($id = null)
    {
        if (!isset($id)) {
            redirect('contact');
        }

        $delete = $this->contact_model->delete_data($id);

        if ($delete) {
            $this->session->set_flashdata('notif', 'success');
            $this->session->set_flashdata('notif_msg', 'Data has deleted!');
        } else {
            $this->session->set_flashdata('notif', 'error');
            $this->session->set_flashdata('notif_msg', 'Data has not deleted!');
        }

        redirect('contact');
    }
}
