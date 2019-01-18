<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class admin extends CI_Controller {
  
    // function __construct(){
    //     parent::__construct();
    //     $this->load->helper('url');
    //     $this->load->library('session');
    // }
   
    public function index(){
       

        $this->load->view("v_admin_login");
   
    }
   
    public function proses(){
    $username=$_POST["username"];
	$password=$_POST["password"];
	if($username=="admin" AND $password=="admin")
	{
	 $_SESSION["username"]=$username;
	 redirect('admin_foto');
	}else{
	echo "<script>
	alert('Anda Bukan Admin!!');
	window.location.href='admin';
	</script>";
	}
	   }
    public function logout()
    {
        $this->session->sess_destroy();
        redirect('v_admin_login');
    }
   
} 