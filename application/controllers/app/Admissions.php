<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admissions extends CI_Controller {

    function __construct(){
        parent::__construct();
         if(!isset($_SESSION['userdata']) || $_SESSION['userdata']['user_logged_status'] != TRUE)
	   {
	    redirect('app/logout');
	   }
        $this->load->model('admission_model');
        $this->load->model('student_model');
        $this->load->model('course_model');
        $this->load->model('batches_model');
        $this->load->model('fee_structure_model');
        $this->load->model('batch_fee_plan_model');
        $this->load->model('scholarship_model');
    }
     public function index($page = "list"){
		$data = array();
		if(!file_exists(APPPATH.'views/admissions/'.$page.'.php'))
		{
		show_404();
		}

		$data["student_list"] = $this->admission_model->get_all_students();
		$data["course_list"] = $this->admission_model->get_all_course_list();
        $data["batch_list"] = $this->admission_model->get_all_batch_list();
		$data["title"] = "Master Admissions";

		$this->load->view('app/templates/header' , $data);
		$this->load->view('app/templates/side_panel' , $data);
		$this->load->view('admissions/'.$page , $data);
		$this->load->view('app/templates/footer' , $data);

    }
    public function save(){
       // print_r($_POST);die;
        if($this->input->post()){
           
            $student_id = $this->input->post('student_id');
            // AUTO ADMISSION NUMBER
            $admission_no = "ADM".date("YmdHis");
            if($student_id!="0" || $student_id != ""){
                 
                $r = $this->admission_model->get_student_details_by_id($student_id);
               
                if($r != False){
                    $data["response"] = FALSE;
                    $data["message"] = "Student have already take admission.";
                    echo json_encode($data);
                    return;
                }
            }else{
                $data["response"] = FALSE;
                $data["message"] = "Select Student.";
                echo json_encode($data);
                return;
            }
            $raw = $this->input->post('admission_date');

            $admission_date = !empty($raw) 
            ? date('Y-m-d', strtotime($raw)) 
            : NULL;
           

           
            $course = $this->course_model->get_course_details_by_id($this->input->post('course_id'));
            $course = $course ? $course[0] : null;
            $batch_plan = $this->batch_fee_plan_model->get_plan_by_batch((int)$this->input->post('batch_id'));

            if(!$course){
                $data["response"] = FALSE;
                $data["message"] = "Course not found.";
                echo json_encode($data);
                return;
            }

            if(!$batch_plan){
                $data["response"] = FALSE;
                $data["message"] = "Batch fee plan not found. Please create batch fee plan first.";
                echo json_encode($data);
                return;
            }

            $admission_id=$this->admission_model->insert([
                'student_id'=>$this->input->post('student_id'),
                'course_id'=>$this->input->post('course_id'),
                'batch_id'=>$this->input->post('batch_id'),
                'admission_date'=> $admission_date,
                'admission_no'=>$admission_no
            ]);

            /* -------------------------
               AUTO FEE LEDGER CREATE
            --------------------------*/

            $duration_years = (int)$course->duration;
            $misc_items = $this->batch_fee_plan_model->get_plan_misc_items($batch_plan->id);
            $gross_total = round($this->calculate_batch_plan_total($batch_plan, $misc_items, $duration_years), 2);
            $discount_rows = $this->normalize_discount_rows($batch_plan, $duration_years);
            $discount_total = 0;

            foreach($discount_rows as $row){
                $discount_total += $row['discount_amount'];
            }

            if($discount_total > $gross_total){
                $discount_total = $gross_total;
            }

            $net_total = round($gross_total - $discount_total, 2);

            // INSERT LEDGER
            $ledger_id=$this->admission_model->insert_ledger([
                'admission_id'=>$admission_id,
                'gross_fee'=>$gross_total,
                'total_discount'=>$discount_total,
                'net_fee'=>$net_total,
                'total_fee'=>$net_total,
                'paid'=>0,
                'balance'=>$net_total
            ]);

            $this->admission_model->insert_fee_plan_snapshot([
                'admission_id' => $admission_id,
                'batch_fee_plan_id' => $batch_plan->id,
                'course_duration_years' => $duration_years,
                'tuition_fee_yearly' => $batch_plan->tuition_fee_yearly,
                'admission_fee' => $batch_plan->admission_fee,
                'misc_charge_mode' => $batch_plan->misc_charge_mode,
                'snapshot_json' => json_encode([
                    'plan' => $batch_plan,
                    'misc_items' => $misc_items
                ])
            ]);

            foreach($discount_rows as $row){
                $discount_data = [
                    'admission_id' => $admission_id,
                    'ledger_id' => $ledger_id,
                    'academic_year_no' => $row['academic_year_no'],
                    'semester_no' => $row['semester_no'],
                    'applicable_on' => $row['applicable_on'],
                    'discount_category' => $row['discount_category'],
                    'scholarship_id' => $row['scholarship_id'],
                    'discount_label' => $row['discount_label'],
                    'reason' => $row['reason'],
                    'discount_type' => $row['discount_type'],
                    'discount_value' => $row['discount_value'],
                    'discount_amount' => $row['discount_amount'],
                    'valid_from' => $row['valid_from'],
                    'valid_to' => $row['valid_to'],
                    'created_by' => isset($_SESSION['userdata']['user_id']) ? (int)$_SESSION['userdata']['user_id'] : NULL,
                    'status' => 1
                ];

                $this->scholarship_model->insert_student_discount($discount_data);

                if($row['discount_category'] === 'SCHOLARSHIP' && !empty($row['scholarship_id'])){
                    $this->scholarship_model->insert_student_scholarship([
                        'admission_id' => $admission_id,
                        'scholarship_id' => $row['scholarship_id'],
                        'discount_amount' => $row['discount_amount']
                    ]);
                }
            }

            /* -------------------------
               AUTO INSTALLMENT CREATE
            --------------------------*/

            $demands = $this->build_batch_plan_demands($batch_plan, $misc_items, $duration_years, $ledger_id, $admission_date, $discount_rows);
            foreach($demands as $demand){
                $this->admission_model->insert_installment($demand);
            }

            $data["response"] = TRUE;
            $data["message"] = "Admission Added.";
            echo json_encode($data);
            return;
        }
       
    }

    private function normalize_discount_rows($batch_plan, $duration_years)
    {
        $rows = [];
        $json = $this->input->post('discounts_json', TRUE);

        if($json){
            $decoded = json_decode($json, TRUE);
            if(is_array($decoded)){
                $rows = $decoded;
            }
        }

        if(empty($rows)){
            return [];
        }

        $normalized = [];
        $used_amount = [];

        foreach($rows as $row){
            $category = strtoupper(trim($row['discount_category'] ?? 'OTHER'));
            $discount_type = strtoupper(trim($row['discount_type'] ?? 'AMOUNT'));
            $discount_value = (float)($row['discount_value'] ?? 0);
            $academic_year_no = (int)($row['academic_year_no'] ?? 1);
            $semester_no = isset($row['semester_no']) && $row['semester_no'] !== '' ? (int)$row['semester_no'] : NULL;
            $scholarship_id = (int)($row['scholarship_id'] ?? 0);
            $applicable_on = strtoupper(trim($row['applicable_on'] ?? 'TUITION'));
            $label = trim($row['discount_label'] ?? '');
            $reason = trim($row['reason'] ?? '');

            if($academic_year_no <= 0 || $academic_year_no > $duration_years){
                continue;
            }

            $base_amount = (float)$batch_plan->tuition_fee_yearly;
            if($applicable_on === 'ALL'){
                $base_amount += ($academic_year_no === 1 ? (float)$batch_plan->admission_fee : 0);
            }

            if($category === 'SCHOLARSHIP' && $scholarship_id > 0){
                $sch = $this->scholarship_model->get_scholarship_by_id($scholarship_id);
                if($sch){
                    if($discount_value <= 0){
                        $discount_value = (float)$sch->discount_percent;
                    }
                    if($label === ''){
                        $label = 'Scholarship Discount';
                    }
                }
            }

            if($discount_value <= 0){
                continue;
            }

            $amount = $discount_type === 'PERCENT'
                ? round(($base_amount * $discount_value) / 100, 2)
                : round($discount_value, 2);

            if(!isset($used_amount[$academic_year_no])){
                $used_amount[$academic_year_no] = 0;
            }

            $remaining = $base_amount - $used_amount[$academic_year_no];
            if($amount > $remaining){
                $amount = max($remaining, 0);
            }

            if($amount <= 0){
                continue;
            }

            $normalized[] = [
                'discount_category' => $category,
                'scholarship_id' => $scholarship_id ?: NULL,
                'academic_year_no' => $academic_year_no,
                'semester_no' => $semester_no,
                'applicable_on' => $applicable_on,
                'discount_label' => $label ?: ($category === 'SCHOLARSHIP' ? 'Scholarship Discount' : 'Special Discount'),
                'reason' => $reason ?: NULL,
                'discount_type' => $discount_type,
                'discount_value' => $discount_value,
                'discount_amount' => $amount,
                'valid_from' => date('Y-m-d'),
                'valid_to' => date('Y-m-d', strtotime('+1 year -1 day'))
            ];

            $used_amount[$academic_year_no] += $amount;
        }

        return $normalized;
    }

    private function calculate_batch_plan_total($batch_plan, $misc_items, $duration_years)
    {
        $total = 0;

        for($year = 1; $year <= $duration_years; $year++){
            $total += (float)$batch_plan->tuition_fee_yearly;
            if($year === 1){
                $total += (float)$batch_plan->admission_fee;
            }
        }

        foreach($misc_items as $item){
            $total += (float)$item->amount;
        }

        return $total;
    }

    private function build_batch_plan_demands($batch_plan, $misc_items, $duration_years, $ledger_id, $admission_date, $discount_rows = [])
    {
        $demands = [];
        $discounts_by_year = [];

        foreach($discount_rows as $row){
            $year = (int)$row['academic_year_no'];
            if(!isset($discounts_by_year[$year])){
                $discounts_by_year[$year] = 0;
            }
            $discounts_by_year[$year] += (float)$row['discount_amount'];
        }

        $base_date = $admission_date ?: date('Y-m-d');

        for($year = 1; $year <= $duration_years; $year++){
            $year_discount = $discounts_by_year[$year] ?? 0;
            $tuition_per_sem = round(((float)$batch_plan->tuition_fee_yearly) / 2, 2);
            $sem1_discount = round($year_discount / 2, 2);
            $sem2_discount = round($year_discount - $sem1_discount, 2);
            $sem1_due = date('Y-m-d', strtotime($base_date.' +'.(($year - 1) * 12).' months'));
            $sem2_due = date('Y-m-d', strtotime($base_date.' +'.((($year - 1) * 12) + 6).' months'));

            $demands[] = [
                'ledger_id' => $ledger_id,
                'academic_year_no' => $year,
                'semester_no' => 1,
                'fee_type_id' => $batch_plan->tuition_fee_type_id ?: NULL,
                'component_group' => 'TUITION',
                'installment_label' => 'Year '.$year.' - Semester 1 Tuition',
                'due_date' => $sem1_due,
                'gross_amount' => $tuition_per_sem,
                'discount_amount' => $sem1_discount,
                'net_amount' => $tuition_per_sem - $sem1_discount,
                'amount' => $tuition_per_sem - $sem1_discount,
                'status' => 'PENDING'
            ];

            $demands[] = [
                'ledger_id' => $ledger_id,
                'academic_year_no' => $year,
                'semester_no' => 2,
                'fee_type_id' => $batch_plan->tuition_fee_type_id ?: NULL,
                'component_group' => 'TUITION',
                'installment_label' => 'Year '.$year.' - Semester 2 Tuition',
                'due_date' => $sem2_due,
                'gross_amount' => $tuition_per_sem,
                'discount_amount' => $sem2_discount,
                'net_amount' => $tuition_per_sem - $sem2_discount,
                'amount' => $tuition_per_sem - $sem2_discount,
                'status' => 'PENDING'
            ];

            if($year === 1 && (float)$batch_plan->admission_fee > 0){
                $demands[] = [
                    'ledger_id' => $ledger_id,
                    'academic_year_no' => 1,
                    'semester_no' => 1,
                    'fee_type_id' => $batch_plan->admission_fee_type_id ?: NULL,
                    'component_group' => 'ADMISSION',
                    'installment_label' => 'One-Time Admission Fee',
                    'due_date' => $sem1_due,
                    'gross_amount' => (float)$batch_plan->admission_fee,
                    'discount_amount' => 0,
                    'net_amount' => (float)$batch_plan->admission_fee,
                    'amount' => (float)$batch_plan->admission_fee,
                    'status' => 'PENDING'
                ];
            }
        }

        foreach($misc_items as $item){
            $due_months = (($item->academic_year_no - 1) * 12);
            if((string)$item->charge_mode === 'SEMESTER' && (int)$item->semester_no === 2){
                $due_months += 6;
            }

            $semester_no = $item->charge_mode === 'SEMESTER' ? (int)$item->semester_no : 1;

            $demands[] = [
                'ledger_id' => $ledger_id,
                'academic_year_no' => (int)$item->academic_year_no,
                'semester_no' => $semester_no,
                'fee_type_id' => $item->fee_type_id,
                'component_group' => 'MISC',
                'installment_label' => 'Year '.$item->academic_year_no.' '.($item->charge_mode === 'SEMESTER' ? '- Semester '.$semester_no.' ' : '').'Misc Fee',
                'due_date' => date('Y-m-d', strtotime($base_date.' +'.$due_months.' months')),
                'gross_amount' => (float)$item->amount,
                'discount_amount' => 0,
                'net_amount' => (float)$item->amount,
                'amount' => (float)$item->amount,
                'status' => 'PENDING'
            ];
        }

        return $demands;
    }

    public function get_all_admissions()
    {
        $data = array();
        if($this->input->is_ajax_request())
        {
        
        $r = $this->admission_model->get_all_admissions();
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

    public function get_admission_details_by_id()
	 {
	  $data = array();
	  if($this->input->is_ajax_request())
	   {
	    $admission_id = (isset($_POST["admission_id"]))?$this->input->post("admission_id" , TRUE):0;
	    if($admission_id == 0 || trim($admission_id) == "")
	     {
	      $data["response"] = FALSE;
	      $data["message"] = "Invalid Admission ID.";
	     }
	    else
	     {
	      
	      $r = $this->admission_model->get_admission_details_by_id($admission_id);
         
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

}
