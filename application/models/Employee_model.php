<?php
class Employee_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

	public function get_emp($where = array(),$where_not_in = array(),$string_where = '',$order_by = array())
    {
        $this->db->select('employee.*, cuti.limit as cuti_limit, medical.nominal as medical_limit, employee_status.name as employee_status_name, bank_list.name as bank_name, client.name as clientname, projects.name as projectname');
    		$this->db->from('employee');
    		$this->db->join('cuti', 'cuti.emp_id = employee.id','left');
    		$this->db->join('medical', 'medical.emp_id = employee.id','left');
    		$this->db->join('bank_list', 'bank_list.id = employee.bank_id','left');
			$this->db->join('client', 'client.id = employee.client_id','left');
    		$this->db->join('projects', 'projects.id = employee.project_id','left');
			$this->db->join('employee_status', 'employee_status.id = employee.employee_status','left');
			$this->db->join('employee_position', 'employee_position.id = employee.employee_position','left');
    		$this->db->where(array('medical.status' => 'active','cuti.status' => 'active'));
    		$this->db->where($where);
    		if(!empty($where_not_in)){
    			$this->db->where_not_in($where_not_in['element'], $where_not_in['data']);
    		}
        if(!empty($string_where)){
    			$this->db->or_where($string_where);
    		}
		if(!empty($order_by)){
    			$this->db->order_by($order_by[0], $order_by[1]);
    		}
            $query = $this->db->get();
        return $query->result_array();
    }

    public function get_emp_print($where = array(),$string_where = '')
      {
          $this->db->select('*');
      		$this->db->from('employee');
          if(!empty($string_where)){
      			$this->db->where('('.$string_where.')');
      		}
          $this->db->where($where);
              $query = $this->db->get();
          return $query->result_array();
      }

	public function insert_emp($data)
    {
		$cuti_limit = $data['cuti_limit'];
		$medical_limit = $data['medical_limit'];
		unset($data['cuti_limit']);
		unset($data['medical_limit']);
		if(empty($data['contract_start'])){
			$data['contract_start'] = NULL;
		}
		if(empty($data['contract_end'])){
			$data['contract_end'] = NULL;
		}
		$this->db->trans_begin();
			try{
				$this->db->insert('employee', $data);
				$emp_id = $this->db->insert_id();
				$this->db->insert('cuti', array('emp_id' => $emp_id, 'limit' => $cuti_limit, 'user_c' => $data['user_c']));
				$this->db->insert('medical', array('emp_id' => $emp_id, 'nominal' => $medical_limit,'user_c' => $data['user_c']));
			}catch (Exception $e){
				$this->db->trans_rollback();
				throw new Exception ('Error on insert');
			}

		if ($this->db->trans_status() === FALSE)
		{
				$this->db->trans_rollback();
				throw new Exception ('Error on insert');
		}
		else
		{
				$this->db->trans_commit();
		}
		return $emp_id;
    }

	public function update_emp($data,$id)
    {
		$flag_cuti = 0;
		$flag_medical = 0;
		if(isset($data['cuti_limit'])){
			$cuti_limit = $data['cuti_limit'];
			unset($data['cuti_limit']);
			$flag_cuti = 1;
		}
		if(isset($data['medical_limit'])){
				$medical_limit = $data['medical_limit'];
				unset($data['medical_limit']);
				$flag_medical = 1;
		}
		if(empty($data['contract_start'])){
			$data['contract_start'] = NULL;
		}
		if(empty($data['contract_end'])){
			$data['contract_end'] = NULL;
		}
		//print_r($data);exit();
		$this->db->trans_begin();
			try{

				$this->db->where(array('id' => $id));
				$this->db->update('employee', $data);
				if($flag_cuti == 1){
					$this->db->where(array('emp_id' => $id));
					$this->db->update('cuti', array('emp_id' => $id, 'status' => 'changed', 'user_m' => $data['user_m']));
					$this->db->insert('cuti', array('emp_id' => $id, 'limit' => $cuti_limit, 'status' => 'active', 'user_c' => $data['user_m']));
				}
				if($flag_medical == 1){
					$this->db->where(array('emp_id' => $id));
					$this->db->update('medical', array('emp_id' => $id, 'status' => 'changed', 'user_m' => $data['user_m']));
					$this->db->insert('medical', array('emp_id' => $id, 'nominal' => $medical_limit,'status' => 'active','user_c' => $data['user_m']));
				}
			}catch (Exception $e){
				$this->db->trans_rollback();
				throw new Exception ('Error on Update');
			}

		if ($this->db->trans_status() === FALSE)
		{
				$this->db->trans_rollback();
				throw new Exception ('Error on Update');
		}
		else
		{
				$this->db->trans_commit();
		}
		return $this->db->trans_status();
    }


}
