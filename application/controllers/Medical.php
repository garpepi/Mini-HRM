<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    class Medical extends MY_Controller {
		
		public function __construct(){
			parent::__construct();
			$this->load->model('employee_model');
			$this->load->model('medical_model');			
		}
		
		private function front_stuff(){
			$this->data = array(
							'title' => 'Medical',
							'box_title_1' => 'Medical',
							'sub_box_title_1' => 'Medical'
						);
			$this->page_css  = array(
							'vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css'
						);
			$this->page_js  = array(
							'vendor/moment/moment.min.js',
							'vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.js',
							'vendor/jquery/jquery.number.min.js'
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
							'page/js/listmedical.js'
						);
            $this->contents = 'medical/lists'; // its your view name, change for as per requirement.
			$this->data['contents'] = array(
							'medical_list' => $this->medical_model->get_medical_reimbursement()
							);
            $this->layout();
        }
		
		public function edit($id) {
			if ($this->input->server('REQUEST_METHOD') != 'POST'){
				$this->front_stuff();
				$this->data['box_title_1'] = 'Edit Medical Reimburstment';
				$this->page_js[] = 'page/js/medical.js';
				$this->contents = 'medical/form'; // its your view name, change for as per requirement.

				$this->data['contents'] = array(
								'medical' => $this->medical_model->get_medical_reimbursement(array('medical_reimbursement.id' => $id))[0]
								);
				$this->layout();
			}else{
				$data = $this->input->post();

				$this->form_validation->set_rules('nominal', 'Nominal', 'required|numeric');
				$this->form_validation->set_rules('emp_id', 'Employee', 'required');
				$this->form_validation->set_rules('date', 'Date', 'required');
								
				if($this->form_validation->run()){				
					try{
						$data['date'] = ($this->input->post('date'))?db_date_only_format($this->input->post('date')):null;
						$data['user_m'] = $this->session->userdata('logged_in_data')['id'];
						$this->medical_model->udpate_medreimbursment($id,$data);
						
					}catch(Exeption $exp){
						$this->session->set_flashdata('form_data', $this->input->post());
						$this->session->set_flashdata('form_status', 0);
						$this->session->set_flashdata('form_msg', $exp);
						redirect('/medical');
					}
					$this->session->set_flashdata('form_status', 1);
					$this->session->set_flashdata('form_msg', 'Success Edit Medical Reimbursment Record');
				}else{
					$this->session->set_flashdata('form_data', $this->input->post());
					$this->session->set_flashdata('form_status', 0);
					if(!$this->form_validation->run()){
						$this->session->set_flashdata('form_msg', validation_errors());
					}else{
						$this->session->set_flashdata('form_msg', 'Data Already Exist');
					}
				}
				redirect('/medical');
			}
        }
		
		public function view($id) {
			$this->front_stuff();
			$this->data['box_title_1'] = 'View Employee';
			$this->page_js[] = 'page/js/medical.js';
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
		
		public function get_reimbursment_record($emp_id,$day=null,$month=null,$year=null) {
			date_default_timezone_set('Etc/GMT+7');
			$employee = $this->employee_model->get_emp(array('employee.id' => $emp_id))[0];
			$med_limit = $employee['medical_limit'];
			$join_date = $employee['join_date'];
			if(empty($day) || empty($month) || empty($year))
			{
				$date_now = date('Y-m-d');				
			}else{
				$date_now = date('Y-m-d',strtotime($year.'-'.$month.'-'.$day));
			}
			$total_used = 0;
			if($employee['employee_status'] == 2){
				//contract counting
				$contract_start = $employee['contract_start'];
				$contract_end = $employee['contract_end'];
				$start = new DateTime($contract_start);
				$end = new DateTime($contract_end);
				$end->modify('+1 day');
				$diff_contract = $start->diff($end); // 1 year should be show 364
				// Change to date 365 = 1 year
				//$month_contract_diff =($diff_contract->format('%y') * 12) + $diff_contract->format('%m');
				// Change to total days/365 round up
				$month_contract_diff = ceil($diff_contract->format("%a")/365*12);
				if($diff_contract->format("%a")+1 < 365){
					$med_limit = $med_limit * $month_contract_diff / 12;
				}
				$total_used = $this->medical_model->count_medical_reimbursement($emp_id, $contract_start, $contract_end)[0]['nominal'];
			}elseif($employee['employee_status'] == 4){
				//permanent QA
				$start = new DateTime(date("Y").'-05-01');
				$end = new DateTime($date_now);
				$end->modify('+1 day');
				$diff_contract = $start->diff($end);
				if($start < $end == false) //start more then = cahnge range forward
				{
					$start = $start->modify('-1 year');
				}
	//			echo $start->format('Y-m-d').'-'.$end->format('Y-m-d').'-';
				//$month_contract_diff =($diff_contract->format('%y') * 12) + $diff_contract->format('%m');
				$month_contract_diff = ceil($diff_contract->format("%a")/365*12);
				$total_used = $this->medical_model->count_medical_reimbursement($emp_id, $start->format('Y-m-d'), $end->format('Y-m-d'))[0]['nominal'];
			}
			else{
				//if probation or permanent
				$start = new DateTime($join_date);
				$end = new DateTime($date_now);
				$end->modify('+1 day');
				if($join_date < $date_now){// join date sebelum hari ini
					//range Before
					$diff = $start->diff($end);
					// Change to date 365 = 1 year
					$month_diff =($diff->format('%y') * 12) + $diff->format('%m');
					if($diff->format("%a")+1 >= 365){// berkerja lebih dari 1 tahun
						if($date_now <= date('Y').'-04-30'){
							// backward period
							$start = date('Y')-1 .'-05-01';// period last year to this year
							$end = date('Y').'-04-30';
						}else{
							//forward period
							$start = date('Y') .'-05-01';// period this year to next year
							$end = date('Y')+1 .'-04-30';
						}
					}else{// berkerja kurang dari 1 tahun
						$diff = $start->diff($end);
						$month_diff =($diff->format('%y') * 12) + $diff->format('%m');
						
						if($diff->format("%a")+1 < 365){
							$med_limit = $med_limit * $month_diff / 12;
						}
						
						if($start < new DateTime(date('Y').'-04-30')){
							// backward period
							$start = $join_date;// period this year to next year
							$end = date('Y').'-04-30';
						}else{
							//forward period
							$start = date('Y') .'-05-01';// period this year to next year
							$end = date('Y')+1 .'-04-30';
						}						
					}
					
				}else{// join date setelah hari ini
					// range after and date same
					$start = date('Y', $join_date).'-05-1';
					$end = date('Y', $join_date)+1 .'04-30';
				}
				$total_used = $this->medical_model->count_medical_reimbursement($emp_id, $start, $end)[0]['nominal'];
			}
			echo ($med_limit - $total_used > 0 ? $med_limit - $total_used : 0) ;
        }
		
		public function add() { // reimbursement
			if ($this->input->server('REQUEST_METHOD') != 'POST'){
				$this->front_stuff();
				$this->data['box_title_1'] = 'Medical Reimbursment';
				$this->page_js[]='page/js/medical.js';
				$this->contents = 'medical/form'; // its your view name, change for as per requirement.
				$this->data['contents'] = array(
								'employee' => $this->employee_model->get_emp(array('employee.status' => 'active'),array(),'',array('name', 'asc'))
								);
				$this->layout();
			}else{
				$this->form_validation->set_rules('nominal', 'Nominal', 'required|numeric');
				$this->form_validation->set_rules('emp_id', 'Employee', 'required');
				$this->form_validation->set_rules('date', 'Date', 'required');
				
				$data = $this->input->post();
				$data['date'] = ($this->input->post('date'))?db_date_only_format($this->input->post('date')):null;
				$data['user_c'] = $this->session->userdata('logged_in_data')['id'];
				
				if($this->form_validation->run()){				
					try{
						$this->medical_model->insert_medreimbursment($data);
					}catch(Exeption $exp){
						$this->session->set_flashdata('form_data', $this->input->post());
						$this->session->set_flashdata('form_status', 0);
						$this->session->set_flashdata('form_msg', $exp);
						redirect('/medical/add');
					}
					$this->session->set_flashdata('form_status', 1);
					$this->session->set_flashdata('form_msg', 'Success Add New Medical Reimburstment Record');
				}else{
					$this->session->set_flashdata('form_data', $this->input->post());
					$this->session->set_flashdata('form_status', 0);
					if(!$this->form_validation->run()){
						$this->session->set_flashdata('form_msg', validation_errors());
					}else{
						$this->session->set_flashdata('form_msg', 'Error on Submit');
					}
				}
				redirect('/medical/add');
			}
        }
		
		public function get_reimbursment_record_new($emp_id,$day=null,$month=null,$year=null) {
			// NO PLORATE
			date_default_timezone_set('Etc/GMT+7');
			$employee = $this->employee_model->get_emp(array('employee.id' => $emp_id))[0];
			$med_limit = $employee['medical_limit'];
			$join_date = $employee['join_date'];
			if(empty($day) || empty($month) || empty($year))
			{
				$date_now = date('Y-m-d');				
			}else{
				$date_now = date('Y-m-d',strtotime($year.'-'.$month.'-'.$day));
			}
			$total_used = 0;
			if($employee['employee_status'] == 2){
				//contract counting
				$contract_start = $employee['contract_start'];
				$contract_end = $employee['contract_end'];
				$start = new DateTime($contract_start);
				$end = new DateTime($contract_end);
				$diff_contract = $start->diff($end); // 1 year should be show 364
				// Change to date 365 = 1 year
				//$month_contract_diff = ceil($diff_contract->format("%a")/365);
				
				$total_used = $this->medical_model->count_medical_reimbursement($emp_id, $contract_start, $contract_end)[0]['nominal'];
				
			}else{
				//permanent QA , permanet, probation
				$start = new DateTime(date("Y").'-05-01');
				$end = new DateTime($date_now);
				$diff_contract = $start->diff($end);
				if($start < $end == false) //start more then = cahnge range forward
				{
					$start = $start->modify('-1 year');
				}
				$total_used = $this->medical_model->count_medical_reimbursement($emp_id, $start->format('Y-m-d'), $end->format('Y-m-d'))[0]['nominal'];
			}
			
			echo ($med_limit - $total_used > 0 ? $med_limit - $total_used : 0) ;
			/*
			return array(
				'used' => $total_used,
				'balance' => ($med_limit - $total_used > 0 ? $med_limit - $total_used : 0),
				'limit' => $med_limit
			);
			*/
        }
		
		public function test(){
			$employee = $this->employee_model->get_emp(array('employee.status' => 'active', 'employee.finger_id !=' => 'NULL', 'employee.project_id !=' => '0' ));
			//$this->stop_fancy_print($employee);		
			
			echo '
			<table border=1>
				<tr>
					<td>
						Employee Id
					</td>
					<td>
						Employee Name
					</td>
					<td>
						Client
					</td>
					<td>
						Project
					</td>
					<td>
						Medical on List
					</td>
					<td>
						Medical used
					</td>
					<td>
						Medical Old Limit
					</td>
					<td>
						Medical Old Balance
					</td>
					<td>
						Medical New Limit
					</td>
					<td>
						Medical New Balance
					</td>				
				</tr>
			';
			
			foreach($employee as $key => $value)
			{
				
				echo '
				<tr>
					<td>
						'.$value['id'].'
					</td>
					<td>
						'.$value['name'].'
					</td>
					<td>
						'.$value['clientname'].'
					</td>
					<td>
						'.$value['projectname'].'
					</td>
					<td>
						'.number_format($value['medical_limit']).'
					</td>
					<td>
						'.number_format($this->get_reimbursment_record($value['id'])['used']).'
					</td>
					<td>
						'.number_format($this->get_reimbursment_record($value['id'])['limit']).'
					</td>
					<td>
						'.number_format($this->get_reimbursment_record($value['id'])['balance']).'
					</td>
					<td>
						'.number_format($this->get_reimbursment_record_new($value['id'])['limit']).'
					</td>
					<td>
						'.number_format($this->get_reimbursment_record_new($value['id'])['balance']).'
					</td>				
				</tr>
			';
				
			}
			
			echo '</table>';
			
		}
		
		
    }