<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class kontak extends CI_Controller {

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
		
		// $data['des_menu'] = "KONTAK";
		
		// $data['isi_msg'] = '';
		
		// $this->load->view('kontak',$data);
		$this->load->view('kontak');
	}
	
	// public function kirim_email()
	// {
	// 	$this->load->library('MyPHPMailer');
	// 	// kirim email

	// 		//$path = base_url().'assets/img/'.$nip.'.png';
	// 		//$path2 = 'assets/img/'.$nip.'.png';
		
	// 		//$data['dataeml'] = $this->model_peserta->tampil_data_u($id_evn)->row_array();

	// 		$fromEmail = "event@wika.co.id";
	// 		$email = "rekrut.ppcp@wika.co.id";
	// 		//$fromEmail = "ngugartup@gmail.com";
	// 		$isiEmail = "<html>
	// 						<head></head>
	// 						<body>
	// 							email : ".$this->input->post('email')."<br />
	// 							Nama : ".$this->input->post('nama')."<br />
	// 							Subject : ".$this->input->post('prihal')."<br />
	// 							isi email : <br />".$this->input->post('pesan')."<br /><br /><br />
	// 							<br /><br /><br />
	// 							Terimakasih.
	// 						</body>
	// 					</html>";
	// 		$mail = new PHPMailer();
	// 		//$FromName = "WIKA EVENT";
	// 		//$mail->AddEmbeddedImage($path2, 'gambar');
	// 		$mail->IsHTML(true);    // set email format to HTML
	// 		$mail->IsSMTP();   // we are going to use SMTP
	// 		$mail->SMTPAuth   = true; // enabled SMTP authentication
	// 		$mail->SMTPSecure = "ssl";  // prefix for secure protocol to connect to the server
	// 		//$mail->Host       = "smtp.gmail.com";      // setting GMail as our SMTP server
	// 		$mail->Host       = "mail.wika.co.id";
	// 		//$mail->Host       = "10.4.0.81";
	// 		$mail->Port       = 465;                   // SMTP port to connect to GMail
	// 		$mail->Username   = $fromEmail;  // alamat email kamu
	// 		$mail->Password   = "w1k4satrian";            // password GMail
	// 		$mail->SetFrom('agung', 'WIKA WEBJOB');  //Siapa yg mengirim email
	// 		$mail->Subject    = "Pertanyaan";
	// 		$mail->Body       = $isiEmail;
	// 		//$mail->addAttachment($path2);			
	// 		$toEmail = $email; // siapa yg menerima email ini
	// 		$mail->AddAddress($toEmail);
		
	// 		if(!$mail->Send()) {
	// 			//echo "Eror: ".$mail->ErrorInfo;
	// 			$datay = file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=get_nav');
	// 			$dataz = explode('-%-',$datay);
	// 			$datax = json_decode($dataz[1]);
	// 			foreach($datax as $dt){
	// 				$data[$dt->menu] = $dt->link_nav;
	// 			}
				
	// 			$data['des_menu'] = "KONTAK";
				
	// 			$data['isi_msg'] = "Eror: ".$mail->ErrorInfo;
				
	// 			$this->load->view('kontak',$data);
	// 		} else {
	// 			//echo "Berhasil dikirim";
	// 			$datay = file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=get_nav');
	// 			$dataz = explode('-%-',$datay);
	// 			$datax = json_decode($dataz[1]);
	// 			foreach($datax as $dt){
	// 				$data[$dt->menu] = $dt->link_nav;
	// 			}
				
	// 			$data['des_menu'] = "KONTAK";
				
	// 			$data['isi_msg'] = "Berhasil dikirim";
				
	// 			$this->load->view('kontak',$data);
	// 		}

	// 	// email
	// }
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */