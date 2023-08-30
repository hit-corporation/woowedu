<?php

class Model_task extends CI_Model {

	public function get_tasks($username){
		$this->db->select('t.*, tc.teacher_name, sj.subject_name');
		$this->db->from('student s');
		$this->db->join('task t', 't.class_id = s.class_id', 'left');
		$this->db->join('teacher tc', 'tc.teacher_id = t.teacher_id', 'left');
		$this->db->join('subject sj', 'sj.subject_id = t.subject_id', 'left');
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

}
