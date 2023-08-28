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

        $get = $this->db->get('ebooks');

        return $get->result_array() ?? [];
    }

    public function get() {

    }

    public function getByCode() {

    }

    private function data_generator() {

    }
}
