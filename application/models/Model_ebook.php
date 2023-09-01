<?php

class Model_ebook extends CI_Model {

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

        if(!empty($limit) && !empty($offset))
            $this->db->limit($limit, $offset);

        $get = $this->db
                        ->select('a.*, b.category_name')
                        ->join('categories b', 'a.category_id=b.id')->get('ebooks a');

        return $get->result_array() ?? [];
    }

    public function get() {
        $get = $this->db
        ->select('a.*, b.category_name')
        ->where()
        ->join('categories b', 'a.category_id=b.id')->get('ebooks a');
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
                        ->join('categories b', 'a.category_id=b.id')
                        ->join('publishers c', 'a.publisher_id=c.id')
                        ->get('ebooks a');
        return $get->row_array();

    }

    private function data_generator() {

    }
}
