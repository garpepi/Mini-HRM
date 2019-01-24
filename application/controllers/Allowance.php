<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    class Allowance extends MY_Controller {
		
		public function __construct(){
			parent::__construct();
			$this->load->model('allowance_model');
			$this->load->model('projects_model');
			$this->load->model('client_model');
			$this->load->model('employee_position_model');
		}
		
		private function front_stuff(){
			$this->data = array(
							'title' => 'Allowance',
							'box_title_1' => 'Allowance',
							'sub_box_title_1' => 'Allowance'
						);
			$this->page_css  = array(
							'vendor/datatables-plugins/dataTables.bootstrap.css',
							'vendor/datatables-responsive/dataTables.responsive.css'
						);
			$this->page_js  = array(
							'vendor/datatables/js/jquery.dataTables.min.js',
							'vendor/datatables-plugins/dataTables.bootstrap.min.js',
							'vendor/datatables-responsive/dataTables.responsive.js',
							'page/js/allowance.js'
						);
			$this->contents = 'contents/allowance/index';   // its your view name, change for as per requirement.
			$this->data['contents'] = array(
									'table_active' => $this->allowance_model->get_show_allowance(array('allowance.status' => 'active','client.status' => 1),'project_id'),
									'data' => array()
								);
		}
		
        		
		public function index() {
			$this->front_stuff();
            $this->layout();
        }
		
		public function positions($project_id, $position_id) {
			$this->front_stuff();
			$this->contents = 'contents/allowance/positions';   // its your view name, change for as per requirement.
			$this->data['contents'] = array(
									'table_active' => $this->allowance_model->get_show_allowance(array('project_id' => $project_id, 'allowance.employee_position_id' => $position_id,'allowance.status' => 'active','client.status' => 1)),
									'data' => array('position' => $position_id,'project' => $project_id)
								);
            $this->layout();
        }
		
		public function lists($project_id = 0) {
			$this->front_stuff();
			$this->contents = 'contents/allowance/lists';   // its your view name, change for as per requirement.
			
			$this->data['contents'] = array(
									'table_active' => $this->employee_position_model->get_emp_position(array('status' => 'active')),
									'projects' => $this->projects_model->get_projects(array("projects.status" =>  'active',"projects.id" => $project_id))[0],
									'project_id' => $project_id
								);
            $this->layout();
        }
		
		public function set($project_id, $position_id) {			
			if ($this->input->server('REQUEST_METHOD') != 'POST'){
				try{
					if(!empty($this->allowance_model->get_allowance(array('allowance.status' => 'active','allowance.project_id' => $project_id, 'allowance.employee_position_id' => $position_id))))
					{
						throw new Exception('Active allowance settings exist!');
					}
					
					$this->front_stuff();
					$this->contents = 'contents/allowance/form';   // its your view name, change for as per requirement.
					$this->data['contents'] = array(
										'client' => $this->client_model->get_client(array("client.status" => 1)),
										'projects' => $this->projects_model->get_projects(array("projects.status" =>  'active',"projects.id" => $project_id))[0],
										'positions' => $this->employee_position_model->get_emp_position(array('status' => 'active', "id" => $position_id)),
										'data' => array('position' => $position_id,'project' => $project_id)
									);
					$this->layout();
				}catch(Exception $exp){
					$this->session->set_flashdata('form_status', 0);
					$this->session->set_flashdata('form_msg', $exp->getMessage());
					redirect('/allowance');
				}			
			}else{
				$this->form_validation->set_rules('meal_allowance', 'Meal Allowance', 'required|numeric');
				$this->form_validation->set_rules('transport', 'Transport', 'required|numeric');
				$this->form_validation->set_rules('internet_laptop', 'Internet + Laptop', 'required|numeric');
				$this->form_validation->set_rules('overtime[]', 'Overtime', 'required');
				$this->form_validation->set_rules('we_overtime_[]', 'Weekend Overtime', 'required');
				
				try{
					if($this->form_validation->run()){
						$data = $this->input->post();
						$data['project_id'] = $project_id;
						$data['employee_position_id'] = $position_id;
						$data['user_c'] = $this->session->userdata('logged_in_data')['id'];
						$data['client'] = $client_id = $this->projects_model->get_projects(array("projects.id" => $data['project_id'], "projects.status" => "Active"))[0]["client_id"];
					//	$this->stop_fancy_print($data);
						
						if(!empty($this->allowance_model->get_allowance(array('allowance.status' => 'active','allowance.client_id' => $data['client'],'allowance.project_id' => $data['project_id'], 'allowance.employee_position_id' => $position_id))))
						{
							throw new Exception('Active allowance settings exist!');
						}
						
						if(!$this->allowance_model->insert_allowance($data)){
							throw new Exception('Error on insert');	
						}else{
							$this->session->set_flashdata('form_status', 1);
							$this->session->set_flashdata('form_msg', 'Success on Add set of allowance');
						}
					}else{
						throw new Exception(validation_errors());	
					}
				}catch(Exception $exp){
					$this->session->set_flashdata('form_data', $this->input->post());
					$this->session->set_flashdata('form_status', 0);
					$this->session->set_flashdata('form_msg', $exp->getMessage());
					redirect('/allowance/set/'.$project_id.'/'.$position_id);
				}				
				redirect('/allowance/lists/'.$project_id);
			}
        }
		
		public function edit($id=0) {
			$ori_data = $this->allowance_model->get_show_allowance(array('allowance.id' => $id))[0];
			if ($this->input->server('REQUEST_METHOD') != 'POST'){
				$this->front_stuff();
				$this->data['contents']['data'] = $ori_data;
				$this->layout();
			}else{
				if($id == 0){
					redirect('/allowance');
				}
				$data = $this->input->post();
				try{
					if(!$this->allowance_model->update_allowance($id, $data)){
						throw new Exception('Error on update');	
					}else{
						$this->session->set_flashdata('form_status', 1);
						$this->session->set_flashdata('form_msg', 'Success on Edit Allowance');
					}				
				}catch(Exception $exp){
					$this->session->set_flashdata('form_data', $this->input->post());
					$this->session->set_flashdata('form_status', 0);
					$this->session->set_flashdata('form_msg', $exp->getMessage());
					redirect('/allowance/edit/'.$id);
				}
				redirect('/allowance');
			}
        }		
    }