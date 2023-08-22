<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Student extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
		check_loggin();
		$this->load->model('model_common'); 
		$this->load->helper('url');
		$this->load->helper('slug');
		$this->load->helper('assets');	
		$lang = ($this->session->userdata('lang')) ? $this->session->userdata('lang') : config_item('language');
		$this->lang->load('message', $lang);
		$this->load->library('csrfsimple');
		$this->load->library('curl');
  }
	
 
	public function index()
	{
		
		$url = base_url().'api/kelas/get_all';
		$header = [
				'Content-Type: application/x-www-form-urlencoded'
		];
		$resp = $this->curl->setHeader($header)->request($url, 'GET', http_build_query($param));
		$response_class = json_decode($resp, TRUE);
 
		if($response['ErrStatus'] === 'error') {
				http_response_code(422);
				$msg = ['err_status' => 'error', 'message' => $response['Message'], 'token' => $this->csrfsimple->genToken()];
				echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
				return;
		}
						
		$data['pageTitle']	= 'Data Siswa';
		$data['data_class']	= $response_class['data'];
		$data['tableName']	= 'siswa';
		$data['csrf_token']	= $this->csrfsimple->genToken();
		$data['page_js']	= [  
			['path' => 'assets/new/js/pages/_student.js', 'defer' => true],
		]; 
 
		$this->template->load('template', 'student/index', $data);
	}
	
 
   
}
