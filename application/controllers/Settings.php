<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    class Settings extends MY_Controller {
		
		public function __construct(){
			parent::__construct();
			$this->load->model('settings_model');
			
		}
		
		private function front_stuff(){
			$this->data = array(
							'title' => 'Settings',
							'box_title_1' => 'Settings',
							'sub_box_title_1' => 'Settings'
						);
			$this->page_css  = array(
							'vendor/datatables-plugins/dataTables.bootstrap.css',
							'vendor/datatables-responsive/dataTables.responsive.css'
						);
			$this->page_js  = array(
							'vendor/datatables/js/jquery.dataTables.min.js',
							'vendor/datatables-plugins/dataTables.bootstrap.min.js',
							'vendor/datatables-responsive/dataTables.responsive.js',
							'page/js/settings.js'
						);
			$this->contents = 'contents/settings/index';   // its your view name, change for as per requirement.
			$this->data['contents'] = array(
									'table_active' => $this->settings_model->get_settings(array('status' => 'active')),
									'table_inactive' => $this->settings_model->get_settings(array('status' => 'inactive')),
									'data' => array()
								);
		}
		
        		
		public function index() {
			$this->front_stuff();
            $this->layout();
        }
		
		public function edit($id=0) {
			$ori_data = $this->settings_model->get_settings(array('id' => $id))[0];
			if ($this->input->server('REQUEST_METHOD') != 'POST'){
				$this->front_stuff();
				$this->data['contents']['data'] = $ori_data;
				$this->layout();
			}else{
				if($id == 0){
					redirect('/settings');
				}
				$this->form_validation->set_rules('name', 'Name', 'required'.($ori_data['name'] != $this->input->post('name') ? '|is_unique[settings.name]' : ''));
				$data = $this->input->post();
				try{
					if($this->form_validation->run()){
						if(!$this->settings_model->update_Settings($id, $data)){
							throw new Exception('Error on update');	
						}else{
							$this->session->set_flashdata('form_status', 1);
							$this->session->set_flashdata('form_msg', 'Success on Edit Settings');
						}
					}else{
						throw new Exception(validation_errors());	
					}
				}catch(Exception $exp){
					$this->session->set_flashdata('form_data', $this->input->post());
					$this->session->set_flashdata('form_status', 0);
					$this->session->set_flashdata('form_msg', $exp->getMessage());
					redirect('/settings/edit/'.$id);
				}
				redirect('/settings');
			}
        }
		
		public function revoke($id=0) {
			if($id == 0 && !is_numeric($id) ){
				redirect('/settings');
			}
			try{
				$count = count($this->settings_model->get_settings(array('status' => 'active')));
				if($count == 1){
					throw new Exception('Settings must be Exist minimum 1');
				}
				$data = array('status' => 'inactive');
				if(!$this->settings_model->update_Settings($id, $data)){
					throw new Exception('Error on update');	
				}else{
					$this->session->set_flashdata('form_status', 1);
					$this->session->set_flashdata('form_msg', 'Success on Revoke Settings');
				}				
			}catch(Exception $exp){
				$this->session->set_flashdata('form_data', $this->input->post());
				$this->session->set_flashdata('form_status', 0);
				$this->session->set_flashdata('form_msg', $exp->getMessage());
				redirect('/settings');
			}
			redirect('/settings');
        }
		
		public function reactivate($id=0) {
			if($id == 0 && !is_numeric($id) ){
				redirect('/settings');
			}
			try{				
				$data = array('status' => 'active');
				if(!$this->settings_model->update_Settings($id, $data)){
					throw new Exception('Error on update');	
				}else{
					$this->session->set_flashdata('form_status', 1);
					$this->session->set_flashdata('form_msg', 'Success on Reactivate Settings');
				}
			}catch(Exception $exp){
				$this->session->set_flashdata('form_data', $this->input->post());
				$this->session->set_flashdata('form_status', 0);
				$this->session->set_flashdata('form_msg', $exp->getMessage());
				redirect('/settings');
			}
			redirect('/settings');
        }
		
		public function add() {
			if ($this->input->server('REQUEST_METHOD') == 'POST'){
				
				$this->form_validation->set_rules('name', 'Name', 'required|is_unique[Settings.name]');
				
				$data = $this->input->post();
				
				try{
					if($this->form_validation->run()){	
						if(!$this->settings_model->insert_Settings($data)){
							throw new Exception('Error on insert');	
						}else{
							$this->session->set_flashdata('form_status', 1);
							$this->session->set_flashdata('form_msg', 'Success on Add new Settings');
						}
					}else{
						throw new Exception(validation_errors());	
					}
				}catch(Exception $exp){
					$this->session->set_flashdata('form_data', $this->input->post());
					$this->session->set_flashdata('form_status', 0);
					$this->session->set_flashdata('form_msg', $exp->getMessage());
					
				}				
				redirect('/settings');
			}else{
				redirect('/settings');
			}
        }
		
    }