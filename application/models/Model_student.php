<?php

class Model_student extends CI_Model {

	public function get_history($limit = null, $page = null, $filter){
		if(!empty($filter['namaSiswa']))
			$this->db->where('LOWER(student_name) LIKE \'%'.trim(strtolower($filter['namaSiswa'])).'%\'', NULL, FALSE);

		if(!empty($filter['kelas']))
			$this->db->where('class_id', $filter['kelas']);

		$this->db->select('s.*');
		$this->db->from('student s');
		$this->db->order_by('student_name', 'asc');
		$this->db->limit($limit, $page);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function get_total_history($filter){
		if(!empty($filter['namaSiswa']))
			$this->db->where('LOWER(student_name) LIKE \'%'.trim(strtolower($filter['namaSiswa'])).'%\'', NULL, FALSE);

		if(!empty($filter['kelas']))
			$this->db->where('class_id', $filter['kelas']);

		$query = $this->db->get('student');
		return $query->num_rows();
	}

}
