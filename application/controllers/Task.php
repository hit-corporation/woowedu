<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Task extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model('model_task');
		
		if (!isset($_SESSION['username'])) redirect('auth/login');
	}

	public function index() {
 
		
		$data['mapelop'] = $this->model_task->get_mapel();
		$this->load->view('header');
		$this->load->view('task/index', $data);
		$this->load->view('footer');
	}
	
	public function getlist()
	{
		$username 	= $this->session->userdata('username');
		$user_level 				= $this->session->userdata('user_level');
		$teacher_id 				= $this->session->userdata('teacher_id');
		$page 		= isset($_GET['page']) ? (int)$_GET['page'] : 1;
		$limit 		= isset($_GET['limit']) ? (int)$_GET['limit'] : 3;
		$mapel		= $_GET['mapel'];
		$startDate	= $_GET['startDate'];
		$endDate	= $_GET['endDate'];

		$page = ($page - 1) * $limit;

		$data['user_level'] 	= $user_level;
		
		if($user_level == 3 ){
			$data['task'] 			= $this->model_task->get_teacher_task($limit, $page, $mapel, $startDate, $endDate);
			$data['total_records'] 	= $this->model_task->get_teacher_total_task($mapel, $startDate, $endDate);			
		}elseif($user_level == 4 ){	
			$data['task'] 			= $this->model_task->get_student_task($limit, $page, $mapel, $startDate, $endDate);
			$data['total_records'] 	= $this->model_task->get_student_total_task($mapel, $startDate, $endDate);		
		}
		

		$data['total_pages'] 	= ceil($data['total_records'] / $limit);

		// create json header	
		header('Content-Type: application/json');
		echo json_encode($data);		
	}

	public function detail($id = ''){
		if(!$id) redirect('dashboard');


		$data['task'] = $this->model_task->get_tasks_detail($id);
		
		$user_level 				= $this->session->userdata('user_level');
		
		if($user_level == 4 ){		
		$student = $this->db->where('nis', $this->session->userdata('username'))->get('student')->row_array();
		$data['task_student'] = $this->db->where('student_id', $student['student_id'])->where('task_id', $id)->order_by('ts_id', 'desc')->get('task_student')->row_array();
		}
		
		$this->load->view('header');
		$this->load->view('task/detail', $data);
		$this->load->view('footer');
	}

	public function store_file(){
		$this->load->helper('file');
		$post = $this->input->post();
		$student = $this->db->where('nis', $this->session->userdata('username'))->get('student')->row_array();

		$dir = './assets/files/student_task/'.$student['class_id'];

		if (!file_exists($dir)) {
			mkdir($dir, 0777, true);
		}

		$config['upload_path'] = $dir;
		$config['allowed_types']        = 'gif|jpg|jpeg|png|pdf|docx|doc|xls|xlsx';
		$config['max_size']             = 10000;
		$config['encrypt_name']         = true;

		$this->load->library('upload', $config);

		if(!$this->upload->do_upload('formFile')){
			// upload fails
			$resp = [
				'success' => false, 
				'message' => json_encode($this->upload->display_errors()) 
			];
			$this->session->set_flashdata('simpan', $resp);
			redirect(base_url('task/detail/'.$post['task_id']));
		}else{
			// upload success
			$upload_data = $this->upload->data();
			
			// insert task student
			$data = [
				'student_id' 	=> $student['student_id'],
				'task_id' 		=> $post['task_id'],
				'task_note'		=> $post['task_note'],
				'task_file'		=> $upload_data['file_name'],
				'task_submit'	=> date('Y-m-d H:i:s',time())
			];
			$insert = $this->db->insert('task_student', $data);
			if($insert){
				$resp = ['success'=>true, 'message'=>'Data berhasil disimpan'];
			}else{
				$resp = ['success'=>false, 'message'=>'Data gagal disimpan'];
			}

			$this->session->set_flashdata('simpan', $resp);
			redirect(base_url('task/detail/'.$post['task_id']));
		}
	}
	
	
	public function save(){
		$this->load->helper('file');
		$post = $this->input->post(); 
		$teacher_id = $this->session->userdata('teacher_id');
		$dir = './assets/files/teacher_task/'.$teacher_id;

		if (!file_exists($dir)) {
			mkdir($dir, 0777, true);
		}

		$config['upload_path'] = $dir;
		$config['allowed_types']        = 'gif|jpg|jpeg|png|pdf|docx|doc|xls|xlsx';
		$config['max_size']             = 10000;
		$config['encrypt_name']         = true;

		$this->load->library('upload', $config);

		if(!$this->upload->do_upload('lampiran')){
			// upload fails
			$resp = [
				'success' => false, 
				'message' => json_encode($this->upload->display_errors()) 
			];
			$this->session->set_flashdata('simpan', $resp);
			redirect(base_url('task/create/'.$post['task_id']));
		}else{
			// upload success
			$upload_data = $this->upload->data();
			
			// insert task student
			$data = [
				'teacher_id' 	=> $teacher_id,
				'available_date' 		=> $post['tanggal_start'].' '.$post['jamstart'],
				'due_date' 		=> $post['tanggal_end'].' '.$post['jamend'],
				'note'		=> $post['keterangan'],
				'subject_id'		=> $post['select_mapel'],
				'task_file'		=> $upload_data['file_name'] 
			];
			$insert = $this->db->insert('task', $data);
			if($insert){
				$resp = ['success'=>true, 'message'=>'Data berhasil disimpan'];
			}else{
				$resp = ['success'=>false, 'message'=>'Data gagal disimpan'];
			}

			$this->session->set_flashdata('simpan', $resp);
			redirect(base_url('task'));
		}
	}
	 
	
	public function create($id = ''){
		$post = $this->input->post();
		$data['mapelop'] = $this->model_task->get_mapel();

 
		if($id != '') $data['data'] = $this->db->where('task_id', $id)->get('task')->row_array();

		$this->load->view('header');
		$this->load->view('task/create', $data);
		$this->load->view('footer');
	}	
	public function delete(){
		$post = $this->input->post();
		$delete = $this->db->where('task_id', $post['id'])->delete('task');

		$res = ($delete) ?  ['success'=>true, 'message'=>'Data berhasil dihapus!'] : ['success'=>false, 'message'=>'Data gagal dihapus!'];
		header('Content-Type: application/json');
		echo json_encode($res);
	}

}
