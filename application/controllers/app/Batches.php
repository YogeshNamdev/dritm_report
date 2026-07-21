<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class batches extends CI_Controller {

    public function __construct(){
        parent::__construct();
        if(!isset($_SESSION['userdata']) || $_SESSION['userdata']['user_logged_status'] != TRUE)
	   {
	    redirect('app/logout');
	   }
         $this->load->model('batches_model');
    }

    // list page
    public function index($page = "list"){
		$data = array();
		if(!file_exists(APPPATH.'views/batches/'.$page.'.php'))
		{
		show_404();
		}

		$data["session_list"] = $this->batches_model->get_all_session_list();
		$data["course_list"] = $this->batches_model->get_all_course_list();
		$data["title"] = "Master Batches";

		$this->load->view('app/templates/header' , $data);
		$this->load->view('app/templates/side_panel' , $data);
		$this->load->view('batches/'.$page , $data);
		$this->load->view('app/templates/footer' , $data);

    }

    // save course
    public function save(){
		$batch_name = trim($this->input->post('batch_name',true));
		$session_id = trim($this->input->post('session_id',true));
		$course_id = trim($this->input->post('course_id',true));
		$start_date = trim($this->input->post('start_date',true));
		$end_date = trim($this->input->post('end_date',true));
		if($batch_name == ''){
			echo json_encode([
				"response"=>FALSE,
				"message"=>"Batch name required"
			]);
			return;
		}else if($session_id == ''){
			echo json_encode([
				"response"=>FALSE,
				"message"=>"Invalid"
			]);
			return;
		}else if($course_id == ''){
			echo json_encode([
				"response"=>FALSE,
				"message"=>"Invalid"
			]);
			return;
		}else if($start_date == ''){
			echo json_encode([
				"response"=>FALSE,
				"message"=>"Batch Start is required"
			]);
			return;
		}else if($end_date == ''){
			echo json_encode([
				"response"=>FALSE,
				"message"=>"Batch End is required"
			]);
			return;
		}
		if($this->batches_model->exists($batch_name ,$session_id, $course_id,)){
			$data["response"] = FALSE;
			$data["message"] = "This Batch already exists.";
			echo json_encode($data);
			return;   
		}else{
            $this->batches_model->insert([
                'batch_name'=>$batch_name,
				'session_id'=>$session_id,
				'course_id'=>$course_id,
				'start_date'=>$start_date,
				'end_date'=>$end_date,
            ]);
            $data["response"] = TRUE;
            $data["message"] = "Batch Added.";
            echo json_encode($data);
        }
    }
    public function get_all_batches()
	 {
	  $data = array();
	  if($this->input->is_ajax_request())
	   {
	    
	    $r = $this->batches_model->get_all_batches();
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

public function get_Batch_details_by_id()
	 {
	  $data = array();
	  if($this->input->is_ajax_request())
	   {
	    $batch_id = (isset($_POST["batch_id"]))?$this->input->post("batch_id" , TRUE):0;
	    if($batch_id == 0 || trim($batch_id) == "")
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "Invalid Batch ID.";
	     }
	    else
	     {
	      
	      $r = $this->batches_model->get_Batch_details_by_id($batch_id);
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

     public function edit_batch_details()
	 {
	  $data = array();
	  if($this->input->is_ajax_request())
	   {
		$batch_id = trim($this->input->post('batch_id',true));
	    $batch_name = trim($this->input->post('batch_name',true));
		$session_id = trim($this->input->post('session_id',true));
		$course_id = trim($this->input->post('course_id',true));
		$start_date = trim($this->input->post('start_date',true));
		$end_date = trim($this->input->post('end_date',true));
		if($batch_name == ''){
			echo json_encode([
				"response"=>FALSE,
				"message"=>"Batch name required"
			]);
			return;
		}else if($session_id == ''){
			echo json_encode([
				"response"=>FALSE,
				"message"=>"Invalid"
			]);
			return;
		}else if($course_id == ''){
			echo json_encode([
				"response"=>FALSE,
				"message"=>"Invalid"
			]);
			return;
		}else if($start_date == ''){
			echo json_encode([
				"response"=>FALSE,
				"message"=>"Batch Start is required"
			]);
			return;
		}else if($end_date == ''){
			echo json_encode([
				"response"=>FALSE,
				"message"=>"Batch End is required"
			]);
			return;
		}
	    else
	     {
			if(!$this->batches_model->exists($batch_name,$session_id,$course_id,$batch_id))
			{
			
				$update_arr = array(
					'batch_name' => $batch_name,
					'session_id' => $session_id,
					'course_id'  => $course_id,
					'start_date' => $start_date,
					'end_date'   => $end_date
				);

				$r = $this->batches_model->update_batch($batch_id , $update_arr);

				if($r !== false)
				{
					$data["response"] = TRUE;
					$data["message"] = "Batch updated successfully.";
				}
				else
				{
					$data["response"] = FALSE;
					$data["message"] = "Some error occurred. Please try again later.";
				}
			}
			else
			{
				$data["response"] = FALSE;
				$data["message"] = "This batch already exists for this course and session.";
			}
	     }
	   }
	  echo json_encode($data);
	 }

	 public function delete_batch()
	 {
	  $data = array();
	  if($this->input->is_ajax_request())
	   {
	    $batch_id = (isset($_POST["batch_id"]))?$this->input->post("batch_id" , TRUE):0;
	    if($batch_id == 0 || trim($batch_id) == "")
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "Invalid Batch ID.";
	     }
	    else
	     {
	      
	      $update_arr = array( "status" => 0 );
	      $r = $this->batches_model->update_Batch($batch_id , $update_arr);
	      if($r > 0)
	       {
	        $data["response"] = TRUE;
	        $data["message"] = "Batch deleted successfully.";
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