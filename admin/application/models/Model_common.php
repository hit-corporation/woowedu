<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_common extends CI_Model{

  public function __construct()
  {
	parent::__construct();	
  }


	public function getMateriAll() {
			$query = "SELECT  materi_id, title FROM materi  ";
			$dataArr = []; 
			$res = $this->db->query($query, $dataArr);
			return $res->result_array();
	}
		
	public function getLastId($field,$table){
		$this->db->select('MAX('.$field.') AS last_id');
		$this->db->from($table);
		$query=$this->db->get();		
	//	echo 'asdasd'.$query->row()->last_id.'ooo';
		return $query->row()->last_id;
	}

	public function insertlog($note)
	{
		$actionlog = array(
				'user'			=> $this->session->userdata('username'),
				'ipadd'			=> $this->ipaddress->get_ip(),
				'logtime'		=> date("Y-m-d H:i:s"),
				'logdetail'		=> $note
		);
		$this->db->insert('actionlog', $actionlog);					
	}
	


	function getusername($userid)
	{
		$this->db->select('username');
		$this->db->from('users');
		$this->db->where('userid', $userid);
		$query=$this->db->get();
		if($query->num_rows()==1)
		{
			return $query->row()->username;
		}
		return false;
	}		
	
// Kelas

	public function get_class_level() {
		$this->db->select('class_level_id, class_level_name'); 
		$this->db->from('class_level');   
		$res = $this->db->get();
		return $res->result_array();
	}	

	public function get_all_class_level(array $filter = NULL, int $limit = NULL, int $offset = NULL) {
		$this->compiledLevelClassQuery(); 
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

	public function count_all_class_level(array $filter = NULL) {
		$this->compiledLevelClassQuery();
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
	

	public function get_all_class(array $filter = NULL, int $limit = NULL, int $offset = NULL) {
		$this->compiledClassQuery(); 
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

	public function count_all_class(array $filter = NULL) {
		$this->compiledClassQuery();
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

	public function getClassOneById($id) {
		$this->compiledClassQuery();
		$this->db->where('class_id', $id);
		$res = $this->db->get();
		return $res->row_array();
	}

	private function compiledLevelClassQuery() {
		$this->db->select('*'); 
		$this->db->get_compiled_select('class_level', FALSE);
	}   
	
	private function compiledClassQuery() {
		$this->db->select('a.*, b.class_level_name')->join('class_level b', 'a.class_level_id=b.class_level_id'); 
		$this->db->get_compiled_select('kelas a', FALSE);
	}   	
	
	public function save_class(array $data) {
			return $this->db->insert('kelas', $data);
	}	
	
	public function modify_class(array $data) {
			$ins = ['class_id' => $data['class_id']];
			return $this->db->update('kelas', $data, $ins);
	}
	

// Teacher
	public function get_all_teacher(array $filter = NULL, int $limit = NULL, int $offset = NULL) {
		$this->compiledTeacherQuery();
		if($this->session->userdata('user_level')==10){
			$this->db->where('sekolah_id',$this->session->userdata('sekolah_id')); 
		}
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

	public function count_all_teacher(array $filter = NULL) {
		$this->compiledTeacherQuery(); 
		//var_dump($_SESSION);
		if($this->session->userdata('user_level')==10){
			$this->db->where('sekolah_id',$this->session->userdata('sekolah_id')); 
		}		
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

	public function getTeacherOneById($id) {
		$this->compiledTeacherQuery();
		$this->db->where('teacher_id', $id);
		$res = $this->db->get();
		return $res->row_array();
	}

	private function compiledTeacherQuery() {
		$this->db->select('*'); 
		$this->db->get_compiled_select('teacher', FALSE);
	}   
	
	public function save_teacher(array $data) {
			return $this->db->insert('teacher', $data);
	}	
	
	public function modify_teacher(array $data) {
			$ins = ['teacher_id' => $data['teacher_id']];
			return $this->db->update('teacher', $data, $ins);
	}
	
	

// Student
	public function get_all_student(array $filter = NULL, int $limit = NULL, int $offset = NULL) {
		$this->compiledStudentQuery();
		if($this->session->userdata('user_level')==10){
			$this->db->where('sekolah_id',$this->session->userdata('sekolah_id')); 
		}				
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
		if($this->session->userdata('user_level')==10){
			$this->db->where('sekolah_id',$this->session->userdata('sekolah_id')); 
		}				
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

	// private function compiledStudentQuery() {
	// 	$this->db->select('nis,student_id ,student_name ,student.class_id ,class_name,address ,phone ,email ,parent_name ,parent_phone ,parent_email '); 
	// 	$this->db->join('kelas','kelas.class_id=student.class_id');
	// 	$this->db->get_compiled_select('student', FALSE);
	// }   

	private function compiledStudentQuery() {
		$this->db->select('a.nis, a.student_id, a.student_name, a.class_id, class_name, a.address, a.phone, a.email, a.gender, d.parent_id, d.name as parent_name, 
							d.phone as parent_phone, d.email as parent_email, subject_name, subject_id'); 
		$this->db->join('kelas b','b.class_id=a.class_id', 'left');
		$this->db->join('subject c','b.class_id=c.class_id', 'left');
		$this->db->join('parent d', 'd.parent_id=a.parent_id', 'left');
		$this->db->get_compiled_select('student a', FALSE);
	}   
	
	/**
	 * Save new student 
	 *
	 * @param array $data
	 * @return void
	 */
	public function save_student(array $data): bool {
			$this->db->trans_start();
			$this->db->insert('student', $data);

			$newUser = [
				'username' 		=> $data['nis'],
				'user_level'	=> 4,
				'password'		=> password_hash('123456', PASSWORD_DEFAULT)
			];

			$this->db->insert('users', $newUser);

			$this->db->trans_complete();

			if($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
				return false;
			}
			$this->db->trans_commit();

			return true;
	}	
	
	public function modify_student(array $data) {
			$ins = ['student_id' => $data['student_id']];
			return $this->db->update('student', $data, $ins);
	}
		
}
