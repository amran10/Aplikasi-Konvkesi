<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class c_tentang_kami extends CI_Controller {

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
		// $datay = file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=get_nav');
  //       $dataz = explode('-%-',$datay);
  //       $datax = json_decode($dataz[1]);
		// foreach($datax as $dt){
		// 	$data[$dt->menu] = $dt->link_nav;
		// 	//echo $data[$dt->menu];
		// }

		// $data['list_evt'] = json_decode(file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=list_kalender_api'));
		
		// 	$data['des_menu'] = "KALENDER REKRUTMEN WIKA";

			// $this->load->view('v_kalender',$data);
			$this->load->view('tentang_kami');
	}

	public function v_kalender()
	{

		$this->load->view('v_kalender');

	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */