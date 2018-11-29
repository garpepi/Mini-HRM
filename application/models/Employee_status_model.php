<?php
class Employee_status_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }
	
	public function get_emp_status($where = array())
    {    
        $this->db->select('*');
        $this->db->where($where);
        $query = $this->db->get('employee_status');
        return $query->result_array();
    }

   
}