<?php
class Attendance_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

	public function get_attd_period($where = array())
    {
  		$this->load->model('employee_model', 'em');
  		$this->load->model('medical_model', 'mm');

          $this->db->select('attendance_period.*,employee.name');
  		$this->db->from('attendance_period');
		$this->db->join('employee','employee.id = attendance_period.emp_id');
          $this->db->where($where);
          $query = $this->db->get();
  		$data = $query->result_array();

  		foreach($data as $key => $value){
  			$data[$key]['employee_data'] = $this->em->get_emp( array('employee.id' => $value['emp_id']) )[0];
  		//	$data[$key]['medical_reimburstment'] = $this->mm->count_medical_reimbursement( $value['emp_id']  )[0];
  		}
      return $data;
    }

	public function get_attd_period_detail($where = array(),$string_emp = '')
    {
        $this->db->select('*');
		$this->db->from('attendance_report');
		$this->db->join('employee','employee.id = attendance_report.emp_id');
		if($string_emp != ''){
			$this->db->or_where($string_emp);
		}
        $this->db->where($where);
        $query = $this->db->get();
		$data = $query->result_array();

        return $data;
    }

	public function get_attd_detail($where = array())
    {
        $this->db->select('*');
		$this->db->where($where);
        $query = $this->db->get('attendance_detail');
        return $query->result_array();
    }

	public function insert_attd($period = array(), $detail = array()){
		$this->db->trans_begin();
		try{
			$this->db->insert('attendance_period', $period);
			$attd_period_id = $this->db->insert_id();
			foreach($detail as $key => $value){
				$detail[$key]['attd_period_id'] = $attd_period_id;
			}
			$this->db->insert_batch('attendance_detail',$detail);
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
		return $attd_period_id;
	}

	public function update_attendance($period = array(), $detail = array(), $period_id){
		$this->db->trans_begin();

		foreach($detail as $key => $value){
			$id = $value['id'];
			unset($value['id']);
			$this->db->where('id', $id);
			$this->db->update('attendance_detail', $value);
		}

		$this->db->where('id', $period_id);
		$this->db->update('attendance_period', $period);

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			throw new Exception ('Error on insert');
		}
		else
		{
			$this->db->trans_commit();
		}
		return TRUE ;
	}

	public function regenerate_attendance_period($data = array(),$att_id='' )
    {
		$this->db->trans_begin();

		$this->db->insert('attendance_period_history', $data);

		$this->db->where('id', $att_id);
		$this->db->delete('attendance_period');

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			throw new Exception ('Error on generate Report');
		}
		else
		{
			$this->db->trans_commit();
		}
		return TRUE ;
    }

	public function post_attendance_period($period = '', $period_report = array() , $date,$client_id, $project_id)
    {
		$this->db->trans_begin();

		$this->db->where(array('period' => $period, 'client_id' => $client_id, 'project_id' => $project_id));
        $this->db->update('attendance_period', array('status' => 'posted', 'posted_date' =>$date));

		$this->db->insert_batch('attendance_report',$period_report);

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			throw new Exception ('Error on generate Report');
		}
		else
		{
			$this->db->trans_commit();
		}
		return TRUE ;
    }

	public function get_time_emp($emp_id,$start_date,$end_date){

        $this->db->select('attendance_period.emp_id, attendance_detail.*');
		$this->db->from('attendance_detail');
		$this->db->join('attendance_period', 'attendance_period.id = attendance_detail.attd_period_id');
        $this->db->where('attendance_period.emp_id',$emp_id);
		$this->db->where('attendance_detail.date >=',$start_date);
		$this->db->where('attendance_detail.date <=',$end_date);
		$this->db->where('attendance_period.status','posted');
        $query = $this->db->get();
        return $query->result_array();
	}


}
