<?php
class Projects_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }
	
	public function get_projects($where = array())
    {    
        $this->db->select('projects.*, client.name as client_name, client.id as client_id');
		$this->db->from('projects');
		$this->db->join('client', 'client.id = projects.client_id');
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result_array();
    }

	public function insert_div($data = array())
    { 
		$data['user_c'] = $this->session->userdata('logged_in_data')['id'];
        return $this->db->insert('projects',$data);
    }
	
	public function update_div($id = 0, $data = array())
    {    
		$data['user_m'] = $this->session->userdata('logged_in_data')['id'];
        $this->db->where(array('id' => $id));
        return$this->db->update('projects',$data);
    }
}