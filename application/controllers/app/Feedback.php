<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Feedback extends CI_Controller {
        
	
	public function __construct()
	 {
	  parent::__construct();
	 }
	
	
	public function index($page = "feedback")
	 {
	  if(!file_exists(APPPATH.'views/app/pages/'.$page.'.php'))
           {
	    show_404();
	   }
	  
	  $data = array();
	  
	  $data["title"] = "feedback";
	  $this->load->model("users_m");
	  $data["quality_feedback"] = $this->users_m->get_all_data("quality_feedback",array("status!="=>0),'id');
	  $this->load->view('app/pages/'.$page , $data);
	 }

	
	
	
}
