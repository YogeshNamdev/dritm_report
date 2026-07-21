<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Import_excel extends CI_Controller {

  
  
  public function __construct(){

    parent :: __construct();
    //$this->load->model('Common_model');
    //$this->load->model('Excel_import_model');
    $this->load->library('excel');
    $this->load->library('upload');

  }


  public function index()
  {
    //echo"hekkjocn";die;
  	    $this->load->view('importProf');
  }


/*public function uploadSupportData() {
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

echo"<pre>";print_r($allDataInSheet);die;
                foreach ($allDataInSheet as $value) {
                           if($i > 0 )
              {
                  
              $check = $this->Excel_import_model->checkdata($value['A']);  
            if($check != 0){
            
            
                   $dobDate = $value['G'];    
      $dob = date("Y-m-d", strtotime($dobDate));


        if($value['J']=='Married')
        {
         $originalDate = $value['K'];    
      $Marr = date("Y-m-d", strtotime($originalDate));
    }
      else{

        $Marr = '';
      }

					
                      $inserdata[$i]['ep_msdid'] = $value['A'];
                      $inserdata[$i]['emp_religion'] = $value['B'];
                      $inserdata[$i]['ep_bank_name'] = $value['C'] ;
                      $inserdata[$i]['ep_branch'] = $value['D'];
                      $inserdata[$i]['ep_account'] = $value['E'];
                      $inserdata[$i]['ep_ifsc'] = $value['F'];
                      $inserdata[$i]['ep_dob'] = $dob;
                      $inserdata[$i]['ep_gender'] = $value['H'];
                      $inserdata[$i]['ep_blood_group'] = $value['I'] ;
                      $inserdata[$i]['ep_marriage_status'] = $value['J'];
                      $inserdata[$i]['ep_marriage'] = $Marr;
                      $inserdata[$i]['emp_level_exp'] = $value['L'];
                      $inserdata[$i]['ep_total_exp'] =  $value['M'];
                      $inserdata[$i]['ep_bpo_experience'] = $value['N'];
                      $inserdata[$i]['ep_address_present'] = $value['O'];
                      $inserdata[$i]['ep_area_present'] = $value['P']; 
                      $inserdata[$i]['ep_city_present'] = $value['Q'];  
                      $inserdata[$i]['ep_landmark'] = $value['R'];
                      $inserdata[$i]['ep_pincode_present'] = $value['S'];


                      $inserdata[$i]['ep_state_present'] = 18;
                      $inserdata[$i]['ep_district_permanent'] = 307;  

                      $inserdata[$i]['ep_address_permanent'] = $value['W'];
                      $inserdata[$i]['ep_area_permanent'] = $value['X'];
                      $inserdata[$i]['ep_city_permanent'] = $value['Y'];
                      $inserdata[$i]['ep_landmark_permanent'] = $value['Z'];
                        
                      $inserdata[$i]['ep_pincode_permanent'] = $value['AA'];


           

                      $inserdata[$i]['ep_state_permanent'] = $value['AC'];
                      $inserdata[$i]['ep_district_permanent'] = $value['AD'];


                      $inserdata[$i]['emp_accomodation'] = $value['AE']; 
                      $inserdata[$i]['ep_vechicle'] = $value['AF'];
                      $inserdata[$i]['ep_source'] = $value['AG'];   
                      $inserdata[$i]['ep_remark'] = $value['AH'];
                      $inserdata[$i]['ep_refence_id'] = $value['AI'];
                      $inserdata[$i]['ep_refence_name'] = $value['AJ'];
                      
                      $inserdata[$i]['emp_language'] = $value['AK'];
                     $inserdata[$i]['emp_category'] =$value['AL'];
                     $inserdata[$i]['ep_distance'] = $value['AM'];
                       
                       
                          
                         
                              
                     //$inserdata[$i]['emp_type'] =$value['H'];
                     
              
                    
}}
                  $i++;
                }        
               
	//echo"<pre>";print_r($inserdata);die;
           // $result = $this->Excel_import_model->update("magnum_emp_profiles",$inserdata,'ep_msdid'); 
          
          
          
            
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
          redirect("ImportData");

      
    }  */// End Function



     


}