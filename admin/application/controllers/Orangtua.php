<?php

class Orangtua extends MY_Controller {

    public function __construct() {
        parent::__construct();

		check_Loggin();
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
		$post = $this->input->post();
        $username = $this->input->post('a_username', TRUE);
        $name = $this->input->post('a_full_name', TRUE);
        $address = $this->input->post('a_address', TRUE);
        $phone = $this->input->post('a_phone', TRUE);
        $email = $this->input->post('a_email', TRUE);
        $children = $this->input->post('a_children', TRUE);

        header('Content-Type: application/json');

        if(empty($username) || empty($name) || empty($children))
        {
            http_response_code(422);
            $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_is_required'), 'token' => $this->csrfsimple->genToken()];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
            return;
        }

        $data = [
            'username' => $username, 
            'name'     => $name,
            'address'  => $address,
            'phone'     => $phone,
            'email'     => $email
        ];

        $children = explode(',', $children);

        $this->db->trans_start();
        $this->db->insert('parent', $data);
        $id = $this->db->insert_id();
        
        foreach($children as $child)
            $this->db->update('student', ['parent_id' => $id], ['student_id' => intval($child)]);
        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            http_response_code(422);
			$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_form_error'), 'token' => $this->csrfsimple->genToken()];
			echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
			return;
        }

        $this->db->trans_commit();
        
        http_response_code(200);
		$msg = ['err_status' => 'success', 'message' => $this->lang->line('woow_form_success'), 'token' => $this->csrfsimple->genToken(), 'POST' => $post];
		echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
		exit;

    }

    /**
     * Edit An Exitsting data in database
     *
     * @return void
     */
    public function edit(): void {
        $id = $this->input->post('a_parent_id', TRUE);
        $username = $this->input->post('a_username', TRUE);
        $name = $this->input->post('a_full_name', TRUE);
        $address = $this->input->post('a_address', TRUE);
        $phone = $this->input->post('a_phone', TRUE);
        $email = $this->input->post('a_email', TRUE);
        $children = $this->input->post('a_children', TRUE);

        header('Content-Type: application/json');

        if(empty($username) || empty($name) || empty($id))
        {
            http_response_code(422);
            $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_is_required'), 'token' => $this->csrfsimple->genToken()];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
            return;
        }

        $data = [
            'username' => $username, 
            'name'     => $name,
            'address'  => $address,
            'phone'     => $phone,
            'email'     => $email
        ];

        $children = explode(',', $children);

        $this->db->trans_start();
        $this->db->update('parent', $data, ['parent_id' => intval($id)]);
        
        foreach($children as $child)
            $this->db->update('student', ['parent_id' => $id], ['student_id' => intval($child)]);
        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            http_response_code(422);
			$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_form_error'), 'token' => $this->csrfsimple->genToken()];
			echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
			return;
        }

        $this->db->trans_commit();
        
        http_response_code(200);
		$msg = ['err_status' => 'success', 'message' => $this->lang->line('woow_form_success'), 'token' => $this->csrfsimple->genToken(), 'POST' => $post];
		echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
		exit;
    }

    /**
     * Delete One Or More Data in Database
     *
     * @return void
     */
    public function delete(): void {
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: DELETE');
		header('Content-Type: application/json');

			$params = file_get_contents('php://input');
			$input = json_decode($params, TRUE);
			if($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
					http_response_code(405);
					$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_mismatch_method')];
					echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
					return;
			}
			// if($this->csrfsimple->checkToken($input['xsrf_token']) === false) {
			// 		http_response_code(422);
			// 		$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_csrf_token_false'), 'token' => $this->csrfsimple->genToken()];
			// 		echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
			// 		return;
			// }

            if($this->db->get_where('student', ['parent_id' => $input['data']])->num_rows() > 0)
            {
                http_response_code(422);
                $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_delete_error')];
                echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
                return;
            }

			if($input['bulk'])
					$this->db->where_in('parent_id', $input['data']);
			else
					$this->db->where('parent_id', $input['data']);
			if(!$this->db->delete('parent')) {
				http_response_code(422);
				$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_delete_error'), 'token' => $this->csrfsimple->genToken()];
				echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
				return;
			}
			http_response_code(200);
			$msg = ['err_status' => 'success', 'message' => $this->lang->line('woow_delete_success'), 'token' => $this->csrfsimple->genToken()];
			echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
			exit();
	}
}
