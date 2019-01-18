<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class n_faq extends CI_Controller {

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
		// /*
		// $datax = json_decode(file_get_contents('http://localhost/api_evn/get_nav'));
		// foreach($datax as $dt){
		// 	$data[$dt->menu] = $dt->link_nav;
		// }
		// */
		// $datay = file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=get_nav');
		// $dataz = explode('-%-',$datay);
		// $datax = json_decode($dataz[1]);
		// foreach($datax as $dt){
		// 	$data[$dt->menu] = $dt->link_nav;
		// }
		
		// $data['tanggal']='';
		// $data['detail']='';
		// $data['tampil'] ='';
		// $a=1;
		// $datax = json_decode(file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=list_faq_api'));
		// foreach($datax as $dt){
		// 	$data['tampil'] .= "<p><b>$a.  Question : ".$dt->pertanyaan."</b></p>";
		// 	$data['tampil'] .= " <p align='justify'> &nbsp; &nbsp; Answer &nbsp &nbsp&nbsp: ".$dt->jawaban." </p>";
		// 	$a++;
		// }
		
		// $data['des_menu'] = "FAQ";
		
		// $this->load->view('v_faq',$data);
		$this->load->view('v_faq');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */