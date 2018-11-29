<?php
class Bank_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }
	
	public function get_bank($where = array())
    {    
        $this->db->select('*');
        $this->db->where($where);
        $query = $this->db->get('bank_list');
        return $query->result_array();
    }

   
}