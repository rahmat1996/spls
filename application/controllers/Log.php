<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Log extends MY_Controller
{
    public $data = ['title'=>'Logs','menu'=>'log'];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('log_model');
    }
    public function index()
    {
        $this->load->view('log/index', $this->data);
    }

    // return all data to load with json format.
    public function data()
    {
        echo json_encode($this->log_model->get_data());
    }
}
