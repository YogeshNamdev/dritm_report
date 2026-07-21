<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fee_structure extends CI_Controller {

    public function __construct(){
        parent::__construct();
         if(!isset($_SESSION['userdata']) || $_SESSION['userdata']['user_logged_status'] != TRUE)
	   {
	    redirect('app/logout');
	   }
        $this->load->model('fee_types_model');
        $this->load->model('course_model');
        $this->load->model('fee_structure_model');
    }

    public function index($page = "list"){
		$data = array();
		if(!file_exists(APPPATH.'views/fee_structure/'.$page.'.php'))
		{
		show_404();
		}

		$data['courses']=$this->course_model->get_all_courses();
        $data['types']=$this->fee_types_model->get_all_fee_types();
		$data["title"] = "Master Fee Structure";
		$this->load->view('app/templates/header' , $data);
		$this->load->view('app/templates/side_panel' , $data);
		$this->load->view('fee_structure/'.$page , $data);
		$this->load->view('app/templates/footer' , $data);

    }
    public function save(){

        if($this->input->post()){
            $course_id = $this->input->post('course_id');
            $types     = $this->input->post('type_id');
            $amounts   = $this->input->post('amount');

            foreach($types as $k=>$type){

                $this->fee_structure_model->insert([
                    'course_id'=>$course_id,
                    'fee_type_id'=>$type,
                    'amount'=>$amounts[$k]
                ]);
            }
            $data["response"] = TRUE;
            $data["message"] = "Fee Structre Added.";
            echo json_encode($data);
            return;  
        }else{
            $data["response"] = FALSE;
			$data["message"] = "Something went worng.";
			echo json_encode($data);
			return;   
        }
    }

    public function get_all_fee_structure()
	 {
	  $data = array();
	  if($this->input->is_ajax_request())
	   {
	    $r = $this->fee_structure_model->get_all_fee_structure();
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
    public function get_fee_structure_detail_by_id()
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
	      
	      $r = $this->fee_structure_model->get_fee_structure_detail_by_id($course_id);
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


     public function edit_fee_structure()
	 {
		 if($this->input->post()){
			
            $course_id = $this->input->post('edit_course_hidden');
            $types     = $this->input->post('edit_type_id');
            $amounts   = $this->input->post('edit_amount');

            foreach($types as $k=>$type){
				$update_arr = array(
					'amount'  => $amounts[$k]
				);
				$r = $this->fee_structure_model->update_fee_structure($course_id ,$type, $update_arr);
            }
            $data["response"] = TRUE;
            $data["message"] = "Fee Structre Update.";
            echo json_encode($data);
            return;  
        }else{
            $data["response"] = FALSE;
			$data["message"] = "Something went worng.";
			echo json_encode($data);
			return;   
        }
	 }

	 public function delete_fee_structure()
	 {
	  $data = array();
	  if($this->input->is_ajax_request())
	   {
	    $course_id = (isset($_POST["course_id"]))?$this->input->post("course_id" , TRUE):0;
	    if($course_id == 0 || trim($course_id) == "")
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "Invalid Batch ID.";
	     }
	    else
	     {
	      
	      $update_arr = array( "status" => 0 );
	      $r = $this->fee_structure_model->delete_fee_structure($course_id , $update_arr);
	      if($r > 0)
	       {
	        $data["response"] = TRUE;
	        $data["message"] = "Fee Structure deleted successfully.";
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