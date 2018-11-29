<?php
class Job_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }
	
	public function get_job($where = array())
    {    
        $this->db->select('*');
        $this->db->where($where);
        $query = $this->db->get('job');
        return $query->result_array();
    }
	
	public function insert_job($data = array())
    { 
		$data['user_c'] = $this->session->userdata('logged_in_data')['id'];
        return $this->db->insert('job',$data);
    }
	
	public function update_job($id = 0, $data = array())
    {    
		$data['user_m'] = $this->session->userdata('logged_in_data')['id'];
        $this->db->where(array('id' => $id));
        return$this->db->update('job',$data);
    }
   
}