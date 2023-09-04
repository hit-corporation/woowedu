<?php
defined('BASEPATH') or exit('No direct script access allowed');

// import phpoffice
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

class Book extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model(['book_model', 'kategori_model', 'publisher_model']);
		$this->load->library('form_validation');

	}

	/**
	 * View
	 *
	 * @return void
	 */
	public function index()
	{
		echo $this->template->render('index');
	}
	
	/**
	 * Get details of an item
	 *
	 * @param int $id
	 * @return void
	 */
	public function show($id): void
	{
		$data['book'] = $this->book_model->get_one($id);
		echo $this->template->render('show', $data);
	}

	/**
	 * Get All Data
	 *
	 * @return void
	 */
	public function get_all(): void
	{
		$book = $this->db->get('books')->result_array();
		echo json_encode($book, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
	}

	/**
	 * Get All Data with Pagination
	 *
	 * @return void
	 */
	public function get_all_paginated(): void
	{
		$draw = $this->input->get('draw') ?? NULL;
		$limit = $this->input->get('length');
		$offset = $this->input->get('start');
		$filters = $this->input->get('columns');
		$data = $this->book_model->get_all($filters, $limit, $offset);

		$response = [
			'draw' => $draw,
			'data' => $data,
			'recordsTotal' => $this->db->count_all_results('books'),
			'recordsFiltered' => $this->book_model->count_all($filters)
		];


		echo json_encode($response, JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_TAG);
	}

	/**
	 * Store new data to database
	 *
	 * @return void
	 */
	public function store(): void
	{
		$code 	   	= $this->input->post('book-code', TRUE);
		$title 	   	= $this->input->post('book-title', TRUE);
		$category  	= $this->input->post('book-category', TRUE);
		$author	   	= $this->input->post('book-author', TRUE);
		$publisher 	= $this->input->post('book-publisher', TRUE);
		$year	   	= $this->input->post('book-year', TRUE) ?? NULL;
		$isbn 	   	= $this->input->post('book-isbn', TRUE) ?? NULL;
		$description = $this->input->post('book-description', TRUE);
		$qty		= $this->input->post('book-qty', TRUE) ?? 0;
		$img 	   	= $_FILES['book-image'];

		$category_data = $this->kategori_model->get_all();
		$publisher_data = $this->publisher_model->get_all();

		// Validation
		$this->form_validation->set_rules('book-code', 'Kode Buku', 'required|is_unique[books.book_code]');
		$this->form_validation->set_rules('book-title', 'Judul', 'required|callback_is_new_book_unique['.$category.']', [
			'is_new_book_unique' => '{field} sudah tersedia pada database'
		]);
		$this->form_validation->set_rules('book-category', 'Kategori', 'required|integer|in_list['.implode(',', array_column($category_data, 'id')).']');
		$this->form_validation->set_rules('book-author', 'Penulis', 'required');
		$this->form_validation->set_rules('book-publisher', 'Penerbit', 'required|integer|in_list['.implode(',', array_column($publisher_data, 'id')).']');
		$this->form_validation->set_rules('book-year', 'Tahun Terbit', 'integer|exact_length[4]|greater_than[1922]');
		$this->form_validation->set_rules('book-qty', 'Stok', 'integer|greater_than_equal_to[0]');
		$this->form_validation->set_rules('book-isbn', 'ISBN');
		$this->form_validation->set_rules('book-description', 'Uraian');

		if(!$this->form_validation->run())
		{
			$resp = ['success' => false, 'errors' => $this->form_validation->error_array(), 'old' => $_POST];
			$this->session->set_flashdata('error', $resp);
			redirect($_SERVER['HTTP_REFERER']);
		}

		// Image
		$filename = NULL;
		if(intval($img['size']) > 0) {
			
			$img_conf = [
				'upload_path'	=> 'assets/img/books/',
				'allowed_types'	=> 'jpg|png|jpeg',
				'file_name'		=> str_replace(' ', '_', $title).'_'.$category.'.jpg',
				'file_ext_tolower'	=> true,
				'encrypt_name'	=> true
			];

			$this->load->library('upload', $img_conf);
			if(!$this->upload->do_upload('book-image'))
			{
				$resp = ['success' => false, 'message' => $this->upload->display_errors(), 'old' => $_POST];
				$this->session->set_flashdata('error', $resp);
				redirect($_SERVER['HTTP_REFERER']);
			}

			$filename = $this->upload->data('file_name');
			// resize
			$config['image_library'] = 'gd2';
			$config['source_image'] = $this->upload->data('full_path');
			$config['create_thumb'] = TRUE;
			$config['maintain_ratio'] = TRUE;
			$config['width']         = 128 - 50;
			$config['height']       = 165 - 50;

			$this->load->library('image_lib', $config);
			if(!$this->image_lib->resize())
			{
				$resp = ['success' => false, 'message' => $this->image_lib->display_errors(), 'old' => $_POST];
				$this->session->set_flashdata('error', $resp);
				redirect($_SERVER['HTTP_REFERER']);
			}
		}

		$data = [
			'book_code'		=> $code,
			'title'			=> $title,
			'author'		=> $author,
			'isbn'			=> $isbn,
			'publish_year'	=> $year,
			'category_id'	=> $category,
			'publisher_id'	=> $publisher,
			'description'	=> $description,
			'cover_img'		=> $filename,
			'qty'			=> $qty
		];

		if(!$this->db->insert('books', $data))
		{
			$resp = ['success' => false, 'message' => 'Data gagal di simpan', 'old' => $_POST];
			$this->session->set_flashdata('error', $resp);
			redirect($_SERVER['HTTP_REFERER']);
		}

		// Success
		$resp = ['success' => true, 'message' => 'Data berhasil di simpan', 'add_stock' => true, 'book_id' => $this->db->insert_id()];
		$this->session->set_flashdata('success', $resp);
		redirect($_SERVER['HTTP_REFERER']);
	}

	/**
	 * Edit Existing Data By ID
	 *
	 * @return void
	 */
	public function edit(): void {
		$id			= trim($this->input->post('book-id', TRUE));
		$code 	   	= trim($this->input->post('book-code', TRUE));
		$title 	   	= trim($this->input->post('book-title', TRUE));
		$category  	= trim($this->input->post('book-category', TRUE));
		$author	   	= trim($this->input->post('book-author', TRUE));
		$publisher 	= trim($this->input->post('book-publisher', TRUE));
		$year	   	= trim($this->input->post('book-year', TRUE)) ?? NULL;
		$isbn 	   	= trim($this->input->post('book-isbn', TRUE)) ?? NULL;
		$description = trim($this->input->post('book-description', TRUE));
		$qty		= trim($this->input->post('book-qty', TRUE)) ?? 0;
		$filename	= trim($this->input->post('book-img_name', TRUE));
		$img 	   	= $_FILES['book-image'];

		$category_data = $this->kategori_model->get_all();
		$publisher_data = $this->publisher_model->get_all();

		// Validation
		$this->form_validation->set_rules('book-id', 'ID', 'required|integer');
		$this->form_validation->set_rules('book-code', 'Kode Buku', 'required');
		$this->form_validation->set_rules('book-title', 'Judul', 'required|callback_is_edit_book_unique['.$category.'.'.$id.']', [
			'is_edit_book_unique' => '{field} sudah tersedia pada database'
		]);
		$this->form_validation->set_rules('book-category', 'Kategori', 'required|integer|in_list['.implode(',', array_column($category_data, 'id')).']');
		$this->form_validation->set_rules('book-author', 'Penulis', 'required');
		$this->form_validation->set_rules('book-publisher', 'Penerbit', 'required|integer|in_list['.implode(',', array_column($publisher_data, 'id')).']');
		$this->form_validation->set_rules('book-year', 'Tahun Terbit', 'integer|exact_length[4]|greater_than[1922]');
		$this->form_validation->set_rules('book-qty', 'Stok', 'integer|greater_than_equal_to[0]');
		$this->form_validation->set_rules('book-isbn', 'ISBN');
		$this->form_validation->set_rules('book-description', 'Uraian');

		if(!$this->form_validation->run())
		{
			$resp = ['success' => false, 'errors' => $this->form_validation->error_array(), 'old' => $_POST];
			$this->session->set_flashdata('error', $resp);
			redirect($_SERVER['HTTP_REFERER']);
		}

		// Image
		if(intval($img['size']) > 0) {
			
			$img_conf = [
				'upload_path'	=> 'assets/img/books/',
				'allowed_types'	=> 'jpg|png|jpeg',
				'file_name'		=> str_replace(' ', '_', $title).'_'.$category.'.jpg',
				'file_ext_tolower'	=> true,
				'encrypt_name'	=> true
			];

			$this->load->library('upload', $img_conf);
			if(!$this->upload->do_upload('book-image'))
			{
				$resp = ['success' => false, 'message' => $this->upload->display_errors(), 'old' => $_POST];
				$this->session->set_flashdata('error', $resp);
				redirect($_SERVER['HTTP_REFERER']);
			}

			$filename = $this->upload->data('file_name');

			$config['image_library'] = 'gd2';
			$config['source_image'] = $this->upload->data('full_path');
			$config['create_thumb'] = TRUE;
			$config['maintain_ratio'] = TRUE;
			$config['width']         = 128 - 50;
			$config['height']       = 165 - 50;

			$this->load->library('image_lib', $config);
			if(!$this->image_lib->resize())
			{
				$resp = ['success' => false, 'message' => $this->image_lib->display_errors(), 'old' => $_POST];
				$this->session->set_flashdata('error', $resp);
				redirect($_SERVER['HTTP_REFERER']);
			}
		}

		$data = [
			'book_code'		=> $code,
			'title'			=> $title,
			'author'		=> $author,
			'isbn'			=> $isbn,
			'publish_year'	=> $year,
			'category_id'	=> $category,
			'publisher_id'	=> $publisher,
			'description'	=> $description,
			'cover_img'		=> $filename,
			'qty'			=> $qty
		];

		if(!$this->db->update('books', $data, ['id' => $id]))
		{
			$resp = ['success' => false, 'message' => 'Data gagal di ubah', 'old' => $_POST];
			$this->session->set_flashdata('error', $resp);
			redirect($_SERVER['HTTP_REFERER']);
		}

		// Success
		$resp = ['success' => true, 'message' => 'Data berhasil di simpan'];
		$this->session->set_flashdata('success', $resp);
		redirect($_SERVER['HTTP_REFERER']);
	}

	/**
	 * Delete a book
	 *
	 * @param mixed $id
	 * @return boolean
	 */
	public function erase($id): void
	{
		if(!$this->db->update('books', ['deleted_at' => date('Y-m-d H:i:s')], ['id' => $id]))
        {
            $return = ['success' => false, 'message' =>  'Data Gagal Di Hapus'];
            $this->session->set_flashdata('error', $return);
            redirect($_SERVER['HTTP_REFERER']);
        }

        $return = ['success' => true, 'message' =>  'Data Berhasil Di Hapus'];
        $this->session->set_flashdata('success', $return);
        redirect($_SERVER['HTTP_REFERER']);
	}

	/**
	 * *********************************************************************************************
	 * 										CUSTOM VALIDATION
	 * *********************************************************************************************
	 */

	 /**
	  * Custom validation for checking if book code is unique 
	  *
	  * @param string $str
	  * @return void
	  */
	 public function is_new_book_code_unique(string $str): void
	 {

	 }

	 /**
	  * Custom check uniqueness of new book
	  *
	  * @param string  $str
	  * @param mixed $args2
	  * @return boolean
	  */
	public function is_new_book_unique($str, $args2): bool
	{
		return $this->db->get_where('books', ['title' => $str, 'category_id' => $args2, 'deleted_at' => NULL])->num_rows() > 0 ? FALSE : TRUE;
	}

	/**
	 * Undocumented functionCustom check uniqueness of edited book
	 *
	 * @param string  $str
	 * @param mixed  $args2
	 * @return boolean
	 */
	public function is_edit_book_unique($str, $args2): bool
	{
		$args = explode('.', $args2);

		$this->db->where('books.id <> '.$args[1])
				 ->where('title', $str)
				 ->where('category_id', $args[0])
				 ->where('deleted_at', NULL);
		$check = $this->db->get('books');
		return $check->num_rows() > 0 ? FALSE : TRUE;
	}

	/**
	 * import book from excel
	 *
	 * @param string  $str
	 * @return boolean
	 */
	public function upload(): void {

		$config['upload_path'] = 'assets/files/uploads';
		$config['allowed_types'] = 'xlsx|xls';
		$config['max_size'] = 10000;
		$config['file_name'] = 'book_'.time();

		$this->load->library('upload', $config);
		if(!$this->upload->do_upload('file')) {
			$resp = ['success' => false, 'message' => $this->upload->display_errors(), 'old' => $_POST];
			$this->session->set_flashdata('error', $resp);
			redirect($_SERVER['HTTP_REFERER']);
		}

		$upload_data = $this->upload->data();
		$filename = $upload_data['file_name'];

		$inputFileName = 'assets/files/uploads/'.$filename;
		try {
			$inputFileType = IOFactory::identify($inputFileName);
			$objReader = IOFactory::createReader($inputFileType);
			$objPHPExcel = $objReader->load($inputFileName);
		} catch(Exception $e) {
			die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
		}

		$sheet = $objPHPExcel->getSheet(0);
		$highestRow = $sheet->getHighestRow();
		$highestColumn = $sheet->getHighestColumn();

		$book_data = [];
		for ($row = 2; $row <= $highestRow; $row++) {
			$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);

			$publisher_id = $this->db->get_where('publishers', [ 'publisher_name' => $rowData[0][3] ])->row_array();

			$book_data[] = [
				'cover_img' => $rowData[0][0],
				'title' => $rowData[0][1],
				'category_id' => $rowData[0][2],
				'publisher_id' => $publisher_id['id'],
				'author' => $rowData[0][4],
				'isbn' => $rowData[0][5],
				'publish_year' => $rowData[0][6],
				'qty' => $rowData[0][7],
				'description' => $rowData[0][8],
			];
		}

		$this->db->insert_batch('books', $book_data);
		$resp = ['success' => true, 'message' => 'Data berhasil di simpan'];
		$this->session->set_flashdata('success', $resp);
		redirect($_SERVER['HTTP_REFERER']);

	}

	/**
	 * Import dataa from excel
	 *
	 * @return void
	 */
	public function import(): void {
		require_once APPPATH.'third_party'.DIRECTORY_SEPARATOR.'xlsx'.DIRECTORY_SEPARATOR.'SimpleXLSX.php';
		// Upload
		$config['upload_path'] = 'assets/files/uploads';
		$config['allowed_types'] = 'xlsx|xls';
		$config['max_size'] = 10000;
		$config['overwrite'] = TRUE;
		$config['file_name'] = 'book_'.time();

		$this->load->library('upload', $config);

		if(!$this->upload->do_upload('file'))
		{
			$resp = ['success' => false, 'message' => $this->upload->display_errors(), 'old' => $_POST];
			$this->session->set_flashdata('error', $resp);
			redirect($_SERVER['HTTP_REFERER']);
			return;
		}

		$data = $this->upload->data();
		// Parse Excel
		if(!$xlsx = SimpleXLSX::parse($data['full_path']))
			throw new Exception(SimpleXLSX::parseError());
		$excel = $xlsx->rows(0);
		unset($excel[0]);
		// looping data

		$this->db->trans_start();
		foreach($excel as $x)
		{
			$category = $this->db->get_where('categories', ['category_name' => trim($x[2])]);
			$publisher = $this->db->get_where('publishers', ['publisher_name' => trim($x[3])]);

			if($category->num_rows() == 0) continue;
			if($publisher->num_rows() == 0) continue;

			$ls = [
				'book_code'		=> $x[0],
				'title'			=> $x[1],
				'category_id'	=> ($category->row_array())['id'],
				'publisher_id'	=> ($publisher->row_array())['id'],
				'author'		=> $x[4],
				'isbn'			=> $x[5],
				'publish_year'	=> $x[6],
				'qty'			=> $x[7],
				'rack_no'		=> $x[8],
				'cover_img'		=> $x[9],
				'description'	=> $x[10]
			];
			
			if($this->db->get_where('books', ['book_code' => $x[0]])->num_rows() > 0)
				$this->db->update('books', $ls, ['book_code' => $x[0]]);
			else
				$this->db->insert('books', $ls);
		}
		$this->db->trans_complete();

		if($this->db->trans_status() == FALSE)
		{
			$this->session->set_flashdata('error', ['message' => 'Beberapa data gagal di input']);
			redirect($_SERVER['HTTP_REFERER']);
			return;
		}

		@unlink($data['full_path']);

		$this->session->set_flashdata('success', ['message' => 'Beberapa data berhasil di input']);
		redirect($_SERVER['HTTP_REFERER']);
	}
	

}
