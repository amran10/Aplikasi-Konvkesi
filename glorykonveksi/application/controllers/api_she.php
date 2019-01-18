<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class api_she extends CI_Controller {

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
		
    }
	
	public function get_pend(){
		$id2=  $this->uri->segment(3);
            // mengambil data mahasiswa dari database
            //$mhs = json_encode("http://webjob.wika.co.id/lama/wj_api3.php?act=cek_fakultas&kd_pend=50&maxResults=10&q=50");
            // menjadikan objek menjadi JSON
			$tambah = str_replace("-o-","=",$id2);
			$tambah = str_replace("-t-","&",$tambah);
			
            $data = file_get_contents("http://webjob.wika.co.id/lama/wj_api3.php?".$tambah);
            // mengeluarkan JSON ke browser
		header("Access-Control-Allow-Origin: *");
        header('HTTP/1.1: 200');
        header('Status: 200');
        header('Content-Length: '.strlen($data));
        exit($data);
	}
	
	public function del_dtl(){
		$id2=  $this->uri->segment(3);
            // mengambil data mahasiswa dari database
            //$mhs = json_encode("http://webjob.wika.co.id/lama/wj_api3.php?act=cek_fakultas&kd_pend=50&maxResults=10&q=50");
            // menjadikan objek menjadi JSON
			$tambah = base64_decode($id2);
			$tambah = str_replace("-o-","=",$tambah);
			$tambah = str_replace("-t-","&",$tambah);

			$dataz = array('kunci'=>'bolehhapus');
			
            //$data = file_get_contents("http://webjob.wika.co.id/lama/riwayat_delet.php?".$tambah);
            // mengeluarkan JSON ke browser
		// kirim API
			$url_x = 'http://webjob.wika.co.id/lama/riwayat_delet_api.php?'.$tambah.$this->session->userdata('username');	
//echo $url_x; die();			
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

			//echo $raw_response; die();
			if($hasil[0] == 'gagal'){
				$data['isi_msg'] = $hasil[1];
				//exit($data);
				redirect('hal_data_diri');
				//$this->load->view('lowongan_r',$data);
			}else{
				$data['isi_msg'] = '';
				redirect('hal_data_diri');
				//$this->load->view('lowongan_r',$data);
			}
			//echo $raw_response; die();
		// kirim API 
	}
	public function del_dt_faq(){
		$id2=  $this->uri->segment(3);
            // mengambil data mahasiswa dari database
            //$mhs = json_encode("http://webjob.wika.co.id/lama/wj_api3.php?act=cek_fakultas&kd_pend=50&maxResults=10&q=50");
            // menjadikan objek menjadi JSON
			$tambah = $id2;
			$tambah = str_replace("-o-","=",$tambah);
			$tambah = str_replace("-t-","&",$tambah);

			$dataz = array('kunci'=>'bolehhapus');
			//echo "hbjbjbbjb";die();
            //$data = file_get_contents("http://webjob.wika.co.id/lama/riwayat_delet.php?".$tambah);
            // mengeluarkan JSON ke browser
		// kirim API
			$url_x = 'http://webjob.wika.co.id/lama/riwayat_delet_api.php?'.$tambah;	
			//echo $url_x; die();			
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

			//echo $raw_response; die();
			if($hasil[0] == 'gagal'){
				$data['isi_msg'] = $hasil[1];
				//exit($data);
				redirect('admin_faq');
				//$this->load->view('lowongan_r',$data);
			}else{
				$data['isi_msg'] = '';
				redirect('admin_faq');
				//$this->load->view('lowongan_r',$data);
			}
			//echo $raw_response; die();
		// kirim API 
	}
	public function update_dt_faq(){
		$id2=  $this->uri->segment(3);
            // mengambil data mahasiswa dari database
            //$mhs = json_encode("http://webjob.wika.co.id/lama/wj_api3.php?act=cek_fakultas&kd_pend=50&maxResults=10&q=50");
            // menjadikan objek menjadi JSON
			$tambah = $id2;
			$tambah = str_replace("-o-","=",$tambah);
			$tambah = str_replace("-t-","&",$tambah);

			$dataz = array('kunci'=>'bolehhapus');
			//echo "hbjbjbbjb";die();
            //$data = file_get_contents("http://webjob.wika.co.id/lama/riwayat_delet.php?".$tambah);
            // mengeluarkan JSON ke browser
		// kirim API
			$url_x = 'http://webjob.wika.co.id/lama/riwayat_delet_api.php?'.$tambah;	
			//echo $url_x; die();			
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

			//echo $raw_response; die();
			if($hasil[0] == 'gagal'){
				$data['isi_msg'] = $hasil[1];
				//exit($data);
				redirect('admin_faq');
				//$this->load->view('lowongan_r',$data);
			}else{
				$data['isi_msg'] = '';
				redirect('admin_faq');
				//$this->load->view('lowongan_r',$data);
			}
			//echo $raw_response; die();
		// kirim API 
	}
	public function del_dt_kalender(){
		$id2=  $this->uri->segment(3);
            // mengambil data mahasiswa dari database
            //$mhs = json_encode("http://webjob.wika.co.id/lama/wj_api3.php?act=cek_fakultas&kd_pend=50&maxResults=10&q=50");
            // menjadikan objek menjadi JSON
			$tambah = $id2;
			$tambah = str_replace("-o-","=",$tambah);
			$tambah = str_replace("-t-","&",$tambah);

			$dataz = array('kunci'=>'bolehhapus');
			//echo "hbjbjbbjb";die();
            //$data = file_get_contents("http://webjob.wika.co.id/lama/riwayat_delet.php?".$tambah);
            // mengeluarkan JSON ke browser
		// kirim API
			$url_x = 'http://webjob.wika.co.id/lama/riwayat_delet_api.php?'.$tambah;	
			//echo $url_x; die();			
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

			//echo $raw_response; die();
			if($hasil[0] == 'gagal'){
				$data['isi_msg'] = $hasil[1];
				//exit($data);
				redirect('admin_kalender');
				//$this->load->view('lowongan_r',$data);
			}else{
				$data['isi_msg'] = '';
				redirect('admin_kalender');
				//$this->load->view('lowongan_r',$data);
			}
			//echo $raw_response; die();
		// kirim API 
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */