<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Asesmen extends CI_Controller {

	private $settings;

	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model(['model_asesmen','model_settings']);
		$this->load->helper('assets_helper');
		
		if (!isset($_SESSION['username'])) redirect('auth/login');

		$this->settings = json_decode(json_encode($this->model_settings->get_settings()), TRUE);
	}

	public function index(){

		$header['add_css'] = [
			'https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css',
			'assets/node_modules/sweetalert2/dist/sweetalert2.min.css',
			'assets/node_modules/pagination-system/dist/pagination-system.min.css',
		];

		$data['page_js'][] = ['path' => 'https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js'];
		$data['page_js'][] = ['path' => 'https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js'];
		$data['page_js'][] = ['path' => 'assets/node_modules/sweetalert2/dist/sweetalert2.min.js'];
		$data['page_js'][] = ['path' => 'https://kit.fontawesome.com/b377b34fd7.js'];
		$data['page_js'][] = ['path' => 'assets/js/_asesmen.js'];

		$teacher_id = isset($_SESSION['teacher_id']) ? $_SESSION['teacher_id'] : null;

		if(!is_null($teacher_id)){
			$data['mapels'] = $this->db->where('teacher_id', $teacher_id)->join('subject s', 's.subject_id=st.subject_id')->get('subject_teacher st')->result_array();
		}else{
			$classLevelId = $_SESSION['class_level_id'];
			$data['mapels'] = $this->db->where('class_level_id', $classLevelId)->get('subject')->result_array();
		}

		$data['classes']= $this->db->where('teacher_id', $teacher_id)->join('kelas k', 'k.class_id = ct.class_id')->get('class_teacher ct')->result_array();

		$this->load->view('header', $header);
		$this->load->view('asesmen/index', $data);
		$this->load->view('footer');
	}

	public function create_standar(){
		$header['add_css'] = [
			'https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css',
			'assets/node_modules/sweetalert2/dist/sweetalert2.min.css',
			'assets/node_modules/pagination-system/dist/pagination-system.min.css',
		];

		$data['page_js'][] = ['path' => 'https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js'];
		$data['page_js'][] = ['path' => 'https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js'];
		$data['page_js'][] = ['path' => 'assets/node_modules/sweetalert2/dist/sweetalert2.min.js'];
		$data['page_js'][] = ['path' => 'https://kit.fontawesome.com/b377b34fd7.js'];
		$data['page_js'][] = ['path' => 'assets/js/_create_asesmen_standar.js'];

		$teacher_id = isset($_SESSION['teacher_id']) ? $_SESSION['teacher_id'] : null;

		if(!is_null($teacher_id)){
			$data['mapels'] = $this->db->where('teacher_id', $teacher_id)->join('subject s', 's.subject_id=st.subject_id')->get('subject_teacher st')->result_array();
		}else{
			$classLevelId = $_SESSION['class_level_id'];
			$data['mapels'] = $this->db->where('class_level_id', $classLevelId)->get('subject')->result_array();
		}

		$data['classes']= $this->db->where('teacher_id', $teacher_id)->join('kelas k', 'k.class_id = ct.class_id')->get('class_teacher ct')->result_array();
		
		$this->load->view('header', $header);
		$this->load->view('asesmen/create_standar', $data);
		$this->load->view('footer');
	}

	public function getAll() {
        $draw = $this->input->get('draw');
        $limit = $this->input->get('length');
        $offset = $this->input->get('start');
        $filters = $this->input->get('columns');

        $data = $this->model_asesmen->getAllSoal($limit, $offset, $filters);

        $datas = [
            'draw' => $draw,
            'data' => $data,
            'recordsTotal' => $this->db->count_all_results('soal'),
            'recordsFiltered' => $this->model_asesmen->getAcountAllSoal($filters)
        ];

        echo json_encode($datas, JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);
    }

}
