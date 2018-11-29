<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    class Leaves extends MY_Controller {
		
		public function __construct(){
			parent::__construct();
			$this->load->model('employee_model');
			$this->load->model('leaves_model');
			$this->load->model('holiday_model');
		}
		
		private function front_stuff(){
			$this->data = array(
							'title' => 'Leaves',
							'box_title_1' => 'Leaves',
							'sub_box_title_1' => 'Leaves'
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
							'page/js/listleaves.js'
						);
            $this->contents = 'leaves/lists'; // its your view name, change for as per requirement.
			$this->data['contents'] = array(
							'active_table' => $this->leaves_model->get_leaves(array('leaves.status' => 'active')),
							'inactive_table' => $this->leaves_model->get_leaves(array('leaves.status' => 'inactive'))
							);
            $this->layout();
        }
		public function revoke($id)
		{
			$data['status'] = 'inactive';
			$this->leaves_model->udpate_leaves($id,$data);
			redirect('/leaves');
		}
		
		public function reactivate($id)
		{
			$data['status'] = 'active';
			$this->leaves_model->udpate_leaves($id,$data);
			redirect('/leaves');
		}
		
		public function edit($id) {
			if ($this->input->server('REQUEST_METHOD') != 'POST'){
				$this->front_stuff();
				$this->data['box_title_1'] = 'Edit Leaves Record';
				$this->page_js[] = 'page/js/leaves.js';
				$this->contents = 'leaves/form'; // its your view name, change for as per requirement.

				$this->data['contents'] = array(
								'leaves' => $this->leaves_model->get_leaves(array('leaves.id' => $id))[0]
								);
				$this->layout();
			}else{
				$data = $this->input->post();

				$this->form_validation->set_rules('reason', 'Reason', 'required');
				$this->form_validation->set_rules('emp_id', 'Employee', 'required');
				$this->form_validation->set_rules('date', 'Date', 'required');
								
				if($this->form_validation->run()){				
					try{
						$data['date'] = ($this->input->post('date'))?db_date_only_format($this->input->post('date')):null;
						$data['user_m'] = $this->session->userdata('logged_in_data')['id'];
						$this->leaves_model->udpate_leaves($id,$data);
						
					}catch(Exeption $exp){
						$this->session->set_flashdata('form_data', $this->input->post());
						$this->session->set_flashdata('form_status', 0);
						$this->session->set_flashdata('form_msg', $exp);
						redirect('/leaves');
					}
					$this->session->set_flashdata('form_status', 1);
					$this->session->set_flashdata('form_msg', 'Success Edit Leaves Reimbursment Record');
				}else{
					$this->session->set_flashdata('form_data', $this->input->post());
					$this->session->set_flashdata('form_status', 0);
					if(!$this->form_validation->run()){
						$this->session->set_flashdata('form_msg', validation_errors());
					}else{
						$this->session->set_flashdata('form_msg', 'Data Already Exist');
					}
				}
				redirect('/leaves');
			}
        }
		
		public function view($id) {
			$this->front_stuff();
			$this->data['box_title_1'] = 'View Employee';
			$this->page_js[] = 'page/js/leaves.js';
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
				$this->data['box_title_1'] = 'Add Leaves Record';
				$this->page_js[]='page/js/leaves.js';
				$this->contents = 'leaves/form'; // its your view name, change for as per requirement.
				$this->data['contents'] = array(
								'employee' => $this->employee_model->get_emp(array('employee.status' => 'active'))
								);
				$this->layout();
			}else{
				$this->form_validation->set_rules('reason', 'Reason', 'required');
				$this->form_validation->set_rules('emp_id', 'Employee', 'required');
				$this->form_validation->set_rules('date', 'Date', 'required');
				
				$data = $this->input->post();
				$data['date'] = ($this->input->post('date'))?db_date_only_format($this->input->post('date')):null;
				$data['user_c'] = $this->session->userdata('logged_in_data')['id'];
				
				if($this->form_validation->run()){				
					try{
						$this->leaves_model->insert_leaves($data);
					}catch(Exception $exp){
						$this->session->set_flashdata('form_data', $this->input->post());
						$this->session->set_flashdata('form_status', 0);
						$this->session->set_flashdata('form_msg', $exp->getMessage());
						redirect('/leaves/add');
					}
					$this->session->set_flashdata('form_status', 1);
					$this->session->set_flashdata('form_msg', 'Success Add New Leaves Record');
				}else{
					$this->session->set_flashdata('form_data', $this->input->post());
					$this->session->set_flashdata('form_status', 0);
					if(!$this->form_validation->run()){
						$this->session->set_flashdata('form_msg', validation_errors());
					}else{
						$this->session->set_flashdata('form_msg', 'Error on Submit');
					}
				}
				redirect('/leaves/add');
			}
        }
		
		public function multipleadd() {
			if ($this->input->server('REQUEST_METHOD') != 'POST'){
				$this->front_stuff();
				$this->data['box_title_1'] = 'Add Leaves Record';
				$this->page_js[]='page/js/multipleleaves.js';
				$this->contents = 'leaves/multipleform'; // its your view name, change for as per requirement.
				$this->data['contents'] = array(
								'employee' => $this->employee_model->get_emp(array('employee.status' => 'active'))
								);
				$this->layout();
			}else{
				$this->form_validation->set_rules('reason', 'Reason', 'required');
				$this->form_validation->set_rules('emp_id', 'Employee', 'required');
				$this->form_validation->set_rules('date-from', 'Date From', 'required');
				$this->form_validation->set_rules('date-to', 'Date To', 'required');
				
				$data = $this->input->post();
				$data['date-from'] = ($this->input->post('date-from'))?db_date_only_format($this->input->post('date-from')):null;
				$data['date-to'] = ($this->input->post('date-to'))?db_date_only_format($this->input->post('date-to')):null;
				
				if($this->form_validation->run()){				
					try{
						$range_day = $this->createDateRangeArray($data['date-from'],$data['date-to']);
						foreach($range_day as $date)
						{
							//if not sunday or saturday
							if(date('N', strtotime($date)) >= 6)
							{
								continue;
							}
							//if not in holiday
							if(!empty($this->holiday_model->get_holiday(array('date' => $date, 'status' => 'active'))))
							{
								continue;
							}
							
							//if exist on sick table
							if(!empty($this->leaves_model->get_leaves(array('date' => $date , 'emp_id' => $data['emp_id'], 'leaves.status' => 'active'))))
							{
								continue;
							}
							
							$insert = array();
							$insert['date'] = $date;
							$insert['emp_id'] = $data['emp_id'];
							$insert['reason'] = $data['reason'];
							$insert['user_c'] = $this->session->userdata('logged_in_data')['id'];
							
							$this->leaves_model->insert_leaves($insert);
						}
						
					}catch(Exception $exp){
						$this->session->set_flashdata('form_data', $this->input->post());
						$this->session->set_flashdata('form_status', 0);
						$this->session->set_flashdata('form_msg', $exp->getMessage());
						redirect('/leaves/multipleadd');
					}
					$this->session->set_flashdata('form_status', 1);
					$this->session->set_flashdata('form_msg', 'Success Add New Leaves Record');
				}else{
					$this->session->set_flashdata('form_data', $this->input->post());
					$this->session->set_flashdata('form_status', 0);
					if(!$this->form_validation->run()){
						$this->session->set_flashdata('form_msg', validation_errors());
					}else{
						$this->session->set_flashdata('form_msg', 'Error on Submit');
					}
				}
				redirect('/leaves/multipleadd');
			}
        }
		private function createDateRangeArray($strDateFrom,$strDateTo)
		{
			// takes two dates formatted as YYYY-MM-DD and creates an
			// inclusive array of the dates between the from and to dates.

			// could test validity of dates here but I'm already doing
			// that in the main script

			$aryRange=array();

			$iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
			$iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));

			if ($iDateTo>=$iDateFrom)
			{
				array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry
				while ($iDateFrom<$iDateTo)
				{
					$iDateFrom+=86400; // add 24 hours
					array_push($aryRange,date('Y-m-d',$iDateFrom));
				}
			}
			return $aryRange;
		}
		
    }