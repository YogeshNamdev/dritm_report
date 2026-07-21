<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends CI_Controller {

	
	public function __construct()
	 {
	  parent::__construct();
	  
	  $_SESSION['page_start_time'] = microtime(true);
	  
	  ini_set("display_errors" , 1);
	  error_reporting(E_ALL);
	 }
	
	
	public function index($page = "index")
	 {
	  $data["page"] = $page;
          
          $data['title'] = APP_TITLE;
          $this->load->model("users_m");
          $data["role_list"] = $this->users_m->get_all_user_roles();
     
          
          
          $title = ucfirst(APP_TITLE);
	
	  
                 $load_page = $page;
              
                $this->load->view('web/templates/js-scripts', $data);
		$this->load->view('web/pages/'.$load_page, $data);
	 }
	
	
}
