<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//require 'vendor/autoload.php';
class Users extends CI_Controller {
        
	public function __construct()
	 {
	  parent::__construct();
	  
	  if(!isset($_SESSION['userdata']) || $_SESSION['userdata']['user_logged_status'] != TRUE)
	   {
	    redirect('app/logout');
	   }
	  
	  $_SESSION['page_start_time'] = microtime(true); 
	 }

	private function is_admin()
	 {
	  return isset($_SESSION["userdata"]["role_id"]) && (int)$_SESSION["userdata"]["role_id"] === 1;
	 }

	private function require_admin()
	 {
	  if(!$this->is_admin())
	   {
	    show_error('You are not authorized to access this module.', 403);
	   }
	 }

	private function deny_json()
	 {
	  echo json_encode(array("response" => FALSE, "message" => "You are not authorized to perform this action."));
	  return FALSE;
	 }
	
	
	public function index($page = "users")
	 {
	  $this->require_admin();
	  if(!file_exists(APPPATH.'views/app/pages/'.$page.'.php'))
           {
	    show_404();
	   }
	  
	  $data = array();
	  
	  $data["title"] = "Users";
	  
	  $this->load->model("users_m");
	  $data["role_list"] = $this->users_m->get_all_user_roles();
	  
	  $this->load->view('app/templates/header' , $data);
	  $this->load->view('app/templates/side_panel' , $data);
	  $this->load->view('app/pages/'.$page , $data);
	  $this->load->view('app/templates/footer' , $data);
	 }
	 
	 
	 
	// public function performance()
 //{
		
///	$this->load->view('app/pages/performance');	

 //} 
	 
	 
	 public function performance($page = "performance")
	 {
	  if(!file_exists(APPPATH.'views/app/pages/'.$page.'.php'))
           {
	    show_404();
	   }
	  
	 //  $data = array();
	  
	  $data["title"] = "Performance";
	  
	  $this->load->model("users_m");
	  $data["role_list"] = $this->users_m->get_all_user_roles();
	   $data["msd_id"] = $this->users_m->get_all_user_roles();
	  
	  $this->load->view('app/templates/header' , $data);
	  $this->load->view('app/templates/side_panel' , $data);
	  $this->load->view('app/pages/'.$page , $data);
	  $this->load->view('app/templates/footer' , $data);
	 }
	 
	 public function logs($page = "logs")
	 {
	  if(!file_exists(APPPATH.'views/app/pages/'.$page.'.php'))
           {
	    show_404();
	   }
	  
	  $data = array();
	  
	  $data["title"] = "Dash-board";
      $user_id = $_SESSION["userdata"]["user_id"];
      $role_id = $_SESSION["userdata"]["role_id"];
     $data["role_id"] = $role_id;
      $this->load->model("users_m");
      $get = $this->users_m->get_msd_id($user_id,$role_id);
      if($get != false){
        $msd_id  = $get[0]->msd_id;
	$data["msd_id"] = $get[0]->msd_id;
       }else{
        redirect('app/home');
      }
      

	  $this->load->view('app/templates/header' , $data);
	  $this->load->view('app/templates/side_panel' , $data);
	  $this->load->view('app/pages/'.$page , $data);
	  $this->load->view('app/templates/footer' , $data);
	 }
	 
	 
	 
	 
	 
	 
	


	public function perform_all() {
		
           $data = array();
		   
				$rol_id = (isset($_POST["rol_id"]))?$this->input->post("rol_id" , TRUE):"";
				$start_date = (isset($_POST["start_date"]))?$this->input->post("start_date" , TRUE):"";
				$end_date = (isset($_POST["end_date"]))?$this->input->post("end_date" , TRUE):"";

				$this->load->model("users_m");
				$rolid_fetch = $this->users_m->rolid_fetch($rol_id);
				
				//echo "<pre>";print_r($rolid_fetch);die;

			if ($rolid_fetch !== false) {
				$ss = 0;
				$ss1 = 0;
				$dd = 0;
				$dd1 = 0;
				$ee = 0;
				$ee1 = 0;
				$hh = 0;
				$hh1 = 0;
				$mm = 0;
				$mm1 = 0;
				$aa = 0;
				$ahtper_count = 0;
       
			foreach ($rolid_fetch as $roid) {
				$msd_id = $roid->msd_id;
				$perform = $this->users_m->get_performance_rolid_wise($msd_id, $start_date, $end_date);
			
			//echo "<pre>";print_r($perform);die;
			if($perform != FALSE){
			
            foreach($perform as $qu) {
				
				$msdid = $qu->msdid; 
				$name = $qu->name;	
                
                $ss = $qu->csaty + $ss;
				$ss1 = $qu->total + $ss1;
				$dd = $qu->uniquecall + $dd;
				$dd1 = $qu->rucall + $dd1;
				$ee = $qu->resolutionless + $ee;
				$ee1 = $qu->resolutiontotal + $ee1;
				$hh = $qu->ticketcount + $hh;
				$hh1 = $qu->totalcount + $hh1;
				$aa = $qu->nocalls + $aa;

                $ahtper = $this->users_m->ahtper($msd_id, $start_date, $end_date);
               
			   if($ahtper != false){
                 $ahtper_count = count($ahtper);
               }else{
                 $ahtper_count = 0;
               }

                $timeParts = explode(':', $qu->talktime);

                if (count($timeParts) == 3) {
                    $talkTimeSeconds = ($timeParts[0] * 3600) + ($timeParts[1] * 60) + $timeParts[2];
                } else {
                    $talkTimeSeconds = 0;
                }

                $mm += $talkTimeSeconds;
                $mm1 += $qu->nocalls;
				
            }
			
			
				$csat = ($ss/$ss1)*100;
				$fcr = ($dd/$dd1)*100;
				$resolution = ($ee/$ee1)*100;
				$reopen = ($hh1/$hh)*100;
				$avr = ($aa/$ahtper_count);

            // AHT in seconds
				$aht = ($mm1 != 0) ? ($mm / $mm1) : 0; 
				$aht_hours = floor($aht / 3600);
				$aht_minutes = floor(($aht % 3600) / 60);
				$aht_seconds = $aht % 60;
				$aht_formatted = sprintf('%02d:%02d:%02d', $aht_hours, $aht_minutes, $aht_seconds);
         
				$data['c_sat'] = round($csat, 2);
				$data['fcr'] = round($fcr, 2);
				$data['resolution'] = round($resolution, 2);
				$data['reopen'] = round($reopen, 2);
				$data['avr'] = round($avr);
				$data['aht'] = $aht_formatted;
				$data['msdid'] = $msdid;
				$data['name'] = $name;
				//$data['datemonth'] = $datemonth;
				
				//echo "<pre>";print_r($data);die;
				
       $arer[] = $data;
			}
        }
		//echo "<pre>";print_r($arer);die;
		
          $data["response"] = TRUE;
	      $data["message"] = count($arer)." Record Found.";
	      $data["total_record"] = count($arer);
        $data["all_record"] = $arer;
		
    } else {
      
         $data["response"] = FALSE;
	      $data["message"] = "No Record Found.";
    }

    echo json_encode($data);
}
	 

	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	
	
