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

		$teacher_id = $_SESSION['teacher_id'];

		$data['mapels'] = $this->db->where('teacher_id', $teacher_id)->join('subject s', 's.subject_id=st.subject_id')->get('subject_teacher st')->result_array();

		$this->load->view('header', $header);
		$this->load->view('asesmen/index', $data);
		$this->load->view('footer');
	}

}
