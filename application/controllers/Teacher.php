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
		$this->load->view('header');
		$this->load->view('teacher/index');
		$this->load->view('footer');
	}

	public function get_task_chart(){
		$sekolah_id = $this->session->userdata('sekolah_id');

		$post = $this->input->post();

		$start = new DateTime($post['start']);
		$end = new DateTime($post['end']);
		$end = $end->modify('+1 day');

		$interval = DateInterval::createFromDateString('1 day');
		$period = new DatePeriod($start, $interval, $end);

		$data = [];
		foreach ($period as $key => $dt) {
			$data[$key]['tanggal'] 	= $dt->format("Y-m-d");
			$hasil 	= $this->model_teacher->get_all_task_by_date($sekolah_id, $dt->format("Y-m-d"))->num_rows();
			$data[$key]['value'] 	= ($hasil) ? $hasil : 0;
		}

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function get_total(){
		$sekolah_id = $this->session->userdata('sekolah_id');
		$data['total_row'] = $this->db->where('sekolah_id', $sekolah_id)->where('status', 1)->get('teacher')->num_rows();

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function detail($id = ''){
		if(!$id) redirect(base_url('teacher'));

		$data['detail']	= $this->db->where('teacher_id', $id)->get('teacher')->row_array();
		$dataUser = $this->db->where('username', $data['detail']['nik'])->get('users')->row_array();
		$data['detail']['photo'] = ($dataUser) ? $dataUser['photo'] : 'user.png';
		$data['detail']['nama_sekolah'] = $this->db->where('sekolah_id', $data['detail']['sekolah_id'])->get('sekolah')->row_array()['sekolah_nama'];

		$this->load->view('header');
		$this->load->view('teacher/detail', $data);
		$this->load->view('footer');
	}

	public function get_class(){
		$data = $this->db->get('kelas')->result_array();

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function search(){
		$get = $this->input->get();
		$username 	= $this->session->userdata('username');
		$user_level = $this->db->where('username', $username)->get('users')->row_array()['user_level'];
		$page 		= isset($get['page']) ? (int)$get['page'] : 1;
		$limit 		= isset($get['limit']) ? (int)$get['limit'] : 3;
		$filter		= $get['filter'];

		$page = ($page - 1) * $limit;

		$data['user_level'] 	= $user_level;
		$data['data'] 			= $this->model_teacher->get_history($limit, $page, $filter);
		$data['total_records'] 	= $this->model_teacher->get_total_history($filter);
		$data['total_pages'] 	= ceil($data['total_records'] / $limit);

		// create json header	
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function get_summary(){
		$post 				= $this->input->post();
		$data['total_exam']	= $this->model_teacher->get_total_exam($post['teacher_id'], $post['start'], $post['end']);
		$data['total_task'] = $this->model_teacher->get_total_task($post['teacher_id'], $post['start'], $post['end']);

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function get_exam(){
		$get = $this->input->get();
		$page 		= isset($get['page']) ? (int)$get['page'] : 1;
		$limit 		= isset($get['limit']) ? (int)$get['limit'] : 3;

		$page = ($page - 1) * $limit;

		$data['data']			= $this->model_teacher->get_exam($limit, $page, $get['teacher_id']);
		$data['total_records'] 	= $this->model_teacher->get_total_row_exam($get['teacher_id']);
		$data['total_pages'] 	= ceil($data['total_records'] / $limit);

		// create json header	
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function get_task(){
		$get = $this->input->get();
		$page 		= isset($get['page']) ? (int)$get['page'] : 1;
		$limit 		= isset($get['limit']) ? (int)$get['limit'] : 10;

		$page = ($page - 1) * $limit;

		$data['data']	= $this->model_teacher->get_task($limit, $page, $get['teacher_id']);
		$data['total_records'] 	= $this->db->where('teacher_id', $get['teacher_id'])->get('task')->num_rows();
		$data['total_pages'] 	= ceil($data['total_records'] / $limit);

		// create json header	
		header('Content-Type: application/json');
		echo json_encode($data);
	}
}
