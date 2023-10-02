<?php defined('BASEPATH') OR exit('No direct script access allowed');

class auth extends CI_Controller 
{
	function __construct() {
		parent::__construct();
		$this->load->model('m_login');
		$this->load->model('m_users');
		$this->load->library('session');
		$lang = ($this->session->userdata('lang')) ? $this->session->userdata('lang') : config_item('language');
		$this->lang->load('message', $lang);
	}

	public function changePassword()
	{
		$userid		= $this->session->userdata['userid'];
		$username	= $this->session->userdata['username'];
		
		$pw_lama	= $this->input->post('password_lama');
		$hasil		= $this->m_login->loginCek($username)->row();
		
		if(hash_verified($pw_lama, $hasil->password)){
			$data = array(
				'userid'=>$userid,
				'password' => get_hash($this->input->post('repassword')),
			);
			$this->m_users->change_pw($data);
			echo "success";
		}else{
			echo "pw_salah";
		}
	}

	public function loginx() {
		header('Content-Type: application/json');

		$username = trim($this->input->post('username'));
		$password = trim($this->input->post('password'));

		if(empty($username)) {
			http_response_code(422);
            $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_login_empty_username')];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
            return;
		}
		if(empty($password)) {
			http_response_code(422);
            $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_login_empty_password')];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
            return;
		}

		$getUser = $this->db->get_where('users', ['username' => $username]);

		if($getUser->num_rows() == 0) {
			http_response_code(422);
            $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_login_username_mismatch')];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
            return;
		}

		$dt = $getUser->row();

		if(password_verify($password, $dt->password) == FALSE) {
			http_response_code(422);
            $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_login_password_mismatch')];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
            return;
		} 
		$this->session->set_userdata(
			array(
				'status_login' => 'y',
				'userid' => $dt->userid,
				'username' => $dt->username,
				'userpic' => $dt->photo,
				'user_level' => $dt->user_level, 
				'sekolah_id' => $dt->sekolah_id, 
				'user_token' => (empty($dt->users_token)) ? md5(uniqid($dt->username, true)) : $dt->users_token, 
				'logged_in' => true
			)
		);
		if($dt->user_level==3){ //guru
			$getTeacher = $this->db->get_where('teacher', ['nik' =>$username]);
			$dteacher = $getTeacher->row();
			$teacher_id = $dteacher->teacher_id;
			$this->session->set_userdata(array('teacher_id'=>$teacher_id));
		}
		if($dt->user_level==4){  //siswa
			$this->db->select('k.class_id,k.class_level_id');
			$this->db->from('kelas k');
			$this->db->join('student s', 's.class_id=k.class_id');
			$this->db->where('nis', $username);
			$dstudent = $this->db->get()->row();
			$class_id = $dstudent->class_id;
			$class_level_id = $dstudent->class_level_id;
			$this->session->set_userdata(array('class_id'=>$class_id));
			$this->session->set_userdata(array('class_level_id'=>$class_level_id));
		}		

		$_token = base64_encode($dt->username.':'.$password);

		http_response_code(200);
        $msg = ['err_status' => 'success', 'message' => 'Login Success','ulevel'=>$dt->user_level, 'token' => $_token];
        echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
        exit;
	}

	public function login()
	{ 
		chek_session_login();
		$this->load->view('login/index');
	}

	public function logout()
	{
		$this->session->sess_destroy();
		$script = '<script>
					window.localStorage.removeItem(\'level\');
					window.localStorage.removeItem(\'token\');
				  </script>';
		echo $script;
		redirect('auth/login');
	}


	public function test() {
		//$this->db->update('users', ['password' => password_hash('admin', PASSWORD_DEFAULT)], ['userid' => 1]);
		$data = [
			'username'		=> 'naquib',
			'password'		=> password_hash('123456', PASSWORD_DEFAULT),
			'user_level'	=> 1
		];
		$this->db->insert('users', $data);
	}

}