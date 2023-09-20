<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sesi extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
		$this->load->model('model_sesi');
		if (!isset($_SESSION['username'])) redirect('auth/login');
	}
	
	public function index()
	{ 
	
		$saturday = strtotime('saturday this week');
		$sunday = strtotime('sunday this week');

		//echo date("d-m-Y",$sunday);
		//echo date("d-m-Y",$saturday);
	
		$data['jsdata'] = '';//$this->loaddata();
		$this->load->view('header');
		$this->load->view('sesi/index',$data);
		$this->load->view('footer'); 
	}

	public function delete()
	{
		$post = $this->input->post();
		$delete = $this->db->where('sesi_id', $post['id'])->delete('sesi');

		$res = ($delete) ?  ['success'=>true, 'message'=>'Data berhasil dihapus!'] : ['success'=>false, 'message'=>'Data gagal dihapus!'];
		header('Content-Type: application/json');
		echo json_encode($res);		
	}
	
	public function sesidetail($id)
	{
		$user_level = $this->session->userdata('user_level');
		$data = $this->db->where('sesi_id', $id)->get('sesi')->row_array();

		$button = '';
		if($user_level == 3){
			$button = '<a href="sesi/create/'.$id.'" class="btn btn-clear border d-inline me-1 rounded-5"><i class="bi bi-pencil-square"></i></a>
						<a class="btn btn-clear border d-inline rounded-5" onclick="deleteSesi('.$id.')"><i class="bi bi-trash3-fill"></i></a>';
		}

		$html = '<div class="container border rounded-4 bg-clear p-3 mb-3 news-item">
							<div class="d-flex justify-content-between">
								<h6 class="mb-2">'.$data['sesi_title'].'</h6>
							</div>
							<div class="d-flex justify-content-between"> 
								<p style="font-size: 14px;">'.$data['sesi_date'].'</p> 
								<p style="font-size: 14px;">'.$data['sesi_jam_start'].' - '.$data['sesi_jam_end'].'</p>
							</div>							
							<p style="font-size: 14px;">'.$data['sesi_note'].'</p>
							<div class="container d-flex justify-content-end">
								'.$button.'
							</div>
						</div>';
		echo $html;
	}
		
 
	public function loaddata()
	{
		$params['sdate']=$_GET['start'];
		$params['edate']=$_GET['end'];
		$params['teacher_id'] = $this->session->userdata['teacher_id'];
		$customerdata = $this->model_sesi->datasesi($params);
		foreach($customerdata->result() as $data) {
			$tanggal =  $data->sesi_date;	 
			$list[] = array('id'=>$data->sesi_id,'title'=>$data->sesi_title,'start'=>$data->sesi_date.'T'.$data->sesi_jam_start,'end'=>$data->sesi_date.'T'.$data->sesi_jam_end  );
		}	
		if($list == null){$list[] = array(0);}
		echo json_encode($list);			
		exit;
		
	}



	public function create($id = ''){
		// $this->session->userdata['teacher_id'];
		$post = $this->input->post();
		$data = [];

 
				
		if( isset($post['title'])){
			$teacher_id =  $this->session->userdata['teacher_id'];
			$data_save = ['sesi_title'=>$post['title'], 'sesi_date'=>$post['tanggal'], 'sesi_jam_start'=>$post['jamstart'], 'sesi_jam_end'=>$post['jamend'], 'teacher_id' =>$teacher_id,'sesi_note'=>trim($post['keterangan'])];

			if($post['id'] == ''){
				$save = $this->db->insert('sesi', $data_save);
				$res = ($save) ?  ['success'=>true, 'message'=>'Data berhasil disimpan!'] : ['success'=>false, 'message'=>'Data gagal disimpan!'];
			}else{
				$save = $this->db->where('sesi_id', $post['id'])->update('sesi', $data_save);
				$res =  ($save) ? ['success'=>true, 'message'=>'Data berhasil diupdate!'] :  ['success'=>false, 'message'=>'Data gagal diupdate!'];
			}

			header('Content-Type: application/json');
			echo json_encode($res); die;
		}

		if($id != '') $data = $this->db->where('sesi_id', $id)->get('sesi')->row_array();

		$this->load->view('header');
		$this->load->view('sesi/create', ['data'=>$data]);
		$this->load->view('footer');
	}

	
}
