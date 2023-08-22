<?php defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		// Cek apakah user sudah login
		check_loggin(); 
		$this->load->model('model_users');

		$lang = ($this->session->userdata('lang')) ? $this->session->userdata('lang') : config_item('language');
		// var_dump($lang);
		// die;
		$this->lang->load('message', $lang);
	}
 

	public function index()
	{
		//echo 'aa'.$this->m_transaction->weeks_in_month('03','2022').'=='.date('d', strtotime("first day of this month"));
		$data['pageTitle']		= 'Dashboard';
		$param = array(); 
		
		if(isset($_GET['sdate']))
		{
			$param['start_date'] = $_GET['sdate'];
		}else{ 
			$param['start_date'] = '01-'.date('m-Y');
		}
		
		$data['start_date']	= $param['start_date'];
		
		if(isset($_GET['edate']))
		{
			$param['end_date'] = $_GET['edate'];
		}else{
			$param['end_date'] = date('d-m-Y');
		}

		$data['end_date']	= $param['end_date'];		
		 

		$this->template->load('template', 'dashboard', $data);
	}

  
	public function error404()
	{
		$data['pageTitle']		= 'Error 404 - Page Not Found';
		$this->load->view('404.php', $data);
	}
}


 