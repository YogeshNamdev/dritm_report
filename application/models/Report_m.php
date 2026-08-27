<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); 
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_m extends CI_Model {


	public function __construct()
	{
	$this->load->database();
	}

	public  function insert_performance($table,$data)
	{
		
	$res = $this->db->insert_batch($table,$data);
			if($res){
				return TRUE;
			}else{
				return FALSE;
			}

	}
	
	
	
	

	
	public function get_my_performance($msd_id, $start_date, $end_date)
	{
		$this->db->select('*');
		$this->db->from('adviser_performance');
		$this->db->where('msdid', $msd_id);
		$this->db->where('datemonth >=', $start_date);
		$this->db->where('datemonth <=', $end_date);
	
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			return false;
		} else {
			return $query->result();
		}
	}


public function get_performance_rolid_wise($msd_id, $start_date, $end_date)
{
   
	 $this->db->where('msdid', $msd_id);
    $this->db->where('datemonth >=', $start_date);
    $this->db->where('datemonth <=', $end_date);
	  $query = $this->db->get('adviser_performance');
    return $query->result();
   
    $query = $this->db->get();

    if ($query->num_rows() == 0) {
        return false;
    } else {
        return $query->result();
    }
}

public function rolid_fetch($rol_id)
{
    $this->db->select('msd_id');
    $this->db->from('master_users');
	 $this->db->where('role_id', $rol_id);
	  $this->db->where('status', 1);
   
    $query = $this->db->get();

    if ($query->num_rows() == 0) {
        return false;
    } else {
        return $query->result();
    }
}




public function ahtper($msd_id, $start_date, $end_date)
  {
   
    $this->db->select('*');
    $this->db->from('adviser_performance');
    $this->db->where('msdid', $msd_id);
    $this->db->where('datemonth >=', $start_date);
    $this->db->where('datemonth <=', $end_date);
    $this->db->where('nocalls !=', '-'); 
    $query = $this->db->get();
  
    if ($query->num_rows() == 0) {
        return false;
    } else {
        return $query->result();
    }
}








