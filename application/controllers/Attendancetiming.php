<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    class Attendancetiming extends MY_Controller {
		
		public function __construct(){
			parent::__construct();
			$this->load->model('attendance_timing_model');
			$this->load->model('division_model');
			
		}
		
		private function front_stuff(){
			$this->data = array(
							'title' => 'Attendance Timing',
							'box_title_1' => 'Attendance Timing',
							'sub_box_title_1' => 'Attendance Timing'
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
							'page/js/timing.js'
						);
			$this->contents = 'contents/timing/index';   // its your view name, change for as per requirement.
			$this->data['contents'] = array(
									'table_active' => $this->attendance_timing_model->get_show_timing(array('attendance_timing.status' => 'active'),'project_id'),
									'data' => array()
								);
		}
		
        		
		public function index() {
			$this->front_stuff();
            $this->layout();
        }
		
		public function edit($client_id = 0,$project_id = 0) {
			$ori_data = $this->attendance_timing_model->get_show_timing(array('attendance_timing.client_id' => $client_id, 'attendance_timing.project_id' => $project_id));
			$id = $ori_data[0]['id'];
			
			if ($this->input->server('REQUEST_METHOD') != 'POST'){
				$this->front_stuff();
				$this->data['contents']['data'] = $ori_data;
				$this->layout();
			}else{
				if($client_id == 0 || $project_id == 0 ){
					redirect('/attendancetiming');
				}
				$data = $this->input->post();
				try{
					foreach($data as $id => $value)
					{
						if(!$this->attendance_timing_model->update_timing($id, array('time' => $value))){
							throw new Exception('Error on update');	
						}else{
							$this->session->set_flashdata('form_status', 1);
							$this->session->set_flashdata('form_msg', 'Success on Edit Timing');
						}										
					}
				}catch(Exception $exp){
					$this->session->set_flashdata('form_data', $this->input->post());
					$this->session->set_flashdata('form_status', 0);
					$this->session->set_flashdata('form_msg', $exp->getMessage());
					redirect('/attendancetiming/edit/'.$client_id.'/'.$project_id);
				}
				redirect('/attendancetiming');
			}
        }		
    }