<?php

class Orangtua extends MY_Controller {

    public function __construct() {
        parent::__construct();

		check_loggin();
		$this->load->model(['model_common', 'model_parent']); 
		$this->load->helper('url');
		$this->load->helper('slug');
		$this->load->helper('assets');	
		$lang = ($this->session->userdata('lang')) ? $this->session->userdata('lang') : config_item('language');
		$this->lang->load('message', $lang);
		$this->load->library('csrfsimple');
    }

    public function index() {
        $data['pageTitle']	= 'Data Orang Tua';
		$data['tableName']	= 'Parents';
		$data['csrf_token']	= $this->csrfsimple->genToken();
		$data['page_js']	= [  
            ['path' => 'assets/node_modules/bootstrap-select/dist/js/bootstrap-select.min.js', 'defer' => true],
			['path' => 'assets/new/js/pages/_parent.js', 'defer' => true]
		]; 
        $data['page_css']	= [  
			'assets/node_modules/bootstrap-select/dist/css/bootstrap-select.min.css',
			'assets/new/css/fpersonno.css'
		]; 
 
		$this->template->load('template', 'parent/index', $data);
    }

    /**
     * List all parents data
     *
     * @return void
     */
    public function list(): void {
        $draw = $this->input->get('draw');
        $limit = $this->input->get('length');
        $offset = $this->input->get('start');
        $filters = $this->input->get('columns');
        $data = $this->model_parent->get_all($limit, $offset, $filters);
        $count = $this->model_parent->count_all($filters);

        $json = [
            'draw' => $draw,
            'data' => $data, 
            'recordsTotal' => $this->db->count_all_results('parent'),
            'recordsFiltered' => $count
        ];

        header('Content-Type: application/json');
        echo json_encode($json, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
    }

    /**
     * Store new data in database
     *
     * @return void
     */
    public function store(): void {
        $username = $this->input->post('a_username', TRUE);
        $name = $this->input->post('a_full_name', TRUE);
        $address = $this->input->post('a_address', TRUE);
        $phone = $this->input->post('a_phone', TRUE);
        $email = $this->input->post('a_email', TRUE);

        header('Content-Type: application/json');

        if(empty($username) || empty($name))
        {
            http_response_code(422);
            $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_is_required'), 'token' => $this->csrfsimple->genToken()];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
            return;
        }

        

        http_response_code(200);
		$msg = ['err_status' => 'success', 'message' => $this->lang->line('woow_form_success'), 'token' => $this->csrfsimple->genToken()];
		echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
		exit;

    }
}
