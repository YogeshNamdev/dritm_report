<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Importexcel extends CI_Controller {

  
  
  public function __construct(){

    parent :: __construct();
    $this->load->model('Common_model');
    $this->load->model('Excel_import_model');
    $this->load->library('excel');
    		$this->load->library('upload');

  }


  public function index()
  {
  	    $this->load->view('import');
  }





public function uploadSupportData() {

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

//echo "<pre>";print_r($allDataInSheet);die;
                foreach ($allDataInSheet as $value) {
                  	
              if($i > 0 )
              {
                  
       $check = $this->Excel_import_model->checkdata($value['A']);  

IF($check > 0)
{
  $this->session->set_flashdata('message','<strong style="color:red"> '. $value['A'].' Already Created in HRMS Please Remove It , Again Upload File </strong>');
          redirect("Importexcel");
}
ELSE IF ($this->Excel_import_model->checkcenter($value['J']) == 0)
{
$this->session->set_flashdata('message','<strong style="color:red"> '. $value['A'].' Has Wrong Center Code, Please Assign Correct Center Code </strong>');
 redirect("Importexcel");
}
ELSE IF ($this->Excel_import_model->checkprocess($value['G']) == 0)
{
$this->session->set_flashdata('message','<strong style="color:red"> '. $value['A'].' Has Wrong Process Code, Please Assign Correct Process Code </strong>');
 redirect("Importexcel");
}
ELSE IF ($this->Excel_import_model->checkdepartment($value['I']) == 0)
{
$this->session->set_flashdata('message','<strong style="color:red"> '. $value['A'].' Has Wrong Department Code, Please Assign Correct Department Code </strong>');
 redirect("Importexcel");
}
ELSE IF ($this->Excel_import_model->checkdesignation($value['E'] ) == 0)
{
$this->session->set_flashdata('message','<strong style="color:red"> '. $value['A'].' Has Wrong Designation Code, Please Assign Correct Designation Code </strong>');
 redirect("Importexcel");
}





        else {
            $fname = $value['B'];
            $mname = $value['C'];
            $lname = $value['D'];

		$department =  $value['I'];
        $designation = $value['E'];
        $center =  $value['J'];
        $process =  $value['G'];
            

	$subprocess ='';


        $originalDate = $value['L'];    
	    $joindate = date("Y-m-d", strtotime($originalDate));


            if($value['O'] == 'PTE')
            {
                $type = 1;
            }
            else{
                $type = 2;
            }
					
                     $inserdata[$i]['emp_code'] = $value['A'];
                     $inserdata[$i]['emp_process'] = $process;
                     $inserdata[$i]['emp_subprocess'] = $subprocess;
                     $inserdata[$i]['emp_fname'] = $fname;
                     $inserdata[$i]['emp_mname'] = $mname;
                     $inserdata[$i]['emp_lname'] = $lname ;
                     $inserdata[$i]['emp_mobile'] = $value['F'] ;
                     $inserdata[$i]['emp_password'] = "123456";
                     $inserdata[$i]['emp_reporting'] = $value['N'];
                     $inserdata[$i]['emp_department'] = $department;
                     $inserdata[$i]['emp_band'] = $value['K'];
                     $inserdata[$i]['emp_designation'] =  $designation;
                     $inserdata[$i]['emp_center'] = $center;
                     $inserdata[$i]['emp_joindate'] = $joindate;
                     $inserdata[$i]['emp_created_date'] = date('Y-m-d H:i:s');
                     $inserdata[$i]['emp_status'] = 1;
                     $inserdata[$i]['emp_etype'] = $type;
                     $inserdata[$i]['emp_batch'] = $value['P'];
                     //$inserdata[$i]['emp_type'] =$value['H'];
                     
              
               $inserdata[$i]['emp_trainer'] = $value['M'];
}}
                  $i++;
                }        
//echo "<pre>";print_r($inserdata);die;
            $result = $this->Excel_import_model->insert("magnum_employees",$inserdata); 
          
         //print_r($result);
          
            
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
          redirect("Importexcel");

      
    }  // End Function



     public function uploadAgentdata(){
 @ini_set('memory_limit', '-1');
  @ini_set('max_execution_time', 0);

            $path = "./assets/excel/";
            require_once APPPATH . "/third_party/PHPExcel.php";
            $config['upload_path'] = $path;
            $config['allowed_types'] = 'xlsx|xls';
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
                    
                    $name = $value['C'];
                    $nam = explode(" ",$name);

                    if(count($nam) == 1)
                    {
                      $fname = $nam[0];
                      $mname = '';
                      $lname = '';
                    } 
                    else if(count($nam)==2)
                    {
                      $fname = $nam[0];
                      $mname = $nam[1];
                      $lname = '';
                    }
                    else{
                      $fname = $nam[0];
                      $mname = $nam[1];
                      $lname = $nam[2];
                    }

                    if($value['D'] == 'Absconding'){
                      $st = 2;}
                      else if($value['D'] == 'Active'){
                        $st = 1;}
                      else if($value['D'] == 'Not Joined'){
                        $st = 6; }  
                      else if($value['D'] == 'Resigned'){
                        $st= 5;}
                        else if($value['D'] == 'Termination'){
                          $st = 4;
                        } 

            $department =  $this->Common_model->getvalue("dept_id","magnum_departments",array("dept_name="=>$value['K']));
            $designation = $this->Common_model->getvalue("desig_id","magnum_designation",array("desig_name="=>$value['Q'],"desig_department="=>3));
            $process =  $this->Common_model->getvalue("process_id","magnum_process",array("process_name="=>$value['U']));

            $originalDate = $value['L'];
			$joindate = date("Y-m-d", strtotime($originalDate));

                    
                     $inserdata[$i]['emp_code'] = $value['B'];
                     $inserdata[$i]['emp_process'] = $process;
                      $inserdata[$i]['emp_batch'] = $value['H'];
                     $inserdata[$i]['emp_fname'] = $fname;
                     $inserdata[$i]['emp_mname'] = $mname;
                     $inserdata[$i]['emp_lname'] = $lname ;
                     $inserdata[$i]['emp_password'] = 123456;
                     $inserdata[$i]['emp_mobile'] = $value['J'];
                     $inserdata[$i]['emp_department'] = 3;
                     $inserdata[$i]['emp_designation'] =  $designation;
                     $inserdata[$i]['emp_joindate'] = $joindate;
                     $inserdata[$i]['emp_status'] = $st;
                      $inserdata[$i]['emp_center'] = 7;
                  
                     


                  $i++;
                }               
               $result = $this->excel_import_model->insert("magnum_employees3",$inserdata); 
                // echo "<pre>"; 
                // print_r($inserdata) ;
                // exit;
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
          redirect("Importexcel");

      
    }  // End Function
      


