<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teacher extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model('model_teacher');
		
		$this->load->library('xlsxwriter');
		
		if (!isset($_SESSION['username'])) redirect('auth/login');
	}

	public function index(){
		// $begin = new DateTime('2010-05-01');
		// $end = new DateTime('2010-05-10');

		// $interval = DateInterval::createFromDateString('1 day');
		// $period = new DatePeriod($begin, $interval, $end);

		// foreach ($period as $dt) {
		// 	echo $dt->format("Y-m-d\n");
		// }


		$this->load->view('header');
		$this->load->view('teacher/index');
		$this->load->view('footer');
	}

	public function get_total(){
		$sekolah_id = $this->session->userdata('sekolah_id');
		$data['total_row'] = $this->db->where('sekolah_id', $sekolah_id)->where('status', 1)->get('teacher')->num_rows();

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function get_class(){
		$data = $this->db->get('kelas')->result_array();

		header('Content-Type: application/json');
		echo json_encode($data);
	}
}
