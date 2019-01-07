<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    class Overtime extends MY_Controller {

		public function __construct(){
			parent::__construct();
			$this->load->library('datatables');
			$this->load->model('employee_model');
			$this->load->model('overtime_model');
		}

		private function front_stuff(){
			$this->data = array(
							'title' => 'Overtime',
							'box_title_1' => 'Overtime',
							'sub_box_title_1' => 'Overtime'
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
			$this->front_stuff();
			$this->page_css  = array(
							'vendor/datatables-plugins/dataTables.bootstrap.css',
							'vendor/datatables-responsive/dataTables.responsive.css'
						);
			$this->page_js  = array(
							'vendor/datatables/js/jquery.dataTables.min.js',
							'vendor/datatables-plugins/dataTables.bootstrap.min.js',
							'vendor/datatables-responsive/dataTables.responsive.js',
							'page/js/listovertime.js'
						);
            $this->contents = 'overtime/lists'; // its your view name, change for as per requirement.
			$this->data['contents'] = array(
							'active_table' => $this->overtime_model->get_overtime(array('overtime.status' => 'active'),array('id','desc')),
							'inactive_table' => $this->overtime_model->get_overtime(array('overtime.status' => 'inactive'),array('id','desc'))
							);
            $this->layout();
        }
		
		public function table($status){
			header('Content-Type: application/json');
			echo $this->overtime_model->json(array('overtime.status' => $status));
		}

		public function revoke($id)
		{
			$data['status'] = 'inactive';
			$this->overtime_model->udpate_overtime($id,$data);
			redirect('/overtime');
		}

		public function reactivate($id)
		{
			$data['status'] = 'active';
			$this->overtime_model->udpate_overtime($id,$data);
			redirect('/overtime');
		}

		public function edit($id) {
			if ($this->input->server('REQUEST_METHOD') != 'POST'){
				$this->front_stuff();
				$this->data['box_title_1'] = 'Edit overtime Record';
				$this->page_js[] = 'page/js/overtime.js';
				$this->contents = 'overtime/form'; // its your view name, change for as per requirement.

				$this->data['contents'] = array(
								'overtime' => $this->overtime_model->get_overtime(array('overtime.id' => $id))[0]
								);
				$this->layout();
			}else{
				$data = $this->input->post();

				$this->form_validation->set_rules('reason', 'Reason', 'required');
				$this->form_validation->set_rules('emp_id', 'Employee', 'required');
				$this->form_validation->set_rules('date', 'Date', 'required');
				if($data['time_go_home'] != NULL || $data['time_go_home'] != '0000-00-00 00:00:00')
				{
					$this->form_validation->set_rules('time_go_home', 'Time Go Home', 'required');					
				}
				else
				{
					$this->form_validation->set_rules('start_in', 'Start Overtime', 'required');
					$this->form_validation->set_rules('end_out', 'End Overtime', 'required');
				}

				if($this->form_validation->run()){
					try{
						if($data['time_go_home'] == '0000-00-00 00:00:00')
						{
							$data['time_go_home'] == NULL;
						}
						$data['date'] = ($this->input->post('date'))?db_date_only_format($this->input->post('date')):null;
						$data['user_m'] = $this->session->userdata('logged_in_data')['id'];
						
						if($data['time_go_home'] != NULL || $data['time_go_home'] != '0000-00-00 00:00:00')
						{
							$time_go_home = DateTime::createFromFormat( 'H:i A', $this->input->post('time_go_home'));
							$data['time_go_home'] = $time_go_home->format( 'H:i:s');
							if($data['date'] > date("Y-m-d"))
							{
								throw new Exception('Date not valid! Greater then now!');
							}
						}
						else
						{
							$data['start_in'] = $start_in->format( 'Y-m-d H:i:s');
							$end_out = DateTime::createFromFormat( 'd/m/Y H:i:s', $this->input->post('end_out'));
							$data['end_out'] = $end_out->format( 'Y-m-d H:i:s');							
							if($data['date'] > date("Y-m-d") || $data['start_in'] > date("Y-m-d H:i:s") || $data['end_out'] > date("Y-m-d H:i:s"))
							{
								throw new Exception('Date, Start In or End Out not valid! Greater then now!');
							}
						}
						
						
						$this->overtime_model->udpate_overtime($id,$data);

					}catch(Exeption $exp){
						$this->session->set_flashdata('form_data', $this->input->post());
						$this->session->set_flashdata('form_status', 0);
						$this->session->set_flashdata('form_msg', $exp);
						redirect('/overtime');
					}
					$this->session->set_flashdata('form_status', 1);
					$this->session->set_flashdata('form_msg', 'Success Edit overtime Reimbursment Record');
				}else{
					$this->session->set_flashdata('form_data', $this->input->post());
					$this->session->set_flashdata('form_status', 0);
					if(!$this->form_validation->run()){
						$this->session->set_flashdata('form_msg', validation_errors());
					}else{
						$this->session->set_flashdata('form_msg', 'Data Already Exist');
					}
				}
				redirect('/overtime');
			}
        }

		public function view($id) {
			$this->front_stuff();
			$this->data['box_title_1'] = 'View Employee';
			$this->page_js[] = 'page/js/overtime.js';
            $this->contents = 'employee/form'; // its your view name, change for as per requirement.
			$this->data['contents'] = array(
							'job' => $this->job_model->get_job(array('status' => 'active')),
							'div' => $this->division_model->get_div(array('status' => 'active')),
							'bank_id' => $this->bank_model->get_bank(array('status' => 'active')),
							'employee_status' => $this->employee_status_model->get_emp_status(array('status' => 'active')),
							'employee' => $this->employee_model->get_emp(array('employee.id' => $id))[0]
							);
            $this->layout();
        }

		public function add() {
			if ($this->input->server('REQUEST_METHOD') != 'POST'){
				$this->front_stuff();
				$this->data['box_title_1'] = 'Add overtime Record';
				$this->page_js[]='page/js/overtime.js';
				$this->contents = 'overtime/form'; // its your view name, change for as per requirement.
				$this->data['contents'] = array(
								'employee' => $this->employee_model->get_emp(array('employee.status' => 'active'))
								);
				$this->layout();
			}else{
				$this->form_validation->set_rules('reason', 'Reason', 'required');
				$this->form_validation->set_rules('emp_id', 'Employee', 'required');
				$this->form_validation->set_rules('date', 'Date', 'required');
				$this->form_validation->set_rules('start_in', 'Start Overtime', 'required');
				$this->form_validation->set_rules('end_out', 'End Overtime', 'required');

				$data = $this->input->post();
				$data['date'] = ($this->input->post('date'))?db_date_only_format($this->input->post('date')):null;
				$data['user_c'] = $this->session->userdata('logged_in_data')['id'];
				$start_in = DateTime::createFromFormat( 'd/m/Y H:i:s', $this->input->post('start_in'));
				$data['start_in'] = $start_in->format( 'Y-m-d H:i:s');
				$end_out = DateTime::createFromFormat( 'd/m/Y H:i:s', $this->input->post('end_out'));
				$data['end_out'] = $end_out->format( 'Y-m-d H:i:s');
				
				if($this->form_validation->run()){
					try{
						if($data['date'] > date("Y-m-d") || $data['start_in'] > date("Y-m-d H:i:s") || $data['end_out'] > date("Y-m-d H:i:s"))
						{
							throw new Exception('Date, Start In or End Out not valid! Greater then now!');
						}
						$this->overtime_model->insert_overtime($data);
					}catch(Exeption $exp){
						$this->session->set_flashdata('form_data', $this->input->post());
						$this->session->set_flashdata('form_status', 0);
						$this->session->set_flashdata('form_msg', $exp);
						redirect('/overtime/add');
					}
					$this->session->set_flashdata('form_status', 1);
					$this->session->set_flashdata('form_msg', 'Success Add New overtime Record');
				}else{
					$this->session->set_flashdata('form_data', $this->input->post());
					$this->session->set_flashdata('form_status', 0);
					if(!$this->form_validation->run()){
						$this->session->set_flashdata('form_msg', validation_errors());
					}else{
						$this->session->set_flashdata('form_msg', 'Error on Submit');
					}
				}
				redirect('/overtime/add');
			}
        }
		
		public function upload() {
			if ($this->input->server('REQUEST_METHOD') != 'POST'){
				$this->front_stuff();
				$this->data['box_title_1'] = 'Upload Overtime';

				$this->page_css  = array(
							);
				$this->page_js  = array(
							);
				$this->contents = 'overtime/upload'; // its your view name, change for as per requirement.
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
						$struk_dat = array();
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
								$sheet = $objPHPExcel->getSheetByName('overtime list');
								if($sheet == NULL){
									throw new Exception('File sheet detected unsure with logic.');
								}
							} catch(Exception $e) {
								return_flash(0,'Error loading file "'.pathinfo($file['file_name'],PATHINFO_BASENAME).'": '.$e->getMessage());
								redirect('overtime/upload');
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
								$raw_data[] = $rowDatas[0];
							}							
							//remove header
							unset($raw_data[0]);
							
							foreach($raw_data as $key => $value)
							{
								
								$date = str_replace("/","-",PHPExcel_Style_NumberFormat::toFormattedString($value[1],'YYYY-MM-DD' ));
								$start_in = str_replace("/","-",PHPExcel_Style_NumberFormat::toFormattedString($value[5],'YYYY-MM-DD HH:i:s'));
								$end_out = str_replace("/","-",PHPExcel_Style_NumberFormat::toFormattedString($value[6],'YYYY-MM-DD HH:i:s'));
								
								// filter and validate the date before add to data structure
								if($this->validateDate($date,'Y-m-d') && $this->validateDate($start_in,'Y-m-d H:i:s') && $this->validateDate($end_out,'Y-m-d H:i:s'))
								{
									$struk_dat[] = array(
										'no' => $value[0],
										'date' => date('Y-m-d', strtotime($date)),
										'emp_id' => $value[2],
										'name' => $value[3],
										'reason' => $value[4],
										'start_in' => date('Y-m-d H:i:s' , strtotime($start_in)),
										'end_out' => date('Y-m-d H:i:s' , strtotime($end_out)),
										'user_c' => $this->session->userdata('logged_in_data')['id'],
										'upload_status' => 'queue',
										'desc_status' => ''
									); 
								}
								else
								{
									$struk_dat[] = array(
										'no' => $value[0],
										'date' => $date,
										'emp_id' => $value[2],
										'name' => $value[3],
										'reason' => $value[4],
										'start_in' => $start_in,
										'end_out' => $end_out,
										'user_c' => $this->session->userdata('logged_in_data')['id'],
										'upload_status' => 'RBS',
										//'desc_status' => 'Date / Start / End not Valid'
										'desc_status' => ''
									);
								}
								
							}
							$this->overtime_model->insert_raw_overtime($struk_dat);
							return_flash(1,'Success Uploading Overtime Data.');
							redirect('overtime/raw_queue');
						} catch(Exception $e) {
							return_flash(0,'Error loading file "'.pathinfo($file['file_name'],PATHINFO_BASENAME).'": '.$e->getMessage());
							redirect('overtime/upload');
						}

				}
			}
        }
		
		public function raw_queue(){
			if ($this->input->server('REQUEST_METHOD') != 'POST'){
				$this->front_stuff();
				$this->data['box_title_1'] = 'Raw Queue';

				$this->page_css  = array(
							'vendor/datatables-plugins/dataTables.bootstrap.css',
							'vendor/datatables-responsive/dataTables.responsive.css'
						);
			$this->page_js  = array(
							'vendor/datatables/js/jquery.dataTables.min.js',
							'vendor/datatables-plugins/dataTables.bootstrap.min.js',
							'vendor/datatables-responsive/dataTables.responsive.js',
							'page/js/rawovertime.js'
						);
						
				$this->contents = 'overtime/raw_queue'; // its your view name, change for as per requirement.
				$this->data['contents'] = array(
									'queue' => $this->overtime_model->get_queue_overtime(array('raw_overtime.upload_status' => 'queue'))
								);
				$this->layout();
			}
			else
			{
				$this->form_validation->set_rules('status[]', 'Status', 'required');
				try{
					if($this->form_validation->run()){
						$data = array();
						// struk_dat
						foreach($this->input->post("status") as $key => $value)
						{
							// key = id & value = status
							$tmp = array();
							$tmp = $this->overtime_model->get_queue_overtime(array("raw_overtime.id" => $key))[0];
							if($value == 'accepted')
							{
								// check to overtime for the record not duplicate
								if(empty($this->overtime_model->get_overtime(array(
									'overtime.date' => $tmp['date'],
									'overtime.emp_id' => $tmp['emp_id'],
									'overtime.start_in' => $tmp['start_in'],
									'overtime.end_out' => $tmp['end_out'])) ) )
									{
										// update raw_queue
										$val['upload_status'] = 'accepted';
										
										// udpate raw_queue
										$this->overtime_model->udpate_raw_overtime($key, $val);
										
										// insert to overtime
										$data = array(
											'date' => $tmp['date'],
											'emp_id' => $tmp['emp_id'],
											'reason' => $tmp['reason'],
											'start_in' => $tmp['start_in'],
											'end_out' => $tmp['end_out'],
											'reason' => $tmp['reason'],
											'user_c' => $this->session->userdata('logged_in_data')['id']
										);
										
										$this->overtime_model->insert_overtime($data);
									}
									else{
										$data['upload_status'] = 'duplicate';
										// udpate raw_queue
										$this->overtime_model->udpate_raw_overtime($key, $data);
									}
							}
							else
							{
								$data['upload_status'] = 'rejected';
								// udpate raw_queue
								$this->overtime_model->udpate_raw_overtime($key, $data);
							}
						
						}
						
						$this->session->set_flashdata('form_status', 1);
						$this->session->set_flashdata('form_msg', 'Success Add New overtime Records');
					}else{
						$this->session->set_flashdata('form_data', $this->input->post());
						$this->session->set_flashdata('form_status', 0);
						if(!$this->form_validation->run()){
							$this->session->set_flashdata('form_msg', validation_errors());
						}else{
							$this->session->set_flashdata('form_msg', 'Error on Submit');
						}
					}
					redirect('/overtime/raw_queue');
				}catch(Exeption $exp){
						$this->session->set_flashdata('form_data', $this->input->post());
						$this->session->set_flashdata('form_status', 0);
						$this->session->set_flashdata('form_msg', $exp);
						redirect('/overtime/raw_queue');
				}
			}
		}
		
		private function validateDate($date, $format = 'm-d-Y')
		{
			$d = DateTime::createFromFormat($format, $date);
			// The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
			return $d && $d->format($format) === $date;
		}
		
		public function queue_reject(){
			header('Content-Type: application/json');
			echo $this->overtime_model->json_queuereject(array('raw_overtime.upload_status != \'accepted\' AND raw_overtime.upload_status !=' => 'queue') );
			//echo $this->db->last_query();
		}

    }
