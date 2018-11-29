<?php
class Overtime_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }
	
	public function get_overtime($where = array())
    {    
        $this->db->select('overtime.*, employee.name as employee_name');
        $this->db->from('overtime');
		$this->db->join('employee','overtime.emp_id = employee.id');
		$this->db->where($where);
        $query = $this->db->get();
        return $query->result_array();
    }
	
	public function count_overtime($emp_id = 0, $start = '', $end = '')
    {    
        $this->db->select('emp_id');
        $this->db->where(array('emp_id' => $emp_id, 'date >=' => $start, 'date <=' => $end , 'status' => 'active'));
        $query = $this->db->get('overtime');
        return $query->num_rows();
    }

	public function count_overtime_over($emp_id = 0, $start = '', $end = '')
    {    
        $this->db->select('emp_id');
        $this->db->where(array('emp_id' => $emp_id, 'date >=' => $start, 'date <=' => $end , 'status' => 'active','time_go_home >' => '00:00:00' ,'time_go_home <' => '06:00:00'));
        $query = $this->db->get('overtime');
        return $query->num_rows();
    }

	public function insert_overtime($data)
    {   
		$this->db->trans_begin();
			$this->db->insert('overtime', $data);			
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
	
	public function udpate_overtime($id,$data)
    {   
		$this->db->trans_begin();
			$this->db->where(array('id' => $id));
			$this->db->update('overtime', $data);			
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