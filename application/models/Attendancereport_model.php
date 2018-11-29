<?php
class Attendancereport_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }
	
	public function get_report($start_date,$end_date,$client_id = null)
    {   
		$this->db->select('attendance_report.*');
		$this->db->from('attendance_report');
		if(!is_null($client_id)){
			$this->db->join('employee', 'employee.id = attendance_report.emp_id');
			$this->db->where(array('employee.client_id' => $client_id));
		}
		$this->db->where(array('period >=' => date('Y-m',strtotime($start_date)),'period <' => date('Y-m',strtotime($end_date))));
		$this->db->order_by("period", "dsc");
        $query = $this->db->get();
		$data = $query->result_array();
        return $data;
    }
	
	public function get_report_spesific($emp_id,$period)
    {   
		$this->db->from('attendance_report');
		$this->db->where(array('period' => $period,'emp_id' => $emp_id));
        $query = $this->db->get();
        return $query->row_array();
    }
	
	public function get_in_out_report($where = array())
    {   
		$result = array();
		$this->db->select('attendance_detail.*,employee.name,attendance_period.emp_id');
		$this->db->from('attendance_detail');
		$this->db->join('attendance_period', 'attendance_period.id = attendance_detail.attd_period_id');
		$this->db->join('employee', 'employee.id = attendance_period.emp_id');
		$this->db->where($where);
        $query = $this->db->get();
		$result = $query->result_array();
		return $query->result_array();
    }
   
}