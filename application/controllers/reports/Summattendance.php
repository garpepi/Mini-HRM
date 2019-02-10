<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    class Summattendance extends MY_Controller {
		
		public function __construct(){
			parent::__construct();
			$this->load->model('attendancereport_model');
			$this->load->model('client_model');	
			$this->load->model('projects_model');				
		}
		
		private function front_stuff(){
			$this->data = array(
							'title' => 'Summary Attendace',
							'box_title_1' => 'Summary Attendace',
							'sub_box_title_1' => 'Summary Attendace'
						);
			$this->page_css 	 = array_merge($this->page_css,  array(
											'vendor/datatables-plugins/dataTables.bootstrap.css',
											'vendor/datatables-responsive/dataTables.responsive.css',
											'vendor/datatables.net-buttons-bs/css/buttons.bootstrap.min.css',
											'vendor/bootstrap-toggle-master/css/bootstrap-toggle.min.css',
											'vendor/select2-4.0.3/dist/css/select2.min.css',
											'vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css'
										));
			$this->page_js  	= array_merge($this->page_js,  array(
									'vendor/datatables/js/jquery.dataTables.min.js',
									'vendor/datatables-plugins/dataTables.bootstrap.min.js',
									'vendor/datatables-responsive/dataTables.responsive.js',
									'vendor/datatables-button/js/dataTables.buttons.min.js',
									'vendor/datatables-button/js/buttons.bootstrap.min.js',
									//'vendor/datatables-button/js/buttons.flash.min.js',
									'vendor/datatables-button/js/buttons.html5.min.js',
									'vendor/jszip/dist/jszip.min.js',
									'vendor/pdfmake/build/pdfmake.min.js',
									'vendor/pdfmake/build/vfs_fonts.js',
									'vendor/datatables-button/js/buttons.print.min.js',
									'vendor/bootstrap-toggle-master/js/bootstrap-toggle.min.js',
									'vendor/select2-4.0.3/dist/js/select2.full.min.js',
									'vendor/moment/moment.min.js',
									'vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.js',
									'page/js/summattendance.js'
								));
			$this->contents = 'reports/sumattendance';   // its your view name, change for as per requirement.
			$this->data['contents'] = array(
									'clients' => $this->client_model->get_client(array('status' => 1)),
									'projects' => $this->projects_model->get_projects(array('projects.status' => 1))
								);
		}
		
        		
		public function index() {
			$this->front_stuff();
            $this->layout();
        }
		
		public function proc() {
			if ($this->input->server('REQUEST_METHOD') != 'POST'){
				redirect('/reports/summattendance');
			}else{
				$data = $this->input->post();
				$this->form_validation->set_rules('date-from', 'Request date from', 'required');
				$this->form_validation->set_rules('date-to', 'Request date to', 'required');
				$this->form_validation->set_rules('project', 'Projetcs', 'required');
				
				try{
					if($this->form_validation->run()){
						$site_date = str_replace('/', '-', $this->input->post('date-from'));
						$data['date-from'] = date('Y-m-d',strtotime('01-'.$site_date) );
						$site_date = '01-'.str_replace('/', '-', $this->input->post('date-to'));
						$data['date-to'] = date('Y-m-d', strtotime("+1 months",strtotime($site_date)));
						
						$client_id = $this->projects_model->get_projects(array('projects.id' => $this->input->post('project')))[0]['client_id'];
						$project_id = $this->input->post('project');

						$sum_data = $this->attendancereport_model->get_report($data['date-from'],$data['date-to'],$client_id,$project_id);
						
						//echo '<pre>';print_r($this->db->last_query());exit();
						if(empty($sum_data)){
							throw new Exception('Data empty!');	
						}else{
							$show_data=array();
							$project_name = $sum_data[0]['project_name'];
							$client_name = $sum_data[0]['client_name'];
							/*
							foreach($sum_data as $key=>$value){
								$show_data[$value['emp_id']]=array(
									'name' => $value['name'],
									'attend_total' => (isset($show_data[$value['emp_id']]['attend_total']) ? $show_data[$value['emp_id']]['attend_total'] : 0)+$value['attend_total'],
									'late_total' => (isset($show_data[$value['emp_id']]['late_total']) ? $show_data[$value['emp_id']]['late_total'] : 0)+$value['late_total'],
									'leaves_total_used' => (isset($show_data[$value['emp_id']]['leaves_total']) ? $show_data[$value['emp_id']]['leaves_total'] : 0)+$value['leaves_total'],
									'daily_report_total' => (isset($show_data[$value['emp_id']]['daily_report_total']) ? $show_data[$value['emp_id']]['daily_report_total'] : 0)+$value['daily_report_total'],
									'overtime_total' => (isset($show_data[$value['emp_id']]['overtime_total']) ? $show_data[$value['emp_id']]['overtime_total'] : 0)+$value['overtime_total'],
									'sick_total' => (isset($show_data[$value['emp_id']]['sick_total']) ? $show_data[$value['emp_id']]['sick_total'] : 0)+$value['sick_total'],
									'leaves_total_calc' => ((isset($show_data[$value['emp_id']]['leaves_total']) ? $show_data[$value['emp_id']]['leaves_total'] : 0)+$value['leaves_total']) + $value['leaves_remaining'],
									'leaves_remaining' => $value['leaves_remaining'],
								);
							}
							
							$this->session->set_flashdata('form_status', 1);
							$this->session->set_flashdata('form_msg', 'Success on Create Summary');
							
							$this->front_stuff();
							$this->data = array(
									'title' => 'Summary Attendace',
									'box_title_1' => 'Summary Attendace '.$data['date-from'] . ' - ' .$data['date-to'],
									'sub_box_title_1' => 'Summary Attendace'
								);
							$this->data['contents'] = array(
												'leaves_data' => $show_data,
												'period' => $data['date-from'] . ' - ' .$data['date-to']
											);
							$this->layout();
							*/
							$no = 1;
							foreach($sum_data as $key=>$value){
								$show_data[$value['emp_id']]=array(									
									$no++,
									$value['name'],
									(isset($show_data[$value['emp_id']][2]) ? $show_data[$value['emp_id']][2] : 0)+$value['attend_total'],
									(isset($show_data[$value['emp_id']][3]) ? $show_data[$value['emp_id']][3] : 0)+$value['daily_report_total'],
									(isset($show_data[$value['emp_id']][4]) ? $show_data[$value['emp_id']][4] : 0)+$value['late_total'],
									(isset($show_data[$value['emp_id']][5]) ? $show_data[$value['emp_id']][5] : 0)+$value['overtime_total'],
									(isset($show_data[$value['emp_id']][6]) ? $show_data[$value['emp_id']][6] : 0)+$value['sick_total'],
									((isset($show_data[$value['emp_id']][8]) ? $show_data[$value['emp_id']][8] : 0)+$value['leaves_total']) + $value['leaves_remaining'],
									$value['leaves_remaining'],
									(isset($show_data[$value['emp_id']][7]) ? $show_data[$value['emp_id']][7] : 0)+$value['leaves_total'],
								);
							}
						//	echo '<pre>';print_r($show_data);exit();
							$this->generate_report($show_data,$data['date-from'] . ' - ' .$data['date-to'],$client_name,$project_name);
						}	
					}else{
						throw new Exception(validation_errors());
					}
							
				}catch(Exception $exp){
					$this->session->set_flashdata('form_data', $this->input->post());
					$this->session->set_flashdata('form_status', 0);
					$this->session->set_flashdata('form_msg', $exp->getMessage());
					redirect('/reports/summattendance');
				}
				//redirect('/reports/summattendance');
			}
        }
		private function generate_report($data_gen,$period,$client_name = '',$project_name=''){
			if(!empty($client_name) && !empty($project_name))
			{
				$title[] = array(
						'Summary Attendance ('.$period.') '.$project_name.' - '.$client_name
					);
			}
			else
			{
				$title[] = array(
						'Summary Attendance ('.$period.')'
					);
			}
			
			$header[] = array(
						'No',
						'Name',
						'Attendance Total',
						'Daily Report Total',
						'Late Total',
						'Overtime Total',
						'Sick Total',
						'Leaves Total',
						'Leaves Remaining',
						'Leaves Used Total'
					);
			$data = array_merge($title,$header);
			$data = array_merge($data,$data_gen);
			// start Generate Excel
			$this->load->library('excel');
			$this->excel->setActiveSheetIndex(0);
			$this->excel->getActiveSheet()->setTitle($period); // naming sheet
			
			
			//$filename='Summary Attendance '.$period.'.xls'; //save our workbook as this file name
			$filename=$title[0][0].'.xls';
			header('Content-Type: application/vnd.ms-excel'); //mime type
			header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
			header('Cache-Control: max-age=0'); //no cache
		//	$this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "Summary Attendace (".$period.')');
			$this->excel->getActiveSheet()->fromArray( 
					$data,  // The data to set
					NULL,        // Array values with this value will not be set
					'A1',         // Top left coordinate of the worksheet range where
					true			 //  print 0
				);
			//make the font become bold
			$this->excel->getActiveSheet()->mergeCells('A1:J1');
			$this->excel->getActiveSheet()->getStyle('A1:J2')->getFont()->setBold(true);
			//set title to center
			$style = array(
					'alignment' => array(
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					)
				);
			$this->excel->getActiveSheet()->getStyle("A1:J1")->applyFromArray($style);
			//Autosize
			for($col = 'A'; $col !== 'J'; $col++) {
				$this->excel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
			}
			//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
			//if you want to save it as .XLSX Excel 2007 format
			$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
			//force user to download the Excel file without writing it to server's HD
			$objWriter->save('php://output');
		}
		
    }