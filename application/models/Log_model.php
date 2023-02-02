<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Log_model extends CI_Model
{
    private $array_search = ['a.remarks']; // array for search data

    private $array_order = [null,'a.date','b.email','c.name','d.name','a.remarks',null]; // array for order data

    public function __construct()
    {
        parent::__construct();
    }

    // function to get all data
    public function get_data()
    {
        $records = [
            'draw' => @$_POST['draw'],
            'recordsTotal' => $this->count_all(),
            'recordsFiltered' => $this->count_filtered(),
            'data' => $this->_get_data()
        ];

        return $records;
    }

    // function to make a query for get all data.
    private function _get_data_query()
    {
        $this->db->select('a.id as id,a.date as date,b.email as contact,c.name as content,d.name as smtp,a.remarks as remarks,a.state as state');
        $this->db->from('logs a');
        $this->db->join('contacts b', 'b.id=a.contact_id', 'left');
        $this->db->join('links c', 'c.id=a.link_id', 'left');
        $this->db->join('smtps d', 'd.id=a.smtp_id', 'left');
        if ($this->input->post('state')) {
            $this->db->where_in('a.state', $this->input->post('state'));
        }
        // Search from value datatables
        if (@$_POST['search']['value']) {
            $this->db->group_start();
            for ($i = 0; $i < count($this->array_search); $i++) {
                if ($i == 0) {
                    $this->db->like($this->array_search[$i], $_POST['search']['value']);
                } else {
                    $this->db->or_like($this->array_search[$i], $_POST['search']['value']);
                }
            }
            $this->db->group_end();
        }
        // Order from value datatables
        $this->db->order_by($this->array_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    }

    // function getting all data via query.
    private function _get_data()
    {
        $this->_get_data_query();
        if (@$_POST['length'] != -1) {
            $this->db->limit(@$_POST['length'], @$_POST['start']);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    // function to count filtered data from getting data.
    private function count_filtered()
    {
        $this->_get_data_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    // function to get all count data.
    private function count_all()
    {
        $this->db->from('logs a');
        return $this->db->count_all_results();
    }
}
