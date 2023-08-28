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

}
