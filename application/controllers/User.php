<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->library('session');
		
		if (!isset($_SESSION['username'])) redirect('auth/login');
	}

	public function index()
	{
		$userId = $this->session->userdata('userid');
		$data['user_data'] = $this->user_model->get_user($userId); 
		
		// JIKA USER LEVEL ORTU
		if($data['user_data']['user_level'] == 5){
			$username = $this->db->where('userid', $userId)->get('users')->row_array()['username'];
			$parentId = $this->db->where('username', $username)->get('parent')->row_array()['parent_id'];
			$students = $this->db->where('parent_id', $parentId)->get('student')->result_array();
			$data['students'] = $students;
		}

		$this->load->view('header');
		$this->load->view('user/index', $data);
		$this->load->view('footer');
	}

	public function store(){
		// load file helper
		$this->load->helper('file');

		$post = $this->input->post();


		// set validation rules
		// if (empty($_FILES['userfile']['name'])){
		// 	$this->form_validation->set_rules('userfile', 'userfile', 'required');

		// 	if($this->form_validation->run() == false){
		// 		// validation fails
		// 		$resp = ['success' => false, 'message' => $this->form_validation->error_array(), 'old' => $post];
		// 		$this->session->set_flashdata('error', $resp);
		// 		redirect($_SERVER['HTTP_REFERER']);
		// 	}
		// }

		// upload images
		$config['upload_path']          = './assets/images/users/';
		$config['allowed_types']        = 'gif|jpg|jpeg|png';
		$config['max_size']             = 2048;
		$config['encrypt_name']         = true;

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload('userfile')){
			// upload fails
			// $resp = ['success' => false, 'message' => $this->upload->display_errors()];
			// $this->session->set_flashdata('error', $resp);
			// echo json_encode($resp); die;
		}else{
			// upload success
			$upload_data = $this->upload->data();
			// resize image
			$config['image_library'] = 'gd2';
			$config['source_image'] = './assets/images/users/'.$upload_data['file_name'];
			$config['create_thumb'] = FALSE;
			$config['maintain_ratio'] = FALSE;
			$config['width']         = 300;
			$config['height']       = 300;

			$this->load->library('image_lib', $config);
			$this->image_lib->resize();
			// remove old image
			$old_image = $this->user_model->get_user($post['user_id'])['photo'];
			if($old_image != '' || $old_image != null){
				unlink('./assets/images/users/'.$old_image);
			}
			// update user data
			$data = [ 'photo' => $upload_data['file_name'] ];
		}
		
		if(isset($data['photo'])){
			// update user data
			$update = $this->user_model->update($data, $post['user_id']);
		}

		// UPDATE DATA BERDASARKAN USER LEVEL
		$user = $this->db->where('userid', $post['user_id'])->get('users')->row_array();
		if($user['user_level'] == 4){
			$update = $this->db->where('nis', $user['username'])->update('student', ['email'=>$post['email'], 'phone'=>$post['phone'], 'edit_at'=>date('Y-m-d H:i:s')]);
		}elseif($user['user_level'] == 3 || $user['user_level'] == 6){
			$update = $this->db->where('nik', $user['username'])->update('teacher', ['email'=>$post['email'], 'phone'=>$post['phone'], 'edit_at'=>date('Y-m-d H:i:s')]);
		}elseif($user['user_level'] == 5){
			$update = $this->db->where('username', $user['username'])->update('parent', ['email'=>$post['email'], 'phone'=>$post['phone'], 'edit_at'=>date('Y-m-d H:i:s')]);
		}

		// update student data
		$res = ($update) ?  ['success' => true, 'message' => 'Data berhasil diubah.'] :  ['success' => false, 'message' => 'Data gagal diubah.'];
		// set success message
		$this->session->set_flashdata('success', $res);
		echo json_encode($res);
	}

	public function store_parent(){
		$post = $this->input->post();

		$data = [
			'parent_email' 	=> $post['parent_email'],
			'parent_phone' 	=> $post['parent_phone'],
			'edit_at'		=> date('Y-m-d H:i:s', time())
		];

		$username 	= $this->db->where('userid', $post['user_id'])->get('users')->row_array()['username']; // user id sementara di hardcode
		$update 	= $this->db->where('nis', $username)->update('student', $data);

		if($update){
			$res = ['success' => true, 'message' => 'Data berhasil di simpan'];
			echo json_encode($res);
		}else{
			$res = ['success' => false, 'message' => 'Data gagal di simpan'];
			echo json_encode($res);
		}
	}

	public function change_password(){
		$post = $this->input->post();
		
		// set validation rules
		$this->form_validation->set_rules('old_password', 'Password Lama', 'required|callback_check_password' , ['check_password' => 'Password lama tidak sesuai.'] );
		$this->form_validation->set_rules('new_password', 'Password Baru', 'required');
		$this->form_validation->set_rules('confirm_password', 'Konfirmasi Password', 'required|matches[new_password]');

		// validate form input
		if($this->form_validation->run() == false){
			// validation fails
			$resp = ['success' => false, 'message' => $this->form_validation->error_array(), 'old' => $post];
			$this->session->set_flashdata('error', $resp);
			echo json_encode($resp);
		}else{
			// validation succeeds
			$data = [
				'password' => password_hash($post['new_password'], PASSWORD_DEFAULT)
			];

			// update user data
			$this->user_model->update($data, $post['user_id']);

			// set success message
			$resp = ['success' => true, 'message' => 'Password berhasil diubah.'];
			$this->session->set_flashdata('success', $resp);
			echo json_encode($resp);
		}
	
	}

	public function check_password($str): bool{
		$member = $this->user_model->get_user($this->session->userdata('userid'));

		if(isset($member) &&  password_verify($str, $member['password'])){
			return true;
		}else{
			return false;
		}
	}

	public function store_tautan_anak(){
		$post = $this->input->post();

		$userId = $post['userId'];
		$userNameOrtu = $this->db->where('userid', $userId)->get('users')->row_array()['username'];
		$parentId = $this->db->where('username', $userNameOrtu)->get('parent')->row_array()['parent_id'];

		$update = $this->db->where('nis', $post['nis'])->update('student', ['parent_id' => $parentId]);
		if($update){
			$students = $this->db->where('parent_id', $parentId)->get('student')->result_array();
			$res = [ 'success' => true, 'message' => 'Data berhasil di simpan', 'data' => $students ];
		}else{
			$res = [ 'success' => false, 'message' => 'Data gagal di simpan', 'data' => []];
		}
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($res);
	}

	public function delete_tautan_anak(){
		$post = $this->input->post();

		$update = $this->db->where('nis', $post['nis'])->update('student', ['parent_id' => null]);
		if($update){
			$res = ['success' => true, 'message' => 'Data berhasil dihapus'];
		} else {
			$res = ['success' => false, 'message' => 'Data gagal dihapus'];
		}
		
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($res);
	}
}
