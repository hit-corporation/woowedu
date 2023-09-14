<?php

class Model_task extends CI_Model {

	public function get_tasks($username){
		$this->db->select('t.*, tc.teacher_name, sj.subject_name');
		$this->db->from('student s');
		$this->db->join('task t', 't.class_id = s.class_id', 'left');
		$this->db->join('teacher tc', 'tc.teacher_id = t.teacher_id', 'left');
		$this->db->join('subject sj', 'sj.subject_id = t.subject_id', 'left');
		$this->db->limit('9');
		$this->db->where('s.nis', $username);

		return $this->db->get()->result_array();
	}

	public function get_tasks_detail($id){
		$this->db->select('t.*, tc.teacher_name, sj.subject_name');
		$this->db->from('task t');
		$this->db->join('teacher tc', 'tc.teacher_id = t.teacher_id', 'left');
		$this->db->join('subject sj', 'sj.subject_id = t.subject_id', 'left');
		$this->db->where('t.task_id', $id);

		return $this->db->get()->row_array();
	}

	public function get_teacher_tasks($teacher_id){
		$this->db->select('t.*, tc.teacher_name, sj.subject_name');
		$this->db->from('student s');
		$this->db->join('task t', 't.class_id = s.class_id', 'left');
		$this->db->join('teacher tc', 'tc.teacher_id = t.teacher_id', 'left');
		$this->db->join('subject sj', 'sj.subject_id = t.subject_id', 'left');
		$this->db->limit('9');
		$this->db->where('t.teacher_id', $teacher_id);

		return $this->db->get()->result_array();
	}
	
	
	public function get_student_task($limit = null, $page = null, $title, $startDate, $endDate){
		
		$username 	= $this->session->userdata('username');
		$this->db->select('t.*, tc.teacher_name, sj.subject_name');
		$this->db->from('student s');
		$this->db->join('task t', 't.class_id = s.class_id', 'left');
		$this->db->join('teacher tc', 'tc.teacher_id = t.teacher_id', 'left');
		$this->db->join('subject sj', 'sj.subject_id = t.subject_id', 'left'); 
		$this->db->where('s.nis', $username);
		
		if(!empty($title))
			$this->db->where('LOWER(judul) LIKE \'%'.trim(strtolower($title)).'%\'', NULL, FALSE);

		if(!empty($startDate))
			$this->db->where('date(tanggal) >=', date('Y-m-d', strtotime($startDate)));
		
		if(!empty($endDate))
			$this->db->where('date(tanggal) <=', date('Y-m-d', strtotime($endDate)));
 
		$this->db->limit($limit, $page);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function get_student_total_task($title, $startDate, $endDate){
		
		$username 	= $this->session->userdata('username');  
		$this->db->where('nis', $username);
		
		if(!empty($title))
			$this->db->where('LOWER(judul) LIKE \'%'.trim(strtolower($title)).'%\'', NULL, FALSE);

		if(!empty($startDate))
			$this->db->where('date(tanggal) >=', date('Y-m-d', strtotime($startDate)));
		
		if(!empty($endDate))
			$this->db->where('date(tanggal) <=', date('Y-m-d', strtotime($endDate)));

		$query = $this->db->get('student');
		return $query->num_rows();
	}	
}
