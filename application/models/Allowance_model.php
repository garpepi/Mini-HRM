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
						'nominal' => $value['nominal']
					);
		}
        return $data;
    }
	
	public function get_show_allowance($where = array())
    {    
        $this->db->select('allowance.*,client.name as client_name');
		$this->db->from('allowance');
		$this->db->join('client', 'client.id = allowance.client_id');
        $this->db->where($where);
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
		$insetData = array(
				array('client_id' => $data['client'],'name' => 'meal_allowance', 'showed_name'=>'Meal Allowance', 'nominal' => $data['meal_allowance'], 'user_c' => $data['user_c']),
				array('client_id' => $data['client'],'name' => 'transport', 'showed_name'=>'Transport', 'nominal' => $data['transport'], 'user_c' => $data['user_c']),
				array('client_id' => $data['client'],'name' => 'internet_laptop', 'showed_name'=>'Internet & Laptop', 'nominal' => $data['internet_laptop'], 'user_c' => $data['user_c']),
				array('client_id' => $data['client'],'name' => 'overtime_meal_allowance', 'showed_name'=>'Overtime', 'nominal' => $data['overtime_meal_allowance'], 'user_c' => $data['user_c']),
				array('client_id' => $data['client'],'name' => 'overtime_go_home_allowance', 'showed_name'=>'Overtime > 12 AM', 'nominal' => $data['overtime_go_home_allowance'], 'user_c' => $data['user_c']),
		);
		return $this->db->insert_batch('allowance',$insetData);
	}
   
}