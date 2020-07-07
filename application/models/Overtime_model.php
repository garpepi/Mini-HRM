<?php
class Overtime_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }
	
	public function get_overtime($where = array(), $order_by=array())
    {    
        $this->db->select('overtime.*, employee.name as employee_name');
        $this->db->from('overtime');
		$this->db->join('employee','overtime.emp_id = employee.id');
		$this->db->where($where);
		if(!empty($order_by))
		{
			$this->db->order_by("id","desc");
		}
        $query = $this->db->get();
        return $query->result_array();
    }
	
	public function json($where = array()) {
        $this->datatables->select('overtime.id, overtime.date as date, overtime.reason as reason, employee.name as employee_name');
        $this->datatables->from('overtime');
        $this->datatables->join('employee', 'overtime.emp_id = employee.id');
		$this->datatables->where($where);
        $this->datatables->add_column('action', '<a href='.base_url()."overtime/edit/$1".' class="btn btn-secondary btn-xs"><i class="fa fa-edit"></i> Edit</a> / <a href='.base_url()."overtime/revoke/$1".' class="btn btn-secondary btn-xs"><i class="fa fa-edit"></i> Revoke</a>', 'id');
        return $this->datatables->generate();
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
	
	public function insert_raw_overtime($data)
    {   
		$this->db->trans_begin();
		$this->db->insert_batch('raw_overtime', $data);			
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
	
	public function insert_single_raw_overtime($data)
    {   
		$this->db->trans_begin();
		$this->db->insert('raw_overtime', $data);			
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
	
	public function get_queue_overtime($where = array(), $order_by=array())
    {    
        $this->db->select('raw_overtime.*, employee.name as employee_name');
        $this->db->from('raw_overtime');
		$this->db->join('employee','raw_overtime.emp_id = employee.id');
		$this->db->where($where);
		if(!empty($order_by))
		{
			$this->db->order_by("id","desc");
		}
        $query = $this->db->get();
        return $query->result_array();
    }
	
	public function json_queuereject($where = array()) {
        $this->datatables->select('raw_overtime.*, employee.name as employee_name');
        $this->datatables->from('raw_overtime');
        $this->datatables->join('employee','raw_overtime.emp_id = employee.id');
		$this->datatables->where($where);
		$this->datatables->order_by("id","desc");
        return $this->datatables->generate();
    }
	
	public function udpate_raw_overtime($id,$data)
    {   
		$this->db->trans_begin();
			$this->db->where(array('id' => $id));
			$this->db->update('raw_overtime', $data);			
		if ($this->db->trans_status() === FALSE)
		{
				$this->db->trans_rollback();
				throw new Exception ('Error on update');
		}
		else
		{
				$this->db->trans_commit();
		}
		return true; 
    }
	
	
}