public function uploadSupportProfileData(){
 @ini_set('memory_limit', '-1');
  @ini_set('max_execution_time', 0);

            $path = "./assets/excel/";
            require_once APPPATH . "/third_party/PHPExcel.php";
            $config['upload_path'] = $path;
            $config['allowed_types'] = 'xlsx|xls';
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
                  		
            $msdid = $this->Common_model->getvalue("emp_id","magnum_employees",array("emp_code="=>$value['E']));
          /*  $main_reason = $this->Common_model->getvalue("reason_main_id","magnum_leaving_reason_main",array("reason_main_name="=>$value['AX']));
             $sub_reason =  $this->Common_model->getvalue("reason_submain_id","magnum_leaving_reason_submain",array("reason_submain_name="=>$value['AY'],"reason_submain_main="=>$main_reason));

      $originalDate = $value['W'];
			$lwd = date("Y-m-d", strtotime($originalDate));

      $dob1 = $value['X'];
      $dob = date("Y-m-d", strtotime($dob1));

       $confirm1 = $value['T'];
      $confirm2 = date("Y-m-d", strtotime($confirm1)); */

					 $inserdata[$i]['ep_employee'] = $msdid;
					 $inserdata[$i]['ep_msdid'] = $value['E'];
					  $inserdata[$i]['ep_reporting_person'] = $value['F'];
                     //$inserdata[$i]['ep_role'] = $value['N'];
                    // $inserdata[$i]['ep_trainer'] = $process;
                    /* $inserdata[$i]['ep_reporting_person'] = $value['L'];
                     //$inserdata[$i]['ep_last_working_day'] = $lwd;
                     //$inserdata[$i]['ep_leaving_reason'] = $main_reason;
                     //$inserdata[$i]['ep_leaving_sub_reason'] = $sub_reason;
                     //$inserdata[$i]['ep_leaving_reason_details'] = $value['AZ'];
                   //  $inserdata[$i]['ep_status'] = $lname ;
                     $inserdata[$i]['ep_dob'] = $dob;
                     $inserdata[$i]['ep_gender'] = $value['BI'];
                     //$inserdata[$i]['ep_marriage'] = $value['BA'];
                     $inserdata[$i]['ep_marriage_status'] = $value['AA'];
                     $inserdata[$i]['ep_blood_group'] = $value['BH'];
                     $inserdata[$i]['ep_bank_name'] =  $value['CH'];  
                   
                     $inserdata[$i]['ep_account'] = $value['CI'];
                     $inserdata[$i]['ep_ifsc'] = $value['CJ'];
                    // $inserdata[$i]['ep_branch'] = $st;
                     $inserdata[$i]['ep_alt_mobile'] =$value['AU'];
                     //$inserdata[$i]['emp_level_exp'] =$value['H'];

                     //$inserdata[$i]['emp_language'] = $center;
                     $inserdata[$i]['emp_religion'] = $value['BD'];
                     $inserdata[$i]['emp_category'] = $value['BE'];
                     $inserdata[$i]['emp_accomodation'] =$value['BF'];
                     //$inserdata[$i]['ep_city_permanent'] =$value['H'];
                     
                     $inserdata[$i]['ep_address_permanent'] = $value['AP'];
                     //$inserdata[$i]['ep_area_permanent'] = $joindate;
                     //$inserdata[$i]['ep_pincode_permanent'] = $st;
                     //$inserdata[$i]['ep_landmark_permanent'] =$value['H'];
                     //$inserdata[$i]['ep_city_present'] =$value['H'];

                     $inserdata[$i]['ep_address_present'] = $value['AO'];
                     //$inserdata[$i]['ep_area_present'] = $joindate;
                     //$inserdata[$i]['ep_distance'] = $st;
                     //$inserdata[$i]['ep_pincode_present'] =$value['H'];
                     //$inserdata[$i]['ep_landmark'] =$value['H'];

                     $inserdata[$i]['ep_source'] = $value['Y'];
                     $inserdata[$i]['ep_remark'] = $value['Z'];
                     $inserdata[$i]['ep_eligible_confirmation'] =$value['S'];
                     $inserdata[$i]['ep_confirmation'] =$confirm2;
                     $inserdata[$i]['ep_confirmation_letter_status'] =$value['U'];
                     $inserdata[$i]['ep_eligible_pf'] =$value['V'];
                     $inserdata[$i]['ep_pf_number'] =$value['AW'];

                     $inserdata[$i]['ep_leaving_letter_status'] = $value['BA'];
                     $inserdata[$i]['ep_detail_reason'] = $value['AZ'];
                     $inserdata[$i]['ep_appointment_letter'] = $value['R'];
                      
                     $inserdata[$i]['ep_msdid'] =$value['B'];
                     $inserdata[$i]['ep_rejoin'] =$value['BB'];
                     */

                  $i++;
                }               
               $result = $this->excel_import_model->insert("magnum_emp_profiles",$inserdata); 
                // echo "<pre>"; 
                // print_r($inserdata) ;
                // exit;
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
          redirect("Importexcel");

      
    }  // End Function

    public function outside_data()
    {
 	$this->load->model('Candidate/Candidate_model','cand');
      $data["hrname"] = $this->cand->getHrName();

          $this->load->view('candidate/outside_data',$data);
    }
  
    public function uploadoutsideData() {
      
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
                      $name = $value['A'];
                      $number = $value['B'];
                      $alternate_contact = $value['C'];
                      $area = $value['D'];
                      $qualification =  $value['E'];
                      $languages = $value['E'];
                      $age =  $value['G'];
                      $source =  $value['H'];
                      $added_at =  date("Y-m-d");
                      $added_by =  $this->session->userdata('eid');

                            $inserdata[$i]['name'] = $value['A'];
                            $inserdata[$i]['number'] = $value['B'];
                            $inserdata[$i]['alternate_contact'] = $value['C'];
                            $inserdata[$i]['area'] = $value['D'];
                            $inserdata[$i]['qualification'] = $value['E'];
                            $inserdata[$i]['languages'] = $value['F'];
                            $inserdata[$i]['age'] = $value['G'];
                            $inserdata[$i]['source'] = $value['H'];
			    $inserdata[$i]['process'] = $value['I'];
                            $inserdata[$i]['panel_status'] = $value['J'];
                            $inserdata[$i]['added_at'] =  date("Y-m-d");
                            $inserdata[$i]['added_by'] = $this->session->userdata('eid');
                      }
                        $i++;
              }        
 //echo"<pre>";print_r($inserdata);die;
                        $result = $this->Excel_import_model->insert("import_candidate_data",$inserdata); 
        
       
        
          
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
        redirect(base_url()."index.php/Importexcel/outside_data");

    
  }  // End Function

 public function uploadsupport()
  {
    //$this->load->model('Candidate/Candidate_model','cand');
   // $data["hrname"] = $this->cand->getHrName();

        $this->load->view('candidate/uploadsupport');
  }
  public function uploadsupportstaffData() {
      
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
                    
                    //$added_by =  $this->session->userdata('eid');
                          $inserdata[$i]['designation'] = $value['A'];
                          $inserdata[$i]['department'] = $value['B'];
                          $inserdata[$i]['first_name'] = $value['C'];
                          $inserdata[$i]['middle_name'] = $value['D'];
                          $inserdata[$i]['last_name'] = $value['E'];
                          $inserdata[$i]['phone_no'] = $value['F'];
                          $inserdata[$i]['email'] = $value['G'];
                          $inserdata[$i]['source'] = $value['H'];
                          $inserdata[$i]['other_source'] = $value['I'];
                          $inserdata[$i]['qualification'] = $value['J'];
                          $inserdata[$i]['experience'] = $value['K'];
                          $inserdata[$i]['in_year'] = $value['L'];
                          $inserdata[$i]['computer_knowledge'] = $value['M'];
                          $inserdata[$i]['computer_knowledge_type'] = $value['N'];
                          $inserdata[$i]['comp_other_know'] = $value['O'];
                          $inserdata[$i]['last_company'] = $value['P'];
                          $inserdata[$i]['last_salary'] = $value['Q'];
                          $inserdata[$i]['last_comp_desig'] = $value['R'];
                          $inserdata[$i]['notice_period'] = $value['S'];
                          $inserdata[$i]['notice_period_time'] = $value['T'];
                          $inserdata[$i]['salary_expectation'] = $value['U'];
                          $inserdata[$i]['current_location'] = $value['V'];
                          $inserdata[$i]['joining_status'] = $value['W'];
                          $inserdata[$i]['remark'] = $value['X'];
                          $inserdata[$i]['communication'] = $value['Y'];
                          $inserdata[$i]['reason_for_leaving'] = $value['Z'];
                          $inserdata[$i]['eat'] =  date("Y-m-d H:i:s");
                          $inserdata[$i]['eby'] = $this->session->userdata('eid');

                    }
                      $i++;
            }        
            //echo"<pre>";print_r($inserdata);die;
        $result = $this->Excel_import_model->insert("magnum_supportstaff_form",$inserdata); 
      echo"<pre>";print_r($result );die;

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
      redirect("Importexcel/uploadsupport");

  
}  // End Function

