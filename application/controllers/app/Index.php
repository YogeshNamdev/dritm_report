<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends CI_Controller {
	 
	public function __construct()
	 {
	  parent::__construct();
	  $_SESSION['page_start_time'] = microtime(true);
	 }
	
	public function index($page = "index")
	 {
	  redirect('app/login');
	 }
	 

}
