<?php
class Pengumuman extends CI_Controller{
    
    /*function __construct() {
        parent::__construct();
        $this->load->model('model_barang');
		$this->load->model('model_webjob');
        //chek_session();
    }*/


    function index()
    {
		if(isset($_POST['submit'])){
			$pil = $this->input->post('cari');
			$data['cari'] = $pil;
			$pil = str_replace(' ','-o-', $pil);
		}else{
			$pil = '';
			$data['cari'] = '';
		}

		/*
		$datax = json_decode(file_get_contents('http://localhost/api_evn/get_nav'));
		foreach($datax as $dt){
			$data[$dt->menu] = $dt->link_nav;
		}
		*/
		$datay = file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=get_nav');
		$dataz = explode('-%-',$datay);
		$datax = json_decode($dataz[1]);
		foreach($datax as $dt){
			$data[$dt->menu] = $dt->link_nav;
		}
		 
		$datay = file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=pengumuman&search='.$pil);
		$dataz = explode('-%-',$datay);
		$data_job = json_decode($dataz[1]);
		//if($dataz[1]) $data_job ='';
		$data['tampil'] = "";
		foreach($data_job as $dt){
			$file_1=str_replace("C:\inetpub\wwwroot\hris_wika","https://hcis.wika.co.id",$dt->file_1);
			$file_2=str_replace("C:\inetpub\wwwroot\hris_wika","https://hcis.wika.co.id",$dt->file_2);
			$file_3=str_replace("C:\inetpub\wwwroot\hris_wika","https://hcis.wika.co.id",$dt->file_3);
			$file_4=str_replace("C:\inetpub\wwwroot\hris_wika","https://hcis.wika.co.id",$dt->file_4);
			$file_5=str_replace("C:\inetpub\wwwroot\hris_wika","https://hcis.wika.co.id",$dt->file_5);
			$file_6=str_replace("C:\inetpub\wwwroot\hris_wika","https://hcis.wika.co.id",$dt->file_6);
		

			$data['tampil'] .= "<h6 style='color:red;'>".date("d M Y",strtotime($dt->tanggal_buat))."</h6>";
			$data['tampil'] .= "<h6>".$dt->judul."</h6><ul>";
			if(strlen($dt->file_1) > 2){ $data['tampil'] .= "<li><a href='".$file_1."' target='_blank'>".$dt->filename_1."</a></li>";}
			if(strlen($dt->file_2) > 2){ $data['tampil'] .= "<li><a href='".$file_2."' target='_blank'>".$dt->filename_2."</a></li>";}
			if(strlen($dt->file_3) > 2){ $data['tampil'] .= "<li><a href='".$file_3."' target='_blank'>".$dt->filename_3."</a></li>";}
			if(strlen($dt->file_4) > 2){ $data['tampil'] .= "<li><a href='".$file_4."' target='_blank'>".$dt->filename_4."</a></li>";}
			if(strlen($dt->file_5) > 2){ $data['tampil'] .= "<li><a href='".$file_5."' target='_blank'>".$dt->filename_5."</a></li>";}
			if(strlen($dt->file_6) > 2){ $data['tampil'] .= "<li><a href='".$file_6."' target='_blank'>".$dt->filename_6."</a></li>";}
			$data['tampil'] .= "</ul><hr>";
		}

		$data['tanggal']='';
		$data['detail']='';
		/*
		$a=1;
		$datax = json_decode(file_get_contents('http://localhost/api_evn/get_pengumuman'));
		foreach($datax as $dt){
			if($a == '1'){
				$data['tampil'] = "<h5>".date("d/m/Y",strtotime($dt->tanggal))."</h5><ul><li><a href='#'>".$dt->detail."</a></li>";
			}else{
				if($data['tanggal'] <> $dt->tanggal){
					$data['tampil'] .= "</ul><hr><h5>".date("d/m/Y",strtotime($dt->tanggal))."</h5><ul><li><a href='#'>".$dt->detail."</a></li>";
				}else{
					$data['tampil'] .= "<li><a href='#'>".$dt->detail."</a></li>";$data['tampil'] .= "<li><a href='#'>".$dt->detail."</a></li>";
				}
			}
			$data['tanggal'] = $dt->tanggal;
			$a++;
		}
		$data['tampil'] .= "</ul>";
		*/
		$data['des_menu'] = "PENGUMUMAN";
		
		$this->load->view('pengumuman',$data);
    }
}