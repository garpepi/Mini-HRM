<?php
class Client_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

	public function get_client($where = array())
    {
        $this->db->select('*');
        $this->db->where($where);
        $query = $this->db->get('client');
        return $query->result_array();
    }
	public function get_client_name($id = '')
    {
        $this->db->select('*');
        $this->db->where(array('id' => $id));
        $query = $this->db->get('client');
        return $query->row_array();
    }
    public function get_div($where = array())
      {
          $this->db->select('*');
          $this->db->where($where);
          $query = $this->db->get('client');
          return $query->result_array();
      }
    public function insert_div($data = array())
      {
		$this->load->model('allowance_model', 'am');
		$this->load->model('attendance_timing_model', 'atm');
		$this->load->model('config_model', 'cm');
		$data['user_c'] = $this->session->userdata('logged_in_data')['id'];
		$this->db->trans_begin();
          if($this->db->insert('client',array('name' => $data['name'], 'user_c'=> $data['user_c'])) ){
			  $data['client'] = $this->db->insert_id();
			  if($this->am->insert_allowance($data)){
				  if($this->atm->insert_attendance_timing($data)){
					  if($this->cm->insert_config_qatracker($data)){
						$this->db->trans_commit();
						return TRUE;					  						  
					  }else{
						$this->db->trans_rollback();
						return FALSE;
					  }
				  }else{
					$this->db->trans_rollback();
					return FALSE;
				  }
			  }else{
				  $this->db->trans_rollback();
				  return FALSE;
			  }
		  }else{
			  $this->db->trans_rollback();
			  return FALSE;
		  }
      }

  	public function update_div($id = 0, $data = array())
      {
  		$data['user_m'] = $this->session->userdata('logged_in_data')['id'];
          $this->db->where(array('id' => $id));
          return$this->db->update('client',$data);
      }


}
