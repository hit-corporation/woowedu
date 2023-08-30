<?php

class Model_sesi extends CI_Model {
 
 
	public function datasesi($param=array())
	{		
  
		$this->db->select('sesi_id,	sesi_title,	sesi_date,sesi_jam_start,	sesi_jam_end,	teacher_id');
		$this->db->from('sesi');	 
		$this->db->where('sesi_date >=',$param['sdate']); 
		$this->db->where('sesi_date <=',$param['edate']); 
		$this->db->where('teacher_id',$param['teacher_id']); 

		$query = $this->db->get();
		return $query;
	}
	
	
}
