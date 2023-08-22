<?php

class Model_subject extends CI_Model {

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

    public function getIdByClassName(string $name):int {
        $query = "SELECT class_id FROM class WHERE class_name";
        $res = $this->db->query($query);
        return $res->row()->class_id;
    }

    private function compiledQuery() {
        //$query = "SELECT a.subject_id as id_mapel, a.code as kode_mapel, a.subject_name as nama_mapel, a.class_id as id_kelas, b.class_name as nama_kelas
         //         FROM \"subject\" a JOIN kelas b ON a.class_id=b.class_id ";
				
				$query = "SELECT a.subject_id as id_mapel, a.code as kode_mapel, a.subject_name as nama_mapel, a.class_level_id as id_kelas, b.class_level_name as nama_kelas
                  FROM \"subject\" a JOIN class_level b ON a.class_level_id=b.class_level_id ";				

        return $query;
    }
}