<?php
class Holiday_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }
	
	public function get_holiday($where = array())
    {    
        $this->db->select('*');
        $this->db->where($where);
        $query = $this->db->get('holiday');
        return $query->result_array();
    }
	
	public function insert_holiday($data = array())
    {    
		if(!empty($data)){
			$data['user_c'] = $this->session->userdata('logged_in_data')['id'];
			return $this->db->insert('holiday',$data);			
		}
    }
	
	public function update_holiday($id = 0, $data = array())
    {    
		if($id != 0){
			$data['user_m'] = $this->session->userdata('logged_in_data')['id'];
			$this->db->where(array('id' => $id));
			return $this->db->update('holiday',$data);			
		}
    }
   
}