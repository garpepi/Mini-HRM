<?php
class Leaves_qac_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }
	
	public function get_qac($where = array())
    {    
        $this->db->select('*');
        $this->db->where($where);
        $query = $this->db->get('cuti_last_periodic');
        return $query->result_array();
    }
	
	public function insert_qac_leaves($data = array())
    {    
		if(!empty($data)){
			$data['user_c'] = $this->session->userdata('logged_in_data')['id'];
			return $this->db->insert('cuti_last_periodic',$data);			
		}
    }
   
	public function update_qac_leaves($emp_id, $period, $data)
    {    
		if($emp_id != 0){
			$data['user_m'] = $this->session->userdata('logged_in_data')['id'];
			$this->db->where(array('emp_id' => $emp_id,'period' => $period));
			return $this->db->update('cuti_last_periodic',$data);			
		}
    }
}