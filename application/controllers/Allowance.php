<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    class Allowance extends MY_Controller {
		
		public function __construct(){
			parent::__construct();
			$this->load->model('allowance_model');
			$this->load->model('division_model');
			
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
							'page/js/division.js'
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
		
		public function lists($project_id = 0) {
			$this->front_stuff();
			$this->contents = 'contents/allowance/list';   // its your view name, change for as per requirement.
			$this->data['contents'] = array(
									'table_active' => $this->allowance_model->get_show_allowance(array('project_id' => $project_id,'allowance.status' => 'active','client.status' => 1)),
								);
            $this->layout();
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