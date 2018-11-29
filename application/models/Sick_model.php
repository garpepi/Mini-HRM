<?php
class Sick_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }
	
	public function get_sick($where = array())
    {    
        $this->db->select('sick.*, employee.name as employee_name');
        $this->db->from('sick');
		$this->db->join('employee','sick.emp_id = employee.id');
		$this->db->where($where);
        $query = $this->db->get();
        return $query->result_array();
    }
	
	public function count_sick($emp_id = 0, $start = '', $end = '')
    {    
        $this->db->select('emp_id');
        $this->db->where(array('emp_id' => $emp_id, 'date >=' => $start, 'date <=' => $end, 'status' => 'active' ));
        $query = $this->db->get('sick');
        return $query->num_rows();
    }

	public function insert_sick($data)
    {   
		if(!empty($this->get_sick(array('date' => $data['date'] , 'emp_id' => $data['emp_id'], 'sick.status' => 'active'))))
		{
			throw new Exception ('Data Already Exist on '. $data['date']);
		}
		$this->db->trans_begin();
			$this->db->insert('sick', $data);			
		if ($this->db->trans_status() === FALSE)
		{
				$this->db->trans_rollback();
				throw new Exception ('Error on insert');
		}
		else
		{
				$this->db->trans_commit();
		}
		return true; 
    }
	
	public function udpate_sick($id,$data)
    {   
		$this->db->trans_begin();
			$this->db->where(array('id' => $id));
			$this->db->update('sick', $data);			
		if ($this->db->trans_status() === FALSE)
		{
				$this->db->trans_rollback();
				throw new Exception ('Error on insert');
		}
		else
		{
				$this->db->trans_commit();
		}
		return true; 
    }
}