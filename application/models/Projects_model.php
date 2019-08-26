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
		$this->load->model('allowance_model', 'am');
		$this->load->model('attendance_timing_model', 'atm');
		$this->load->model('config_model', 'cm');
		$this->load->model('Employee_position_model', 'ep');
		$data['user_c'] = $this->session->userdata('logged_in_data')['id'];
		$employee_position_ids = $this->ep->get_emp_position(array('status'=>'active'));
		$this->db->trans_begin();
          if($this->db->insert('projects',array('name' => $data['name'], 'client_id' => $data['client_id'], 'user_c'=> $data['user_c'])) ){
			  $data['project_id'] = $this->db->insert_id();
			  $data['client'] = $data['client_id'];
			  foreach($employee_position_ids as $employee_position_id)
			  {
				  $data['employee_position_id'] = $employee_position_id['id'];
				  if(!$this->am->insert_allowance($data))
				  {
					$this->db->trans_rollback();
					return FALSE;
					exit();
				  }
			  }
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
    }
	
	public function update_div($id = 0, $data = array())
    {    
		$data['user_m'] = $this->session->userdata('logged_in_data')['id'];
        $this->db->where(array('id' => $id));
        return$this->db->update('projects',$data);
    }
}