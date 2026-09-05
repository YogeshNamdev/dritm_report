<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller {

    private $report_roles = array(1, 2, 3, 4, 5);
    private $add_roles = array(1, 2 ,3, 4 , 5);
    private $update_roles = array(1, 3);
    private $agent_report_roles = array(1, 2 , 5);
    
    public function __construct(){
        parent::__construct();
        if(!isset($_SESSION['userdata']) || $_SESSION['userdata']['user_logged_status'] != TRUE)
        {
        redirect('app/logout');
        }
        if(!$this->is_allowed($this->report_roles))
        {
            show_error('You are not authorized to access reports.', 403);
        }
        $this->load->model("Report_m");
        
    }

    private function is_allowed($roles)
    {
        return isset($_SESSION["userdata"]["role_id"]) && in_array((int)$_SESSION["userdata"]["role_id"], $roles);
    }

    private function deny_json()
    {
        echo json_encode(array("response" => FALSE, "message" => "You are not authorized to perform this action."));
        return FALSE;
    }

    private function current_role_id()
    {
        return (int)$_SESSION["userdata"]["role_id"];
    }

    private function current_emp_id()
    {
        return (int)$_SESSION["userdata"]["emp_id"];
    }

    private function allowed_complaint_statuses()
    {
        return array('Open', 'PC', 'WIP', 'OWA', 'Closed','Force Closed');
    }

    private function normalize_complaint_status($status)
    {
        $status = trim((string)$status);
        return in_array($status, $this->allowed_complaint_statuses()) ? $status : 'Open';
    }

    private function resolution_report_meta($report_key)
    {
        $map = array(
            'no_same' => array(
                'table' => 'no_same_resolution_report',
                'name' => 'No/Same Resolution Report',
                'update_method' => 'update_no_or_same_resolutions'
            ),
            'copy_paste' => array(
                'table' => 'copy_paste_wrong_resolution_report',
                'name' => 'Copy/Paste Resolution Report',
                'update_method' => 'update_copypaste_resolutions'
            ),
            'direction' => array(
                'table' => 'direction_report',
                'name' => 'Direction Report',
                'update_method' => 'update_direction_resolutions'
            )
        );
        return isset($map[$report_key]) ? $map[$report_key] : FALSE;
    }

    private function add_resolution_history($report_key, $record, $previous_status, $new_status, $action_type)
    {
        $meta = $this->resolution_report_meta($report_key);
        if($meta == FALSE || $record == FALSE)
        {
            return FALSE;
        }

        $previous_status = ($previous_status == "") ? NULL : $previous_status;
        $new_status = ($new_status == "") ? NULL : $new_status;

        return $this->Report_m->insert_complaint_status_history(array(
            'report_key' => $report_key,
            'report_name' => $meta['name'],
            'record_id' => (int)$record->id,
            'complaint_no' => (int)$record->complaint_no,
            'previous_complaint_status' => $previous_status,
            'new_complaint_status' => $new_status,
            'action_type' => $action_type,
            'officer_worked' => ($previous_status !== NULL && $new_status !== NULL && $previous_status != $new_status) ? 1 : 0,
            'helpdesk_remark' => isset($record->helpdesk_remark) ? $record->helpdesk_remark : NULL,
            'other_remark' => isset($record->remark) ? $record->remark : NULL,
            'updated_by' => $this->current_emp_id(),
            'updated_at' => date('Y-m-d H:i:s'),
            'created_by' => isset($record->added_by) ? (int)$record->added_by : $this->current_emp_id(),
            'created_at' => isset($record->added_at) ? $record->added_at : date('Y-m-d H:i:s')
        ));
    }

    private function normalize_callback_remark($remark_type, $remark_other)
    {
        return ($remark_type == "Other") ? trim($remark_other) : $remark_type;
    }

    private function require_agent_report_access()
    {
        if(!$this->is_allowed($this->agent_report_roles))
        {
            show_error('You are not authorized to access this report.', 403);
        }
    }


    
    public function NoOrSameResolutionDetails($page = "list"){
        $data = array();
		if(!file_exists(APPPATH.'views/reports/NoOrSameResolutionDetails/'.$page.'.php'))
		{
		show_404();
		}
        
        $data["user_list"] = $this->Report_m->get_all_user_list();
        $data["department_list"] = $this->Report_m->get_all_departments();
        $data["district_list"] = $this->Report_m->get_all_district();
        $data["issue_list"] = $this->Report_m->get_all_issue(1);
		$data["title"] = "No Or Same Resolution Report";
		$this->load->view('app/templates/header' , $data);
		$this->load->view('app/templates/side_panel' , $data);
		$this->load->view('reports/NoOrSameResolutionDetails/'.$page , $data);
		$this->load->view('app/templates/footer' , $data);
    }

    public function CopyPasteResolutionReport($page = "list"){
        $data = array();
		if(!file_exists(APPPATH.'views/reports/CopyPasteResolutionReport/'.$page.'.php'))
		{
		show_404();
		}
        $data["user_list"] = $this->Report_m->get_all_user_list();
        $data["department_list"] = $this->Report_m->get_all_departments();
        $data["district_list"] = $this->Report_m->get_all_district();
        $data["issue_list"] = $this->Report_m->get_all_issue(2);
		$data["title"] = "Copy paste Resolution Report";
		$this->load->view('app/templates/header' , $data);
		$this->load->view('app/templates/side_panel' , $data);
		$this->load->view('reports/CopyPasteResolutionReport/'.$page , $data);
		$this->load->view('app/templates/footer' , $data);
    }

    public function DirectionReport($page = "list"){
        $data = array();
		if(!file_exists(APPPATH.'views/reports/DirectionReport/'.$page.'.php'))
		{
		show_404();
		}
        $data["user_list"] = $this->Report_m->get_all_user_list();
        $data["department_list"] = $this->Report_m->get_all_departments();
        $data["district_list"] = $this->Report_m->get_all_district();
        $data["issue_list"] = $this->Report_m->get_all_issue(3);
		$data["title"] = "Direction Report";
		
		$this->load->view('app/templates/header' , $data);
		$this->load->view('app/templates/side_panel' , $data);
		$this->load->view('reports/DirectionReport/'.$page , $data);
		$this->load->view('app/templates/footer' , $data);
    }

    public function OwaForm($page = "owaform"){
        $data = array();
		if(!file_exists(APPPATH.'views/reports/OWAReport/'.$page.'.php'))
		{
		show_404();
		}
        
        $data["user_list"] = $this->Report_m->get_all_owa_user_list();
        $data["department_list"] = $this->Report_m->get_all_departments();
        $data["district_list"] = $this->Report_m->get_all_district();
        $data["attribute_list"] = $this->Report_m->get_all_attribute();
		$data["title"] = "OWA FORM";
		$this->load->view('app/templates/header' , $data);
		$this->load->view('app/templates/side_panel' , $data);
		$this->load->view('reports/OWAReport/'.$page , $data);
		$this->load->view('app/templates/footer' , $data);
    }

    public function OwaList($page = "owalist"){
        $data = array();
		if(!file_exists(APPPATH.'views/reports/OWAReport/'.$page.'.php'))
		{
		show_404();
		}
         $data["user_list"] = $this->Report_m->get_all_owa_user_list();
		$data["title"] = "OWA LIST";
		$this->load->view('app/templates/header' , $data);
		$this->load->view('app/templates/side_panel' , $data);
		$this->load->view('reports/OWAReport/'.$page , $data);
		$this->load->view('app/templates/footer' , $data);
    }

    public function TATManagement($page = "list"){
        $data = array();
		if(!file_exists(APPPATH.'views/reports/TATManagement/'.$page.'.php'))
		{
		    show_404();
		}
        $data["department_list"] = $this->Report_m->get_all_departments();
		$data["title"] = "TAT Management";
		$this->load->view('app/templates/header' , $data);
		$this->load->view('app/templates/side_panel' , $data);
		$this->load->view('reports/TATManagement/'.$page , $data);
		$this->load->view('app/templates/footer' , $data);
    }

    public function PSMAuditDashboard($page = "list"){
        $data = array();
		if(!file_exists(APPPATH.'views/reports/PSMAuditDashboard/'.$page.'.php'))
		{
		    show_404();
		}
        $data["department_list"] = $this->Report_m->get_live_departments();
        $data["district_list"] = $this->Report_m->get_live_districts();
        $data["officer_list"] = $this->Report_m->get_live_officers();
		$data["title"] = "PSM Audit Dashboard";
		$this->load->view('app/templates/header' , $data);
		$this->load->view('app/templates/side_panel' , $data);
		$this->load->view('reports/PSMAuditDashboard/'.$page , $data);
		$this->load->view('app/templates/footer' , $data);
    }

    private function psm_report_filters()
    {
        return array(
            "from_date" => $this->input->post("from_date", TRUE),
            "to_date" => $this->input->post("to_date", TRUE),
            "complaint_no" => $this->input->post("complaint_no", TRUE),
            "department" => $this->input->post("department", TRUE),
            "officer" => $this->input->post("officer", TRUE),
            "current_officer" => $this->input->post("current_officer", TRUE),
            "login_user_id" => $this->input->post("login_user_id", TRUE),
            "complaint_status" => $this->input->post("complaint_status", TRUE),
            "district" => $this->input->post("district", TRUE)
        );
    }

    public function get_psm_report_tab()
    {
        $data = array();
        if($this->input->is_ajax_request())
        {
            $report_type = $this->input->post("report_type", TRUE);
            $filters = $this->psm_report_filters();
            $data["response"] = TRUE;
            $data["report_type"] = $report_type;

            switch($report_type)
            {
                case "audit":
                    $data["all_record"] = $this->Report_m->get_psm_audit_report($filters);
                    break;
                case "history":
                    $data["all_record"] = $this->Report_m->get_psm_history_report($filters);
                    break;
                case "officer":
                    $data["all_record"] = $this->Report_m->get_psm_officer_analysis($filters);
                    break;
                case "department":
                    $data["all_record"] = $this->Report_m->get_psm_department_analysis($filters);
                    break;
                case "repeated":
                    $data["all_record"] = $this->Report_m->get_psm_top_repeated_complaints($filters);
                    break;
                case "same_officer":
                    $data["all_record"] = $this->Report_m->get_psm_same_officer_repeated_report($filters);
                    break;
                case "complaint_summary":
                    $data["all_record"] = $this->Report_m->get_psm_complaint_summary_report($filters);
                    break;
                default:
                    $data["response"] = FALSE;
                    $data["message"] = "Invalid report type.";
                    $data["all_record"] = array();
                    break;
            }

            if($data["response"] == TRUE)
            {
                $data["total_record"] = count($data["all_record"]);
                $data["message"] = $data["total_record"]." Record Found.";
            }
        }
        else
        {
            $data["response"] = FALSE;
            $data["message"] = "Invalid Request.";
        }
        echo json_encode($data);
    }

    public function get_psm_complaint_timeline()
    {
        $data = array();
        if($this->input->is_ajax_request())
        {
            $complaint_no = $this->input->post("complaint_no", TRUE);
            if(empty($complaint_no))
            {
                $data["response"] = FALSE;
                $data["message"] = "Complaint number is required.";
                echo json_encode($data);
                return;
            }

            $timeline = $this->Report_m->get_psm_complaint_timeline($complaint_no);
            $events = $timeline["events"];
            $data["response"] = TRUE;
            $data["complaint_no"] = $complaint_no;
            $data["summary"] = $timeline["summary"];
            $data["all_record"] = $events;
            $data["total_record"] = count($events);
            $data["message"] = count($events)." timeline record(s) found.";
        }
        else
        {
            $data["response"] = FALSE;
            $data["message"] = "Invalid Request.";
        }
        echo json_encode($data);
    }

    public function get_psm_audit_dashboard()
    {
        $data = array();
        if($this->input->is_ajax_request())
        {
            $filters = $this->psm_report_filters();

            $summary = $this->Report_m->get_psm_summary($filters);
            $data["response"] = TRUE;
            $data["summary"] = $summary;
            $data["audit"] = $this->Report_m->get_psm_audit_report($filters);
            $data["history"] = $this->Report_m->get_psm_history_report($filters);
            $data["officer_analysis"] = $this->Report_m->get_psm_officer_analysis($filters);
            $data["department_analysis"] = $this->Report_m->get_psm_department_analysis($filters);
            $data["top_repeated"] = $this->Report_m->get_psm_top_repeated_complaints($filters);
            $data["same_officer_repeated"] = $this->Report_m->get_psm_same_officer_repeated_report($filters);
            $data["complaint_summary"] = $this->Report_m->get_psm_complaint_summary_report($filters);
        }
        else
        {
            $data["response"] = FALSE;
            $data["message"] = "Invalid Request.";
        }
        echo json_encode($data);
    }

    public function SendToLowerLevelDashboard($page = "list"){
        $data = array();
		if(!file_exists(APPPATH.'views/reports/SendToLowerLevelDashboard/'.$page.'.php'))
		{
		    show_404();
		}
        $data["department_list"] = $this->Report_m->get_live_departments();
        $data["district_list"] = $this->Report_m->get_live_districts();
        $data["officer_list"] = $this->Report_m->get_live_officers();
		$data["title"] = "Send to Lower Level Dashboard";
		$this->load->view('app/templates/header' , $data);
		$this->load->view('app/templates/side_panel' , $data);
		$this->load->view('reports/SendToLowerLevelDashboard/'.$page , $data);
		$this->load->view('app/templates/footer' , $data);
    }

    private function lower_level_report_filters()
    {
        return array(
            "from_date" => $this->input->post("from_date", TRUE),
            "to_date" => $this->input->post("to_date", TRUE),
            "complaint_no" => $this->input->post("complaint_no", TRUE),
            "department" => $this->input->post("department", TRUE),
            "officer" => $this->input->post("officer", TRUE),
            "current_officer" => $this->input->post("current_officer", TRUE),
            "login_user_id" => $this->input->post("login_user_id", TRUE),
            "complaint_status" => $this->input->post("complaint_status", TRUE),
            "district" => $this->input->post("district", TRUE)
        );
    }

    public function get_lower_level_report_tab()
    {
        $data = array();
        if($this->input->is_ajax_request())
        {
            $report_type = $this->input->post("report_type", TRUE);
            $filters = $this->lower_level_report_filters();
            $data["response"] = TRUE;
            $data["report_type"] = $report_type;

            switch($report_type)
            {
                case "audit":
                    $data["all_record"] = $this->Report_m->get_lower_level_audit_report($filters);
                    break;
                case "history":
                    $data["all_record"] = $this->Report_m->get_lower_level_history_report($filters);
                    break;
                case "officer":
                    $data["all_record"] = $this->Report_m->get_lower_level_officer_report($filters);
                    break;
                case "same_officer":
                    $data["all_record"] = $this->Report_m->get_lower_level_same_officer_report($filters);
                    break;
                case "complaint_summary":
                    $data["all_record"] = $this->Report_m->get_lower_level_complaint_summary_report($filters);
                    break;
                default:
                    $data["response"] = FALSE;
                    $data["message"] = "Invalid report type.";
                    $data["all_record"] = array();
                    break;
            }

            if($data["response"] == TRUE)
            {
                $data["total_record"] = count($data["all_record"]);
                $data["message"] = $data["total_record"]." Record Found.";
            }
        }
        else
        {
            $data["response"] = FALSE;
            $data["message"] = "Invalid Request.";
        }
        echo json_encode($data);
    }

    public function get_lower_level_complaint_timeline()
    {
        $data = array();
        if($this->input->is_ajax_request())
        {
            $complaint_no = $this->input->post("complaint_no", TRUE);
            if(empty($complaint_no))
            {
                $data["response"] = FALSE;
                $data["message"] = "Complaint number is required.";
                echo json_encode($data);
                return;
            }

            $timeline = $this->Report_m->get_lower_level_complaint_timeline($complaint_no);
            $events = $timeline["events"];
            $data["response"] = TRUE;
            $data["complaint_no"] = $complaint_no;
            $data["summary"] = $timeline["summary"];
            $data["all_record"] = $events;
            $data["total_record"] = count($events);
            $data["message"] = count($events)." timeline record(s) found.";
        }
        else
        {
            $data["response"] = FALSE;
            $data["message"] = "Invalid Request.";
        }
        echo json_encode($data);
    }

    public function get_all_tat_mappings()
    {
        $data = array();
        if($this->input->is_ajax_request())
        {
            $r = $this->Report_m->get_tat_mappings();
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
                $data["message"] = "No TAT mapping found.";
            }
        }
        echo json_encode($data);
    }

    public function CallbackReport($page = "list"){
        $this->require_agent_report_access();
        $data = array();
		if(!file_exists(APPPATH.'views/reports/CallbackReport/'.$page.'.php'))
		{
		show_404();
		}
        $data["user_list"] = $this->Report_m->get_all_user_list();
        $data["department_list"] = $this->Report_m->get_all_departments();
		$data["title"] = "Callback Report";
		$this->load->view('app/templates/header' , $data);
		$this->load->view('app/templates/side_panel' , $data);
		$this->load->view('reports/CallbackReport/'.$page , $data);
		$this->load->view('app/templates/footer' , $data);
    }

    public function NameChangeReport($page = "list"){
        $this->require_agent_report_access();
        $data = array();
		if(!file_exists(APPPATH.'views/reports/NameChangeReport/'.$page.'.php'))
		{
		show_404();
		}
        $data["user_list"] = $this->Report_m->get_all_user_list();
		$data["title"] = "Name Change Report";
		$this->load->view('app/templates/header' , $data);
		$this->load->view('app/templates/side_panel' , $data);
		$this->load->view('reports/NameChangeReport/'.$page , $data);
		$this->load->view('app/templates/footer' , $data);
    }

    public function CreatationReport($page = "list"){
        $data = array();
		if(!file_exists(APPPATH.'views/reports/CreatationReport/'.$page.'.php'))
		{
		show_404();
		}
        $data["department_list"] = $this->Report_m->get_all_departments();
        $data["attribute_list"] = $this->Report_m->get_all_attribute();
        //print_r($data["attribute_list"]);die;
		$data["title"] = "Creatation Report";
		$this->load->view('app/templates/header' , $data);
		$this->load->view('app/templates/side_panel' , $data);
		$this->load->view('reports/CreatationReport/'.$page , $data);
		$this->load->view('app/templates/footer' , $data);
    }

    public function get_all_no_same_resolution_details()
    {
        $data = array();
        if($this->input->is_ajax_request())
        {
        $remark = (isset($_POST["remark"]))?$this->input->post("remark" , TRUE):"0";
        $shift = (isset($_POST["shift"]))?$this->input->post("shift" , TRUE):"morning";
        
        $r = $this->Report_m->get_all_no_same_resolution_details($shift,$remark);
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

    public function get_all_copypaste_details()
    {
        $data = array();
        if($this->input->is_ajax_request())
        {
        $remark = (isset($_POST["remark"]))?$this->input->post("remark" , TRUE):"0";
        $shift = (isset($_POST["shift"]))?$this->input->post("shift" , TRUE):"morning";
        $r = $this->Report_m->get_all_copypaste_details($shift, $remark);
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

    public function get_all_direction_details()
    {
        $data = array();
        if($this->input->is_ajax_request())
        {
            $remark = (isset($_POST["remark"]))?$this->input->post("remark" , TRUE):"0";
        $shift = (isset($_POST["shift"]))?$this->input->post("shift" , TRUE):"morning";
        $r = $this->Report_m->get_all_direction_details($shift,$remark);
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

    public function get_all_owa_details()
    {
        $data = array();
        if($this->input->is_ajax_request())
        {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $agent_id = $this->input->post('agent_id');
        $r = $this->Report_m->get_all_owa_details($start_date, $end_date , $agent_id);
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

    public function get_all_owa_details_old()
    {
        $data = array();
        if($this->input->is_ajax_request())
        {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $agent_id = $this->input->post('agent_id');
        $r = $this->Report_m->get_all_owa_details_old($start_date, $end_date , $agent_id);
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

    public function get_all_callback_details()
    {
        if(!$this->is_allowed($this->agent_report_roles)) { return $this->deny_json(); }
        $data = array();
        if($this->input->is_ajax_request())
        {
            $r = $this->Report_m->get_callback_reports($this->current_role_id(), $this->current_emp_id());
            if($r != FALSE)
            {
                $data["response"] = TRUE;
                $data["message"] = count($r)." Record Found.";
                $data["total_record"] = count($r);
                $data["all_record"] = $r;
                $data["emp_id"] = $this->current_emp_id();
            }
            else
            {
                $data["response"] = FALSE;
                $data["message"] = "No Record Found.";
            }
        }
        echo json_encode($data);
    }

    public function add_callback_details()
    {
        if(!$this->is_allowed($this->agent_report_roles)) { return $this->deny_json(); }
        $data = array();
        if ($this->input->post()) {
            $remark_type = $this->input->post('remark_type');
            $remark_other = $this->input->post('remark_other');
            $latest_remark = $this->normalize_callback_remark($remark_type, $remark_other);
            $agent_id = ($this->current_role_id() == 1) ? (int)$this->input->post('agent_id') : $this->current_emp_id();

            if(trim($latest_remark) == "")
            {
                $data["response"] = FALSE;
                $data["message"] = "Enter remark.";
                echo json_encode($data);
                return;
            }

            $details = $this->Report_m->insert_callback_report([
                'date'              => $this->input->post('date'),
                'phone_number'      => $this->input->post('phone_number'),
                'agent_id'          => $agent_id,
                'assigned_agent_id' => $agent_id,
                'department'        => $this->input->post('department'),
                'remark_type'       => $remark_type,
                'remark_other'      => ($remark_type == "Other") ? $remark_other : NULL,
                'latest_remark'     => $latest_remark,
                'created_by'        => $this->current_emp_id()
            ]);

            if ($details > 0) {
                $this->Report_m->insert_callback_history([
                    'callback_id'       => $details,
                    'action_type'       => 'create',
                    'new_agent_id'      => $agent_id,
                    'remark_type'       => $remark_type,
                    'remark_other'      => ($remark_type == "Other") ? $remark_other : NULL,
                    'remark_text'       => $latest_remark,
                    'updated_by'        => $this->current_emp_id()
                ]);
                $data["response"] = TRUE;
                $data["message"]  = "Callback details added.";
            } else {
                $data["response"] = FALSE;
                $data["message"]  = "Failed to add callback details.";
            }
        } else {
            $data["response"] = FALSE;
            $data["message"]  = "Invalid Request.";
        }
        echo json_encode($data);
    }

    public function update_callback_details()
    {
        if(!$this->is_allowed($this->agent_report_roles)) { return $this->deny_json(); }
        $data = array();
        if ($this->input->post()) {
            $id = (int)$this->input->post('hidden_id');
            $record = $this->Report_m->get_callback_report_by_id($id);
            if($record == FALSE)
            {
                $data["response"] = FALSE;
                $data["message"] = "Invalid callback record.";
                echo json_encode($data);
                return;
            }
            if($this->current_role_id() != 1 && (int)$record->assigned_agent_id !== $this->current_emp_id() && (int)$record->created_by !== $this->current_emp_id())
            {
                return $this->deny_json();
            }

            $new_agent_id = ($this->current_role_id() == 1) ? (int)$this->input->post('agent_id') : (int)$record->assigned_agent_id;
            $assignment_changed = ((int)$record->assigned_agent_id !== $new_agent_id);
            $remark_type = $this->input->post('remark_type');
            $remark_other = $this->input->post('remark_other');
            $has_remark = ($remark_type != "" && $remark_type != "0");
            $latest_remark = $has_remark ? $this->normalize_callback_remark($remark_type, $remark_other) : "";

            if($has_remark && trim($latest_remark) == "")
            {
                $data["response"] = FALSE;
                $data["message"] = "Enter remark.";
                echo json_encode($data);
                return;
            }
            if(!$has_remark && !$assignment_changed)
            {
                $data["response"] = FALSE;
                $data["message"] = "Select another agent or add a remark.";
                echo json_encode($data);
                return;
            }

            $update_arr = [
                'agent_id'          => $new_agent_id,
                'assigned_agent_id' => $new_agent_id,
                'updated_by'        => $this->current_emp_id(),
                'updated_at'        => date('Y-m-d H:i:s')
            ];
            if($has_remark)
            {
                $update_arr['remark_type'] = $remark_type;
                $update_arr['remark_other'] = ($remark_type == "Other") ? $remark_other : NULL;
                $update_arr['latest_remark'] = $latest_remark;
            }

            $updated = $this->Report_m->update_callback_report($id, $update_arr);
            if($assignment_changed)
            {
                $this->Report_m->insert_callback_history([
                    'callback_id'       => $id,
                    'action_type'       => 'assign',
                    'previous_agent_id' => (int)$record->assigned_agent_id,
                    'new_agent_id'      => $new_agent_id,
                    'updated_by'        => $this->current_emp_id()
                ]);
            }
            if($has_remark)
            {
                $this->Report_m->insert_callback_history([
                    'callback_id'       => $id,
                    'action_type'       => 'update',
                    'previous_agent_id' => (int)$record->assigned_agent_id,
                    'new_agent_id'      => $new_agent_id,
                    'remark_type'       => $remark_type,
                    'remark_other'      => ($remark_type == "Other") ? $remark_other : NULL,
                    'remark_text'       => $latest_remark,
                    'updated_by'        => $this->current_emp_id()
                ]);
            }

            $data["response"] = TRUE;
            if($assignment_changed && !$has_remark)
            {
                $data["message"] = "Callback assigned successfully.";
            }
            else
            {
                $data["message"] = ($updated > 0) ? "Callback details updated." : "History saved.";
            }
        } else {
            $data["response"] = FALSE;
            $data["message"] = "Invalid Request.";
        }
        echo json_encode($data);
    }

    public function get_callback_history()
    {
        //if(!$this->is_allowed(array(1))) { return $this->deny_json(); }
        $data = array();
        if($this->input->is_ajax_request())
        {
            $callback_id = (int)$this->input->post("callback_id");
            $r = $this->Report_m->get_callback_history($callback_id);
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
                $data["message"] = "No History Found.";
            }
        }
        echo json_encode($data);
    }

    public function get_all_name_change_details()
    {
        if(!$this->is_allowed($this->agent_report_roles)) { return $this->deny_json(); }
        $data = array();
        if($this->input->is_ajax_request())
        {
            $r = $this->Report_m->get_name_change_reports($this->current_role_id(), $this->current_emp_id());
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

    public function get_all_number_change_details()
    {
        if(!$this->is_allowed($this->agent_report_roles)) { return $this->deny_json(); }
        $data = array();
        if($this->input->is_ajax_request())
        {
            $r = $this->Report_m->get_number_change_reports($this->current_role_id(), $this->current_emp_id());
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

    public function add_name_change_details()
    {
        if(!$this->is_allowed($this->agent_report_roles)) { return $this->deny_json(); }
        $data = array();
        if ($this->input->post()) {
            $agent_id = ($this->current_role_id() == 1) ? (int)$this->input->post('agent_id') : $this->current_emp_id();
            $details = $this->Report_m->insert_name_change_report([
                'date'             => $this->input->post('date'),
                'phone_number'     => $this->input->post('phone_number'),
                'complaint_number' => $this->input->post('complaint_number'),
                'old_name'         => $this->input->post('old_name'),
                'new_name'         => $this->input->post('new_name'),
                'agent_id'         => $agent_id,
                'remark'           => 'Needs to be updated',
                'created_by'       => $this->current_emp_id()
            ]);

            if ($details > 0) {
                $data["response"] = TRUE;
                $data["message"]  = "Name change details added.";
            } else {
                $data["response"] = FALSE;
                $data["message"]  = "Failed to add details.";
            }
        } else {
            $data["response"] = FALSE;
            $data["message"]  = "Invalid Request.";
        }
        echo json_encode($data);
    }


     public function add_details_of_no_same_resolutions()
    {
        if(!$this->is_allowed($this->add_roles)) { return $this->deny_json(); }
        if ($this->input->post()) {

            $details = $this->Report_m->insert_no_same_resolutions([
                'date'        => $this->input->post('date'),
                'agent_id' => $this->input->post('agent_id'),
                'complaint_no' => $this->input->post('complaint_no'),
                'department'      => $this->input->post('department'),
                'district'       => $this->input->post('district'),
                'issue'         => $this->input->post('issue'),
                'complaint_status' => $this->normalize_complaint_status($this->input->post('complaint_status', TRUE)),
                'added_by'    => $_SESSION["userdata"]["emp_id"]
            ]);

            if ($details > 0) {
                $record = $this->Report_m->get_resolution_record('no_same_resolution_report', $details);
                $this->add_resolution_history('no_same', $record, NULL, $record->complaint_status, 'create');

                $data["response"] = true;
                $data["message"]  = "Details Added.";

            } else {

                $data["response"] = false;
                $data["message"]  = "Failed to add details.";
            }

            echo json_encode($data);
            return;

        } else {

            $data["response"] = false;
            $data["message"]  = "Invalid Request.";

            echo json_encode($data);
        }
    }

     public function add_details_of_copypaste_resolutions()
    {
        if(!$this->is_allowed($this->add_roles)) { return $this->deny_json(); }
        if ($this->input->post()) {
            
            $details = $this->Report_m->insert_copypaste_resolutions([
                'date'        => $this->input->post('date'),
                'agent_id' => $this->input->post('agent_id'),
                'complaint_no' => $this->input->post('complaint_no'),
                'department'      => $this->input->post('department'),
                'district'       => $this->input->post('district'),
                'issue'         => $this->input->post('issue'),
                'complaint_status' => $this->normalize_complaint_status($this->input->post('complaint_status', TRUE)),
                'added_by'    => $_SESSION["userdata"]["emp_id"]
            ]);
            

            if ($details > 0) {
                $record = $this->Report_m->get_resolution_record('copy_paste_wrong_resolution_report', $details);
                $this->add_resolution_history('copy_paste', $record, NULL, $record->complaint_status, 'create');

                $data["response"] = true;
                $data["message"]  = "Details Added.";

            } else {

                $data["response"] = false;
                $data["message"]  = "Failed to add details.";
            }

            echo json_encode($data);
            return;

        } else {

            $data["response"] = false;
            $data["message"]  = "Invalid Request.";

            echo json_encode($data);
        }
    }




    

public function add_details_of_direction_resolutions()
    {
        if(!$this->is_allowed($this->add_roles)) { return $this->deny_json(); }
        if ($this->input->post()) {
            
            $details = $this->Report_m->insert_direction_resolutions([
                'date'        => $this->input->post('date'),
                'agent_id' => $this->input->post('agent_id'),
                'complaint_no' => $this->input->post('complaint_no'),
                'department'      => $this->input->post('department'),
                'district'       => $this->input->post('district'),
                'issue'         => $this->input->post('issue'),
                'complaint_status' => $this->normalize_complaint_status($this->input->post('complaint_status', TRUE)),
                'added_by'    => $_SESSION["userdata"]["emp_id"]
            ]);
            

            if ($details > 0) {
                $record = $this->Report_m->get_resolution_record('direction_report', $details);
                $this->add_resolution_history('direction', $record, NULL, $record->complaint_status, 'create');

                $data["response"] = true;
                $data["message"]  = "Details Added.";

            } else {

                $data["response"] = false;
                $data["message"]  = "Failed to add details.";
            }

            echo json_encode($data);
            return;

        } else {

            $data["response"] = false;
            $data["message"]  = "Invalid Request.";

            echo json_encode($data);
        }
    }


   

   public function update_details_of_copypaste_resolutions()
    {
        if(!$this->is_allowed($this->update_roles)) { return $this->deny_json(); }
        if ($this->input->post()) {
            $id = (int)$this->input->post('hidden_id');
            $old_record = $this->Report_m->get_resolution_record('copy_paste_wrong_resolution_report', $id);
            
            $details = $this->Report_m->update_copypaste_resolutions([
                'call_date'         => $this->input->post('call_date'),
                'helpdesk_remark'   => $this->input->post('helpdesk_remark'),
                'remark'   => $this->input->post('other_remark'),
                'complaint_status' => $this->normalize_complaint_status($this->input->post('complaint_status', TRUE)),
                'officer_mobile_no' => $this->input->post('officer_mobile_no'),
                'updated_by'        => $_SESSION["userdata"]["emp_id"],
                'updated_at'        => date('Y-m-d H:i:s')
            ], $id);
            

            if ($details > 0) {
                $new_record = $this->Report_m->get_resolution_record('copy_paste_wrong_resolution_report', $id);
                $this->add_resolution_history('copy_paste', $new_record, $old_record ? $old_record->complaint_status : NULL, $new_record->complaint_status, 'update');
                $data["response"] = true;
                $data["message"]  = "Details Updated.";

            } else {

                $data["response"] = false;
                $data["message"]  = "Failed to Update details.";
            }

            echo json_encode($data);
            return;

        } else {

            $data["response"] = false;
            $data["message"]  = "Invalid Request.";

            echo json_encode($data);
        }
    }

    public function update_details_of_no_or_same_resolutions()
    {
        if(!$this->is_allowed($this->update_roles)) { return $this->deny_json(); }
        if ($this->input->post()) {
            $id = (int)$this->input->post('hidden_id');
            $old_record = $this->Report_m->get_resolution_record('no_same_resolution_report', $id);
           
            $details = $this->Report_m->update_no_or_same_resolutions([
                'call_date'         => $this->input->post('call_date'),
                'helpdesk_remark'   => $this->input->post('helpdesk_remark'),
                'remark'   => $this->input->post('other_remark'),
                'complaint_status' => $this->normalize_complaint_status($this->input->post('complaint_status', TRUE)),
                'officer_mobile_no' => $this->input->post('officer_mobile_no'),
                'updated_by'        => $_SESSION["userdata"]["emp_id"],
                'updated_at'        => date('Y-m-d H:i:s')
            ], $id);
            

            if ($details > 0) {
                $new_record = $this->Report_m->get_resolution_record('no_same_resolution_report', $id);
                $this->add_resolution_history('no_same', $new_record, $old_record ? $old_record->complaint_status : NULL, $new_record->complaint_status, 'update');
                $data["response"] = true;
                $data["message"]  = "Details Updated.";

            } else {

                $data["response"] = false;
                $data["message"]  = "Failed to Update details.";
            }

            echo json_encode($data);
            return;

        } else {

            $data["response"] = false;
            $data["message"]  = "Invalid Request.";

            echo json_encode($data);
        }
    }

    public function update_details_of_direction_resolutions()
    {
        if(!$this->is_allowed($this->update_roles)) { return $this->deny_json(); }
        if ($this->input->post()) {
            $id = (int)$this->input->post('hidden_id');
            $old_record = $this->Report_m->get_resolution_record('direction_report', $id);
            
            $details = $this->Report_m->update_direction_resolutions([
                'call_date'         => $this->input->post('call_date'),
               'helpdesk_remark'   => $this->input->post('helpdesk_remark'),
                'remark'   => $this->input->post('other_remark'),
                'complaint_status' => $this->normalize_complaint_status($this->input->post('complaint_status', TRUE)),
                'officer_mobile_no' => $this->input->post('officer_mobile_no'),
                'updated_by'        => $_SESSION["userdata"]["emp_id"],
                'updated_at'        => date('Y-m-d H:i:s')
            ], $id);
            

            if ($details > 0) {
                $new_record = $this->Report_m->get_resolution_record('direction_report', $id);
                $this->add_resolution_history('direction', $new_record, $old_record ? $old_record->complaint_status : NULL, $new_record->complaint_status, 'update');
                $data["response"] = true;
                $data["message"]  = "Details Updated.";

            } else {

                $data["response"] = false;
                $data["message"]  = "Failed to Update details.";
            }

            echo json_encode($data);
            return;

        } else {

            $data["response"] = false;
            $data["message"]  = "Invalid Request.";

            echo json_encode($data);
        }
    }

    public function update_resolution_complaint_status()
    {
        if(!$this->is_allowed($this->update_roles)) { return $this->deny_json(); }
        $data = array();
        if($this->input->post())
        {
            $report_key = $this->input->post('report_key', TRUE);
            $id = (int)$this->input->post('record_id');
            $new_status = $this->normalize_complaint_status($this->input->post('complaint_status', TRUE));
            $meta = $this->resolution_report_meta($report_key);
            if($meta == FALSE || $id <= 0)
            {
                $data["response"] = FALSE;
                $data["message"] = "Invalid request.";
                echo json_encode($data);
                return;
            }

            $old_record = $this->Report_m->get_resolution_record($meta['table'], $id);
            if($old_record == FALSE)
            {
                $data["response"] = FALSE;
                $data["message"] = "Invalid record.";
                echo json_encode($data);
                return;
            }

            $update_method = $meta['update_method'];
            $updated = $this->Report_m->$update_method(array(
                'complaint_status' => $new_status,
                'updated_by' => $this->current_emp_id(),
                'updated_at' => date('Y-m-d H:i:s')
            ), $id);

            $new_record = $this->Report_m->get_resolution_record($meta['table'], $id);
            $this->add_resolution_history($report_key, $new_record, $old_record->complaint_status, $new_status, 'status_popup');

            $data["response"] = TRUE;
            $data["message"] = ($updated > 0) ? "Complaint status updated." : "No status change detected.";
        }
        else
        {
            $data["response"] = FALSE;
            $data["message"] = "Invalid Request.";
        }
        echo json_encode($data);
    }

    public function get_resolution_complaint_history()
    {
        if(!$this->is_allowed(array(1, 2, 3, 4))) { return $this->deny_json(); }
        $data = array();
        if($this->input->is_ajax_request())
        {
            $report_key = $this->input->post('report_key', TRUE);
            $id = (int)$this->input->post('record_id');
            $meta = $this->resolution_report_meta($report_key);
            if($meta == FALSE || $id <= 0)
            {
                $data["response"] = FALSE;
                $data["message"] = "Invalid request.";
                echo json_encode($data);
                return;
            }

            $record = $this->Report_m->get_resolution_record($meta['table'], $id);
            if($record == FALSE)
            {
                $data["response"] = FALSE;
                $data["message"] = "Invalid record.";
                echo json_encode($data);
                return;
            }

            $history = $this->Report_m->get_complaint_status_history($report_key, $id);
            if($history != FALSE)
            {
                $data["response"] = TRUE;
                $data["message"] = count($history)." history record(s) found.";
                $data["total_record"] = count($history);
                $data["all_record"] = $history;
                $data["current_status"] = $record->complaint_status;
                $data["complaint_no"] = $record->complaint_no;
                $data["report_name"] = $meta['name'];
            }
            else
            {
                $data["response"] = FALSE;
                $data["message"] = "No history found for this complaint.";
                $data["current_status"] = $record->complaint_status;
                $data["complaint_no"] = $record->complaint_no;
                $data["report_name"] = $meta['name'];
            }
        }
        echo json_encode($data);
    }


    public function add_owa_details()
    {
        if(!$this->is_allowed($this->add_roles)) { return $this->deny_json(); }
        if ($this->input->post()) {

            $details = $this->Report_m->insert_owa_details([
                'date'        => $this->input->post('date'),
                'agent_id' => $this->input->post('agent_id'),
                'complaint_no' => $this->input->post('complaint_no'),
                'new_department'      => $this->input->post('new_department'),
                'old_department'      => $this->input->post('old_department'),
                'new_attribute'      => $this->input->post('new_attribute'),
                'old_attribute'      => $this->input->post('old_attribute'),
                'owa_reason'       => $this->input->post('owa_reason'),
                'sme_remark'       => $this->input->post('sme_remark'),
                'other_owa_reason'         => $this->input->post('other_owa_reason'),
                'other_agent'         => $this->input->post('other_agent'),
                'added_by'    => $_SESSION["userdata"]["emp_id"]
            ]);

            if ($details > 0) {

                $data["response"] = true;
                $data["message"]  = "Details Added.";

            } else {

                $data["response"] = false;
                $data["message"]  = "Failed to add details.";
            }

            echo json_encode($data);
            return;

        } else {

            $data["response"] = false;
            $data["message"]  = "Invalid Request.";

            echo json_encode($data);
        }
    }

    public function add_complaint_details()
    {
        // if(!$this->is_allowed($this->agent_report_roles)) { return $this->deny_json(); }
        $data = array();
        
        if ($this->input->post()) {
            $comp_type = $this->input->post('comp_type');
            $agent_id = ($this->current_role_id() == 1) ? (int)$this->input->post('agent_id') : $this->current_emp_id();
            if($comp_type == "1")
            {
                if ($this->Report_m->check_duplicate($this->input->post('complaint_no'),'complaint_creation') > 0) { 
                    $data["response"] = FALSE;
                    $data["message"]  = "Complaint Number Already exist.";
                }else{
                    $details = $this->Report_m->insert_complaint_details([
                    'comp_id' => $this->input->post('complaint_no'),
                    'department'         => $this->input->post('department'),
                    'attribute'         => $this->input->post('attribute'),
                    'added_by'       => $this->current_emp_id()
                    ]);
                    if ($details > 0) {
                        $data["response"] = TRUE;
                        $data["message"]  = "Detail Add Successfully.";
                    } else {
                        $data["response"] = FALSE;
                        $data["message"]  = "Failed to add details.";
                    }
                }
                
            }
            else
            {   
                if ($this->Report_m->check_duplicate($this->input->post('complaint_no'),'demand_suggestion_creation') > 0) { 
                    $data["response"] = FALSE;
                    $data["message"]  = "Complaint Number Already exist.";
                }else{
                    $details = $this->Report_m->insert_demandsuggestion_complaint_details([
                        'comp_type' => $this->input->post('comp_type'),
                        'comp_id' => $this->input->post('complaint_no'),
                        'department'         => $this->input->post('department'),
                        'attribute'         => $this->input->post('attribute'),
                        'added_by'       => $this->current_emp_id()
                    ]);
                    if ($details > 0) {
                        $data["response"] = TRUE;
                        $data["message"]  = "Detail Add Successfully.";
                    } else {
                        $data["response"] = FALSE;
                        $data["message"]  = "Failed to add details.";
                    }
                }
                
            }
        } else {
            $data["response"] = FALSE;
            $data["message"]  = "Invalid Request.";
        }
        echo json_encode($data);
    }

    public function add_high_complaint_details()
    {
        // if(!$this->is_allowed($this->agent_report_roles)) { return $this->deny_json(); }
        $data = array();
        if ($this->input->post()) {
            $agent_id = ($this->current_role_id() == 1) ? (int)$this->input->post('agent_id') : $this->current_emp_id();
            $details = $this->Report_m->insert_high_complaint_details([
                'comp_id' => $this->input->post('other_complaint_no'),
                'type' => $this->input->post('type'),
                'added_by'       => $this->current_emp_id()
            ]);

            if ($details > 0) {
                $data["response"] = TRUE;
                $data["message"]  = "Hight rated Complaint details added.";
            } else {
                $data["response"] = FALSE;
                $data["message"]  = "Failed to add details.";
            }
        } else {
            $data["response"] = FALSE;
            $data["message"]  = "Invalid Request.";
        }
        echo json_encode($data);
    }

    public function get_all_complaints()
    {
        // if(!$this->is_allowed($this->agent_report_roles)) { return $this->deny_json(); }
        $data = array();
        if($this->input->is_ajax_request())
        {
            $from_date = $this->input->post("from_date");
            $to_date   = $this->input->post("to_date");
           
           $r = $this->Report_m->get_all_complaints($this->current_role_id(),
                $this->current_emp_id(),
                $from_date,
                $to_date
            );
            
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

    public function update_name_change_detail()
    {
        if(!$this->is_allowed($this->update_roles)) { return $this->deny_json(); }
        if ($this->input->post()) {
            $details = $this->Report_m->update_name_change_detail([
                'remark' => 'DONE',
                'updated_by'        => $_SESSION["userdata"]["emp_id"],
                'updated_at'        => date('Y-m-d H:i:s')
            ], $this->input->post('id'));
            

            if ($details > 0) {
                $data["response"] = true;
                $data["message"]  = "Details Updated.";

            } else {

                $data["response"] = false;
                $data["message"]  = "Failed to Update details.";
            }

            echo json_encode($data);
            return;

        } else {

            $data["response"] = false;
            $data["message"]  = "Invalid Request.";

            echo json_encode($data);
        }
    }

    public function update_number_change_detail()
    {
        if(!$this->is_allowed($this->update_roles)) { return $this->deny_json(); }
        if ($this->input->post()) {
            $details = $this->Report_m->update_number_change_detail([
                'updated_by'        => $_SESSION["userdata"]["emp_id"],
                'updated_at'        => date('Y-m-d H:i:s')
            ], $this->input->post('id'));
            

            if ($details > 0) {
                $data["response"] = true;
                $data["message"]  = "Details Updated.";

            } else {

                $data["response"] = false;
                $data["message"]  = "Failed to Update details.";
            }

            echo json_encode($data);
            return;

        } else {

            $data["response"] = false;
            $data["message"]  = "Invalid Request.";

            echo json_encode($data);
        }
    }

    public function RemarkFormats($page = "list"){
        $data = array();
		if(!file_exists(APPPATH.'views/reports/RemarkFormats/'.$page.'.php'))
		{
		show_404();
		}
		$data["title"] = "Remark Formats";
		$this->load->view('app/templates/header' , $data);
		$this->load->view('app/templates/side_panel' , $data);
		$this->load->view('reports/RemarkFormats/'.$page , $data);
		$this->load->view('app/templates/footer' , $data);
    }

    private function normalize_text_color($color, $default)
    {
        $color = trim((string)$color);
        return preg_match('/^#[0-9a-fA-F]{6}$/', $color) ? strtoupper($color) : $default;
    }

    private function validate_remark_format_payload($title, $subtitle, $remark_content, $title_color, $subtitle_color)
    {
        if(trim($title) == "")
        {
            return "Enter title.";
        }
        if(strlen(trim($title)) > 150)
        {
            return "Title cannot exceed 150 characters.";
        }
        if(trim($subtitle) == "")
        {
            return "Enter subtitle.";
        }
        if(strlen(trim($subtitle)) > 200)
        {
            return "Subtitle cannot exceed 200 characters.";
        }
        $readable_content = trim(preg_replace('/\s+/', ' ', str_replace("\xc2\xa0", ' ', html_entity_decode(strip_tags((string)$remark_content), ENT_QUOTES, 'UTF-8'))));
        if($readable_content == "")
        {
            return "Enter description.";
        }
        if(!preg_match('/^#[0-9a-fA-F]{6}$/', $title_color))
        {
            return "Select a valid title color.";
        }
        if(!preg_match('/^#[0-9a-fA-F]{6}$/', $subtitle_color))
        {
            return "Select a valid subtitle color.";
        }
        return "";
    }

    public function get_all_remark_formats()
    {
        $data = array();
        if($this->input->is_ajax_request())
        {
            $r = $this->Report_m->get_remark_formats($this->current_emp_id(),$this->current_role_id());
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
                $data["message"] = "No saved remark formats found.";
            }
        }
        echo json_encode($data);
    }

    public function add_remark_format()
    {
        $data = array();
        if ($this->input->post()) {
            $title = $this->input->post('title', TRUE);
            $subtitle = $this->input->post('subtitle', TRUE);
            $remark_content = $this->input->post('remark_content');
            $title_color = $this->normalize_text_color($this->input->post('title_color', TRUE), '#212529');
            $subtitle_color = $this->normalize_text_color($this->input->post('subtitle_color', TRUE), '#6C757D');
            $validation_message = $this->validate_remark_format_payload($title, $subtitle, $remark_content, $title_color, $subtitle_color);
            if($validation_message != "")
            {
                $data["response"] = FALSE;
                $data["message"] = $validation_message;
                echo json_encode($data);
                return;
            }

            $details = $this->Report_m->insert_remark_format([
                'title'          => trim($title),
                'subtitle'       => trim($subtitle),
                'remark_content' => $remark_content,
                'title_color'    => $title_color,
                'subtitle_color' => $subtitle_color,
                'created_by'     => $this->current_emp_id()
            ]);

            if ($details > 0) {
                $data["response"] = TRUE;
                $data["message"]  = "Remark format saved.";
            } else {
                $data["response"] = FALSE;
                $data["message"]  = "Failed to save remark format.";
            }
        } else {
            $data["response"] = FALSE;
            $data["message"]  = "Invalid Request.";
        }
        echo json_encode($data);
    }

    public function update_remark_format()
    {
        $data = array();
        if ($this->input->post()) {
            $id = (int)$this->input->post('hidden_id');
            $record = $this->Report_m->get_remark_format_by_id($id, $this->current_emp_id());
            if($record == FALSE)
            {
                $data["response"] = FALSE;
                $data["message"] = "Invalid remark format.";
                echo json_encode($data);
                return;
            }

            $title = $this->input->post('title', TRUE);
            $subtitle = $this->input->post('subtitle', TRUE);
            $remark_content = $this->input->post('remark_content');
            $title_color = $this->normalize_text_color($this->input->post('title_color', TRUE), '#212529');
            $subtitle_color = $this->normalize_text_color($this->input->post('subtitle_color', TRUE), '#6C757D');
            $validation_message = $this->validate_remark_format_payload($title, $subtitle, $remark_content, $title_color, $subtitle_color);
            if($validation_message != "")
            {
                $data["response"] = FALSE;
                $data["message"] = $validation_message;
                echo json_encode($data);
                return;
            }

            $updated = $this->Report_m->update_remark_format($id, $this->current_emp_id(), [
                'title'          => trim($title),
                'subtitle'       => trim($subtitle),
                'remark_content' => $remark_content,
                'title_color'    => $title_color,
                'subtitle_color' => $subtitle_color,
                'updated_by'     => $this->current_emp_id(),
                'updated_at'     => date('Y-m-d H:i:s')
            ]);

            $data["response"] = TRUE;
            $data["message"] = ($updated > 0) ? "Remark format updated." : "No changes detected.";
        } else {
            $data["response"] = FALSE;
            $data["message"] = "Invalid Request.";
        }
        echo json_encode($data);
    }

    public function delete_remark_format()
    {
        $data = array();
        if ($this->input->post()) {
            $id = (int)$this->input->post('id');
            $record = $this->Report_m->get_remark_format_by_id($id, $this->current_emp_id());
            if($record == FALSE)
            {
                $data["response"] = FALSE;
                $data["message"] = "Invalid remark format.";
                echo json_encode($data);
                return;
            }

            $deleted = $this->Report_m->update_remark_format($id, $this->current_emp_id(), [
                'status'     => 0,
                'deleted_by' => $this->current_emp_id(),
                'deleted_at' => date('Y-m-d H:i:s')
            ]);

            $data["response"] = ($deleted > 0);
            $data["message"] = ($deleted > 0) ? "Remark format deleted." : "Failed to delete remark format.";
        } else {
            $data["response"] = FALSE;
            $data["message"] = "Invalid Request.";
        }
        echo json_encode($data);
    }

    public function get_attribute()
    {
        $data = array();
        if($this->input->is_ajax_request())
        {
            $department = (int)$this->input->post('department');
            $r = $this->Report_m->get_attribute($department);
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
                $data["message"] = "No saved remark formats found.";
            }
        }
        echo json_encode($data);
    }

    public function get_other_related_complaints()
    {
        // if(!$this->is_allowed($this->agent_report_roles)) { return $this->deny_json(); }
        $data = array();
        if($this->input->is_ajax_request())
        {
            $from_date = $this->input->post("from_date");
            $to_date   = $this->input->post("to_date");
           
           $r = $this->Report_m->get_other_related_complaints($this->current_role_id(),
                $this->current_emp_id()
            );
            
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

    public function DisasterReport($page = "list"){
        $this->require_agent_report_access();
        $data = array();
		if(!file_exists(APPPATH.'views/reports/DisasterReport/'.$page.'.php'))
		{
		show_404();
		}
        $data["user_list"] = $this->Report_m->get_all_user_list();
        $data["district_list"] = $this->Report_m->get_all_districts();
		$data["title"] = "Disaster Report";
		$this->load->view('app/templates/header' , $data);
		$this->load->view('app/templates/side_panel' , $data);
		$this->load->view('reports/DisasterReport/'.$page , $data);
		$this->load->view('app/templates/footer' , $data);
    }

    public function add_disaster_details()
    {
        // if(!$this->is_allowed($this->agent_report_roles)) { return $this->deny_json(); }
        $data = array();
        if ($this->input->post()) {
            
            $details = $this->Report_m->insert_disaster_details([
                'date' => $this->input->post('date'),
                'case_no'         => $this->input->post('case_no'),
                'sub_situation' => $this->input->post('sub_situation'),
                'citizen_name'  => $this->input->post('citizen_name'),
                'citizen_mobile_no' => $this->input->post('citizen_mobile_no'),
                'district'         => $this->input->post('district'),
                'case_remark'         => $this->input->post('case_remark'),
                'other_remark'         => $this->input->post('other_remark'),
                'added_by'       => $this->input->post('agent_id'),
                'added_at'       => date('Y-m-d H:i:s'),
                
            ]);

            if ($details > 0) {
                $data["response"] = TRUE;
                $data["message"]  = "Case details added.";
            } else {
                $data["response"] = FALSE;
                $data["message"]  = "Failed to add details.";
            }
        } else {
            $data["response"] = FALSE;
            $data["message"]  = "Invalid Request.";
        }
        echo json_encode($data);
    }

        public function get_all_disasters_details()
    {
        // if(!$this->is_allowed($this->agent_report_roles)) { return $this->deny_json(); }
        $data = array();
        if($this->input->is_ajax_request())
        {
            $from_date = $this->input->post("from_date");
            $to_date   = $this->input->post("to_date");
           
           $r = $this->Report_m->get_all_disasters_details();
            
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

    private function clean_contact_numbers($numbers)
    {
        if(!is_array($numbers))
        {
            $numbers = explode(",", (string)$numbers);
        }

        $clean = array();
        foreach($numbers as $number)
        {
            $number = trim($number);
            if($number !== "")
            {
                $clean[] = $number;
            }
        }

        return implode(", ", $clean);
    }

    public function get_all_isat_number_deocs()
    {
        $data = array();
        if($this->input->is_ajax_request())
        {
            $r = $this->Report_m->get_all_isat_number_deocs();
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
        else
        {
            $data["response"] = FALSE;
            $data["message"] = "Invalid Request.";
        }
        echo json_encode($data);
    }

    public function save_isat_number_deocs()
    {
        $data = array();
        if($this->input->is_ajax_request() && $this->input->post())
        {
            $id = (int)$this->input->post("id");
            $save_data = array(
                "district_officer" => trim($this->input->post("district_officer")),
                "mobile_number"    => trim($this->input->post("mobile_number")),
                "other_number"     => trim($this->input->post("other_number"))
            );

            if($save_data["district_officer"] == "" || $save_data["mobile_number"] == "")
            {
                echo json_encode(array("response" => FALSE, "message" => "District Officer and Mobile Number are required."));
                return;
            }

            if($id > 0)
            {
                $save_data["update_by"] = $this->current_emp_id();
                $save_data["updated_at"] = date("Y-m-d H:i:s");
                $saved = $this->Report_m->update_isat_number_deocs($id, $save_data);
                $data["response"] = ($saved >= 0);
                $data["message"] = ($saved >= 0) ? "ISAT/DEOC number updated." : "Failed to update ISAT/DEOC number.";
            }
            else
            {
                $save_data["added_by"] = $this->current_emp_id();
                $save_data["added_at"] = date("Y-m-d H:i:s");
                $saved = $this->Report_m->insert_isat_number_deocs($save_data);
                $data["response"] = ($saved > 0);
                $data["message"] = ($saved > 0) ? "ISAT/DEOC number added." : "Failed to add ISAT/DEOC number.";
            }
        }
        else
        {
            $data["response"] = FALSE;
            $data["message"] = "Invalid Request.";
        }
        echo json_encode($data);
    }

    public function delete_isat_number_deocs()
    {
        $data = array();
        if($this->input->is_ajax_request() && $this->input->post("id"))
        {
            $deleted = $this->Report_m->delete_isat_number_deocs((int)$this->input->post("id"), array(
                "status" => 0,
                "update_by" => $this->current_emp_id(),
                "updated_at" => date("Y-m-d H:i:s")
            ));
            $data["response"] = ($deleted > 0);
            $data["message"] = ($deleted > 0) ? "ISAT/DEOC number deleted." : "Failed to delete ISAT/DEOC number.";
        }
        else
        {
            $data["response"] = FALSE;
            $data["message"] = "Invalid Request.";
        }
        echo json_encode($data);
    }

    public function get_all_deocs_numbers()
    {
        $data = array();
        if($this->input->is_ajax_request())
        {
            $r = $this->Report_m->get_all_deocs_numbers();
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
        else
        {
            $data["response"] = FALSE;
            $data["message"] = "Invalid Request.";
        }
        echo json_encode($data);
    }

    public function save_deocs_numbers()
    {
        $data = array();
        if($this->input->is_ajax_request() && $this->input->post())
        {
            $id = (int)$this->input->post("id");
            $save_data = array(
                "eocs_name"        => trim($this->input->post("eocs_name")),
                "incharge_name"    => trim($this->input->post("incharge_name")),
                "incharge_mobile"  => trim($this->input->post("incharge_mobile")),
                "other_numbers"    => $this->clean_contact_numbers($this->input->post("other_numbers")),
                "toll_free_no"     => trim($this->input->post("toll_free_no"))
            );

            if($save_data["eocs_name"] == "" || $save_data["incharge_name"] == "" || $save_data["incharge_mobile"] == "" || $save_data["other_numbers"] == "" || $save_data["toll_free_no"] == "")
            {
                echo json_encode(array("response" => FALSE, "message" => "All DEOC number fields are required."));
                return;
            }

            if($id > 0)
            {
                $save_data["updated_by"] = $this->current_emp_id();
                $save_data["updated_at"] = date("Y-m-d H:i:s");
                $saved = $this->Report_m->update_deocs_numbers($id, $save_data);
                $data["response"] = ($saved >= 0);
                $data["message"] = ($saved >= 0) ? "DEOC number updated." : "Failed to update DEOC number.";
            }
            else
            {
                $save_data["added_by"] = $this->current_emp_id();
                $save_data["added_at"] = date("Y-m-d H:i:s");
                $saved = $this->Report_m->insert_deocs_numbers($save_data);
                $data["response"] = ($saved > 0);
                $data["message"] = ($saved > 0) ? "DEOC number added." : "Failed to add DEOC number.";
            }
        }
        else
        {
            $data["response"] = FALSE;
            $data["message"] = "Invalid Request.";
        }
        echo json_encode($data);
    }

    public function delete_deocs_numbers()
    {
        $data = array();
        if($this->input->is_ajax_request() && $this->input->post("id"))
        {
            $deleted = $this->Report_m->delete_deocs_numbers((int)$this->input->post("id"), array(
                "status" => 0,
                "updated_by" => $this->current_emp_id(),
                "updated_at" => date("Y-m-d H:i:s")
            ));
            $data["response"] = ($deleted > 0);
            $data["message"] = ($deleted > 0) ? "DEOC number deleted." : "Failed to delete DEOC number.";
        }
        else
        {
            $data["response"] = FALSE;
            $data["message"] = "Invalid Request.";
        }
        echo json_encode($data);
    }

    public function PotralIssueReport($page = "list"){
        $this->require_agent_report_access();
        $data = array();
		if(!file_exists(APPPATH.'views/reports/PotralIssueReport/'.$page.'.php'))
		{
		show_404();
		}
        $data["user_list"] = $this->Report_m->get_all_user_list();
        $data["district_list"] = $this->Report_m->get_all_districts();
		$data["title"] = "Portal Issues Report";
		$this->load->view('app/templates/header' , $data);
		$this->load->view('app/templates/side_panel' , $data);
		$this->load->view('reports/PotralIssueReport/'.$page , $data);
		$this->load->view('app/templates/footer' , $data);
    }

    public function get_all_portal_issue_details()
    {
        $data = array();
        if($this->input->is_ajax_request())
        {
            $r = $this->Report_m->get_all_portal_issue_details();
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
        else
        {
            $data["response"] = FALSE;
            $data["message"] = "Invalid Request.";
        }
        echo json_encode($data);
    }

    public function add_portal_issue_details()
    {
        // if(!$this->is_allowed($this->agent_report_roles)) { return $this->deny_json(); }
        $data = array();
        if ($this->input->post()) {
            
            $details = $this->Report_m->insert_portal_issue_details([
                'date' => $this->input->post('date'),
                'case_reason'         => $this->input->post('case_reason'),
                'reason_case_not_registered' => $this->input->post('reason_case_not_registered'),
                'other_remark'         => $this->input->post('other_remark'),
                'added_by'       => $this->input->post('agent_id'),
                'added_at'       => date('Y-m-d H:i:s'),
            ]);

            if ($details > 0) {
                $data["response"] = TRUE;
                $data["message"]  = "Case details added.";
            } else {
                $data["response"] = FALSE;
                $data["message"]  = "Failed to add details.";
            }
        } else {
            $data["response"] = FALSE;
            $data["message"]  = "Invalid Request.";
        }
        echo json_encode($data);
    }
    
    public function update_portal_issue_details()
    {
        
        if ($this->input->post()) {
            
            $details = $this->Report_m->insert_portal_issue_followup([
                'case_id'         => $this->input->post('update_id'),
                'follow_up_remark'         => $this->input->post('follow_up_remark'),
                'added_by' => $this->input->post('follow_up_agent_id')
            ]);
            

            if ($details > 0) {
                $this->Report_m->update_portal_issue_details([
                    'updated_by' => $this->input->post('follow_up_agent_id'),
                    'updated_at' => date('Y-m-d H:i:s')
                ], $this->input->post('update_id'));

                $data["response"] = true;
                $data["message"]  = "Details Updated.";

            } else {

                $data["response"] = false;
                $data["message"]  = "Failed to Update details.";
            }

            echo json_encode($data);
            return;

        } else {

            $data["response"] = false;
            $data["message"]  = "Invalid Request.";

            echo json_encode($data);
        }
    }

    public function get_portal_issue_history()
    {
        $data = array();
        if($this->input->is_ajax_request() && $this->input->post("id"))
        {
            $id = (int)$this->input->post("id");
            $r = $this->Report_m->get_portal_issue_history($id);
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
        else
        {
            $data["response"] = FALSE;
            $data["message"] = "Invalid Request.";
        }
        echo json_encode($data);

    }

    public function get_owa_details_by_id()
    {
        $data = array();
        if($this->input->is_ajax_request())
        {
            $id = $this->input->post("owa_id");

            $r = $this->Report_m->get_owa_details_by_id($id);
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

    public function update_sme_remark()
    {
        //if(!$this->is_allowed($this->update_roles)) { return $this->deny_json(); }
        if ($this->input->post()) {
            $details = $this->Report_m->update_owa_details([
                'sme_remark' => $this->input->post('sme_remark'),
                'updated_by' => $this->current_emp_id(),
                'updated_at' => date('Y-m-d H:i:s')
            ], $this->input->post('id'));
            

            if ($details > 0) {
                $data["response"] = true;
                $data["message"]  = "SME Remark Updated.";

            } else {

                $data["response"] = false;
                $data["message"]  = "Failed to Update SME Remark.";
            }

            echo json_encode($data);
            return;

        } else {

            $data["response"] = false;
            $data["message"]  = "Invalid Request.";

            echo json_encode($data);
        }
    }

    public function get_demand_suggestion_details()
    {
        // if(!$this->is_allowed($this->agent_report_roles)) { return $this->deny_json(); }
        $data = array();
        if($this->input->is_ajax_request())
        {
            $from_date = $this->input->post("from_date");
            $to_date   = $this->input->post("to_date");
           
           $r = $this->Report_m->get_demand_suggestion_details($this->current_role_id(),
                $this->current_emp_id(),
                $from_date,
                $to_date
            );
            
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

    public function add_number_change_request(){
        $data = array();
        if ($this->input->post()) {
            $details = $this->Report_m->insert_number_change_request([
                'oldPhone' => $this->input->post('old_phone_number'),
                'newPhone' => $this->input->post('new_phone_number'),
                'complaint_no' => $this->input->post('other_complaint_no'),
                'added_by' => $this->current_emp_id(),
                'added_at' => date('Y-m-d H:i:s')
            ]);
            if ($details > 0) {
                $data["response"] = TRUE;
                $data["message"]  = "Number change request added.";
            } else {
                $data["response"] = FALSE;
                $data["message"]  = "Failed to add number change request.";
            }
        } else {
            $data["response"] = FALSE;
            $data["message"]  = "Invalid Request.";
        }
        echo json_encode($data);
    }

    public function correctIncorrectDataAssign($page = "list"){
        $data = array();
		if(!file_exists(APPPATH.'views/reports/CorrectIncorrectDataAssign/'.$page.'.php'))
		{
		show_404();
		}
        $data["user_list"] = $this->Report_m->get_all_user_list();
        
		$data["title"] = "Correct Incorrect Data Assign";
		$this->load->view('app/templates/header' , $data);
		$this->load->view('app/templates/side_panel' , $data);
		$this->load->view('reports/CorrectIncorrectDataAssign/'.$page , $data);
		$this->load->view('app/templates/footer' , $data);
    }

    public function correct_incorrect_data_assign()
    {
        if ($this->input->post()) {

            $number    = (int) $this->input->post('number');
            $agent_ids = $this->input->post('agent_id'); // array of emp_id
            $date      = $this->input->post('date');      // yyyy-mm-dd ya empty

            // Validation
            if ($number <= 0) {
                echo json_encode(['response' => FALSE, 'message' => 'Invalid number.']);
                return;
            }
            if (empty($agent_ids) || !is_array($agent_ids)) {
                echo json_encode(['response' => FALSE, 'message' => 'Select at least one agent.']);
                return;
            }

            // ---- Date ko SP wale format (dd/mm/yyyy) me convert karo ----
            $date = $this->input->post('date'); // yyyy-mm-dd ya empty

// ---- Date ko SP wale format (dd/mm/yyyy) me convert karo ----
        $sp_date = NULL;
        if (!empty($date)) {
            $date_obj = DateTime::createFromFormat('Y-m-d', $date);
            if ($date_obj !== FALSE) {
                $sp_date = $date_obj->format('d/m/Y'); // e.g. 18/08/2026
            }
        }
                
                $r = $this->Report_m->correct_incorrect_data_assign(
                    $this->current_role_id(),
                    $this->current_emp_id(),
                    $number,
                    $sp_date,
                    $agent_ids
                );

                echo json_encode($r);
            }
        }

    public function today_assign_incorrect_data($page = "assigndata"){
        $data = array();
		if(!file_exists(APPPATH.'views/reports/CorrectIncorrectDataAssign/'.$page.'.php'))
		{
		show_404();
		}
        $data["user_list"] = $this->Report_m->get_all_user_list();
        
		$data["title"] = "Correct Incorrect Data Assign";
		$this->load->view('app/templates/header' , $data);
		$this->load->view('app/templates/side_panel' , $data);
		$this->load->view('reports/CorrectIncorrectDataAssign/'.$page , $data);
		$this->load->view('app/templates/footer' , $data);
    }

    public function today_assign_data()
    {
        $filter_date = $this->input->post('filter_date');

        $r = $this->Report_m->today_assign_data(
            $this->current_role_id(),
            $this->current_emp_id(),
            $filter_date
        );

        echo json_encode($r);
    }

public function update_assign_data()
{
    $id = $this->input->post('id');

    if (empty($id)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid row ID']);
        return;
    }

    $row = $this->Report_m->get_assign_row($id);

    if (!$row) {
        echo json_encode(['status' => 'error', 'message' => 'Record nahi mila']);
        return;
    }

    // created_at se sirf date nikaalo (datetime format: YYYY-MM-DD HH:MM:SS)
    $assigned_date = date('Y-m-d', strtotime($row->created_at));
    $today         = date('Y-m-d');

    if ($assigned_date != $today) {
        echo json_encode([
            'status'  => 'error',
            'message' => 'Yeh complaint ' . $assigned_date . ' ko assign hui thi, aaj update nahi ho sakti'
        ]);
        return;
    }

    $correct_incorrect  = $this->input->post('correct_incorrect');
    $description_error  = $this->input->post('description_error');
    $remark             = $this->input->post('remark');

    $data = [
        'correct_incorrect' => $correct_incorrect,
        'description_error' => $description_error,
        'remark'             => $remark,
        'update_at'          => date('Y-m-d H:i:s')
    ];

    $updated = $this->Report_m->update_assign_data($id, $data);

    echo json_encode([
        'status'  => $updated ? 'success' : 'error',
        'message' => $updated ? 'Row update ho gayi' : 'Update fail ho gaya, dobara try karein'
    ]);
}


    public function complaint_details()
{
    if (!$this->input->post()) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
        return;
    }

    $compId = (int) $this->input->post('compId');

    if (empty($compId)) {
        echo json_encode(['status' => 'error', 'message' => 'Complaint ID invalid hai']);
        return;
    }

    $res = $this->Report_m->get_complaint_details($compId);

    if ($res === FALSE) {
        echo json_encode(['status' => 'error', 'message' => 'Complaint details nahi mila']);
        return;
    }

    echo json_encode([
        'status'       => 'success',
        'details'      => $res['details'],
        'summary'      => $res['summary'],
        'area_details' => $res['area_details']
    ]);
}

public function get_tl_list()
{
    $filter_date = $this->input->post('filter_date');
    $list = $this->Report_m->get_active_tl_list($filter_date);
    echo json_encode($list);
}

public function admin_assign_data()
{
    // Sirf admin (role_id == 1) hi is endpoint ko use kar sakta hai
    if (($_SESSION['userdata']['role_id'] ?? null) != 1) {
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
        return;
    }

    $filter_date = $this->input->post('filter_date');
    $tl_name     = $this->input->post('tl_name');
    $correct_incorrect = $this->input->post('correct_incorrect');

    $rows = $this->Report_m->admin_assign_data($filter_date, $tl_name, $correct_incorrect);
    echo json_encode($rows);
}

// public function update_tl_status()
// {
//     if (($_SESSION['userdata']['role_id'] ?? null) != 1) {
//         echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
//         return;
//     }

//     $id = $this->input->post('id');
//     $tl_status = $this->input->post('tl_status');

//     if (empty($id) || empty($tl_status)) {
//         echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
//         return;
//     }

//     // ⚠️ Confirm karo: admin ka naam session me kis key me store hai (name / emp_name / username)
//     $admin_name = $_SESSION['userdata']['user_name'] ?? ($_SESSION['userdata']['user_name'] ?? 'Admin');

//     $data = [
//         'tl_status'       => $tl_status,
//         'given_tl_status' => $admin_name,
//         'tl_update_at'    => date('Y-m-d H:i:s')
//     ];

//     $updated = $this->Report_m->update_tl_status($id, $data);

//     echo json_encode([
//         'status'  => $updated ? 'success' : 'error',
//         'message' => $updated ? 'TL status update ho gaya' : 'Update fail ho gaya, dobara try karein'
//     ]);
// }

// public function update_tl_status()
// {
//     if (($_SESSION['userdata']['role_id'] ?? null) != 1) {
//         echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
//         return;
//     }

//     $id            = $this->input->post('id');
//     $tl_status     = $this->input->post('tl_status');
//     $given_tl_name = $this->input->post('given_tl_name'); // upar dropdown se selected TL

//     if (empty($id) || empty($tl_status)) {
//         echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
//         return;
//     }

//     // Agar TL dropdown se koi naam selected hai to wahi save hoga.
//     // Agar kuch bhi selected nahi hai, to current logged-in admin ka apna naam fallback ban jaega.
//     $admin_name = !empty($given_tl_name)
//         ? $given_tl_name
//         : ($_SESSION['userdata']['user_name'] ?? 'Admin');

//     $data = [
//         'tl_status'       => $tl_status,
//         'given_tl_status' => $admin_name,
//         'tl_update_at'    => date('Y-m-d H:i:s')
//     ];

//     $updated = $this->Report_m->update_tl_status($id, $data);

//     echo json_encode([
//         'status'  => $updated ? 'success' : 'error',
//         'message' => $updated ? 'TL status update ho gaya' : 'Update fail ho gaya, dobara try karein'
//     ]);
// }

public function update_tl_status()
{
    if (($_SESSION['userdata']['role_id'] ?? null) != 1) {
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
        return;
    }

    $id            = $this->input->post('id');
    $tl_status     = $this->input->post('tl_status');
    $tl_sub_status = $this->input->post('tl_sub_status'); // naya, optional
    $given_tl_name = $this->input->post('given_tl_name');

    if (empty($id) || empty($tl_status)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
        return;
    }

    $admin_name = !empty($given_tl_name)
        ? $given_tl_name
        : ($_SESSION['userdata']['name'] ?? 'Admin');

    $data = [
        'tl_status'       => $tl_status,
        // Sirf "Incorrect" ke sath hi meaningful hai, warna NULL save karo
        'tl_sub_status'   => ($tl_status === 'Incorrect' && !empty($tl_sub_status)) ? $tl_sub_status : null,
        'given_tl_status' => $admin_name,
        'tl_update_at'    => date('Y-m-d H:i:s')
    ];

    $updated = $this->Report_m->update_tl_status($id, $data);

    echo json_encode([
        'status'  => $updated ? 'success' : 'error',
        'message' => $updated ? 'TL status update ho gaya' : 'Update fail ho gaya, dobara try karein'
    ]);
}
public function assign_summary()
{
    $from_date = $this->input->post('from_date');
    $to_date   = $this->input->post('to_date');

    $rows = $this->Report_m->get_assign_summary($from_date, $to_date);

    // Har date ka total (us din sabhi agents milaake kitna assign hua)
    $day_totals = array();
    foreach ($rows as $r) {
        $date = $r->assign_date;
        if (!isset($day_totals[$date])) {
            $day_totals[$date] = 0;
        }
        $day_totals[$date] += (int) $r->day_count;
    }

    $result = array();
    foreach ($rows as $r) {
        $result[] = array(
            'assign_date' => $r->assign_date,
            'emp_id'      => $r->assigned_to_emp_id,
            'agent_name'  => $r->user_name,
            'msd_id'      => $r->msd_id,
            'count'       => (int) $r->day_count,
            'day_total'   => $day_totals[$r->assign_date]
        );
    }

    echo json_encode($result);
}

public function export_report()
{
    $type      = $this->input->post('type');
    $from_date = $this->input->post('from_date');
    $to_date   = $this->input->post('to_date');

    if (empty($from_date) || empty($to_date)) {
        show_error('From date and To date are required.', 400);
        return;
    }

    $from = $from_date . ' 00:00:00';
    $to   = $to_date . ' 23:59:59';

    switch ($type) {

        case 'breakdown':
            $filename = 'Correct_Incorrect_Breakdown_' . date('Ymd_His') . '.csv';
            $headers  = ['Correct/Incorrect', 'Description Error', 'Total Count'];
            $this->db->select('correct_incorrect, description_error, COUNT(*) AS total_count', FALSE);
            $this->db->from('tbl_correct_incorrect_assign');
            $this->db->where('created_at >=', $from);
            $this->db->where('created_at <=', $to);
            $this->db->group_by('correct_incorrect, description_error');
            $this->db->order_by('correct_incorrect', 'ASC');
            $this->db->order_by('total_count', 'DESC');
            $rows = $this->db->get()->result_array();
            break;

        case 'summary':
            $filename = 'Top_Level_Summary_' . date('Ymd_His') . '.csv';
            $headers  = ['Correct/Incorrect', 'Total Count'];
            $this->db->select('correct_incorrect, COUNT(*) AS total_count', FALSE);
            $this->db->from('tbl_correct_incorrect_assign');
            $this->db->where('created_at >=', $from);
            $this->db->where('created_at <=', $to);
            $this->db->group_by('correct_incorrect');
            $this->db->order_by('total_count', 'DESC');
            $rows = $this->db->get()->result_array();
            break;

        case 'match_percent':
            $filename = 'Match_Mismatch_Percent_' . date('Ymd_His') . '.csv';
            $headers  = ['Total Reviewed', 'Matched Count', 'Mismatched Count', 'Agreement %'];
            $sql = "SELECT
                        COUNT(*) AS total_reviewed,
                        SUM(CASE WHEN correct_incorrect = tl_status THEN 1 ELSE 0 END) AS matched_count,
                        SUM(CASE WHEN correct_incorrect != tl_status THEN 1 ELSE 0 END) AS mismatched_count,
                        ROUND(SUM(CASE WHEN correct_incorrect = tl_status THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 2) AS agreement_percentage
                    FROM tbl_correct_incorrect_assign
                    WHERE created_at >= ?
                      AND created_at <= ?
                      AND tl_status IS NOT NULL
                      AND tl_status != ''";
            $rows = $this->db->query($sql, [$from, $to])->result_array();
            break;

        case 'overall':
            $filename = 'Overall_All_Data_' . date('Ymd_His') . '.csv';
            $headers  = ['Comp Date', 'Created By Agent ID', 'Comp ID', 'Phone', 'TL Name', 'Audit By', 'Correct/Incorrect', 'Description Error', 'TL Status'];
            $this->db->select("a.compdate, a.created_by_agent_id, a.compid, a.phone, a.tl_name, u.user_name AS audit_by,
                                COALESCE(NULLIF(a.correct_incorrect, ''), 'Not Checked') AS correct_incorrect,
                                a.description_error,
                                a.tl_status", FALSE);
            $this->db->from('tbl_correct_incorrect_assign a');
            $this->db->join('master_users u', 'u.emp_id = a.assigned_to_emp_id', 'left');
            $this->db->where('DATE(a.created_at) >=', $from_date);
            $this->db->where('DATE(a.created_at) <=', $to_date);
            $this->db->order_by('a.created_at', 'DESC');
            $rows = $this->db->get()->result_array();
            break;
        case 'owa_agent_wise':
    $filename = 'OWA_Agent_Wise_Work_' . date('Ymd_His') . '.csv';
    $headers  = ['Date', 'Agent ID', 'Agent Name', 'MSD ID', 'Total Work Count'];

    $sql = "SELECT
                combined.work_date      AS work_date,
                u.emp_id                AS agent_id,
                u.user_name             AS agent_name,
                u.msd_id                AS msd_id,
                COUNT(combined.id)      AS total_count
            FROM (
                SELECT id, added_by, DATE(added_at) AS work_date FROM owa_report
                WHERE DATE(added_at) >= ? AND DATE(added_at) <= ?

                UNION ALL

                SELECT id, added_by, DATE(added_at) AS work_date FROM `owa_report_22-08-2026`
                WHERE DATE(added_at) >= ? AND DATE(added_at) <= ?
            ) AS combined
            LEFT JOIN master_users u ON u.emp_id = combined.added_by
            GROUP BY combined.work_date, combined.added_by
            ORDER BY combined.work_date ASC, total_count DESC";

    $rows = $this->db->query($sql, [$from_date, $to_date, $from_date, $to_date])->result_array();
    break;

        default:
            show_error('Invalid report type.', 400);
            return;
    }

    // ---- CSV output (Excel me directly khulti hai) ----
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');
    header('Expires: 0');

    $output = fopen('php://output', 'w');
    fputs($output, "\xEF\xBB\xBF");   // UTF-8 BOM, taaki Excel me special/hindi characters sahi dikhein
    fputcsv($output, $headers);

    foreach ($rows as $row) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit; // zaroori — CI ka koi extra output CSV ko corrupt na kare
}

public function update_feedback_done()
{
    $this->config->load('config');
    $allowed_ids = $this->config->item('feedback_done_allowed_emp_ids') ?: array();

    $current_emp_id = (string) $this->current_emp_id();

    if (!in_array($current_emp_id, $allowed_ids)) {
        echo json_encode(['status' => 'error', 'message' => 'Aapko yeh action karne ki permission nahi hai']);
        return;
    }

    $id        = $this->input->post('id');
    $from_time = $this->input->post('from_time');
    $to_time   = $this->input->post('to_time');

    if (empty($id) || empty($from_time) || empty($to_time)) {
        echo json_encode(['status' => 'error', 'message' => 'From aur To time dono zaroori hain']);
        return;
    }

    $time_duration = $from_time . ' - ' . $to_time;

    $updated = $this->Report_m->update_feedback_done($id, $time_duration);

    echo json_encode([
        'status'  => $updated ? 'success' : 'error',
        'message' => $updated ? 'Feedback Done mark ho gaya' : 'Update fail ho gaya, dobara try karein'
    ]);
}


}
