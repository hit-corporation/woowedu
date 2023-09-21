<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ebook extends CI_Controller {

	private $settings;

	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model(['model_ebook', 'model_settings', 'transaction_model']);
		
		if (!isset($_SESSION['username'])) redirect('auth/login');

		$this->settings = json_decode(json_encode($this->model_settings->get_settings()), TRUE);
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

		$body['book'] = $this->model_ebook->get($param);

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
		$filter = $this->input->get('filter');
		$total	= $this->db->count_all_results('ebooks');
		$offset = ($page - 1) * ($limit + 1); 
		
		$data = $this->model_ebook->list($limit, $offset, $filter);

		$json = [
			'data' 		=> $data,
			'totalData' => $total,
		];

		header('X-Total-Count: '.$json['totalData']);
		header('Content-Type: application/json');
		echo json_encode($json, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
	}

	public function test() {
		echo '<pre>';
		print_r($this->settings);
		echo '</pre>';
	}

	/**
	 * Start read a book
	 *
	 * @return void
	 */
	public function open_book(): void {
		$id = $this->input->get('id');
		// only active member that can read the book
		if (!isset($_SESSION['logged_in']) && empty($_SESSION['username'])) {
			$data['heading'] = 'PERINGATAN';
			$data['message'] = '<p>Halaman hanya di peruntukan untuk anggota aktif. Silahkan login terlebih dahulu !!!' .
				'<br/> <a href="' . $_SERVER['HTTP_REFERER'] . '">Kembali</a></p>';
			$this->load->view('errors/html/error_general', $data);
			return;
		}
		// set transaction code
		$transcode = strtoupper(bin2hex(random_bytes(8)));
		// set cookie for reading time limit and idle time limit
		$cookie_option = [
			'expires'	=> strtotime('+' . $this->settings['limit_idle_value'] . ' ' . $this->settings['limit_idle_unit']),
			'path'		=> '/',
			'samesite'	=> 'Lax'
		];

		if (!isset($_COOKIE['read_book']))
			setcookie('read_book', base64_encode(json_encode(['key' => $transcode, 'expired' => date('Y-m-d H:i:s', $cookie_option['expires'])])), $cookie_option);

		// get latest transaction book
		// $latest_transaction = $this->transaction_model->get_latest_transaction($id, $_SESSION['userid']);

		$insert = [
			'trans_code' 	=> $transcode,
			'start_time' 	=> date('Y-m-d H:i:s.u'),
			'member_id' 	=> $_SESSION['userid'],
			'book_id'		=> $id,
			// 'config_idle'	=> $this->settings['limit_idle_value'].' '.$this->settings['limit_idle_unit'],
			// 'config_borrow_limit' => $this->settings['max_allowed'],
			// 'end_time'		=> isset($latest_transaction['end_time']) ? $latest_transaction['end_time'] : date('Y-m-d H:i:s.u', strtotime('+'.$this->settings['due_date_value'].' '.$this->settings['due_date_unit'])),
		];

		// $book = $this->db->get_where('ebooks', ['id' => $id])->row_array();
		// $url = base_url('ebook/detail/'.$book['book_code']);
		$book = $this->model_ebook->get($id);

		if($this->db->insert('read_log', $insert))
		{
			$url = $book['from_api '] === 0 ? base_url('ebook/read_book?id=' . $id) : $book['file_1']; 
		}
		
		redirect($url);
	}

	/**
	 * Read a book
	 *
	 * @return void
	 */
	public function read_book(): void
	{
		$id = $this->input->get('id');

		if (!isset($_COOKIE['read_book'])) {
			echo '<script>';
			echo 'window.location.href="' . base_url('ebook/detail/') . '"';
			echo '</script>';
			return;
		}

		$data['book'] = $this->model_ebook->get($id);
		$data['setting'] = $this->settings;
		$this->load->view('ebook/read', $data);
	}

		/**
	 * Closing after read book
	 *
	 * @return void
	 */
	public function close_book(): void
	{
		$id = $this->input->get('id');
		$lastPage = $this->input->get('last-page');
		$cookie = json_decode(base64_decode($_COOKIE['read_book']), TRUE);

		$update = [
			'trans_code' => $cookie['key'],
			'book_id'  => $id,
			'end_time' => date('Y-m-d H:i:s.u'),
			'last_page'	=> $lastPage
		];

		$this->db->update('read_log', $update, ['trans_code' => trim($cookie['key'])]);

		setcookie('read_book', NULL, time() - 1000);
		redirect('ebook/detail/' . $id);
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
