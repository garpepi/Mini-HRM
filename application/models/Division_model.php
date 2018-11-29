<?php
class Division_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }
	
	public function get_div($where = array())
    {    
        $this->db->select('*');
        $this->db->where($where);
        $query = $this->db->get('division');
        return $query->result_array();
    }

	public function insert_div($data = array())
    { 
		$data['user_c'] = $this->session->userdata('logged_in_data')['id'];
        return $this->db->insert('division',$data);
    }
	
	public function update_div($id = 0, $data = array())
    {    
		$data['user_m'] = $this->session->userdata('logged_in_data')['id'];
        $this->db->where(array('id' => $id));
        return$this->db->update('division',$data);
    }
}