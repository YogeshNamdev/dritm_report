<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
        
	
	public function __construct()
	 {
	  parent::__construct();
	 
	  if(!isset($_SESSION['userdata']) || $_SESSION['userdata']['user_logged_status'] != TRUE)
	   {
	    redirect('app/logout');
	   }
	  $_SESSION['page_start_time'] = microtime(true); 
	 }
	
	
	public function index($page = "dashboard")
	 {
	  if(!file_exists(APPPATH.'views/app/pages/'.$page.'.php'))
           {
	    show_404();
	   }
	  	$data = array();
		$data["title"] = "Dashboard";
		$user_id = $_SESSION["userdata"]["user_id"];
		$role_id = $_SESSION["userdata"]["role_id"];
		if(!in_array((int)$role_id, array(1, 2, 3)))
		{
			show_error('You are not authorized to access dashboard.', 403);
		}
		$data["role_id"] = $role_id;
		$this->load->model("Report_m");
		$data["agent_list"] = $this->Report_m->get_all_user_list();
		$data["department_list"] = $this->Report_m->get_all_departments();
		$data["attribute_list"] = $this->Report_m->get_all_attribute();
      

	  $this->load->view('app/templates/header' , $data);
	  $this->load->view('app/templates/side_panel' , $data);
	  $this->load->view('app/pages/'.$page , $data);
	  $this->load->view('app/templates/footer' , $data);
	 }
	 

	
	 

    
   
	
    
  
}
