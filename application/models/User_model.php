<?php

class User_model extends CI_Model {

	public function get_user($user_id)
	{
		if($this->get_level($user_id) == 3){

		}
		$this->db->where('u.userid', $user_id);
		$this->db->join('student s', 's.nis = u.username');
		$this->db->join('sekolah sc', 'sc.sekolah_id = s.sekolah_id');
		$query = $this->db->get('users u');
		return $query->row_array();
	}

	public function update($data, $user_id){
		$this->db->where('userid', $user_id);
		return $this->db->update('users', $data);
	}

	public function get_level($user_id){
		$this->db->where('userid', $user_id);
		return $this->db->get('users')->row_array()['user_level'];
	}


}
