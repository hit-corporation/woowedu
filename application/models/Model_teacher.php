<?php

class Model_teacher extends CI_Model {

	public function __construct(){
        parent::__construct();
    }

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

	public function get_exam($limit = null, $page = null, $teacher_id){
		$this->db->select('e.*, s.subject_name, ec.category_name, k.class_name');
		$this->db->from('exam e');
		$this->db->join('kelas k', 'k.class_id = e.class_id');
		$this->db->join('subject s', 's.subject_id = e.subject_id');
		$this->db->join('exam_category ec', 'e.category_id = ec.category_id');
		$this->db->where('e.teacher_id', $teacher_id);
		$this->db->order_by('start_date', 'desc');
		$this->db->limit($limit, $page);
		return $this->db->get()->result_array();
	}

	public function get_total_exam($teacher_id, $start_dt, $end_dt){
		$this->db->where('teacher_id', $teacher_id);
		$this->db->where('DATE(start_date) >=', $start_dt);
		$this->db->where('DATE(end_date) <=', $end_dt);
		return $this->db->get('exam')->num_rows();
	}

	public function get_task($limit = null, $page = null, $teacher_id){

		$this->db->select('t.*, m.title, s.subject_name');
		$this->db->from('task t');
		$this->db->join('materi m', 'm.materi_id = t.materi_id');
		$this->db->join('subject s', 's.subject_id = m.subject_id');
		$this->db->where('t.teacher_id', $teacher_id);
		$this->db->order_by('available_date', 'desc');
		$this->db->limit($limit, $page);
		return $this->db->get()->result_array();
	}

	public function get_total_task($teacher_id, $start_dt, $end_dt){
		$this->db->where('teacher_id', $teacher_id);
		$this->db->where('DATE(available_date) >=', $start_dt);
		$this->db->where('DATE(available_date) <=', $end_dt);
		return $this->db->get('task')->num_rows();
	}

	public function get_total_row_exam($teacher_id){
		$this->db->select('e.*, s.subject_name');
		$this->db->from('exam e');
		$this->db->join('subject s', 's.subject_id = e.subject_id');
		$this->db->join('exam_category ec', 'e.category_id = ec.category_id');
		$this->db->where('e.teacher_id', $teacher_id);
		return $this->db->get()->num_rows();
	}

	public function get_all_task_by_date($sekolah_id, $date){
		$this->db->where('DATE(t.available_date)', $date);
		$this->db->where('tc.sekolah_id', $sekolah_id);
		$this->db->join('teacher tc', 'tc.teacher_id = t.teacher_id');
		return $this->db->get('task t');
	}
	
	public function get_kelas(){
		
		$teacher_id 	= $this->session->userdata('teacher_id'); 		
		$this->db->select('k.class_id, k.class_name');
		$this->db->from('kelas k');
		$this->db->join('subject s','s.class_level_id=k.class_level_id');
		$this->db->join('subject_teacher st','s.subject_id=st.subject_id');
		$this->db->where('st.teacher_id',$teacher_id);
		$this->db->group_by('k.class_id, k.class_name');
		$this->db->order_by('k.class_id');
		
 
		return $this->db->get()->result_array();
	}	
	
	
	public function get_mapel(){
		
		$teacher_id 	= $this->session->userdata('teacher_id'); 
		$this->db->select('s.subject_id, subject_name');
		$this->db->from('subject s');
		$this->db->join('subject_teacher st','s.subject_id=st.subject_id');
		$this->db->where('teacher_id',$teacher_id);

		return $this->db->get()->result_array();
	}	
	
	
	public function get_ujian($limit = null, $page = null, $mapel,$kelas, $startDate, $endDate){
		
		$teacher_id 	= $this->session->userdata('teacher_id');
		$this->db->select('e.*,  s.subject_name, k.class_name');
		$this->db->from('exam e'); 
		$this->db->join('subject s', 's.subject_id = e.subject_id');  
		$this->db->join('kelas k', 'k.class_id = e.class_id');  
		$this->db->where('e.teacher_id', $teacher_id);
		
		if(!empty($mapel))
			$this->db->where('e.subject_id',$mapel);
		
		if(!empty($mapel))
			$this->db->where('e.class_id',$kelas);		

		if(!empty($startDate))
			$this->db->where('date(start_date) >=', date('Y-m-d', strtotime($startDate)));
		
		if(!empty($endDate))
			$this->db->where('date(end_date) <=', date('Y-m-d', strtotime($endDate)));

 
		$this->db->limit($limit, $page);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function get_total_ujian($mapel,$kelas, $startDate, $endDate){
		
		$teacher_id 	= $this->session->userdata('teacher_id');  
		
		$this->db->where('teacher_id', $teacher_id);
		
		if(!empty($mapel))
			$this->db->where('subject_id',$mapel);
	
		if(!empty($kelas))
			$this->db->where('class_id',$kelas);	

		if(!empty($startDate))
			$this->db->where('date(start_date) >=', date('Y-m-d', strtotime($startDate)));
		
		if(!empty($endDate))
			$this->db->where('date(end_date) <=', date('Y-m-d', strtotime($endDate)));

		$query = $this->db->get('exam');
		return $query->num_rows();
		
		
	}
	
}