	public function add_user()
	 {
	  if(!$this->is_admin()) { return $this->deny_json(); }
	  $data = array();
	  if($this->input->is_ajax_request())
	   {
	    $role_id = (isset($_POST["role_id"]))?$this->input->post("role_id" , TRUE):0;
	    $user_name = (isset($_POST["user_name"]))?$this->input->post("user_name" , TRUE):"";
	    $msd_id = (isset($_POST["msd_id"]))?$this->input->post("msd_id" , TRUE):"";
	    $user_password = (isset($_POST["user_password"]))?$this->input->post("user_password" , TRUE):"";
	    if($role_id == 0 || trim($role_id) == "")
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "Select user role.";
	     }
	    else if(trim($user_name) == "")
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "Enter your full name.";
	     }
	    else if(trim($msd_id) == "")
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "Enter your valid MSD-ID.";
	     }
	    else if(trim($user_password) == "")
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "Enter user password.";
	     }
	    else
	     {
	      $this->load->model("users_m");
	      $r = $this->users_m->check_email_exists($msd_id);
	      if($r == FALSE)
	       {
	        $insert_arr = array("role_id" => $role_id , "user_name" => $user_name , "msd_id" => $msd_id , "user_password" => $user_password , "eby" => $_SESSION["userdata"]["user_id"] , "eat" => date("Y-m-d H:i:s"));
	        $r = $this->users_m->add_user($insert_arr);
	        if($r > 0)
	         {
	          $data["response"] = TRUE;
	          $data["message"] = "User added successfully.";
	         }
	        else
	         {
	          $data["response"] = FALSE;
	          $data["message"] = "Some error occured. Please try again later.";
	         }
	       }
	      else
	       {
	        $data["response"] = FALSE;
	        $data["message"] = "This MSD-ID already exists. Try with another MSD-ID.";
	       }
	     }
	   }
	  echo json_encode($data);
	 }
	
	
	public function edit_user_profile()
	 {
	  if(!$this->is_admin()) { return $this->deny_json(); }
	  $data = array();
	  if($this->input->is_ajax_request())
	   {
	    $user_id = (isset($_POST["user_id"]))?$this->input->post("user_id" , TRUE):0;
	    $role_id = (isset($_POST["role_id"]))?$this->input->post("role_id" , TRUE):0;
	    $user_name = (isset($_POST["user_name"]))?$this->input->post("user_name" , TRUE):"";
	    $msd_id = (isset($_POST["msd_id"]))?$this->input->post("msd_id" , TRUE):"";
		$user_password = (isset($_POST["user_password"]))?$this->input->post("user_password" , TRUE):"";
	    if($user_id == 0 || trim($user_id) == "")
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "Invalid User-ID.";
	     }
	    else if($role_id == 0 || trim($role_id) == "")
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "Select user role.";
	     }
	    else if(trim($user_name) == "")
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "Enter your full name.";
	     }
	    else if(trim($msd_id) == "")
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "Enter your valid DRITM-ID.";
	     }
	    else
	     {
	      $this->load->model("users_m");
	      $r = $this->users_m->check_user_email_exists($user_id , $msd_id);
	      if($r == FALSE)
	       {
	        $update_arr = array("role_id" => $role_id , "user_name" => $user_name , "msd_id" => $msd_id, "eby" => $_SESSION["userdata"]["user_id"] , "eat" => date("Y-m-d H:i:s"));
	        if(trim($user_password) != "")
	         {
	          $update_arr["user_password"] = $user_password;
	         }
	        $r = $this->users_m->update_user($user_id , $update_arr);
	        if($r > 0)
	         {
	          $data["response"] = TRUE;
	          $data["message"] = "User profile updated successfully.";
	         }
	        else
	         {
	          $data["response"] = FALSE;
	          $data["message"] = "Some error occured. Please try again later.";
	         }
	       }
	      else
	       {
	        $data["response"] = FALSE;
	        $data["message"] = "This DRITM-ID is already exists. Try with other DRITM-ID.";
	       }
	     }
	   }
	  echo json_encode($data);
	 }
	
	
	public function get_all_users()
	 {
	  if(!$this->is_admin()) { return $this->deny_json(); }
	  $data = array();
	  if($this->input->is_ajax_request())
	   {
	    $this->load->model("users_m");
	    $r = $this->users_m->get_all_users();
	    if($r != FALSE)
	     {
	      $data["response"] = TRUE;
	      $data["message"] = count($r)." Record Found.";
	      $data["total_record"] = count($r);
	      $data["all_record"] = $r;
	     }
	    else
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "No Record Found.";
	     }
	   }
	  echo json_encode($data);
	 }

	 public function get_all_advisers()
	 {
	  $data = array();
	  if($this->input->is_ajax_request())
	   {
	    $this->load->model("users_m");
	    $r = $this->users_m->get_all_users();
	    if($r != FALSE)
	     {
			$arer = array();
			foreach($r as $rr){
				$arr = array();
				$arr["role_id"] = $rr->role_id;
				$arr["user_name"]= $rr->user_name;
				$arr["user_id"] = $rr->user_id;
				$arr["msd_id"] = $rr->msd_id;
				$msd_id = $rr->msd_id;
				$quality = $this->users_m->get_adviser_quality($msd_id);
				$fatal = $this->users_m->get_fatal_count($msd_id);
				$left = 0;
				if($fatal != False){
					foreach($fatal as $fa){
						if($fa->action_value != 0){
							$left =	1 + $left; 
						}
					}
					
				}
				if($fatal != false){
					$fatal_count = count($fatal);
				}else{
					$fatal_count = 0;
				}
				//if($quality != false){
				$arr['total_audit'] = count($quality);
				$arr['fatal_count'] = $fatal_count;
				$arr['left'] = ($fatal_count-$left);
				//}else{
					//$arr['total_audit'] = 0;
				//$arr['fatal_count'] = 0;
				//$arr['left'] = 0;
				//}
				$arer[] = $arr;
			}
	      $data["response"] = TRUE;
	      $data["message"] = count($arer)." Record Found.";
	      $data["total_record"] = count($arer);
	      $data["all_record"] = $arer;
	     }
	    else
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "No Record Found.";
	     }
	   }
	  echo json_encode($data);
	 }

	 public function get_all_logs()
	 {
	  $data = array();
	  if($this->input->is_ajax_request())
	   {
	    $this->load->model("users_m");
	    $r = $this->users_m->get_all_logs();
	    if($r != FALSE)
	     {
	      $data["response"] = TRUE;
	      $data["message"] = count($r)." Record Found.";
	      $data["total_record"] = count($r);
	      $data["all_record"] = $r;
	     }
	    else
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "No Record Found.";
	     }
	   }
	  echo json_encode($data);
	 }
	
	
	public function get_feedback_details_by_id()
	 {
//echo "<pre>";print_r($_POST);die;
	  $data = array();
	  if($this->input->is_ajax_request())
	   {
	    $id = (isset($_POST["id"]))?$this->input->post("id" , TRUE):0;
	    if($id == 0 || trim($id) == "")
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "Invalid ID.";
	     }
	    else
	     {
	      $this->load->model("users_m");
	      $r = $this->users_m->get_feedback_details_by_id($id);
	      if($r != FALSE)
	       {
	        $data["response"] = TRUE;
	        $data["message"] = count($r)." Record Found.";
	        $data["total_record"] = count($r);
	        $data["all_record"] = $r;
	       }
	      else
	       {
	        $data["response"] = FALSE;
	        $data["message"] = "No Record Found.";
	       }
	     }
	   }
	  echo json_encode($data);
	 }

	 
	
	
	public function manage_login_status_of_user()
	 {
	  if(!$this->is_admin()) { return $this->deny_json(); }
	  $data = array();
	  if($this->input->is_ajax_request())
	   {
	    $user_id = (isset($_POST["user_id"]))?$this->input->post("user_id" , TRUE):0;
	    $login_status = (isset($_POST["login_status"]))?$this->input->post("login_status" , TRUE):0;
	    if($user_id == 0 || trim($user_id) == "")
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "Invalid User ID.";
	     }
	    else
	     {
	      $this->load->model("users_m");
	      
	      $update_arr = array("login_status" => $login_status , "eby" => $_SESSION["userdata"]["user_id"] , "eat" => date("Y-m-d H:i:s"));
	      $r = $this->users_m->update_user($user_id , $update_arr);
	      if($r > 0)
	       {
	        $data["response"] = TRUE;
	        $data["message"] = "User login status updated successfully.";
	       }
	      else
	       {
	        $data["response"] = FALSE;
	        $data["message"] = "Some error occured. Please try again later.";
	       }
	     }
	   }
	  echo json_encode($data);
	 }
	
	
	public function delete_feedback()
	 {
	  $data = array();
	  if($this->input->is_ajax_request())
	   {
	    $id = (isset($_POST["id"]))?$this->input->post("id" , TRUE):0;
	    if($id == 0 || trim($id) == "")
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "Invalid ID.";
	     }
	    else
	     {
	      $this->load->model("users_m");
	      
	      $update_arr = array("status" => 0 , "eby" => $_SESSION["userdata"]["user_id"] , "eat" => date("Y-m-d H:i:s"));
	      $r = $this->users_m->update_feedback($id , $update_arr);
	      if($r > 0)
	       {
	        $data["response"] = TRUE;
	        $data["message"] = "Feeback deleted successfully.";
	       }
	      else
	       {
	        $data["response"] = FALSE;
	        $data["message"] = "Some error occured. Please try again later.";
	       }
	     }
	   }
	  echo json_encode($data);
	 }


	public function edit_quality_feedback()
	{
		//echo "<pre>";print_r($_POST);die;
	  $data = array();
	  if($this->input->is_ajax_request())
	   {
		$id = (isset($_POST["feedback_id"]))?$this->input->post("feedback_id" , TRUE):"";
		$title = (isset($_POST["title"]))?$this->input->post("title" , TRUE):"";
	    $briefing_type = (isset($_POST["briefing_type"]))?$this->input->post("briefing_type" , TRUE):"";
	    $description = (isset($_POST["txtEditor"]))?$this->input->post("txtEditor" , TRUE):"";
	    if($briefing_type == 0 || trim($briefing_type) == "")
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "Please Select Briefing Status.";
	     }
	    else if(trim($title) == "")
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "Enter Title.";
	     }
	    else if($description == "")
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "Enter Description.";
	     }
	    else
	     {
	      $this->load->model("users_m");
	      if(!empty($_FILES['link']['name'])){
			$res = $this->users_m->get_feedback_by_id($id);
			if($res != false){
				$ext1 = $res[0]->feedback_link;
				if($ext1 != false){
					$d = base_url().'web_components/pdf/feedback/'.$id.'.'.$ext1;
					//print_r($d);die;
					unlink($d);
				}
			}
		}
	      $update_arr = array('title'=>$title ,'briefing_type'=>$briefing_type,'description'=>$description,'eby' => $_SESSION["userdata"]["user_id"] , 'eat'=> date("Y-m-d H:i:s"));
		  if(!empty($_FILES['link']['name'])){
			$ext = pathinfo($_FILES["link"]["name"] , PATHINFO_EXTENSION);
			$update_arr["feedback_link"] = $ext;
		  }
	      $r = $this->users_m->update_feedback($id , $update_arr);
	      if($r > 0)
	       {
				
                if(!file_exists('web_components/pdf/feedback')) {
                    mkdir('web_components/pdf/feedback', 0777, true);
                }
                if(!empty($_FILES['link']['name'])){
					
                  $ext = pathinfo($_FILES["link"]["name"] , PATHINFO_EXTENSION);
                  $upload_paths = 'web_components/pdf/feedback/'.$id.'.'.$ext; 
                  if (!move_uploaded_file($_FILES["link"]["tmp_name"], $upload_paths)) {
                      $data['response'] = FALSE;
                      $data['message'] = 'Something Worng';
                  }
                  else {
                    $data['response'] = TRUE;
                    $data['message'] = 'Quality Updated successfully';
                  }
                }
	          $data["response"] = TRUE;
	          $data["message"] = "Quality Updated successfully.";
	        
	       }
	      else
	       {
	        $data["response"] = FALSE;
	        $data["message"] = "Some error occured. Please try again later.";
	       }
	     }
	   }
	  echo json_encode($data);
	}
	
	public function change_password($page = "change_password")
	 {
	  if(!file_exists(APPPATH.'views/app/pages/'.$page.'.php'))
           {
	    show_404();
	   }
	  
	  $data = array();
	  
	  $data["title"] = "Change Password";
	  
	  $this->load->view('app/templates/header' , $data);
	  $this->load->view('app/templates/side_panel' , $data);
	  $this->load->view('app/pages/'.$page , $data);
	  $this->load->view('app/templates/footer' , $data);
	 }
	
	 public function get_user_details_by_id()
	 {
	  if(!$this->is_admin()) { return $this->deny_json(); }
	  $data = array();
	  if($this->input->is_ajax_request())
	   {
	    $user_id = (isset($_POST["user_id"]))?$this->input->post("user_id" , TRUE):0;
	    if($user_id == 0 || trim($user_id) == "")
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "Invalid User ID.";
	     }
	    else
	     {
	      $this->load->model("users_m");
	      $r = $this->users_m->get_user_details_by_id($user_id);
	      if($r != FALSE)
	       {
	        $data["response"] = TRUE;
	        $data["message"] = count($r)." Record Found.";
	        $data["total_record"] = count($r);
	        $data["all_record"] = $r;
	       }
	      else
	       {
	        $data["response"] = FALSE;
	        $data["message"] = "No Record Found.";
	       }
	     }
	   }
	  echo json_encode($data);
	 }
	
	public function validate_change_password()
	 {
	  $data = array();
	  if($this->input->is_ajax_request())
	   {
	    $user_id = (isset($_POST["user_id"]))?$this->input->post("user_id" , TRUE):0;
	    $old_password = (isset($_POST["old_password"]))?$this->input->post("old_password" , TRUE):"";
	    $new_password = (isset($_POST["new_password"]))?$this->input->post("new_password" , TRUE):"";
	    $renew_password = (isset($_POST["renew_password"]))?$this->input->post("renew_password" , TRUE):"";
	    if($user_id == 0 || trim($user_id) == "")
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "Invalid User-ID.";
	     }
	    else if(trim($old_password) == "")
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "Enter your old password.";
	     }
	    else if(trim($new_password) == "")
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "Enter your new password.";
	     }
	    else if(trim($renew_password) == "")
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "Enter again your new password.";
	     }
	    else if(trim($new_password) != trim($renew_password))
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "Password not matched.";
	     }
	    else if(trim($old_password) == trim($renew_password))
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "Old Password and new password should not be same.";
	     }
	    else
	     {
	      $this->load->model("users_m");
	      $r = $this->users_m->get_user_details_by_id($user_id);
	      if($r != FALSE)
	       {
	        if($r[0]->user_password == $old_password)
	         {
	          $update_arr = array("user_password" => $renew_password , "eby" => $_SESSION["userdata"]["user_id"] , "eat" => date("Y-m-d H:i:s"));
	          $r = $this->users_m->update_user_details($user_id , $update_arr);
	          if($r > 0)
	           {
	            unset($_SESSION["userdata"]);
	            $data["response"] = TRUE;
	            $data["message"] = "Password changed successfully.";
	           }
	          else
	           {
	            $data["response"] = FALSE;
	            $data["message"] = "Some error occured. Please try again later.";
	           }
	         }
	        else
	         {
	          $data["response"] = FALSE;
	          $data["message"] = "Invalid Old Password.";
	         }
	       }
	      else
	       {
	        $data["response"] = FALSE;
	        $data["message"] = "User Not Found.";
	       }
	     }
	   }
	  echo json_encode($data);
	 }
	
	
	public function edit_profile($page = "edit_profile")
	 {
	  if(!file_exists(APPPATH.'views/app/pages/'.$page.'.php'))
           {
	    show_404();
	   }
	  
	  $data = array();
	  
	  $data["title"] = "Edit Profile";
	  
	  $this->load->view('app/templates/header' , $data);
	  $this->load->view('app/templates/side_panel' , $data);
	  $this->load->view('app/pages/'.$page , $data);
	  $this->load->view('app/templates/footer' , $data);
	 }
	
	
	public function validate_edit_profile()
	 {
	  $data = array();
	  if($this->input->is_ajax_request())
	   {
	    $user_id = (isset($_POST["user_id"]))?$this->input->post("user_id" , TRUE):0;
	    $user_name = (isset($_POST["user_name"]))?$this->input->post("user_name" , TRUE):"";
	    $user_email = (isset($_POST["user_email"]))?$this->input->post("user_email" , TRUE):"";
	    $user_mobile = (isset($_POST["user_mobile"]))?$this->input->post("user_mobile" , TRUE):"";
	    $user_address = (isset($_POST["user_address"]))?$this->input->post("user_address" , TRUE):"";
	    if($user_id == 0 || trim($user_id) == "")
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "Invalid User-ID.";
	     }
	    else if(trim($user_name) == "")
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "Enter your full name.";
	     }
	    else if(trim($user_email) == "" || filter_var($user_email , FILTER_VALIDATE_EMAIL) == FALSE)
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "Enter your valid email-id.";
	     }
	    else if(trim($user_mobile) == "" || strlen($user_mobile) != 10)
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "Enter your valid 10 digit mobile no.";
	     }
	    else if(trim($user_address) == "")
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "Enter your complete address.";
	     }
	    else
	     {
	      $this->load->model("users_m");
	      $r = $this->users_m->check_user_email_exists($user_id , $user_email);
	      if($r == FALSE)
	       {
	        $update_arr = array("user_name" => $user_name , "user_email" => $user_email , "user_mobile" => $user_mobile , "user_address" => $user_address , "eby" => $_SESSION["userdata"]["user_id"] , "eat" => date("Y-m-d H:i:s"));
	        $r = $this->users_m->update_user_details($user_id , $update_arr);
	        if($r > 0)
	         {
	          $_SESSION["userdata"]["user_name"] = $user_name;
	          $_SESSION["userdata"]["user_email"] = $user_email;
	          $_SESSION["userdata"]["user_mobile"] = $user_mobile;
	          $_SESSION["userdata"]["user_address"] = $user_address;
	          
	          $data["response"] = TRUE;
	          $data["message"] = "Profile updated successfully.";
	         }
	        else
	         {
	          $data["response"] = FALSE;
	          $data["message"] = "Some error occured. Please try again later.";
	         }
	       }
	      else
	       {
	        $data["response"] = FALSE;
	        $data["message"] = "This email-id is already exists. Try with other email-id.";
	       }
	     }
	   }
	  echo json_encode($data);
	 }

	 public function save_audit()
	 {
		
		if($_SERVER['REQUEST_METHOD']=='POST')
		{
			
			$upload_status =  $this->uploadDoc();
			
			if($upload_status!=false)
			{
				$inputFileName = 'assets/uploads/imports/'.$upload_status;
				$inputTileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);
				$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputTileType);
				$spreadsheet = $reader->load($inputFileName);
				$sheet = $spreadsheet->getSheet(0);
				
				$count_Rows = 0;
				foreach($sheet->getRowIterator() as $row)
				{
					$employee_name = $spreadsheet->getActiveSheet()->getCell('A'.$row->getRowIndex());
					$msd_id = $spreadsheet->getActiveSheet()->getCell('B'.$row->getRowIndex());
					$call_date = $spreadsheet->getActiveSheet()->getCell('C'.$row->getRowIndex());
					$audit_date	 = $spreadsheet->getActiveSheet()->getCell('D'.$row->getRowIndex());
					$tenured = $spreadsheet->getActiveSheet()->getCell('E'.$row->getRowIndex());
					$qa = $spreadsheet->getActiveSheet()->getCell('F'.$row->getRowIndex());
					$call_type = $spreadsheet->getActiveSheet()->getCell('G'.$row->getRowIndex());
					$month = $spreadsheet->getActiveSheet()->getCell('H'.$row->getRowIndex());
					$week = $spreadsheet->getActiveSheet()->getCell('I'.$row->getRowIndex());
					$team_leader = $spreadsheet->getActiveSheet()->getCell('J'.$row->getRowIndex());
					$manager = $spreadsheet->getActiveSheet()->getCell('K'.$row->getRowIndex());
					$ticket_id = $spreadsheet->getActiveSheet()->getCell('L'.$row->getRowIndex());
					$caller_no = $spreadsheet->getActiveSheet()->getCell('M'.$row->getRowIndex());
					$audit_type = $spreadsheet->getActiveSheet()->getCell('N'.$row->getRowIndex());
					$call_opening = $spreadsheet->getActiveSheet()->getCell('O'.$row->getRowIndex());
					$call_closing = $spreadsheet->getActiveSheet()->getCell('P'.$row->getRowIndex());
					$ivr_transfer = $spreadsheet->getActiveSheet()->getCell('Q'.$row->getRowIndex());
					$hold_dead_air = $spreadsheet->getActiveSheet()->getCell('R'.$row->getRowIndex());
					$ros_confident = $spreadsheet->getActiveSheet()->getCell('S'.$row->getRowIndex());
					$tone_pace = $spreadsheet->getActiveSheet()->getCell('T'.$row->getRowIndex());
					$language_control = $spreadsheet->getActiveSheet()->getCell('U'.$row->getRowIndex());
					$apology_empathy = $spreadsheet->getActiveSheet()->getCell('V'.$row->getRowIndex());
					$active_listening = $spreadsheet->getActiveSheet()->getCell('W'.$row->getRowIndex());
					$customer_orientation = $spreadsheet->getActiveSheet()->getCell('X'.$row->getRowIndex());
					$fact_finding = $spreadsheet->getActiveSheet()->getCell('Y'.$row->getRowIndex());
					$took_ownership = $spreadsheet->getActiveSheet()->getCell('Z'.$row->getRowIndex());
					$interruption = $spreadsheet->getActiveSheet()->getCell('AA'.$row->getRowIndex());
					$accurate = $spreadsheet->getActiveSheet()->getCell('AB'.$row->getRowIndex());
					$complete_info = $spreadsheet->getActiveSheet()->getCell('AC'.$row->getRowIndex());
					$documentation = $spreadsheet->getActiveSheet()->getCell('AD'.$row->getRowIndex());
					$call_avoidance = $spreadsheet->getActiveSheet()->getCell('AE'.$row->getRowIndex());
					$unprofessional_behavior = $spreadsheet->getActiveSheet()->getCell('AF'.$row->getRowIndex());
					$disparaging_gem = $spreadsheet->getActiveSheet()->getCell('AG'.$row->getRowIndex());
					$point_obtain = $spreadsheet->getActiveSheet()->getCell('AH'.$row->getRowIndex());
					$total_opportunities = $spreadsheet->getActiveSheet()->getCell('AI'.$row->getRowIndex());
					$no = $spreadsheet->getActiveSheet()->getCell('AJ'.$row->getRowIndex());
					$accuracy_score = $spreadsheet->getActiveSheet()->getCell('AK'.$row->getRowIndex());
					$issue_type = $spreadsheet->getActiveSheet()->getCell('AL'.$row->getRowIndex());
					$summary = $spreadsheet->getActiveSheet()->getCell('AM'.$row->getRowIndex());
					$fatal_reason = $spreadsheet->getActiveSheet()->getCell('AN'.$row->getRowIndex());
					$fatal_sub_reason = $spreadsheet->getActiveSheet()->getCell('AO'.$row->getRowIndex());
					$duration = $spreadsheet->getActiveSheet()->getCell('AP'.$row->getRowIndex());
					$aht = $spreadsheet->getActiveSheet()->getCell('AQ'.$row->getRowIndex());
					$aht_acpt = $spreadsheet->getActiveSheet()->getCell('AR'.$row->getRowIndex());
					$repeat = $spreadsheet->getActiveSheet()->getCell('AS'.$row->getRowIndex());
					$repeat_apt = $spreadsheet->getActiveSheet()->getCell('AT'.$row->getRowIndex());
					$ticket_resolution_script = $spreadsheet->getActiveSheet()->getCell('AU'.$row->getRowIndex());

					$data = array(
						'employee_name'=> $employee_name,
						'msd_id'=> $msd_id,
						'call_date'=> $call_date,
						'audit_date'=> $audit_date,
						'tenured'=> $tenured,
						'qa'=> $qa,
						'call_type'=> $call_type,
						'month'=> $month,
						'week'=> $week,
						'team_leader'=> $team_leader,
						'manager'=> $manager,
						'ticket_id'=> $ticket_id,
						'caller_no'=> $caller_no,
						'audit_type'=> $audit_type,
						'call_opening'=> $call_opening,
						'call_closing'=> $call_closing,
						'ivr_transfer'=> $ivr_transfer,
						'hold_dead_air'=> $hold_dead_air,
						'ros_confident'=> $ros_confident,
						'tone_pace'=> $tone_pace,
						'language_control'=> $language_control,
						'apology_empathy'=> $apology_empathy,
						'active_listening'=> $active_listening,
						'customer_orientation'=> $customer_orientation,
						'fact_finding'=> $fact_finding,
						'took_ownership'=> $took_ownership,
						'interruption'=> $interruption,
						'accurate'=> $accurate,
						'complete_info'=> $complete_info,
						'documentation'=> $documentation,
						'call_avoidance'=> $call_avoidance,
						'unprofessional_behavior'=> $unprofessional_behavior,
						'disparaging_gem'=> $disparaging_gem,
						'point_obtain'=> $point_obtain,
						'total_opportunities'=> $total_opportunities,
						'no'=> $no,
						'accuracy_score'=> $accuracy_score,
						'issue_type'=> $issue_type,
						'summary'=> $summary,
						'fatal_reason'=> $fatal_reason,
						'fatal_sub_reason'=> $fatal_sub_reason,
						'duration'=> $duration,
						'aht'=> $aht,
						'aht_acpt'=> $aht_acpt,
						'repeat'=> $repeat,
						'repeat_apt'=> $repeat_apt,
						'ticket_resolution_script'=> $ticket_resolution_script,
					);
					$this->load->model("users_m");
					$this->users_m->insert($data);
					
					$count_Rows++;
				}
				$this->session->set_flashdata('success','Successfulyy Data Imported');
				redirect(base_url()."app/users/add_feedback");
			}
			else
			{
				$this->session->set_flashdata('error','File is not uploaded');
				redirect(base_url()."app/users/add_feedback");
			}
		}
		
	 }





	/* public function save_adviser()
	 {
		if($_SERVER['REQUEST_METHOD']=='POST')
		{	
			$upload_status =  $this->uploadDoc();
			if($upload_status!=false)
			{
				$inputFileName = 'assets/uploads/imports/'.$upload_status;
				$inputTileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);
				$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputTileType);
				$spreadsheet = $reader->load($inputFileName);
				$sheet = $spreadsheet->getSheet(0);
				
				$count_Rows = 0;
				foreach($sheet->getRowIterator() as $row)
				{
					$employee_name = $spreadsheet->getActiveSheet()->getCell('A'.$row->getRowIndex());
					$msd_id = $spreadsheet->getActiveSheet()->getCell('B'.$row->getRowIndex());

					$data = array(
						'user_name'=> $employee_name,
						'msd_id'=> $msd_id,
						'role_id'=> 2,
						'eby'=>1,
					);
					$this->load->model("users_m");
					$this->users_m->insert_adviser($data);
					
					$count_Rows++;
				}
				$this->session->set_flashdata('success','Successfulyy Data Imported');
				redirect(base_url()."app/users/list_of_adviser");
			}
			else
			{
				$this->session->set_flashdata('error','File is not uploaded');
				redirect(base_url()."app/users/list_of_adviser");
			}
		}
		
	 }

	*/
	 public function uploadDoc()
	 {
		 $uploadPath = 'assets/uploads/imports/';
		 if(!is_dir($uploadPath))
		 {
			 mkdir($uploadPath,0777,TRUE); // FOR CREATING DIRECTORY IF ITS NOT EXIST
		 }
 
		 $config['upload_path']=$uploadPath;
		 $config['allowed_types'] = 'csv|xlsx|xls';
		 $config['max_size'] = 1000000;
		 $this->load->library('upload',$config);
		 $this->upload->initialize($config);
		 if($this->upload->do_upload('upload_excel'))
		 {
			 $fileData = $this->upload->data();
			 return $fileData['file_name'];
		 }
		 else
		 {
			 return false;
		 }
	 }

	 public function add_adviser($page = "add_adviser")
	 {
	  if(!file_exists(APPPATH.'views/app/pages/'.$page.'.php'))
           {
	    show_404();
	   }
	  
	  $data = array();
	  
	  $data["title"] = "Roles";
	  
	  $this->load->view('app/templates/header' , $data);
	  $this->load->view('app/templates/side_panel' , $data);
	  $this->load->view('app/pages/'.$page , $data);
	  $this->load->view('app/templates/footer' , $data);
	 }
	public function roles($page = "roles")
	 {
	  $this->require_admin();
	  if(!file_exists(APPPATH.'views/app/pages/'.$page.'.php'))
		{
	    	show_404();
	   	}
	  
	  $data = array();
	  
	  $data["title"] = "Roles";
	  
	  $this->load->view('app/templates/header' , $data);
	  $this->load->view('app/templates/side_panel' , $data);
	  $this->load->view('app/pages/'.$page , $data);
	  $this->load->view('app/templates/footer' , $data);
	 }
	 public function monthy_audits($page = "monthly_audit")
	 {
	  if(!file_exists(APPPATH.'views/app/pages/'.$page.'.php'))
           {
	    show_404();
	   }
	  $data = array();
	  
	  $data["title"] = "Monthly Audit Of Adviser";
	  $user_id = $_SESSION["userdata"]["user_id"];
      $role_id = $_SESSION["userdata"]["role_id"];
     
      $this->load->model("users_m");
      $get = $this->users_m->get_msd_id($user_id,$role_id);
      if($get != false){
        $msd_id  = $get[0]->msd_id;
        $quality = $this->users_m->get_adviser_quality($msd_id);
        $fatal = $this->users_m->get_fatal_count($msd_id);
		$left = 0;
$left1 = 0;

		if($fatal != False){
			foreach($fatal as $fa){
				if($fa->action_value != 0){
					$left =	1 + $left; 
				}
if($fa->accept_status != 0){
					$left1 =1 + $left1; 
				}

			}
			//echo "<pre>";print_r($left);die;
		}
        if($fatal != false){
          $fatal_count = count($fatal);
        }else{
          $fatal_count = 0;
        }
        if($quality != false){
          $ss = 0;
          $ss1 = 0;
          foreach($quality as $qu){
            $ss = $qu->point_obtain + $ss;
            $ss1 = $qu->total_opportunities + $ss1;
          }
          $qua = ($ss/$ss1)*100;
            
            $data['adviser_quality'] = round($qua, 2);
            $data['total_audit'] = count($quality);
            $data['fatal_count'] = $fatal_count;
			$data['left'] = ($fatal_count-$left);
            $data['feedback_left'] = ($fatal_count-$left1);
        }
		else{
			$data['adviser_quality'] = 0;
            $data['total_audit'] = 0;
            $data['fatal_count'] = 0;
			$data['left'] = 0;
            $data['feedback_left'] = 0;

		}
      }
		$this->load->view('app/templates/header' , $data);
		$this->load->view('app/templates/side_panel' , $data);
		$this->load->view('app/pages/'.$page , $data);
		$this->load->view('app/templates/footer' , $data);
	 }

	 public function list_of_adviser($page = "list_of_adviser")
	 {
	  if(!file_exists(APPPATH.'views/app/pages/'.$page.'.php'))
           {
	    show_404();
	   }
	  $data = array();
	  
	  $data["title"] = "List Of Adviser";
		$this->load->view('app/templates/header' , $data);
		$this->load->view('app/templates/side_panel' , $data);
		$this->load->view('app/pages/'.$page , $data);
		$this->load->view('app/templates/footer' , $data);
	 }
	
	
	public function add_user_role()
	 {
	  if(!$this->is_admin()) { return $this->deny_json(); }
	  $data = array();
	  if($this->input->is_ajax_request())
	   {
	    $role_name = (isset($_POST["role_name"]))?$this->input->post("role_name" , TRUE):"";
	    //print_r($role_name);
	//die;
	    if(trim($role_name) == "")
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "Enter user role name.";
	     }
	    else
	     {
	      $this->load->model("users_m");
	      $r = $this->users_m->check_user_role_exists($role_name);
	      if($r == FALSE)
	       {
	        $insert_arr = array("role_name" => $role_name , "eby" => $_SESSION["userdata"]["user_id"] , "eat" => date("Y-m-d H:i:s"));
	        $r = $this->users_m->add_user_role($insert_arr);
	        if($r > 0)
	         {
	          $data["response"] = TRUE;
	          $data["message"] = "User role added successfully.";
	         }
	        else
	         {
	          $data["response"] = FALSE;
	          $data["message"] = "Some error occured. Please try again later.";
	         }
	       }
	      else
	       {
	        $data["response"] = FALSE;
	        $data["message"] = "This role already exists.";
	       }
	     }
	   }
	  echo json_encode($data);
	 }

	public function edit_user_role()
	 {
	  if(!$this->is_admin()) { return $this->deny_json(); }
	  $data = array();
	  if($this->input->is_ajax_request())
	   {
	    $role_id = (isset($_POST["role_id"]))?$this->input->post("role_id" , TRUE):0;
	    $role_name = (isset($_POST["role_name"]))?$this->input->post("role_name" , TRUE):"";
	    if($role_id == 0 || trim($role_id) == "")
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "Invalid Role ID.";
	     }
	    else if(trim($role_name) == "")
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "Enter user role name.";
	     }
	    else
	     {
	      $this->load->model("users_m");
	      $update_arr = array("role_name" => $role_name , "eby" => $_SESSION["userdata"]["user_id"] , "eat" => date("Y-m-d H:i:s"));
	      $r = $this->users_m->update_user_role($role_id , $update_arr);
	      if($r > 0)
	       {
	        $data["response"] = TRUE;
	        $data["message"] = "User role updated successfully.";
	       }
	      else
	       {
	        $data["response"] = FALSE;
	        $data["message"] = "No changes saved.";
	       }
	     }
	   }
	  echo json_encode($data);
	 }
	
	
	public function get_all_user_roles()
	 {
	  if(!$this->is_admin()) { return $this->deny_json(); }
	  $data = array();
	  if($this->input->is_ajax_request())
	   {
	    $this->load->model("users_m");
	    $r = $this->users_m->get_all_user_roles();
	    if($r != FALSE)
	     {
	      $data["response"] = TRUE;
	      $data["message"] = count($r)." Record Found.";
	      $data["total_record"] = count($r);
	      $data["all_record"] = $r;
	     }
	    else
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "No Record Found.";
	     }
	   }
	  echo json_encode($data);
	 }
	 
	 
	 
	 public function get_monthly_audit()
	 {
	  $data = array();
	  if($this->input->is_ajax_request())
	   {
			$this->load->model("users_m");
			$user_id = $_SESSION["userdata"]["user_id"];
			$role_id = $_SESSION["userdata"]["role_id"];
			$get = $this->users_m->get_msd_id($user_id,$role_id);
			$msd_id  = $get[0]->msd_id;
	        $r = $this->users_m->get_monthly_audit($msd_id);
	        if($r != FALSE)
	        {
			$arr = array();
		 foreach($r as $rr){
			$aa = array();
			$aa['caller_no'] = $rr->caller_no;
			$aa['ticket_id'] = $rr->ticket_id;
			$call_date = date('d-m-Y H:i:s', strtotime($rr->call_date));
			$audit_date = date('d-m-Y', strtotime($rr->audit_date));
			if($rr->fatal_reason == "NA"){
				$fatal_reason = "";
			}else{
				$fatal_reason = $rr->fatal_reason;
			}
			if($rr->fatal_sub_reason == "NA"){
				$fatal_sub_reason = "";
			}else{
				$fatal_sub_reason = $rr->fatal_sub_reason;
			}
				$aa['call_date'] = $call_date;
				$aa['audit_date'] = $audit_date;
				$aa['issue_type'] = $rr->issue_type;
				$aa['accuracy_score'] = $rr->accuracy_score;
				$aa['accept_status'] = $rr->accept_status;

				$aa['fatal_sub_reason'] = $fatal_sub_reason;
				$aa['fatal_reason'] = $fatal_reason;
				$aa['summary'] = $rr->summary;
				$aa['no'] = $rr->no;
				$aa['audit_emp_id'] = $rr->audit_emp_id;
				$aa['msd_id'] = $msd_id;
				$aa['action_value'] = $rr->action_value;
			if($rr->action_value != 0||$rr->action_value != "0" ){
				$res = $this->users_m->get_adviser_action($rr->audit_emp_id);
				if($res != FALSE){
					$aa['comment'] = $res[0]->comment;
				}
			}
			$arr[] = $aa;

		 }
	      $data["response"] = TRUE;
	      $data["message"] = count($arr)." Record Found.";
	      $data["total_record"] = count($arr);
	      $data["all_record"] = $arr;
	     }
	    else
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "No Record Found.";
	     }
	   }
	  echo json_encode($data);
	 }
	
	
	
	 public function update_action()
	 {
		
	  $data = array();
	  if($this->input->is_ajax_request())
	   {
	    $audit_emp_id = (isset($_POST["audit_emp_id"]))?$this->input->post("audit_emp_id" , TRUE):"";
		$action = (isset($_POST["action"]))?$this->input->post("action" , TRUE):"";
		$msd_id = (isset($_POST["msd_id"]))?$this->input->post("msd_id" , TRUE):"";
		$comment = (isset($_POST["comment"]))?$this->input->post("comment" , TRUE):"";
	      $this->load->model("users_m");
	        $insert_arr = array("audit_emp_id" => $audit_emp_id ,"action_value" => $action ,"comment" => $comment ,"msd_id" => $msd_id , "eat" => date("Y-m-d H:i:s"));
	        $r = $this->users_m->add_action($insert_arr);
	        if($r > 0)
	         {
				$update_arr = array("action_value" =>$action , "eat" => date("Y-m-d H:i:s"));
				$rs = $this->users_m->update_action($update_arr,$audit_emp_id);
				if($rs > 0){
					$data["response"] = TRUE;
				}else{
					$data["response"] = FALSE;
					$data["message"] = "Some error occured. Please try again later.";
				}
			}
	          
		
	       }
		   echo json_encode($data);
	     }
		 
		 
		 
	 public function update_call_acceptance()
  	{
    $data = array();
    if($this->input->is_ajax_request())
    {
      $audit_emp_id = (isset($_POST["audit_emp_id"]))?$this->input->post("audit_emp_id" , TRUE):"";
      $action = (isset($_POST["action"]))?$this->input->post("action" , TRUE):"";
      $msd_id = (isset($_POST["msd_id"]))?$this->input->post("msd_id" , TRUE):"";
      $this->load->model("users_m");
      $update_arr = array("accept_status" => $action  , "eat" => date("Y-m-d H:i:s"));
//print_r($update_arr);die;

      $r = $this->users_m->update_action($update_arr, $audit_emp_id);
      if($r > 0)
      {
          $data["response"] = TRUE;
      }else{
          $data["response"] = FALSE;

      }

    }else{
      $data["response"] = FALSE;
    }
    echo json_encode($data);
  }  
  
  
  

	public function get_adviser_data_by_msd_id()
	{
		$data = array();
		if($this->input->is_ajax_request())
		{
			$msd_id = (isset($_POST["msd_id"]))?$this->input->post("msd_id" , TRUE):"";
			$this->load->model("users_m");
			$r = $this->users_m->get_monthly_audit($msd_id);
//echo"<pre>";print_r($r);die;
			if($r != FALSE)
			{
				$arr = array();
				foreach($r as $rr){
					$aa = array();
					$call_date = date('d-m-Y H:i:s', strtotime($rr->call_date));
					$audit_date = date('d-m-Y', strtotime($rr->audit_date));
					if($rr->fatal_reason == "NA"){
						$fatal_reason = "";
					}else{
						$fatal_reason = $rr->fatal_reason;
					}
					if($rr->fatal_sub_reason == "NA"){
						$fatal_sub_reason = "";
					}else{
						$fatal_sub_reason = $rr->fatal_sub_reason;
					}
					$aa['call_date'] = $call_date;
                                        $aa['accuracy_score'] = $rr->accuracy_score;
					$aa['audit_date'] = $audit_date;
					$aa['issue_type'] = $rr->issue_type;
					$aa['fatal_sub_reason'] = $fatal_sub_reason;
					$aa['fatal_reason'] = $fatal_reason;
					$aa['summary'] = $rr->summary;
					$aa['no'] = $rr->no;
					$aa['audit_emp_id'] = $rr->audit_emp_id;
					$aa['msd_id'] = $msd_id;
					$aa['action_value'] = $rr->action_value;
					if($rr->action_value != 0||$rr->action_value != "0" ){
						$res = $this->users_m->get_adviser_action($rr->audit_emp_id);
						if($res != FALSE){
							$aa['comment'] = $res[0]->comment;
						}
					}
					$arr[] = $aa;

				}
				$data["response"] = TRUE;
				$data["message"] = count($arr)." Record Found.";
				$data["total_record"] = count($arr);
				$data["all_record"] = $arr;
			}
			else
			{
				$data["response"] = FALSE;
				$data["message"] = "No Record Found.";
			}
		}
		echo json_encode($data);
	}
		
		
		

	 public function delete_user()
	 {
	  if(!$this->is_admin()) { return $this->deny_json(); }
	  $data = array();
	  if($this->input->is_ajax_request())
	   {
	    $user_id = (isset($_POST["user_id"]))?$this->input->post("user_id" , TRUE):0;
	    if($user_id == 0 || trim($user_id) == "")
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "Invalid User ID.";
	     }
	    else
	     {
	      $this->load->model("users_m");
	      $update_arr = array("login_status" => 0 , "status" => 0 , "eby" => $_SESSION["userdata"]["user_id"] , "eat" => date("Y-m-d H:i:s"));
	      $r = $this->users_m->update_user($user_id , $update_arr);
	      if($r > 0)
	       {
	        $data["response"] = TRUE;
	        $data["message"] = "User deleted successfully.";
	       }
	      else
	       {
	        $data["response"] = FALSE;
	        $data["message"] = "Some error occured. Please try again later.";
	       }
	     }
	   }
	  echo json_encode($data);
	 }
	 
	 
	 
	 
	
	public function delete_user_role()
	{
		if(!$this->is_admin()) { return $this->deny_json(); }
		$data = array();
		if($this->input->is_ajax_request())
		{
			$role_id = (isset($_POST["role_id"]))?$this->input->post("role_id" , TRUE):0;
			if($role_id == 0 || trim($role_id) == "")
			{
				$data["response"] = FALSE;
				$data["message"] = "Invalid Role ID.";
			}
			else
			{
				$this->load->model("users_m");

				$update_arr = array("status" => 0 , "eby" => $_SESSION["userdata"]["user_id"] , "eat" => date("Y-m-d H:i:s"));
				$r = $this->users_m->update_user_role($role_id , $update_arr);
				if($r > 0)
				{
					$data["response"] = TRUE;
					$data["message"] = "User role deleted successfully.";
				}
				else
				{
					$data["response"] = FALSE;
					$data["message"] = "Some error occured. Please try again later.";
				}
			}
		}
		echo json_encode($data);
	}
	
	
	
	
	
public function add_new_audit($page = "add_new_feedback")
{
	  if(!file_exists(APPPATH.'views/app/pages/'.$page.'.php'))
           {
	    show_404();
	   }
	  
	  $data = array();
	  
	  $data["title"] = "Add Audit";
	  
	  $this->load->view('app/templates/header' , $data);
	  $this->load->view('app/templates/side_panel' , $data);
	  $this->load->view('app/pages/'.$page , $data);
	  $this->load->view('app/templates/footer' , $data);
}





	public function adviser_feedback($page = "adviser_feedback")
	 {
	  if(!file_exists(APPPATH.'views/app/pages/'.$page.'.php'))
           {
	    show_404();
	   }
	  
	  $data = array();
	  $this->load->model("users_m");
	   $data["users"] = $this->users_m->get_all_users();

	  $data["title"] = "Adviser's Feedback";	  	  
	  $this->load->view('app/templates/header' , $data);
	  $this->load->view('app/templates/side_panel' , $data);
	  $this->load->view('app/pages/'.$page , $data);
	  $this->load->view('app/templates/footer' , $data);
	 }
	 
	 
	 
	 
	 

public function get_adviser_feedback(){
	$data = array();
	if($this->input->is_ajax_request())
	{	
		$start_date = (isset($_POST["start_date"]))?$this->input->post("start_date" , TRUE):"";
		$end_date = (isset($_POST["end_date"]))?$this->input->post("end_date" , TRUE):"";
		$msd_id = (isset($_POST["msd_id"]))?$this->input->post("msd_id" , TRUE):"";	   
		$this->load->model("users_m");
		$r = $this->users_m->get_adviser_feedback($start_date,$end_date,$msd_id);
		//echo "<pre>";print_r($r);die;
		if($r != FALSE)
		{
			$arr = array();
			foreach($r as $rr){
				$aa = array();
				$call_date = date('d-m-Y H:i:s', strtotime($rr->call_date));
				$audit_date = date('d-m-Y', strtotime($rr->audit_date));
				if($rr->fatal_reason == "NA"){
					$fatal_reason = "";
				}else{
					$fatal_reason = $rr->fatal_reason;
				}
				if($rr->fatal_sub_reason == "NA"){
					$fatal_sub_reason = "";
				}else{
					$fatal_sub_reason = $rr->fatal_sub_reason;
				}
				$aa['call_date'] = $call_date;
				$aa['accuracy_score'] = $rr->accuracy_score;
				$aa['audit_date'] = $audit_date;
				$aa['issue_type'] = $rr->issue_type;
				$aa['fatal_sub_reason'] = $fatal_sub_reason;
				$aa['fatal_reason'] = $fatal_reason;
				$aa['summary'] = $rr->summary;
				$aa['no'] = $rr->no;
				$aa['audit_emp_id'] = $rr->audit_emp_id;
				$aa['msd_id'] = $rr->msd_id;
				$aa['action_value'] = $rr->action_value;
                                $aa['accept_status'] = $rr->accept_status;

				if($rr->action_value != 0||$rr->action_value != "0" ){
					$res = $this->users_m->get_adviser_action($rr->audit_emp_id);
					if($res != FALSE){
						$aa['comment'] = $res[0]->comment;
						$aa['call_accepet_date'] = $res[0]->eat;

					}
				}else{
$aa['call_accepet_date'] = "";

				}
				$arr[] = $aa;

			}
			$data["response"] = TRUE;
			$data["message"] = count($arr)." Record Found.";
			$data["total_record"] = count($arr);
			$data["all_record"] = $arr;
		}
		else
		{
			$data["response"] = FALSE;
			$data["message"] = "No Record Found.";
		}

	}
	echo json_encode($data);
}






public function get_adviser_monthly_data(){


$msd_id = (isset($_POST["msd_id"]))?$this->input->post("msd_id" , TRUE):"";
$month = (isset($_POST["month"]))?$this->input->post("month" , TRUE):"";
 $current_year = date('Y');

//echo'<pre>';print_r($current_year);die;

$this->load->model("users_m");
	$quality = $this->users_m->get_adviser_quality_month($msd_id,$month,$current_year);
	//echo'<pre>';print_r($quality);die;
        $fatal = $this->users_m->get_fatal_count($msd_id,$month);
        if($fatal != false){
          $fatal_count = count($fatal);
        }else{
          $fatal_count = 0;
        }
        if($quality != false){
          $ss = 0;
          $ss1 = 0;
          foreach($quality as $qu){
            $ss = $qu->point_obtain + $ss;
            $ss1 = $qu->total_opportunities + $ss1;
          }
          $qua = ($ss/$ss1)*100;
            
            $data['adviser_quality'] = round($qua, 2);
            $data['total_audit'] = count($quality);
            $data['fatal_count'] = $fatal_count;
        }
        else{
          $data['adviser_quality'] = 0;
            $data['total_audit'] = 0;
            $data['fatal_count'] =0;
        }


     

echo json_encode($data);


}





public function export_adviser_performance() {
   
			@ini_set('memory_limit', '-1');
			@ini_set('max_execution_time', 0);
			$path = "uploads/";
          require_once APPPATH . "/third_party/PHPExcel.php";
          $config['upload_path'] = $path;
          $config['allowed_types'] = 'xlsx|xls|csv';
          $config['remove_spaces'] = TRUE;
          $this->load->library('excel', $config);
          $this->upload->initialize($config);            
          if (!$this->upload->do_upload('file')) {
              $error = array('error' => $this->upload->display_errors());
      
          } else {
              $data = array('upload_data' => $this->upload->data());
          }
          if(empty($error)){
            if (!empty($data['upload_data']['file_name'])) {
              $import_xls_file = $data['upload_data']['file_name'];
          } else {
              $import_xls_file = 0;
          }
    
     
        $inputFileName = $path . $import_xls_file; 
  
          try {
            //PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
              $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
              $objReader = PHPExcel_IOFactory::createReader($inputFileType);
              $objPHPExcel = $objReader->load($inputFileName);
              $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

              $flag = true;
              $i=0;


              foreach ($allDataInSheet as $value) {
                    if($i > 0 )
                    {
						
						
					$datemonth = $value['A'];
					$msdid = $value['B'];
					$name = $value['C'];
					$nocalls = $value['D'];
					$outboundcalls = $value['E'];
					$noemails = $value['F'];
					$aht = $value['G'];
					$talktime = $value['H'];
					$csaty = $value['I'];
					$csatn = $value['J'];
					$total = $value['K'];
					$repeatcall = $value['L'];
					$uniquecall = $value['M'];
					$rucall = $value['N'];
					$resolutionless = $value['O'];
					$resolutiontotal = $value['P'];
					$ticketcount = $value['Q'];
					$totalcount = $value['R'];

					$inserdata[$i]['datemonth'] = $datemonth;
					$inserdata[$i]['msdid'] = $msdid;
					$inserdata[$i]['name'] = $name;
					$inserdata[$i]['nocalls'] = $nocalls;
					$inserdata[$i]['outboundcalls'] = $outboundcalls;
					$inserdata[$i]['noemails'] = $noemails;
					$inserdata[$i]['aht'] = $aht;
					$inserdata[$i]['talktime'] = $talktime;
					$inserdata[$i]['csaty'] = $csaty;
					$inserdata[$i]['csatn'] = $csatn;
					$inserdata[$i]['total'] = $total;
					$inserdata[$i]['repeatcall'] = $repeatcall;
					$inserdata[$i]['uniquecall'] = $uniquecall;
					$inserdata[$i]['rucall'] = $rucall;
					$inserdata[$i]['resolutionless'] = $resolutionless;
					$inserdata[$i]['resolutiontotal'] = $resolutiontotal;
					$inserdata[$i]['ticketcount'] = $ticketcount;
					$inserdata[$i]['totalcount'] = $totalcount;
							
							
                      }
                        $i++;
              }        
			  
						$this->load->model('Users_m');
						//echo"<pre>";print_r($inserdata);die;
						$result = $this->Users_m->insert_performance("adviser_performance",$inserdata); 
        
               echo "<pre>"; 
              
              if($result){
                echo "Imported successfully";
              }else{
                echo "ERROR !";
              }             

        } catch (Exception $e) {
             die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME)
                      . '": ' .$e->getMessage());
          }
        }else{
            echo $error['error'];
          }
          
          
  
  $this->session->set_flashdata('message','File Data Insert Successfully');
        redirect(base_url()."index.php/app/users/performance");
   
   
}












public function get_my_performance(){


	$msd_id = (isset($_POST["msd_id"]))?$this->input->post("msd_id" , TRUE):"";
	$start_date = (isset($_POST["start_date"]))?$this->input->post("start_date" , TRUE):"";
	$end_date = (isset($_POST["end_date"]))?$this->input->post("end_date" , TRUE):"";

// echo'<pre>';print_r($_POST);die;

    $this->load->model("users_m");
	$perform = $this->users_m->get_my_performance($msd_id,$start_date,$end_date);
	
	 if($perform != false){
				$ss = 0;
				$ss1 = 0;

				$dd = 0;
				$dd1 = 0;

				$ee = 0;
				$ee1 = 0;

				$hh = 0;
				$hh1 = 0;

				$mm = 0;
				$mm1 = 0;

				$aa = 0;
				$ahtper_count = 0;
		  
		  
          foreach($perform as $qu){
				$ss = $qu->csaty + $ss;
				$ss1 = $qu->total + $ss1;

				$dd = $qu->uniquecall + $dd;
				$dd1 = $qu->rucall + $dd1;

				$ee = $qu->resolutionless + $ee;
				$ee1 = $qu->resolutiontotal + $ee1;

				$hh = $qu->ticketcount + $hh;
				$hh1 = $qu->totalcount + $hh1;

				$aa = $qu->nocalls + $aa;

				$ahtper = $this->users_m->ahtper($msd_id,$start_date,$end_date);
			
			  
            if($ahtper != false){
          $ahtper_count = count($ahtper);
            }else{
          $ahtper_count = 0;
            }
		
			
				$timeParts = explode(':', $qu->talktime);
				$talkTimeSeconds = ($timeParts[0] * 3600) + ($timeParts[1] * 60) + $timeParts[2];
				$mm += $talkTimeSeconds;

				// Accumulate number of calls
				$mm1 += $qu->nocalls;
				
          }
          $csat = ($ss/$ss1)*100;
		  
		  $fcr = ($dd/$dd1)*100;
		  
		  $resolution = ($ee/$ee1)*100;
		  
		   $reopen = ($hh1/$hh)*100;
		   
		    $avr = ($aa/$ahtper_count);
		   
		    // AHT in seconds
		   
		   $aht = ($mm1 != 0) ? ($mm / $mm1) : 0; 
		   $aht_hours = floor($aht / 3600);
			$aht_minutes = floor(($aht % 3600) / 60);
			$aht_seconds = $aht % 60;
			$aht_formatted = sprintf('%02d:%02d:%02d', $aht_hours, $aht_minutes, $aht_seconds);
			
			 // AHT in seconds
		  
		 // echo'<pre>';print_r($ss);die;
            
            $data['c_sat'] = round($csat, 2);
			 $data['fcr'] = round($fcr, 2);
			  $data['resolution'] = round($resolution, 2);
			   $data['reopen'] = round($reopen, 2);
			    $data['avr'] = round($avr);
			     $data['aht'] = $aht_formatted;
           // $data['total_audit'] = count($quality);
           // $data['fatal_count'] = $fatal_count;
        }
        else{
          $data['c_sat'] = 0;
		   $data['fcr'] = 0;
		    $data['resolution'] = 0;
            $data['reopen'] = 0;
			 $data['avr'] = 0;
			$data['aht'] = '00:00:00';
           // $data['fatal_count'] =0;
        }


echo json_encode($data);


}






	
}
