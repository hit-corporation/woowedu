<?php 

class IndoPustaka extends MY_Controller {

    private $username;
    private $password;

    public function __construct() {
        parent::__construct();

        $this->load->library('curl');

        $this->username = "OGE3ZWVlZTgtNTVhNy0xMWVkLTk0ZWUtOGZlY2YzZmEwYjQ2";
        $this->password = "indopustakatesting";
        $this->url = "http://api.indopustakaplus.com/api.php";
    }

    /**
     * Do request to api.indopustaka.com
     *
     * @param string $action
     * @param array $param
     * @return array
     */
    private function request(string $action = NULL, array $param = NULL): array {
        $params = !empty($param) ? array_merge(['action' => $action], $params) : ['action' => $action];
        $query = http_build_query($params);
        $url =  $this->url.'?'.$query;

        $options = [
            'encoding'        => 'UTF-8',
            'max_redirs'      => 10,
            'follow_location' => true
        ];

        $headers = [
            'Authorization: Basic '.base64_encode($this->username.':'.$this->password)
        ];

        $curl = $this->curl->setOption($options)->setHeader($headers)->request($url);
        return json_decode($curl, TRUE);
    }

    /**
     * Get All Categories
     *
     * @return void
     */
    public function get_categories(): void {
        $request = $this->request('kategori');


        if($request['message'][0] != 'Success')
        {
            http_response_code(422);
            echo json_encode(['success' => false, 'message' => 'Gagal mengambil data dari API !!!']);
            return;
        }
        
        $this->db->trans_start();
        foreach($request['data_kategori'] as $req)
        {
            $data = [
                'category_code' => $req['id_kategori'],
                'category_name' => $req['nama_kategori'],
                'total_books'   => $req['jumlah_buku_kategori']
            ];

            if($this->db->get_where('categories', ['category_code' => $req['id_kategori']])->num_rows() > 0)
                $this->db->update('categories', $data, ['category_code' => $req['id_kategori']]);
            else
                $this->db->insert('categories', $data);
        }
        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            http_response_code(422);
            echo json_encode(['success' => false, 'message' => 'Data gagal di simpan !!!']);
            return;
        }
        $this->db->trans_commit();

        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'Data berhasil di simpan !!!']);
    }

    /**
     * Get All ebooks
     *
     * @return void
     */
    public function get_books(): void {
        $request = $this->request('ebook');


        if($request['message'][0] != 'Success')
        {
            http_response_code(422);
            echo json_encode(['success' => false, 'message' => 'Gagal mengambil data dari API !!!']);
            return;
        }
        /*
         "id_buku": "B0000000000000007245",
            "judul": "BUKU TEKS PENDAMPING Tematik 5 Tema 2: Udara Bersih Bagi Kesehatan-SD MI KelasV",
            "sinopsis": "BUKU TEKS PENDAMPING Tematik 5 Tema 2: Udara Bersih Bagi Kesehatan-SD MI KelasV",
            "penulis": " Drs. Samidi, M.Pd.",
            "penerbit": "Penerbit ANDI",
            "id_kategori": "91",
            "nama_kategori": "Pelajaran",
            "ISBN": "978-979-29-9945-7",
            "halaman": "vi+170",
            "edisi": "i",
            "tahun_terbit": "2019",
            "cover": "https://api.indopustakaplus.com/url/cover/BUKU TEKS PENDAMPING Tematik 5 Tema 2 Udara Bersih Bagi Kesehatan - SDMI Kelas V.jpg",
            "pdf_url": "https://api.indopustakaplus.com/url/pdf/BUKU TEKS PENDAMPING Tematik 5 Tema 2 Udara Bersih Bagi Kesehatan - SDMI Kelas V.pdf",
            "status_het": "N",
            "stok_buku": "10",
            "stok_current": "10"
        */
        
        $this->db->trans_start();
        foreach($request['data_ebook'] as $req)
        {
            $data = [
                'book_code'     => $req['id_buku'],
                'title'         => $req['judul'],
                'description'   => $req['sinopsis'],
                'isbn'          => $req['ISBN'],
                'author'        => $req['penulis'],
                'publish_year'  => $req['tahun_terbit'],
                'category_id'   => $req['id_kategori'],
                'total_pages'   => (explode('+', $req['halaman']))[1],
                'qty'           => $req['stok_buku'],
                'file_1'        => $req['pdf_url'],
                'cover_img'     => $req['cover']
            ];

            if($this->db->get_where('ebooks', ['book_code' => $req['id_buku']])->num_rows() > 0)
                $this->db->update('ebooks', $data, ['book_code' => $req['id_buku']]);
            else
                $this->db->insert('ebooks', $data);
        }
        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            http_response_code(422);
            echo json_encode(['success' => false, 'message' => 'Data gagal di simpan !!!']);
            return;
        }
        $this->db->trans_commit();

        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'Data berhasil di simpan !!!']);
    }
}