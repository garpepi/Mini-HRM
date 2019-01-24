<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    class Employee extends MY_Controller {

		public function __construct(){
			parent::__construct();
			$this->load->model('employee_model');
			$this->load->model('job_model');
			$this->load->model('division_model');
			$this->load->model('api_model');
			$this->load->model('bank_model');
			$this->load->model('employee_status_model');
			$this->load->model('client_model');
			$this->load->model('projects_model');
			$this->load->model('employee_position_model');
		}

		private function front_stuff(){
			$this->data = array(
							'title' => 'Employee',
							'box_title_1' => 'Employee',
							'sub_box_title_1' => 'Employee'
						);
			$this->page_css  = array(
							'vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css'
						);
			$this->page_js  = array(
							'vendor/moment/moment.min.js',
							'vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.js'
						);
		}


		public function index() {
			$this->front_stuff();
			$this->page_css  = array(
							'vendor/datatables-plugins/dataTables.bootstrap.css',
							'vendor/datatables-responsive/dataTables.responsive.css'
						);
			$this->page_js  = array(
							'vendor/datatables/js/jquery.dataTables.min.js',
							'vendor/datatables-plugins/dataTables.bootstrap.min.js',
							'vendor/datatables-responsive/dataTables.responsive.js',
							'page/js/listsuser.js'
						);
            $this->contents = 'employee/lists'; // its your view name, change for as per requirement.
			$this->data['contents'] = array(
							'employee' => $this->employee_model->get_emp()
							);
            $this->layout();
        }

    public function printt() {
      if ($this->input->server('REQUEST_METHOD') != 'POST'){
        $this->front_stuff();
  			$this->page_css  = array(
                'vendor/datatables-plugins/dataTables.bootstrap.css',
                'vendor/datatables-responsive/dataTables.responsive.css',
                'vendor/bootstrap-toggle-master/css/bootstrap-toggle.min.css',
                'vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css',
                'vendor/select2-4.0.3/dist/css/select2.min.css'
  						);
  			$this->page_js  = array(
                'vendor/datatables/js/jquery.dataTables.min.js',
                'vendor/datatables-plugins/dataTables.bootstrap.min.js',
                'vendor/datatables-responsive/dataTables.responsive.js',
                'vendor/bootstrap-toggle-master/js/bootstrap-toggle.min.js',
                'vendor/select2-4.0.3/dist/js/select2.full.min.js',
                'vendor/moment/moment.min.js',
  							'vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.js',
  							'page/js/employeeprint.js'
  						);
              $this->contents = 'employee/print'; // its your view name, change for as per requirement.
  			$this->data['contents'] = array(
							'employee_status' => $this->employee_status_model->get_emp_status(array('status' => 'active')),
							'employee_position' => $this->employee_position_model->get_emp_position(array('status' => 'active')),
  							'employee' => $this->employee_model->get_emp()
  							);
              $this->layout();
      }else{
        try{
					if(empty($this->input->post())){
						throw new Exception('Error collecting data Input');
					}
					//$this->form_validation->set_rules('employee', 'Employee', 'required');
					//$this->form_validation->set_rules('employee', 'Employee', 'required');
					//$this->form_validation->set_rules('employee_status', 'Employee Status', 'required');
          $this->form_validation->set_rules('status', 'Status', 'required');
          $this->form_validation->set_rules('from', 'Join Date from', 'required');
          $this->form_validation->set_rules('to', 'Join Date to', 'required');
					if($this->form_validation->run()){
            $from = db_date_only_format($this->input->post('from'));
            $to = db_date_only_format($this->input->post('to'));
            $period = $from .' - '. $to;
						$all = 0;
						$selected_employee = '';
            $selected_employee_status = '';
						foreach($this->input->post('employee') as $key => $value){
							if($value == 0){
								$all++;
							}else{
								if($selected_employee == ''){
									$selected_employee = $selected_employee.'employee.id = '.$value;
								}else{
									$selected_employee = $selected_employee.' OR employee.id = '.$value;
								}
							}
						}
            foreach($this->input->post('employee_status') as $key => $value){
							if($value == 0){
								$all++;
							}else{
								if($selected_employee_status == ''){
									$selected_employee_status = $selected_employee_status.'employee_status = '.$value;
								}else{
									$selected_employee_status = $selected_employee_status.' OR employee_status = '.$value;
								}
							}
						}
            $select_string = '';
            if($selected_employee_status != '' || $selected_employee != '')
            {
              if($selected_employee == '' && $selected_employee_status != '')
              {
                  $select_string = $selected_employee_status;
              }elseif($selected_employee != '' && $selected_employee_status == ''){
                  $select_string = $selected_employee;
              }else{
                  $select_string = '(' .$selected_employee . ') AND (' . $selected_employee_status .')';
              }
            }

						if($all >= 2){
							if($this->input->post('status') != 'all')
							{
								$employee_list = $this->employee_model->get_emp_print(array('employee.status' =>$this->input->post('status') ,'join_date >=' => $from, 'join_date <=' => $to));								
							}else{
								$employee_list = $this->employee_model->get_emp_print(array('join_date >=' => $from, 'join_date <=' => $to));
							}
						}else{
							if($this->input->post('status') != 'all')
							{
								$employee_list = $this->employee_model->get_emp_print(array('employee.status' =>$this->input->post('status') ,'join_date >=' => $from, 'join_date <=' => $to),$select_string);								
							}else{
								$employee_list = $this->employee_model->get_emp_print(array('join_date >=' => $from, 'join_date <=' => $to),$select_string);		
							}
						}
						//echo $this->input->post('status').'<br>'.$select_string.'<br>'; print_r($this->db->last_query());exit();
						if(empty($employee_list)){
							throw new Exception('No data Available to print');
						}
					}else{
						throw new Exception(validation_errors());
					}


					$exp_data= array();
					$no = 1;
					foreach($employee_list as $key=>$value)
					{
						$exp_data[] = array(
  						$no++,
  						$value['name'],
              $value['npwp'],
              $value['aia_account'],
              $value['bank_account'],
              $value['hp'],
              $value['hp2'],
              $value['email2'],
              $value['email'],
              $value['join_date'],
              $value['contract_start'],
              $value['contract_end'],
              $value['status'],
              $value['non_active_date'],
              $value['note'],
						);
					}
					$this->generate_report($exp_data,$period);
				}catch(Exception $e){
					$this->session->set_flashdata('form_data', $this->input->post());
					return_flash(0,$e->getMessage());
					redirect('employee/printt');
				}
      }

    }
    private function generate_report($data_gen,$period){
			$title[] = array(
						'Employee List ('.$period.')'
					);
			$header[] = array(
						'No',
						'Name',
						'NPWP NO',
						'No Card AIA',
						'Account number BCA',
						'HP 1',
						'HP 2',
						'Personal Email',
						'Adidata Email',
						'Join Date',
						'Start Contract',
						'End Contract',
						'Status',
						'Non-Active Date',
						'Note'
					);
			$footer = array(
						array('')
					);

			$data = array_merge($title,$header);
			$data = array_merge($data,$data_gen);
			$data = array_merge($data,$footer);
			//echo '<pre>';print_r($data);exit();
			// start Generate Excel
			$this->load->library('excel');
			$this->excel->setActiveSheetIndex(0);
			$this->excel->getActiveSheet()->setTitle($period); // naming sheet


			$filename='Employee List '.$period.'.xls'; //save our workbook as this file name
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
			$this->excel->getActiveSheet()->mergeCells('A1:N1');
			$this->excel->getActiveSheet()->getStyle('A1:N2')->getFont()->setBold(true);
			//set title to center
			$style = array(
					'alignment' => array(
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					)
				);
			$this->excel->getActiveSheet()->getStyle("A1:J1")->applyFromArray($style);
			//Autosize
			for($col = 'B'; $col !== 'N'; $col++) {
				$this->excel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
			}
			//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
			//if you want to save it as .XLSX Excel 2007 format
			$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
			//force user to download the Excel file without writing it to server's HD
			$objWriter->save('php://output');
		}

		public function edit($id) {
			if ($this->input->server('REQUEST_METHOD') != 'POST'){
			$this->front_stuff();
			$this->data['box_title_1'] = 'Edit Employee';
			$this->page_js[] = 'page/js/adduser.js';
            $this->contents = 'employee/form'; // its your view name, change for as per requirement.
			$applications = array();
			$employeeData = $this->employee_model->get_emp(array('employee.id' => $id,'cuti.status' => 'active', 'medical.status' => 'active'))[0];
			/*
			if($employeeData['client_id'] == 1){
				$qatrackerroute = '';				
			}else{
				$qatrackerroute = $employeeData['clientname'];
			}
			*/
			
			if($employeeData['client_id'] != 3 && $employeeData['client_id'] != 0) //3 = Adidata
			{
				//$qatracker = $this->api_model->get_qatracker($id,$qatrackerroute);
				$qatracker = $this->api_model->get_qatracker($id,$employeeData['client_id']);
				if($qatracker['status_code'] == 200){
					$applications['qatracker'] = $qatracker['data'][0];
				}
			}
			$this->data['contents'] = array(
							'job' => $this->job_model->get_job(array('status' => 'active')),
							'div' => $this->division_model->get_div(array('status' => 'active')),
							'bank_id' => $this->bank_model->get_bank(array('status' => 'active')),
							'client_id' => $this->client_model->get_client(array('status' => 1)),
							'projects_id' => $this->projects_model->get_projects(array('projects.status' => 1)),
							'employee_status' => $this->employee_status_model->get_emp_status(array('status' => 'active')),
							'employee_position' => $this->employee_position_model->get_emp_position(array('status' => 'active')),
							'employee' => $employeeData,
							'applications' => $applications
							);
            $this->layout();
			}else{
				$original_value = $this->employee_model->get_emp(array('employee.id' => $id))[0];
				$data = $this->input->post();
				if($data['finger_id'] != $original_value['finger_id']) {
					   $is_unique =  '|is_unique[employee.finger_id]';
					} else {
					   $is_unique =  '';
					}
				$this->form_validation->set_rules('finger_id', 'Finger Id', 'required'.$is_unique);
				$this->form_validation->set_rules('name', 'Name', 'required');
				$this->form_validation->set_rules('nick_name', 'Nick Name', 'required');
				$this->form_validation->set_rules('job_id', 'Job', 'required');
				$this->form_validation->set_rules('div_id', 'Division', 'required');
				$this->form_validation->set_rules('employee_status', 'Employee Status', 'required');
				$this->form_validation->set_rules('employee_position', 'Employee Position', 'required');
				if($data['npwp'] != $original_value['npwp']) {
					   $is_unique =  '|is_unique[employee.npwp]';
					} else {
					   $is_unique =  '';
					}
				$this->form_validation->set_rules('npwp', 'NPWP', 'required|numeric|exact_length[15]'.$is_unique);
				if($data['hp'] != $original_value['hp']) {
					   $is_unique =  '|is_unique[employee.hp]';
					} else {
					   $is_unique =  '';
					}
				$this->form_validation->set_rules('hp', 'HP', 'required|numeric|is_unique[employee.hp2]'.$is_unique);
				if($this->input->post('hp2')) {
					if($data['hp2'] != $original_value['hp2']) {
					   $is_unique =  '|is_unique[employee.hp2]';
					} else {
					   $is_unique =  '';
					}
				   $this->form_validation->set_rules('hp2', 'HP2', 'numeric|is_unique[employee.hp]'.$is_unique);
				}

				$this->form_validation->set_rules('address', 'Address', 'required');
				//if($this->input->post('bank_account')) {
					if($data['bank_account'] != $original_value['bank_account']) {
					   $is_unique =  '|is_unique[employee.bank_account]';
					} else {
					   $is_unique =  '';
					}
				   $this->form_validation->set_rules('bank_account', 'Bank Account', 'required|numeric'.$is_unique);
				   $this->form_validation->set_rules('bank_id', 'Bank Name', 'required');
				//}
				//if($this->input->post('aia_number')) {
					if($data['aia_account'] != $original_value['aia_account']) {
					   $is_unique =  '|is_unique[employee.aia_account]';
					} else {
					   $is_unique =  '';
					}
				   $this->form_validation->set_rules('aia_account', 'AIA Account', 'numeric|required'.$is_unique);
				//}
				if($data['email'] != $original_value['email']) {
					   $is_unique =  '|is_unique[employee.email]';
					} else {
					   $is_unique =  '';
					}
				$this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[employee.email2]'.$is_unique);
				if($this->input->post('email2')) {
					if($data['email2'] != $original_value['email2']) {
					   $is_unique =  '|is_unique[employee.email2]';
					} else {
					   $is_unique =  '';
					}
				   $this->form_validation->set_rules('email2', 'Email 2', 'valid_email|is_unique[employee.email]'.$is_unique);
				}
				//if($this->input->post('medical_limit')) {
				   $this->form_validation->set_rules('medical_limit', 'Medical Limit', 'greater_than_equal_to[0]|numeric|required');
				   $this->form_validation->set_rules('cuti_limit', 'Cuti Limit', 'greater_than_equal_to[0]|numeric|required');
				//}
				//$this->form_validation->set_rules('status', 'Status', 'required');

				if($this->input->post('employee_status') == 2){
					$this->form_validation->set_rules('contract_start', 'Start Contract', 'required');
					$this->form_validation->set_rules('contract_end', 'End Contract', 'required');
				}

				$data['birth_of_date'] = ($this->input->post('birth_of_date'))?db_date_only_format($this->input->post('birth_of_date')):null;
				$data['join_date'] = ($this->input->post('join_date'))?db_date_only_format($this->input->post('join_date')):null;
				$data['non_active_date'] = ($this->input->post('non_active_date'))?db_date_only_format($this->input->post('non_active_date')):null;
				$data['contract_start'] = ($this->input->post('contract_start'))?db_date_only_format($this->input->post('contract_start')):null;
				$data['contract_end'] = ($this->input->post('contract_end'))?db_date_only_format($this->input->post('contract_end')):null;
				$data['user_m'] = $this->session->userdata('logged_in_data')['id'];
				if($data['medical_limit'] == $original_value['medical_limit']) {
					unset($data['medical_limit']);
				}
				if($data['cuti_limit'] == $original_value['cuti_limit']) {
					unset($data['cuti_limit']);
				}
				$applications = $data['applications'];
				$emp_id = $id;
				unset($data['applications']);
				unset($data['employee_id']);


				if($this->form_validation->run()){
					try{
						$client_id = $this->projects_model->get_projects(array("projects.id" => $data['project_id'], "projects.status" => "Active"))[0]["client_id"];
						if(empty($client_id))
						{
							$data['client_id'] = 0;
							$data['project_id'] = 0;
						}
						else
						{
							$data['client_id'] = $client_id;							
						}
						if(($original_value['client_id'] != 3) && ($original_value['client_id'] != 0) && ($data['client_id'] !== $original_value['client_id']) ){ //3 Adidata
							// delete others Qatracker if exist
							/*
							if($original_value['client_id'] == 1){ // 1==Mandiri
								$qatrackerroute_original = '';				
							}else{
								$qatrackerroute_original = $original_value['clientname'];
							}*/
							//$qatracker_employee_status_ori = $this->api_model->get_qatracker($id,$qatrackerroute_original);
							$qatracker_employee_status_ori = $this->api_model->get_qatracker($id,$original_value['client_id']);
							api_log($qatracker_employee_status_ori['status_code'],$qatracker_employee_status_ori);
							if($qatracker_employee_status_ori['status_code'] == 200){
								$response_ori=$this->api_model->update_qatracker($emp_id, array('status' => 'inactive', 'email' => $data['email']),$original_value['client_id']);
							}
						}
						
						// Get route Qa tracker
						/*$qatrackerroute = $this->client_model->get_client_name($data['client_id'])['name'];
						
						if($qatrackerroute == 'Mandiri'){
							$qatrackerroute = '';
						}$this->fancy_print($qatrackerroute);
						*/
						$qatrackerroute = $data['client_id'];
						$qatracker_employee_status = $this->api_model->get_qatracker($id,$data['client_id']);
						api_log($qatracker_employee_status['status_code'],$qatracker_employee_status);
						// API update application
						if($qatracker_employee_status['status_code'] == 200){
							if($data['email'] != $original_value['email']) {
								if($applications['qatracker'] == 'not'){
									$response=$this->api_model->update_qatracker($emp_id, array('status' => 'inactive', 'email' => $data['email']),$qatrackerroute);
								}elseif($applications['qatracker'] == 'admin'){
									$response=$this->api_model->update_qatracker($emp_id, array('status' => 'active','type' =>1, 'email' => $data['email']),$qatrackerroute);
								}elseif($applications['qatracker'] == 'tester'){
									$response=$this->api_model->update_qatracker($emp_id, array('status' => 'active','type' =>0, 'email' => $data['email']),$qatrackerroute);
								}elseif($applications['qatracker'] == 'super_admin'){
									$response=$this->api_model->update_qatracker($emp_id, array('status' => 'active','type' =>3, 'email' => $data['email']),$qatrackerroute);
								}
							}else{
								if($applications['qatracker'] == 'not'){
									$response=$this->api_model->update_qatracker($emp_id, array('status' => 'inactive'),$qatrackerroute);
								}elseif($applications['qatracker'] == 'admin'){
									$response=$this->api_model->update_qatracker($emp_id, array('status' => 'active','type' =>1),$qatrackerroute);
								}elseif($applications['qatracker'] == 'tester'){
									$response=$this->api_model->update_qatracker($emp_id, array('status' => 'active','type' =>0),$qatrackerroute);
								}elseif($applications['qatracker'] == 'super_admin'){
									$response=$this->api_model->update_qatracker($emp_id, array('status' => 'active','type' =>3),$qatrackerroute);
								}
							}
							api_log($response['status_code'],$response);
							if($response['status_code'] != 200){
								throw new Exception('Error on update Qa Tracker Data');
							}
						}else{
							api_log($qatracker_employee_status['status_code'],'ENTER INSERT AREA FOR : emp_id= '.$id.' NAME= '.$data['email']);
							// not register on qatracker
							if($applications['qatracker'] != 'not' && $data['client_id'] != 3 && $data['client_id'] != 0){
								if($applications['qatracker'] == 'admin'){
									$response_insert = $this->api_model->insert_qatracker(array(
										'emp_id' => $id,
										'email' => $data['email'],
										'name' => $data['name'],
										'type' => 1
									),$qatrackerroute);
								 api_log($response_insert['status_code'],$response_insert);
								}
								if($applications['qatracker'] == 'tester'){
									$response_insert = $this->api_model->insert_qatracker(array(
										'emp_id' => $id,
										'email' => $data['email'],
										'name' => $data['name'],
										'type' => 0
									),$qatrackerroute);
								 api_log($response_insert['status_code'],$response_insert);
								}
								if($applications['qatracker'] == 'sup_admin'){
									$response_insert = $this->api_model->insert_qatracker(array(
										'emp_id' => $id,
										'email' => $data['email'],
										'name' => $data['name'],
										'type' => 3
									),$qatrackerroute);
								 api_log($response_insert['status_code'],$response_insert);
								}
							}
						}
              
						if (!$this->employee_model->update_emp($data,$id))
						{
							throw new Exception('Error on update HRM Data');
						}


					}catch(Exception $exp){
						$this->session->set_flashdata('form_data', $this->input->post());
						$this->session->set_flashdata('form_status', 0);
						$this->session->set_flashdata('form_msg', $exp->getMessage());
						redirect('/employee/edit/'.$id);
					}

					$this->session->set_flashdata('form_status', 1);
					$this->session->set_flashdata('form_msg', 'Success Edit New Application Name');
				}else{
					$this->session->set_flashdata('form_data', $this->input->post());
					$this->session->set_flashdata('form_status', 0);
					if(!$this->form_validation->run()){
						$this->session->set_flashdata('form_msg', validation_errors());
					}else{
						$this->session->set_flashdata('form_msg', 'Data Already Exist');
					}
				}
				redirect('/employee/edit/'.$id);
			}
        }

		public function view($id) {
			$this->front_stuff();
			$this->data['box_title_1'] = 'View Employee';
			$this->page_js[] = 'page/js/adduser.js';
            $this->contents = 'employee/form'; // its your view name, change for as per requirement.
			$this->data['contents'] = array(
							'job' => $this->job_model->get_job(array('status' => 'active')),
							'div' => $this->division_model->get_div(array('status' => 'active')),
							'bank_id' => $this->bank_model->get_bank(array('status' => 'active')),
							'client_id' => $this->client_model->get_client(array('status' => 1)),
							'projects_id' => $this->projects_model->get_projects(array('projects.status' => 1)),
							'employee_status' => $this->employee_status_model->get_emp_status(array('status' => 'active')),
							'employee_position' => $this->employee_position_model->get_emp_position(array('status' => 'active')),
							'employee' => $this->employee_model->get_emp(array('employee.id' => $id))[0]
							);
            $this->layout();
        }

		public function add() {
			if ($this->input->server('REQUEST_METHOD') != 'POST'){
				$this->front_stuff();
				$this->data['box_title_1'] = 'Add Employee';
				$this->page_js[] = 'page/js/adduser.js';
				$this->contents = 'employee/form'; // its your view name, change for as per requirement.
				$this->data['contents'] = array(
								'job' => $this->job_model->get_job(array('status' => 'active')),
								'div' => $this->division_model->get_div(array('status' => 'active')),
								'bank_id' => $this->bank_model->get_bank(array('status' => 'active')),
								'client_id' => $this->client_model->get_client(array('status' => 1)),
								'projects_id' => $this->projects_model->get_projects(array('projects.status' => 1)),
								'employee_position' => $this->employee_position_model->get_emp_position(array('status' => 'active')),
								'employee_status' => $this->employee_status_model->get_emp_status(array('status' => 'active'))
								);
				$this->layout();
			}else{
				$this->form_validation->set_rules('finger_id', 'Finger Id', 'required|is_unique[employee.finger_id]');
				$this->form_validation->set_rules('name', 'Name', 'required');
				$this->form_validation->set_rules('nick_name', 'Nick Name', 'required');
				$this->form_validation->set_rules('job_id', 'Job', 'required');
				$this->form_validation->set_rules('div_id', 'Division', 'required');
				$this->form_validation->set_rules('employee_status', 'Employee Status', 'required');
				$this->form_validation->set_rules('hp', 'HP', 'required|numeric|is_unique[employee.hp]|is_unique[employee.hp2]');
				$this->form_validation->set_rules('npwp', 'NPWP', 'required|numeric|is_unique[employee.npwp]');
				$this->form_validation->set_rules('project_id', 'Client-Project', 'required');
				$this->form_validation->set_rules('employee_position', 'Employee Position', 'required');
				if($this->input->post('hp2')) {
				   $this->form_validation->set_rules('hp2', 'HP2', 'numeric|is_unique[employee.hp]|is_unique[employee.hp2]');
				}

				$this->form_validation->set_rules('address', 'Address', 'required');
				//if($this->input->post('bank_account')) {
				   $this->form_validation->set_rules('bank_account', 'Bank Account', 'is_unique[employee.bank_account]|required|numeric');
				   $this->form_validation->set_rules('bank_id', 'Bank Name', 'required');
				//}
				//if($this->input->post('aia_number')) {
				   $this->form_validation->set_rules('aia_account', 'AIA Account', 'is_unique[employee.aia_account]|numeric|required');
				//}
				$this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[employee.email]|is_unique[employee.email2]');
				if($this->input->post('email2')) {
				   $this->form_validation->set_rules('email2', 'Email 2', 'valid_email|is_unique[employee.email]|is_unique[employee.email2]');
				}
				//if($this->input->post('medical_limit')) {
				   $this->form_validation->set_rules('medical_limit', 'Medical Limit', 'greater_than_equal_to[0]|numeric|required');
				   $this->form_validation->set_rules('cuti_limit', 'Cuti Limit', 'greater_than_equal_to[0]|numeric|required');
				//}
				//$this->form_validation->set_rules('status', 'Status', 'required');
				if($this->input->post('employee_status') == 2){
					$this->form_validation->set_rules('contract_start', 'Start Contract', 'required');
					$this->form_validation->set_rules('contract_end', 'End Contract', 'required');
				}

				$data = $this->input->post();
				$data['birth_of_date'] = ($this->input->post('birth_of_date'))?db_date_only_format($this->input->post('birth_of_date')):null;
				$data['join_date'] = ($this->input->post('join_date'))?db_date_only_format($this->input->post('join_date')):null;
				$data['contract_start'] = ($this->input->post('contract_start'))?db_date_only_format($this->input->post('contract_start')):null;
				$data['contract_end'] = ($this->input->post('contract_end'))?db_date_only_format($this->input->post('contract_end')):null;
				$data['non_active_date'] = ($this->input->post('non_active_date'))?db_date_only_format($this->input->post('non_active_date')):null;
				$data['user_c'] = $this->session->userdata('logged_in_data')['id'];
				$applications = $data['applications'];
				unset($data['applications']);
				//unset($data['cuti_limit']);
				//unset($data['medical_limit']);


				if($this->form_validation->run()){
					try{
						/*
						$qatrackerroute = $this->client_model->get_client_name($data['client_id'])['name'];
						if($qatrackerroute == 'Mandiri'){
							$qatrackerroute = '';
						}*/
						$client_id = $this->projects_model->get_projects(array("projects.id" => $data['project_id'], "projects.status" => "Active"))[0]["client_id"];
						if(empty($client_id))
						{
							$data['client_id'] = 0;
							$data['project_id'] = 0;
						}
						else
						{
							$data['client_id'] = $client_id;							
						}
						$qatrackerroute = $data['client_id'];
						$id = $this->employee_model->insert_emp($data);
						// adding for application
						if($applications['qatracker'] != 'not'){
							if($applications['qatracker'] == 'admin'){
								$this->api_model->insert_qatracker(array(
									'emp_id' => $id,
									'email' => $data['email'],
									'name' => $data['name'],
									'type' => 1
								),$qatrackerroute);
							}else{
								$this->api_model->insert_qatracker(array(
									'emp_id' => $id,
									'email' => $data['email'],
									'name' => $data['name'],
									'type' => 0
								),$qatrackerroute);
							}
						}
					}catch(Exeption $exp){
						$this->session->set_flashdata('form_data', $this->input->post());
						$this->session->set_flashdata('form_status', 0);
						$this->session->set_flashdata('form_msg', $exp);
						redirect('/employee/add');
					}
					$this->session->set_flashdata('form_status', 1);
					$this->session->set_flashdata('form_msg', 'Success Add New Application Name');
				}else{
					$this->session->set_flashdata('form_data', $this->input->post());
					$this->session->set_flashdata('form_status', 0);
					if(!$this->form_validation->run()){
						$this->session->set_flashdata('form_msg', validation_errors());
					}else{
						$this->session->set_flashdata('form_msg', 'Data Already Exist');
					}
				}
				redirect('/employee/add');
			}
        }

    }
