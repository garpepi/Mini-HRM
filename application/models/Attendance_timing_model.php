<?php
class Attendance_timing_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }
	
	public function get_timing($where = array())
    {    
        $this->db->select('*');
        $this->db->where($where);
        $query = $this->db->get('attendance_timing');
		$data = array();
		foreach ($query->result_array() as $key => $value){
			$data[$value['name']] = array(
						'id' => $value['id'],
						'time' => $value['time']
					);
		}
        return $data;
    }
	
	public function get_show_timing($where = array())
    {    
        $this->db->select('attendance_timing.*, client.name as client_name, projects.name as project_name');
        $this->db->from('attendance_timing');
		$this->db->join('client','client.id = attendance_timing.client_id');
		$this->db->join('projects','projects.id = attendance_timing.project_id');
		$this->db->where($where);
        $query = $this->db->get();
        return $query->result_array();
    }
	
	public function update_timing($id = 0, $data = array())
    {    
		if($id != 0){
			$data['user_m'] = $this->session->userdata('logged_in_data')['id'];
			$this->db->where(array('id' => $id));
			return$this->db->update('attendance_timing',$data);			
		}
    }
	
	public function insert_attendance_timing($data = array())
	{
		$insetData = array(
				array('project_id' => $data['project_id'],'client_id' => $data['client'],'name' => 'comes', 'showed_name'=>'Come In', 'time' => $data['time_in'], 'user_c' => $data['user_c']),
				array('project_id' => $data['project_id'],'client_id' => $data['client'],'name' => 'go_home', 'showed_name'=>'Go Home', 'time' => $data['time_out'], 'user_c' => $data['user_c'])
		);
		return $this->db->insert_batch('attendance_timing',$insetData);
	}

   
}