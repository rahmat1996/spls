<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
    public $data = ['title'=>'Dashboard','menu'=>'dashboard'];

    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $this->load->view('dashboard/index', $this->data);
    }
}
