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
}
