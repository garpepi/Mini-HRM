<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    class MY_Controller extends CI_Controller 
    { 
        //set the class variable.
        public $template  = array();
        public $data      = array('content' => array()); // all things that will be pass throug to view
		public $page_css  = array();
		public $page_js  = array();
		public $usr_desc = array(); // for sidebar
		
        /*Loading the default libraries, helper, language */
        public function __construct(){
            parent::__construct();
			$this->is_login();
			$this->is_active();
         //   $this->load->helper(array('form','language','url'));
          //  $this->lang->load('english');
        }
		
		public function is_login(){
			// check login or not
			if(!$this->session->userdata('logged_in_data'))
			{
				redirect('/login', 'refresh');
			}else{
				$this->usr_desc['user_id'] = $this->session->userdata('logged_in_data')['id'];
			}			
		}
		public function is_active(){
			// check login or not
			$this->load->model('users_model');
			$user_status = $this->users_model->get_users(array('users.id' => $this->session->userdata('logged_in_data')['id']))[0]['status'];
			
			if($user_status == 'inactive'){
				$this->session->sess_destroy();
				$this->session->set_flashdata('logedin_msg', 'Your Account has been inactivate');
				redirect('/login', 'refresh');
			}
		}
		
		/* Check status admin
		protected function is_admin($user_id){
				//querry if user admin or not
				//$user_status = ($user_id != 1 ? 'nonmin' : 'admin') ;
				$this->load->model('users_model');
				$user_status = $this->users_model->get_users(array('id' => $user_id));				

				if($user_status[0]['type'] == 1)
				{
					return 1;
				}else{
					return 0;
				}
		}*/
				
		/* Page management
		private function page_access(){
			return 1; //test only
			 $admin_list = array('home','manageapplications','manageenvironment','managetypeofchanges','manageprogres','managephases','manageteamleads','manageprojects');
			 $tester_list = array('home','reports');
			 $guess_list = array('home');
			 $flag_admin = 0;
			 $flag_tester = 0;

			 if(($this->is_admin($this->usr_desc['user_id']) && in_array($this->uri->segment(1),$admin_list)) )
			 {
				 $flag_admin = 1;
			 }
			 
			 if(($this->is_tester($this->usr_desc['user_id']) && in_array($this->uri->segment(1),$tester_list)) )
			 {
				 $flag_tester = 1;
			 }
			 
			 if($flag_admin || $flag_tester){
				 return 1;
			 }else{
				 return 0;
			 }
			 // write to log "USER <ID> <USER> NOT ALLOW TO ENTER PAGE <PAGE>"
		}*/
		
        /*Front Page Layout*/
        public function layout() {
            // making template and send data to view.
			$this->data['page_css'] = $this->page_css;
			$this->data['page_js'] = $this->page_js;
            $this->template['header'] = $this->load->view('layout/header', $this->data, true);
            $this->template['sidebar'] = $this->load->view('layout/sidebar', $this->usr_desc, true);
			$this->template['top_nav'] = $this->load->view('layout/top_nav', $this->data, true);
            $this->template['contents'] = $this->load->view($this->contents, $this->data, true);
            $this->template['footer'] = $this->load->view('layout/footer', $this->data, true);
            $this->load->view('layout/wrapper', $this->template);
        }
		public function plain_layout() {
            // making template and send data to view.
			$this->data['page_css'] = $this->page_css;
			$this->data['page_js'] = $this->page_js;
            $this->template['header'] = $this->load->view('layout/header', $this->data, true);
            $this->template['sidebar'] = $this->load->view('layout/sidebar', $this->usr_desc, true);
			$this->template['top_nav'] = $this->load->view('layout/top_nav', $this->data, true);
            $this->template['contents'] = $this->load->view($this->contents, $this->data, true);
            $this->template['footer'] = $this->load->view('layout/footer', $this->data, true);
            $this->load->view('layout/wrapper-plain', $this->template);
        }
		
		public function fancy_print($data){
			echo '<pre>';
				print_r($data);
			echo '</pre>';
		}
		public function stop_fancy_print($data){
			echo '<pre>';
				print_r($data);
			echo '</pre>';
			exit();
		}
    }