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

	public function get_total_exam($class_id, $start_dt, $end_dt){
		$this->db->where('class_id', $class_id);
		$this->db->where('DATE(start_date) >=', $start_dt);
		$this->db->where('DATE(end_date) <=', $end_dt);
		return $this->db->get('exam')->num_rows();
	}

	public function average_exam_score($student_id, $start_dt, $end_dt){
		$this->db->select('AVG(exam_total_nilai) as exam_total_nilai');
		$this->db->where('student_id', $student_id);
		$this->db->where('DATE(exam_submit) >=', $start_dt);
		$this->db->where('DATE(exam_submit) <=', $end_dt);
		return $this->db->get('exam_student')->row_array();
	}

	public function get_task($limit = null, $page = null, $student_id){
		$class_id = $this->get_class($student_id);

		$this->db->select('t.*, m.title');
		$this->db->from('task t');
		$this->db->join('materi m', 'm.materi_id = t.materi_id');
		$this->db->where('class_id', $class_id);
		$this->db->order_by('available_date', 'desc');
		$this->db->limit($limit, $page);
		return $this->db->get()->result_array();
	}

	public function get_total_task($student_id){
		$class_id = $this->get_class($student_id);

		$this->db->select('t.*, m.title');
		$this->db->from('task t');
		$this->db->join('materi m', 'm.materi_id = t.materi_id');
		$this->db->where('class_id', $class_id);
		return $this->db->get()->num_rows();
	}

	public function get_exam($limit = null, $page = null, $student_id){
		$class_id = $this->get_class($student_id);
		$this->db->select('e.*, s.subject_name, ec.category_name');
		$this->db->from('exam e');
		$this->db->join('subject s', 's.subject_id = e.subject_id');
		$this->db->join('exam_category ec', 'e.category_id = ec.category_id');
		$this->db->where('e.class_id', $class_id);
		$this->db->order_by('start_date', 'desc');
		$this->db->limit($limit, $page);
		return $this->db->get()->result_array();
	}

	public function get_total_row_exam($student_id){
		$class_id = $this->get_class($student_id);
		$this->db->select('e.*, s.subject_name');
		$this->db->from('exam e');
		$this->db->join('subject s', 's.subject_id = e.subject_id');
		$this->db->join('exam_category ec', 'e.category_id = ec.category_id');
		$this->db->where('e.class_id', $class_id);
		return $this->db->get()->num_rows();
	}

	public function get_class($student_id){
		$this->db->where('student_id', $student_id);
		return $this->db->get('student s')->row_array()['class_id'];
	}

	public function get_student_class($username){
		$teacher = $this->db->where('nik', $username)->get('teacher')->row_array();

		$this->db->select('COUNT(s.class_id) as value, k.class_name as category');
		$this->db->from('student s');
		$this->db->join('kelas k', 'k.class_id = s.class_id');
		$this->db->where('sekolah_id', $teacher['sekolah_id']);
		$this->db->where('s.ta_aktif', 1);
		$this->db->group_by('s.class_id, k.class_name');
		$this->db->order_by('s.class_id', 'asc');
		return $this->db->get()->result_array();
	}

	public function download($class = null, $nama = null){
		if(!empty($nama))
			$this->db->where('LOWER(student_name) LIKE \'%'.trim(strtolower($nama)).'%\'', NULL, FALSE);

		if(!empty($class))
			$this->db->where('class_id', $class);

		$this->db->select('s.nis, s.student_name, s.gender, s.address, s.phone, s.email');
		$this->db->from('student s');
		return $this->db->get()->result_array();
	}

	public function get_history_book($limit = null, $page = null, $filter){
		$this->db->select('book_id, max(start_time), book_code, title, cover_img, author, publish_year, description');
		$this->db->from('read_log');
		$this->db->join('ebooks', 'ebooks.id = read_log.book_id');
		$this->db->where('member_id', $filter['user_id']);
		$this->db->group_by('book_id, book_code, title, cover_img, author, publish_year, description');
		$this->db->limit($limit, $page);
		return $this->db->get()->result_array();
	}

	public function get_history_book_total($filter){
		$this->db->select('book_id, max(start_time), book_code, title, cover_img, author, publish_year, description');
		$this->db->from('read_log');
		$this->db->join('ebooks', 'ebooks.id = read_log.book_id');
		$this->db->where('member_id', $filter['user_id']);
		$this->db->group_by('book_id, book_code, title, cover_img, author, publish_year, description');
		return $this->db->get()->num_rows();
	}
}
