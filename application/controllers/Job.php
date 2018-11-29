<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    class Job extends MY_Controller {
		
		public function __construct(){
			parent::__construct();
			$this->load->model('job_model');
			
		}
		
		private function front_stuff(){
			$this->data = array(
							'title' => 'Job',
							'box_title_1' => 'Job',
							'sub_box_title_1' => 'Job'
						);
			$this->page_css  = array(
							'vendor/datatables-plugins/dataTables.bootstrap.css',
							'vendor/datatables-responsive/dataTables.responsive.css'
						);
			$this->page_js  = array(
							'vendor/datatables/js/jquery.dataTables.min.js',
							'vendor/datatables-plugins/dataTables.bootstrap.min.js',
							'vendor/datatables-responsive/dataTables.responsive.js',
							'page/js/job.js'
						);
			$this->contents = 'contents/job/index';   // its your view name, change for as per requirement.
			$this->data['contents'] = array(
									'table_active' => $this->job_model->get_job(array('status' => 'active')),
									'table_inactive' => $this->job_model->get_job(array('status' => 'inactive')),
									'data' => array()
								);
		}
		
        		
		public function index() {
			$this->front_stuff();
            $this->layout();
        }
		
		public function edit($id=0) {
			$ori_data = $this->job_model->get_job(array('id' => $id))[0];
			if ($this->input->server('REQUEST_METHOD') != 'POST'){
				$this->front_stuff();
				$this->data['contents']['data'] = $ori_data;
				$this->layout();
			}else{
				if($id == 0){
					redirect('/job');
				}
				$this->form_validation->set_rules('name', 'Name', 'required'.($ori_data['name'] != $this->input->post('name') ? '|is_unique[job.name]' : ''));
				$data = $this->input->post();
				try{
					if($this->form_validation->run()){
						if(!$this->job_model->update_job($id, $data)){
							throw new Exception('Error on update');	
						}else{
							$this->session->set_flashdata('form_status', 1);
							$this->session->set_flashdata('form_msg', 'Success on Edit Job');
						}
					}else{
						throw new Exception(validation_errors());	
					}
				}catch(Exception $exp){
					$this->session->set_flashdata('form_data', $this->input->post());
					$this->session->set_flashdata('form_status', 0);
					$this->session->set_flashdata('form_msg', $exp->getMessage());
					redirect('/job/edit/'.$id);
				}
				redirect('/job');
			}
        }
		
		public function revoke($id=0) {
			if($id == 0 && !is_numeric($id) ){
				redirect('/job');
			}
			try{
				$count = count($this->job_model->get_job(array('status' => 'active')));
				if($count == 1){
					throw new Exception('Job must be Exist minimum 1');
				}
				$data = array('status' => 'inactive');
				if(!$this->job_model->update_job($id, $data)){
					throw new Exception('Error on update');	
				}else{
					$this->session->set_flashdata('form_status', 1);
					$this->session->set_flashdata('form_msg', 'Success on Revoke Job');
				}				
			}catch(Exception $exp){
				$this->session->set_flashdata('form_data', $this->input->post());
				$this->session->set_flashdata('form_status', 0);
				$this->session->set_flashdata('form_msg', $exp->getMessage());
				redirect('/job');
			}
			redirect('/job');
        }
		
		public function reactivate($id=0) {
			if($id == 0 && !is_numeric($id) ){
				redirect('/job');
			}
			try{				
				$data = array('status' => 'active');
				if(!$this->job_model->update_job($id, $data)){
					throw new Exception('Error on update');	
				}else{
					$this->session->set_flashdata('form_status', 1);
					$this->session->set_flashdata('form_msg', 'Success on Reactivate Job');
				}
			}catch(Exception $exp){
				$this->session->set_flashdata('form_data', $this->input->post());
				$this->session->set_flashdata('form_status', 0);
				$this->session->set_flashdata('form_msg', $exp->getMessage());
				redirect('/job');
			}
			redirect('/job');
        }
		
		public function add() {
			if ($this->input->server('REQUEST_METHOD') == 'POST'){
				
				$this->form_validation->set_rules('name', 'Name', 'required|is_unique[job.name]');
				
				$data = $this->input->post();
				
				try{
					if($this->form_validation->run()){	
						if(!$this->job_model->insert_job($data)){
							throw new Exception('Error on insert');	
						}else{
							$this->session->set_flashdata('form_status', 1);
							$this->session->set_flashdata('form_msg', 'Success on Add new Job');
						}
					}else{
						throw new Exception(validation_errors());	
					}
				}catch(Exception $exp){
					$this->session->set_flashdata('form_data', $this->input->post());
					$this->session->set_flashdata('form_status', 0);
					$this->session->set_flashdata('form_msg', $exp->getMessage());
					
				}				
				redirect('/job');
			}else{
				redirect('/job');
			}
        }
		
    }