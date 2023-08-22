<?php

ini_set('memory_limit', '4000M'); 

class Sekolah extends MY_Controller {

    public function __construct()
    {
			parent::__construct();
			check_Loggin();
			$this->load->model(['model_common', 'model_sekolah']); 
			$this->load->helper('url');
			$this->load->helper('slug');
			$this->load->helper('assets');	
			$lang = ($this->session->userdata('lang')) ? $this->session->userdata('lang') : config_item('language');
			$this->lang->load('message', $lang);
			$this->load->library('csrfsimple');
    }

    public function getAll() {
        $draw = $this->input->post('draw', TRUE);
        $limit = $this->input->post('length', TRUE);
        $offset = $this->input->post('start', TRUE);
        $filters = $this->input->post('columns');

        $data = $this->model_sekolah->getAll($filters, $limit, $offset);
        
        $resp = [
            'draw' => $draw,
            'data' => $data,
            'recordsTotal' => $this->db->count_all_results('sekolah'),
            'recordsFiltered' => $this->model_sekolah->countAll($filters)
        ];

        echo json_encode($resp, JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);
    }
 
}