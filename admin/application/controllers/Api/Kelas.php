<?php

class Kelas extends MY_Controller {
	public function __construct() 
	{
		parent::__construct(); 
		$this->load->model(['model_common']);
		$this->load->model(['Model_settings']);
		$this->load->library(['csrfsimple', 'curl']);
		$lang = ($this->session->userdata('lang')) ? $this->session->userdata('lang') : config_item('language');
		$this->lang->load('message', $lang);
	}
	
	
	public function get_all_level() 
	{
    header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET');
		header('Content-Type: application/json');		
		
		if($_SERVER['REQUEST_METHOD'] !== 'GET') {
				http_response_code(405);
				$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_mismatch_method')];
				echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
				return;
		}
				
		$draw   = $this->input->get('draw');
		$limit  = $this->input->get('length');
		$offset = $this->input->get('start');
		$filter = $this->input->get('columns'); 
		$rec   = $this->model_common->get_all_class_level($filter, $limit, $offset);
		$count  = $this->model_common->count_all_class_level($filter);
    $countFilter = $this->model_common->count_all_class_level($filter);
		
		$datas =   array(
			"draw"			  => $draw,
			"recordsTotal"	  => $count,
			"recordsFiltered" => $countFilter,
			"data"			  => $rec
		);
		http_response_code(200);
		echo json_encode($datas, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
		exit();
	}	

	public function get_all() 
	{
    	header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET');
		header('Content-Type: application/json');		
		
		if($_SERVER['REQUEST_METHOD'] !== 'GET') {
				http_response_code(405);
				$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_mismatch_method')];
				echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
				return;
		}
				
		$draw   = $this->input->get('draw');
		$limit  = $this->input->get('length');
		$offset = $this->input->get('start');
		$filter = $this->input->get('columns'); 
		$rec   = $this->model_common->get_all_class($filter, $limit, $offset);
		$count  = $this->model_common->count_all_class($filter);
    	$countFilter = $this->model_common->count_all_class($filter);
		
		$datas =   array(
			"draw"			  => $draw,
			"recordsTotal"	  => $count,
			"recordsFiltered" => $countFilter,
			"data"			  => $rec
		);
		http_response_code(200);
		echo json_encode($datas, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
		exit();
	}
 
 
 
	public function add_data() 
	{
    header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: POST');
		header('Content-Type: application/json');

		if($_SERVER['REQUEST_METHOD'] !== 'POST') {
				http_response_code(405);
				$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_mismatch_method')];
				echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
				return;
		}
		
		// params
		 
		$class_name   = trim($this->input->post('class_name')); 
		$token  = trim($this->input->post('xsrf_token'));
		//validation
		if($this->csrfsimple->checkToken($token) === false) {
				http_response_code(422);
				$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_csrf_token_false'), 'token' => $this->csrfsimple->genToken()];
				echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
				return;
		}
		if(empty($class_name)) {
				http_response_code(422);
				$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_is_required'), 'token' => $this->csrfsimple->genToken()];
				echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
				return;
		}
 
		// insert action
		$data = [ 
				'class_name'  => $class_name 
		];
		if(!$this->model_common->save_class($data)) {
				http_response_code(422);
			$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_form_error'), 'token' => $this->csrfsimple->genToken()];
			echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
			return;
		}
        http_response_code(200);
		$msg = ['err_status' => 'success', 'message' => $this->lang->line('woow_form_success'), 'token' => $this->csrfsimple->genToken()];
		echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
		exit;
	}

	public function edit_data() 
	{
    header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: PUT');
		header('Content-Type: application/json');

		if($_SERVER['REQUEST_METHOD'] !== 'PUT') {
				http_response_code(405);
				$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_mismatch_method')];
				echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
				return;
		}
		$input = json_decode(file_get_contents('php://input'), TRUE);
		// params 
		$class_id     = trim($input['class_id']);
		$class_name   = trim($input['class_name']); 
		$token  = trim($input['xsrf_token']);

		if($this->csrfsimple->checkToken($token) === false) {
				http_response_code(422);
				$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_csrf_token_false'), 'token' => $this->csrfsimple->genToken()];
				echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
				return;
		}
		if(empty($class_id) || empty($class_name)) {
				http_response_code(422);
				$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_is_required'), 'token' => $this->csrfsimple->genToken()];
				echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
				return;
		}
		
		// update action
		$data = [
				'class_id'        => $class_id, 
				'class_name'  => $class_name 
		];
		if(!$this->model_common->modify_class($data)) {
				http_response_code(422);
			$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_form_error'), 'token' => $this->csrfsimple->genToken()];
			echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
			return;
		}
		http_response_code(200);
		$msg = ['err_status' => 'success', 'message' => $this->lang->line('woow_form_success'), 'token' => $this->csrfsimple->genToken()];
		echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
		exit;
	}
 

 

	public function delete_data() {
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
			if($this->csrfsimple->checkToken($input['xsrf_token']) === false) {
					http_response_code(422);
					$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_csrf_token_false'), 'token' => $this->csrfsimple->genToken()];
					echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
					return;
			}
			if($input['bulk'])
					$this->db->where_in('class_id', $input['data']);
			else
					$this->db->where('class_id', $input['data']);
			if(!$this->db->delete('kelas')) {
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