<?php

class Model_sekolah extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function getAll(array $filter = NULL, int $limit = NULL, int $offset = NULL) {
        $query = $this->compiledQuery();
        if(!empty($limit) && !is_null($offset))
            $query .= " LIMIT {$limit} OFFSET {$offset}";
        $res = $this->db->query($query);
        return $res->result_array();
    }

    public function countAll(array $filter = NULL):int {
        $query = $this->compiledQuery();

        $res = $this->db->query($query);
        return $res->num_rows();
    }

    private function compiledQuery() {
 
				$query = "SELECT sekolah_id, sekolah_kode, sekolah_nama,  sekolah_alamat,sekolah_phone 
                  FROM sekolah ";				

        return $query;
    }
}