<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sesi extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
		$this->load->model('model_sesi');
	}
	
	public function index()
	{ 
		$data['jsdata'] = $this->loaddata();
		$this->load->view('header');
		$this->load->view('sesi/index',$data);
		$this->load->view('footer'); 
	}
	
	public function loaddata()
	{
		$params['sdate']='2023-08-21';
		$params['edate']='2023-08-23';
		$params['teacher_id'] = '92508';
		$customerdata = $this->model_sesi->datasesi($params);
		foreach($customerdata->result() as $data) {
			$tanggal =  $data->sesi_date;			   
			
/*				
sesi_id
sesi_title
sesi_date
sesi_jam_start
sesi_jam_end
teacher_id
{start: days[5] + " 18:00", end: days[5] + " 21:00", resourceId: 2, title: "asdsadsad", color: "#FF0000"},
*/
			$list[] = array('resourceId'=>$data->sesi_id,'title'=>$data->sesi_title,'start'=>$data->sesi_date.' '.$data->sesi_jam_start,'end'=>$data->sesi_date.' '.$data->sesi_jam_end  );
		}	
		if($list == null){$list[] = array(0);}
		return json_encode($list);			
		
	}	
}
