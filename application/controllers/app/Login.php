<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	
	public function __construct()
	 {
	  parent::__construct();
	 }
	
	
	 public function index($page = "login")
	 {
	  if(isset($_SESSION['userdata']) && $_SESSION['userdata']['user_logged_status'] == TRUE && in_array($_SESSION['userdata']['role_id'], array(1, 2, 3, 4)))
	   {
	    redirect('app/Reports/NoOrSameResolutionDetails');
	   }
	  
	  if(!file_exists(APPPATH.'views/app/pages/'.$page.'.php'))
           {
	    show_404();
	   }
	  
	  $data = array();
	  
	  $data["title"] = "Sign-In";
	  $this->load->model("users_m");
	  $data["role_list"] = $this->users_m->get_all_user_roles();
	  
	  $this->load->view('app/pages/'.$page , $data);
	 }
	public function validate_user_login()
	 {
		//print_r($_POST);die;
	  $data = array();
	  if($this->input->is_ajax_request())
	   {
		$role_id = (isset($_POST["role_id"]))?$this->input->post("role_id" , TRUE):"0";
	    $user_msd = (isset($_POST["user_msd"]))?$this->input->post("user_msd" , TRUE):"";
	    $user_password = (isset($_POST["user_password"]))?$this->input->post("user_password" , TRUE):"";
	    if($role_id == 0 || $role_id == "0")
		{
			$data["response"] = FALSE;
			$data["message"] = "Select Role First.";
		}
		else if(trim($user_msd) == "")
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "Enter your valid MSD-ID.";
	     }
	    else if(trim($user_password) == "")
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "Enter your Password.";
	     }
	    else
	     {
	      $this->load->model("users_m");
			$r = $this->users_m->validate_login($user_msd , $user_password, $role_id);
				
				if($r != FALSE)
				{
					$arr = array("user_id" => $r[0]->user_id ,"role_id" => $r[0]->role_id, "role_name" => $r[0]->role_name , "user_name" => $r[0]->user_name ,"login_date_time"=> date("Y-m-d H:i:s"),"msd_id" => $r[0]->msd_id);
					$ree = $this->users_m->add_log($arr);
					$user_data = array("user_id" => $r[0]->user_id , "role_id" => $r[0]->role_id , "role_name" => $r[0]->role_name , "user_name" => $r[0]->user_name , "msd_id" => $r[0]->msd_id, "user_logged_status" => TRUE,"log_id" =>$ree , "emp_id" =>$r[0]->emp_id );
					$_SESSION["userdata"] = $user_data;
					$data["response"] = TRUE;
					$data["message"] = "Sign-In Successfully.";
					$data["role_id"] = $r[0]->role_id;
					
				}
				else
				{
					$data["response"] = FALSE;
					$data["message"] = "Invalid MSD-ID, password, or role.";
				}
	     }
	   }
	  echo json_encode($data);
	 }
}
