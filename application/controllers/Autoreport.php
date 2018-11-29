<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    class Autoreport extends CI_Controller {
		public function __construct() {
		   parent::__construct();
		   $this->load->model('employee_model');
		}
		public $months_end = 2;
		private function generate_report($data_gen, $date){
			$header[] = array(
						'Name',
						'Address',
						'Email',
						'Employee Status',
						'Join Date',
						'Start Contract Date',
						'End Contract Date'
					);
			$data = array_merge($header,$data_gen);
			// start Generate Excel
			$this->load->library('excel');
			$this->excel->setActiveSheetIndex(0);
			$this->excel->getActiveSheet()->setTitle('Adidata Contract Report'); // naming sheet


			$filename='Summary Employee Contract Report '.$date.'.xls'; //save our workbook as this file name
		//	header('Content-Type: application/vnd.ms-excel'); //mime type
		//	header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
		//	header('Cache-Control: max-age=0'); //no cache
			$this->excel->getActiveSheet()->fromArray(
					$data,  // The data to set
					NULL,        // Array values with this value will not be set
					'A1'         // Top left coordinate of the worksheet range where
								 //    we want to set these values (default is A1)
				);
			//make the font become bold
			$this->excel->getActiveSheet()->getStyle('A1:X1')->getFont()->setBold(true);
			//Autosize
			for($col = 'A'; $col !== 'Y'; $col++) {
				$this->excel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
			}
			//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
			//if you want to save it as .XLSX Excel 2007 format
			$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
			//force user to download the Excel file without writing it to server's HD
			$objWriter->save('./genreports/'.$filename);
		}
				
		
		public function heartbeat($clent_id){
			$this->load->model('api_model');
			$return_api = $this->api_model->hb($clent_id);
			return 1;
		}
		
		public function autogenerate(){
			//validate if not 6 a clock
			/*
			if(date('H') != 6){
				exit();
			}
			*/
			//if cli
			if(!is_cli()){
				exit();
			}
			write_file('./genreports/file.log', date('Y-m-d').'Fetch : ', "a+");
			$monitoring_contract =  date('Y-m-d', strtotime('+'.$this->months_end.' month'));
			//echo $monitoring_contract;
			$where = array(
				'employee.status' => 'active',
				'employee.employee_status' => 2,
				'employee.contract_end <=' => $monitoring_contract
			);
			$fetch = $this->employee_model->get_emp($where);
			$data = array();
			if(!empty($fetch))
			{
				// Write log generate to file
				write_file('./genreports/file.log', 'exist'."\n", "a+");
				foreach($fetch as $key=> $value)
				{
					// set data
					$temp = array(); // initialize
					$temp = array(
							$value['name'],
							$value['address'],
							$value['email'],
							$value['employee_status_name'],
							$value['join_date'],
							$value['contract_start'],
							$value['contract_end']
							);

					array_push($data,$temp);
				}
				$this->generate_report($data,date('d M Y'));				
			}else{
				write_file('./genreports/file.log', 'empty'."\n", "a+");
			}
		}

		private function sending_email($from_email, $from_name, $to, $cc= array(), $subject, $message, $attachment = ''){
			$this->load->library('email');
		//$this->email->initialize($config);
			$this->email->from($from_email, $from_name);
			$this->email->to($to);
			if(!empty($cc)){
				$this->email->cc($cc);
			}

			$this->email->subject($subject);
			$this->email->message($message);
			if(!empty($attachment)){
				if(is_file($attachment)){
					$this->email->attach($attachment);
					if($this->email->send()){
						return 'Sending Ok';
					}else{
						return 'Sending Not Ok';
					}
				}else{
					echo 'file not found - '. $attachment;
					exit();
					return 'Sending Not Ok - no attachment';
				}
			}
			return 1;
		}

		public function autoemail(){
			//validate if not 9 a clock
			/*if(date('H') != 9){
				exit();
			}*/

			//if cli
			if(!is_cli()){
				exit();
			}

			$this->load->model('autoreportemail_model');
			$milist = $this->autoreportemail_model->get_milist(array('status' => 'active','client' => 'adidata'));
			$email =array();
			foreach($milist as $value)
			{
				$email[]=$value['email'];
			}
			$date = '';
			if(date('j') == 1){
				$date = date('Y-m',strtotime('-1 month')).'-01 00:00:00 - '.date('Y-m-d H:i:s');
			}else{
				$date = date('Y-m').'-01 00:00:00'.' - '.date('Y-m-d H:i:s');
			}

			$holiday_date = array();
			$this->load->model('holiday_model');
			foreach($this->holiday_model->get_holiday(array('status' => 'active')) as $holiday)
			{
				$holiday_date[] = $holiday['date'];
			}

			if(in_array(date('Y-m-d'), $holiday_date))
			{
				return 'Today is holiday';
			}else{
				if(!$this->isWeekend(date('Y-m-d')))
				{
				  return $this->sending_email( 'no-reply.hrm@adi-internal.com', 'Adidata - miniHRM App', $email, array(), 'Contract Summary Report '.date('d M Y'), "Dear Admin Adidata, \n\n This system was generate about employee that has ending contract in next ".$this->months_end." months. \n\n Thanks & Regards, \n admin@Adidata.co.id \n\n\n\n Adidata - miniHRM App - by Garpepi", './genreports/Summary Employee Contract Report '.date('d M Y').'.xls');
				}else{
				  return 'Today is weekend';
				}

			}

		}

    public function isWeekend($date) {
        return (date('N', strtotime($date)) >= 6);
    }

		public function test()
		{
			$this->load->model('autoreportemail_model');
			$milist = array();
			foreach($this->autoreportemail_model->get_milist(array('status' => 'active','client' => 'adidata')) as $milists)
			{
				$milist[] = $milists->email;
			}

		}

    }
