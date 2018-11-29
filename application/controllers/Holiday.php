<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    class Holiday extends MY_Controller {
		
		public function __construct(){
			parent::__construct();
			$this->load->model('holiday_model');			
		}
		
		private function front_stuff(){
			$this->data = array(
							'title' => 'Holiday',
							'box_title_1' => 'Holiday',
							'sub_box_title_1' => 'Holiday'
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
							'page/js/holiday.js'
						);
			$this->contents = 'contents/holiday/index';   // its your view name, change for as per requirement.
			$this->data['contents'] = array(
									'table_active' => $this->holiday_model->get_holiday(array('status' => 'active')),
									'table_inactive' => $this->holiday_model->get_holiday(array('status' => 'inactive')),
									'data' => array()
								);
		}
		
        		
		public function index() {
			$this->front_stuff();
            $this->layout();
        }
		
		public function add() {
			if ($this->input->server('REQUEST_METHOD') != 'POST'){
				redirect('/holiday');
			}else{
				$data = $this->input->post();
				$this->form_validation->set_rules('name', 'Name', 'required');
				$this->form_validation->set_rules('date', 'Date', 'required|is_unique[holiday.date]');
				
				try{
					if($this->form_validation->run()){
						$data['date'] = ($this->input->post('date'))?db_date_only_format($this->input->post('date')):null;
						if(!$this->holiday_model->insert_holiday($data)){
							throw new Exception('Error on insert');	
						}else{
							$this->session->set_flashdata('form_status', 1);
							$this->session->set_flashdata('form_msg', 'Success on Edit Holiday');
						}	
					}else{
						throw new Exception(validation_errors());
					}
							
				}catch(Exception $exp){
					$this->session->set_flashdata('form_data', $this->input->post());
					$this->session->set_flashdata('form_status', 0);
					$this->session->set_flashdata('form_msg', $exp->getMessage());
					redirect('/holiday');
				}
				redirect('/holiday');
			}
        }		
		
		public function edit($id=0) {
			$ori_data = $this->holiday_model->get_holiday(array('id' => $id))[0];
			if ($this->input->server('REQUEST_METHOD') != 'POST'){
				$this->front_stuff();
				$this->data['contents']['data'] = $ori_data;
				$this->layout();
			}else{
				if($id == 0){
					redirect('/holiday');
				}
				$this->form_validation->set_rules('name', 'Name', 'required'.($ori_data['name'] != $this->input->post('name') ? '|is_unique[holiday.name]' : ''));
				$this->form_validation->set_rules('date', 'Date', 'required'.($ori_data['date'] != $this->input->post('date') ? '|is_unique[holiday.date]' : ''));
				$data = $this->input->post();
				try{
					if($this->form_validation->run()){
						$data['date'] = ($this->input->post('date'))?db_date_only_format($this->input->post('date')):null;
						if(!$this->holiday_model->update_holiday($id, $data)){
							throw new Exception('Error on update');	
						}else{
							$this->session->set_flashdata('form_status', 1);
							$this->session->set_flashdata('form_msg', 'Success on Edit Holiday');
						}	
					}else{
						throw new Exception(validation_errors());
					}								
				}catch(Exception $exp){
					$this->session->set_flashdata('form_data', $this->input->post());
					$this->session->set_flashdata('form_status', 0);
					$this->session->set_flashdata('form_msg', $exp->getMessage());
					redirect('/holiday/edit/'.$id);
				}
				redirect('/holiday');
			}
        }
		public function revoke($id=0) {
			if($id == 0 && !is_numeric($id) ){
				redirect('/holiday');
			}
			try{
				$data = array('status' => 'inactive');
				if(!$this->holiday_model->update_holiday($id, $data)){
					throw new Exception('Error on update');	
				}else{
					$this->session->set_flashdata('form_status', 1);
					$this->session->set_flashdata('form_msg', 'Success on Revoke Holiday');
				}				
			}catch(Exception $exp){
				$this->session->set_flashdata('form_data', $this->input->post());
				$this->session->set_flashdata('form_status', 0);
				$this->session->set_flashdata('form_msg', $exp->getMessage());
				redirect('/holiday');
			}
			redirect('/holiday');
        }
		
		public function reactivate($id=0) {
			if($id == 0 && !is_numeric($id) ){
				redirect('/holiday');
			}
			try{				
				$data = array('status' => 'active');
				if(!$this->holiday_model->update_holiday($id, $data)){
					throw new Exception('Error on update');	
				}else{
					$this->session->set_flashdata('form_status', 1);
					$this->session->set_flashdata('form_msg', 'Success on Reactivate Holiday');
				}
			}catch(Exception $exp){
				$this->session->set_flashdata('form_data', $this->input->post());
				$this->session->set_flashdata('form_status', 0);
				$this->session->set_flashdata('form_msg', $exp->getMessage());
				redirect('/holiday');
			}
			redirect('/holiday');
        }
    }