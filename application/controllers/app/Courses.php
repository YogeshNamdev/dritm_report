<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Courses extends CI_Controller {

    public function __construct(){
        parent::__construct();
        if(!isset($_SESSION['userdata']) || $_SESSION['userdata']['user_logged_status'] != TRUE)
	   {
	    redirect('app/logout');
	   }
         $this->load->model('Course_model');
    }

    // list page
    public function index($page = "list"){
     $data = array();
    if(!file_exists(APPPATH.'views/courses/'.$page.'.php'))
    {
    show_404();
    }
    

    $data["title"] = "Master Course";
	     
	  $this->load->view('app/templates/header' , $data);
	  $this->load->view('app/templates/side_panel' , $data);
	  $this->load->view('courses/'.$page , $data);
	  $this->load->view('app/templates/footer' , $data);

    }

    // save course
    public function save(){

        $course_name=$this->input->post('course_name',true);
        $duration=$this->input->post('duration',true);
        $type=$this->input->post('type',true);

        if($this->Course_model->exists($course_name)){
            $data["response"] = FALSE;
	        $data["message"] = "This Course already exists.";
            echo json_encode($data);
        }else{
            $this->Course_model->insert([
                'course_name'=>$course_name,
                'duration'=>$this->input->post('duration'),
                'type'=>$this->input->post('type')
            ]);

            $data["response"] = TRUE;
            $data["message"] = "Course Added.";
            echo json_encode($data);
        }

        
    }
    public function get_all_courses()
	 {
	  $data = array();
	  if($this->input->is_ajax_request())
	   {
	    
	    $r = $this->Course_model->get_all_courses();
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

public function get_course_details_by_id()
	 {
	  $data = array();
	  if($this->input->is_ajax_request())
	   {
	    $course_id = (isset($_POST["course_id"]))?$this->input->post("course_id" , TRUE):0;
	    if($course_id == 0 || trim($course_id) == "")
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "Invalid Course ID.";
	     }
	    else
	     {
	      
	      $r = $this->Course_model->get_course_details_by_id($course_id);
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

     public function edit_course_details()
	 {
	  $data = array();
	  if($this->input->is_ajax_request())
	   {
	    $course_id = (isset($_POST["course_id"]))?$this->input->post("course_id" , TRUE):0;
	    $type = (isset($_POST["type"]))?$this->input->post("type" , TRUE):0;
	    $duration = (isset($_POST["duration"]))?$this->input->post("duration" , TRUE):"";
	    $course_name = (isset($_POST["course_name"]))?$this->input->post("course_name" , TRUE):"";
	    if($course_id == 0 || trim($course_id) == "")
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "Invalid User-ID.";
	     }
	   
	    else if(trim($course_name) == "")
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "Enter your course name.";
	     }
	    else if(trim($duration) == "")
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "Enter your duration.";
	     }else if(trim($type) == "")
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "Enter your type.";
	     }
	    else
	     {
	      $r = $this->Course_model->exists($course_name);
	      if($r == FALSE)
	       {
	        $update_arr = array("course_name" => $course_name , "duration" => $duration , "type" => $type);
	        $r = $this->Course_model->update_course($course_id , $update_arr);
	        if($r > 0)
	         {
	          $data["response"] = TRUE;
	          $data["message"] = "Course updated successfully.";
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
	        $data["message"] = "This Course name is already exists. Try with other Course name.";
	       }
	     }
	   }
	  echo json_encode($data);
	 }

	 public function delete_course()
	 {
	  $data = array();
	  if($this->input->is_ajax_request())
	   {
	    $course_id = (isset($_POST["course_id"]))?$this->input->post("course_id" , TRUE):0;
	    if($course_id == 0 || trim($course_id) == "")
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "Invalid Course ID.";
	     }
	    else
	     {
	      
	      $update_arr = array( "status" => 0 );
	      $r = $this->Course_model->update_course($course_id , $update_arr);
	      if($r > 0)
	       {
	        $data["response"] = TRUE;
	        $data["message"] = "Course deleted successfully.";
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