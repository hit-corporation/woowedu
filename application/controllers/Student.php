<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Student extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model('model_student');
		
		if (!isset($_SESSION['username'])) redirect('auth/login');
	}

	public function index(){
		$this->load->view('header');
		$this->load->view('student/index');
		$this->load->view('footer');
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
		$data['students'] 		= $this->model_student->get_history($limit, $page, $filter);
		$data['total_records'] 	= $this->model_student->get_total_history($filter);
		$data['total_pages'] 	= ceil($data['total_records'] / $limit);

		// create json header	
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function get_class(){
		$data = $this->db->get('kelas')->result_array();

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function get_total(){
		$data['total_row'] = $this->db->get('student')->num_rows();

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function detail($id = ''){
		if(!$id) redirect(base_url('student'));

		$data['detail']	= $this->db->where('student_id', $id)->get('student')->row_array();
		$dataUser = $this->db->where('username', $data['detail']['nis'])->get('users')->row_array();
		$data['detail']['photo'] = ($dataUser) ? $dataUser['photo'] : 'user.png';
		$data['detail']['parent_name'] = ($data['detail']['parent_id']) ? $this->db->where('parent_id', $data['detail']['parent_id'])->get('parent')->row_array()['name'] : '';
		$data['detail']['nama_sekolah'] = $this->db->where('sekolah_id', $data['detail']['sekolah_id'])->get('sekolah')->row_array()['sekolah_nama'];

		$this->load->view('header');
		$this->load->view('student/detail', $data);
		$this->load->view('footer');
	}

	public function get_summary(){
		$post 				= $this->input->post();
		$class_id 			= $this->db->where('student_id', $post['student_id'])->get('student')->row_array()['class_id'];
		$data['total_exam']	= $this->model_student->get_total_exam($class_id, $post['start'], $post['end']);
		$data['average_exam_score'] = $this->model_student->average_exam_score($post['student_id'], $post['start'], $post['end'])['exam_total_nilai'];

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function get_task(){
		$get = $this->input->get();
		$page 		= isset($get['page']) ? (int)$get['page'] : 1;
		$limit 		= isset($get['limit']) ? (int)$get['limit'] : 3;

		$page = ($page - 1) * $limit;

		$data['data']	= $this->model_student->get_task($limit, $page, $get['student_id']);
		$i = 0;
		foreach($data['data'] as $key => $val){
			$task_student = $this->db->where('task_id', $val['task_id'])->where('student_id', $get['student_id'])->get('task_student')->row_array();
			$data['data'][$i]['task_file_answer'] = ($task_student) ? $task_student['task_file'] : '';
			$data['data'][$i]['task_submit'] = ($task_student) ? $task_student['task_submit'] : '';
			$i++;
		}

		$data['total_records'] 	= $this->model_student->get_total_task($get['student_id']);
		$data['total_pages'] 	= ceil($data['total_records'] / $limit);

		// create json header	
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function get_exam(){
		$get = $this->input->get();
		$page 		= isset($get['page']) ? (int)$get['page'] : 1;
		$limit 		= isset($get['limit']) ? (int)$get['limit'] : 3;

		$page = ($page - 1) * $limit;

		$data['data']	= $this->model_student->get_exam($limit, $page, $get['student_id']);

		$i = 0;
		foreach($data['data'] as $key => $val){
			$exam_student = $this->db->where('exam_id', $val['exam_id'])->where('student_id', $get['student_id'])->get('exam_student')->row_array();
			$data['data'][$i]['exam_total_nilai'] = ($exam_student) ? $exam_student['exam_total_nilai'] : '';
			$data['data'][$i]['exam_submit'] = ($exam_student) ? $exam_student['exam_submit'] : '';
			$i++;
		}

		$data['total_records'] 	= $this->model_student->get_total_row_exam($get['student_id']);
		$data['total_pages'] 	= ceil($data['total_records'] / $limit);

		// create json header	
		header('Content-Type: application/json');
		echo json_encode($data);
	}

}
