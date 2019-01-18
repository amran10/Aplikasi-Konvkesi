<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class admin_foto extends CI_Controller {

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

	 function __construct()
	 {
        parent::__construct();
        $this->load->model('m_upload');
         
    }

	public function index()
	{


		$data['des_menu'] = "BACKEND FOTO";
		$this->load->view('backend_v_foto',$data);
	
	}

		public function upload(){

		$data='';
        if(isset($_POST['submit'])){
			$folders = "assets2/img/upload/";
			$foto			= "";
		
				// echo "jjsssssssss";die();
				if(is_uploaded_file($_FILES['foto']['tmp_name'])) { 
					$file1='f1_'.mt_rand(10,99).'_'.time().strtolower(strrchr($_FILES["foto"]["name"],"."));
					$target_path = $folders.$file1; copy($_FILES['foto']['tmp_name'], $target_path);
					$foto = $file1;
					//$width = $imgsize[0];
				    //$height = $imgsize[1];
				    //$mime = $imgsize['mime'];
					
					//proses barang
					
					//$id				= $this->input->post('id');
					$keterangan			= $this->input->post('keterangan');
					//$foto			= $this->input->post('foto');
					$set_foto			= $this->input->post('set_foto');
					$created_date	= date("Y-m-d H:i:s");
					
					// $datay = file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=get_nav');
					// $dataz = explode('-%-',$datay);
					// $datax = json_decode($dataz[1]);
					// foreach($datax as $dt){
					// 	$data[$dt->menu] = $dt->link_nav;
					// }

					//$asd = $kelamin.$nama;
		            $dataz       = array(
										//'id' => $id,
										'keterangan' => $keterangan,
										'foto' => $foto,
										'set_foto' => $set_foto,
										'created_date' => $created_date
										);
		            //echo $set_foto;die();
			     	$url_x = 'http://webjob.wika.co.id/lama/foto_api.php?act_a=do_foto';		
					$ch = curl_init();           
					curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
					curl_setopt($ch,CURLOPT_FAILONERROR,0);
					curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
					curl_setopt($ch,CURLOPT_FRESH_CONNECT,1);
					curl_setopt($ch,CURLOPT_POST,1);
					curl_setopt($ch,CURLOPT_URL,$url_x);
					curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

					///curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
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
					
				}

            $this->load->view('backend_v_foto',$data);
        }
        else{
            
        }
    }

     
} 
 

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */