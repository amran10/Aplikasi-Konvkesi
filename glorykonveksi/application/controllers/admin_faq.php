<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class admin_faq extends CI_Controller {

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
        $this->load->model('m_faq');
         
    }

	public function index()
	{

    
		$data['faq_'] = json_decode(file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=list_faq_api'));

		$data['des_menu'] = "BACKEND FAQ";
		$this->load->view('beranda',$data);
		
	}

		public function post_faq_backend(){

		if(isset($_POST['submit'])){
            
            // proses login disini
			$pertanyaan = $this->input->post('pertanyaan');
            $jawaban   =   $this->input->post('jawaban');

            //echo $jawaban;

			$dataz = array(
						'pertanyaan' => $pertanyaan,
						'jawaban' => $jawaban
						);
			//var_dump($dataz);die();
			
			
			// echo "adadaadaadwaawdwa";
			// echo $jawaban;die();
			// kirim API
			$url_x = 'http://webjob.wika.co.id/lama/faq_api.php?act_a=do_faq';		
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
			//var_dump($raw_response);die();
			$datay = file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=get_nav');
			$dataz = explode('-%-',$datay);
			$datax = json_decode($dataz[1]);
			foreach($datax as $dt){
				$data[$dt->menu] = $dt->link_nav;
			}

		}
			
			
			$data['faq_']='';
			$a=1;
			$data['faq_'] = json_decode(file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=list_faq_api'));

			$data['des_menu'] = "FAQ";

            $this->load->view('beranda',$data);
        } 
       public function faq_update(){

       	$data['faq_'] = json_decode(file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=list_faq_api_update'));

		if(isset($_POST['edit'])){
            
            // proses login disini
			$pertanyaan = $this->input->post('pertanyaan');
            $jawaban   =   $this->input->post('jawaban');

			$dataz = array(
						'pertanyaan' => $pertanyaan,
						'jawaban' => $jawaban
						);
		}

            $this->load->view('beranda',$data);
        } 

         public function beranda(){

            $this->load->view('beranda');
        } 
 }
