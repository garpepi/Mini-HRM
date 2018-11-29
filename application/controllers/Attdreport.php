<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    class Attdreport extends MY_Controller {

		public function __construct(){
			parent::__construct();
			$this->load->model('attendance_model');
			$this->load->model('raw_attendance_model');
			$this->load->model('employee_model');
			$this->load->model('medical_model');
			$this->load->model('allowance_model');
			$this->load->model('leaves_model');
			$this->load->model('leaves_qac_model');
			$this->load->model('holiday_model');
			$this->load->model('client_model');
		}

		private function front_stuff(){
			$this->data = array(
							'title' => 'Attendance Report',
							'box_title_1' => '',
							'sub_box_title_1' => ''
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
			if ($this->input->server('REQUEST_METHOD') != 'POST'){
				$this->front_stuff();
				$this->data = array(
							'title' => 'Posted Attendance Report',
							'box_title_1' => 'Posted Posting Report',
							'sub_box_title_1' => 'Posted Posting Report'
						);

				$this->page_css 	 = array_merge($this->page_css,  array(
										'vendor/datatables-plugins/dataTables.bootstrap.css',
										'vendor/datatables-responsive/dataTables.responsive.css',
										'vendor/bootstrap-toggle-master/css/bootstrap-toggle.min.css',
										'vendor/select2-4.0.3/dist/css/select2.min.css'
									));
				$this->page_js  	= array_merge($this->page_js,  array(
										'vendor/datatables/js/jquery.dataTables.min.js',
										'vendor/datatables-plugins/dataTables.bootstrap.min.js',
										'vendor/datatables-responsive/dataTables.responsive.js',
										'vendor/bootstrap-toggle-master/js/bootstrap-toggle.min.js',
										'vendor/select2-4.0.3/dist/js/select2.full.min.js',
										'page/js/attendancereport.js'
									));
				$this->contents = 'attdreport/posted'; // its your view name, change for as per requirement.
				$this->data['contents'] = array(
									'employee' => $this->employee_model->get_emp(array('employee.status' => 'active')),
									'attendant_posted' => array(),
									'clients' => $this->client_model->get_client(array('status' => 1)),
									'period' => ''
								);
				$this->layout();
			}else{
				try{
					if(empty($this->input->post())){
						throw new Exception('Error collecting data Input');
					}

					//$this->form_validation->set_rules('employee', 'Employee', 'required');
					$this->form_validation->set_rules('month', 'Month', 'required|numeric');
					$this->form_validation->set_rules('year', 'Year', 'required|numeric');
					$this->form_validation->set_rules('client', 'Client', 'required|numeric');
					if($this->form_validation->run()){
						$period = $this->input->post('year').'-'.$this->input->post('month');
						$client_id = $this->input->post('client');
						$all = 0;
						$selected_employee = '';
						foreach($this->input->post('employee') as $key => $value){
							if($value == 0){
								$all = 1;
							}else{
								if($selected_employee == ''){
									$selected_employee = $selected_employee.'attendance_report.emp_id = '.$value;
								}else{
									$selected_employee = $selected_employee.' OR attendance_report.emp_id = '.$value;
								}
							}
						}
						if($all){
							$attendant_posted = $this->attendance_model->get_attd_period_detail(array('attendance_report.period' => $period, 'employee.client_id' => $client_id));
						}else{
							$attendant_posted = $this->attendance_model->get_attd_period_detail(array('attendance_report.period' => $period, 'employee.client_id' => $client_id),$selected_employee);
						}

						if(empty($attendant_posted)){
							throw new Exception('No data Available to post');
						}
					}else{
						throw new Exception(validation_errors());
					}

					/*
					$this->front_stuff();
					$this->data = array(
								'title' => 'Attendance Report',
								'box_title_1' => 'Posting Report',
								'sub_box_title_1' => 'Posting Report'
							);
					$this->data['box_title_1'] = 'Posting Attendance';

					$this->page_css 	 = array_merge($this->page_css,  array(
											'vendor/datatables-plugins/dataTables.bootstrap.css',
											'vendor/datatables-responsive/dataTables.responsive.css',
											'vendor/datatables.net-buttons-bs/css/buttons.bootstrap.min.css',
											'vendor/bootstrap-toggle-master/css/bootstrap-toggle.min.css',
											'vendor/select2-4.0.3/dist/css/select2.min.css'
										));
					$this->page_js  	= array_merge($this->page_js,  array(
											'vendor/datatables/js/jquery.dataTables.min.js',
											'vendor/datatables-plugins/dataTables.bootstrap.min.js',
											'vendor/datatables-responsive/dataTables.responsive.js',
											'vendor/datatables-button/js/dataTables.buttons.min.js',
											'vendor/datatables-button/js/buttons.bootstrap.min.js',
											//'vendor/datatables-button/js/buttons.flash.min.js',
											'vendor/datatables-button/js/buttons.html5.min.js',
											'vendor/jszip/dist/jszip.min.js',
											'vendor/pdfmake/build/pdfmake.min.js',
											'vendor/pdfmake/build/vfs_fonts.js',
											'vendor/datatables-button/js/buttons.print.min.js',
											'vendor/bootstrap-toggle-master/js/bootstrap-toggle.min.js',
											'vendor/select2-4.0.3/dist/js/select2.full.min.js',
											'page/js/attendancereport.js'
										));
					$this->contents = 'attdreport/posted'; // its your view name, change for as per requirement.

					$this->data['contents'] = array(
										'period' => $period,
										'attendant_posted' => $attendant_posted,
										'employee' => $this->employee_model->get_emp(array('employee.status' => 'active')),
									);
					$this->layout();
					*/
					$exp_data= array();
					$no = 1;
					foreach($attendant_posted as $key=>$value)
					{
						$exp_data[] = array(
						$no++,
						$value['name'],
						number_format($value['leaves_remaining']),
						number_format($value['attend_total']),
						number_format($value['daily_report_total']),
						number_format($value['late_total']),
						number_format($value['overtime_total']),
						number_format($value['overtime_go_home']),
						number_format($value['laptop_internet_total']),
						number_format($value['transport_total']),
						number_format($value['meal_allowance_total']),
						number_format($value['overtime_meal_allowance_total']),
						number_format($value['medical_total']),
						number_format($value['total']),
						$value['bank_account_number']
						);
					}
					$holiday = count($this->holiday_model->get_holiday(array('status' => 'active'  ,'date >=' => $period.'-01','date <=' => $period.'-'.date('t',strtotime($period.'-01')))));
					$workday = $this->countDays($this->input->post('year'),$this->input->post('month')) - $holiday;
					$this->generate_report($exp_data,$period,$workday);
				}catch(Exception $e){
					$this->session->set_flashdata('form_data', $this->input->post());
					return_flash(0,$e->getMessage());
					redirect('attdreport');
				}
			}
        }

		private function countDays($year, $month, $ignore=array(0,6)) {
			$count = 0;
			$counter = mktime(0, 0, 0, $month, 1, $year);
			while (date("n", $counter) == $month) {
				if (in_array(date("w", $counter), $ignore) == false) {
					$count++;
				}
				$counter = strtotime("+1 day", $counter);
			}
			return $count;
		}
		private function generate_report($data_gen,$period,$workday){
			$title[] = array(
						'Posted Attendance Report ('.$period.')'
					);
			$header[] = array(
						'No',
						'Name',
						'Leaves Remaining',
						'Attendance Total',
						'Daily Report Total',
						'Late Total',
						'Overtime Total',
						'Overtime late night',
						'Laptop + Internet Total',
						'Transport Total',
						'Meal Allowance Total',
						'Overtime Meal Allowance Total',
						'Medical',
						'Total',
						'BCA Account'
					);
			$footer = array(
						array('Meal Allowance = Allowance x daily report total'),
						array('Trasport = Allowance x (attend total - late total)'),
						array('Laptop & Internet = Allowance x attendance total'),
						array('Overtime = Allowance x overtime total'),
						array('Total Working Day = '.$workday),
						array('','','','','Mengetahui, ','','','Menyetujui, '),
						array('','','','','','','',''),
						array('','','','','','','',''),
						array('','','','','','','',''),
						array('','','','','Heriawan','','','Junus Susanto'),
					);

			$data = array_merge($title,$header);
			$data = array_merge($data,$data_gen);
			$data = array_merge($data,$footer);
			//echo '<pre>';print_r($data);exit();
			// start Generate Excel
			$this->load->library('excel');
			$this->excel->setActiveSheetIndex(0);
			$this->excel->getActiveSheet()->setTitle($period); // naming sheet


			$filename='Posted Attendance Report '.$period.'.xls'; //save our workbook as this file name
			header('Content-Type: application/vnd.ms-excel'); //mime type
			header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
			header('Cache-Control: max-age=0'); //no cache
		//	$this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "Summary Attendace (".$period.')');
			$this->excel->getActiveSheet()->fromArray(
					$data,  // The data to set
					NULL,        // Array values with this value will not be set
					'A1',         // Top left coordinate of the worksheet range where
					true			 //  print 0
				);
			//make the font become bold
			$this->excel->getActiveSheet()->mergeCells('A1:N1');
			$this->excel->getActiveSheet()->getStyle('A1:N2')->getFont()->setBold(true);
			//set title to center
			$style = array(
					'alignment' => array(
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					)
				);
			$this->excel->getActiveSheet()->getStyle("A1:J1")->applyFromArray($style);
			//Autosize
			for($col = 'B'; $col !== 'N'; $col++) {
				$this->excel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
			}
			//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
			//if you want to save it as .XLSX Excel 2007 format
			$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
			//force user to download the Excel file without writing it to server's HD
			$objWriter->save('php://output');
		}

		public function dopost($period,$client_id) {
			if ($this->input->server('REQUEST_METHOD') == 'POST'){
				try{
					
					$period_report = array();
					$date_post = date('Y-m-d H:i:s');
					$allowance = $this->allowance_model->get_allowance(array('allowance.client_id' => $client_id));
					$attendance_period = $this->attendance_model->get_attd_period(array('attendance_period.period' => $period, 'attendance_period.status' => 'not post', 'attendance_period.client_id' => $client_id));

					foreach($attendance_period as $key => $value){
						$internet_laptop = $allowance['internet_laptop']['nominal'] * $value['attend_total'];
						$transport = $allowance['transport']['nominal'] * ($value['attend_total'] - $value['late_total']);
						$meal_allowance = $allowance['meal_allowance']['nominal'] * $value['daily_report_total'];
						$overtime_meal = $allowance['overtime_meal_allowance']['nominal'] * $value['overtime_total'];
						$overtime_go_home = $allowance['overtime_go_home_allowance']['nominal'] * $value['overtime_go_home'];
						$total = $internet_laptop + $transport + $meal_allowance + $overtime_meal + $overtime_go_home + $value['medical_total'];
						// count leaves_remaining
						$leaves_remaining = 0;
						if($value['employee_data']['employee_status'] != 1){
							$leaves_remaining = $this->leaves_qac_model->get_qac(array('emp_id' => $value['emp_id'],'period' => $period))[0]['leaves_remain'];
						}else{
							$leaves_remaining = $this->leaves_model->count_leaves($value['emp_id'], date('Y').'-05-01', date('Y')+1 .'-04-31');
						}
						$period_report[] = array(
											'emp_id' => $value['emp_id'],
											'name' => $value['employee_data']['name'],
											'period' => $period,
											'leaves_remaining' => $leaves_remaining,
											'sick_total' => $value['sick_total'],
											'leaves_total' => $value['leaves_total'],
											'attend_total' => $value['attend_total'],
											'late_total' => $value['late_total'],
											'overtime_total' => $value['overtime_total'],
											'overtime_go_home' => $value['overtime_go_home'],
											'medical_total' => $value['medical_total'],
											'daily_report_total' => $value['daily_report_total'],
											'laptop_internet_total' => $internet_laptop,
											'transport_total' => $transport,
											'meal_allowance_total' => $meal_allowance,
											'overtime_meal_allowance_total' => $overtime_meal + $overtime_go_home,
											'total' => $total,
											'bank_account_number' => $value['employee_data']['bank_account'],
											'bank_name' => $value['employee_data']['bank_name'],
											'posted_date' => $date_post
										);
					}

					$this->attendance_model->post_attendance_period($period, $period_report, $date_post,$client_id);
				}catch(Exception $e){
					$this->session->set_flashdata('form_data', $this->input->post());
					return_flash(0,$e->getMessage());
					redirect('attdreport/posting');
				}
				return_flash(1,'Success Posting Attendance Data.');
				redirect('attdreport/posting');
			}
        }

		public function posting() {
			if ($this->input->server('REQUEST_METHOD') != 'POST'){
				$this->front_stuff();
				$this->data = array(
							'title' => 'Attendance Report',
							'box_title_1' => 'Posting Report',
							'sub_box_title_1' => 'Posting Report'
						);
				$this->data['box_title_1'] = 'Posting Attendance';

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
										'page/js/attdreportposting.js'
									));
				$this->contents = 'attdreport/posting'; // its your view name, change for as per requirement.
				$this->data['contents'] = array(
									'attendant_period' => array(),
									'clients' => $this->client_model->get_client(array('status' => 1)),
									'allowance' => array()
								);
				$this->layout();
			}else{
				try{

					if(empty($this->input->post())){
						throw new Exception('Error collecting data Input');
					}

					$period = $this->input->post('year').'-'.$this->input->post('month');
					$client_id = $this->input->post('client');
					$attendance_period = $this->attendance_model->get_attd_period(array('attendance_period.period' => $period, 'attendance_period.status' => 'not post', 'attendance_period.client_id' => $client_id));
					//$this->stop_fancy_print($this->db->last_query());
					if(empty($attendance_period)){
						throw new Exception('No data Available to post');
					}

					$this->front_stuff();
					$this->data = array(
								'title' => 'Attendance Report',
								'box_title_1' => 'Posting Report',
								'sub_box_title_1' => 'Posting Report'
							);
					$this->data['box_title_1'] = 'Posting Attendance';

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
											'page/js/attdreportposting.js'
										));
					$this->contents = 'attdreport/posting'; // its your view name, change for as per requirement.
					$this->data['contents'] = array(
										'period' => $period,
										'client_id' => $client_id,
										'client_name' => $this->client_model->get_client_name($client_id),
										'attendant_period' => $attendance_period,
										'allowance' => $this->allowance_model->get_allowance(array('allowance.client_id' => $client_id))
									);
					$this->layout();

				}catch(Exception $e){
					$this->session->set_flashdata('form_data', $this->input->post());
					return_flash(0,$e->getMessage());
					redirect('attdreport/posting');
				}

			}
        }


    }
