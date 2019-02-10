<?php
class Employee_position_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }
	
	public function get_emp_position($where = array())
    {    
        $this->db->select('*');
        $this->db->where($where);
        $query = $this->db->get('employee_position');
        return $query->result_array();
    }

   
}