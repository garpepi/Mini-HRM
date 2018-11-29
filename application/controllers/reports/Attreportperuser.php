<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    class Attreportperuser extends MY_Controller {
		
		public function __construct(){
			parent::__construct();
			$this->load->model('attendance_model');
			$this->load->model('attendancereport_model');
			$this->load->model('client_model');		
			$this->load->model('sick_model');
			$this->load->model('overtime_model');	
			$this->load->model('holiday_model');
		}
		
		private function front_stuff(){
			$this->data = array(
							'title' => 'Attendace report monthly',
							'box_title_1' => 'Attendace report monthly',
							'sub_box_title_1' => 'Attendace report monthly'
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
									'clients' => $this->client_model->get_client(array('status' => 1))
								);
		}
		
        		
		public function index() {
			$this->front_stuff();
            $this->layout();
        }
		
		public function procdetail($client,$start,$end){
			$generate_time = date("d-m-Y H:i:s");
			$period_ids = array();
			$data_to_print = array();
			//get all attendance_period_id that exist on range
			$raw_period_ids = $this->attendance_model->get_attd_period(array('attendance_period.status' => 'posted','attendance_period.period >=' => $start, 'attendance_period.period <=' => $end,'attendance_period.client_id' => $client ));
			
			foreach($raw_period_ids as $key => $value){
				$period_ids[$value['period']][] = array('id' =>$value['id'],'name' => $value['name'],'emp_id' => $value['emp_id']);
			}

			/*
			$data_to_print[$key][$value]=array(
					'name'=>,
					'period'=>,
					'detail'=>array(
						'date' =>,
						'arrived' =>,
						'returns'=>,
						'duration'=>,
						'desc'
					)
				);
			*/
			foreach($period_ids as $key => $values){
				$tmp_detail = array();
				// fetch per attendance period_id
				foreach($values as $value){
					$tmp_detail = $this->attendance_model->get_attd_detail(array('attd_period_id' => $value['id']));
					$emp_id = $value['emp_id'];
					//fetch per attendance detail
					$detail = array();
					foreach($tmp_detail as $attd_detail){
						// get durations
						if(!empty($attd_detail['arrived']) && !empty($attd_detail['returns']))
						{
							$format = 'H:i:s';
							$arrived = DateTime::createFromFormat($format, $attd_detail['arrived']);
							$return = DateTime::createFromFormat($format, $attd_detail['returns']);
							$count = $return->diff($arrived);							
							//$this->stop_fancy_print($count->h.':'.$count->i.':'.$count->s);
							$dt = $count->h.':'.$count->i.':'.$count->s;
						}else{
							$dt = '-';
						}										
						// get decription
							// check holiday
							$holiday = $this->holiday_model->get_holiday(array('status' => 'active','date' =>$attd_detail['date']));
							if(empty($holiday) && date("N",strtotime($attd_detail['date'])) < 6  ){
								// check not holiday but 
								if($dt == '-')
								{
									// not present sick?
									$sick = $this->sick_model->get_sick(array('sick.status' => 'active', 'sick.emp_id' => $emp_id,'sick.date' => $attd_detail['date'] ));
									if(!empty($sick)){
										$desc = 'Sick - '.$sick[0]['reason'];
									}else{
										$desc = '';
									}
								}else{
									// presnt overtime?
									$overtime = $this->overtime_model->get_overtime(array('overtime.status' => 'active', 'overtime.emp_id' => $emp_id,'overtime.date' => $attd_detail['date'] ));
									if(!empty($overtime)){
										$desc = 'Overtime - '.$overtime[0]['reason'];
									}else{
										$desc = '';
									}									
								}
								$holidayflag = 0;
							}else{
								$holidayflag = 1;
								$desc = '';
							}
						// add to variable
						$detail[] = array(
							'date' => date("d-m-Y",strtotime($attd_detail['date'])),
							'arrived' => (!empty($attd_detail['arrived']) ? $attd_detail['arrived']: '-'),
							'returns'=> (!empty($attd_detail['returns']) ? $attd_detail['returns']: '-'),
							'duration'=> $dt,
							'desc' => $desc,
							'holiday' => $holidayflag
						);
					}
					
					$data_to_print[$key][$value['id']]=array(
						'name'=> $value['name'],
						'period'=> date("F, Y",strtotime($key)),
						'generate' => $generate_time,
						'detail'=> $detail
					);					
				}
			}
			//$this->stop_fancy_print($data_to_print);
			$this->load->view('reports/attendancepersonalprint', array('data' => $data_to_print));
		}
				
    }