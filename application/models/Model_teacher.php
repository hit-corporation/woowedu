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

	public function get_history($limit = null, $page = null, $filter){
		if(!empty($filter['namaGuru']))
			$this->db->where('LOWER(teacher_name) LIKE \'%'.trim(strtolower($filter['namaGuru'])).'%\'', NULL, FALSE);

		$this->db->select('t.*');
		$this->db->from('teacher t');
		$this->db->order_by('teacher_name', 'asc');
		$this->db->limit($limit, $page);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function get_total_history($filter){
		if(!empty($filter['namaGuru']))
			$this->db->where('LOWER(teacher_name) LIKE \'%'.trim(strtolower($filter['namaGuru'])).'%\'', NULL, FALSE);

		$query = $this->db->get('teacher');
		return $query->num_rows();
	}

	public function get_total_exam($class_id, $start_dt, $end_dt){
		$this->db->where('class_id', $class_id);
		$this->db->where('DATE(start_date) >=', $start_dt);
		$this->db->where('DATE(end_date) <=', $end_dt);
		return $this->db->get('exam')->num_rows();
	}
}