public function perform_all($start_all)
{
    $this->db->select('*');
    $this->db->from('tl_wise_performance');
    $this->db->where('month', $start_all); // Removed the extra space after 'month'
    
    $query = $this->db->get();

    if ($query->num_rows() == 0) {
        return false;
    } else {
        return $query->result();
    }
}
	
	
	
	
	
	
	
	public function validate_login($user_msd , $user_password)
	 {
	  $r = $this->db->query("select t1.*,t2.role_name from master_users t1,master_roles t2 where t1.status=1 and t1.login_status=1 and t1.msd_id='".$user_msd."' and t1.user_password='".$user_password."' and t2.role_id=t1.role_id and t2.status=1");
	  if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
	 }

	 public function validate_student($mobile , $dob)
	 {
	  $r = $this->db->query("select t1.*,t2.role_name from students t1,master_roles t2 where t1.status=1 and t1.mobile='".$mobile."' and t1.dob='".$dob."' and t2.role_id=t1.role_id and t1.status=1");
	  if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
	 }
	
	
	public function get_feedback_details_by_id($id)
	 {
	  $r = $this->db->get_where("quality_feedback" , array("status" => 1 , "id" => $id));
	  if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
	 }
	 public function update_feedback($id , $update_arr)
	 {
	  $this->db->where("id" , $id);
	  $this->db->update("quality_feedback" , $update_arr);
	  return $this->db->affected_rows();
	 }
	
	public function update_user_details($user_id , $update_arr)
	 {
	  $this->db->where("user_id" , $user_id);
	  $this->db->update("master_users" , $update_arr);
	  return $this->db->affected_rows();
	 }
	
	
	public function check_user_email_exists($user_id , $msd_id)
	 {
	  $r = $this->db->query("select * from master_users where status=1 and msd_id='".$msd_id."' and user_id!='".$user_id."'");
	  if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
	 }
	

	public function check_email_exists($msd_id)
	 {
	  $r = $this->db->query("select * from master_users where status=1 and msd_id='".$msd_id."'");
	  if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
	 }
	
	
	public function update_user($user_id , $update_arr)
	 {
	  $this->db->where("user_id" , $user_id);
	  $this->db->update("master_users" , $update_arr);
	  return $this->db->affected_rows();
	 }
	 
	 	public function get_all_user_list()
	 {
	  $r = $this->db->get_where("master_users" , array("status" => 1));
	  if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
	 }
	public function get_all_owa_user_list()
	 {
		$this->db->where('status', 1);
		$this->db->where_in('role_id', array(3,4));

		$r = $this->db->get('master_users');

		if($r->num_rows() > 0){
		return $r->result();
		}else{
		return FALSE;
		}
	 }
     	public function get_all_issue($report_id)
	 {
	  $r = $this->db->get_where("issues" , array("report_id" =>$report_id));
	  if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
	 }

    public function get_all_departments()
    {
        $this->db->where('isdisabled', 0);
        $this->db->order_by('Priority', 'ASC');

        $r = $this->db->get('department');

        if ($r->num_rows() > 0) {
            return $r->result();
        } else {
            return FALSE;
        }
    }
	public function get_all_district()
    {
        $this->db->order_by('Priority', 'ASC');
        $r = $this->db->get('district');

        if ($r->num_rows() > 0) {
            return $r->result();
        } else {
            return FALSE;
        }
    }
	 public function get_all_users()
	 {
	  $r = $this->db->query("select t1.*,t2.role_name from master_users t1,master_roles t2 where t1.status=1 and t2.role_id=t1.role_id and t2.status=1 order by t1.user_name");
	  if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
	 }

	public function get_callback_reports($role_id, $emp_id)
	{
		$this->db->select("t1.*, creator.user_name as created_by_name, updater.user_name as updated_by_name, assigned.user_name as assigned_agent_name, assigned.msd_id as assigned_msd_id, dept.Departname_E");
		$this->db->from("callback_report t1");
		$this->db->join("master_users creator", "t1.created_by = creator.emp_id", "left");
		$this->db->join("master_users updater", "t1.updated_by = updater.emp_id", "left");
		$this->db->join("master_users assigned", "t1.assigned_agent_id = assigned.emp_id", "left");
		$this->db->join("department dept", "t1.department = dept.Departid", "left");
		$this->db->where("t1.status", 1);
		// if((int)$role_id !== 1)
		// {
		// 	$this->db->group_start();
		// 	$this->db->where("t1.assigned_agent_id", $emp_id);
		// 	$this->db->or_where("t1.created_by", $emp_id);
		// 	$this->db->group_end();
		// }
		$this->db->order_by("t1.created_at", "DESC");
		$r = $this->db->get();
		if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
	}

	public function insert_callback_report($data)
	{
		$this->db->insert("callback_report", $data);
		return $this->db->insert_id();
	}

	public function get_callback_report_by_id($id)
	{
		$r = $this->db->get_where("callback_report", array("id" => $id, "status" => 1));
		if($r->num_rows() > 0){ return $r->row(); }else{ return FALSE; }
	}

	public function update_callback_report($id, $data)
	{
		$this->db->where("id", $id);
		$this->db->update("callback_report", $data);
		return $this->db->affected_rows();
	}

	public function insert_callback_history($data)
	{
		$this->db->insert("callback_report_history", $data);
		return $this->db->insert_id();
	}

	public function get_callback_history($callback_id)
	{
		$this->db->select("h.*, updater.user_name as updated_by_name, previous_agent.user_name as previous_agent_name, new_agent.user_name as new_agent_name");
		$this->db->from("callback_report_history h");
		$this->db->join("master_users updater", "h.updated_by = updater.emp_id", "left");
		$this->db->join("master_users previous_agent", "h.previous_agent_id = previous_agent.emp_id", "left");
		$this->db->join("master_users new_agent", "h.new_agent_id = new_agent.emp_id", "left");
		$this->db->where("h.callback_id", $callback_id);
		$this->db->order_by("h.updated_at", "DESC");
		$r = $this->db->get();
		if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
	}

	public function get_name_change_reports($role_id, $emp_id)
	{
		$this->db->select("t1.*, agent.user_name as agent_name, agent.msd_id as agent_msd_id, creator.user_name as created_by_name");
		$this->db->from("name_change_report t1");
		$this->db->join("master_users agent", "t1.agent_id = agent.emp_id", "left");
		$this->db->join("master_users creator", "t1.created_by = creator.emp_id", "left");
		$this->db->where("t1.status", 1);
		// if((int)$role_id !== 1)
		// {
		// 	$this->db->group_start();
		// 	$this->db->where("t1.agent_id", $emp_id);
		// 	$this->db->or_where("t1.created_by", $emp_id);
		// 	$this->db->group_end();
		// }
		$this->db->order_by("t1.created_at", "DESC");
		$r = $this->db->get();
		if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
	}

	public function insert_name_change_report($data)
	{
		$this->db->insert("name_change_report", $data);
		return $this->db->insert_id();
	}

    private function get_shift_condition($shift)
    {
        if($shift == "evening")
        {
            return " and TIME(t1.added_at) > '14:30:00' and TIME(t1.added_at) <= '22:00:00'";
        }

        return " and TIME(t1.added_at) >= '07:00:00' and TIME(t1.added_at) <= '14:30:00'";
    }

    public function get_all_no_same_resolution_details($shift = "morning", $remark = "0")
{
    $shift_condition = $this->get_shift_condition($shift);

    $remark_condition = "";

    // If remark selected
    if($remark != "0" && !empty($remark))
    {
        // If "other" selected then exclude fixed remarks
        if($remark == "other")
        {
            $remark_condition = "
                AND (
                    t1.helpdesk_remark NOT IN (
                        'L1 Officer call done',
                        'Ringing',
                        'Switch off',
                        'Busy'
                    )
                    OR t1.helpdesk_remark IS NULL
                    OR t1.helpdesk_remark = ''
                )
            ";
        }
        else
        {
            // Normal remark filter
            $remark_condition = "
                AND t1.helpdesk_remark = '".$this->db->escape_str($remark)."'
            ";
        }
    }

    $query = "
        SELECT 
            t1.id as did,
            t1.*,
            t2.*,
            t3.*,
            t4.*,
            t5.*
        FROM no_same_resolution_report t1
        LEFT JOIN master_users t2 
            ON t1.agent_id = t2.emp_id
        LEFT JOIN department t3 
            ON t1.department = t3.Departid
        LEFT JOIN district t4 
            ON t1.district = t4.District_Code
        LEFT JOIN issues t5 
            ON t1.issue = t5.id
        WHERE t1.status = 1
        ".$shift_condition."
        ".$remark_condition." order by t1.id desc
    ";

    $r = $this->db->query($query);

    if($r->num_rows() > 0)
    {
        return $r->result();
    }
    else
    {
        return FALSE;
    }
}

    // public function get_all_copypaste_details($shift = "morning" , $remark){
    // $shift_condition = $this->get_shift_condition($shift);
    // $r = $this->db->query("select t1.id as did,t1.*,t2.*,t3.*,t4.*,t5.* from copy_paste_wrong_resolution_report t1 left join master_users t2 on t1.agent_id = t2.emp_id left join department t3 on t1.department=t3.Departid left join district t4 on t1.district=t4.District_Code left join issues t5 on t1.issue=t5.id where t1.status=1".$shift_condition);
	//   if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
    // }

	public function get_all_copypaste_details($shift = "morning", $remark = "0")
{
    $shift_condition = $this->get_shift_condition($shift);

    $remark_condition = "";

    // If remark selected
    if($remark != "0" && !empty($remark))
    {
        // If "other" selected then exclude fixed remarks
        if($remark == "other")
        {
            $remark_condition = "
                AND (
                    t1.helpdesk_remark NOT IN (
                        'L1 Officer call done',
                        'Ringing',
                        'Switch off',
                        'Busy'
                    )
                    OR t1.helpdesk_remark IS NULL
                    OR t1.helpdesk_remark = ''
                )
            ";
        }
        else
        {
            // Normal remark filter
            $remark_condition = " 
                AND t1.helpdesk_remark = '".$this->db->escape_str($remark)."' 
            ";
        }
    }

    $query = "
        SELECT 
            t1.id as did,
            t1.*,
            t2.*,
            t3.*,
            t4.*,
            t5.*
        FROM copy_paste_wrong_resolution_report t1
        LEFT JOIN master_users t2 
            ON t1.agent_id = t2.emp_id
        LEFT JOIN department t3 
            ON t1.department = t3.Departid
        LEFT JOIN district t4 
            ON t1.district = t4.District_Code
        LEFT JOIN issues t5 
            ON t1.issue = t5.id
        WHERE t1.status = 1
        ".$shift_condition."
        ".$remark_condition." order by t1.id desc
    ";

    $r = $this->db->query($query);

    if($r->num_rows() > 0)
    {
        return $r->result();
    }
    else
    {
        return FALSE;
    }
}
    public function get_all_direction_details($shift = "morning", $remark = "0")
{
    $shift_condition = $this->get_shift_condition($shift);

    $remark_condition = "";

    // If remark selected
    if($remark != "0" && !empty($remark))
    {
        // If "other" selected then exclude fixed remarks
        if($remark == "other")
        {
            $remark_condition = "
                AND (
                    t1.helpdesk_remark NOT IN (
                        'L1 Officer call done',
                        'Ringing',
                        'Switch off',
                        'Busy'
                    )
                    OR t1.helpdesk_remark IS NULL
                    OR t1.helpdesk_remark = ''
                )
            ";
        }
        else
        {
            // Normal remark filter
            $remark_condition = "
                AND t1.helpdesk_remark = '".$this->db->escape_str($remark)."'
            ";
        }
    }

    $query = "
        SELECT 
            t1.id as did,
            t1.*,
            t2.*,
            t3.*,
            t4.*,
            t5.*
        FROM direction_report t1
        LEFT JOIN master_users t2 
            ON t1.agent_id = t2.emp_id
        LEFT JOIN department t3 
            ON t1.department = t3.Departid
        LEFT JOIN district t4 
            ON t1.district = t4.District_Code
        LEFT JOIN issues t5 
            ON t1.issue = t5.id
        WHERE t1.status = 1
        ".$shift_condition."
        ".$remark_condition." order by t1.id desc
    ";

    $r = $this->db->query($query);

    if($r->num_rows() > 0)
    {
        return $r->result();
    }
    else
    {
        return FALSE;
    }
}

	 public function add_user_role($insert_arr)
	 {
        $this->db->insert("master_roles" , $insert_arr);
        return $this->db->insert_id();
	 
	 }

	public function get_resolution_record($table, $id)
	{
		$r = $this->db->get_where($table, array("id" => (int)$id, "status" => 1));
		if($r->num_rows() > 0){ return $r->row(); }else{ return FALSE; }
	}

	public function insert_complaint_status_history($data)
	{
		$this->db->insert("report_complaint_status_history", $data);
		return $this->db->insert_id();
	}

	public function get_complaint_status_history($report_key, $record_id)
	{
		$this->db->select("
			h.*,
			updater.user_name AS updated_by_name,
			updater.msd_id AS updated_by_msd_id,
			creator.user_name AS created_by_name,
			creator.msd_id AS created_by_msd_id
		");
		$this->db->from("report_complaint_status_history h");
		$this->db->join("master_users updater", "h.updated_by = updater.emp_id", "left");
		$this->db->join("master_users creator", "h.created_by = creator.emp_id", "left");
		$this->db->where("h.report_key", $report_key);
		$this->db->where("h.record_id", (int)$record_id);
		$this->db->order_by("h.updated_at", "ASC");
		$this->db->order_by("h.id", "ASC");
		$r = $this->db->get();
		if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
	}

	 public function check_user_role_exists($role_name)
	 {
	  $r = $this->db->query("select * from master_roles where status=1 and role_name='".$role_name."'");
	  if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
	 }
	 public function update_user_role($role_id , $update_arr)
	 {
	  $this->db->where("role_id" , $role_id);
	  $this->db->update("master_roles" , $update_arr);
	  return $this->db->affected_rows();
	 }
	 public function add_user($insert_arr)
	 {
        $this->db->insert("master_users" , $insert_arr);
        return $this->db->insert_id();
	 
	 }
	 public function save_quality_feedback($insert_arr)
	 {
        $this->db->insert("quality_feedback" , $insert_arr);
        return $this->db->insert_id();
	 
	 }
	 public function get_all_feedbacks()
	 {
	  $r = $this->db->query("select * from quality_feedback where status=1 ");
	  if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
	 }
	 public function get_feedback_by_id($id)
	 {
	  $r = $this->db->query("select feedback_link from quality_feedback where id = '".$id."' and status=1 ");
	  if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
	 }

	 public function get_all_data($table, array $cond, $pid) {
         $query = $this->db->select('*')
                ->from($table)
                ->where($cond)
                ->order_by($pid, "DESC")
                ->get();
            return $query->result();
          }

	public function add_log($arr)
	 {
        $this->db->insert("log_table" , $arr);
        return $this->db->insert_id();
	 
	 }
	 public function update_log($id , $update_arr)
	 {
	  $this->db->where("id" , $id);
	  $this->db->update("log_table" , $update_arr);
	  return $this->db->affected_rows();
	 }
	 public function get_all_logs()
	 {
	  $r = $this->db->get_where("log_table" , array("status" => 1));
	  if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
	 }

	 public function insert($insert_arr)
	 {
        $this->db->insert("audit_details_file" , $insert_arr);
        return $this->db->insert_id();
	 
	 }
	 
	 
	 public function export_adviser_performance($insert_arr)
	 {
        $this->db->insert("adviser_performance" , $insert_arr);
        return $this->db->insert_id();
	 
	 }

    function insert_no_same_resolutions($data){
        $this->db->insert('no_same_resolution_report',$data);
        return $this->db->insert_id();
    }
    function insert_copypaste_resolutions($data){
        $this->db->insert('copy_paste_wrong_resolution_report',$data);
        return $this->db->insert_id();
    }

    function insert_direction_resolutions($data){
        $this->db->insert('direction_report',$data);
        return $this->db->insert_id();
    }

public function get_msd_id($user_id,$role_id)
    {
		if(in_array((int)$role_id, array(1, 2, 3))){
			$sql = "SELECT msd_id FROM master_users WHERE status = 1 and user_id = '".$user_id."' and role_id = '".$role_id."' ";
		}
         
		
         $rs = $this->db->query($sql);
         if($rs->num_rows() == 0) {return false;}else {return $rs->result(); }
	}
	public function get_student_id($user_id,$role_id)
    {
         $sql = "SELECT msd_id FROM master_users WHERE status = 1 and user_id = '".$user_id."' and role_id = '".$role_id."' ";
		
         $rs = $this->db->query($sql);
         if($rs->num_rows() == 0) {return false;}else {return $rs->result(); }
	}

	public function get_adviser_quality($msd_id)
    {
		$sql = "SELECT audit_emp_id ,point_obtain , total_opportunities FROM audit_details_file where status =  1 and msd_id = '".$msd_id."'";
        $rs = $this->db->query($sql);
        if($rs->num_rows() == 0) {return false;}else {return $rs->result(); }
	}

public function get_adviser_quality_month($msd_id,$month, $current_year)
    {
		$sql = "SELECT audit_emp_id ,point_obtain , total_opportunities FROM audit_details_file where status =  1 and msd_id = '".$msd_id."' and MONTH(audit_date) = '".$month."' and year(audit_date) = '".$current_year."'";
        $rs = $this->db->query($sql);
        if($rs->num_rows() == 0) {return false;}else {return $rs->result(); }
	}

	public function get_fatal_count($msd_id, $month)
    {
		$sql = "SELECT no,action_value,accept_status FROM audit_details_file where status =  1 and msd_id = '".$msd_id."' and no = 1 and MONTH(audit_date) = '".$month."'";
		$rs = $this->db->query($sql);
        if($rs->num_rows() == 0) {return false;}else {return $rs->result(); }
	}
public function get_fatal_count_month($msd_id,$month)
    {
		$sql = "SELECT no,action_value,accept_status FROM audit_details_file where status =  1 and msd_id = '".$msd_id."' and no = 1 and MONTH(audit_date) = '".$month."'";
		$rs = $this->db->query($sql);
        if($rs->num_rows() == 0) {return false;}else {return $rs->result(); }
	}

	public function get_monthly_audit($msd_id)
    {
		$sql = "SELECT * FROM audit_details_file where status =  1 and msd_id = '".$msd_id."'";
        $rs = $this->db->query($sql);
        if($rs->num_rows() == 0) {return false;}else {return $rs->result(); }
	}
	public function add_action($insert_arr)
	 {
        $this->db->insert("adviser_feedback" , $insert_arr);
        return $this->db->insert_id();
	 }
	 public function update_action($update_arr,$audit_emp_id)
	 {
	  $this->db->where("audit_emp_id" , $audit_emp_id);
	  $this->db->update("audit_details_file" , $update_arr);
	  return $this->db->affected_rows();
	 }

	 public function get_adviser_action($audit_emp_id)
    {
		$sql = "SELECT * FROM adviser_feedback where status =  1 and audit_emp_id = '".$audit_emp_id."'";
        $rs = $this->db->query($sql);
        if($rs->num_rows() == 0) {return false;}else {return $rs->result(); }
	}
	public function insert_adviser($insert_arr)
	 {
        $this->db->insert("master_users" , $insert_arr);
        return $this->db->insert_id();
	 }
	 public function get_user_details_by_id($user_id)
	 {
	  $r = $this->db->get_where("master_users" , array("status" => 1 , "user_id" => $user_id));
	  if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
	 }
	public function get_adviser_feedback($start_date,$end_date,$msd_id)
	 {
		$cond = "";
	           if($start_date != "" && $end_date != ""){
            $cond .= "AND audit_date >= '".$start_date."' AND audit_date<= '".$end_date."'";
        }
        if($msd_id != "" ){
            $cond .= "AND msd_id = '".$msd_id."'";
        }
        	  $sql = "SELECT * FROM audit_details_file where status =  1 ".$cond." ";
        $rs = $this->db->query($sql);
        if($rs->num_rows() == 0) {
return false;}
else {
		return $rs->result(); 
	}
}


public function update_copypaste_resolutions($update_arr, $id)
	 {
	  $this->db->where("id" , $id);
	  $this->db->update("copy_paste_wrong_resolution_report" , $update_arr);
	  return $this->db->affected_rows();
	 }

     public function update_no_or_same_resolutions($update_arr, $id)
	 {
	  $this->db->where("id" , $id);
	  $this->db->update("no_same_resolution_report" , $update_arr);
	  return $this->db->affected_rows();
	 }

      public function update_direction_resolutions($update_arr, $id)
	 {
	  $this->db->where("id" , $id);
	  $this->db->update("direction_report" , $update_arr);
	  return $this->db->affected_rows();
	 }


	public function get_all_attribute()
	{
		$this->db->select('attribID, attribname, attribname_E');
		$this->db->from('complaintattrib');
		$this->db->where('isdisabled', 0);

		$r = $this->db->get();

		if ($r->num_rows() > 0) {
			return $r->result();
		} else {
			return FALSE;
		}
	}	

	function insert_owa_details($data){
        $this->db->insert('owa_report',$data);
        return $this->db->insert_id();
    }

	public function get_all_owa_details($start_date = null, $end_date = null, $agent_id = 0)
		{
			$this->db->select("
				t1.*, 
				t2.user_name, 
				t2.msd_id, 
				t3.Departid AS new_department_id, 
				t3.Departname_E AS new_department_name, 
				t4.Departid AS old_department_id, 
				t4.Departname_E AS old_department_name, 
				t5.attribID AS old_attribute_id, 
				t5.attribname_E AS old_attribute_name, 
				t6.attribID AS new_attribute_id, 
				t6.attribname_E AS new_attribute_name
			");

			$this->db->from('owa_report t1');

			$this->db->join('master_users t2', 't1.agent_id = t2.emp_id', 'left');
			$this->db->join('department t3', 't1.new_department = t3.Departid', 'left');
			$this->db->join('department t4', 't1.old_department = t4.Departid', 'left');
			$this->db->join('complaintattrib t5', 't1.old_attribute = t5.attribID', 'left');
			$this->db->join('complaintattrib t6', 't1.new_attribute = t6.attribID', 'left');

			$this->db->where('t1.status', 1);

			// Start Date Condition
			if(!empty($start_date))
			{
				$this->db->where('DATE(t1.added_at) >=', $start_date);
			}

			// End Date Condition
			if(!empty($end_date))
			{
				$this->db->where('DATE(t1.added_at) <=', $end_date);
			}

			// Agent Condition
			if(!empty($agent_id) && $agent_id != 0)
			{
				$this->db->where('t1.added_by', $agent_id);
			}

			$this->db->order_by('t1.id', 'DESC');

			$r = $this->db->get();

			if($r->num_rows() > 0)
			{
				return $r->result();
			}
			else
			{
				return FALSE;
    }
}

public function get_all_owa_details_old($start_date = null, $end_date = null, $agent_id = 0)
		{
			$this->db->select("
				t1.*, 
				t2.user_name, 
				t2.msd_id, 
				t3.Departid AS new_department_id, 
				t3.Departname_E AS new_department_name, 
				t4.Departid AS old_department_id, 
				t4.Departname_E AS old_department_name, 
				t5.attribID AS old_attribute_id, 
				t5.attribname_E AS old_attribute_name, 
				t6.attribID AS new_attribute_id, 
				t6.attribname_E AS new_attribute_name
			");

			$this->db->from('owa_report_22-08-2026 t1');

			$this->db->join('master_users t2', 't1.agent_id = t2.emp_id', 'left');
			$this->db->join('department t3', 't1.new_department = t3.Departid', 'left');
			$this->db->join('department t4', 't1.old_department = t4.Departid', 'left');
			$this->db->join('complaintattrib t5', 't1.old_attribute = t5.attribID', 'left');
			$this->db->join('complaintattrib t6', 't1.new_attribute = t6.attribID', 'left');

			$this->db->where('t1.status', 1);

			// Start Date Condition
			if(!empty($start_date))
			{
				$this->db->where('DATE(t1.added_at) >=', $start_date);
			}

			// End Date Condition
			if(!empty($end_date))
			{
				$this->db->where('DATE(t1.added_at) <=', $end_date);
			}

			// Agent Condition
			if(!empty($agent_id) && $agent_id != 0)
			{
				$this->db->where('t1.added_by', $agent_id);
			}

			$this->db->order_by('t1.id', 'DESC');

			$r = $this->db->get();

			if($r->num_rows() > 0)
			{
				return $r->result();
			}
			else
			{
				return FALSE;
    }
}
	
	public function insert_complaint_details($insert_arr)
	 {
        $this->db->insert("complaint_creation" , $insert_arr);
        return $this->db->insert_id();
	 }

	 public function insert_high_complaint_details($insert_arr)
	 {
        $this->db->insert("High_complaint_creation" , $insert_arr);
        return $this->db->insert_id();
	 }


		public function get_all_complaints($role_id, $emp_id, $from_date = "", $to_date = "")
		{
			$sql = "
				SELECT 
					t1.*, 
					t2.Departname_E AS department_name,
					t3.attribname_E AS attribute_name

				FROM complaint_creation t1

				LEFT JOIN department t2 
					ON t1.department = t2.Departid 

				LEFT JOIN complaintattrib t3 
					ON t1.attribute = t3.attribID 

				WHERE 1=1
			";

			$params = array();

			// Role wise condition
			if($role_id != 1)
			{
				$sql .= " AND t1.added_by = ?";
				$params[] = $emp_id;
			}

			// From date
			if($from_date != "")
			{
				$sql .= " AND DATE(t1.added_at) >= ?";
				$params[] = $from_date;
			}

			// To date
			if($to_date != "")
			{
				$sql .= " AND DATE(t1.added_at) <= ?";
				$params[] = $to_date;
			}

			$sql .= " ORDER BY t1.id DESC";

			$r = $this->db->query($sql, $params);

			if($r->num_rows() > 0)
			{
				return $r->result();
			}

			return FALSE;
		}


		public function update_name_change_detail($update_arr, $id)
		{
		$this->db->where("id" , $id);
		$this->db->update("name_change_report" , $update_arr);
		return $this->db->affected_rows();
		}

		public function get_remark_formats($emp_id, $role_id = 0)
		{
			$this->db->select("
				t1.*, 
				creator.user_name as created_by_name, 
				updater.user_name as updated_by_name
			");

			$this->db->from("remark_formats t1");

			$this->db->join(
				"master_users creator",
				"t1.created_by = creator.emp_id",
				"left"
			);

			$this->db->join(
				"master_users updater",
				"t1.updated_by = updater.emp_id",
				"left"
			);

			$this->db->where("t1.status", 1);

			// If role is not Admin then show only own records
			if($role_id != 1)
			{
				$this->db->where("t1.created_by", $emp_id);
			}

			$this->db->order_by("t1.updated_at IS NULL", "ASC", false);
			$this->db->order_by("IFNULL(t1.updated_at, t1.created_at)", "DESC", false);

			$r = $this->db->get();

			if($r->num_rows() > 0)
			{
				return $r->result();
			}
			else
			{
				return FALSE;
			}
		}

	public function get_remark_format_by_id($id, $emp_id)
	{
		$r = $this->db->get_where("remark_formats", array("id" => $id, "created_by" => $emp_id, "status" => 1));
		if($r->num_rows() > 0){ return $r->row(); }else{ return FALSE; }
	}

	public function insert_remark_format($data)
	{
		$this->db->insert("remark_formats", $data);
		return $this->db->insert_id();
	}

	public function update_remark_format($id, $emp_id, $data)
	{
		$this->db->where("id", $id);
		$this->db->where("created_by", $emp_id);
		$this->db->where("status", 1);
		$this->db->update("remark_formats", $data);
		return $this->db->affected_rows();
	}

	public function get_tat_mappings()
	{
		$this->db->select("
			attr.attribID AS attribute_id,
			attr.attribname_E AS attribute_name,
			attr.TAT1,
			attr.TAT2,
			attr.TAT3,
			attr.TAT4,
			attr.Priority AS attribute_priority,
			dept.Departid AS department_id,
			dept.Departname_E AS department_name,
		");
		$this->db->from("complaintattrib attr");
		$this->db->join("department dept", "attr.subdepid = dept.Departid", "inner");
		$this->db->where("dept.isdisabled", 0);
		$this->db->where("attr.isdisabled", 0);
		$this->db->order_by("dept.Priority", "ASC");
		$this->db->order_by("attr.Priority", "ASC");
		$this->db->order_by("attr.attribname_E", "ASC");

		$r = $this->db->get();
		if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
	}

	private function live_db()
	{
		$live = $this->load->database('live', TRUE);
		$live->query("SET SESSION group_concat_max_len = 1000000");
		return $live;
	}

	public function get_live_departments()
	{
		$live = $this->live_db();
		$live->where('isdisabled', 0);
		$live->order_by('Priority', 'ASC');
		$r = $live->get('department');
		if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
	}

	public function get_live_districts()
	{
		$live = $this->live_db();
		$live->order_by('Priority', 'ASC');
		$r = $live->get('district');
		if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
	}

	public function get_live_officers()
	{
		$live = $this->live_db();
		$live->select('officerid, officername, loginuserid, officerno');
		$live->from('officermaster');
		$live->where('isdisabled', 0);
		$live->order_by('officername', 'ASC');
		$r = $live->get();
		if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
	}

	private function psm_remark_text()
	{
		return 'शिकायत चयनित अधिकारी के कार्य क्षेत्र से संबंधित नही है';
	}

	private function psm_filter_sql($filters, &$params)
	{
		$where = " WHERE cs.Remarks LIKE ? ";
		$params[] = "%".$this->psm_remark_text()."%";

		if(!empty($filters["from_date"]))
		{
			$where .= " AND DATE(cs.StDate) >= ? ";
			$params[] = $filters["from_date"];
		}
		if(!empty($filters["to_date"]))
		{
			$where .= " AND DATE(cs.StDate) <= ? ";
			$params[] = $filters["to_date"];
		}
		if(!empty($filters["complaint_no"]))
		{
			$where .= " AND cs.CompId = ? ";
			$params[] = $filters["complaint_no"];
		}
		if(!empty($filters["department"]))
		{
			$where .= " AND c.compdepart = ? ";
			$params[] = $filters["department"];
		}
		if(!empty($filters["officer"]))
		{
			$where .= " AND cs.OfficerId = ? ";
			$params[] = $filters["officer"];
		}
		if(!empty($filters["current_officer"]))
		{
			$where .= " AND c.officerid = ? ";
			$params[] = $filters["current_officer"];
		}
		if(!empty($filters["login_user_id"]))
		{
			$where .= " AND (om.loginuserid LIKE ? OR curr.loginuserid LIKE ?) ";
			$params[] = "%".$filters["login_user_id"]."%";
			$params[] = "%".$filters["login_user_id"]."%";
		}
		if(!empty($filters["complaint_status"]))
		{
			$where .= " AND c.StatusRemark = ? ";
			$params[] = $filters["complaint_status"];
		}
		if(!empty($filters["district"]))
		{
			$where .= " AND c.callerdistcode = ? ";
			$params[] = $filters["district"];
		}

		return $where;
	}

	private function psm_base_from()
	{
		return "
			FROM complaintsummary cs
			INNER JOIN officermaster om ON om.officerid = cs.OfficerId
			LEFT JOIN complaints c ON c.compid = cs.CompId
			LEFT JOIN officermaster curr ON curr.officerid = c.officerid
			LEFT JOIN department d ON d.Departid = c.compdepart
			LEFT JOIN district dist ON dist.District_Code = c.callerdistcode
		";
	}

	private function psm_query($sql, $params)
	{
		$live = $this->live_db();
		$r = $live->query($sql, $params);
		if($r->num_rows() > 0){ return $r->result(); }else{ return array(); }
	}

	public function get_psm_summary($filters)
	{
		$params = array();
		$where = $this->psm_filter_sql($filters, $params);
		$sql = "
			SELECT
				COUNT(*) AS total_psm,
				COUNT(DISTINCT cs.CompId) AS unique_complaints,
				COUNT(DISTINCT cs.OfficerId) AS officers_involved,
				SUM(CASE WHEN repeated.Total_PSM > 1 THEN 1 ELSE 0 END) AS repeated_psm_actions
			".$this->psm_base_from()."
			LEFT JOIN (
				SELECT CompId, COUNT(*) AS Total_PSM
				FROM complaintsummary
				WHERE Remarks LIKE ?
				GROUP BY CompId
			) repeated ON repeated.CompId = cs.CompId
			".$where;
		array_unshift($params, "%".$this->psm_remark_text()."%");
		$r = $this->psm_query($sql, $params);
		return isset($r[0]) ? $r[0] : (object)array("total_psm" => 0, "unique_complaints" => 0, "officers_involved" => 0, "repeated_psm_actions" => 0);
	}

	public function get_psm_audit_report($filters)
	{
		$params = array();
		$where = $this->psm_filter_sql($filters, $params);
		$sql = "
			SELECT
				cs.CompId,
				cs.StDate,
				cs.OfficerId,
				om.officername,
				om.officerno,
				om.loginuserid,
				cs.Status AS psm_status,
				cs.Remarks,
				d.Departname_E AS department_name,
				dist.District_Name_E AS district_name,
				c.StatusRemark AS complaint_status,
				curr.officerid AS CurrentOfficerId,
				curr.officername AS CurrentOfficer,
				curr.officerno AS CurrentMobile,
				curr.loginuserid AS CurrentLogin
			".$this->psm_base_from()."
			".$where."
			ORDER BY cs.CompId, cs.StDate DeSC";
		return $this->psm_query($sql, $params);
	}

	public function get_psm_history_report($filters)
	{
		$params = array();
		$where = $this->psm_filter_sql($filters, $params);
		$sql = "
			SELECT
				cs.CompId,
				COUNT(*) AS Total_PSM,
				COUNT(DISTINCT cs.OfficerId) AS Unique_PSM_Officers,
				CASE WHEN COUNT(*) > COUNT(DISTINCT cs.OfficerId) THEN 'Yes' ELSE 'No' END AS Same_Officer_Repeated,
				curr.officername AS CurrentOfficer,
				curr.officerno AS CurrentMobile,
				curr.loginuserid AS CurrentLogin,
				d.Departname_E AS department_name,
				dist.District_Name_E AS district_name,
				c.StatusRemark AS complaint_status,
				MIN(cs.StDate) AS First_PSM_Date,
				MAX(cs.StDate) AS Last_PSM_Date,
				DATEDIFF(CURDATE(), DATE(MIN(cs.StDate))) AS Complaint_Age_Days
			".$this->psm_base_from()."
			".$where."
			GROUP BY cs.CompId, curr.officername, curr.officerno, curr.loginuserid, d.Departname_E, dist.District_Name_E, c.StatusRemark
			HAVING COUNT(*) > 1
			ORDER BY Total_PSM DESC";
		return $this->psm_query($sql, $params);
	}

	public function get_psm_complaint_timeline($complaint_no)
	{
		$live = $this->live_db();
		$psm_remark = "%".$this->psm_remark_text()."%";

		$summary_sql = "
			SELECT
				c.compid AS CompId,
				c.StatusRemark AS complaint_status,
				d.Departname_E AS department_name,
				dist.District_Name_E AS district_name,
				curr.officerid AS CurrentOfficerId,
				curr.officername AS CurrentOfficer,
				curr.officerno AS CurrentMobile,
				curr.loginuserid AS CurrentLogin,
				COUNT(cs.CompId) AS Total_PSM,
				COUNT(DISTINCT cs.OfficerId) AS Unique_PSM_Officers,
				MIN(cs.StDate) AS First_PSM_Date,
				MAX(cs.StDate) AS Last_PSM_Date,
				DATEDIFF(CURDATE(), DATE(MIN(cs.StDate))) AS Complaint_Age_Days
			FROM complaints c
			LEFT JOIN complaintsummary cs ON cs.CompId = c.compid AND cs.Remarks LIKE ?
			LEFT JOIN officermaster curr ON curr.officerid = c.officerid
			LEFT JOIN department d ON d.Departid = c.compdepart
			LEFT JOIN district dist ON dist.District_Code = c.callerdistcode
			WHERE c.compid = ?
			GROUP BY c.compid, c.StatusRemark, d.Departname_E, dist.District_Name_E, curr.officerid, curr.officername, curr.officerno, curr.loginuserid";
		$summary = $live->query($summary_sql, array($psm_remark, $complaint_no));

		$events_sql = "
			SELECT
				cs.CompId,
				cs.StDate,
				cs.OfficerId,
				om.officername,
				om.officerno,
				om.loginuserid,
				cs.Status AS psm_status,
				cs.Remarks,
				d.Departname_E AS department_name,
				c.StatusRemark AS complaint_status,
				curr.officerid AS CurrentOfficerId,
				curr.officername AS CurrentOfficer,
				curr.officerno AS CurrentMobile,
				curr.loginuserid AS CurrentLogin
			FROM complaintsummary cs
			INNER JOIN officermaster om ON om.officerid = cs.OfficerId
			LEFT JOIN complaints c ON c.compid = cs.CompId
			LEFT JOIN officermaster curr ON curr.officerid = c.officerid
			LEFT JOIN department d ON d.Departid = c.compdepart
			WHERE cs.CompId = ? AND cs.Remarks LIKE ?
			ORDER BY cs.StDate ASC, cs.OfficerId ASC";
		$events = $live->query($events_sql, array($complaint_no, $psm_remark));

		return array(
			"summary" => ($summary->num_rows() > 0) ? $summary->row() : FALSE,
			"events" => ($events->num_rows() > 0) ? $events->result() : array()
		);
	}

	public function get_psm_officer_analysis($filters)
	{
		$params = array();
		$where = $this->psm_filter_sql($filters, $params);
		$sql = "
			SELECT
				om.officerid,
				om.officername,
				om.loginuserid,
				om.officerno,
				COUNT(*) AS Total_PSM,
				COUNT(DISTINCT cs.CompId) AS Unique_Complaints
			".$this->psm_base_from()."
			".$where."
			GROUP BY om.officerid, om.officername, om.loginuserid, om.officerno
			ORDER BY Total_PSM DESC";
		return $this->psm_query($sql, $params);
	}

	public function get_psm_department_analysis($filters)
	{
		$params = array();
		$where = $this->psm_filter_sql($filters, $params);
		$sql = "
			SELECT
				IFNULL(d.Departname_E, 'Not Mapped') AS Departname_E,
				COUNT(*) AS Total_PSM,
				COUNT(DISTINCT cs.CompId) AS Total_Complaints
			".$this->psm_base_from()."
			".$where."
			GROUP BY IFNULL(d.Departname_E, 'Not Mapped')
			ORDER BY Total_PSM DESC";
		return $this->psm_query($sql, $params);
	}

	public function get_psm_top_repeated_complaints($filters)
	{
		$params = array();
		$where = $this->psm_filter_sql($filters, $params);
		$sql = "
			SELECT
				cs.CompId,
				COUNT(*) AS Total_PSM,
				curr.officername AS CurrentOfficer,
				c.StatusRemark AS complaint_status
			".$this->psm_base_from()."
			".$where."
			GROUP BY cs.CompId, curr.officername, c.StatusRemark
			HAVING COUNT(*) > 1
			ORDER BY Total_PSM DESC
			LIMIT 100";
		return $this->psm_query($sql, $params);
	}

	public function get_psm_same_officer_repeated_report($filters)
	{
		$params = array();
		$where = $this->psm_filter_sql($filters, $params);
		$sql = "
			SELECT
				cs.CompId,
				cs.OfficerId,
				om.officername,
				om.officerno,
				om.loginuserid,
				COUNT(*) AS PSM_Count,
				MIN(cs.StDate) AS First_PSM_Date,
				MAX(cs.StDate) AS Last_PSM_Date
			".$this->psm_base_from()."
			".$where."
			GROUP BY
				cs.CompId,
				cs.OfficerId,
				om.officername,
				om.officerno,
				om.loginuserid
			HAVING COUNT(*) > 1
			ORDER BY PSM_Count DESC";
		return $this->psm_query($sql, $params);
	}

	public function get_psm_complaint_summary_report($filters)
	{
		$params = array();
		$where = $this->psm_filter_sql($filters, $params);
		$sql = "
			SELECT
				cs.CompId,
				COUNT(*) AS Total_PSM,
				GROUP_CONCAT(
					DISTINCT CONCAT(
						IFNULL(om.officername, '-'),
						' (',
						IFNULL(om.loginuserid, '-'),
						' / ',
						IFNULL(om.officerno, '-'),
						')'
					)
					ORDER BY om.officername
					SEPARATOR ' | '
				) AS Officers
			".$this->psm_base_from()."
			".$where."
			GROUP BY cs.CompId 
			HAVING COUNT(*) > 1
			ORDER BY Total_PSM , cs.CompId DESC";
		return $this->psm_query($sql, $params);
	}

	private function lower_level_filter_sql($filters, &$params)
	{
		$where = "
			WHERE
				(cs.Remarks LIKE ? OR cs.StatusRemarkId IN (53,22,34))
				AND cs.statusCode IN ('L1','L2','L3')
				AND (
					c.OfficerLevel = 'L4'
					OR (c.OfficerLevel = 'L3' AND om.deptid = 40)
				)
		";
		$params[] = "%को पुनःप्रेषित की गयी है%";

		if(!empty($filters["from_date"]))
		{
			$where .= " AND DATE(cs.StDate) >= ? ";
			$params[] = $filters["from_date"];
		}
		if(!empty($filters["to_date"]))
		{
			$where .= " AND DATE(cs.StDate) <= ? ";
			$params[] = $filters["to_date"];
		}
		if(!empty($filters["complaint_no"]))
		{
			$where .= " AND cs.CompId = ? ";
			$params[] = $filters["complaint_no"];
		}
		if(!empty($filters["department"]))
		{
			$where .= " AND c.compdepart = ? ";
			$params[] = $filters["department"];
		}
		if(!empty($filters["officer"]))
		{
			$where .= " AND cs.OfficerId = ? ";
			$params[] = $filters["officer"];
		}
		if(!empty($filters["current_officer"]))
		{
			$where .= " AND c.officerid = ? ";
			$params[] = $filters["current_officer"];
		}
		if(!empty($filters["login_user_id"]))
		{
			$where .= " AND (om.loginuserid LIKE ? OR curr.loginuserid LIKE ?) ";
			$params[] = "%".$filters["login_user_id"]."%";
			$params[] = "%".$filters["login_user_id"]."%";
		}
		if(!empty($filters["complaint_status"]))
		{
			$where .= " AND c.StatusRemark = ? ";
			$params[] = $filters["complaint_status"];
		}
		if(!empty($filters["district"]))
		{
			$where .= " AND c.callerdistcode = ? ";
			$params[] = $filters["district"];
		}

		return $where;
	}

	private function lower_level_base_from()
	{
		return "
			FROM complaintsummary cs FORCE INDEX (statusReId)
			INNER JOIN officermaster om ON om.officerid = cs.OfficerId
			LEFT JOIN complaints c ON c.compid = cs.CompId
			LEFT JOIN officermaster curr ON curr.officerid = c.officerid
			LEFT JOIN department d ON d.Departid = c.compdepart
			LEFT JOIN district dist ON dist.District_Code = c.callerdistcode
		";
	}

	private function lower_level_query($sql, $params)
	{
		$live = $this->live_db();
		$r = $live->query($sql, $params);
		if($r->num_rows() > 0){ return $r->result(); }else{ return array(); }
	}

	public function get_lower_level_officer_report($filters)
	{
		$params = array();
		$where = $this->lower_level_filter_sql($filters, $params);
		$sql = "
			SELECT
				cs.OfficerId,
				om.officername,
				om.loginuserid,
				om.officerno,
				COUNT(*) AS Total_Reverted
			".$this->lower_level_base_from()."
			".$where."
			GROUP BY
				cs.OfficerId,
				om.officername,
				om.loginuserid,
				om.officerno
			ORDER BY Total_Reverted DESC";
		return $this->lower_level_query($sql, $params);
	}

	public function get_lower_level_same_officer_report($filters)
	{
		$params = array();
		$where = $this->lower_level_filter_sql($filters, $params);
		$sql = "
			SELECT
				cs.CompId,
				cs.OfficerId,
				om.officername,
				om.loginuserid,
				om.officerno,
				COUNT(*) AS Revert_Count
			".$this->lower_level_base_from()."
			".$where."
			GROUP BY
				cs.CompId,
				cs.OfficerId,
				om.officername,
				om.loginuserid,
				om.officerno
			HAVING COUNT(*) > 1
			ORDER BY Revert_Count DESC";
		return $this->lower_level_query($sql, $params);
	}

	public function get_lower_level_complaint_summary_report($filters)
	{
		$params = array();
		$where = $this->lower_level_filter_sql($filters, $params);
		$sql = "
			SELECT
				cs.CompId,
				COUNT(*) AS Total_Reverted,
				GROUP_CONCAT(
					DISTINCT CONCAT(
						IFNULL(om.officername, '-'),
						' (',
						IFNULL(om.loginuserid, '-'),
						' / ',
						IFNULL(om.officerno, '-'),
						')'
					)
					ORDER BY om.officername
					SEPARATOR ' | '
				) AS Officers
			".$this->lower_level_base_from()."
			".$where."
			GROUP BY cs.CompId
			HAVING COUNT(*) > 1
			ORDER BY Total_Reverted DESC";
		return $this->lower_level_query($sql, $params);
	}

	public function get_lower_level_audit_report($filters)
	{
		$params = array();
		$where = $this->lower_level_filter_sql($filters, $params);
		$sql = "
			SELECT
				cs.CompId,
				cs.StDate,
				cs.StatusRemarkId,
				cs.statusCode AS TargetLevel,
				cs.OfficerId,
				om.officername,
				om.loginuserid,
				om.officerno,
				cs.Remarks,
				d.Departname_E AS department_name,
				dist.District_Name_E AS district_name,
				c.StatusRemark AS complaint_status,
				curr.officerid AS CurrentOfficerId,
				curr.officername AS CurrentOfficer,
				curr.officerno AS CurrentMobile,
				curr.loginuserid AS CurrentLogin
			".$this->lower_level_base_from()."
			".$where."
			ORDER BY cs.CompId, cs.StDate";
		return $this->lower_level_query($sql, $params);
	}

	public function get_lower_level_history_report($filters)
	{
		$params = array();
		$where = $this->lower_level_filter_sql($filters, $params);
		$sql = "
			SELECT
				cs.CompId,
				COUNT(*) AS Total_Reverted,
				COUNT(DISTINCT cs.OfficerId) AS Unique_Officers,
				curr.officername AS CurrentOfficer,
				curr.officerno AS CurrentMobile,
				curr.loginuserid AS CurrentLogin,
				d.Departname_E AS department_name,
				dist.District_Name_E AS district_name,
				c.StatusRemark AS complaint_status,
				MIN(cs.StDate) AS First_Action_Date,
				MAX(cs.StDate) AS Last_Action_Date,
				DATEDIFF(CURDATE(), DATE(MIN(cs.StDate))) AS Complaint_Age_Days
			".$this->lower_level_base_from()."
			".$where."
			GROUP BY cs.CompId, curr.officername, curr.officerno, curr.loginuserid, d.Departname_E, dist.District_Name_E, c.StatusRemark
			ORDER BY Total_Reverted DESC";
		return $this->lower_level_query($sql, $params);
	}

	public function get_lower_level_complaint_timeline($complaint_no)
	{
		$live = $this->live_db();
		$lower_remark = "%को पुनःप्रेषित की गयी है%";

		$summary_sql = "
			SELECT
				c.compid AS CompId,
				c.StatusRemark AS complaint_status,
				d.Departname_E AS department_name,
				dist.District_Name_E AS district_name,
				curr.officerid AS CurrentOfficerId,
				curr.officername AS CurrentOfficer,
				curr.officerno AS CurrentMobile,
				curr.loginuserid AS CurrentLogin,
				COUNT(cs.CompId) AS Total_Reverted,
				COUNT(DISTINCT cs.OfficerId) AS Unique_Officers,
				MIN(cs.StDate) AS First_Action_Date,
				MAX(cs.StDate) AS Last_Action_Date,
				DATEDIFF(CURDATE(), DATE(MIN(cs.StDate))) AS Complaint_Age_Days
			FROM complaints c
			LEFT JOIN complaintsummary cs FORCE INDEX (statusReId) ON cs.CompId = c.compid
				AND (cs.Remarks LIKE ? OR cs.StatusRemarkId IN (53,22,34))
				AND cs.statusCode IN ('L1','L2','L3')
			LEFT JOIN officermaster om ON om.officerid = cs.OfficerId
			LEFT JOIN officermaster curr ON curr.officerid = c.officerid
			LEFT JOIN department d ON d.Departid = c.compdepart
			LEFT JOIN district dist ON dist.District_Code = c.callerdistcode
			WHERE
				c.compid = ?
				AND (
					c.OfficerLevel = 'L4'
					OR (c.OfficerLevel = 'L3' AND om.deptid = 40)
				)
			GROUP BY c.compid, c.StatusRemark, d.Departname_E, dist.District_Name_E, curr.officerid, curr.officername, curr.officerno, curr.loginuserid";
		$summary = $live->query($summary_sql, array($lower_remark, $complaint_no));

		$events_sql = "
			SELECT
				cs.CompId,
				cs.StDate,
				cs.StatusRemarkId,
				cs.statusCode AS TargetLevel,
				cs.OfficerId,
				om.officername,
				om.loginuserid,
				om.officerno,
				cs.Status AS action_status,
				cs.Remarks,
				d.Departname_E AS department_name,
				c.StatusRemark AS complaint_status,
				curr.officerid AS CurrentOfficerId,
				curr.officername AS CurrentOfficer,
				curr.officerno AS CurrentMobile,
				curr.loginuserid AS CurrentLogin
			FROM complaintsummary cs FORCE INDEX (statusReId)
			INNER JOIN officermaster om ON om.officerid = cs.OfficerId
			LEFT JOIN complaints c ON c.compid = cs.CompId
			LEFT JOIN officermaster curr ON curr.officerid = c.officerid
			LEFT JOIN department d ON d.Departid = c.compdepart
			WHERE
				cs.CompId = ?
				AND (cs.Remarks LIKE ? OR cs.StatusRemarkId IN (53,22,34))
				AND cs.statusCode IN ('L1','L2','L3')
				AND (
					c.OfficerLevel = 'L4'
					OR (c.OfficerLevel = 'L3' AND om.deptid = 40)
				)
			ORDER BY cs.StDate ASC, cs.OfficerId ASC";
		$events = $live->query($events_sql, array($complaint_no, $lower_remark));

		return array(
			"summary" => ($summary->num_rows() > 0) ? $summary->row() : FALSE,
			"events" => ($events->num_rows() > 0) ? $events->result() : array()
		);
	}

	public function get_attribute($department)
	{
		$this->db->select('attribID, attribname, attribname_E');
		$this->db->from('complaintattrib');
		$this->db->where('isdisabled', 0);
		$this->db->where('subdepid', $department);

		$r = $this->db->get();

		if ($r->num_rows() > 0) {
			return $r->result();
		} else {
			return FALSE;
		}
	}


	public function get_other_related_complaints($role_id, $emp_id)
	{
		$this->db->select("t1.*");
		$this->db->from("high_complaint_creation t1");
		
		if((int)$role_id !== 1)
		{	
			$this->db->where("t1.added_by", $emp_id);
		}
		$this->db->order_by("t1.added_at", "DESC");
		$r = $this->db->get();
		if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
	}

	public function get_all_districts()
    {
        
        $this->db->order_by('Priority', 'ASC');

        $r = $this->db->get('district');

        if ($r->num_rows() > 0) {
            return $r->result();
        } else {
            return FALSE;
        }
    }
	 public function insert_disaster_details($data)
	{
		
		$this->db->insert("register_disaster_case", $data);
		return $this->db->insert_id();
	}

	 public function get_all_disasters_details()
    {
		$sql = "SELECT * FROM register_disaster_case left join district on register_disaster_case.district = district.District_Code left join master_users on register_disaster_case.added_by = master_users.emp_id where register_disaster_case.status =  1 order by register_disaster_case.id desc";
        $rs = $this->db->query($sql);
        if($rs->num_rows() == 0) {return false;}else {return $rs->result(); }
	}

	public function get_all_isat_number_deocs()
	{
		$this->db->where("status", 1);
		$this->db->order_by("id", "DESC");
		$r = $this->db->get("isat_number_deocs");
		if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
	}

	public function insert_isat_number_deocs($data)
	{
		$this->db->insert("isat_number_deocs", $data);
		return $this->db->insert_id();
	}

	public function update_isat_number_deocs($id, $data)
	{
		$this->db->where("id", $id);
		$this->db->where("status", 1);
		$this->db->update("isat_number_deocs", $data);
		return $this->db->affected_rows();
	}

	public function delete_isat_number_deocs($id, $data)
	{
		$this->db->where("id", $id);
		$this->db->where("status", 1);
		$this->db->update("isat_number_deocs", $data);
		return $this->db->affected_rows();
	}

	public function get_all_deocs_numbers()
	{
		$this->db->where("status", 1);
		$this->db->order_by("id", "DESC");
		$r = $this->db->get("deocs_numbers");
		if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
	}

	public function insert_deocs_numbers($data)
	{
		$this->db->insert("deocs_numbers", $data);
		return $this->db->insert_id();
	}

	public function update_deocs_numbers($id, $data)
	{
		$this->db->where("id", $id);
		$this->db->where("status", 1);
		$this->db->update("deocs_numbers", $data);
		return $this->db->affected_rows();
	}

	public function delete_deocs_numbers($id, $data)
	{
		$this->db->where("id", $id);
		$this->db->where("status", 1);
		$this->db->update("deocs_numbers", $data);
		return $this->db->affected_rows();
	}


	public function get_all_portal_issue_details(){
		$sql = "SELECT * FROM portal_issue_cases  left join master_users on portal_issue_cases.added_by = master_users.emp_id where portal_issue_cases.status =  1 order by portal_issue_cases.id desc";
        $rs = $this->db->query($sql);
        if($rs->num_rows() == 0) {return false;}else {return $rs->result(); }
	}

	public function insert_portal_issue_details($data)
	{
		
		$this->db->insert("portal_issue_cases", $data);
		return $this->db->insert_id();
	}
	public function insert_portal_issue_followup($data)
	{
		
		$this->db->insert("portal_issue_followup", $data);
		return $this->db->insert_id();
	}
	public function update_portal_issue_details($data , $id)
	{
		$this->db->where("id", $id);
		$this->db->where("status", 1);
		$this->db->update("portal_issue_cases", $data);
		return $this->db->affected_rows();
	}

	public function get_portal_issue_history($portal_issue_id)
	{
		$this->db->select("f.*, u.user_name as added_by_name");
		$this->db->from("portal_issue_followup f");
		$this->db->join("master_users u", "f.added_by = u.emp_id", "left");
		$this->db->where("f.case_id", $portal_issue_id);
		$this->db->order_by("f.added_at", "DESC");
		$r = $this->db->get();
		if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
	}

	public function get_owa_details_by_id($id)
	{
		$r = $this->db->get_where("owa_report", array("id" => $id, "status" => 1));
		if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
	}

	public function update_owa_details($data, $id)
	{
		$this->db->where("id", $id);
		$this->db->where("status", 1);
		$this->db->update("owa_report", $data);
		return $this->db->affected_rows();
	}
	public function insert_demandsuggestion_complaint_details($insert_arr)
	 {
		$this->db->insert("demand_suggestion_creation" , $insert_arr);
		return $this->db->insert_id();	
	 }

	 public function get_demand_suggestion_details($role_id, $emp_id, $from_date = "", $to_date = "")
		{
			$sql = "
				SELECT 
					t1.*, 
					t2.Departname_E AS department_name,
					t3.attribname_E AS attribute_name

				FROM demand_suggestion_creation t1

				LEFT JOIN department t2 
					ON t1.department = t2.Departid 

				LEFT JOIN complaintattrib t3 
					ON t1.attribute = t3.attribID 

				WHERE 1=1
			";

			$params = array();

			// Role wise condition
			if($role_id != 1)
			{
				$sql .= " AND t1.added_by = ?";
				$params[] = $emp_id;
			}

			// From date
			if($from_date != "")
			{
				$sql .= " AND DATE(t1.added_at) >= ?";
				$params[] = $from_date;
			}

			// To date
			if($to_date != "")
			{
				$sql .= " AND DATE(t1.added_at) <= ?";
				$params[] = $to_date;
			}

			$sql .= " ORDER BY t1.id DESC";

			$r = $this->db->query($sql, $params);

			if($r->num_rows() > 0)
			{
				return $r->result();
			}

			return FALSE;
		}

		public function check_duplicate($complaint_no,$table_name)
		{
			return $this->db
						->where('comp_id', $complaint_no)
						->get($table_name)
						->num_rows();
		}

		public function insert_number_change_request($insert_arr)
		{
			$this->db->insert("number_change_requests" , $insert_arr);
			return $this->db->insert_id();	
		}


		public function get_number_change_reports($role_id, $emp_id)
		{
			$this->db->select("t1.*, agent.user_name as agent_name, agent.msd_id as agent_msd_id, creator.user_name as created_by_name");
			$this->db->from("number_change_requests t1");
			$this->db->join("master_users agent", "t1.added_by = agent.emp_id", "left");
			$this->db->join("master_users creator", "t1.added_by = creator.emp_id", "left");
			$this->db->where("t1.status", 1);
			// if((int)$role_id !== 1)
			// {
			// 	$this->db->group_start();
			// 	$this->db->where("t1.agent_id", $emp_id);
			// 	$this->db->or_where("t1.created_by", $emp_id);
			// 	$this->db->group_end();
			// }
			$this->db->order_by("t1.added_at", "DESC");
			$r = $this->db->get();
			if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
		}

		public function update_number_change_detail($update_arr, $id)
		{
			$this->db->where("id" , $id);
			$this->db->update("number_change_requests" , $update_arr);
			return $this->db->affected_rows();
		}

		public function correct_incorrect_data_assign($role_id, $emp_id, $number, $date, $agent_ids)
		{
			$live = $this->live_db();

			// ---- Manually build query with proper escaping (placeholder ki jagah) ----
			$number_safe = (int) $number;

			if (!empty($date)) {
				$date_safe = $live->escape($date); // ye automatically quotes bhi laga dega: '18/08/2026'
				$sql = "CALL sp_adv3_new1($date_safe, $number_safe)";
			} else {
				$sql = "CALL sp_adv3_new1(NULL, $number_safe)";
			}

			$query = $live->query($sql);

			// ---- Yahan turant check karo query fail to nahi hui ----
			if ($query === FALSE) {
				$error = $live->error(); // array with 'code' and 'message'
				log_message('error', 'SP Error: ' . print_r($error, true));
				return ['response' => FALSE, 'message' => 'DB Error: ' . $error['message']];
			}

			$records = $query->result();

			// ---- Extra result sets clear karo (procedure status) ----
			while ($live->conn_id->more_results() && $live->conn_id->next_result()) {
				if ($extra = $live->conn_id->store_result()) {
					$extra->free();
				}
			}

			if (empty($records)) {
				return ['response' => FALSE, 'message' => 'No data found from procedure.'];
			}

			// ---- Round-robin equal distribution ----
			$total_agents = count($agent_ids);
			$batch_id     = date('YmdHis') . '_' . $emp_id;

			$filter_date_value = NULL;
			if (!empty($date)) {
				$date_obj = DateTime::createFromFormat('d/m/Y', $date);
				if ($date_obj !== FALSE) {
					$filter_date_value = $date_obj->format('Y-m-d');
				}
			}

			$insert_data = [];
			foreach ($records as $index => $row) {
				$assigned_to = $agent_ids[$index % $total_agents];

				$insert_data[] = [
					'batch_id'              => $batch_id,
					'compid'                => $row->compid,
					'compdate'              => !empty($row->compdate) ? date('Y-m-d', strtotime($row->compdate)) : NULL,
					'created_by_agent_id'   => $row->agent_id,
					'assigned_to_emp_id'    => $assigned_to,
					'phone'                 => $row->Phone,
					'tl_name'               => $row->{'TL Name'} ?? null,
					// 'dept'                  => $row->Dept,
					// 'ca'                    => $row->CA,
					// 'compremarks'           => $row->compremarks,
					'filter_date'           => $filter_date_value,
				];
			}

			$this->db->insert_batch('tbl_correct_incorrect_assign', $insert_data);

			$summary = [];
			foreach ($insert_data as $d) {
				$summary[$d['assigned_to_emp_id']] = ($summary[$d['assigned_to_emp_id']] ?? 0) + 1;
			}

			return [
				'response'      => TRUE,
				'batch_id'      => $batch_id,
				'total_records' => count($insert_data),
				'summary'       => $summary
			];
		}

		public function today_assign_data($role_id, $emp_id, $filter_date = null)
		{
			$this->db->where('assigned_to_emp_id', $emp_id);

			if (!empty($filter_date)) {
				$this->db->where('created_at >=', $filter_date . ' 00:00:00');
				$this->db->where('created_at <=', $filter_date . ' 23:59:59');
			}

			$this->db->order_by('compid', 'desc');
			//$this->db->limit(10);

			$rs = $this->db->get('tbl_correct_incorrect_assign');

			return ($rs->num_rows() > 0) ? $rs->result() : [];
		}

public function update_assign_data($id, $data)
{
    $this->db->where('id', $id);
    return $this->db->update('tbl_correct_incorrect_assign', $data);
}
public function get_assign_row($id)
{
    return $this->db->get_where('tbl_correct_incorrect_assign', ['id' => $id])->row();
}



// public function get_complaint_details($compid)
// {
//     $live = $this->live_db();

//     // 1. First check in complaints table
//     $live->select('
//         c.*,
//         d.Departname_E AS department_name,
//         dist.District_Name_E AS district_name,
//         om.officername AS current_officer_name,
//         om.officerno AS current_officer_no,
//         om.loginuserid AS current_officer_login
//     ');

//     $live->from('complaints c');

//     $live->join(
//         'department d',
//         'c.compdepart = d.Departid',
//         'left'
//     );

//     $live->join(
//         'district dist',
//         'c.callerdistcode = dist.District_Code',
//         'left'
//     );

//     $live->join(
//         'officermaster om',
//         'c.officerid = om.officerid',
//         'left'
//     );

//     $live->where('c.compid', $compid);

//     $query = $live->get();

//     // Record found in complaints
//     if ($query !== false && $query->num_rows() > 0) {
//         return $query->result();
//     }


//     // 2. If not found, check in cls_complaints
//     $live->select('
//         c.*,
//         d.Departname_E AS department_name,
//         dist.District_Name_E AS district_name,
//         om.officername AS current_officer_name,
//         om.officerno AS current_officer_no,
//         om.loginuserid AS current_officer_login
//     ');

//     $live->from('cls_complaints c');

//     $live->join(
//         'department d',
//         'c.compdepart = d.Departid',
//         'left'
//     );

//     $live->join(
//         'district dist',
//         'c.callerdistcode = dist.District_Code',
//         'left'
//     );

//     $live->join(
//         'officermaster om',
//         'c.officerid = om.officerid',
//         'left'
//     );

//     $live->where('c.compid', $compid);

//     $query = $live->get();

//     // Record found in cls_complaints
//     if ($query !== false && $query->num_rows() > 0) {
//         return $query->result();
//     }

//     // Not found in either table
//     return false;
// }

public function get_complaint_details($compId)
{
    $live = $this->live_db();

    // ---- Procedure 1: Complaint ki poori detail ----
    $query = $live->query("CALL GetComplaintByCompid(?)", array($compId));
    $details = $query ? $query->row() : null;
    $this->clear_mysql_procedure_result($live);

    if (!$details) {
        return FALSE;
    }

    // ---- Procedure 2: Date-wise remark/status summary ----
    $query2 = $live->query("CALL GETCOMPLAINTSUMMARY(?)", array($compId));
    $summary = $query2 ? $query2->result() : array();
    $this->clear_mysql_procedure_result($live);   // <-- yeh line missing thi, ab add ki

    // ---- Procedure 3: Area details ----
    $query3 = $live->query("CALL getAddressByCompid(?)", array($compId));
    $area_details = $query3 ? $query3->result() : array();
    $this->clear_mysql_procedure_result($live);

    return array(
        'details'      => $details,
        'summary'      => $summary,
        'area_details' => $area_details
    );
}

// Ab yeh function connection accept karega, sirf $this->db par fix nahi rahega
private function clear_mysql_procedure_result($db_conn)
{
    $conn = $db_conn->conn_id;

    if ($conn instanceof mysqli) {
        while ($conn->more_results() && $conn->next_result()) {
            if ($res = $conn->store_result()) {
                $res->free();
            }
        }
    }
}

// Distinct TL list — jo TLs ke against data assign hua hai
public function get_active_tl_list($filter_date = null)
{
    $this->db->distinct();
    $this->db->select('tl_name');
    $this->db->where('tl_name IS NOT NULL', null, false);
    $this->db->where('tl_name !=', '');

    if (!empty($filter_date)) {
        $this->db->where('created_at >=', $filter_date . ' 00:00:00');
        $this->db->where('created_at <=', $filter_date . ' 23:59:59');
    }

    $this->db->order_by('tl_name', 'asc');
    return $this->db->get('tbl_correct_incorrect_assign')->result();
}

// Admin view — koi emp_id restriction nahi, sirf date + TL filter
public function admin_assign_data($filter_date, $tl_name)
{
    if (!empty($filter_date)) {
        $this->db->where('created_at >=', $filter_date . ' 00:00:00');
        $this->db->where('created_at <=', $filter_date . ' 23:59:59');
    }

    if (!empty($tl_name)) {
        $this->db->where('tl_name', $tl_name);
    }

    $this->db->order_by('compid', 'desc');
    return $this->db->get('tbl_correct_incorrect_assign')->result();
}

// TL/Admin feedback save
public function update_tl_status($id, $data)
{
    $this->db->where('id', $id);
    return $this->db->update('tbl_correct_incorrect_assign', $data);
}

// Date-wise + agent-wise assign count. Agent ka naam employee master table se join hoga.
public function get_assign_summary($from_date = null, $to_date = null)
{
    $this->db->select(
        'DATE(t.created_at) AS assign_date,
         t.assigned_to_emp_id,
         e.user_name,
         e.msd_id,
         COUNT(t.id) AS day_count',
        false
    );

    $this->db->from('tbl_correct_incorrect_assign t');

    $this->db->join(
        'master_users e',
        'e.emp_id = t.assigned_to_emp_id',
        'left'
    );

    if (!empty($from_date)) {
        $this->db->where('t.created_at >=', $from_date . ' 00:00:00');
    }

    if (!empty($to_date)) {
        $this->db->where('t.created_at <=', $to_date . ' 23:59:59');
    }

    $this->db->group_by([
        'DATE(t.created_at)',
        't.assigned_to_emp_id'
    ]);

    $this->db->order_by('assign_date', 'DESC');
    $this->db->order_by('day_count', 'DESC');

    return $this->db->get()->result();
}

public function update_feedback_done($id, $time_duration)
{
    $data = array(
        'feedback_done'          => 'Yes',
        'feedback_time_duration' => $time_duration
    );

    $this->db->where('id', $id);
    return $this->db->update('tbl_correct_incorrect_assign', $data);
}

}
