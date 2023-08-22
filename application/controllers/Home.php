<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('model_task');
		$this->load->library('session');
	}

	public function index()
	{
		$username 		= $this->session->userdata('username');
		$data['tasks'] 	= $this->model_task->get_tasks($username);

		$this->load->view('header');
		$this->load->view('home/index', $data);
		$this->load->view('footer');
	}
}
