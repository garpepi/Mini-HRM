<?php
class Api_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
    }
	
	public function hb($client_id = ''){
		try{
			$this->load->model('config_model', 'cm');
			if($client_id != ''){
				$uri = $this->cm->get_url(array('app_id' => 1, 'client_id' => $client_id)).'/Api/hb';
				$client = new GuzzleHttp\Client();
				$res = $client->get($uri, [
					'auth' => ['admin', '1234', 'digest'],
					'headers' => [
						'X-API-KEY' => '12345'
					],
					['connect_timeout' => 3.14],
					['debug' => true]
				]);
				api_log("Beat up!",$res->getStatusCode(),$uri);
			}
		}catch(GuzzleHttp\Exception\ClientException $e){
			$response = $e->getResponse();
			$responseBodyAsString = $response->getBody()->getContents();
			$return['status_code'] = $response->getStatusCode();
			$return['msg'] = $responseBodyAsString;
			api_log("Error on API Model - heartbeat",$response->getStatusCode(),$uri.' - '.$responseBodyAsString);
			return $return;
		}catch(GuzzleHttp\Exception\ConnectException $e){			
			$return['status_code'] = $response->getStatusCode();
			api_log("Error on API Model - heartbeat ".$return['status_code'],'Connection Exception! '.$e->getMessage(),$uri);
			return $return;
		}catch(GuzzleHttp\Exception\RequestException $e){
			$return['status_code'] = '901';
			api_log("Error on API Model - heartbeat ".$return['status_code'],'Request Exception! '.$e->getMessage(),$uri);
			return $return;
		}
		
	}
	public function get_qatracker($emp_id = '',$client_id = '')
    {   
		$this->load->model('config_model', 'cm');
		try{
			/*
			if($client_id == '')
			{
				$destination = 'qatracker';
			}elseif($destination == 'Adidata'){
				$return['status_code'] = 911;
				$return['msg'] = 'Adidata';				
				return $return;
			}else{
				$destination = strtolower($destination).'-qatracker';
			}*/
			//Adidata
			if($client_id == 3){
				$return['status_code'] = 911;
				$return['msg'] = 'Adidata';				
				return $return;
			}
			if($client_id == 0){
				$return['status_code'] = 912;
				$return['msg'] = 'No Client';				
				return $return;
			}			
			$php_required = '5.5';
			$this->load->helper('url');

			$result = null;
			$status_code = null;
			$content_type = null;
			//$uri = $this->config->item($destination).'/Api/qatrackerusers';
			$uri = $this->cm->get_url(array('app_id' => 1, 'client_id' => $client_id)).'/Api/qatrackerusers';
			api_log("Initiate API","get_qatracker",$uri);
			if(empty($uri)){
				$return['status_code'] = 510;
				$return['msg'] = 'URL Not Config, please tell dev app';
				api_log("Error on API Model - get_qatracker",$return['status_code'],$return['msg']);				
				return $return;
			}
			$client = new GuzzleHttp\Client();
			$res = $client->post($uri, [
				'form_params' => [
					'emp_id' => $emp_id,
					'X-API-KEY' => '12345'
				],
				'auth' => ['updateusers', 'update', 'digest'],
				 'headers' => [
					'User-Agent' => 'testing/1.0',
					'Accept'     => 'application/json',
					'realm'      => 'REST API'
				],
				['debug' => true]
			]);
			
			$return['status_code'] = $res->getStatusCode();
			$return['data'] = json_decode((string) $res->getBody());
			api_log("Finsih API Model - get_qatracker",$res->getStatusCode(),'ok');
			return $return;
		}catch(GuzzleHttp\Exception\ClientException $e){
			
			$response = $e->getResponse();
			$responseBodyAsString = $response->getBody()->getContents();
			$return['status_code'] = $response->getStatusCode();
			$return['msg'] = $responseBodyAsString;
			api_log("Error on API Model - get_qatracker",$response->getStatusCode(),$responseBodyAsString);
			return $return;
		}catch(GuzzleHttp\Exception\ConnectException $e){			
			$return['status_code'] = $response->getStatusCode();
			api_log("Error on API Model - get_qatracker ".$return['status_code'],'Connection Exception! '.$e->getMessage(),$uri);
			return $return;
		}catch(GuzzleHttp\Exception\RequestException $e){
			$return['status_code'] = '901';
			api_log("Error on API Model - get_qatracker ".$return['status_code'],'Request Exception! '.$e->getMessage(),$uri);
			return $return;
		}

    }
	
	public function insert_qatracker($data = array(),$client_id = '')
    {    
		$this->load->model('config_model', 'cm');
		try{
			/*
			if($route == '')
			{
				$route = 'qatracker';
			}else{
				$route = strtolower($route).'-qatracker';
			}*/
			
			$php_required = '5.5';
			$this->load->helper('url');

			$result = null;
			$status_code = null;
			$content_type = null;

			//$uri = $this->config->item($route).'/Api/addingusers';
			$uri = $this->cm->get_url(array('app_id' => 1, 'client_id' => $client_id)).'/Api/addingusers';
			api_log("Initiate API","insert_qatracker",$uri);
			if(empty($uri)){
				$return['status_code'] = 510;
				$return['msg'] = 'URL Not Configure, please tell dev app';
				api_log("Error on API Model - insert_qatracker",$return['status_code'],$return['msg']);
				return $return;
			}
			$client = new GuzzleHttp\Client();
			$res = $client->post($uri, [
				'form_params' => [
					'emp_id' => $data['emp_id'],
					'email' => $data['email'],
					'name' => $data['name'],
					'type' => $data['type'],
					'X-API-KEY' => '12345'
				],
				'auth' => ['admin', '1234', 'digest'],
				 'headers' => [
					'User-Agent' => 'testing/1.0',
					'Accept'     => 'application/json',
					'realm'      => 'REST API'
				],
				['debug' => true]
			]);
			
			$return['status_code'] = $res->getStatusCode();
			$return['data'] = json_decode((string) $res->getBody());
			api_log("Finsih API Model - insert_qatracker",$res->getStatusCode(),'ok');
			return $return;
		}catch(GuzzleHttp\Exception\ClientException $e){
			$response = $e->getResponse();
			$responseBodyAsString = $response->getBody()->getContents();
			api_log("Error on API Model - insert_qatracker",$response->getStatusCode(),$responseBodyAsString);
			return $return['status_code']=$response->getStatusCode() . $responseBodyAsString;
		}catch(GuzzleHttp\Exception\ConnectException $e){			
			$return['status_code'] = $response->getStatusCode();
			api_log("Error on API Model - get_qatracker ".$return['status_code'],'Connection Exception! '.$e->getMessage(),$uri);
			return $return;
		}catch(GuzzleHttp\Exception\RequestException $e){
			$return['status_code'] = '901';
			api_log("Error on API Model - get_qatracker ".$return['status_code'],'Request Exception! '.$e->getMessage(),$uri);
			return $return;
		}
    }

	public function update_qatracker($emp_id = '', $data = array(),$client_id = '')
    {    
		$this->load->model('config_model', 'cm');
		try{
			/*
			if($route == '')
			{
				$route = 'qatracker';
			}else{
				$route = strtolower($route).'-qatracker';
			}
			*/
			
			$php_required = '5.5';
			$this->load->helper('url');

			$result = null;
			$status_code = null;
			$content_type = null;

			//$uri = $this->config->item($route).'/Api/updateusers';
			$uri = $this->cm->get_url(array('app_id' => 1, 'client_id' => $client_id)).'/Api/updateusers';
			api_log("Initiate API","update_qatracker",$uri);
			if(empty($uri)){
				$return['status_code'] = 510;
				$return['msg'] = 'URL Not Configure, please tell dev app';
				api_log("Error on API Model - update_qatracker",$return['status_code'],$return['msg']);	
				return $return;
			}
			$client = new GuzzleHttp\Client();
			$res = $client->post($uri, [
				'form_params' => [
					'emp_id' => $emp_id,
					'data' => $data,
					'X-API-KEY' => 'updateusers'
				],
				'auth' => ['updateusers', 'update', 'digest'],
				 'headers' => [
					'User-Agent' => 'testing/1.0',
					'Accept'     => 'application/json',
					'realm'      => 'REST API'
				],
				['debug' => true]
			]);
			
			$return['status_code'] = $res->getStatusCode();
			$return['data'] = json_decode((string) $res->getBody());
			api_log("Finsih API Model - update_qatracker",$res->getStatusCode(),'ok');
			return $return;
		}catch(GuzzleHttp\Exception\ClientException $e){
			$response = $e->getResponse();
			$responseBodyAsString = $response->getBody()->getContents();
			$return['status_code']=$response->getStatusCode() . $responseBodyAsString;
			api_log("Error on API Model - update_qatracker",$response->getStatusCode(),$responseBodyAsString);
			return $return;
		}catch(GuzzleHttp\Exception\ConnectException $e){			
			$return['status_code'] = $response->getStatusCode();
			api_log("Error on API Model - update_qatracker ".$return['status_code'],'Connection Exception! '.$e->getMessage(),$uri);
			return $return;
		}catch(GuzzleHttp\Exception\RequestException $e){
			$return['status_code'] = '901';
			api_log("Error on API Model - update_qatracker ".$return['status_code'],'Request Exception! '.$e->getMessage(),$uri);
			return $return;
		}
    }
	
	public function get_daily_report($id = 0,$period = '',$client_id='')
    {
		$this->load->model('config_model', 'cm');
		try{
			/*
			if($route == '')
			{
				$route = 'qatracker';
			}else{
				$route = strtolower($route).'-qatracker';
			}
			*/
			
			$php_required = '5.5';
			$this->load->helper('url');

			$result = null;
			$status_code = null;
			$content_type = null;

			//$uri = $this->config->item('qatracker').'/Api/dailyreport';
			$uri = $this->cm->get_url(array('app_id' => 1, 'client_id' => $client_id)).'/Api/dailyreport';
			api_log("Initiate API","get_daily_report id=".$id.":period=".$period,$uri);
			if(empty($uri)){
				$return['status_code'] = 510;
				$return['msg'] = 'URL Not Configure, please tell dev app';
				api_log("Error on API Model - get_daily_report",$return['status_code'],$return['msg']);					
				return $return;
			}
			$client = new GuzzleHttp\Client();
			$res = $client->post($uri, [
				'form_params' => [
					'id' => $id,
					'period' => $period,
					'X-API-KEY' => '12345'
				],
				'auth' => ['admin', '1234', 'digest'],
				 'headers' => [
					'User-Agent' => 'testing/1.0',
					'Accept'     => 'application/json',
					'realm'      => 'REST API'
				],
				['debug' => true]
			]);
			
			$return['status_code'] = $res->getStatusCode();
			$return['data'] = json_decode((string) $res->getBody());
			api_log("Finsih API Model - get_daily_report",$res->getStatusCode(),'ok');
			return $return;
		}catch(GuzzleHttp\Exception\ClientException $e){
			$response = $e->getResponse();
			$responseBodyAsString = $response->getBody()->getContents();
			api_log("Error on API Model - get_daily_report",$response->getStatusCode(),$responseBodyAsString);
			return $return['status_code']=$response->getStatusCode() . $responseBodyAsString;
		}catch(GuzzleHttp\Exception\ConnectException $e){			
			$return['status_code'] = $response->getStatusCode();
			api_log("Error on API Model - get_qatracker ".$return['status_code'],'Connection Exception! '.$e->getMessage(),$uri);
			return $return;
		}catch(GuzzleHttp\Exception\RequestException $e){
			$return['status_code'] = '901';
			api_log("Error on API Model - get_qatracker ".$return['status_code'],'Request Exception! '.$e->getMessage(),$uri);
			return $return;
		}
		
    }
}