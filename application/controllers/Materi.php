<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Materi extends CI_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model('model_mapel');
		$this->load->helper('assets');
	}

	public function index()
	{
		$datamodel = 'table';
		if(!empty($this->input->get('mode')) && in_array($this->input->get('mode'), ['table', 'grid']))
			$datamodel = $this->input->get('data_model');

		$data['page_js'][] = ['path' => 'https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js'];
		$data['page_js'][] = ['path' => 'https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js'];
		$data['page_js'][] = ['path' => 'assets/libs/sweetalert2/sweetalert2.min.js'];
		if($datamodel == 'grid')
			$data['page_js'][] = ['path' => 'assets/js/materi_grid.js', 'defer' => true, 'type' => 'module'];
		else
			$data['page_js'][] = ['path' => 'assets/js/materi_table.js', 'defer' => true];

		$header['add_css'] = [
			'https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css',
			'assets/libs/sweetalert2/sweetalert2.min.css',
			'assets/node_modules/pagination-system/dist/pagination-system.min.css',
			'assets/css/materi.css'

		];

		$data['datamodel'] = $datamodel;

		$this->load->view('header', $header);
		$this->load->view('mapel/index', $data);
		$this->load->view('footer');
	}

	/**
	 * list of subject
	 *
	 * @return void
	 */
	public function list(): void {
		$draw	= $this->input->get('draw') ?? '';
		$limit  = $this->input->get('length');
		$offset = $this->input->get('start');
		$count  = $this->db->count_all_results('materi');
		$filter = $this->input->get('columns');
		$data   = $this->model_mapel->get_all($limit, $offset, $filter);


		foreach ($data as $key => $val) {
			$dir = str_replace('\application\controllers','',__DIR__).'/assets/files/materi/'; // get direktory file
			if(file_exists($dir.$val['materi_file'])){
				$data[$key]['file_size'] = filesize($dir.$val['materi_file']);
			}else{
				$data[$key]['file_size'] = 0;
			}
		}

		$json = [
			'draw'				=> $draw,
			'data' 		   		=> $data,
			'recordsTotal' 		=> $count,
			'recordsFiltered'	=> $this->model_mapel->num_all($filter)
		];

		header('Content-Type: application/json');
		echo json_encode($json, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_QUOT);
	}

	/**
	 * list f subjects
	 *
	 * @return void
	 */
	public function getAll(): void {
        $draw = $this->input->post('draw', TRUE);
        $limit = $this->input->post('length', TRUE);
        $offset = $this->input->post('start', TRUE);
        $filters = $this->input->post('columns');

        $data = $this->model_subject->getAll($filters, $limit, $offset);
        
        $resp = [
            'draw' => $draw,
            'data' => $data,
            'recordsTotal' => $this->db->count_all_results('subject'),
            'recordsFiltered' => $this->model_subject->countAll($filters)
        ];

        echo json_encode($resp, JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);
    }

	public function getAllMateri(){
		$materies = $this->db->join('subject s', 's.subject_id = m.subject_id')->get('materi m')->result_array();

		header('Content-Type: application/json');
		echo json_encode($materies, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_QUOT);
	}

	public function materi_saya(){

		$header['add_css'] = [
			'https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css',
			'assets/node_modules/sweetalert2/dist/sweetalert2.min.css',
			'assets/node_modules/pagination-system/dist/pagination-system.min.css',
		];

		$data['page_js'][] = ['path' => 'https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js'];
		$data['page_js'][] = ['path' => 'https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js'];
		$data['page_js'][] = ['path' => 'assets/node_modules/sweetalert2/dist/sweetalert2.min.js'];
		$data['page_js'][] = ['path' => 'https://kit.fontawesome.com/b377b34fd7.js'];
		$data['page_js'][] = ['path' => 'assets/js/_materi_saya.js'];

		$teacher_id = $_SESSION['teacher_id'];

		$data['mapels'] = $this->db->where('teacher_id', $teacher_id)->join('subject s', 's.subject_id=st.subject_id')->get('subject_teacher st')->result_array();

		$this->load->view('header', $header);
		$this->load->view('mapel/materi_saya', $data);
		$this->load->view('footer');
	}

	/**
	 * GET list_materi_saya
	 */
	public function list_materi_saya(): void {
		$draw	= $this->input->get('draw') ?? '';
		$limit  = $this->input->get('length');
		$offset = $this->input->get('start');
		$count  = $this->db->count_all_results('materi');
		$filter = $this->input->get('columns');

		$filter[0]['search']['value'] = isset($filter[0]['search']['value']) ? $_SESSION['teacher_id'] : null;
		
		$data   = $this->model_mapel->get_all_materi_saya($limit, $offset, $filter);


		foreach ($data as $key => $val) {
			$dir = str_replace('\application\controllers','',__DIR__).'/assets/files/materi/'; // get direktory file
			if(file_exists($dir.$val['materi_file'])){
				$data[$key]['file_size'] = filesize($dir.$val['materi_file']);
			}else{
				$data[$key]['file_size'] = 0;
			}
		}

		$json = [
			'draw'				=> $draw,
			'data' 		   		=> $data,
			'recordsTotal' 		=> count($data),
			'recordsFiltered'	=> $this->model_mapel->num_all_materi_saya($filter)
		];

		header('Content-Type: application/json');
		echo json_encode($json, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_QUOT);
	}

	public function store_materi_saya(){
		$post 		= $this->input->post();
		// save materi ==========================================================
		if($post['materi_id'] == ''){
			$title		= trim($post['input_materi']);
			$materi_id	= ($post['materi_id'] != '') ? $post['materi_id'] : null;
			$file_name 	= $_FILES['input_file']['name'];

			$subject = $this->db->where('subject_id', $post['subject_id'])->get('subject')->row_array();

			// upload file
			$dir = 'assets/files/materi/';
			if(!is_dir($dir))  @mkdir($dir, 0777);
			$filename = str_replace(' ', '_', strtolower($subject['subject_name'])).'-'.str_replace(' ', '_', strtolower($post['input_materi']));
			$ext = pathinfo(basename($file_name), PATHINFO_EXTENSION);
			$move = move_uploaded_file($_FILES['input_file']['tmp_name'], FCPATH.$dir.$filename.'.'.$ext);

			if(!$move){
				$message = ['success' => false, 'message' => 'File gagal di upload !!!'];
			}else{ 

				// cek materi sudah pernah ada atau belum		
				$cek = $this->db->where('materi_id', $materi_id)->get('materi')->num_rows();
				
				if($cek > 0){
					// edit
					$data = [
						'title' 	=> $title,
						'edit_at' 	=> date('Y-m-d H:i:s'),
					];
					$this->db->update('materi', $data, ['subject_id' => $post['subject_id']]);
					$message = ['success' => true, 'message' => 'Data berhasil di update !!!'];
				}else{
					// save
					$data = [
						'subject_id' 		=> $post['subject_id'],
						'teacher_id'		=> $_SESSION['teacher_id'],
						'title' 			=> trim($title),
						'available_date' 	=> date('Y-m-d H:i:s'),
						'edit_at'			=> date('Y-m-d H:i:s'),
						'materi_file'       => $filename.'.'.$ext,
					];
					$this->db->insert('materi', $data);
					$message = ['success' => true, 'message' => 'Data berhasil di simpan !!!'];
				}
			}
		}else{
		// update materi ========================================================
			$update = $this->db->update('materi', ['title' => trim($post['input_materi'])], ['materi_id' => $post['materi_id']]);
			
			if($update){
				$message = ['success' => true, 'message' => 'Data berhasil di Update !!!'];
			}else{
				$message = ['success' => false, 'message' => 'Data gagal di Update !!!'];
			}
		}
		
		$this->session->set_flashdata('success', $message);
		redirect('materi/materi_saya');

	}

	/**
	 * view modal relasi
	 */
	public function relasi(){
		$this->load->view('mapel/relasi', true);
	}

	public function set_relasi(){
		$post = $this->input->post();
		$materi_id = $post['a_materi_id'];

		$this->db->delete('materi_kelas', ['materi_id' => $materi_id]);

		foreach ($post['teacher_class'] as $val) {
			$insert = $this->db->insert('materi_kelas', ['class_id'=>$val, 'materi_id'=>$materi_id]);
		}

		if(!$insert){
			$data = ['success'=>false, 'message'=>'Data gagal di simpan!'];
		}else{
			$data = ['success'=>true, 'message'=>'Data berhasil di simpan!'];
		}
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($data);
	}

	/**
	 * Delete, Post ID
	 */
	function delete(){
		$post = $this->input->post();

		// cek di materi_kelas sudah ada atau belum datanya
		$materi_kelas = $this->db->where('materi_id', $post['materi_id'])->get('materi_kelas')->num_rows();
		if($materi_kelas > 0){
			$this->db->delete('materi_kelas', ['materi_id' => $post['materi_id']]);
		}

		$delete = $this->db->delete('materi', ['materi_id' => $post['materi_id']]);

		if($delete){
			$res = ['success' => true, 'message' => 'Data berhasil di hapus !!!'];
		}else{
			$res = ['success' => false, 'message' => 'Data gagal di hapus !!!'];
		}
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($res);
	}

	/**
	 * Index Materi Sekolah
	 */
	public function materi_sekolah(){
		$datamodel = 'table';
		if(!empty($this->input->get('mode')) && in_array($this->input->get('mode'), ['table', 'grid']))
			$datamodel = $this->input->get('data_model');

		$data['page_js'][] = ['path' => 'https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js'];
		$data['page_js'][] = ['path' => 'https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js'];
		$data['page_js'][] = ['path' => 'assets/libs/sweetalert2/sweetalert2.min.js'];
		if($datamodel == 'grid')
			$data['page_js'][] = ['path' => 'assets/js/materi_grid.js', 'defer' => true, 'type' => 'module'];
		else
			$data['page_js'][] = ['path' => 'assets/js/materi_table.js', 'defer' => true];

		$header['add_css'] = [
			'https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css',
			'assets/libs/sweetalert2/sweetalert2.min.css',
			'assets/node_modules/pagination-system/dist/pagination-system.min.css',
			'assets/css/materi.css'

		];

		$data['datamodel'] = $datamodel;

		$this->load->view('header', $header);
		$this->load->view('mapel/materi_sekolah', $data);
		$this->load->view('footer');
	}

	/**
	 * Materi Global
	 */
	public function materi_global(){
		$datamodel = 'table';
		if(!empty($this->input->get('mode')) && in_array($this->input->get('mode'), ['table', 'grid']))
			$datamodel = $this->input->get('data_model');

		$data['page_js'][] = ['path' => 'https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js'];
		$data['page_js'][] = ['path' => 'https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js'];
		$data['page_js'][] = ['path' => 'assets/libs/sweetalert2/sweetalert2.min.js'];
		$data['page_js'][] = ['path' => 'assets/node_modules/moment/moment.js'];
		if($datamodel == 'grid')
			$data['page_js'][] = ['path' => 'assets/js/materi_grid.js', 'defer' => true, 'type' => 'module'];
		else
			$data['page_js'][] = ['path' => 'assets/js/_materi_global.js', 'defer' => true];

		$header['add_css'] = [
			'https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css',
			'assets/libs/sweetalert2/sweetalert2.min.css',
			'assets/node_modules/pagination-system/dist/pagination-system.min.css',
			'assets/css/materi.css'

		];

		$data['datamodel'] = $datamodel;

		$this->load->view('header', $header);
		$this->load->view('mapel/materi_global', $data);
		$this->load->view('footer');
	}

	/**
	 * GET list_materi_saya
	 */
	public function list_materi_global(): void {
		$draw	= $this->input->get('draw') ?? '';
		$limit  = $this->input->get('length');
		$offset = $this->input->get('start');
		$count  = $this->db->count_all_results('materi');
		$filter = $this->input->get('columns');
		
		$data   = $this->model_mapel->get_all_materi_global($limit, $offset, $filter);

		foreach ($data as $key => $val) {
			$dir = str_replace('\application\controllers','',__DIR__).'/assets/files/materi/'; // get direktory file
			if(file_exists($dir.$val['materi_file'])){
				$data[$key]['file_size'] = filesize($dir.$val['materi_file']);
			}else{
				$data[$key]['file_size'] = 0;
			}
		}

		$json = [
			'draw'				=> $draw,
			'data' 		   		=> $data,
			'recordsTotal' 		=> count($data),
			'recordsFiltered'	=> $this->model_mapel->num_all_materi_global($filter)
		];

		header('Content-Type: application/json');
		echo json_encode($json, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_QUOT);
	}
}
