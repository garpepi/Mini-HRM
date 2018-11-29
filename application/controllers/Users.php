<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    class Users extends MY_Controller {
		
		public function __construct(){
			parent::__construct();
			$this->load->model('users_model');
			$this->load->model('employee_model');
			
		}
		
		private function front_stuff(){
			$this->data = array(
							'title' => 'Admin',
							'box_title_1' => 'Admin',
							'sub_box_title_1' => 'Admin'
						);
			$this->page_css  = array(
							'vendor/datatables-plugins/dataTables.bootstrap.css',
							'vendor/datatables-responsive/dataTables.responsive.css'
						);
			$this->page_js  = array(
							'vendor/datatables/js/jquery.dataTables.min.js',
							'vendor/datatables-plugins/dataTables.bootstrap.min.js',
							'vendor/datatables-responsive/dataTables.responsive.js',
							'page/js/users.js'
						);
			$this->contents = 'users/index';   // its your view name, change for as per requirement.
			$users = $this->users_model->get_users();
			$exclude_emplyoee = array();
			foreach($users as $value){
				$exclude_emplyoee[] = $value['emp_id'];
			}
			$this->data['contents'] = array(
									'table_active' => $this->users_model->get_users(array('users.status' => 'active')),
									'table_inactive' => $this->users_model->get_users(array('users.status' => 'inactive')),
									'employee' => $this->employee_model->get_emp(array('employee.status' => 'active'), array('element' => 'employee.id','data' => $exclude_emplyoee)),
									'data' => array()
								);
		}
		
        		
		public function index() {
			$this->front_stuff();
            $this->layout();
        }
		
		public function edit() {
			$id = $this->session->userdata('logged_in_data')['id'];
			$ori_data = $this->users_model->get_users(array('users.id' => $id))[0];
			if ($this->input->server('REQUEST_METHOD') != 'POST'){
				$this->front_stuff();
				$this->data['contents']['data'] = $ori_data;
				$this->layout();
			}else{
				if($id == 0){
					redirect('/users');
				}
				
				$this->form_validation->set_rules('emp_id', 'Employee', 'required');
				$this->form_validation->set_rules('password', 'Password', 'required');
				$this->form_validation->set_rules('re-password', 'Confirm Password', 'required|matches[password]');
				
				$data = $this->input->post();
				try{

					if($this->form_validation->run()){
						if($ori_data['emp_id'] != $data['emp_id']){
							echo $id.' - '.$data['emp_id'];exit();
							throw new Exception('Error on Validate');	
						}
						$data['password'] = hash("sha256", $data['password'].$this->config->item('mysalt_psw'));
						unset($data['re-password']);
						if(!$this->users_model->update_user($id, $data)){
							throw new Exception('Error on update');	
						}else{
							$this->session->set_flashdata('form_status', 1);
							$this->session->set_flashdata('form_msg', 'Success Change Password');
						}
					}else{
						throw new Exception(validation_errors());	
					}
				}catch(Exception $exp){
					$this->session->set_flashdata('form_data', $this->input->post());
					$this->session->set_flashdata('form_status', 0);
					$this->session->set_flashdata('form_msg', $exp->getMessage());
					redirect('/users/edit/'.$id);
				}
				redirect('/users');
			}
        }
		
		public function revoke($id=0) {
			if($id == 0 && !is_numeric($id) ){
				redirect('/users');
			}
			try{
				if($id == $this->session->userdata('logged_in_data')['id']){
					throw new Exception('Admin Cannot revoke Himself');
				}
				$count = count($this->users_model->get_users(array('users.status' => 'active')));
				if($count == 1){
					throw new Exception('Admin must be Exist minimum 1');
				}
				$data = array('users.status' => 'inactive');
				if(!$this->users_model->update_user($id, $data)){
					throw new Exception('Error on update');	
				}else{
					$this->session->set_flashdata('form_status', 1);
					$this->session->set_flashdata('form_msg', 'Success on Revoke Admin');
				}				
			}catch(Exception $exp){
				$this->session->set_flashdata('form_data', $this->input->post());
				$this->session->set_flashdata('form_status', 0);
				$this->session->set_flashdata('form_msg', $exp->getMessage());
				redirect('/users');
			}
			redirect('/users');
        }
		
		public function reset_password($id=0) {
			$this->load->helper('string');
			if($id == 0 && !is_numeric($id) ){
				redirect('/users');
			}
			try{
				$user = $this->users_model->get_users(array('users.id' => $id))[0];
				$password = random_string('alnum', 16);
				$data['password'] = hash("sha256", $password.$this->config->item('mysalt_psw'));
				if(!$this->users_model->update_user($id, $data)){
					throw new Exception('Error on update');	
				}else{
					// Sending email maybe?
					
					$this->session->set_flashdata('form_status', 1);
					$this->session->set_flashdata('form_msg', 'Success on Reset Password for <b>'. $user['name'] . '</b> . The new Password is : <b>'.$password.'</b>');
				}
			}catch(Exception $exp){
				$this->session->set_flashdata('form_data', $this->input->post());
				$this->session->set_flashdata('form_status', 0);
				$this->session->set_flashdata('form_msg', $exp->getMessage());
				redirect('/users');
			}
			redirect('/users');
        }
		
		public function reactivate($id=0) {
			if($id == 0 && !is_numeric($id) ){
				redirect('/users');
			}
			try{				
				$data = array('users.status' => 'active');
				if(!$this->users_model->update_user($id, $data)){
					throw new Exception('Error on update');	
				}else{
					$this->session->set_flashdata('form_status', 1);
					$this->session->set_flashdata('form_msg', 'Success on Reactivate Admin');
				}
			}catch(Exception $exp){
				$this->session->set_flashdata('form_data', $this->input->post());
				$this->session->set_flashdata('form_status', 0);
				$this->session->set_flashdata('form_msg', $exp->getMessage());
				redirect('/users');
			}
			redirect('/users');
        }
		
		public function add() {
			if ($this->input->server('REQUEST_METHOD') == 'POST'){
				
				$this->form_validation->set_rules('emp_id', 'Employee', 'required|is_unique[users.emp_id]');
				$this->form_validation->set_rules('password', 'Password', 'required');
				$this->form_validation->set_rules('re-password', 'Confirm Password', 'required|matches[password]');
				
				$data = $this->input->post();

				try{
					if($this->form_validation->run()){	
						unset($data['re-password']);
						$data['password'] = hash("sha256", $data['password'].$this->config->item('mysalt_psw'));
						if(!$this->users_model->insert_user($data)){
							throw new Exception('Error on insert');	
						}else{
							$this->session->set_flashdata('form_status', 1);
							$this->session->set_flashdata('form_msg', 'Success Assign new User');
						}
					}else{
						throw new Exception(validation_errors());	
					}
				}catch(Exception $exp){
					$this->session->set_flashdata('form_data', $this->input->post());
					$this->session->set_flashdata('form_status', 0);
					$this->session->set_flashdata('form_msg', $exp->getMessage());
					
				}				
				redirect('/users');
			}else{
				redirect('/users');
			}
        }
		
    }