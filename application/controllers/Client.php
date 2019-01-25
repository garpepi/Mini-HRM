<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    class Client extends MY_Controller {

		public function __construct(){
			parent::__construct();
			$this->load->model('client_model');
			$this->load->model('allowance_model');

		}

		private function front_stuff(){
			$this->data = array(
							'title' => 'client',
							'box_title_1' => 'Client',
							'sub_box_title_1' => 'Client'
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
							'page/js/client.js'
						);
			$this->contents = 'contents/client/index';   // its your view name, change for as per requirement.
			$this->data['contents'] = array(
									'table_active' => $this->client_model->get_div(array('status' => 1)),
									'table_inactive' => $this->client_model->get_div(array('status' => 0)),
									'data' => array()
								);
		}


		public function index() {
			$this->front_stuff();
            $this->layout();
        }

		public function edit($id=0) {
			$ori_data = $this->client_model->get_div(array('id' => $id))[0];
			$allowance_data = $this->allowance_model->get_allowance(array('client_id' => $id));
			foreach($allowance_data as $key => $value){
				$ori_data['meal_allowance'] = $allowance_data['meal_allowance']['nominal'];
				$ori_data['transport'] = $allowance_data['transport']['nominal'];
				$ori_data['internet_laptop'] = $allowance_data['internet_laptop']['nominal'];
				$ori_data['overtime_meal_allowance'] = $allowance_data['overtime_meal_allowance']['nominal'];
				$ori_data['overtime_go_home_allowance'] = $allowance_data['overtime_go_home_allowance']['nominal'];
			}
			
			if ($this->input->server('REQUEST_METHOD') != 'POST'){
				$this->front_stuff();
				$this->data['contents']['data'] = $ori_data;
				$this->layout();
			}else{
				if($id == 0){
					redirect('/client');
				}
				$this->form_validation->set_rules('name', 'Name', 'required'.($ori_data['name'] != $this->input->post('name') ? '|is_unique[client.name]' : ''));
				$data = $this->input->post();
				try{
					if($this->form_validation->run()){
						if(!$this->client_model->update_div($id, $data)){
							throw new Exception('Error on update');
						}else{
							$this->session->set_flashdata('form_status', 1);
							$this->session->set_flashdata('form_msg', 'Success on Edit client');
						}
					}else{
						throw new Exception(validation_errors());
					}
				}catch(Exception $exp){
					$this->session->set_flashdata('form_data', $this->input->post());
					$this->session->set_flashdata('form_status', 0);
					$this->session->set_flashdata('form_msg', $exp->getMessage());
					redirect('/client/edit/'.$id);
				}
				redirect('/client');
			}
        }

		public function revoke($id=0) {
			if($id == 0 && !is_numeric($id) ){
				redirect('/client');
			}
			try{
				$count = count($this->client_model->get_div(array('status' => 1)));
				if($count == 1){
					throw new Exception('client must be Exist minimum 1');
				}
				$data = array('status' => 0);
				if(!$this->client_model->update_div($id, $data)){
					throw new Exception('Error on update');
				}else{
					$this->session->set_flashdata('form_status', 1);
					$this->session->set_flashdata('form_msg', 'Success on Revoke client');
				}
			}catch(Exception $exp){
				$this->session->set_flashdata('form_data', $this->input->post());
				$this->session->set_flashdata('form_status', 0);
				$this->session->set_flashdata('form_msg', $exp->getMessage());
				redirect('/client');
			}
			redirect('/client');
        }

		public function reactivate($id=0) {
			if($id == 0 && !is_numeric($id) ){
				redirect('/client');
			}
			try{
				$data = array('status' => 1);
				if(!$this->client_model->update_div($id, $data)){
					throw new Exception('Error on update');
				}else{
					$this->session->set_flashdata('form_status', 1);
					$this->session->set_flashdata('form_msg', 'Success on Reactivate client');
				}
			}catch(Exception $exp){
				$this->session->set_flashdata('form_data', $this->input->post());
				$this->session->set_flashdata('form_status', 0);
				$this->session->set_flashdata('form_msg', $exp->getMessage());
				redirect('/client');
			}
			redirect('/client');
        }

		public function add() {
			if ($this->input->server('REQUEST_METHOD') == 'POST'){

				$this->form_validation->set_rules('name', 'Name', 'required|is_unique[client.name]');

				$data = $this->input->post();

				try{
					if($this->form_validation->run()){
						if(!$this->client_model->insert_div($data)){
							throw new Exception('Error on insert client');
						}else{
							$this->session->set_flashdata('form_status', 1);
							$this->session->set_flashdata('form_msg', 'Success on Add new client');
						}
					}else{
						throw new Exception(validation_errors());
					}
				}catch(Exception $exp){
					$this->session->set_flashdata('form_data', $this->input->post());
					$this->session->set_flashdata('form_status', 0);
					$this->session->set_flashdata('form_msg', $exp->getMessage());

				}
				redirect('/client');
			}else{
				redirect('/client');
			}
        }

    }
