<?php
class Medical_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }
	
	public function get_medical_reimbursement($where = array())
    {    
        $this->db->select('medical_reimbursement.*, employee.name as employee_name');
        $this->db->from('medical_reimbursement');
		$this->db->join('employee','medical_reimbursement.emp_id = employee.id');
		$this->db->where($where);
        $query = $this->db->get();
        return $query->result_array();
    }
	
	public function count_medical_reimbursement($emp_id = 0, $start = '', $end = '')
    {    
        $this->db->select_sum('nominal');
        $this->db->where(array('emp_id' => $emp_id, 'date >=' => $start, 'date <=' => $end ));
        $query = $this->db->get('medical_reimbursement');
        return $query->result_array();
    }

	public function insert_medreimbursment($data)
    {   
		$this->db->trans_begin();
			$this->db->insert('medical_reimbursement', $data);			
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
	
	public function udpate_medreimbursment($id,$data)
    {   
		$this->db->trans_begin();
			$this->db->where(array('id' => $id));
			$this->db->update('medical_reimbursement', $data);			
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