<?php

class Model_teacher extends CI_Model {

	public function get_teacher_status($status){
		$this->db->select('COUNT(status)');
		$this->db->from('teacher t');
		$this->db->where('status', $status);
		$this->db->group_by('status');
		return $this->db->get()->row_array();
	}
}
