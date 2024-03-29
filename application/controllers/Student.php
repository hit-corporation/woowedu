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
		// $page 		= isset($get['page']) ? (int)$get['page'] : 1;
		$page 		= $get['start'];
		$limit 		= $get['length'];
		$filter['namaSiswa'] = $get['columns'][0]['search']['value'];
		$filter['kelas'] = $get['columns'][1]['search']['value'];
		// $page = ($page - 1) * $limit;

		$data['draw']			= $get['draw'];
		$data['user_level'] 	= $user_level;
		$data['data'] 			= $this->model_student->get_history($limit, $page, $filter);
		$data['recordsTotal'] 	= $this->model_student->get_total_history($filter);
		$data['recordsFiltered'] = $this->model_student->get_total_history($filter);

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

		$data['detail']	= $this->db->where('student_id', $id)->join('kelas', 'kelas.class_id = student.class_id')->get('student')->row_array();
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
		$filter['start_dt']	= $post['start'];
		$filter['end_dt']	= $post['end'];
		$data['total_task']	= $this->model_student->get_total_task($post['student_id'], $filter);
		$data['total_task_submit'] = $this->model_student->get_total_task_submit($post['student_id'], $filter);
		
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

		$filter['user_id'] = ($user) ? $user['userid'] : [];

		if($filter['user_id']){
			$books = $this->model_student->get_history_book($limit, $page, $filter);
		}else{
			$books = [];
		}

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
		$data['recordsTotal'] 		= ($books) ? $this->model_student->get_history_book_total($filter) : 0;
		$data['recordsFiltered'] 	= $data['recordsTotal'];
		// $data['total_pages'] 		= ceil($data['recordsTotal'] / $limit);

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
			// $teachers = $this->db->where('class_id', $class_id)->get('class_teacher')->result_array();
			// $teacher_ids = [];
			// foreach ($teachers as $val) {
			// 	$teacher_ids[] = $val['teacher_id'];
			// }
		} else{
			// JIKA USER LOGIN SEBAGAI GURU
			// $teacher = $this->db->where('nik', $this->session->userdata('username'))->get('teacher')->row();
			// $teacher_ids[] = $teacher->teacher_id;
			$student = $this->db->where('student_id', $_GET['student_id'])->get('student')->row_array();
			$class_id = $student['class_id'];
		}

		// $params['teacher_id'] = $teacher_ids;
		$params['class_id'] = $class_id;
		
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
				'end' 			=> $data->sesi_date.'T'.$data->sesi_jam_end,
				'sesi_note'		=> strip_tags($data->sesi_note)
			];
		}	
		if($list == null) $list[] = array(0);
		echo json_encode($list);			
		exit;
	}
	
	public function save_exam()
	{
	//	var_dump($_POST);exit;
		$_total_soal = $_POST['total_soal'];
		if($_total_soal>0){
			
			$total_nilai = 0;
			
			for($i=0;$i<$_total_soal;$i++){ 
				$no = $i+1;
				$jawaban = strtolower($_POST['correct_answer'.$no]);//$this->Incer_model->get_soal_answer($_POST['soal_id'.$no]);
				if($_POST['answer'.$no]==$jawaban)$total_nilai++;
				
				$this->db->where('student_id', $_SESSION['student_id']); 
				$this->db->where('soal_id', $_POST['soal_id'.$no]);
        $this->db->delete('exam_answer');
					
				$data = [  
					'student_id' => $_SESSION['student_id'], 
					'soal_id' => $_POST['soal_id'.$no] ,
					'class_id' => $_SESSION['class_id'],
					'exam_answer' => $_POST['answer'.$no],
					'correct_answer' => $_POST['correct_answer'.$no],
					'exam_submit' =>  date('Y-m-d H:i:s'),
					'create_by' => $_SESSION['username']
				];
				
				$this->db->insert('exam_answer', $data);
			}
			
			$this->db->where('student_id', $_SESSION['student_id']); 
			$this->db->where('materi_id', $_POST['materi_id']);
			$this->db->where('category_id', $_POST['category_id']);
			$this->db->delete('soal_student');
				
			$data = [  
				'student_id' => $_SESSION['student_id'], 
				'materi_id' => $_POST['materi_id'], 
				'total_nilai' => $total_nilai,
				'create_by' => $_SESSION['username'],  
				'soal_submit' =>  date('Y-m-d H:i:s'),
				'category_id' =>  $_POST['category_id'],
				'ta_id' =>  $_SESSION['ta_id']
			];
			
			$this->db->insert('soal_student', $data);
				
		}
		
		echo "<script>
			alert('Submit berhasil');
			window.location.href = '". base_url()."student/detail/".$this->session->userdata['student_id']."
			</script>"; 			
		
	}
	
	public function submit_ujian($examid)
	{
		$data_exam = $this->model_student->get_exam_soal($examid);	 
 	
		$data['data_exam'] = $data_exam;						 				 
		$this->load->view('header');
		$this->load->view('student/submitexam', $data);
		$this->load->view('footer');		
	}

	public function get_materi_kelas(){
		$get = $this->input->get();
		$page 		= isset($get['start']) ? (int)$get['start'] : 0;
		$limit 		= isset($get['length']) ? (int)$get['length'] : 10;

		$data['data'] = $this->model_student->get_materi_guru($limit, $page, $get['student_id']);

		$data['draw'] 				= $get['draw'];
		$data['recordsTotal'] 		= $this->model_student->get_total_row_materi_guru($get['student_id']);
		$data['recordsFiltered'] 	= $this->model_student->get_total_row_materi_guru($get['student_id']);
		$data['total_pages'] 		= ceil($data['recordsTotal'] / $limit);

		// create json header	
		header('Content-Type: application/json');
		echo json_encode($data);

	}
}
