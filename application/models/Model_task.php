<?php

class Model_task extends CI_Model {

	public function __construct(){
        parent::__construct();
    }

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

	public function get_teacher_task_by_id($teacher_id){
		$this->db->select('t.*, tc.teacher_name, sj.subject_name');
		$this->db->from('student s');
		$this->db->join('task t', 't.class_id = s.class_id', 'left');
		$this->db->join('teacher tc', 'tc.teacher_id = t.teacher_id', 'left');
		$this->db->join('subject sj', 'sj.subject_id = t.subject_id', 'left');
		$this->db->limit('9');
		$this->db->where('t.teacher_id', $teacher_id);

		return $this->db->get()->result_array();
	}
	
	
	public function get_student_task($limit = null, $page = null, $mapel, $startDate, $endDate){
		
		$username 	= $this->session->userdata('username');
		$this->db->select('t.*,   sj.subject_name');
		$this->db->from('student s');
		$this->db->join('task t', 't.class_id = s.class_id', 'left');
		$this->db->join('materi m', 'm.subject_id = t.subject_id', 'left');
		$this->db->join('subject sj', 'sj.subject_id = m.subject_id', 'left'); 
		$this->db->where('s.nis', $username);
		
		if(!empty($mapel))
			$this->db->where('t.subject_id',$mapel);

		if(!empty($startDate))
			$this->db->where('date(due_date) >=', date('Y-m-d', strtotime($startDate)));
		
		if(!empty($endDate))
			$this->db->where('date(due_date) <=', date('Y-m-d', strtotime($endDate)));
 
		$this->db->limit($limit, $page);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function get_student_total_task($mapel, $startDate, $endDate){
		
		$username 	= $this->session->userdata('username');  
		$this->db->select('t.task_id');
		$this->db->from('student s');
		$this->db->join('task t', 't.class_id = s.class_id', 'left');
		
		$this->db->where('nis', $username);
		
		if(!empty($mapel))
			$this->db->where('t.subject_id',$mapel);

		if(!empty($startDate))
			$this->db->where('date(due_date) >=', date('Y-m-d', strtotime($startDate)));
		
		if(!empty($endDate))
			$this->db->where('date(due_date) <=', date('Y-m-d', strtotime($endDate)));

		$query = $this->db->get();
		return $query->num_rows();
	}	
	
	
	public function get_teacher_task($limit = null, $page = null, $mapel, $startDate, $endDate){
		
		$teacher_id 	= $this->session->userdata('teacher_id');
		$this->db->select('t.*, tc.teacher_name, sj.subject_name');
		$this->db->from('task t');
		$this->db->join('materi m', 'm.materi_id = t.materi_id', 'left');
		$this->db->join('teacher tc', 'tc.teacher_id = t.teacher_id', 'left');
		$this->db->join('subject sj', 'sj.subject_id = m.subject_id', 'left'); 
		$this->db->where('tc.teacher_id', $teacher_id);
		
		if(!empty($mapel))
			$this->db->where('t.subject_id',$mapel);

		if(!empty($startDate))
			$this->db->where('date(due_date) >=', date('Y-m-d', strtotime($startDate)));
		
		if(!empty($endDate))
			$this->db->where('date(due_date) <=', date('Y-m-d', strtotime($endDate)));
 
		$this->db->limit($limit, $page);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function get_teacher_total_task($mapel, $startDate, $endDate){
		
		$teacher_id 	= $this->session->userdata('teacher_id');  
		
		$this->db->where('teacher_id', $teacher_id);
		
		if(!empty($mapel))
			$this->db->where('t.subject_id',$mapel);

		if(!empty($startDate))
			$this->db->where('date(due_date) >=', date('Y-m-d', strtotime($startDate)));
		
		if(!empty($endDate))
			$this->db->where('date(due_date) <=', date('Y-m-d', strtotime($endDate)));

		$query = $this->db->get('task');
		return $query->num_rows();
	}		
	
	public function get_mapel(){
		
		
		$this->db->select('s.subject_id, subject_name');
		$this->db->from('subject s');
		
		
		$user_level 				= $this->session->userdata('user_level');
		
		if($user_level == 3 ){
			$teacher_id 	= $this->session->userdata('teacher_id'); 
			$this->db->join('subject_teacher st','s.subject_id=st.subject_id');
			$this->db->where('teacher_id',$teacher_id);
		}elseif($user_level == 4 ){
			$class_level_id 	= $this->session->userdata('class_level_id');  
			$this->db->where('class_level_id',$class_level_id);			
		}
		return $this->db->get()->result_array();
	}

}
