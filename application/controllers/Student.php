<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Student extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model('model_student');
		$this->load->model('model_sesi');
		
		$this->load->library('xlsxwriter');
		
		if (!isset($_SESSION['username'])) redirect('auth/login');
	}

	public function index(){
		$kelas = $this->db->get('kelas')->result_array();
		foreach ($kelas as $key => $value) {
			$rowStudent = $this->db->where('class_id', $value['class_id'])->get('student')->num_rows();
			$kelas[$key]['value'] = ($rowStudent) ? $rowStudent : 0;
		}

		$this->load->view('header');
		$this->load->view('student/index', ['kelas'=>$kelas]);
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

		$examRataRata = $this->model_student->average_exam_score($post['student_id'], $post['start'], $post['end'])['exam_total_nilai'];
		$data['average_exam_score'] = ($examRataRata) ? $examRataRata : 0;

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function get_task(){
		$get = $this->input->get();
		$page 		= isset($get['start']) ? (int)$get['start'] : 1;
		$limit 		= isset($get['length']) ? (int)$get['length'] : 3;

		
		// $page = $page * $limit;

		$data['data']	= $this->model_student->get_task($limit, $page, $get['student_id']);
		$i = 0;
		foreach($data['data'] as $key => $val){
			$task_student = $this->db->where('task_id', $val['task_id'])->where('student_id', $get['student_id'])->get('task_student')->row_array();
			$data['data'][$i]['task_file_answer'] = ($task_student) ? $task_student['task_file'] : '';
			$data['data'][$i]['task_submit'] = ($task_student) ? $task_student['task_submit'] : '';
			$data['data'][$i]['task_note'] = ($task_student) ? $task_student['task_note'] : '';
			$i++;
		}

		$data['draw'] = $get['draw'];
		$data['recordsTotal'] 	= $this->model_student->get_total_task($get['student_id']);
		$data['recordsFiltered'] 	= $this->model_student->get_total_task($get['student_id']);
		$data['total_pages'] 	= ceil($data['recordsTotal'] / $limit);

		// create json header	
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function get_exam(){
		$get = $this->input->get();
		$page 		= isset($get['start']) ? (int)$get['start'] : 0;
		$limit 		= isset($get['length']) ? (int)$get['length'] : 10;

		$data['data']	= $this->model_student->get_exam($limit, $page, $get['student_id']);

		foreach($data['data'] as $key => $val){
			$exam_student = $this->db->where('exam_id', $val['exam_id'])->where('student_id', $get['student_id'])->get('exam_student')->row_array();
			$data['data'][$key]['exam_total_nilai'] = ($exam_student) ? $exam_student['exam_total_nilai'] : '';
			$data['data'][$key]['exam_submit'] = ($exam_student) ? $exam_student['exam_submit'] : '';
		}

		$data['draw'] = $get['draw'];
		$data['recordsTotal'] 	= $this->model_student->get_total_row_exam($get['student_id']);
		$data['recordsFiltered'] 	= $this->model_student->get_total_row_exam($get['student_id']);
		$data['total_pages'] 	= ceil($data['recordsTotal'] / $limit);

		// create json header	
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function download(){
		$post = $this->input->post();

		$kelas 	= isset($post['kelas']) ? $post['kelas'] : null;
		$nama 	= isset($post['nama']) ? ($post['nama']) : null;

		$students = $this->model_student->download($kelas, $nama);

		$header2 = array(
			'NIS'			=>'string',
			'Nama Murid'	=>'string',
			'Jenis Kelamin'	=>'string',
			'Alamat'		=>'string',
			'Telp'			=>'string',
			'Email'			=>'string',
		);

		// FORMAT FILE NAME = LIST_DATA_SISWA_TIMESTAMP
		$name = 'assets\files\student\LIST_DATA_SISWA_'.date('Ymd-His', time()).'.xlsx';
		
		$writer = new xlsxwriter();

		$header_style = array(
			'widths'		=>array(30,40,30,50,20,10,30),
			'font'			=>'Arial',
			'font-size'		=>12, 
			'wrap_text'		=>true, 
			'border'		=>'left,right,top,bottom',
			'border-style'	=>'medium', 
			'border-color'	=>'#4A8C42', 
			'valign'		=>'top', 
			'color'			=>'#FFFFFF', 
			'fill'			=>'#4A8C42'
		);

		$writer->writeSheetHeader('Sheet1', $header2, $header_style);	
		// $writer->writeSheetRow('Sheet1', ['Data Siswa: ']);

		foreach($students as $row)
			$writer->writeSheetRow('Sheet1', $row );
		
		$writer->writeToFile($name);
		
		header("Content-Description: File Transfer"); 
		header("Content-Type: application/octet-stream"); 
		header("Content-Disposition: attachment; filename=\"".basename($name)."\""); 

		readfile($name);
		exit(); 

	}

	public function get_history_book($id = ''){
		$get = $this->input->get();
		$student 	= $this->db->where('student_id', $get['student_id'])->get('student')->row_array();
		$user 		= $this->db->where('username', $student['nis'])->get('users')->row_array();

		$page 		= isset($get['start']) ? (int)$get['start'] : 1;
		$limit 		= isset($get['length']) ? (int)$get['length'] : 3;

		$filter['user_id'] = $user['userid'];

		$books = $this->model_student->get_history_book($limit, $page, $filter);
		// foreach ($books as $key => $val) {
		// 	$ebook = $this->db->where('id', $val['book_id'])->get('ebooks')->row_array();
		// 	$books[$key]['book_code'] = $ebook['book_code']; 
		// 	$books[$key]['title'] = $ebook['title']; 
		// 	$books[$key]['cover_img'] = $ebook['cover_img']; 
		// 	$books[$key]['author'] = $ebook['author']; 
		// 	$books[$key]['publish_year'] = $ebook['publish_year']; 
		// 	$books[$key]['description'] = $ebook['description']; 
		// }

		$data['data'] 				= $books;
		$data['draw'] 				= $get['draw'];
		$data['recordsTotal'] 		= $this->model_student->get_history_book_total($filter);
		$data['recordsFiltered'] 	= $data['recordsTotal'];
		$data['total_pages'] 		= ceil($data['recordsTotal'] / $limit);

		// create json header	
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function book_detail(){
		$get = $this->input->get();
		$data = $this->db->where('ebooks.id', $get['book_id'])
					->join('publishers', 'publishers.id = ebooks.publisher_id')
					->get('ebooks')->row_array();
		$res = ['success'=>true, 'data'=>$data];
		header('Content-Type: application/json');
		echo json_encode($res);
	}

	public function sesi_load_data($id = ''){
		$params['sdate']	= $_GET['start'];
		$params['edate']	= $_GET['end'];

		$class_id = '';

		// JIKA USER LOGIN SEBAGAI MURID
		if($this->session->userdata('user_level') == 4){
			$class_id = $this->session->userdata('class_id');
			$teachers = $this->db->where('class_id', $class_id)->get('class_teacher')->result_array();
			$teacher_ids = [];
			foreach ($teachers as $val) {
				$teacher_ids[] = $val['teacher_id'];
			}
		}else{
			// JIKA USER LOGIN SEBAGAI GURU
			$teacher = $this->db->where('nik', $this->session->userdata('username'))->get('teacher')->row();
			$teacher_ids[] = $teacher->teacher_id;
		}

		$params['teacher_id'] = $teacher_ids;
		
		$sesi = $this->model_sesi->data_sesi_student($params);

		$list = [];
		foreach($sesi->result() as $data) {
			$tanggal =  $data->sesi_date;	 
			$list[] = [
				'id' 			=> $data->sesi_id,
				'title' 		=> $data->sesi_title,
				'teacher' 		=> $data->teacher_name,
				'subject_name'	=> $data->subject_name,
				'start' 		=> $data->sesi_date.'T'.$data->sesi_jam_start,
				'end' 			=> $data->sesi_date.'T'.$data->sesi_jam_end  
			];
		}	
		if($list == null) $list[] = array(0);
		echo json_encode($list);			
		exit;
	}
}
