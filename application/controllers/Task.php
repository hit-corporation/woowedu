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
		$data = [];
		$this->load->view('header');
		$this->load->view('task/index', $data);
		$this->load->view('footer');
	}

	public function detail($id = ''){
		if(!$id) redirect('dashboard');

		$student = $this->db->where('nis', $this->session->userdata('username'))->get('student')->row_array();

		$data['task'] = $this->model_task->get_tasks_detail($id);
		$data['task_student'] = $this->db->where('student_id', $student['student_id'])->where('task_id', $id)->order_by('ts_id', 'desc')->get('task_student')->row_array();

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
		$config['allowed_types']        = 'gif|jpg|jpeg|png|pdf';
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

}
