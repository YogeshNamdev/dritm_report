<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fee_types extends CI_Controller {

    public function __construct(){
        parent::__construct();
         if(!isset($_SESSION['userdata']) || $_SESSION['userdata']['user_logged_status'] != TRUE)
	   {
	    redirect('app/logout');
	   }
         $this->load->model('fee_types_model');
    }

    // list page
    public function index($page = "list"){
		$data = array();
		if(!file_exists(APPPATH.'views/fee_types/'.$page.'.php'))
		{
		show_404();
		}

		$data["fee_types_list"] = $this->fee_types_model->get_all_fee_types();
		$data["title"] = "Master Fee types";

		$this->load->view('app/templates/header' , $data);
		$this->load->view('app/templates/side_panel' , $data);
		$this->load->view('fee_types/'.$page , $data);
		$this->load->view('app/templates/footer' , $data);

    }

    // save course
    public function save(){
		$fee_name = trim($this->input->post('fee_name',true));
		$fee_category = strtoupper(trim($this->input->post('fee_category',true)));
		$charge_mode = strtoupper(trim($this->input->post('charge_mode',true)));
		$discount_allowed = $this->input->post('discount_allowed',true) ? 1 : 0;
		
		if($fee_name == ''){
			echo json_encode([
				"response"=>FALSE,
				"message"=>"Fee name required"
			]);
			return;
		}
		if($this->fee_types_model->exists($fee_name)){
			$data["response"] = FALSE;
			$data["message"] = "This Fee Name already exists.";
			echo json_encode($data);
			return;   
		}else{
            $this->fee_types_model->insert([
                'fee_name'=>$fee_name,
                'fee_category'=> in_array($fee_category,['ADMISSION','TUITION','MISC']) ? $fee_category : 'MISC',
                'charge_mode'=> in_array($charge_mode,['ONE_TIME','YEARLY','SEMESTER']) ? $charge_mode : 'ONE_TIME',
                'discount_allowed'=>$discount_allowed
            ]);
            $data["response"] = TRUE;
            $data["message"] = "Fee Added.";
            echo json_encode($data);
        }
    }
    public function get_all_fee_types()
	 {
	  $data = array();
	  if($this->input->is_ajax_request())
	   {
	    
	    $r = $this->fee_types_model->get_all_fee_types();
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

public function get_fee_types_details_by_id()
	 {
	  $data = array();
	  if($this->input->is_ajax_request())
	   {
	    $fee_types_id = (isset($_POST["fee_types_id"]))?$this->input->post("fee_types_id" , TRUE):0;
	    if($fee_types_id == 0 || trim($fee_types_id) == "")
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "Invalid Batch ID.";
	     }
	    else
	     {
	      
	      $r = $this->fee_types_model->get_fee_types_details_by_id($fee_types_id);
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

     public function edit_fee_types_details()
	 {
	  $data = array();
	  if($this->input->is_ajax_request())
	   {
		$fee_types_id = trim($this->input->post('fee_types_id',true));

		$fee_name = trim($this->input->post('fee_name',true));
		if($fee_name == ''){
			echo json_encode([
				"response"=>FALSE,
				"message"=>"Fee name required"
			]);
			return;
		}else if($fee_types_id == ''){
			echo json_encode([
				"response"=>FALSE,
				"message"=>"Invalid"
			]);
			return;
		}
	    else
	     {
			if(!$this->fee_types_model->exists($fee_name))
			{
			
				$update_arr = array(
					'fee_name' => $fee_name,
                    'fee_category' => in_array(strtoupper(trim($this->input->post('fee_category',true))),['ADMISSION','TUITION','MISC']) ? strtoupper(trim($this->input->post('fee_category',true))) : 'MISC',
                    'charge_mode' => in_array(strtoupper(trim($this->input->post('charge_mode',true))),['ONE_TIME','YEARLY','SEMESTER']) ? strtoupper(trim($this->input->post('charge_mode',true))) : 'ONE_TIME',
                    'discount_allowed' => $this->input->post('discount_allowed',true) ? 1 : 0
				);

				$r = $this->fee_types_model->update_batch($fee_types_id , $update_arr);

				if($r !== false)
				{
					$data["response"] = TRUE;
					$data["message"] = "Fee name updated successfully.";
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
				$data["message"] = "This Fee Name already exists.";
			}
	     }
	   }
	  echo json_encode($data);
	 }

	 public function delete_fee_types()
	 {
	  $data = array();
	  if($this->input->is_ajax_request())
	   {
	    $fee_types_id = (isset($_POST["fee_types_id"]))?$this->input->post("fee_types_id" , TRUE):0;
	    if($fee_types_id == 0 || trim($fee_types_id) == "")
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "Invalid Batch ID.";
	     }
	    else
	     {
	      
	      $update_arr = array( "status" => 0 );
	      $r = $this->fee_types_model->update_Batch($fee_types_id , $update_arr);
	      if($r > 0)
	       {
	        $data["response"] = TRUE;
	        $data["message"] = "Fee Name deleted successfully.";
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
