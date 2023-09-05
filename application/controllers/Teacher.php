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

		$data['data']	= $this->model_teacher->get_exam($limit, $page, $get['teacher_id']);

		$i = 0;
		foreach($data['data'] as $key => $val){
			$exam = $this->db->where('exam_id', $val['exam_id'])->where('teacher_id', $get['teacher_id'])->get('exam_student')->row_array();
			$data['data'][$i]['exam_total_nilai'] = ($exam) ? $exam['exam_total_nilai'] : '';
			$data['data'][$i]['exam_submit'] = ($exam) ? $exam['exam_submit'] : '';
			$i++;
		}

		$data['total_records'] 	= $this->model_teacher->get_total_row_exam($get['teacher_id']);
		$data['total_pages'] 	= ceil($data['total_records'] / $limit);

		// create json header	
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function get_task(){
		$get = $this->input->get();
		$page 		= isset($get['page']) ? (int)$get['page'] : 1;
		$limit 		= isset($get['limit']) ? (int)$get['limit'] : 3;

		$page = ($page - 1) * $limit;

		$data['data']	= $this->model_teacher->get_task($limit, $page, $get['teacher_id']);
		$i = 0;
		foreach($data['data'] as $key => $val){
			$task = $this->db->where('task_id', $val['task_id'])->where('teacher_id', $get['teacher_id'])->get('task')->row_array();
			$data['data'][$i]['task_file_answer'] = ($task) ? $task['task_file'] : '';
			$data['data'][$i]['task_submit'] = ($task) ? $task['task_submit'] : '';
			$i++;
		}

		$data['total_records'] 	= $this->db->where('teacher_id', $get['teacher_id'])->get('task')->num_rows();
		$data['total_pages'] 	= ceil($data['total_records'] / $limit);

		// create json header	
		header('Content-Type: application/json');
		echo json_encode($data);
	}
}
