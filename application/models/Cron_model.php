<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Cron_model extends CI_Model
{
    private $tbl_config = 'configs';
    private $tbl_smtp = 'smtps';
    private $tbl_contact = 'contacts';
    private $tbl_log = 'logs';
    private $tbl_link = 'links';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_config()
    {
        $this->db->select('smtp_row,contact_row,max_initial_send,max_send_mail_perday,app_name,url_logo,url_subscribe,url_unsubscribe,url_skip_ads');
        $this->db->from($this->tbl_config);
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->row();
    }

    public function get_smtp($position=0)
    {
        $this->db->select('id,name,host,username,password,port,secured_by,send_limit');
        $this->db->from($this->tbl_smtp);
        $this->db->where_in('state', [1]);
        $this->db->limit(1, $position);
        $query = $this->db->get();
        return $query->row();
    }

    public function get_contact($limit=0, $position=0)
    {
        $this->db->select('id,uid,email,state');
        $this->db->from($this->tbl_contact);
        $this->db->where_in('state', [0,1]);
        $this->db->order_by('state', 'DESC');
        $this->db->order_by('id', 'ASC');
        $this->db->limit($limit, $position);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_log_count($contact_id)
    {
        return $this->db->where('contact_id', $contact_id)->from($this->tbl_log)->count_all_results();
    }

    public function update_batch_contact_to_unsubscribe($data)
    {
        return $this->db->update_batch($this->tbl_contact, $data, 'id');
    }

    public function get_count_contact()
    {
        return $this->db->where_in('state', [0,1])->from($this->tbl_contact)->count_all_results();
    }

    public function get_count_smtp()
    {
        return $this->db->where('state', 1)->from($this->tbl_smtp)->count_all_results();
    }

    public function get_link()
    {
        $this->db->select('id,name,url');
        $this->db->from($this->tbl_link);
        $this->db->where_in('state', [1]);
        $query = $this->db->get();
        return $query->result();
    }

    public function insert_batch_log($data)
    {
        return $this->db->insert_batch($this->tbl_log, $data);
    }

    public function update_config($data)
    {
        return $this->db->update($this->tbl_config, $data, ['id'=>1]);
    }
}
