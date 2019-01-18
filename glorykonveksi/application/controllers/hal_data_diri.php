<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class hal_data_diri extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Mapsss to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_namea>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$pil=  $this->uri->segment(3);
		$pil2 = explode("-",$pil);
		$data['link_u'] = "";
		
		if($this->session->userdata('username') <> ''){			
			$email = $this->session->userdata('username');
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
				$data['foto'] = $dt->foto;
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
			$data['des_menu1'] = $data['id'];
			$data['des_menu'] = $data['nama'];
			
		}else{
			$data = $this->model_webjob->tampil_dt_diri('0')->row_array();
			$data['email']='';
			$data['kerja'] = $this->model_webjob->tampil_dt_kerja('0')->result();
			$data['organisasi'] = $this->model_webjob->tampil_dt_organisasi('0')->result();
			$data['pend'] = $this->model_webjob->tampil_dt_pendidikan('0')->result();
			$data['prestasi'] = $this->model_webjob->tampil_dt_prestasi('0')->result();
			//$data['id_dt'] = $data['id'];
			$data['id_dt'] = "1";
			$data['des_menu'] = $data['nama'];
		}
		
		//$this->model_webjob->post_dt_diri($data);
		
		if($pil2[0] == 'job'){
			$data['i_hiden'] = "hidden";
			$data['i_req'] = 'type="hidden"';
		}else{
			$data['i_hiden'] = "file";
			$data['i_req'] = 'required  type="file"';
		}
		
		$datay = file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=get_nav');
		$dataz = explode('-%-',$datay);
		$datax = json_decode($dataz[1]);
		foreach($datax as $dt){
			$data[$dt->menu] = $dt->link_nav;
		}
		
		$data['j_dt'] = $pil2[0];
		if($pil2[0] == 'job'){
			$this->load->view('hal_data_diri',$data);
		}else{
			if($this->session->userdata('username') <> ''){	
				$this->load->view('hal_data_diri',$data);
			}else{
				redirect('n_login');
			}
		}
    }
	
	public function seleksi()
	{
		$email = $this->session->userdata('username');
		
		$datay = file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=get_nav');
		$dataz = explode('-%-',$datay);
		$datax = json_decode($dataz[1]);
		foreach($datax as $dt){
			$data[$dt->menu] = $dt->link_nav;
		}
		
		$data['tanggal']='';
		$data['detail']='';
		$a=1;
		$data['dt_seleksi'] = json_decode(file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=list_seleksi&email='.$email));
		
		$data['des_menu'] = "STATUS SELEKSI";
		
		$this->load->view('seleksi',$data);
	}
	
	public function post_dt_diri()
    {
        if(isset($_POST['submit'])){
			$folders = "assets2/doc/";
			$foto			= "";
			$ijazah			= "";
			$transkrip		= "";
			$cv				= "";
			if(is_uploaded_file($_FILES['foto']['tmp_name'])) {
					$file1='f1_'.mt_rand(10,99).'_'.time().strtolower(strrchr($_FILES["foto"]["name"],"."));
					$target_path = $folders.$file1; copy($_FILES['foto']['tmp_name'], $target_path);
					$foto = $file1;
				}
			if($this->input->post('j_dt') == "reg"){
				
				if(is_uploaded_file($_FILES['ijazah']['tmp_name'])) {
					$file1='f1_'.mt_rand(10,99).'_'.time().strtolower(strrchr($_FILES["ijazah"]["name"],"."));
					$target_path = $folders.$file1; copy($_FILES['ijazah']['tmp_name'], $target_path);
					$ijazah = $file1;
				}
				if(is_uploaded_file($_FILES['transkrip']['tmp_name'])) {
					$file1='f1_'.mt_rand(10,99).'_'.time().strtolower(strrchr($_FILES["transkrip"]["name"],"."));
					$target_path = $folders.$file1; copy($_FILES['transkrip']['tmp_name'], $target_path);
					$transkrip = $file1;
				}
				if(is_uploaded_file($_FILES['cv']['tmp_name'])) {
					$file1='f1_'.mt_rand(10,99).'_'.time().strtolower(strrchr($_FILES["cv"]["name"],"."));
					$target_path = $folders.$file1; copy($_FILES['cv']['tmp_name'], $target_path);
					$cv = $file1;
				}
			}
			// proses barang
			$id				= $this->input->post('id');
			$nama			= $this->input->post('nama');
			$no_ktp			= $this->input->post('no_ktp');
			$tem_lahir		= $this->input->post('tem_lahir');
			$tgl_lahir		= date("Y-m-d",strtotime($this->input->post('tgl_lahir')));
			$j_kelamin		= $this->input->post('j_kelamin');
			$agama			= $this->input->post('agama');
			$alamat_ktp		= $this->input->post('alamat_ktp');
			$alamat_dom		= $this->input->post('alamat_dom');
			$sesuai_ktp		= $this->input->post('sesuai_ktp');
			$handphone		= $this->input->post('handphone');
			//$foto			= $this->input->post('foto');
			//$ijazah			= $this->input->post('ijazah');
			//$transkrip		= $this->input->post('transkrip');
			//$cv				= $this->input->post('cv');
			$toefl			= $this->input->post('toefl');
			$email			= $this->input->post('email');
			$created_date	= date("Y-m-d H:i:s");
			
			$datay = file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=get_nav');
			$dataz = explode('-%-',$datay);
			$datax = json_decode($dataz[1]);
			foreach($datax as $dt){
				$data[$dt->menu] = $dt->link_nav;
			}

			//$asd = $kelamin.$nama;
            $dataz       = array(
								'nama' => $nama,
								'no_ktp' => $no_ktp,
								'tem_lahir' => $tem_lahir,
								'tgl_lahir' => $tgl_lahir,
								'j_kelamin' => $j_kelamin,
								'agama' => $agama,
								'alamat_ktp' => $alamat_ktp, 
								'alamat_dom' => $alamat_dom,
								'sesuai_ktp' => $sesuai_ktp,
								'handphone' => $handphone,
								'foto' => $foto,
								'ijazah' => $ijazah,
								'transkrip' => $transkrip,
								'cv' => $cv,
								'toefl' => $toefl,
								'email' => $email,
								'created_date' => $created_date
								);
            //$this->model_barang->edit($data,$id);
            //$this->model_webjob->post_dt_diri($dataz);
			
			//$data = $this->model_webjob->tampil_dt_diri($email)->row_array();
			
			/*
			$datax = json_decode(file_get_contents('http://webjob.wika.co.id/api_evn/get_nav'));
			foreach($datax as $dt){
				$data[$dt->menu] = $dt->link_nav;
			}
			*/
			
			/*
			$data['pend'] = $this->model_webjob->tampil_dt_pendidikan('sdssd')->result();
			$data['kerja'] = $this->model_webjob->tampil_dt_kerja($email)->result();
			$data['organisasi'] = $this->model_webjob->tampil_dt_organisasi($email)->result();
			$data['prestasi'] = $this->model_webjob->tampil_dt_prestasi($email)->result();
			*/
			$data['pend'] = json_decode(file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=dt_pend&email='.$email));
			$data['kerja'] = json_decode(file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=dt_ker&email='.$email));
			$data['organisasi'] = json_decode(file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=dt_org&email='.$email));
			$data['prestasi'] = json_decode(file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=dt_pres&email='.$email));

			if($this->session->userdata('username') <> ''){
				$data['des_menu'] = $this->session->userdata('nm_user');
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

			$data['link_u'] = $this->input->post('link1');

		// kirim API
			$url_x = 'http://webjob.wika.co.id/lama/user_api.php?act_a=do_reg_api';		
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
			$data_r = json_decode($raw_response);
			foreach($data_r as $dt){
				$data['rep_id'] = $dt->rep_id;
				$data['id_dt'] = $dt->rep_id;
				$data['nama'] = $dt->nama;
				$data['des_menu1'] = $dt->rep_id;
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
				$data['foto'] = $dt->foto;
				$data['email'] = $dt->email;
			}
		// kirim API

			//echo 'selesai - '.$raw_response; die();
            $this->load->view('hal_data_diri',$data);
        }
        else{
            
        }
    }
	
	public function post_dt_pendidikan()
    {
        if(isset($_POST['submit'])){
			
        	// proses barang
			
			$id				= $this->input->post('id');
			$email			= $this->input->post('eml_p');
			$pendidikan		= $this->input->post('pendidikan');
			$sekolah		= $this->input->post('sekolah');
			//$fakultas		= $this->input->post('fakultas');
			$fakultas_pil	= $this->input->post('fakultas_pil');
			$fakultas				= ($this->input->post('fakultas') == "lain-lain" ? $this->input->post('fakultas_pil') : $this->input->post('fakultas'));
			//$jurusan		= $this->input->post('jurusan');
			$jurusan_pil	= $this->input->post('jurusan_pil');
			$jurusan				= ($this->input->post('jurusan') == "lain-lain" ? $this->input->post('jurusan_pil') : $this->input->post('jurusan'));
			//$program		= $this->input->post('program');
			$prog_pil		= $this->input->post('prog_pil');
			$program				= ($this->input->post('program') == "lain-lain" ? $this->input->post('prog_pil') : $this->input->post('program'));
			//$geglar			= $this->input->post('geglar');
			$geglar_pil		= $this->input->post('geglar_pil');
			$geglar				= ($this->input->post('geglar') == "lain-lain" ? $this->input->post('geglar_pil') : $this->input->post('geglar'));
			$ipk			= $this->input->post('ipk');
			$kota			= $this->input->post('kota');
			$thn_masuk		= $this->input->post('thn_masuk');
			$thn_lulus		= $this->input->post('thn_lulus');
			$created_date	=  date("Y-m-d H:i:s");
			
			$datay = file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=get_nav');
			$dataz = explode('-%-',$datay);
			$datax = json_decode($dataz[1]);
			foreach($datax as $dt){
				$data[$dt->menu] = $dt->link_nav;
			}

			//$asd = $kelamin.$nama;
            $dataz       = array(
								'id' => $id,
								'email' => $email,
								'pendidikan' => $pendidikan,
								'sekolah' => $sekolah,
								'fakultas' => $fakultas,
								'fakultas_pil' => $fakultas_pil,
								'jurusan' => $jurusan,
								'jurusan_pil' => $jurusan_pil,
								'program' => $program,
								'prog_pil' => $prog_pil,
								'geglar' => $geglar,
								'geglar_pil' => $geglar_pil,
								'ipk' => $ipk,
								'kota' => $kota,
								'thn_masuk' => $thn_masuk,
								'thn_lulus' => $thn_lulus,
								'created_date' => $created_date

								);
            //var_dump($dataz);die();
            //$this->model_barang->edit($data,$id);
            //$this->model_webjob->post_dt_pendidikan($dataz);
			
			//$data = $this->model_webjob->tampil_dt_email('asad')->row_array();

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
				$data['foto'] = $dt->foto;
			}

			
			/*
			$datax = json_decode(file_get_contents('http://webjob.wika.co.id/api_evn/get_nav'));
			foreach($datax as $dt){
				$data[$dt->menu] = $dt->link_nav;
			}
			*/
			
			//$data['pend'] = json_decode(file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=dt_pend&email='.$email));
			$data['kerja'] = json_decode(file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=dt_ker&email='.$email));
			$data['organisasi'] = json_decode(file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=dt_org&email='.$email));
			$data['prestasi'] = json_decode(file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=dt_pres&email='.$email));
			
			if($this->session->userdata('username') <> ''){
				$data['des_menu'] = $this->session->userdata('nm_user');
			}else{
				$data['des_menu'] = ['nama'];
				}
				
				$data['des_menu1'] = $data['id'];
			
			
			$data['j_dt'] = $this->input->post('j_dt');
			$data['id_dt'] = $this->input->post('id');
			if($data['j_dt'] == "job"){
				$data['i_hiden'] = "hidden";
				$data['i_req'] = 'type="hidden"';
			}else{
				$data['i_hiden'] = "file";
				$data['i_req'] = 'required  type="file"';
			}

			$data['link_u'] = $this->input->post('link2');

			// kirim API
			$url_x = 'http://webjob.wika.co.id/lama/riwayat_pendidikan_api.php?act_a=do_reg_api';		
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
			//var_dump($raw_response);exit();
			$error = curl_error($ch);
			//var_dump($raw_response);die();
			//$response = json_decode($raw_response, 1);
			#print_r("Post data: ".json_encode($data_string));
			//$datah = $response['data'];
			
			if (strpos($raw_response, '1 sukses') !== FALSE) {
				$result = 'sukses';
			}
			//echo "curl response is:" . $result.$error;
			curl_close ($ch);
			//return array($result, $raw_response, $error);

			$data['pend'] = json_decode($raw_response);
			

		// kirim API 
			//echo 'selesai - '.$raw_response; die();

            $this->load->view('hal_data_diri',$data);
        }
        else{
            
        }
    }
	
	public function post_dt_kerja()
    {
        if(isset($_POST['submit'])){
			
        	// proses barang
			$id			= $this->input->post('id');
			$email			= $this->input->post('eml_k');
			$perusahaan			= $this->input->post('perusahaan');
			$jabatan			= $this->input->post('jabatan');
			$thn_masuk_k			= $this->input->post('thn_masuk_k');
			$thn_keluar_k			= $this->input->post('thn_keluar_k');
			$uraian_k			= $this->input->post('uraian_k');
			$atasan			= $this->input->post('atasan');
			$tlpn_atasan			= $this->input->post('tlp_atasan');
			$created_date			= date("Y-m-d H:i:s");
			
			$datay = file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=get_nav');
			$dataz = explode('-%-',$datay);
			$datax = json_decode($dataz[1]);
			foreach($datax as $dt){
				$data[$dt->menu] = $dt->link_nav;
			}

			//$asd = $kelamin.$nama;
            $dataz       = array(
								'id' => $id,
								'email' => $email,
								'perusahaan' => $perusahaan,
								'jabatan' => $jabatan,
								'thn_masuk_k' => $thn_masuk_k,
								'thn_keluar_k' => $thn_keluar_k,
								'uraian_k' => $uraian_k,
								'atasan' => $atasan,
								'tlpn_atasan' => $tlpn_atasan,
								'created_date' => $created_date
								);

            //var_dump($dataz);die();
            //$this->model_barang->edit($data,$id);
            //$this->model_webjob->post_dt_kerja($dataz);
			
			//$data = $this->model_webjob->tampil_dt_email($email)->row_array();
			
			/*
			$datax = json_decode(file_get_contents('http://webjob.wika.co.id/api_evn/get_nav'));
			foreach($datax as $dt){
				$data[$dt->menu] = $dt->link_nav;
			}
			*/
			
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
				$data['foto'] = $dt->foto;
			}
			
			$data['pend'] = json_decode(file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=dt_pend&email='.$email));
			//$data['kerja'] = json_decode(file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=dt_ker&email='.$email));
			$data['organisasi'] = json_decode(file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=dt_org&email='.$email));
			$data['prestasi'] = json_decode(file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=dt_pres&email='.$email));
			
			if($this->session->userdata('username') <> ''){
				$data['des_menu'] = $this->session->userdata('nm_user');
			}else{
				$data['des_menu'] = $data['nama'];
			}


				$data['des_menu1'] = $data['id'];
			
			$data['j_dt'] = $this->input->post('j_dt');
			$data['id_dt'] = $this->input->post('id');
			if($data['j_dt'] == "job"){
				$data['i_hiden'] = "hidden";
				$data['i_req'] = 'type="hidden"';
			}else{
				$data['i_hiden'] = "file";
				$data['i_req'] = 'required  type="file"';
			}

			$data['link_u'] = $this->input->post('link3');

		// kirim API
			$url_x = 'http://webjob.wika.co.id/lama/riwayat_pekerjaan_api.php?act_a=do_reg_api';		
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

			$data['kerja'] = json_decode($raw_response);
			//echo $raw_response; die();
			

		// kirim API 

            $this->load->view('hal_data_diri',$data);
        }
        else{
            
        }
    }
	
	public function post_dt_organisasi()
    {
        if(isset($_POST['submit'])){
			
        	// proses barang
			$id			= $this->input->post('id');
			$email			= $this->input->post('eml_o');
			$pil_o			= $this->input->post('pil_o');
			$organisasi			= $this->input->post('organisasi');
			$jabatan_o			= $this->input->post('jabatan_o');
			$thn_masuk_o			= $this->input->post('thn_masuk_o');
			$thn_keluar_o			= $this->input->post('thn_keluar_o');
			$jenis_o				= ($this->input->post('jenis_o') == "A" ? $this->input->post('jenis_o1') : $this->input->post('jenis_o'));
			$created_date			= date("Y-m-d H:i:s");
			
			//var_dump($jenis_o);exit();
			// nilai jenis_o == nilai jenis_o1 apabila nilai jenis_o1 yang di input
	  //       if ($jenis_o == $jenis_o1) {
			//     // if body
			// } elseif ($jenis_o) {
			//     // elseif body
			// } else {
			//     // else body;
			// }

			$datay = file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=get_nav');
			$dataz = explode('-%-',$datay);
			$datax = json_decode($dataz[1]);
			foreach($datax as $dt){
				$data[$dt->menu] = $dt->link_nav;
			}

			//$asd = $kelamin.$nama;
            $dataz       = array(
								'id' => $id,
								'email' => $email,
								'pil_o' => $pil_o,
								'organisasi' => $organisasi,
								'jabatan_o' => $jabatan_o,
								'thn_masuk_o' => $thn_masuk_o,
								'thn_keluar_o' => $thn_keluar_o,
								'jenis_o' => $jenis_o,
								'created_date' => $created_date
								);
            //.echo $thn_masuk_o;die();
          	//var_dump($dataz);die();
            //$this->model_barang->edit($data,$id);
            //$this->model_webjob->post_dt_organisasi($dataz);
			
			//$data = $this->model_webjob->tampil_dt_email('asad')->row_array();
			//$jenis_o = !empty ($jenis_o1) ? $jenis_o1 : $jenis_o;	
			//
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
				$data['foto'] = $dt->foto;
			}
			
			/*
			$datax = json_decode(file_get_contents('http://webjob.wika.co.id/api_evn/get_nav'));
			foreach($datax as $dt){
				$data[$dt->menu] = $dt->link_nav;
			}
			*/
			
			$data['pend'] = json_decode(file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=dt_pend&email='.$email));
			$data['kerja'] = json_decode(file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=dt_ker&email='.$email));
			//$data['organisasi'] = json_decode(file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=dt_org&email='.$email));
			$data['prestasi'] = json_decode(file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=dt_pres&email='.$email));
			
			if($this->session->userdata('username') <> ''){
				$data['des_menu'] = $this->session->userdata('nm_user');
			}else{
				$data['des_menu'] = $data['nama'];

			}

				$data['des_menu1'] = $data['id'];
			
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
			$url_x = 'http://webjob.wika.co.id/lama/riwayat_organisasi_api.php?act_a=do_reg_api';		
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
			//var_dump(json_encode($dataz));																													 
			$raw_response = curl_exec($ch);
			//var_dump($raw_response);
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

			$data['organisasi'] = json_decode($raw_response);
			//echo $raw_response; die();
		// kirim API 

            $this->load->view('hal_data_diri',$data);
        }
        else{
            
        }
    }
	
	public function post_dt_prestasi()
    {
        if(isset($_POST['submit'])){
			
        	// proses barang
			$id			= $this->input->post('id');
			$email			= $this->input->post('eml_pr');
			$prestasi			= $this->input->post('prestasi');
			$lembaga			= $this->input->post('lembaga');
			$tingkatan			= $this->input->post('tingkatan');
			$tahun_pr			= $this->input->post('tahun_pr');
			$desk_pr			= $this->input->post('desk_pr');
			$created_date			= date("Y-m-d H:i:s");
			
			$datay = file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=get_nav');
			$dataz = explode('-%-',$datay);
			$datax = json_decode($dataz[1]);
			foreach($datax as $dt){
				$data[$dt->menu] = $dt->link_nav;
			}
			
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
				$data['foto'] = $dt->foto;
			}

			//$asd = $kelamin.$nama;
            $dataz       = array(
								'id' => $id,
								'email' => $email,
								'prestasi' => $prestasi,
								'lembaga' => $lembaga,
								'tingkatan' => $tingkatan,
								'tahun_pr' => $tahun_pr,
								'desk_pr' => $desk_pr,
								'created_date' => $created_date
								);
            //$this->model_barang->edit($data,$id);
            //$this->model_webjob->post_dt_prestasi($dataz);
			
			//$data = $this->model_webjob->tampil_dt_email($email)->row_array();
			
			/*
			$datax = json_decode(file_get_contents('http://webjob.wika.co.id/api_evn/get_nav'));
			foreach($datax as $dt){
				$data[$dt->menu] = $dt->link_nav;
			}
			*/
			
			$data['pend'] = json_decode(file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=dt_pend&email='.$email));
			$data['kerja'] = json_decode(file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=dt_ker&email='.$email));
			$data['organisasi'] = json_decode(file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=dt_org&email='.$email));
			//$data['prestasi'] = json_decode(file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=dt_ker&email='.$email));
			
			if($this->session->userdata('username') <> ''){
				$data['des_menu'] = $this->session->userdata('nm_user');
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

			$data['link_u'] = $this->input->post('link5');
			
			// kirim API
			$url_x = 'http://webjob.wika.co.id/lama/riwayat_prestasi_api.php?act_a=do_reg_api';		
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

			$data['prestasi'] = json_decode($raw_response);
			//echo $raw_response; die();
		// kirim API 

            $this->load->view('hal_data_diri',$data);
        }
        else{
            
        }
	}
	
	public function change(){


    $datay = file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=get_nav');
    $dataz = explode('-%-',$datay);
    $datax = json_decode($dataz[1]);
    foreach($datax as $dt){
      $data[$dt->menu] = $dt->link_nav;
    }

    if(isset($_POST['submit'])){
            
      // proses login disini
    		$email = $this->session->userdata('username');
            $password = $this->input->post('password');
            $new_password   =   $this->input->post('new_password');

            //echo $jawaban;

      $dataz = array(
      		'email' => $email,
            'password' => $password,
            'new_password' => $new_password
            );
      //var_dump($dataz);die();
      
      
      // echo "adadaadaadwaawdwa";
      // echo $jawaban;die();
      //kirim API
      $url_x = 'http://webjob.wika.co.id/lama/user_api.php?act_a=do_change';   
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
			
			if($hasil[0] == "sukses"){
				$datay = file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=get_nav');
				$dataz = explode('-%-',$datay);
				$datax = json_decode($dataz[1]);
				foreach($datax as $dt){
					$data[$dt->menu] = $dt->link_nav;
				}
				
				//$this->session->set_userdata(array('status_login'=>'oke','username'=>$hasil[1],'id_user'=>$hasil[1],'nm_user'=>$hasil[2]));
				$data['des_menu'] = "CHANGE PASSWORD";
				$data['isi_msg'] = $hasil[1];
				
				$this->load->view('ganti_password',$data);
				
			}else{
				$datay = file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=get_nav');
				$dataz = explode('-%-',$datay);
				$datax = json_decode($dataz[1]);
				foreach($datax as $dt){
					$data[$dt->menu] = $dt->link_nav;
				}
				
				//$this->session->set_flashdata('success', 'User Updated successfully');
				
				redirect('hal_data_diri',$data);
			}

		// kirim API 
			
        }
        else{
            $data['des_menu'] = "CHANGE PASSWORD";
		
			$this->load->view('ganti_password',$data);
        }
		
	}

	
	function print_cv()
    {
		$email=  $this->uri->segment(3);
        $this->load->library('cfpdf');
        $pdf=new FPDF('P','mm','A4');
        $pdf->AddPage();
		$pdf->Image('assets2/img/wika-logo_blue.png',10,10);
        $pdf->SetFont('Arial','B','L');
        $pdf->SetFontSize(14);
        $pdf->Text(10, 10, 'LAPORAN TRANSAKSI');
        $pdf->SetFont('Arial','B','L');
        $pdf->SetFontSize(10);
        $pdf->Cell(10, 10,'','',1);
        $pdf->Cell(10, 7, 'No', 1,0);
        $pdf->Cell(27, 7, 'Tanggal', 1,0);
        $pdf->Cell(30, 7, 'Operator', 1,0);
        $pdf->Cell(38, 7, 'Total Transaksi', 1,1);
        // tampilkan dari database
        $pdf->SetFont('Arial','','L');
        $data=  $this->model_transaksi->laporan_default();
        $no=1;
        $total=0;
        foreach ($data->result() as $r)
        {
            $pdf->Cell(27, 7, $no, 1,0);
            $pdf->Cell(27, 7, $r->tanggal_transaksi, 1,0);
            $pdf->Cell(30, 7, $r->nama_lengkap, 1,0);
            $pdf->Cell(38, 7, $r->total, 1,1);
            $no++;
            $total=$total+$r->total;
        }
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
			$data['foto'] = $dt->foto;
		}
        // end
        $pdf->Cell(67,7,'Total',1,0,'R');
        $pdf->Cell(38,7,$total,1,0);
        $pdf->Output();
    }
	
	function print_cv2(){
		include("application/libraries/mpdf60/mpdf.php");
        //$mpdf=new mPDF('c','A4');
        $mpdf=new mPDF('', 'A4', 0, '',8,8,5,8,8);          
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->list_indent_first_level = 0;  // 1 or 0 - whether to indent the first level of a list
		
		$email = $this->session->userdata('username');
		
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
				$foto = $dt->foto;
			}
		
		switch($data['agama']){
			case '1' : $agama = 'ISLAM';
					break;
			case '2' : $agama = 'KRISTEN PROTESTAN';
					break;
			case '3' : $agama = 'KRISTEN KATHOLIK';
					break;
			case '5' : $agama = 'HINDU';
					break;
			case '6' : $agama = 'KHONGHUCU';
					break;
			default : $agama = '';
					break;
		}
		
        $html=          
        '
        <!doctype html>
          <head>
            <meta charset="UTF-8">
            <title>CV</title>
            <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
			<!--     Fonts and icons     -->
			<link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
			<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.css" />
			<!-- CSS Files -->
			<link href="/assets2/css/bootstrap.min.css" rel="stylesheet" />
			<link href="/assets2/css/now-ui-kit.css?v=1.1.0" rel="stylesheet" />
			<link href="/assets2/css/demo.css" rel="stylesheet" />
			
			<!-- kalender -->

			<script src="/assets2/jquery/jquery-1.10.2.js"></script>
			
          </head>
          <body>
              <div id="content">
					<h5 align="right" style="margin-bottom:0px;"><u>CURRICULUM VITAE</u></h5>
					<nav class="navbar navbar-expand-lg bg-info" style="min-height:0px; ">
						<h6 style="width:100%; margin-bottom:0px; padding-left:10px; color:#ffffff;"><b>DATA PRIBADI</b></h6>
					</nav>
					
					<table width="100%" style="margin-left:50px; margin-bottom:20px;">
						<tr>
							<td width="20%">NAMA LENGKAP </td><td width="5%"> : </td><td width="50%"> '.$data['nama'].'</td>
							<td width="25%" rowspan="8" style="vertical-align:top;"><img style="height:250px; width:170px;" src="';
						
				if($foto <> ''){
					$html .= '/assets2/doc/'.$foto;
				}else{
					if($j_kelamin=='1') {
						$html .= '/assets2/img/av_user_l.png';
					}else{
						$html .= '/assets2/img/av_user_p.png';
					}
				}
								
				$html .='" alt="Image" >
							</td>
						</tr>
						<tr>
							<td>NOMOR KTP </td><td> : </td><td> '.$data['no_ktp'].'</td>
						</tr>
						<tr>
							<td>TEMPAT DAN TANGGAL LAHIR </td><td> : </td><td> '.$data['tem_lahir'].' / '.$data['tgl_lahir'].'</td>
						</tr>
						<tr>
							<td>JENIS KELAMIN </td><td> : </td><td> '.(($data['j_kelamin'] == '1')? 'Laki-Laki':'Perempuan').'</td>
						</tr>
						<tr>
							<td>AGAMA </td><td> : </td><td> '.$agama.'</td>
						</tr>
						<tr>
							<td>ALAMAT SESUAI KTP </td><td> : </td><td> '.$data['alamat_ktp'].'</td>
						</tr>
						<tr>
							<td>ALAMAT DOMISILI </td><td> : </td><td> '.$data['alamat_dom'].'</td>
						</tr>
						<tr>
							<td>HANDPHONE </td><td> : </td><td> '.$data['handphone'].'</td>
						</tr>
						<tr>
							<td>TOEFL </td><td> : </td><td> '.$data['toefl'].'</td>
						</tr>
					</table>
					
					<nav class="navbar navbar-expand-lg bg-info" style="min-height:0px; margin-bottom:0px;">
						<h6 style="width:100%; margin-bottom:0px; padding-left:10px; color:#ffffff;"><b>PENDIDIKAN FORMAL</b></h6>
					</nav>
					
					<table class="table table-bordered"  style="margin-top: 10px;">
						<thead style="background-color:#B4B7BA;">
						  <tr>
							<th>NO</th>
							<th>UNIVERSITAS</th>
							<th>FAKULTAS/JURUSAN/PROGRAM STUDI</th>
							<th>TAHUN</th>
							<th>IPK</th>
						  </tr>
						</thead>
						<tbody>';
						
		$pend = json_decode(file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=dt_pend&email='.$email));
		$a=1;
		foreach($pend as $k){
			
				$html .='<tr>
							<td>'.$a.'</td>
							<td>'.$k->sekolah.'</td>
							<td>'.$k->jurusan.'</td>
							<td>'.$k->thn_masuk.' / '.$k->thn_lulus.'</td>
							<td>'.$k->ipk.'</td>
						  </tr>';
				$a++;
			}
				
				$html .= '</tbody>
					</table>
					
					<nav class="navbar navbar-expand-lg bg-info" style="min-height:0px; margin-bottom:0px;">
						<h6 style="width:100%; margin-bottom:0px; padding-left:10px; color:#ffffff;"><b>PENGALAMAN BEKERJA</b></h6>
					</nav>
					
					<table class="table table-bordered" style="margin-top: 10px;">
						<thead style="background-color:#B4B7BA;">
						  <tr>
							<th>NO</th>
							<th>NAMA PERUSAHAAN</th>
							<th>JABATAN</th>
							<th>TAHUN</th>
						  </tr>
						</thead>
						<tbody>';
						
		$kerja = json_decode(file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=dt_ker&email='.$email));
		$a=1;
		foreach($kerja as $k){
			
				$html .='<tr>
							<td>'.$a.'</td>
							<td>'.$k->perusahaan.'</td>
							<td>'.$k->jabatan.'</td>
							<td>'.$k->thn_masuk_k.' / '.$k->thn_keluar_k.'</td>
						</tr>';
				$a++;
			}
				
				$html .= '</tbody>
					</table>
					
					
					<nav class="navbar navbar-expand-lg bg-info" style="min-height:0px; margin-bottom:0px;">
						<h6 style="width:100%; margin-bottom:0px; padding-left:10px; color:#ffffff;"><b>PENGALAMAN BERORGANISASI</b></h6>
					</nav>
					
					<table class="table table-bordered" style="margin-top: 10px;">
						<thead style="background-color:#B4B7BA;">
						  <tr>
							<th style="text-align:center;">NO</th>
							<th style="text-align:center;">NAMA ORGANISASI</th>
							<th style="text-align:center;">JENIS ORGANISASI</th>
							<th style="text-align:center;" width="10px">JABATAN</th>
							<th style="text-align:center;" width="10px">TAHUN</th>
						  </tr>
						</thead>
						<tbody>';
						
		$organisasi = json_decode(file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=dt_org&email='.$email));
		$a=1;
		foreach($organisasi as $k){
				$jenis = $k->jenis_o;
				if ($jenis == '0') {
					$jenis_ = 'Tidak ikut dalam organisasi';
				} else if ($jenis == '1') {
					$jenis_ = 'Politik';
				} else if ($jenis == '2') {
					$jenis_ = 'Ekonomi, termasuk Badan Usaha Negara';
				} else if ($jenis == '3') {
					$jenis_ = 'Sosial';
				} else if ($jenis == '4') {
					$jenis_ = 'Kebudayaan';
				} else if ($jenis == '5') {
					$jenis_ = 'Pendidikan';
				} else if ($jenis == '6') {
					$jenis_ = 'Keagamaan';
				} else if ($jenis == '7') {
					$jenis_ = 'Olah raga';
				} else if ($jenis == '8') {
					$jenis_ = 'Golongan Karya';
				} else if ($jenis == '9') {
					$jenis_ = 'Organisasi Masa';
				} else if ($jenis == 'B') {
					$jenis_ = 'Dharma Wanita';
				} else if ($jenis == 'C') {
					$jenis_ = 'KORPRI';
				} else {
					$jenis_ = $jenis;
				}
				$html .='<tr>
							<td>'.$a.'</td>
							<td>'.$k->organisasi.'</td>
							<td>'.$jenis_.'</td>
							<td>'.$k->jabatan_o.'</td>
							<td>'.$k->thn_masuk_o.' / '.$k->thn_keluar_o.'</td>
						</tr>';
				$a++;
			}
				
				$html .= '</tbody>
					</table>
					
					
              </div><!-- /#content -->        
          </body>
		  
			<!--   Core JS Files   -->
			<script src="/assets2/js/core/jquery.3.2.1.min.js" type="text/javascript"></script>
			<script src="/assets2/js/core/popper.min.js" type="text/javascript"></script>
			<script src="/assets2/js/core/bootstrap.min.js" type="text/javascript"></script>
			
			<script src="/assets2/js/plugins/bootstrap-switch.js"></script>
			
			<script src="/assets2/js/plugins/nouislider.min.js" type="text/javascript"></script>
			
			<script src="/assets2/js/now-ui-kit.js?v=1.1.0" type="text/javascript"></script>

			<link href="/assets2/fullcalendar.css" rel="stylesheet" />
			<link href="/assets2/fullcalendar.print.css" rel="stylesheet" media="print" />

			<script src="/assets2/jquery/jquery-ui.custom.min.js"></script>

			<script src="/assets2/fullcalendar.js"></script>

			<script src="/assets2/js/bootstrap-datepicker.min.js"></script>

        </html>
        ';
        $mpdf->WriteHTML($html);
        $mpdf->Output();die();
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */