<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class lowongan extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$datay = file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=lowongan&jenis=reg');
        $dataz = explode('-%-',$datay);
		$dt_job = $dataz[1];
        $data['posisi'] = json_decode($dataz[1]);
		
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
		
		$data['gmbr'] = "/assets2/img/mtshe.png";

		$data['link_1'] = "/lowongan/low_dtl/reg-o-";
		$data['link_mtshe'] = "/lowongan/low_dtl/mtshe-reg";
		$data['link_ppcp'] = "/lowongan/low_dtl/ppcp-reg";
		
		$data['link_r'] = "/lowongan/pil_lwg/reg";
		$data['link_j'] = "/lowongan/pil_lwg/job";
		$data['class_r'] = "btn btn-primary btn-round btn-lg";
		$data['class_j'] = "btn btn-primary btn-simple btn-round btn-lg";
        //$data['record'] = $this->model_barang->tampil_data_e()->result();
		//$data['akses'] = $this->model_barang->hak_akses($this->session->userdata('username'))->row_array();
		//$this->load->view('lowongan_r',$data);
		if($dt_job<>'[]'){
			$data['isi_msg'] = '';
		}else{
			$data['isi_msg'] = 'Saat ini lowongan reguler tidak tersedia.';
		}
		

		$this->load->view('lowongan_r',$data);
	}

	public function pil_lwg()
    {
		$pil=  $this->uri->segment(3);

		$datay = file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=lowongan&jenis='.$pil);
        $dataz = explode('-%-',$datay);
		$dt_job = $dataz[1];
        $data['posisi'] = json_decode($dataz[1]);
		
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

        $datax = json_decode(file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=list_gambar&set_foto=5'));
		foreach($datax as $dt){
		$data['jobfair']=$dt->foto;
		}
		
		$data['link_1'] = "/lowongan/low_dtl/".$pil."-o-";
		$data['link_mtshe'] = "/lowongan/low_dtl/mtshe-".$pil;
		$data['link_ppcp'] = "/lowongan/low_dtl/ppcp-".$pil;
			
        //$data['record'] = $this->model_barang->tampil_data_e()->result();
		//$data['akses'] = $this->model_barang->hak_akses($this->session->userdata('username'))->row_array();
		
		//$this->load->view('lowongan_r',$data);
		$data['isi_msg'] = '';

		if($pil == 'job'){
			$data['gmbr'] = "/assets2/img/ppcp.png";
			$data['link_r'] = "/lowongan/pil_lwg/reg";
			$data['link_j'] = "/lowongan/pil_lwg/job";
			$data['class_r'] = "btn btn-primary btn-simple btn-round btn-lg";
			$data['class_j'] = "btn btn-primary btn-round btn-lg";
			if($dt_job<>'[]'){
			$data['isi_msg'] = '';
			}else{
				$data['isi_msg'] = 'Saat ini lowongan jobfair tidak tersedia.';
			}
			$this->load->view('lowongan_r',$data);
		}else{
			if($dt_job<>'[]'){
				$data['isi_msg'] = '';
			}else{
				$data['isi_msg'] = 'Saat ini lowongan reguler tidak tersedia.';
			}
			$data['gmbr'] = "/assets2/img/mtshe.png";
			$data['link_r'] = "/lowongan/pil_lwg/reg";
			$data['link_j'] = "/lowongan/pil_lwg/job";
			$data['class_r'] = "btn btn-primary btn-round btn-lg";
			$data['class_j'] = "btn btn-primary btn-simple btn-round btn-lg";
			$this->load->view('lowongan_r',$data);
		}
		
    }
	
	public function low_dtl()
    {
		$pil=  $this->uri->segment(3);
		$pil2 = explode("-o-",$pil);

		$datay = file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=lowongan_kota&rpk='.$pil2[1]);
		$dataz = explode('-%-',$datay);
		//echo 'http://webjob.wika.co.id/lama/wj_api3.php?act=lowongan_kota&rpk='.$pil2[1]; die();
		$data['kota'] = json_decode($dataz[1]);
		
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
		
		if($pil2[0] == 'job'){
			$data['link_r'] = "/lowongan/pil_lwg/reg";
			$data['link_j'] = "/lowongan/pil_lwg/job";
			$data['class_r'] = "btn btn-primary btn-simple btn-round btn-lg";
			$data['class_j'] = "btn btn-primary btn-round btn-lg";
			$data['link_dtl2'] = "/lowongan/low_dtl2/job-o-".$pil2[1];
		}else{
			$data['link_r'] = "/lowongan/pil_lwg/reg";
			$data['link_j'] = "/lowongan/pil_lwg/job";
			$data['class_r'] = "btn btn-primary btn-round btn-lg";
			$data['class_j'] = "btn btn-primary btn-simple btn-round btn-lg";
			$data['link_dtl2'] = "/lowongan/low_dtl2/reg-o-".$pil2[1];
		}
		
		$this->load->view('lowongan_dtl',$data);
		
    }
	
	public function low_dtl2()
    {
		$pil=  $this->uri->segment(3);
		$pil2 = explode("-o-",$pil);

		$datay = file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=lowongan_dtl&req_id='.$pil2[1]);
		$dataz = explode('-%-',$datay);
		$data_job = json_decode($dataz[1]);
		
		foreach($data_job as $dt){
			$data['deskripsi'] = '<li style=padding:3px;>'.str_replace("\n","</li>\n<li style=padding:3px;>",trim($dt->JOB_DESCRIPTION,"\n")).'</li>';
			$data['syarat'] = '<li style=padding:3px;>'.str_replace("\n","</li>\n<li style=padding:3px;>",trim($dt->REQUIREMENT,"\n")).'</li>';
			$data['tgl_selesai'] = DATE("d M Y",strtotime($dt->TANGGAL_SELESAI));
			$data['des_menu'] = $dt->POSISI;
		}

		$data['kota'] = "";
		$datay = file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=lowongan_kota&rpk='.$pil2[1]);
        $dataz = explode('-%-',$datay);
		$data_job2 = json_decode($dataz[1]);
		foreach($data_job2 as $dt){
			$data['kota'] .= $dt->lokasi.'-';
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
		
		/*
		$data['deskripsi']='';
		$data['syarat']='';
		
		$datax = json_decode(file_get_contents('http://localhost/api_evn/get_dtl_lowongan/1'));
		foreach($datax as $dt){
			$id_low=$dt->id_low;
			$data[$dt->jenis] .= "<li>".$dt->deskripsi."</li>";
		}
		*/
		$data['link_apply'] = "lowongan/low_apply/".$pil."";
		
		if($pil2[0] == 'job'){
			
		}else{
			
		}
		
		$this->load->view('lowongan_dtl2',$data);
		
    }
	
	public function low_apply()
    {
		$pil=  $this->uri->segment(3);
		$pil2 = explode("-",$pil);
		$email = $this->session->userdata('username');
		
		if($this->session->userdata('username') <> ''){			
			$data_dr = json_decode(file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=dt_diri&email='.$email));
			foreach($data_dr as $dt){
				$data['id'] = $dt->rep_id;
				$data['rep_id'] = $dt->rep_id;
				$data['nama'] = $dt->nama;
				$data['no_ktp'] = $dt->no_ktp;
				$data['tem_lahir'] = $dt->tem_lahir;
				$data['tgl_lahir'] = $dt->tgl_lahir;
				$data['j_kelamin'] = $dt->j_kelamin;
				$data['agama'] = $dt->agama;
				$data['alamat_ktp'] = $dt->alamat_ktp;
				$data['alamat_dom'] = $dt->alamat_dom;
				$data['sesuai_ktp'] = $dt->sesuai_ktp;
				$data['handphone'] = $dt->handphone;
				$data['toefl'] = $dt->toefl;
				$data['email'] = $dt->email;
				$data['foto'] = 'f1_96_1531107184.jpg';
			}
			/*
			$data['pend'] = $this->model_webjob->tampil_dt_pendidikan($this->session->userdata('username'))->result();
			$data['kerja'] = $this->model_webjob->tampil_dt_kerja($this->session->userdata('username'))->result();
			$data['organisasi'] = $this->model_webjob->tampil_dt_organisasi($this->session->userdata('username'))->result();
			$data['prestasi'] = $this->model_webjob->tampil_dt_prestasi($this->session->userdata('username'))->result();
			*/
			$data['pend'] = json_decode(file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=dt_pend&email='.$email));
			$data['kerja'] = json_decode(file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=dt_ker&email='.$email));
			$data['organisasi'] = json_decode(file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=dt_org&email='.$email));
			$data['prestasi'] = json_decode(file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=dt_pres&email='.$email));
			$data['id_dt'] = $data['id'];
			$data['des_menu'] = $data['nama'];
		}else{
			redirect('n_login');
			die();
		}
		
		//$this->model_webjob->post_dt_diri($data);
		
		if($pil2[0] == 'job'){
			$data['i_hiden'] = "hidden";
			$data['i_req'] = 'type="hidden"';
		}else{
			$data['i_hiden'] = "file";
			$data['i_req'] = 'required  type="file"';
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

		$data['link_u'] = $pil;
		$data['j_dt'] = $pil2[0];
		if($pil2[0] == 'job'){
			$this->load->view('lowongan_apply',$data);
		}else{
			if($this->session->userdata('username') <> ''){	
				$this->load->view('lowongan_apply',$data);
			}else{
				redirect('n_login');
			}
		}
	}
	
	public function post_apply_akhir()
    {
        if(isset($_POST['submit'])){
			
        	// proses barang
			$link_u			= explode('-o-',$this->input->post('link6'));
			$email			= $this->input->post('eml_apply');
			//echo $this->input->post('link6').$email; die();
			//$asd = $kelamin.$nama; job-o-REQ-000104-o-LKT-000006-
			$datay = file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=get_nav');
			$datazx = explode('-%-',$datay);
			$datax = json_decode($datazx[1]);
			foreach($datax as $dt){
				$data[$dt->menu] = $dt->link_nav;
			}
			
            $dataz       = array(
								'email' 	=> $email,
								'jenis' 	=> $link_u[0],
								'req_id' 	=> $link_u[1],
								'kd_kantor' => $link_u[2]
								);
			//echo $link_u[2]; die();
            //$this->model_barang->edit($data,$id);

			$data_dr = json_decode(file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=dt_diri&email='.$email));
			foreach($data_dr as $dt){
				$data['rep_id'] = $dt->rep_id;
				$data['nama'] = $dt->nama;
				$data['no_ktp'] = $dt->no_ktp;
				$data['tem_lahir'] = $dt->tem_lahir;
				$data['tgl_lahir'] = $dt->tgl_lahir;
				$data['j_kelamin'] = $dt->j_kelamin;
				$data['agama'] = $dt->agama;
				$data['alamat_ktp'] = $dt->alamat_ktp;
				$data['alamat_dom'] = $dt->alamat_dom;
				$data['sesuai_ktp'] = $dt->sesuai_ktp;
				$data['handphone'] = $dt->handphone;
				$data['toefl'] = $dt->toefl;
				$data['email'] = $dt->email;
			}
			
			/*
			$datax = json_decode(file_get_contents('http://localhost/api_evn/get_nav'));
			foreach($datax as $dt){
				$data[$dt->menu] = $dt->link_nav;
			}
			*/
			
			//$data['pend'] = $this->model_webjob->tampil_dt_pendidikan($email)->result();
			$data['pend'] = json_decode(file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=dt_pend&email='.$email));
			$data['kerja'] = json_decode(file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=dt_ker&email='.$email));
			//$data['organisasi'] = $this->model_webjob->tampil_dt_organisasi($email)->result();
			$data['prestasi'] = json_decode(file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=dt_pres&email='.$email));
			
			if($this->session->userdata('username') <> ''){
				$data['des_menu'] = "Agung";
			}else{
				$data['des_menu'] = $data['nama'];
			}
			
			$data['j_dt'] = $this->input->post('j_dt');
			$data['id_dt'] = $this->input->post('id');
			if($data['j_dt'] == "job"){
				$data['i_hiden'] = "hidden";
				$data['i_req'] = 'type="hidden"';
			}else{
				$data['i_hiden'] = "file";
				$data['i_req'] = 'required  type="file"';
			}

			$data['link_u'] = $this->input->post('link4');

		// kirim API
			$url_x = 'http://webjob.wika.co.id/lama/apply_api.php?act_a=do_reg_api';		
			$ch = curl_init();           
			curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
			curl_setopt($ch,CURLOPT_FAILONERROR,0);
			curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
			curl_setopt($ch,CURLOPT_FRESH_CONNECT,1);
			curl_setopt($ch,CURLOPT_POST,1);
			curl_setopt($ch,CURLOPT_URL,$url_x);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

			//curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dataz));                                                                  
			//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
				'Content-Type: application/json'                                                                     
			));                                                                                                                   
																																 
			$raw_response = curl_exec($ch);
			$error = curl_error($ch);
			//$response = json_decode($raw_response, 1);
			#print_r("Post data: ".json_encode($data_string));
			//$datah = $response['data'];
			
			if (strpos($raw_response, '1 sukses') !== FALSE) {
				$result = 'sukses';
			}
			//echo "curl response is:" . $result.$error;
			curl_close ($ch);
			//return array($result, $raw_response, $error);

			//echo $raw_response; die();
			$hasil = explode('-o-',$raw_response);

			$datay = file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=lowongan&jenis=reg');
			$dataz = explode('-%-',$datay);
			$data['posisi'] = json_decode($dataz[1]);
			
			$datay = file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=get_nav');
			$dataz = explode('-%-',$datay);
			$datax = json_decode($dataz[1]);
			foreach($datax as $dt){
				$data[$dt->menu] = $dt->link_nav;
			}
			
			$data['gmbr'] = "/assets2/img/mtshe.png";

			$data['link_1'] = "/lowongan/low_dtl/reg-o-";
			$data['link_mtshe'] = "/lowongan/low_dtl/mtshe-reg";
			$data['link_ppcp'] = "/lowongan/low_dtl/ppcp-reg";
			
			$data['link_r'] = "/lowongan/pil_lwg/reg";
			$data['link_j'] = "/lowongan/pil_lwg/job";
			$data['class_r'] = "btn btn-primary btn-round btn-lg";
			$data['class_j'] = "btn btn-primary btn-simple btn-round btn-lg";
			//echo $raw_response; die();
			if($hasil[0] == 'gagal'){
				$data['isi_msg'] = $hasil[1];
				$this->load->view('lowongan_r',$data);
			}else{
				$data['isi_msg'] = 'Lamaran sudah diterima.';
				$this->load->view('lowongan_r',$data);
			}
			//echo $raw_response; die();
		// kirim API 

        }
        else{
            
        }
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */