<?php
class Raw_attendance_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

	public function get_ra($finger_id= 0,$period = '',$client_id = '', $project_id = '')
    {
		$this->load->model('attendance_timing_model');
		$timing = $this->attendance_timing_model->get_timing(array('client_id' => $client_id,'project_id' => $project_id));
		$period_start = $period.'-01';
		$last_date = date('t',strtotime($period_start));
		$result = array();
		$first = '';
		$last = '';
		for($i=1;$i<= $last_date; $i++){
			$period_loop = $period.'-'.sprintf("%02d", $i);
			$this->db->select('*');
			$this->db->where(array('finger_id' => $finger_id, 'date' => $period_loop));
			$this->db->order_by('date ASC', 'tap_time ASC');
			$query = $this->db->get('raw_attendance');
			$return = $query->result_array();

			if(!empty($return)){
				$time = array();
				foreach ($return as $key => $row) {
					if(in_array($row['tap_time'], $time) == 0){
						$time[] = $row['tap_time'];
					}
				}
				$first = reset($time);
				$last = end($time);
				if(count($time) == 1){
					if($first > '05:59' && $first < $timing['comes']['time']){
						$result[$period_loop]['go_home'] = '';
						$result[$period_loop]['come_in'] = $first;
					}elseif($first > $timing['go_home']['time'] && $first < '23:59'){
						$result[$period_loop]['come_in'] = '';
						$result[$period_loop]['go_home'] = $first;
					}else{
						$result[$period_loop]['come_in'] = '';
						$result[$period_loop]['go_home'] = '';
					}
				}else{
					if($first > '05:59' && $first <= $timing['comes']['time']){
						$result[$period_loop]['come_in'] = $first;
					}else{
						$result[$period_loop]['come_in'] = '';
					}

					if($last >= $timing['go_home']['time'] && $last < '23:59'){
						$result[$period_loop]['go_home'] = $last;
					}else{
						$result[$period_loop]['go_home'] = '';
					}

				}
			}else{
				$result[$period_loop]['come_in'] = '';
				$result[$period_loop]['go_home'] = '';
			}
		}
        return $result;
    }

	public function inser_ra($datas = array())
    {
		$this->db->trans_start();
		foreach($datas as $data )
		{
			$this->db->insert('raw_attendance',$data);
			//echo $this->db->last_query().'<br>';
		}
		return $this->db->trans_complete();
        //return $this->db->insert_batch('raw_attendance', $data);
    }


}
