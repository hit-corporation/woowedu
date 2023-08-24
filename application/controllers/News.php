<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class News extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model('model_news');
		
		if (!isset($_SESSION['username'])) redirect('auth/login');
	}

	public function index(){
		$this->load->view('header');
		$this->load->view('news/index');
		$this->load->view('footer');
	}

	public function create(){
		$this->load->view('header');
		$this->load->view('news/create');
		$this->load->view('footer');
	}

	public function history(){
		$page 		= isset($_GET['page']) ? (int)$_GET['page'] : 1;
		$limit 		= isset($_GET['limit']) ? (int)$_GET['limit'] : 3;
		$title		= $_GET['title'];
		$startDate	= $_GET['startDate'];
		$endDate	= $_GET['endDate'];

		$page = ($page - 1) * $limit;

		$data['news'] 	= $this->model_news->get_history($limit, $page, $title, $startDate, $endDate);
		$data['total_records'] = $this->model_news->get_total_history($title, $startDate, $endDate);
		$data['total_pages'] = ceil($data['total_records'] / $limit);

		// create json header	
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function detail($id = ''){
		if($id == '') redirect('news');

		$data['data'] = $this->db->where('id', $id)->get('news')->row_array();

		$this->load->view('header');
		$this->load->view('news/detail', $data);
		$this->load->view('footer');
	}

}
