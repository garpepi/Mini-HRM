<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    class Inoutattendance extends MY_Controller {

		public function __construct(){
			parent::__construct();
			$this->load->model('attendancereport_model');
			$this->load->model('sick_model');
			$this->load->model('leaves_model');
			$this->load->model('overtime_model');
			$this->load->model('holiday_model');
			$this->load->model('attendance_timing_model');
			$this->load->model('client_model');
		}

		private function front_stuff(){
			$this->data = array(
							'title' => 'In Out Attendace',
							'box_title_1' => 'In Out Attendace',
							'sub_box_title_1' => 'In Out Attendace'
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
									'vendor/datatables-plugins/rowsgroup/dataTables.rowsGroup.js',
									'vendor/datatables-responsive/dataTables.responsive.js',
									'vendor/datatables-button/js/dataTables.buttons.min.js',
									'vendor/datatables-button/js/buttons.bootstrap.min.js',
									'vendor/datatables-button/js/buttons.html5.min.js',
									'vendor/jszip/dist/jszip.min.js',
									'vendor/pdfmake/build/pdfmake.min.js',
									'vendor/pdfmake/build/vfs_fonts.js',
									'vendor/datatables-button/js/buttons.print.min.js',
									'vendor/bootstrap-toggle-master/js/bootstrap-toggle.min.js',
									'vendor/select2-4.0.3/dist/js/select2.full.min.js',
									'vendor/moment/moment.min.js',
									'vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.js',
									'page/js/inoutattendance.js'
								));
			$this->contents = 'reports/inoutattendance';   // its your view name, change for as per requirement.
			$this->data['contents'] = array(
									'clients' => $this->client_model->get_client(array('status' => 1))
								);
		}


		public function index() {
			$this->front_stuff();
            $this->layout();
        }

		public function proc() {
			if ($this->input->server('REQUEST_METHOD') != 'POST'){
				redirect('/reports/inoutattendance');
			}else{
				$data = $this->input->post();
				$this->form_validation->set_rules('date-from', 'Request date Period', 'required');
				$this->form_validation->set_rules('client', 'Client', 'required');

				try{
					if($this->form_validation->run()){
						$site_date = str_replace('/', '-', $this->input->post('date-from'));
						$start_date = date('Y-m-d',strtotime('01-'.$site_date));
						$end_date = date('Y-m-t',strtotime('01-'.$site_date));

						$detail_data = $this->attendancereport_model->get_in_out_report(array('attendance_detail.date >=' => $start_date, 'attendance_detail.date <=' => $end_date,'attendance_period.status'=>'posted','attendance_period.client_id' => $this->input->post('client')));
						$holiday_raw = $this->holiday_model->get_holiday(array('status' => 'active', 'date >=' => $start_date, 'date <=' => $end_date) );
						$holiday = array();
						if(!empty($holiday_raw)){
							foreach($holiday_raw as $key => $value){
								$holiday[] = $value['date'];
							}
						}
						$holiday_count = count($holiday);

						if(empty($detail_data)){
							throw new Exception('No Posted Data Found!');
						}else{
							$show_data=array();
							/*
							foreach($detail_data as $key => $value)
							{
								$period_data = $this->attendancereport_model->get_report_spesific($value['emp_id'],date('Y-m',strtotime('01-'.$site_date)));
								$show_data[$value['emp_id']]['name'] = $value['name'];
								$status = '';
								$status_in = '';
								if($value['attend']){
									$status = 'A';
								}else{
									if(!empty($this->sick_model->get_sick(array('sick.emp_id' => $value['emp_id'],'sick.date'=> $value['date'],'sick.status'=>'active' ) )))
									{
										$status = 'S';
									}
									if(!empty($this->leaves_model->get_leaves(array('leaves.emp_id' => $value['emp_id'],'leaves.date'=> $value['date'],'leaves.status'=>'active' ) )))
									{
										$status = 'LV';
									}
								}

								if($value['late']){
									$status_in = 'LT';
									$status = 'LT';
								}

								if(!empty($this->overtime_model->get_overtime(array('overtime.emp_id' => $value['emp_id'],'overtime.date'=> $value['date'],'overtime.status'=>'active' ) )))
								{
									$status = 'OV';
								}
								$show_data[$value['emp_id']]['absen'][$value['date']]['status'] = $status;
								$show_data[$value['emp_id']]['absen'][$value['date']]['status_in'] = $status_in;
								$show_data[$value['emp_id']]['absen'][$value['date']]['late_total'] = $period_data['late_total'];
								$show_data[$value['emp_id']]['absen'][$value['date']]['daily_report_total'] = $period_data['daily_report_total'];
								$show_data[$value['emp_id']]['absen'][$value['date']]['leaves_total'] = $period_data['leaves_total'];//cutidigunakan per bulan tsb
								$show_data[$value['emp_id']]['absen'][$value['date']]['sick_total'] = $period_data['sick_total'];
								$show_data[$value['emp_id']]['absen'][$value['date']]['overtime_total'] = $period_data['overtime_total'];
								$show_data[$value['emp_id']]['absen'][$value['date']]['attend_total'] = $period_data['attend_total'];
								$show_data[$value['emp_id']]['absen'][$value['date']]['leaves_remaining'] = $period_data['leaves_remaining'];//sisacuti
								$show_data[$value['emp_id']]['absen'][$value['date']]['in'] = (empty($value['arrived']) ? '-' : $value['arrived']);
								$show_data[$value['emp_id']]['absen'][$value['date']]['out'] = (empty($value['returns']) ? '-' : $value['returns']);
							}


							$this->session->set_flashdata('form_status', 1);
							$this->session->set_flashdata('form_msg', 'Success on Create Summary');

							$this->front_stuff();
							$this->data = array(
									'title' => 'In-Out Attendace',
									'box_title_1' => 'In-Out Attendace '.$data['date-from'],
									'sub_box_title_1' => 'In-Out Attendace'
								);
							$this->data['contents'] = array(
												'inout_data' => $show_data,
												'period' => $start_date,
												'holiday' => $holiday
											);
							$this->contents = 'reports/inoutattendance-plain';   // its your view name, change for as per requirement.
							$this->page_js  	= array_merge($this->page_js,  array(
									'page/js/inoutattendance-plain.js'
								));
							$this->plain_layout();
							*/
							$no = 1;
							$in = array();
							$out = array();
							$lates = array();
							$time_workday= array();
							$periodd = array();
							$countemp = 0;
							$timing = $this->attendance_timing_model->get_timing(array('attendance_timing.client_id'=> $client_id, 'attendance_timing.project_id'=> $project_id,'attendance_timing.status'=> 'active' ));
							$totalworkinghhour = array();
							$latehour = array();
							
							if(empty($timing))
							{
								throw new Exception("Attendance timing not set");
							}
							foreach($detail_data as $key => $value)
							{
								$period_data = $this->attendancereport_model->get_report_spesific($value['emp_id'],date('Y-m',strtotime('01-'.$site_date)));
								
								$status = '';
								// Ading new status only showed on in
								$lateStatus = '';
								// end adding new status
								$status_in = '';
								if($value['attend']){
									$status = 'A';
								}else{
									if(!empty($this->sick_model->get_sick(array('sick.emp_id' => $value['emp_id'],'sick.date'=> $value['date'],'sick.status'=>'active' ) )))
									{
										$status = 'S';
									}
									if(!empty($this->leaves_model->get_leaves(array('leaves.emp_id' => $value['emp_id'],'leaves.date'=> $value['date'],'leaves.status'=>'active' ) )))
									{
										$status = 'LV';
									}
								}

								if($value['late']){
									$status_in = 'LT';
									$status = 'LT';
                  					$lateStatus = 'LT';
								}

								if(!empty($this->overtime_model->get_overtime(array('overtime.emp_id' => $value['emp_id'],'overtime.date'=> $value['date'],'overtime.status'=>'active' ) )))
								{
									$status = 'OV';
								}

								if(!isset($in[$value['emp_id']]))
								{
									$in[$value['emp_id']] = array(
										$no++,
										$value['name'],
										'in',
										(empty($value['arrived']) ? '-' : $value['arrived']).''.($lateStatus !='LT' ? ' - '.$lateStatus: '')
									);
									$countemp++;
								}else{
									array_push($in[$value['emp_id']] , (empty($value['arrived']) ? '-' : $value['arrived']).''.($status !='A' ? ' - '.$status: ''));
								}
								if(!isset($out[$value['emp_id']]))
								{
									$out[$value['emp_id']] = array(
										'',
										'',
										'out',
										(empty($value['returns']) ? '-' : $value['returns']).''.($status !='A' ? ' - '.$status : '')
									);
								}else{
									array_push($out[$value['emp_id']] , (empty($value['returns']) ? '-' : $value['returns']).''.($status !='A' ? ' - '.$status : ''));
								}

								if(!isset($lates[$value['emp_id']]))
								{
									if(!empty($value['arrived']) && $timing['comes']['time'] <= $value['arrived'])
									{
										$ar = new DateTime($value['arrived']);
										$tm = new DateTime($timing['comes']['time']);
										$interval = $tm->diff($ar);
										$retlt = $interval->format("%H : %I : %S");
									}else{
										$retlt = '--';
									}
									$lates[$value['emp_id']] = array(
										'',
										'',
										'Lates',
										$retlt
									);
								}else{
									if(!empty($value['arrived']) && $timing['comes']['time'] <= $value['arrived'])
									{
										$ar = new DateTime($value['arrived']);
										$tm = new DateTime($timing['comes']['time']);
										$interval = $tm->diff($ar);
										$retlt = $interval->format("%H : %I : %S");
									}else{
										$retlt = '--';
									}
									array_push($lates[$value['emp_id']] , $retlt);
								}

								if(!isset($time_workday[$value['emp_id']]))
								{
									if(!empty($value['arrived']) && !empty($value['returns']))
									{
										$ar = new DateTime($value['arrived']);
										$rt = new DateTime($value['returns']);
										$interval = $ar->diff($rt);
										$rettw = $interval->format("%H:%I:%S");
									}else{
										$rettw = '--';
									}
									$time_workday[$value['emp_id']] = array(
										'',
										'',
										'Working Hours',
										$rettw
									);

								}else{
									if(!empty($value['arrived']) && !empty($value['returns']))
									{
										$ar = new DateTime($value['arrived']);
										$rt = new DateTime($value['returns']);
										$interval = $ar->diff($rt);
										$rettw = $interval->format("%H:%I:%S");
									}else{
										$rettw = '--';
									}
									array_push($time_workday[$value['emp_id']] , $rettw);
								}
								
								if(!isset($periodd[$value['emp_id']]))
								{
									$workday = $this->countDays(date('Y',strtotime('01-'.$site_date)),date('m',strtotime('01-'.$site_date))) - $holiday_count;
									$periodd[$value['emp_id']] = array(
										$period_data['sick_total'], //sick
										$period_data['leaves_total'],  //leaves / month based on report database leaves
										$workday-$period_data['sick_total']-$period_data['leaves_total'],
										$workday
									);
								}
							}

							foreach($time_workday as $emp_ids => $times){
								$tmme = '0';
								foreach($times as $adds)
								{
									if(!empty($adds) && $adds != '--' && $adds != 'Working Hours')
									{
										$tmttosec = $this->timetosec($adds);
										$tmme = $tmme + $tmttosec;
									}
								}
								$totalworkinghhour[$emp_ids] = $this->sectotime($tmme);
							}

							foreach($lates as $emp_ids => $times){
								$tmme = '0';
								foreach($times as $adds)
								{
									if(!empty($adds) && $adds != '--' && $adds != 'Working Hours')
									{
										$tmttosec = $this->timetosec($adds);
										$tmme = $tmme + $tmttosec;
									}
								}
								$latehour[$emp_ids] = $this->sectotime($tmme);
							}
							//sort and combine in and out for excel
							foreach($in as $empid => $listabs)
							{
								$in[$empid]= array_merge($in[$empid],$periodd[$empid]);
								$out[$empid]= array_merge($out[$empid],array('','',''));
								$time_workday[$empid]= array_merge($time_workday[$empid],array('','','',''));
								$time_workday[$empid]= array_merge($time_workday[$empid],array($totalworkinghhour[$empid]));
								$lates[$empid]= array_merge($lates[$empid],array('','','',''));
								$lates[$empid]= array_merge($lates[$empid],array($latehour[$empid]));
								array_push($show_data,$in[$empid]);
								array_push($show_data,$out[$empid]);
								array_push($show_data,$lates[$empid]);
								array_push($show_data,$time_workday[$empid]);
							}
							//echo '<pre>';print_r($show_data);exit();
							$this->generate_report($show_data,$start_date,$countemp);
						}
					}else{
						throw new Exception(validation_errors());
					}

				}catch(Exception $exp){
					$this->session->set_flashdata('form_data', $this->input->post());
					$this->session->set_flashdata('form_status', 0);
					$this->session->set_flashdata('form_msg', $exp->getMessage());
					redirect('/reports/inoutattendance');
				}
				//redirect('/reports/inoutattendance');
			}
        }

		private function generate_report($data_gen,$period,$count){
			$title[] = array(
						'In-Out Attendace ('.date('Y-m',strtotime($period)).')'
					);
			$header1[] = array(
						'No',
						'Name',
						'Date'
					);
			$header2[] = array(
						'',
						'',
						''
					);
			$loop_date = $period;
			$index = 0;
			for($i=1;$i <= date("t",strtotime($period));$i++)
			{
				array_push($header1[0],$i);
				array_push($header2[0],date("D",strtotime($loop_date)));
				$loop_date = date("Y-m-d",strtotime($loop_date. "+1 days"));
				$index ++;
			}
			$header1[0] = array_merge($header1[0],array('Sick Total', 'Leaves Total','Attend Total','Working Day','TOTAL'));
			$header2[0] = array_merge($header2[0],array('', '', '', '', ''));
			$data = array_merge($title,$header1);
			$data = array_merge($data,$header2);
			$data = array_merge($data,$data_gen);
			$lastLetter = $this->stringFromColumnIndex($index+6);
		//	echo '<pre>';print_r($data);exit();
			// start Generate Excel
			$this->load->library('excel');
			$this->excel->setActiveSheetIndex(0);
			$this->excel->getActiveSheet()->setTitle($period); // naming sheet


			$filename='In-Out Attendace '.date('Y-m',strtotime($period)).'.xls'; //save our workbook as this file name
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
			$this->excel->getActiveSheet()->mergeCells('A1:'.$lastLetter.'1');
			$this->excel->getActiveSheet()->mergeCells('A2:A3');
			$this->excel->getActiveSheet()->mergeCells('B2:B3');
			$this->excel->getActiveSheet()->mergeCells('C2:C3');
			$this->excel->getActiveSheet()->mergeCells($this->stringFromColumnIndex($index+3).'2:'.$this->stringFromColumnIndex($index+3).'3');
			$this->excel->getActiveSheet()->mergeCells($this->stringFromColumnIndex($index+4).'2:'.$this->stringFromColumnIndex($index+4).'3');
			$this->excel->getActiveSheet()->mergeCells($this->stringFromColumnIndex($index+5).'2:'.$this->stringFromColumnIndex($index+5).'3');
			$this->excel->getActiveSheet()->mergeCells($this->stringFromColumnIndex($index+6).'2:'.$this->stringFromColumnIndex($index+6).'3');
			for($merg = 4; $merg < 4+($count*4) ; $merg++)
			{
				$st = $merg;
				$en = $merg+1;
				if($st%4 == 0)
				{
					$this->excel->getActiveSheet()->mergeCells('A'.$st.':A'.$en);
					$this->excel->getActiveSheet()->mergeCells('B'.$st.':B'.$en);
					$this->excel->getActiveSheet()->mergeCells($this->stringFromColumnIndex($index+3).$st.':'.$this->stringFromColumnIndex($index+3).$en);
					$this->excel->getActiveSheet()->mergeCells($this->stringFromColumnIndex($index+4).$st.':'.$this->stringFromColumnIndex($index+4).$en);
				}
				$merg++;
			}
			$this->excel->getActiveSheet()->getStyle('A1:'.$lastLetter.'3')->getFont()->setBold(true);
			
			//set title to center
			$style = array(
					'alignment' => array(
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
						'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
					)
				);
			$this->excel->getActiveSheet()->getStyle("A1:".$lastLetter."1")->applyFromArray($style);
			$this->excel->getActiveSheet()->getStyle("A1:A256")->applyFromArray($style);
			$this->excel->getActiveSheet()->getStyle("B1:B2561")->applyFromArray($style);
			$this->excel->getActiveSheet()->getStyle($this->stringFromColumnIndex($index+3)."1:".$this->stringFromColumnIndex($index+3)."256")->applyFromArray($style);
			$this->excel->getActiveSheet()->getStyle($this->stringFromColumnIndex($index+4)."1:".$this->stringFromColumnIndex($index+4)."2561")->applyFromArray($style);
			$this->excel->getActiveSheet()->getStyle($this->stringFromColumnIndex($index+5)."1:".$this->stringFromColumnIndex($index+5)."256")->applyFromArray($style);
			$this->excel->getActiveSheet()->getStyle($this->stringFromColumnIndex($index+6)."1:".$this->stringFromColumnIndex($index+6)."2561")->applyFromArray($style);


			//Autosize
			for($col = 'C'; $col !== $this->stringFromColumnIndex($index+2); $col++) {
				$this->excel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
			}

			//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
			//if you want to save it as .XLSX Excel 2007 format
			$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
			//force user to download the Excel file without writing it to server's HD
			$objWriter->save('php://output');
		}

		private function stringFromColumnIndex($pColumnIndex = 0)
		{
			//    Using a lookup cache adds a slight memory overhead, but boosts speed
			//    caching using a static within the method is faster than a class static,
			//        though it's additional memory overhead
			static $_indexCache = array();

			if (!isset($_indexCache[$pColumnIndex])) {
				// Determine column string
				if ($pColumnIndex < 26) {
					$_indexCache[$pColumnIndex] = chr(65 + $pColumnIndex);
				} elseif ($pColumnIndex < 702) {
					$_indexCache[$pColumnIndex] = chr(64 + ($pColumnIndex / 26)) .
												  chr(65 + $pColumnIndex % 26);
				} else {
					$_indexCache[$pColumnIndex] = chr(64 + (($pColumnIndex - 26) / 676)) .
												  chr(65 + ((($pColumnIndex - 26) % 676) / 26)) .
												  chr(65 + $pColumnIndex % 26);
				}
			}
			return $_indexCache[$pColumnIndex];
		}

		private function countDays($year, $month, $ignore=array(0,6)) {
			$count = 0;
			$counter = mktime(0, 0, 0, $month, 1, $year);
			while (date("n", $counter) == $month) {
				if (in_array(date("w", $counter), $ignore) == false) {
					$count++;
				}
				$counter = strtotime("+1 day", $counter);
			}
			return $count;
		}

		private function timetosec($str_time)
		{
			$str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time);
			sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
			$time_seconds = $hours * 3600 + $minutes * 60 + $seconds;
			return $time_seconds;
		}

		private function sectotime($init)
		{
			$hours = floor($init / 3600);
			$minutes = floor(($init / 60) % 60);
			$seconds = $init % 60;
			return "$hours:$minutes:$seconds";
		}

    }
