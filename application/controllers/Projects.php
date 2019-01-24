<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    class Projects extends MY_Controller {
		
		public function __construct(){
			parent::__construct();
			$this->load->model('projects_model');
			$this->load->model('client_model');
			
		}
		
		private function front_stuff(){
			$this->data = array(
							'title' => 'Projects',
							'box_title_1' => 'Projects',
							'sub_box_title_1' => 'Projects'
						);
			$this->page_css  = array(
							'vendor/datatables-plugins/dataTables.bootstrap.css',
							'vendor/datatables-responsive/dataTables.responsive.css',
							'vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css'
						);
			$this->page_js  = array(
							'vendor/datatables/js/jquery.dataTables.min.js',
							'vendor/datatables-plugins/dataTables.bootstrap.min.js',
							'vendor/datatables-responsive/dataTables.responsive.js',
							'vendor/moment/moment.min.js',
							'vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.js',
							'page/js/projects.js'
						);
			$this->contents = 'contents/projects/index';   // its your view name, change for as per requirement.
			$this->data['contents'] = array(
									'client' => $this->client_model->get_client(array("client.status" => 1)),
									'table_active' => $this->projects_model->get_projects(array('projects.status' => 'active')),
									'table_inactive' => $this->projects_model->get_projects(array('projects.status' => 'inactive')),
									'data' => array()
								);
		}
		
        		
		public function index() {
			$this->front_stuff();
            $this->layout();
        }
		
		public function edit($id=0) {
			$ori_data = $this->projects_model->get_projects(array('projects.id' => $id))[0];
			if ($this->input->server('REQUEST_METHOD') != 'POST'){
				$this->front_stuff();
				$this->data['contents']['data'] = $ori_data;
				$this->layout();
			}else{
				if($id == 0){
					redirect('/projects');
				}
				$this->form_validation->set_rules('name', 'Name', 'required'.($ori_data['name'] != $this->input->post('name') ? '|is_unique[projects.name]' : ''));
				$data = $this->input->post();
				try{
					if($this->form_validation->run()){
						if(!$this->projects_model->update_div($id, $data)){
							throw new Exception('Error on update');	
						}else{
							$this->session->set_flashdata('form_status', 1);
							$this->session->set_flashdata('form_msg', 'Success on Edit projects');
						}
					}else{
						throw new Exception(validation_errors());	
					}
				}catch(Exception $exp){
					$this->session->set_flashdata('form_data', $this->input->post());
					$this->session->set_flashdata('form_status', 0);
					$this->session->set_flashdata('form_msg', $exp->getMessage());
					redirect('/projects/edit/'.$id);
				}
				redirect('/projects');
			}
        }
		
		public function revoke($id=0) {
			if($id == 0 && !is_numeric($id) ){
				redirect('/projects');
			}
			try{
				$count = count($this->projects_model->get_projects(array('projects.status' => 'active')));
				if($count == 1){
					throw new Exception('projects must be Exist minimum 1');
				}
				$data = array('projects.status' => 'inactive');
				if(!$this->projects_model->update_div($id, $data)){
					throw new Exception('Error on update');	
				}else{
					$this->session->set_flashdata('form_status', 1);
					$this->session->set_flashdata('form_msg', 'Success on Revoke projects');
				}				
			}catch(Exception $exp){
				$this->session->set_flashdata('form_data', $this->input->post());
				$this->session->set_flashdata('form_status', 0);
				$this->session->set_flashdata('form_msg', $exp->getMessage());
				redirect('/projects');
			}
			redirect('/projects');
        }
		
		public function reactivate($id=0) {
			if($id == 0 && !is_numeric($id) ){
				redirect('/projects');
			}
			try{				
				$data = array('projects.status' => 'active');
				if(!$this->projects_model->update_div($id, $data)){
					throw new Exception('Error on update');	
				}else{
					$this->session->set_flashdata('form_status', 1);
					$this->session->set_flashdata('form_msg', 'Success on Reactivate projects');
				}
			}catch(Exception $exp){
				$this->session->set_flashdata('form_data', $this->input->post());
				$this->session->set_flashdata('form_status', 0);
				$this->session->set_flashdata('form_msg', $exp->getMessage());
				redirect('/projects');
			}
			redirect('/projects');
        }
		
		public function add() {
			if ($this->input->server('REQUEST_METHOD') == 'POST'){
				
				$this->form_validation->set_rules('client_id', 'Client', 'required');
				$this->form_validation->set_rules('leaves_sub', 'Leaves Substitute for Overtime', 'required');
				$this->form_validation->set_rules('name', 'Name', 'required');
				$this->form_validation->set_rules('meal_allowance', 'Meal Allowance', 'required|numeric');
				$this->form_validation->set_rules('transport', 'Transport', 'required|numeric');
				$this->form_validation->set_rules('internet_laptop', 'Internet + Laptop', 'required|numeric');
				$this->form_validation->set_rules('overtime[]', 'Overtime', 'required');
				$this->form_validation->set_rules('we_overtime_[]', 'Weekend Overtime', 'required');
				
				$data = $this->input->post();

				try{
					if($this->form_validation->run()){	
						if(!$this->projects_model->insert_div($data)){
							throw new Exception('Error on insert');	
						}else{
							$this->session->set_flashdata('form_status', 1);
							$this->session->set_flashdata('form_msg', 'Success on Add new projects');
						}
					}else{
						throw new Exception(validation_errors());	
					}
				}catch(Exception $exp){
					$this->session->set_flashdata('form_data', $this->input->post());
					$this->session->set_flashdata('form_status', 0);
					$this->session->set_flashdata('form_msg', $exp->getMessage());
					
				}				
				redirect('/projects');
			}else{
				redirect('/projects');
			}
        }
		
    }