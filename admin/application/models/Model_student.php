<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_student extends CI_Model{

  public function __construct()
  {
		parent::__construct();	
  }
 

// Student
	public function get_all_student(array $filter = NULL, int $limit = NULL, int $offset = NULL) {
		$this->compiledStudentQuery();
		if(!empty($filter) && is_array($filter))  {
				$i=0;
				foreach($filter as $f) {
						if(empty($f['search']['value'])) continue;
						$key= $f['data'];
						$val = $f['search']['value'];
						if($i === 0) 
								$this->db->where('LOWER('.$key.') LIKE \'%'.$this->db->escape_like_str(strtolower($val)).'%\''); 
						else
								$this->db->or_where('LOWER('.$key.') LIKE \'%'.$this->db->escape_like_str(strtolower($val)).'%\'');
						$i++;
				}
		}
		if(!empty($limit))
		$this->db->limit($limit, $offset);
		$res = $this->db->get();
		return $res->result_array();
	}	

	public function count_all_student(array $filter = NULL) {
		$this->compiledStudentQuery();
		if(!empty($filter) && is_array($filter))  {
				$i=0;
				foreach($filter as $f) {
						if(empty($f['search']['value'])) continue;
						$key= $f['data'];
						$val = $f['search']['value'];
						if($i === 0) 
								$this->db->where('LOWER('.$key.') LIKE \'%'.$this->db->escape_like_str(strtolower($val)).'%\''); 
						else
								$this->db->or_where('LOWER('.$key.') LIKE \'%'.$this->db->escape_like_str(strtolower($val)).'%\'');
						$i++;
				}
		}
		return $this->db->count_all_results();
	}

	public function getStudentOneById($id) {
		$this->compiledStudentQuery();
		$this->db->where('student_id', $id);
		$res = $this->db->get();
		return $res->row_array();
	}

	private function compiledStudentQuery() {
		$this->db->select('nis,student_id ,student_name ,student.class_id ,class_name,address ,phone ,email ,parent_name ,parent_phone ,parent_email,subject_name,subject_id '); 
		$this->db->join('kelas','kelas.class_id=student.class_id');
		$this->db->join('subject','kelas.class_id=subject.class_id');
		$this->db->get_compiled_select('student', FALSE);
	}   
	
	public function save_student(array $data) {
			return $this->db->insert('student', $data);
	}	
	
	public function modify_student(array $data) {
			$ins = ['student_id' => $data['student_id']];
			return $this->db->update('student', $data, $ins);
	}
		
	public function get_nilai_tugas($student_id,$subject_id)
	{
		$this->db->select('SUM(task_nilai) AS nilai');
		$this->db->from('task');
		$this->db->join('task_student','task.task_id=task_student.task_id');
		$this->db->join('materi','materi.materi_id = task.materi_id');		
		$this->db->join('subject','subject.subject_id=materi.subject_id');		
		$this->db->where('student_id',$student_id);		
		$this->db->where('subject.subject_id',$subject_id);		
		$query=$this->db->get();		 
		$nilai = $query->row()->nilai;		

		return (int)$nilai;
	}
	
	public function get_nilai_ujian($student_id,$subject_id)
	{
	
		$this->db->select('SUM(exam_total_nilai) AS nilai');
		$this->db->from('exam_student');
		$this->db->join('exam','exam_student.exam_id = exam.exam_id');		
		$this->db->join('subject','subject.subject_id=exam.subject_id');		
		$this->db->where('student_id',$student_id);		
		$this->db->where('subject.subject_id',$subject_id);		
		$query=$this->db->get();		 
		$nilai = $query->row()->nilai;		 
		return (int)$nilai;
	}
}
