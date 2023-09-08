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
		if(!empty($this->input->get('data_model')) && in_array($this->input->get('data_model'), ['table', 'grid']))
			$datamodel = $this->input->get('data_model');

		$data['page_js'][] = ['path' => 'https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js', 'defer' => true];

		if($datamodel == 'grid')
			$data['page_js'][] = ['path' => 'assets/js/materi_grid.js', 'defer' => true];
		else
			$data['page_js'][] = ['path' => 'assets/js/materi_table.js', 'defer' => true];

		$data['datamodel'] = $datamodel;

		$this->load->view('header');
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
		$count  = $this->db->count_all_results('subject');
		$filter = $this->input->get('columns');
		$data   = $this->model_mapel->get_all($limit, $offset, $filter);

		$json = [
			'draw'				=> $draw,
			'data' 		   		=> $data,
			'recordsTotal' 		=> $count,
			'recordsFiltered'	=> $this->model_mapel->num_all($filter)
		];

		header('Content-Type: application/json');
		echo json_encode($json, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_QUOT);
	}
}
