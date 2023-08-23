<?php

class Model_news extends CI_Model {

	public function get_news(){
		$this->db->limit('5');
		$this->db->order_by('tanggal', 'desc');
		return $this->db->get('news')->result_array();
	}

}
