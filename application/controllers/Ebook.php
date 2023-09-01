<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ebook extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model('model_ebook');
		
		if (!isset($_SESSION['username'])) redirect('auth/login');
	}

	public function index(){

		$header['add_js'] = [
			'libs/htmx.min.js'
		];

		$header['add_css'] = [
			'assets/node_modules/pagination-system/dist/pagination-system.min.css',
			'assets/css/ebook.css'
		];

		$this->load->view('header', $header);
		$this->load->view('ebook/index');
		$this->load->view('footer');
	}

	/**
	 * Detail of a book
	 *
	 * @param string $param
	 * @return void
	 */
	public function detail(string $param): void {

		$body['book'] = $this->model_ebook->getByCode($param);

		$this->load->view('header');
		$this->load->view('ebook/detail', $body);
		$this->load->view('footer');
	}

	/**
	 * Lists Books 
	 *
	 * @return void
	 */
	public function list(): void {

		$limit  = $this->input->get('count');
		$page 	= $this->input->get('page');
		$total	= $this->db->count_all_results('ebooks');
		$offset = $page == 1 ? $page * $limit : ($page - 1) * ($limit + 1); 
		
		$data = $this->model_ebook->list($limit, $offset);

		$json = [
			'data' 		=> $data,
			'totalData' => $total,
		];

		header('X-Total-Count: '.$json['totalData']);
		header('Content-Type: application/json');
		echo json_encode($json, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
	}

	/**
	 * Start read a book
	 *
	 * @return void
	 */
	public function openBook(): void {
		$transcode = strtoupper(bin2hex(random_bytes(8)));
	}


	public function history(){
		$username 	= $this->session->userdata('username');
		$user_level = $this->db->where('username', $username)->get('users')->row_array()['user_level'];
		$page 		= isset($_GET['page']) ? (int)$_GET['page'] : 1;
		$limit 		= isset($_GET['limit']) ? (int)$_GET['limit'] : 3;
		$title		= $_GET['title'];
		$startDate	= $_GET['startDate'];
		$endDate	= $_GET['endDate'];

		$page = ($page - 1) * $limit;

		$data['user_level'] 	= $user_level;
		$data['news'] 			= $this->model_news->get_history($limit, $page, $title, $startDate, $endDate);
		$data['total_records'] 	= $this->model_news->get_total_history($title, $startDate, $endDate);
		$data['total_pages'] 	= ceil($data['total_records'] / $limit);

		// create json header	
		header('Content-Type: application/json');
		echo json_encode($data);
	}

}
