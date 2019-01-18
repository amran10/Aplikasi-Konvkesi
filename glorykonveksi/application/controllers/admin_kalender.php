<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class admin_kalender extends CI_Controller {

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

	 // function __construct(){
  //       parent::__construct();
  //       $this->load->model('m_upload');
         
  //   }

	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->load->model('m_kalender');
    }

	public function index()
	{
	
		$data['kalender'] = json_decode(file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=list_kalender_api'));

		$data['des_menu'] = "BACKEND KALENDER";
		$this->load->view('backend_v_kalender',$data);
	}

	public function simpan_event(){

		if(isset($_POST['submit'])){
            
            // proses login disini
			$title = $this->input->post('title');
            $start   =  $this->input->post('start');
            $end = $this->input->post('end');
            $description   =   $this->input->post('description');
            //echo $jawaban;

			$dataz = array(
						'title' => $title,
						'start' => $start,
						'end' => $end,
						'description' => $description
						);
			//var_dump($dataz);die();
			
			
			// echo "adadaadaadwaawdwa";
			// echo $jawaban;die();
			// kirim API
			$url_x = 'http://webjob.wika.co.id/lama/kalender_api.php?act_a=do_kalender';		
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
			
			
			$data['kalender']='';
			$a=1;
			$data['kalender'] = json_decode(file_get_contents('http://webjob.wika.co.id/lama/wj_api3.php?act=list_kalender_api'));

			$data['des_menu'] = "KALENDER";

            $this->load->view('backend_v_kalender',$data);
        } 


		Public function getEvents()
		{
			$result=$this->m_kalender->getEvents();
			echo json_encode($result);
		}
		/*Add new event */
		Public function addEvent()
		{
			$result=$this->m_kalender->addEvent();
			echo $result;
		}
		/*Update Event */
		Public function updateEvent()
		{
			$result=$this->m_kalender->updateEvent();
			echo $result;
		}
		/*Delete Event*/
		Public function deleteEvent()
		{
			$result=$this->m_kalender->deleteEvent();
			echo $result;
		}
		Public function dragUpdateEvent()
		{	
			$result=$this->m_kalender->dragUpdateEvent();
			echo $result;
		}

		public function get_events()
	 {
	     // Our Start and End Dates
	     $start = $this->input->get("start");
	     $end = $this->input->get("end");

	     $startdt = new DateTime('now'); // setup a local datetime
	     $startdt->setTimestamp($start); // Set the date based on timestamp
	     $start_format = $startdt->format('Y-m-d H:i:s');

	     $enddt = new DateTime('now'); // setup a local datetime
	     $enddt->setTimestamp($end); // Set the date based on timestamp
	     $end_format = $enddt->format('Y-m-d H:i:s');

	     $events = $this->calendar_model->get_events($start_format, $end_format);

	     $data_events = array();

	     foreach($events->result() as $r) {

	         $data_events[] = array(
	             "id" => $r->ID,
	             "title" => $r->title,
	             "description" => $r->description,
	             "end" => $r->end,
	             "start" => $r->start
	         );
	     }

     echo json_encode(array("events" => $data_events));
     exit();
 }

 }

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */