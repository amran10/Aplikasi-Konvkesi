<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class n_login extends CI_Controller {

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
		
		$data['des_menu'] = "LOGIN";
		
		$this->load->view('login_webjob',$data);
    }
	
	public function login()
    {
		
        if(isset($_POST['submit'])){
            
            // proses login disini
            //$username   =   $this->input->post('username');
            //$password   =   $this->input->post('password');
			$dataz = array('email' => $this->input->post('username'),
							'password' => $this->input->post('password')
						);
			
		// kirim API
			$url_x = 'http://webjob.wika.co.id/lama/user_api.php?act_a=do_login';		
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
				
				$this->session->set_userdata(array('status_login'=>'oke','username'=>$hasil[1],'id_user'=>$hasil[1],'nm_user'=>$hasil[2]));

				//redirect('auth_wj',$dt_nav);
				redirect('hal_data_diri',$dt_nav);
				
			}else{
				$datay = file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=get_nav');
				$dataz = explode('-%-',$datay);
				$datax = json_decode($dataz[1]);
				foreach($datax as $dt){
					$data[$dt->menu] = $dt->link_nav;
				}
				
				$data['des_menu'] = "LOGIN";
				$data['isi_msg'] = $hasil[1];
				
				$this->load->view('login_webjob',$data);
			}

		// kirim API 
			
        }
        else{
            $datay = file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=get_nav');
			$dataz = explode('-%-',$datay);
			$datax = json_decode($dataz[1]);
			foreach($datax as $dt){
				$data[$dt->menu] = $dt->link_nav;
			}

			$this->load->view('beranda_wj',$data);
        }
    }
	
	public function registrasi()
	{
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
		
		if(isset($_POST['submit'])){
            
            // proses login disini
			$nama = $this->input->post('nama');
            $email   =   $this->input->post('email');
            $password   =   $this->input->post('password');
			$password2   =   $this->input->post('c_password');
			$dataz = array(
						'nama' => $nama,
						'email' => $email,
						'password' => $password,
						'password2' => $password2
						);
			
		// kirim API
			$url_x = 'http://webjob.wika.co.id/lama/user_api.php?act_a=do_reg';		
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
				$data['des_menu'] = "LOGIN";
				$data['isi_msg'] = $hasil[1];
				
				$this->load->view('login_webjob',$data);
				
			}else{
				$datay = file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=get_nav');
				$dataz = explode('-%-',$datay);
				$datax = json_decode($dataz[1]);
				foreach($datax as $dt){
					$data[$dt->menu] = $dt->link_nav;
				}
				
				$data['des_menu'] = "LOGIN";
				$data['isi_msg'] = $hasil[1];
				
				$this->load->view('login_webjob',$data);
			}

		// kirim API 
			
        }
        else{
            $data['des_menu'] = "REGISTRASI";
		
			$this->load->view('reg_webjob',$data);
        }
		
	}
	
	public function aktivasi()
	{
		$email=  base64_decode($this->uri->segment(3));
		
		$datay = file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=get_nav');
		$dataz = explode('-%-',$datay);
		$datax = json_decode($dataz[1]);
		foreach($datax as $dt){
			$data[$dt->menu] = $dt->link_nav;
		}
		
            
		$dataz = array(
					'email' => $email
					);
		
	// kirim API
		$url_x = 'http://webjob.wika.co.id/lama/user_api.php?act_a=do_aktivasi';	
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
		
		if($hasil[0] == "sukses"){
			$datay = file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=get_nav');
			$dataz = explode('-%-',$datay);
			$datax = json_decode($dataz[1]);
			foreach($datax as $dt){
				$data[$dt->menu] = $dt->link_nav;
			}
			
			//$this->session->set_userdata(array('status_login'=>'oke','username'=>$hasil[1],'id_user'=>$hasil[1],'nm_user'=>$hasil[2]));
			$data['des_menu'] = "LOGIN";
			$data['isi_msg'] = $hasil[1];
			
			$this->load->view('login_webjob',$data);
			
		}else{
			$datay = file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=get_nav');
			$dataz = explode('-%-',$datay);
			$datax = json_decode($dataz[1]);
			foreach($datax as $dt){
				$data[$dt->menu] = $dt->link_nav;
			}
			
			$data['des_menu'] = "LOGIN";
			$data['isi_msg'] = $hasil[1];
			
			$this->load->view('login_webjob',$data);
		}

	// kirim API 
			
		
	}
	public function lupa_password()
	
	{
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
		
		if(isset($_POST['submit'])){
            
            // proses login disini
            $email   =   $this->input->post('email');

			$dataz = array(
						'email' => $email
						);
		//echo $email;die();
		//var_dump($email);exit();
		// kirim API
			$url_x = 'http://webjob.wika.co.id/lama/home_api.php?act_a=do_reset_password';		
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
			//$response = json_decode($raw_response, 1);
			#print_r("Post data: ".json_encode($data_string));
			//$datah = $response['data'];
			
			//echo "curl response is:" . $result.$error;
			curl_close ($ch);
			//return array($result, $raw_response, $error);
			}
			//echo $raw_response; die();
				$datay = file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=get_nav');
				$dataz = explode('-%-',$datay);
				$datax = json_decode($dataz[1]);
				foreach($datax as $dt){
					$data[$dt->menu] = $dt->link_nav;
				}
				
            $data['des_menu'] = "LUPA PASSWORD";
		
			$this->load->view('lupa_password',$data);
        
		
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */