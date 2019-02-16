<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    class Attendance extends MY_Controller {

		public function __construct(){
			parent::__construct();
			$this->load->model('attendance_model');
			$this->load->model('raw_attendance_model');
			$this->load->model('employee_model');
			$this->load->model('api_model');
			$this->load->model('medical_model');
			$this->load->model('leaves_model');
			$this->load->model('sick_model');
			$this->load->model('overtime_model');
			$this->load->model('holiday_model');
			$this->load->model('attendance_timing_model');
			$this->load->model('leaves_qac_model');
			$this->load->model('projects_model');

		}

		private function front_stuff(){
			$this->data = array(
							'title' => 'Attendance',
							'box_title_1' => 'Attendance',
							'sub_box_title_1' => 'Attendance'
						);
			$this->page_css  = array(
							'vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css'
						);
			$this->page_js  = array(
							'vendor/moment/moment.min.js',
							'vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.js'
						);
		}


		public function index() {
			$this->input();
        }

		public function input() {
			if ($this->input->server('REQUEST_METHOD') != 'POST'){
				$this->front_stuff();
				$this->data['box_title_1'] = 'Input Attendance';

				$this->page_css 	 = array_merge($this->page_css,  array(
										'vendor/datatables-plugins/dataTables.bootstrap.css',
										'vendor/datatables-responsive/dataTables.responsive.css',
										'vendor/bootstrap-toggle-master/css/bootstrap-toggle.min.css',
										'page/css/attendanceinput.css'
									));
				$this->page_js  	= array_merge($this->page_js,  array(
										'vendor/datatables/js/jquery.dataTables.min.js',
										'vendor/datatables-plugins/dataTables.bootstrap.min.js',
										'vendor/datatables-responsive/dataTables.responsive.js',
										'vendor/bootstrap-toggle-master/js/bootstrap-toggle.min.js',
										'page/js/attendanceinput.js'
									));
				$this->contents = 'attendance/input'; // its your view name, change for as per requirement.
				$this->data['contents'] = array(
									'attendant_detail' => array(),
									'employee' => $this->employee_model->get_emp(array('employee.status' => 'active', 'employee.employee_status >'  => 1),array(),'',array('employee.name','asc'))
								);
				$this->layout();
			}else{
				$this->form_validation->set_rules('emp_id', 'Employee Name', 'required');
				$this->form_validation->set_rules('month', 'Month', 'required|numeric');
				$this->form_validation->set_rules('year', 'Year', 'required|numeric');
				try{
						if($this->form_validation->run()){
							$employee_data = $this->employee_model->get_emp(array('employee.id' => $this->input->post('emp_id')))[0];
							$employee_id = $employee_data['id'];
							$client_id = $employee_data['client_id'];
							$project_id = $employee_data['project_id'];
							$finger_id = $employee_data['finger_id'];
							$period = $this->input->post('year').'-'.$this->input->post('month');

							$attended_period = $this->attendance_model->get_attd_period(array('attendance_period.emp_id' => $employee_id , 'attendance_period.period' => $period));
							if(empty($attended_period) ){ // data not exist yet

								if($employee_data['employee_status'] != 1 && $employee_data['employee_status'] != 4) // Contract and Probation
								{
									if( !(( date('Y-m', strtotime($period)) >= date('Y-m', strtotime($employee_data['contract_start'])) ) &&  ( date('Y-m', strtotime($period)) <= date('Y-m', strtotime($employee_data['contract_end'])) )) )
									{
										//out of range from contract
										throw new Exception('This Employee\'s Contract has been deprecated or has not been started on this period. Please renew / recheck the contract first!');
									}
								}
								$return_id = $this->input_firsttime($finger_id,$period,$employee_id,$client_id,$project_id);

								redirect('/attendance/edit/'.$return_id);
							}else{

								if($attended_period[0]['status'] == 'posted'){
									redirect('/attendance/view/'.$attended_period[0]['id']);
								}else{
									redirect('/attendance/edit/'.$attended_period[0]['id']);
								}
							}


						}else{
							throw new Exception(validation_errors());
						}
				}catch(Exception $e){
					$this->session->set_flashdata('form_data', $this->input->post());
					return_flash(0,$e->getMessage());
				}
				redirect('attendance/input');
			}

        }

		private function input_firsttime($finger_id,$period,$employee_id,$client_id,$project_id)
		{
			$timing = $this->attendance_timing_model->get_timing(array('attendance_timing.client_id'=> $client_id, 'attendance_timing.project_id'=> $project_id,'attendance_timing.status'=> 'active' ));
			if(empty($timing))
			{
				throw new Exception("Attendance timing not set");
			}
			// Insert data
			// Initialize data
			$detail_data = array();
			$period_data = array();
			$attend_total = 0;
			$day_off_total = 0;
			$late_total = 0;
			$overtime_total = 0;
			$sick_total = 0;
			$daily_report_total = 0;
			$medical_total = 0;
			$holiday = array();
			$leaves_data = array();

			//get raw attendance
			$raw = $this->raw_attendance_model->get_ra($finger_id,$period,$client_id,$project_id);
			//get medical reimbursment
			$medical_reimbursement = $this->medical_model->get_medical_reimbursement(array('emp_id' => $employee_id, 'date >=' => $period.'-01','date <= ' => date('Y-m-t' , strtotime($period.'-01'))));
			//get holiday
			$holiday_raw = $this->holiday_model->get_holiday(array('date >=' => $period.'-01','date <= ' => date('Y-m-t' , strtotime($period.'-01')), 'status' => 'active'));
			
			//get overtime data
			$overtime = $this->overtime_model->get_overtime(array('emp_id' => $employee_id, 'date >=' => $period.'-01','date <= ' => date('Y-m-t' , strtotime($period.'-01')),'overtime.status' => 'active'));

			if(!empty($holiday_raw)){
				foreach($holiday_raw as $key => $value){
					$holiday[] = $value['date'];
				}
			}
			//get overtime (per day)
			$overtime_total = $this->overtime_model->count_overtime($employee_id, $period.'-01', date('Y-m-t' , strtotime($period.'-01') ) );

			//get sick
			$sick_total = $this->sick_model->count_sick($employee_id, $period.'-01', date('Y-m-t' , strtotime($period.'-01') ) );

			//get leaves
			$leaves_data = $this->leaves_model->get_leaves(array('emp_id' => $employee_id, 'date >=' => $period.'-01','date <= ' => date('Y-m-t' , strtotime($period.'-01')), 'leaves.status' => 'active' ));

			//$this->stop_fancy_print($sick_total);
			//hit API daily report data
			/*
			$routeqatracker = $this->employee_model->get_emp(array('employee.id' => $employee_id))[0]['clientname'];
			if($routeqatracker == 'Mandiri'){
				$routeqatracker = '';
			}*/
			
			$daily_report = $this->api_model->get_daily_report($employee_id,$period,$client_id);
			$daily_report_data = array();

			if($daily_report['status_code'] != 200){
				throw new Exception('Error : '.$daily_report['status_code'].' API Fail to get. Pelase Contact Administrator');
			}else{
				if(!empty($daily_report['data'])){
					foreach($daily_report['data'] as $key => $value){
						$daily_report_data[] = date('Y-m-d' , strtotime($value->created_date));
					}
				}
			}

			// setting data to ready to insert to detail

			foreach($raw as $key => $value){
				$attend = 0;
				$day_off = 0;
				$late = 0;
				$daily_report = 0;
				$weekend = 0; // 0 Week daily , 1 Weekend
				$overtimeStat = 0;

				if(date('D', strtotime($key)) == 'Sat' || date('D', strtotime($key)) == 'Sun' || in_array($key, $holiday)){
					$weekend = 1;
				}

				if(!empty($value['come_in']) || !empty($value['go_home'])){
					if($weekend == 1){
						foreach($overtime as $overtimes) {
							if($overtimes['date'] == $key){
								$attend = 1;
								$attend_total++;
								$overtimeStat = 1;
								// tambah cuti jika lembur
							}
						}
					}else{
						if(!empty($value['come_in']) && !empty($value['go_home']) && strtotime($value['come_in']) < strtotime($timing['comes']['time'])){
							$attend = 1;
							$attend_total++;
						}else{
							$attend = 1;
							$attend_total++;
							$late = 1;
							$late_total++;
						}
					}

				}else{
					if($weekend == 0){
						$day_off = 1;
						$day_off_total++;
					}
				}
        // Daily Report Fetching
				if(in_array($key,$daily_report_data) && $attend == 1){
				  if($overtimeStat == 1 || $attend == 1)
				  {
					$daily_report = 1;
					$daily_report_total++;
				  }
				}


				if(!empty($medical_reimbursement)){
					foreach($medical_reimbursement as $med_reimburse){
						if($key == $med_reimburse['date']){
							$medical_total = $medical_total + $med_reimburse['nominal'];
						}
					}
				}

				$detail_data[]= array(
							'date' => $key,
							'arrived' => (!empty($value['come_in']) ? (strtotime($value['come_in']) < strtotime('12:00') ? $value['come_in'] : NULL) : NULL),
							'returns '=> (!empty($value['go_home']) ? $value['go_home'] : NULL),
							'attend' => $attend,
							'leaves' => $day_off,
							'late' => $late,
							'daily_report' => $daily_report,
							'user_c' => $this->session->userdata('logged_in_data')['id']
						);
			}
			//Get employee per this period
			$employees_data = $this->employee_model->get_emp(array('employee.id' => $employee_id))[0];
			$client_id = $employees_data['client_id'];
			$project_id = $employees_data['project_id'];
			$employee_position_id = $employees_data['employee_position'];
			$period_data = array(
						'emp_id' => $employee_id,
						'client_id' => $client_id,
						'project_id' => $project_id,
						'employee_position_id' => $employee_position_id,
						'period' => $period,
						'leaves_total' => $day_off_total,
						'attend_total' => $attend_total,
						'late_total' => $late_total,
						'overtime_total' => $overtime_total,
						'medical_total' => $medical_total,
						'sick_total' => $sick_total,
						'daily_report_total' => $daily_report_total,
						'user_c' => $this->session->userdata('logged_in_data')['id']
					);

		//$this->stop_fancy_print($period_data);
			return $this->attendance_model->insert_attd($period_data,$detail_data);
		}

		public function edit($attend_period_id) {
			if ($this->input->server('REQUEST_METHOD') != 'POST'){
				$this->front_stuff();
				$this->data['box_title_1'] = 'Input Attendance';

				$this->page_css 	 = array_merge($this->page_css,  array(
										'vendor/datatables-plugins/dataTables.bootstrap.css',
										'vendor/datatables-responsive/dataTables.responsive.css',
										'vendor/bootstrap-toggle-master/css/bootstrap-toggle.min.css',
										'page/css/attendanceinput.css'
									));
				$this->page_js  	= array_merge($this->page_js,  array(
										'vendor/datatables/js/jquery.dataTables.min.js',
										'vendor/datatables-plugins/dataTables.bootstrap.min.js',
										'vendor/datatables-responsive/dataTables.responsive.js',
										'vendor/bootstrap-toggle-master/js/bootstrap-toggle.min.js',
										'page/js/attendanceinput.js'
									));
				$attendance_period = $this->attendance_model->get_attd_period(array('attendance_period.id' => $attend_period_id));
				if($attendance_period[0]['status'] == 'posted'){
					redirect('/attendance/view/'.$attended_period[0]['id']);
				}
				$this->contents = 'attendance/input'; // its your view name, change for as per requirement.

				$holiday_raw = $this->holiday_model->get_holiday(array('status' => 'active', 'date >=' => $attendance_period[0]['period'].'-01', 'date <=' => date('Y-m-t', strtotime($attendance_period[0]['period'].'-01')) ));
				$holiday = array();
				if(!empty($holiday_raw)){
					foreach($holiday_raw as $key => $value){
						$holiday[] = $value['date'];
					}
				}
				$this->data['contents'] = array(
									'employee' => $this->employee_model->get_emp(array('employee.status' => 'active', 'employee.employee_status >' => 1),array(),'',array('employee.name','asc')),
									'employee_selected' => $this->employee_model->get_emp(array('employee.id' => $attendance_period[0]['emp_id'],'employee.status' => 'active')),
									'medical' => $this->medical_model->get_medical_reimbursement(array('emp_id' => $attendance_period[0]['emp_id'],'date >=' => $attendance_period[0]['period'].'-01', 'date <=' => date('Y-m-t', strtotime($attendance_period[0]['period'].'-01')) )),
									'overtime' => $this->overtime_model->get_overtime(array('emp_id' => $attendance_period[0]['emp_id'],'date >=' => $attendance_period[0]['period'].'-01', 'date <=' => date('Y-m-t', strtotime($attendance_period[0]['period'].'-01')) ,'overtime.status' => 'active')),
									'sick' => $this->sick_model->get_sick(array('emp_id' => $attendance_period[0]['emp_id'],'date >=' => $attendance_period[0]['period'].'-01', 'date <=' => date('Y-m-t', strtotime($attendance_period[0]['period'].'-01')) ,'sick.status' => 'active')),
									'leaves' => $this->leaves_model->get_leaves(array('emp_id' => $attendance_period[0]['emp_id'],'date >=' => $attendance_period[0]['period'].'-01', 'date <=' => date('Y-m-t', strtotime($attendance_period[0]['period'].'-01')) ,'leaves.status' => 'active')),
									'holiday' => $holiday,
									'attendant_period' => $attendance_period,
									'attendant_detail' => $this->attendance_model->get_attd_detail(array('attd_period_id' => $attend_period_id)),
									'status_view' => 0
								);
				$this->layout();
			}else{
				try{
					$period_data = $this->attendance_model->get_attd_period(array('attendance_period.id' => $attend_period_id));
					$projects = $this->projects_model->get_projects(array("projects.id" => $period_data[0]['project_id'], "projects.status" => "Active"));
					$post_status = $period_data[0]['status'];
					$emp_id = $period_data[0]['emp_id'];
					$period = $period_data[0]['period'];
					if($post_status == 'posted'){
						throw new Exception('Data has been posted');
					}
					if(empty($this->input->post())){
						throw new Exception('Error collecting data Input');
					}

					$input_data = $this->input->post();
					//$this->stop_fancy_print($input_data);
					if(empty($input_data['id']) || empty($input_data['date']) || empty($input_data['arrived']) || empty($input_data['returns']) || empty($input_data['attend']) || empty($input_data['leaves']) || empty($input_data['late']) ){
						throw new Exception('Error on variable data Input');
					}

					$detail_data = array();
					$period_data = array();
					$attend_total = 0;
					$day_off_total = 0;
					$late_total = 0;
					$daily_report_total = 0;
					$overtime_total = $this->overtime_model->count_overtime($emp_id, $period.'-01', date('Y-m-t' , strtotime($period.'-01') ));
					$overtime_go_home = $this->overtime_model->count_overtime_over($emp_id, $period.'-01', date('Y-m-t' , strtotime($period.'-01') ));
					$medical_total = $this->medical_model->count_medical_reimbursement($emp_id, $period.'-01', date('Y-m-t' , strtotime($period.'-01') ))[0]['nominal'];
					$sick_total = $this->sick_model->count_sick($emp_id, $period.'-01', date('Y-m-t' , strtotime($period.'-01') ));


					//echo '<pre>';print_r($input_data);exit();
					foreach($input_data['date'] as $key => $value){
						//this monthdata
						$attend_status = ($input_data['attend'][$key] == 1 && $input_data['leaves'][$key] == 0 && $input_data['sicks'][$key] == 0 ? $input_data['attend'][$key] : 0);
						$leaves_status = ($input_data['attend'][$key] == 0 && $input_data['leaves'][$key] == 1 && $input_data['sicks'][$key] == 0 ? $input_data['leaves'][$key] : 0);
						$attend_total = $attend_total + $attend_status;
						$day_off_total = $day_off_total + $leaves_status ;
						$late_total = $late_total + $input_data['late'][$key];
						//end this monthdata

						if($input_data['attend'][$key] == 1 && $input_data['daily_reportx'][$key] == 1)
						{
							$daily_report_total = $daily_report_total + 1;
						}

						$detail_data[] = array(
										'id' => $input_data['id'][$key],
										'date' => $input_data['date'][$key],
										'arrived' => (empty($input_data['arrived'][$key]) ? NULL : $input_data['arrived'][$key]),
										'returns' => (empty($input_data['returns'][$key]) ? NULL : $input_data['returns'][$key]),
										'attend' => $attend_status,
										'leaves' => $leaves_status,
										'late' => $input_data['late'][$key],
										'user_m' => $this->session->userdata('logged_in_data')['id']
									);
					}
					
					// QA Leaves Calculation START
					// check employee contract ended
					$empt_data = $this->employee_model->get_emp(array('employee.id' => $emp_id,'employee.status' => 'active'));
					
					// GET LAST LEAVES USING QAC
					if($empt_data[0]['employee_status'] != 1 && $empt_data[0]['employee_status'] != 4) // Contract and Probation
					{
						if(( date('Y-m', strtotime($period)) >= date('Y-m', strtotime($empt_data[0]['contract_start'])) ) &&  ( date('Y-m', strtotime($period)) <= date('Y-m', strtotime($empt_data[0]['contract_end'])) ) )
						{
							//in range from contract
							$qac = $this->leaves_qac_model->get_qac(array('period' => date('Y-m', strtotime($period.' -1 months')), 'emp_id' => $emp_id));
						}else{
							//out of range from contract
							throw new Exception('This Employee\'s Contract has been deprecated. Please renew the contract first!');
						}
					}elseif($empt_data[0]['employee_status'] == 4){ // Permanet QA

						// Permanent QA
						if( ( date('Y-m', strtotime($period)) >= date('Y-m', strtotime('-1 year',strtotime(date('Y').'-05-01'))) )  &&  ( date('Y-m', strtotime($period)) <= date('Y-m', strtotime(date('Y').'-05-01')) ) )
						{
							//in range for period counting
							$qac = $this->leaves_qac_model->get_qac(array('period' => date('Y-m', strtotime($period.' -1 months')), 'emp_id' => $emp_id));
						}else{
							//out of range from period counting

							// less then start this year period
							if(date('Y-m', strtotime($period)) < date('Y-m', strtotime('-1 year',strtotime(date('Y').'-05-01'))))
							{
								if(date('Y-m', strtotime($period.' -1 months')) <  date('Y-m', strtotime('-1 year',strtotime(date('Y').'-05-01'))))
								{
									$qac = $this->leaves_qac_model->get_qac(array('period' => date('Y-m', strtotime($period.' -1 months')), 'emp_id' => $emp_id));
								}else{
									throw new Exception('Error On Logical, contact admin! EQAC-1');
								}
							}

							// more then start this year period
							elseif(date('Y-m', strtotime($period)) > date('Y-m', strtotime(date('Y').'-05-01')) )
							{
								if(date('Y-m', strtotime($period)) >  date('Y-m', strtotime(date('Y').'-05-01')) )
								{
									$qac = $this->leaves_qac_model->get_qac(array('period' => date('Y-m', strtotime($period.' -1 months')), 'emp_id' => $emp_id));
								}else{
									throw new Exception('Error On Logical, contact admin! EQAC-2');
								}
							}

						}
					}
						
					
					

					$qac_status = $this->leaves_qac_model->get_qac(array('period' => $period, 'emp_id' => $emp_id));
					$last_leaves = (!empty($qac) ? $qac[0]['leaves_remain'] : 0 );
					$default_leaves = ($empt_data[0]['employee_status'] == 2 ? 1 : ( date('m') == '05' ? 12 : 0)) ;

					if(!empty($qac_status))
					{
						//update
						// Check if leaves_subs on or off
						if($projects[0]['leaves_sub'] == 1) // IF ON USE OLD METHOD
						{
							$leaves_remains = array(
											// OLD Formula
											'leaves_remain' => ($overtime_total-$day_off_total+$default_leaves)+($last_leaves),
											'user_m' => $this->session->userdata('logged_in_data')['id']
										);
						}
						else
						{
							$leaves_remains = array(
											// OLD Formula
											//'leaves_remain' => ($overtime_total-$day_off_total+$default_leaves)+($last_leaves),
											// NEW Formula 12/17/2018
											'leaves_remain' => ($default_leaves - $day_off_total)+($last_leaves),
											'user_m' => $this->session->userdata('logged_in_data')['id']
										);
						}
						$this->leaves_qac_model->update_qac_leaves($emp_id,$period,$leaves_remains);
					}else{
						//insert
						// Check if leaves_subs on or off
						if($projects[0]['leaves_sub'] == 1)// IF ON USE OLD METHOD
						{
							$leaves_remains = array(
											'emp_id' => $emp_id,
											'period' => $period,
											// OLD Formula
											'leaves_remain' => ($overtime_total-$day_off_total+$default_leaves)+($last_leaves)
										);
						}
						else
						{
							$leaves_remains = array(
											'emp_id' => $emp_id,
											'period' => $period,
											// OLD Formula
											//'leaves_remain' => ($overtime_total-$day_off_total+$default_leaves)+($last_leaves)
											// NEW Formula 12/17/2018
											'leaves_remain' => ($default_leaves - $day_off_total)+($last_leaves)
										);							
						}
						$this->leaves_qac_model->insert_qac_leaves($leaves_remains);
					}
					// QA Leaves Calculation END
					$period_data = array(
										'leaves_total' => $day_off_total,
										'attend_total' => $attend_total,
										'late_total' => $late_total,
										'overtime_total' => $overtime_total,
										'overtime_go_home' => $overtime_go_home,
										'sick_total' => $sick_total,
										'medical_total' => $medical_total,
										'daily_report_total' => $daily_report_total,
										'user_m' => $this->session->userdata('logged_in_data')['id']
									);
					if( $this->attendance_model->update_attendance($period_data,$detail_data,$attend_period_id) )
					{
						$this->session->set_flashdata('form_status', 1);
						$this->session->set_flashdata('form_msg', 'Success Edit Attendace Data');
					}else{
						throw new Exception('Error on update data Input');
					}
				}catch(Exception $e){
					$this->session->set_flashdata('form_data', $this->input->post());
					return_flash(0,$e->getMessage());
				}
				if($post_status == 'posted'){
						redirect('attendance/input');
				}else{
					redirect('attendance/edit/'.$attend_period_id);
				}
			}
		}
		public function upload() {
			if ($this->input->server('REQUEST_METHOD') != 'POST'){
				$this->front_stuff();
				$this->data['box_title_1'] = 'Upload Attendance';

				$this->page_css  = array(
							);
				$this->page_js  = array(
							);
				$this->contents = 'attendance/upload'; // its your view name, change for as per requirement.
				$this->data['contents'] = array(

								);
				$this->layout();
			}else{
				$config['upload_path']          = './xlsuploads/';
				$config['allowed_types']        = 'xls|xlsx';
				$config['detect_mime'] 			= TRUE;
				$config['encrypt_name'] 		= TRUE;
				$this->load->library('upload', $config);

				if ( ! $this->upload->do_upload('xlsfile'))
				{
						$error = array('error' => $this->upload->display_errors());
						print_r($this->upload->data());
						print_r($error);
				}
				else
				{
						$file= $this->upload->data();

						// read excel
						$this->load->library('excel');
						//read file from path
						try {
							$objPHPExcel = PHPExcel_IOFactory::load('./xlsuploads/'.$file['file_name']);
						} catch(Exception $e) {
							die('Error loading file "'.pathinfo($file['file_name'],PATHINFO_BASENAME).'": '.$e->getMessage());
						}
						try {
							//get only the Cell Collection
							try {
								//$sheet = $objPHPExcel->getSheet(2);
								$sheet = $objPHPExcel->getSheetByName('Att.log report');
								if($sheet == NULL){
									throw new Exception('File sheet detected unsure with logic.');
								}
							} catch(Exception $e) {
								return_flash(0,'Error loading file "'.pathinfo($file['file_name'],PATHINFO_BASENAME).'": '.$e->getMessage());
								redirect('attendance/upload');
							}

							$highestRow = $sheet->getHighestRow();
							$highestColumn = $sheet->getHighestColumn();
							$rowData = array();
							$absensi = array();
							//  Loop through each row of the worksheet in turn
							for ($row = 1; $row <= $highestRow; $row++){
								//  Read a row of raw_data into an array
								$rowDatas = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
																NULL,
																TRUE,
																FALSE);
								//  Insert row raw_data array into your database of choice here
								$raw_data[] = $rowDatas[0];
							}
							if($raw_data[0][0] != 'Attendance Record Report'){
								 throw new Exception('File sheet detected unsure with logic.');
							}

							// get month
							$period = substr($raw_data[2][2],0,7);
							// Start reading generate by ID
							$finger_id = 0;
							foreach($raw_data as $key => $value){
								if($key >= 4){
									// Get ID and Name
									if($key % 2 == 0){
										$temp = array();
										$finger_id = $value[2];
										$temp['finger_id'] = $value[2];
										$temp['name'] = $value[10];
										$temp['attendence'] = array();
									}else{
										// Breakdown Attendance
										foreach($value as $date => $attendence_data){
											$day = $date +1 ;
											$temp['attendence'][$period.'-'.$day] = $this->strip_time($attendence_data);
										}
										$data[] = $temp;
									}
								}
							}

							if(!$this->insert_raw($data)){
								throw new Exception('Returning FALSE on Insert raw-data.');
							}
							return_flash(1,'Success Uploading Attendance Data.');
							redirect('attendance/upload');
						} catch(Exception $e) {
							return_flash(0,'Error loading file "'.pathinfo($file['file_name'],PATHINFO_BASENAME).'": '.$e->getMessage());
							redirect('attendance/upload');
						}


				}
			}

        }

		private function strip_time($string = ''){
			$data = array();
			if(!empty($string)){
				for($i=0;$i<strlen($string);$i++){
					$data[]=substr($string,$i,5);
					$i += 4;
				}
			}
			return $data;
		}

		private function insert_raw($raw_data = array()){
		//	$this->fancy_print($raw_data);
			if(empty($raw_data)){
				throw new Exception('No data Generated.');
			}

			foreach($raw_data as $fetch){
				if(isset($fetch['attendence'])){
					foreach($fetch['attendence'] as $date => $tap_times){
						if(!empty($tap_times)){
							$temp = array();
							$temp['finger_id'] = $fetch['finger_id'];
							$temp['name'] = $fetch['name'];
							$temp['user_c'] = $this->session->userdata('logged_in_data')['id'];
							foreach($tap_times as $tap_time){
								$temp['date'] = $date;
								$temp['tap_time'] = $tap_time;
								$data[] = $temp;
							}
						}
					}
				}else{
					throw new Exception('Error on Data re-structing.');
				}
			}

			if($this->raw_attendance_model->inser_ra($data) < 0){
				throw new Exception('Error on Insert raw-data.');
			}

			return true;
		}

		public function regenerate($attend_period_id)
		{
			$return_id = '';
			$period_datas = $this->attendance_model->get_attd_period(array('attendance_period.id' => $attend_period_id));
			if(!empty($period_datas))
			{
				$period_data = $period_datas[0];
				if($period_data['status'] == 'posted')
				{
					redirect('attendance/view/'.$period_data['id']);
				}

				unset($period_data['employee_data']);
				unset($period_data['name']);
				$period_data['id_old'] = $period_data['id'];
				unset($period_data['id']);
				$employee_data = $this->employee_model->get_emp(array('employee.id' => $period_data['emp_id']))[0];

				try{
					$this->attendance_model->regenerate_attendance_period($period_data,$period_data['id_old']);
					$return_id = $this->input_firsttime($employee_data['finger_id'],$period_data['period'],$period_data['emp_id'],$employee_data['client_id'],$employee_data['project_id']);
				}catch(Exception $e){
					throw new Exception('Error on Regnerate data.');
				}

				if(!empty($return_id))
				{
					redirect('/attendance/edit/'.$return_id);
				}else{
					redirect('attendance/view/'.$period_data['id']);
				}
			}else{
				throw new Exception('Error No ID Found!.');
			}

		}

		public function view($attend_period_id)
		{
			$this->front_stuff();
				$this->data['box_title_1'] = 'Input Attendance';

				$this->page_css 	 = array_merge($this->page_css,  array(
										'vendor/datatables-plugins/dataTables.bootstrap.css',
										'vendor/datatables-responsive/dataTables.responsive.css',
										'vendor/bootstrap-toggle-master/css/bootstrap-toggle.min.css',
										'page/css/attendanceinput.css'
									));
				$this->page_js  	= array_merge($this->page_js,  array(
										'vendor/datatables/js/jquery.dataTables.min.js',
										'vendor/datatables-plugins/dataTables.bootstrap.min.js',
										'vendor/datatables-responsive/dataTables.responsive.js',
										'vendor/bootstrap-toggle-master/js/bootstrap-toggle.min.js',
										'page/js/attendanceinput.js'
									));
				$attendance_period = $this->attendance_model->get_attd_period(array('attendance_period.id' => $attend_period_id));

				$this->contents = 'attendance/input'; // its your view name, change for as per requirement.

				$holiday_raw = $this->holiday_model->get_holiday(array('status' => 'active', 'date >=' => $attendance_period[0]['period'].'-01', 'date <=' => date('Y-m-t', strtotime($attendance_period[0]['period'].'-01')) ));
				$holiday = array();
				if(!empty($holiday_raw)){
					foreach($holiday_raw as $key => $value){
						$holiday[] = $value['date'];
					}
				}
				$this->data['contents'] = array(
									//'employee' => $this->employee_model->get_emp(array('employee.status' => 'active')),
									'employee' => $this->employee_model->get_emp(array('employee.status' => 'active', 'employee.employee_status >'  => 1),array(),'',array('employee.name','asc')),
									'employee_selected' => $this->employee_model->get_emp(array('employee.id' => $attendance_period[0]['emp_id'],'employee.status' => 'active')),
									'medical' => $this->medical_model->get_medical_reimbursement(array('emp_id' => $attendance_period[0]['emp_id'],'date >=' => $attendance_period[0]['period'].'-01', 'date <=' => date('Y-m-t', strtotime($attendance_period[0]['period'].'-01')) )),
									'overtime' => $this->overtime_model->get_overtime(array('emp_id' => $attendance_period[0]['emp_id'],'date >=' => $attendance_period[0]['period'].'-01', 'date <=' => date('Y-m-t', strtotime($attendance_period[0]['period'].'-01')) ,'overtime.status' => 'active')),
									'sick' => $this->sick_model->get_sick(array('emp_id' => $attendance_period[0]['emp_id'],'date >=' => $attendance_period[0]['period'].'-01', 'date <=' => date('Y-m-t', strtotime($attendance_period[0]['period'].'-01')) ,'sick.status' => 'active')),
									'leaves' => $this->leaves_model->get_leaves(array('emp_id' => $attendance_period[0]['emp_id'],'date >=' => $attendance_period[0]['period'].'-01', 'date <=' => date('Y-m-t', strtotime($attendance_period[0]['period'].'-01')) ,'leaves.status' => 'active')),
									'holiday' => $holiday,
									'attendant_period' => $attendance_period,
									'attendant_detail' => $this->attendance_model->get_attd_detail(array('attd_period_id' => $attend_period_id)),
									'status_view' => 1
								);
				$this->layout();
		}


    }
