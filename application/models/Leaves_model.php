<?php
class Leaves_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }
	
	public function get_leaves($where = array())
    {    
        $this->db->select('leaves.*, employee.name as employee_name');
        $this->db->from('leaves');
		$this->db->join('employee','leaves.emp_id = employee.id');
		$this->db->where($where);
        $query = $this->db->get();
        return $query->result_array();
    }
	
	public function count_leaves($emp_id = 0, $start = '', $end = '')
    {    
        $this->db->select('emp_id');
        $this->db->where(array('emp_id' => $emp_id, 'date >=' => $start, 'date <=' => $end ));
        $query = $this->db->get('leaves');
        return $query->num_rows();
    }

	public function insert_leaves($data)
    {   
		if(!empty($this->get_leaves(array('date' => $data['date'] , 'emp_id' => $data['emp_id'], 'leaves.status' => 'active'))))
		{
			throw new Exception ('Data Already Exist on '. $data['date']);
		}
		$this->db->trans_begin();
			$this->db->insert('leaves', $data);			
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
	
	public function udpate_leaves($id,$data)
    {   
		$this->db->trans_begin();
			$this->db->where(array('id' => $id));
			$this->db->update('leaves', $data);			
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