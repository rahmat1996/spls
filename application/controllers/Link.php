<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Link extends MY_Controller
{
    public $data = ['title'=>'Links','menu'=>'link','is_edit'=>false];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('link_model');
        $this->load->library('form_validation');
        $this->load->helper('form');
    }
    public function index()
    {
        $this->load->view('link/index', $this->data);
    }

    // return all data to load with json format.
    public function data()
    {
        echo json_encode($this->link_model->get_data());
    }

    // add data
    public function add()
    {
        $this->data['title'] = "Add Link";
        if ($this->input->post()) {
            $rules = [
                [
                    'field' => 'name',
                    'label' => 'Name',
                    'rules' => 'trim|required'
                ],
                [
                    'field' => 'url',
                    'label' => 'Url',
                    'rules' => 'trim|valid_url|required'
                ],
                [
                    'field' => 'remarks',
                    'label' => 'Remarks',
                    'rules' => 'trim'
                ],
            ];

            $this->form_validation->set_rules($rules);

            if ($this->form_validation->run()) {
                $insert = $this->link_model->insert_data();
                if ($insert) {
                    $this->session->set_flashdata('notif', 'success');
                    $this->session->set_flashdata('notif_msg', 'Data has saved!');
                } else {
                    $this->session->set_flashdata('notif', 'error');
                    $this->session->set_flashdata('notif_msg', 'Data has not saved!');
                }
                redirect('link/add');
            }
        }

        $this->load->view('link/form', $this->data);
    }

    // edit data.
    public function edit($id = null)
    {
        if (!isset($id)) {
            redirect('link');
        }

        $this->data['title'] = "Edit Link";
        $this->data['link'] = $this->link_model->get_by_id($id);
        $this->data['is_edit'] = true;

        if (!$this->data['link']) {
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
                    'field' => 'url',
                    'label' => 'Url',
                    'rules' => 'trim|valid_url|required'
                ],
                [
                    'field' => 'remarks',
                    'label' => 'Remarks',
                    'rules' => 'trim'
                ],
            ];

            $this->form_validation->set_rules($rules);

            if ($this->form_validation->run()) {
                $update = $this->link_model->update_data();
                if ($update) {
                    $this->session->set_flashdata('notif', 'success');
                    $this->session->set_flashdata('notif_msg', 'Data has updated!');
                } else {
                    $this->session->set_flashdata('notif', 'error');
                    $this->session->set_flashdata('notif_msg', 'Data has not updated!');
                }
                redirect('link');
            }
        }
        $this->load->view('link/form', $this->data);
    }

    // delete data.
    public function delete($id = null)
    {
        if (!isset($id)) {
            redirect('link');
        }

        $delete = $this->link_model->delete_data($id);

        if ($delete) {
            $this->session->set_flashdata('notif', 'success');
            $this->session->set_flashdata('notif_msg', 'Data has deleted!');
        } else {
            $this->session->set_flashdata('notif', 'error');
            $this->session->set_flashdata('notif_msg', 'Data has not deleted!');
        }

        redirect('link');
    }
}
