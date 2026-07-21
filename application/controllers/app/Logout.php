<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends CI_Controller {


    public function __construct()
	 {
	  	parent::__construct();
          
	 	 session_destroy();
		}


	public function index()
	 {
		$id = $_SESSION['userdata']['log_id'];
		$update_arr = array("logout_date_time" => date("Y-m-d H:i:s"));
		$this->load->model("users_m");
		$r = $this->users_m->update_log($id , $update_arr);
   	 	 redirect(base_url());
	 }


	public function session_out()
	 {	
   	  redirect('app/login?session_out');
	 }


}
