<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sessions extends CI_Controller {

    public function __construct(){
        parent::__construct();
        if(!isset($_SESSION['userdata']) || $_SESSION['userdata']['user_logged_status'] != TRUE)
	   {
	    redirect('app/logout');
	   }
         $this->load->model('Sessions_model');
    }

    // list page
    public function index($page = "list"){
     $data = array();
    if(!file_exists(APPPATH.'views/sessions/'.$page.'.php'))
    {
    show_404();
    }
    

    $data["title"] = "Master Sessions";
	     
	  $this->load->view('app/templates/header' , $data);
	  $this->load->view('app/templates/side_panel' , $data);
	  $this->load->view('sessions/'.$page , $data);
	  $this->load->view('app/templates/footer' , $data);

    }

    // save course
    public function save(){

        $session_name = trim($this->input->post('session_name',true));

		
		if($session_name == ''){
			echo json_encode([
				"response"=>FALSE,
				"message"=>"Session name required"
			]);
			return;
		}
		if($this->Sessions_model->exists($session_name)){
			
			$data["response"] = FALSE;
			$data["message"] = "This Session already exists.";
			echo json_encode($data);
			return;   
		}else{
            $this->Sessions_model->insert([
                'session_name'=>$session_name 
            ]);
			
            $data["response"] = TRUE;
            $data["message"] = "Session Added.";
            echo json_encode($data);
        }

        
    }
    public function get_all_sessions()
	 {
	  $data = array();
	  if($this->input->is_ajax_request())
	   {
	    
	    $r = $this->Sessions_model->get_all_sessions();
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

public function get_session_details_by_id()
	 {
	  $data = array();
	  if($this->input->is_ajax_request())
	   {
	    $session_id = (isset($_POST["session_id"]))?$this->input->post("session_id" , TRUE):0;
	    if($session_id == 0 || trim($session_id) == "")
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "Invalid Session ID.";
	     }
	    else
	     {
	      
	      $r = $this->Sessions_model->get_session_details_by_id($session_id);
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

     public function edit_session_details()
	 {
	  $data = array();
	  if($this->input->is_ajax_request())
	   {
	    $session_id = (isset($_POST["session_id"]))?$this->input->post("session_id" , TRUE):0;
	    
	    $session_name = (isset($_POST["session_name"]))?$this->input->post("session_name" , TRUE):"";
	    if($session_id == 0 || trim($session_id) == "")
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "Invalid session-ID.";
	     }
	   
	    else if(trim($session_name) == "")
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "Enter your Session name.";
	     }
	    else
	     {
	      $r = $this->Sessions_model->exists($session_name);
	      if($r == FALSE)
	       {
	        $update_arr = array("session_name" => $session_name);
	        $r = $this->Sessions_model->update_session($session_id , $update_arr);
	        if($r > 0)
	         {
	          $data["response"] = TRUE;
	          $data["message"] = "Session updated successfully.";
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
	        $data["message"] = "This Session name is already exists. Try with other Course name.";
	       }
	     }
	   }
	  echo json_encode($data);
	 }

	 public function delete_sessions()
	 {
	  $data = array();
	  if($this->input->is_ajax_request())
	   {
	    $session_id = (isset($_POST["session_id"]))?$this->input->post("session_id" , TRUE):0;
	    if($session_id == 0 || trim($session_id) == "")
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "Invalid Session ID.";
	     }
	    else
	     {
	      
	      $update_arr = array( "status" => 0 );
	      $r = $this->Sessions_model->update_session($session_id , $update_arr);
	      if($r > 0)
	       {
	        $data["response"] = TRUE;
	        $data["message"] = "Session deleted successfully.";
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

}