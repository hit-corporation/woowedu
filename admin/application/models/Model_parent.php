<?php

class Model_parent extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Get All Parent Data
     *
     * @param array $limit
     * @param int $offset
     * @param int $filter
     * @return array
     */
    public function get_all(int $limit = NULL, int $offset = NULL, array $filter = NULL): array {
        $this->db->select("a.parent_id, a.username, a.name, a.address, a.gender, a.phone, a.email, 
                           string_agg(b.student_id::text, ',') as student_id, string_agg(b.student_name, ',') as wali_dari ", NULL, FALSE)
                 ->join('student b', 'a.parent_id=b.parent_id', 'left outer')
                 ->group_by('a.parent_id, a.username, a.name, a.address, a.gender, a.phone, a.email');

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
        
        if(!empty($limit) && !is_null($offset))
            $this->db->limit($limit, $offset);
        $get = $this->db->get('parent a');

        return $get->result_array() ?? [];
    }

    /**
     * Count all results
     *
     * @param array $filter
     * @return integer
     */
    public function count_all(array $filter = NULL): int {
        $this->db->select("a.parent_id, a.username, a.name, a.address, a.gender, string_agg(b.student_name, ',') as wali_dari ", NULL, FALSE)
                 ->join('student b', 'a.parent_id=b.parent_id', 'left outer')
                 ->group_by('a.parent_id, a.username, a.name, a.address, a.gender');

        $get = $this->db->get('parent a');

        return $get->num_rows() ?? 0;
    }
}
