<?php

class Model_sesi extends CI_Model {
 
 
	public function datasesi($param=array())
	{		
  
		$this->db->select('sesi_id,	sesi_title,	sesi_date,sesi_jam_start,	sesi_jam_end,	teacher_id');
		$this->db->from('sesi');	 
		$this->db->where('sesi_date >=',date("Y-m-d",strtotime($param['sdate']))); 
		$this->db->where('sesi_date <=',date("Y-m-d",strtotime($param['edate']))); 
		$this->db->where('teacher_id',$param['teacher_id']); 

		$query = $this->db->get();
		return $query;
	}

	public function data_sesi_student($param = []){
		$this->db->select('sesi_id,	sesi_title,	sesi_date, sesi_jam_start, sesi_jam_end, sesi.teacher_id, teacher_name, subject.subject_name');
		$this->db->from('sesi');
		$this->db->join('teacher', 'teacher.teacher_id = sesi.teacher_id', 'left');
		$this->db->join('materi', 'materi.materi_id = sesi.materi_id', 'left');
		$this->db->join('subject', 'subject.subject_id = materi.subject_id', 'left');
		$this->db->where('sesi_date >=', date("Y-m-d",strtotime($param['sdate']))); 
		$this->db->where('sesi_date <=', date("Y-m-d",strtotime($param['edate']))); 

		if(isset($param['class_id'])){
			$this->db->where('sesi.class_id', $param['class_id']);
		}

		// $this->db->where_in('sesi.teacher_id', $param['teacher_id']);

		$query = $this->db->get();
		return $query;
	}
	
	
}
