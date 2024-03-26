<?php

class Model_asesmen extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Get All Books Data
     *
     * @param int $limit
     * @param int $offset
     * @param array $filter
     * @return array
     */
    public function list(int $limit = NULL, int $offset = NULL, array $filter = NULL): array {
        $param = [];
        $query = "SELECT a.*, b.category_name FROM ebooks a, categories b, publishers c 
                  WHERE 
                        a.category_id::text=b.category_code AND
                        a.publisher_id=c.id";
        
        if(!empty($filter['title']))
        {
            $query .= " AND LOWER(a.title) LIKE ?";
            $param[] = '%'.strtolower(trim($filter['title'])).'%';
        }

        if(!empty($filter['category']))
        {
            $query .= " AND b.category_code=?";
            $param[] = $filter['category'];
        }
            

        if(!empty($limit) && !is_null($offset))
            $query .= " LIMIT {$limit} OFFSET {$offset}";

        $get = $this->db->query($query, $param);
        return $get->result_array() ?? [];
    }

    /**
     * Count All List 
     *
     * @return void
     */
    public function count_list(array $filter = NULL): int {
        $param = [];
        $query = "SELECT a.*, b.category_name FROM ebooks a, categories b, publishers c 
                  WHERE 
                        a.category_id::text=b.category_code AND
                        a.publisher_id=c.id";
        
        if(!empty($param['title']))
        {
            $query .= " AND a.title LIKE ?";
            $param[] = '%'.$filter['title'].'%';
        }

        if(!empty($filter['category']))
        {
            $query .= " AND a.category_id=?";
            $param[] = $filter['category'];
        }

        $get = $this->db->query($query, $param);
        return $get->num_rows() ?? 0;
    }

    /**
     * get by id
     *
     * @return array
     */
    public function get($id): array {
        $get = $this->db->select('a.*, b.category_name, c.publisher_name')
                        ->where('a.id', $id)
                        ->join('categories b', 'a.category_id=b.category_code')
                        ->join('publishers c', 'a.publisher_id=c.id')
                        ->get('ebooks a');

        return $get->row_array();
    }

    /**
     * Get a book dta by book code
     *
     * @param string $code
     * @return array
     */
    public function getByCode(string $code): array {
        $get = $this->db->select('a.*, b.category_name, c.publisher_name')
                        ->where('a.book_code', $code)
                        ->join('categories b', 'a.category_id=b.category_code')
                        ->join('publishers c', 'a.publisher_id=c.id')
                        ->get('ebooks a');
        return $get->row_array();

    }

    private function data_generator() {

    }
}
