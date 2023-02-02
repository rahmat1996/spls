<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Contact_model extends CI_Model
{
    private $table = 'contacts'; // table contacts

    private $array_search = ['email']; // array for search data

    private $array_order = [null,'email',null,null]; // array for order data

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
        $this->db->select('id,email,state');
        $this->db->from($this->table);
        if ($this->input->post('state')) {
            $this->db->where_in('state', $this->input->post('state'));
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
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // inserting a data.
    public function insert_data()
    {
        $post = $this->input->post();

        $data = [
            'uid' => $this->generate_uuid(),
            'email' => $post['email'],
            'state' => $post['state']
        ];

        $this->db->insert($this->table, $data);

        return ($this->db->affected_rows() != 1) ? false : true;
    }

    public function generate_uuid()
    {
        $check = true;
        $uuid = null;
        while ($check) {
            $uuid = $this->uuid->v4(true);
            $this->db->where('uid', $uuid);
            $row = $this->db->get($this->table)->num_rows();
            if (!$row) {
                $check = false;
            }
        }
        return $uuid;
    }

    // updating a data.
    public function update_data()
    {
        $post = $this->input->post();

        $data = [
            'email' => $post['email'],
            'state' => $post['state']
        ];

        $this->db->update($this->table, $data, ['id' => $post['id']]);

        return ($this->db->affected_rows() != 1) ? false : true;
    }

    // get data detail by id.
    public function get_by_id($id)
    {
        return $this->db->select('id,email,state')->get_where($this->table, ['id' => $id])->row();
    }

    // delete.
    public function delete_data($id)
    {
        $this->db->delete($this->table, ['id' => $id]);
        return ($this->db->affected_rows() != 1) ? false : true;
    }
}
