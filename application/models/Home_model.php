<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Home_model extends CI_Model
{
    private $table = 'contacts'; // table contacts

    public function __construct()
    {
        parent::__construct();
    }

    public function update_contact_to_subscribe($uid)
    {
        if ($this->uid_in_contact($uid, [0,2])) {
            $update = $this->db->update($this->table, ['state'=>1], ['uid'=>$uid]);
            if ($update) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function update_contact_to_unsubscribe($uid)
    {
        if ($this->uid_in_contact($uid, [0,1])) {
            $update = $this->db->update($this->table, ['state'=>2], ['uid'=>$uid]);
            if ($update) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function uid_in_contact($uid, $state=[])
    {
        $total = $this->db->where('uid', $uid)->where_in('state', $state)->from($this->table)->count_all_results();
        if ($total==1) {
            return true;
        } else {
            return false;
        }
    }
}
