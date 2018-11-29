<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    class Division extends MY_Controller {
		
		public function __construct(){
			parent::__construct();
			$this->load->model('division_model');
			
		}
		
		private function front_stuff(){
			$this->data = array(
							'title' => 'Division',
							'box_title_1' => 'Division',
							'sub_box_title_1' => 'Division'
						);
			$this->page_css  = array(
							'vendor/datatables-plugins/dataTables.bootstrap.css',
							'vendor/datatables-responsive/dataTables.responsive.css'
						);
			$this->page_js  = array(
							'vendor/datatables/js/jquery.dataTables.min.js',
							'vendor/datatables-plugins/dataTables.bootstrap.min.js',
							'vendor/datatables-responsive/dataTables.responsive.js',
							'page/js/division.js'
						);
			$this->contents = 'contents/division/index';   // its your view name, change for as per requirement.
			$this->data['contents'] = array(
									'table_active' => $this->division_model->get_div(array('status' => 'active')),
									'table_inactive' => $this->division_model->get_div(array('status' => 'inactive')),
									'data' => array()
								);
		}
		
        		
		public function index() {
			$this->front_stuff();
            $this->layout();
        }
		
		public function edit($id=0) {
			$ori_data = $this->division_model->get_div(array('id' => $id))[0];
			if ($this->input->server('REQUEST_METHOD') != 'POST'){
				$this->front_stuff();
				$this->data['contents']['data'] = $ori_data;
				$this->layout();
			}else{
				if($id == 0){
					redirect('/division');
				}
				$this->form_validation->set_rules('name', 'Name', 'required'.($ori_data['name'] != $this->input->post('name') ? '|is_unique[division.name]' : ''));
				$data = $this->input->post();
				try{
					if($this->form_validation->run()){
						if(!$this->division_model->update_div($id, $data)){
							throw new Exception('Error on update');	
						}else{
							$this->session->set_flashdata('form_status', 1);
							$this->session->set_flashdata('form_msg', 'Success on Edit Division');
						}
					}else{
						throw new Exception(validation_errors());	
					}
				}catch(Exception $exp){
					$this->session->set_flashdata('form_data', $this->input->post());
					$this->session->set_flashdata('form_status', 0);
					$this->session->set_flashdata('form_msg', $exp->getMessage());
					redirect('/division/edit/'.$id);
				}
				redirect('/division');
			}
        }
		
		public function revoke($id=0) {
			if($id == 0 && !is_numeric($id) ){
				redirect('/division');
			}
			try{
				$count = count($this->division_model->get_div(array('status' => 'active')));
				if($count == 1){
					throw new Exception('Division must be Exist minimum 1');
				}
				$data = array('status' => 'inactive');
				if(!$this->division_model->update_div($id, $data)){
					throw new Exception('Error on update');	
				}else{
					$this->session->set_flashdata('form_status', 1);
					$this->session->set_flashdata('form_msg', 'Success on Revoke Division');
				}				
			}catch(Exception $exp){
				$this->session->set_flashdata('form_data', $this->input->post());
				$this->session->set_flashdata('form_status', 0);
				$this->session->set_flashdata('form_msg', $exp->getMessage());
				redirect('/division');
			}
			redirect('/division');
        }
		
		public function reactivate($id=0) {
			if($id == 0 && !is_numeric($id) ){
				redirect('/division');
			}
			try{				
				$data = array('status' => 'active');
				if(!$this->division_model->update_div($id, $data)){
					throw new Exception('Error on update');	
				}else{
					$this->session->set_flashdata('form_status', 1);
					$this->session->set_flashdata('form_msg', 'Success on Reactivate Division');
				}
			}catch(Exception $exp){
				$this->session->set_flashdata('form_data', $this->input->post());
				$this->session->set_flashdata('form_status', 0);
				$this->session->set_flashdata('form_msg', $exp->getMessage());
				redirect('/division');
			}
			redirect('/division');
        }
		
		public function add() {
			if ($this->input->server('REQUEST_METHOD') == 'POST'){
				
				$this->form_validation->set_rules('name', 'Name', 'required|is_unique[division.name]');
				
				$data = $this->input->post();
				
				try{
					if($this->form_validation->run()){	
						if(!$this->division_model->insert_div($data)){
							throw new Exception('Error on insert');	
						}else{
							$this->session->set_flashdata('form_status', 1);
							$this->session->set_flashdata('form_msg', 'Success on Add new Division');
						}
					}else{
						throw new Exception(validation_errors());	
					}
				}catch(Exception $exp){
					$this->session->set_flashdata('form_data', $this->input->post());
					$this->session->set_flashdata('form_status', 0);
					$this->session->set_flashdata('form_msg', $exp->getMessage());
					
				}				
				redirect('/division');
			}else{
				redirect('/division');
			}
        }
		
    }