public function gem_data()
    {
      $this->load->model('Candidate/Candidate_model','cand');
      $data["hrname"] = $this->cand->getHrName();
  
          $this->load->view('candidate/gem_data',$data);
    }

public function uploadGemData() {
      
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

           // echo "<pre>";print_r($allDataInSheet);die;
            foreach ($allDataInSheet as $value) {
                  if($i > 0 )
                  {
                      $inserdata[$i]['emp_userid'] = $value['B'];
                      $emp_code = $value['A'];
//print_r($inserdata);die;
    			$re = $this->Common_model->update_gem($inserdata[$i]['emp_userid'],$emp_code); 
//echo $re;die;
                                    }
                      $i++;
            }
       
            
       // $result = $this->Excel_import_model->insert("import_candidate_data",$inserdata); 
      
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
      redirect("Importexcel/gem_data");

  
}  // End Function

public function update_empolyee_data()
    {
      $this->load->model('Candidate/Candidate_model','cand');
      $data["hrname"] = $this->cand->getHrName();
  
          $this->load->view('candidate/update_empolyee_data',$data);
    }

public function updateEmpData() {
      
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
      
          $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
          $objReader = PHPExcel_IOFactory::createReader($inputFileType);
          $objPHPExcel = $objReader->load($inputFileName);
          $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
          $flag = true;
          $i=0;

      //  echo "<pre>";print_r($allDataInSheet);die;
          foreach ($allDataInSheet as $value) {
                if($i > 0 )
                {
                    $inserdata[$i]['emp_status'] = $value['C'];
                    $emp_code = $value['B'];
                    $emp_id = $value['A'];

                  $re = $this->Common_model->update_employee($inserdata[$i]['emp_status'],$emp_code,$emp_id); 

                                  }
                    $i++;
          }
           echo "<pre>"; 
          
          if($result){
            echo "Update successfully";
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
    redirect("Importexcel/update_empolyee_data");


}  // End Function


}