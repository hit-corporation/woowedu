<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('model_task');
		$this->load->model('model_news');
		$this->load->library('session');

		if (!isset($_SESSION['username'])) redirect('auth/login');
	}

	public function index()
	{
		$username 		= $this->session->userdata('username');
		$data['tasks'] 	= $this->model_task->get_tasks($username);
		$data['news']	= $this->model_news->get_news();

		$this->load->view('header');
		$this->load->view('home/index', $data);
		$this->load->view('footer');
	}
}
