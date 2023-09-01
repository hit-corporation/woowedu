<?php

class Model_teacher extends CI_Model {

	public function get_teacher_status($status){
		$this->db->select('COUNT(status)');
		$this->db->from('teacher t');
		$this->db->where('status', $status);
		$this->db->group_by('status');
		return $this->db->get()->row_array();
	}

	public function get_teacher_login_month($month){
		$this->db->select('COUNT(id)');
		$this->db->from('actionlog a');
		$this->db->where('EXTRACT(MONTH FROM logtime) =', $month);
		$this->db->where('EXTRACT(YEAR FROM logtime) =', date('Y', time()));
		return $this->db->get()->row_array();
	}
}
