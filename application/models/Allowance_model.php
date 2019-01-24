<?php
class Allowance_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }
	
	public function get_allowance($where = array())
    {    
        $this->db->select('*');
        $this->db->where($where);
        $query = $this->db->get('allowance');
		$data = array();
		foreach ($query->result_array() as $key => $value){
			$data[$value['name']] = array(
						'id' => $value['id'],
						'client_id' => $value['client_id'],
						'project_id' => $value['project_id'],
						'nominal' => $value['nominal']
					);
		}
        return $data;
    }
	
	public function get_show_allowance($where = array(),$group_by = '',$distinct = '')
    {    
        $this->db->select('allowance.*,client.name as client_name, projects.name as project_name, employee_position.name as employee_position');
		$this->db->from('allowance');
		$this->db->join('client', 'client.id = allowance.client_id');
		$this->db->join('projects', 'projects.id = allowance.project_id');
		$this->db->join('employee_position', 'employee_position.id = allowance.employee_position_id');
        $this->db->where($where);
		if(!empty($group_by))
		{
			$this->db->group_by($group_by);
		}
		if(!empty($distinct))
		{
			$this->db->distinct($distinct);
		}
        $query = $this->db->get();
        return $query->result_array();
    }
	
	public function update_allowance($id = 0, $data = array())
    {    
		if($id != 0){
			$data['user_m'] = $this->session->userdata('logged_in_data')['id'];
			$this->db->where(array('id' => $id));
			return$this->db->update('allowance',$data);			
		}
    }
	
	public function insert_allowance($data = array())
	{
		$insetData = array();
		if(isset($data['meal_allowance']))
		{
			$insetData[] = array('project_id' => $data['project_id'], 'client_id' => $data['client'],'employee_position_id' => $data['employee_position_id'],'name' => 'meal_allowance', 'showed_name'=>'Meal Allowance', 'nominal' => $data['meal_allowance'], 'user_c' => $data['user_c']);
		}
		
		if(isset($data['transport']))
		{
			$insetData[] = array('project_id' => $data['project_id'], 'client_id' => $data['client'],'employee_position_id' => $data['employee_position_id'],'name' => 'transport', 'showed_name'=>'Transport', 'nominal' => $data['transport'], 'user_c' => $data['user_c']);
		}
		
		if(isset($data['internet_laptop']))
		{
			$insetData[] = array('project_id' => $data['project_id'], 'client_id' => $data['client'],'employee_position_id' => $data['employee_position_id'],'name' => 'internet_laptop', 'showed_name'=>'Internet & Laptop', 'nominal' => $data['internet_laptop'], 'user_c' => $data['user_c']);
		}
		
		if(isset($data['overtime']))
		{
			foreach($data['overtime'] as $key => $value)
			{
				$param = $key +1;
				$insetData[] = array('project_id' => $data['project_id'], 'client_id' => $data['client'],'employee_position_id' => $data['employee_position_id'],'name' => 'overtime_'.$param.'h', 'showed_name'=>'Overtime '.$param.' Hour', 'nominal' => $value, 'user_c' => $data['user_c']);
			}
		}
		
		if(isset($data['we_overtime_']))
		{
			foreach($data['we_overtime_'] as $key => $value)
			{
				$param = $key +1;
				$insetData[] = array('project_id' => $data['project_id'], 'client_id' => $data['client'],'employee_position_id' => $data['employee_position_id'],'name' => 'we_overtime_'.$param.'h', 'showed_name'=>'Weekend Overtime '.$param.' Hour', 'nominal' => $value, 'user_c' => $data['user_c']);
			}
		}
		return $this->db->insert_batch('allowance',$insetData);
	}
   
}