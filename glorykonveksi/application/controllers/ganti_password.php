<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ganti_password extends CI_Controller {

  /**
   * Index Page for this controller.
   *
   * Maps to the following URL
   *    http://example.com/index.php/welcome
   *  - or -  
   *    http://example.com/index.php/welcome/index
   *  - or -
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
        $this->load->model('m_ganti_password');
         
    }

  public function index()
  {

    $datay = file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=get_nav');
    $dataz = explode('-%-',$datay);
    $datax = json_decode($dataz[1]);
    foreach($datax as $dt){
      $data[$dt->menu] = $dt->link_nav;
    }    


    $data['des_menu'] = "CHANGE PASSWORD";
    $this->load->view('ganti_password',$data);
  
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
            $password = $this->input->post('password');
            $new_password   =   $this->input->post('new_password');
            $c_n_password = $this->input->post('c_n_password');

            //echo $jawaban;

      $dataz = array(
            'password' => $password,
            'new_password' => $new_password,
            'c_n_password' => $c_n_password
            );
      //var_dump($dataz);die();
      
      
      // echo "adadaadaadwaawdwa";
      // echo $jawaban;die();
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
      
      var_dump($raw_response);die();
      
      if (strpos($raw_response, '1 sukses') !== FALSE) {
        $result = 'sukses';
      }
      //echo "curl response is:" . $result.$error;
      curl_close ($ch);
      //var_dump($raw_response);die();

      $hasil = explode('-o-',$raw_response);

      $datay = file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=get_nav');
      $dataz = explode('-%-',$datay);
      $datax = json_decode($dataz[1]);
      foreach($datax as $dt){
        $data[$dt->menu] = $dt->link_nav;
      }


      {
      $datay = file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=get_nav');
      $dataz = explode('-%-',$datay);
      $datax = json_decode($dataz[1]);
      foreach($datax as $dt){
        $data[$dt->menu] = $dt->link_nav;
      }

      $this->load->view('ganti_password',$data);
      
  } 
    }
      $data['des_menu'] = "CHANGE PASSWORD";

            $this->load->view('ganti_password',$data);


    }

     
} 
 
