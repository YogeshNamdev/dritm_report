<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ImportData extends CI_Controller {

  
  
  public function __construct(){

    parent :: __construct();
    //$this->load->model('Common_model');
    //$this->load->model('Excel_import_model');
    $this->load->library('excel');
    $this->load->library('upload');

  }


  public function index()
  {
  	    $this->load->view('importProf');
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
$data = array('employee_name' => $value['A'],
				'msd_id' => $value['B'],
				'call_date' => $value['C'],
				'audit_date' => $value['D'],
				'tenured' => $value['E'],
				'qa'=> $value['F'],
				'lob'=> $value['G'], 
				'month' => $value['H'],
				'week' => $value['I'],
				'team_leader'=> $value['J'],
				'manager' => $value['K'],
				'ticket_id' => $value['L'],
				'caller_no' => $value['M'],
				'audit_type' => $value['N'],
				'call_opening' => $value['O'],
				'call_opening_Reason' => $value['P'],
				'authentication_verification' => $value['Q'],
				'reason_authentication' => $value['R'],
				'call_closing' => $value['S'],
				'reason_call_closing' => $value['T'],
				'ivr_transfer' => $value['U'],
				'reason_ivr_transfer' => $value['V'],
				'hold_dead_air' => $value['W'],
				'reason_hold_dead_air' => $value['X'],
				'ros' => $value['Y'],
				'reason_ros' => $value['Z'],
				'call_handling_etiquettes' => $value['AA'],
				'call_handling_reason' => $value['AB'],
				'interruption' => $value['AC'],
				'interruption_reason' => $value['AD'],
				'language_control' => $value['AE'],
				'language_control_reason' => $value['AF'],
				'apology_empathy'=> $value['AG'],
				'apology_empathy_reason'=> $value['AH'],
				'active_listening' => $value['AI'],
				'active_listening_reason' => $value['AJ'],
				'customer_orientation' => $value['AK'],
				'customer_orientation_reason' => $value['AL'],
				
				'fact_finding' => $value['AM'],
				'fact_finding_reason' => $value['AN'],
				'call_back_time_supervisor' => $value['AO'],
				'back_time_supervisor_reason' => $value['AP'],
				'took_ownership' => $value['AQ'],
				'took_ownership_reason' => $value['AR'],
				'system_nevigation_case' => $value['AS'],
				'system_nevigation_reason' => $value['AT'],
				'accurate' => $value['AU'],
				'accurate_reason' => $value['AV'],
				'complete_info' => $value['AW'],
				'complete_info_reason' => $value['AX'],
				'documentation' => $value['AY'],
				'documentation_reason' => $value['AZ'],
				'call_avoidance' => $value['BA'],
				'call_avoidance_reason' => $value['BB'],
				'unprofessional_behavior' => $value['BC'],
				'unprofessional_behavior_reason' => $value['BD'],
				'disparaging_gem' => $value['BE'],
				'point_obtain' => $value['BF'],
				'total_opportunities' => $value['BG'],
				//'fatal_sub_reason' => $value['BH'],
				'no' => $value['BH'],
				'accuracy_score' => $value['BI'],
				'issue_type' => $value['BJ'],
				'summary' => $value['BK'],
				'fatal_reason' => $value['BL'],
				'duration' => $value['BM'],
				'feedback_status' => ''
				  
                  );
				  
				// echo "<pre>";print_r($data);die;
				  
$this->load->model("users_m");
//echo "<pre>";print_r($data);die;
		$insert_id = $this->users_m->insert($data);
            }
		
            $i++;
      } 
       
               
	//echo"<pre>";print_r($inserdata);die;
            //$result = $this->Excel_import_model->update("magnum_emp_profiles",$inserdata,'ep_msdid'); 
          
          
          
            
                 echo "<pre>"; 
                
                if($insert_id){
			//$this->session->set_flashdata('success','Successfulyy Data Imported');
				                   redirect("http://10.180.47.62/gemaudit/app/users/add_new_audit");
                }else{
                  //$this->session->set_flashdata('error','File is not uploaded');
				redirect("http://10.180.47.62/gemaudit/app/users/add_new_audit");                }             

          } catch (Exception $e) {
               die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME)
                        . '": ' .$e->getMessage());
            }
          }else{
              echo $error['error'];
            }
            
            
    
    $this->session->set_flashdata('message','File Data Insert Successfully');
          redirect("ImportData");

      
    }  // End Function
public function save_adviser() {
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
$data = array('user_name' => $value['A'],
                  'msd_id' => $value['B'],
                 'role_id'=> 2,
                   'eby'=>1,
				   'eat'=>date('Y-m-d')
                  );
$this->load->model("users_m");
$this->users_m->insert_adviser($data);

            }
		
            $i++;
      } 
       
               
	//echo"<pre>";print_r($inserdata);die;
            //$result = $this->Excel_import_model->update("magnum_emp_profiles",$inserdata,'ep_msdid'); 
          
          
          
            
                 echo "<pre>"; 
                
                if($insert_id){
			//$this->session->set_flashdata('success','Successfulyy Data Imported');
				                   redirect("http://10.180.47.62/gemaudit/app/users/add_adviser");
                }else{
                  //$this->session->set_flashdata('error','File is not uploaded');
				redirect("http://10.180.47.62/gemaudit/app/users/add_adviser");                }             

          } catch (Exception $e) {
               die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME)
                        . '": ' .$e->getMessage());
            }
          }else{
              echo $error['error'];
            }
            
            
    
    $this->session->set_flashdata('message','File Data Insert Successfully');
          redirect("ImportData");

      
    }  // End Function


}