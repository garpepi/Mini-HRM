<?php
class Settings_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }
	
	public function get_settings($where = array())
    {    
        $this->db->select('*');
        $this->db->where($where);
        $query = $this->db->get('settings');
        return $query->result_array();
    }
	
	public function get_autoAttend()
    {    
        $this->db->select('value');
        $this->db->where(array("name" => "AutoAttend","Status" => "active"));
        $query = $this->db->get('settings');
		
		if(!empty($query->result())){
			foreach ($query->result() as $row)
			{
					return $row->value;
			}
		}else{
			return false;
		}
    }
	
	public function insert_settings($data = array())
    { 
		$data['user_c'] = $this->session->userdata('logged_in_data')['id'];
        return $this->db->insert('settings',$data);
    }
	
	public function update_settings($id = 0, $data = array())
    {    
		$data['user_m'] = $this->session->userdata('logged_in_data')['id'];
        $this->db->where(array('id' => $id));
        return$this->db->update('settings',$data);
    }
   
}