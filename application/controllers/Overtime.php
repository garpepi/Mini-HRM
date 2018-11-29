<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    class Overtime extends MY_Controller {

		public function __construct(){
			parent::__construct();
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
							'active_table' => $this->overtime_model->get_overtime(array('overtime.status' => 'active')),
							'inactive_table' => $this->overtime_model->get_overtime(array('overtime.status' => 'inactive'))
							);
            $this->layout();
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
				$this->form_validation->set_rules('time_go_home', 'Time Go Home', 'required');

				if($this->form_validation->run()){
					try{
						$data['date'] = ($this->input->post('date'))?db_date_only_format($this->input->post('date')):null;
						$data['user_m'] = $this->session->userdata('logged_in_data')['id'];
						$time_go_home = DateTime::createFromFormat( 'H:i A', $this->input->post('time_go_home'));
						$data['time_go_home'] = $time_go_home->format( 'H:i:s');
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
				$this->form_validation->set_rules('time_go_home', 'Time Go Home', 'required');

				$data = $this->input->post();
				$data['date'] = ($this->input->post('date'))?db_date_only_format($this->input->post('date')):null;
				$data['user_c'] = $this->session->userdata('logged_in_data')['id'];
				$time_go_home = DateTime::createFromFormat( 'H:i A', $this->input->post('time_go_home'));
				$data['time_go_home'] = $time_go_home->format( 'H:i:s');

				if($this->form_validation->run()){
					try{
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

    }
