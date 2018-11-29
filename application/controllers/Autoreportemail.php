<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    class Autoreportemail extends MY_Controller {
		
		public function __construct(){
			parent::__construct();
			$this->load->model('autoreportemail_model');			
		}
		
		private function front_stuff(){
			$this->data = array(
							'title' => 'Autoreport Email',
							'box_title_1' => 'Autoreport Email',
							'sub_box_title_1' => 'Autoreport Email'
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
							'page/autoreportemail.js'
						);
			$this->contents = 'contents/autoreportemail/index';   // its your view name, change for as per requirement.
			$this->data['contents'] = array(
									'table_active' => $this->autoreportemail_model->get_milist(array('status' => 'active')),
									'table_inactive' => $this->autoreportemail_model->get_milist(array('status' => 'inactive')),
									'data' => array()
								);
		}
		
        		
		public function index() {
			$this->front_stuff();
            $this->layout();
        }
		
		public function add() {
			if ($this->input->server('REQUEST_METHOD') != 'POST'){
				redirect('/autoreportemail');
			}else{
				$data = $this->input->post();
				$this->form_validation->set_rules('email', 'Email', 'required');
				$this->form_validation->set_rules('client', 'Client', 'required');
				
				try{
					if($this->form_validation->run()){
						if(!$this->autoreportemail_model->add_milist($data)){
							throw new Exception('Error on insert');	
						}else{
							$this->session->set_flashdata('form_status', 1);
							$this->session->set_flashdata('form_msg', 'Success on Edit Autoreport Email data');
						}	
					}else{
						throw new Exception(validation_errors());
					}
							
				}catch(Exception $exp){
					$this->session->set_flashdata('form_data', $this->input->post());
					$this->session->set_flashdata('form_status', 0);
					$this->session->set_flashdata('form_msg', $exp->getMessage());
					redirect('/autoreportemail');
				}
				redirect('/autoreportemail');
			}
        }		
		
		public function edit($id=0) {
			$ori_data = $this->autoreportemail_model->get_milist(array('id' => $id))[0];
			if ($this->input->server('REQUEST_METHOD') != 'POST'){
				$this->front_stuff();
				$this->data['contents']['data'] = $ori_data;
				$this->layout();
			}else{
				if($id == 0){
					redirect('/autoreportemail');
				}
				$this->form_validation->set_rules('email', 'Email', 'required');
				$this->form_validation->set_rules('client', 'Client', 'required');
				$data = $this->input->post();
				try{
					if($this->form_validation->run()){						
						if(!$this->autoreportemail_model->update_milist($id, $data)){
							throw new Exception('Error on update');	
						}else{
							$this->session->set_flashdata('form_status', 1);
							$this->session->set_flashdata('form_msg', 'Autoreport Email data');
						}	
					}else{
						throw new Exception(validation_errors());
					}								
				}catch(Exception $exp){
					$this->session->set_flashdata('form_data', $this->input->post());
					$this->session->set_flashdata('form_status', 0);
					$this->session->set_flashdata('form_msg', $exp->getMessage());
					redirect('/autoreportemail/edit/'.$id);
				}
				redirect('/autoreportemail');
			}
        }
		public function revoke($id=0) {
			if($id == 0 && !is_numeric($id) ){
				redirect('/autoreportemail');
			}
			try{
				$data = array('status' => 'inactive');
				if(!$this->autoreportemail_model->update_milist($id, $data)){
					throw new Exception('Error on update');	
				}else{
					$this->session->set_flashdata('form_status', 1);
					$this->session->set_flashdata('form_msg', 'Success on Revoke Autoreport Email');
				}				
			}catch(Exception $exp){
				$this->session->set_flashdata('form_data', $this->input->post());
				$this->session->set_flashdata('form_status', 0);
				$this->session->set_flashdata('form_msg', $exp->getMessage());
				redirect('/autoreportemail');
			}
			redirect('/autoreportemail');
        }
		
		public function reactivate($id=0) {
			if($id == 0 && !is_numeric($id) ){
				redirect('/autoreportemail');
			}
			try{				
				$data = array('status' => 'active');
				if(!$this->autoreportemail_model->update_milist($id, $data)){
					throw new Exception('Error on update');	
				}else{
					$this->session->set_flashdata('form_status', 1);
					$this->session->set_flashdata('form_msg', 'Success on Reactivate Autoreprot Email');
				}
			}catch(Exception $exp){
				$this->session->set_flashdata('form_data', $this->input->post());
				$this->session->set_flashdata('form_status', 0);
				$this->session->set_flashdata('form_msg', $exp->getMessage());
				redirect('/autoreportemail');
			}
			redirect('/autoreportemail');
        }
    }