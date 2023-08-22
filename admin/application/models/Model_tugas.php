<?php

class Model_tugas extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getAll(array $filters = NULL, int $limit = NULL, int $offset = NULL) {
        $query = $this->queryString();

        $filt = [];

        if(!empty($filters[2]['search']['value'])) {
            $query .= " AND a.code LIKE ?";
            $filt[] = '%'.$filters[2]['search']['value'].'%';
        }

        if(!empty($filters[3]['search']['value'])) {
            $query .= " AND d.materi_id=?";
            $filt[] = $filters[3]['search']['value'];
        }

        if(!empty($filters[5]['search']['value'])) {
            $query .= " AND b.class_id=?";
            $filt[] = $filters[5]['search']['value'];
        }

        if(!empty($filters[7]['search']['value'])) {
            $query .= " AND c.teacher_id=?";
            $filt[] = $filters[7]['search']['value'];
        }

        if(!empty($limit) && !is_null($offset))
            $query .= " LIMIT {$limit} OFFSET {$offset}";

        $res = $this->db->query($query, [$filt]);
        return $res->result_array();
    }

    public function countAll(array $filters = NULL) {
        $query = $this->queryString();

        $res = $this->db->query($query);
        return $res->num_rows();
    }

    private function queryString() {
        $query = "SELECT a.*, b.class_id, b.class_name, c.teacher_id, c.teacher_name, d.materi_id, d.title
                  FROM task a 
                  JOIN kelas b ON a.class_id=b.class_id
                  JOIN teacher c ON a.teacher_id=c.teacher_id
                  JOIN materi d ON a.materi_id=d.materi_id
                  WHERE 1=1";
        return $query;
    }
}