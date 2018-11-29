<?php
class Users_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function login($email, $password)
    {    
		$password = $password.$this->config->item('mysalt_psw');
		$this->db->select('users.id, employee.email as email, password, employee.name');
		$this->db->from('users');
		$this->db->join('employee', 'employee.id = users.emp_id');
		$this->db->where('employee.email', $email);
		$this->db->where('password', hash("sha256", $password));
		$this->db->where('users.status', 'active');
		$this->db->limit(1);
		$query = $this->db->get();
		
		if($query->num_rows() == 1)
		{
			return $query->result();
		}
		else
		{
			return false;
		}
    }
	
	public function get_users($where = array())
    {    
        $this->db->select('users.*, employee.email as email, employee.name');
		$this->db->from('users');
		$this->db->join('employee', 'employee.id = users.emp_id');
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result_array();
    }
	
	public function get_employee_not_in_users($where = array())
    {    
        $this->db->select('users.*, employee.email as email, employee.name');
		$this->db->from('users');
		$this->db->join('employee', 'employee.id = users.emp_id');
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result_array();
    }
	
	public function insert_user($data = array())
    { 
		$data['user_c'] = $this->session->userdata('logged_in_data')['id'];
        return $this->db->insert('users',$data);
    }
	
	public function update_user($id = 0, $data = array())
    {    
		$data['user_m'] = $this->session->userdata('logged_in_data')['id'];
        $this->db->where(array('id' => $id));
        return$this->db->update('users',$data);
    }

   
}