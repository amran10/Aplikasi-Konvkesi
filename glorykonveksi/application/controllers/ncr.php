<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ncr extends CI_Controller {

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
			$data['kerja'] = json_decode(file_get_contents('https://event.wika.co.id/api_she/get_ncr_hd'));
			$data['id_dt'] = $data['id'];
			$data['des_menu'] = $data['nama'];
		}else{
			$data['id'] = '';
				$data['rep_id'] = '';
				$data['nama'] = '';
				$data['no_ktp'] = '';
				$data['tem_lahir'] = '';
				$data['tgl_lahir'] = '';
				$data['j_kelamin'] = '';
				$data['agama'] = '';
				$data['alamat_ktp'] = '';
				$data['alamat_dom'] = '';
				$data['sesuai_ktp'] = '';
				$data['handphone'] = '';
				$data['toefl'] = '';
				$data['email'] = '';
				$data['foto'] = 'f1_96_1531107184.jpg';
				
				$data['kerja'] = json_decode(file_get_contents('https://event.wika.co.id/api_she/get_ncr_hd'));
				$data['spk'] = json_decode(file_get_contents('https://event.wika.co.id/api_she/get_spk'));
				
				$data['l_spk']='';
				foreach ($data['spk'] as $k) {
					$data['l_spk'] .= '"'.$k->nama.'",';
				}
				
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
		$this->load->view('ncr_hd1',$data);
		
	}
	
	public function ncr_dtl()
    {
		$pil=  $this->uri->segment(3);
		$pil2 = explode("-",$pil);
		
		$dataz = array('id' => $pil2[1],
						'pass' => $pil2[2]
					);
			
			
		// kirim API
			if($pil2[0] == 'add'){
				$url_x = 'https://event.wika.co.id/api_she/cek_ncr_hd';		
			}else{
				$url_x = 'https://event.wika.co.id/api_she/del_ncr_hd';		
			}
			
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
			$result = 'gagal';
			if (strpos($raw_response, 'sukses') !== FALSE) {
				$result = 'sukses';
			}
			//echo "curl response is:" . $result.$error;
			curl_close ($ch);
			//return array($result, $raw_response, $error);
			
		//echo $result; die();
		if($result == 'sukses'){
			if($pil2[0] == 'del'){redirect('ncr',$data);}
			$data['id'] = '';
			$data['rep_id'] = '';
			$data['nama'] = '';
			$data['no_ktp'] = '';
			$data['tem_lahir'] = '';
			$data['tgl_lahir'] = '';
			$data['j_kelamin'] = '';
			$data['agama'] = '';
			$data['alamat_ktp'] = '';
			$data['alamat_dom'] = '';
			$data['sesuai_ktp'] = '';
			$data['handphone'] = '';
			$data['toefl'] = '';
			$data['email'] = '';
			$data['foto'] = 'f1_96_1531107184.jpg';
			
			$data['kerja'] = json_decode(file_get_contents('https://event.wika.co.id/api_she/get_ncr_dtl/'.$pil2[1]));
			$data['rcahd'] = json_decode(file_get_contents('https://event.wika.co.id/api_she/get_ncr_hd/'.$pil2[1]));
			
			$a=1; 
			foreach ($data['rcahd'] as $k) {
				$data['proj'] = $k->proj;
				$data['id_m'] = $k->id;
				$data['password'] = $k->password;
				$data['tanggal'] = date('d M Y',strtotime($k->tanggal));
				$data['lokasi'] = $k->lokasi;
				$data['auditor'] = str_replace("-"," , ",$k->auditor);
				$data['cuaca'] = $k->cuaca;
				$data['waktu'] = $k->waktu;
				$l_audit = $k->auditor;
			}
			
			$a = substr_count($l_audit, '-');
			$l_audit = explode('-',$l_audit);
			$data['l_opt'] = '';
			for($b=0;$b<$a;$b++){
				$data['l_opt'] .= '<option value="'.$l_audit[$b].'" >'.$l_audit[$b].'</option>';
			}
			
			
		}else{
			redirect('ncr',$data);
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
		$this->load->view('ncr_dtl',$data);
		
	}
	
	public function del_ncr_dtl()
    {
		$pil=  $this->uri->segment(3);
		$pil2 = explode("-",$pil);
		
		$dataz = array('id' => $pil2[0],
					);
			
		// kirim API
			$url_x = 'https://event.wika.co.id/api_she/del_ncr_dtl';
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
			$result = 'gagal';
			if (strpos($raw_response, 'sukses') !== FALSE) {
				$result = 'sukses';
			}
			//echo "curl response is:" . $result.$error;
			curl_close ($ch);
			//return array($result, $raw_response, $error);
			
		//echo $result; die();
		
		if($result == 'sukses'){
			$data['id'] = '';
			$data['rep_id'] = '';
			$data['nama'] = '';
			$data['no_ktp'] = '';
			$data['tem_lahir'] = '';
			$data['tgl_lahir'] = '';
			$data['j_kelamin'] = '';
			$data['agama'] = '';
			$data['alamat_ktp'] = '';
			$data['alamat_dom'] = '';
			$data['sesuai_ktp'] = '';
			$data['handphone'] = '';
			$data['toefl'] = '';
			$data['email'] = '';
			$data['foto'] = 'f1_96_1531107184.jpg';
			
			$data['kerja'] = json_decode(file_get_contents('https://event.wika.co.id/api_she/get_ncr_dtl/'.$pil2[1]));
			$data['rcahd'] = json_decode(file_get_contents('https://event.wika.co.id/api_she/get_ncr_hd/'.$pil2[1]));
			
			$a=1; 
			foreach ($data['rcahd'] as $k) {
				$data['proj'] = $k->proj;
				$data['id_m'] = $k->id;
				$data['password'] = $k->password;
				$data['tanggal'] = date('d M Y',strtotime($k->tanggal));
				$data['lokasi'] = $k->lokasi;
				$data['auditor'] = str_replace("-"," , ",$k->auditor);
				$data['cuaca'] = $k->cuaca;
				$data['waktu'] = $k->waktu;
				$l_audit = $k->auditor;
			}
			
			$a = substr_count($l_audit, '-');
			$l_audit = explode('-',$l_audit);
			$data['l_opt'] = '';
			for($b=0;$b<$a;$b++){
				$data['l_opt'] .= '<option value="'.$l_audit[$b].'" >'.$l_audit[$b].'</option>';
			}
			
			
		}else{
			redirect('rca',$data);
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

		$this->load->view('ncr_dtl',$data);
		
	}
	
	public function upd_ncr_dtl()
    {
		$pil=  $this->uri->segment(3);
		$pil2 = explode("-",$pil);
		
		$dataz = array('id' => $pil2[0],
					);
			
		// kirim API
			$url_x = 'https://event.wika.co.id/api_she/upd_ncr_dtl';
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
			$result = 'gagal';
			if (strpos($raw_response, 'sukses') !== FALSE) {
				$result = 'sukses';
			}
			//echo "curl response is:" . $result.$error;
			curl_close ($ch);
			//return array($result, $raw_response, $error);
			
		//echo $result; die();
		
		if($result == 'sukses'){
			$data['id'] = '';
			$data['rep_id'] = '';
			$data['nama'] = '';
			$data['no_ktp'] = '';
			$data['tem_lahir'] = '';
			$data['tgl_lahir'] = '';
			$data['j_kelamin'] = '';
			$data['agama'] = '';
			$data['alamat_ktp'] = '';
			$data['alamat_dom'] = '';
			$data['sesuai_ktp'] = '';
			$data['handphone'] = '';
			$data['toefl'] = '';
			$data['email'] = '';
			$data['foto'] = 'f1_96_1531107184.jpg';
			
			$data['kerja'] = json_decode(file_get_contents('https://event.wika.co.id/api_she/get_ncr_dtl/'.$pil2[1]));
			$data['rcahd'] = json_decode(file_get_contents('https://event.wika.co.id/api_she/get_ncr_hd/'.$pil2[1]));
			
			$a=1; 
			foreach ($data['rcahd'] as $k) {
				$data['proj'] = $k->proj;
				$data['id_m'] = $k->id;
				$data['password'] = $k->password;
				$data['tanggal'] = date('d M Y',strtotime($k->tanggal));
				$data['lokasi'] = $k->lokasi;
				$data['auditor'] = str_replace("-"," , ",$k->auditor);
				$data['cuaca'] = $k->cuaca;
				$data['waktu'] = $k->waktu;
				$l_audit = $k->auditor;
			}
			
			$a = substr_count($l_audit, '-');
			$l_audit = explode('-',$l_audit);
			$data['l_opt'] = '';
			for($b=0;$b<$a;$b++){
				$data['l_opt'] .= '<option value="'.$l_audit[$b].'" >'.$l_audit[$b].'</option>';
			}
			
			
		}else{
			redirect('ncr',$data);
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

		$this->load->view('ncr_dtl',$data);
		
	}
	
	public function post_hd()
    {
		$nm_auditor = '';
		$auditor = $this->input->post('auditor');
		foreach( $auditor as $key => $n ) {
		  $nm_auditor .= $n.'-';
		}
        $dataz = array('proj' => $this->input->post('nama'),
						'tanggal' => date('Y-m-d',strtotime($this->input->post('tgl_lahir'))),
						'lokasi' => $this->input->post('lokasi'),
						'auditor' => $nm_auditor,
						'cuaca' => $this->input->post('cuaca'),
						'waktu' => $this->input->post('waktu1').' - '.$this->input->post('waktu2'),
						'password' => $this->input->post('password'),
					);
			
			
		// kirim API
			$url_x = 'https://event.wika.co.id/api_she/ins_ncr_hd';		
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
			
			if (strpos($raw_response, 'sukses') !== FALSE) {
				$result = 'sukses';
			}
			//echo "curl response is:" . $result.$error;
			curl_close ($ch);
			//return array($result, $raw_response, $error);
			
			$datay = file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=get_nav');
			$dataz = explode('-%-',$datay);
			$datax = json_decode($dataz[1]);
			foreach($datax as $dt){
				$data[$dt->menu] = $dt->link_nav;
			}
			
			redirect('ncr',$data);
			
    }
	
	public function post_dtl()
    {
		$std1 = $this->input->post('std1');
		$std2 = $this->input->post('std2');
		$std3 = $this->input->post('std3');
		$std4 = $this->input->post('std4');
		$std5 = $this->input->post('std5');
		
		$nil_std='';
		if($std1<>'') $nil_std .= $std1.'-';
		if($std2<>'') $nil_std .= $std2.'-';
		if($std3<>'') $nil_std .= $std3.'-';
		if($std4<>'') $nil_std .= $std4.'-';
		if($std5<>'') $nil_std .= $std5.'-';
		
		$namaFile = '';
		if(is_uploaded_file($_FILES['lampiran']['tmp_name'])) {
			// ambil data file
			$namaFile='f_'.mt_rand(10,99).'_'.time().strtolower(strrchr($_FILES["lampiran"]["name"],"."));
			//$namaFile = $_FILES['fileToUpload'.$a]['name'];
			$namaSementara = $_FILES['lampiran']['tmp_name'];
			//print_r($_FILES); die();
			// tentukan lokasi file akan dipindahkan
			$dirUpload = "assets/";

			// pindahkan file
			$terupload = move_uploaded_file($namaSementara, $dirUpload.$namaFile);
			
			if ($terupload) {
				//echo "Upload berhasil!<br/>";
				//echo "Link: <a href='".$dirUpload.$namaFile."'>".$namaFile."</a>";
			} else {
				$up_er .= "Upload File Gagal!";
			}
		}
		$linkfil = $this->input->post('id_m').'-'.$this->input->post('password');
        $dataz = array('auditor' 	=> $this->input->post('auditor'),
						'm_id' 		=> $this->input->post('id_m'),
						'lampiran' 	=> $namaFile,
						'uraian' 	=> $this->input->post('uraian'),
						'standard' 	=> $nil_std,
						'klausul1' 	=> $this->input->post('klausul1'),
						'klausul2' 	=> $this->input->post('klausul2'),
						'klausul3' 	=> $this->input->post('klausul3'),
						'klausul4' 	=> $this->input->post('klausul4'),
						'klausul5' 	=> $this->input->post('klausul5'),
						'status_temuan' => $this->input->post('status_temuan'),
					);
		/*	
			echo $dataz['auditor'].'<br>';
			echo $dataz['m_id'].'<br>';
			echo $dataz['lampiran'].'<br>';
			echo $dataz['uraian'].'<br>';
			echo $dataz['standard'].'<br>';
			echo $dataz['klausul1'].'<br>';
			echo $dataz['klausul2'].'<br>';
			echo $dataz['klausul3'].'<br>';
			echo $dataz['klausul4'].'<br>';
			echo $dataz['klausul5'].'<br>';
			echo $dataz['status_temuan'].'<br>'; die();
		*/	
		// kirim API
			$url_x = 'https://event.wika.co.id/api_she/ins_ncr_dtl';		
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
			
			if (strpos($raw_response, 'sukses') !== FALSE) {
				$result = 'sukses';
			}
			//echo "curl response is:" . $result.$error;
			curl_close ($ch);
			//return array($result, $raw_response, $error);
			
			$datay = file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=get_nav');
			$dataz = explode('-%-',$datay);
			$datax = json_decode($dataz[1]);
			foreach($datax as $dt){
				$data[$dt->menu] = $dt->link_nav;
			}
			
			redirect('ncr',$data);
			
    }
	
	public function photo()
    {
		$this->load->view('photo');
		
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */