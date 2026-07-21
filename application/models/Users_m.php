<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_m extends CI_Model {


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
	
	
	
	
	
	
	
	public function validate_login($user_msd , $user_password, $role_id = NULL)
	 {
	  $this->db->select('t1.*, t2.role_name');
	  $this->db->from('master_users t1');
	  $this->db->join('master_roles t2', 't2.role_id = t1.role_id');
	  $this->db->where('t1.status', 1);
	  $this->db->where('t1.login_status', 1);
	  $this->db->where('t1.msd_id', $user_msd);
	  $this->db->where('t1.user_password', $user_password);
	  $this->db->where('t2.status', 1);
	  if($role_id !== NULL)
	   {
	    $this->db->where('t1.role_id', $role_id);
	   }
	  $r = $this->db->get();
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
	 
	 	public function get_all_user_roles()
	 {
	  $r = $this->db->get_where("master_roles" , array("status" => 1));
	  if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
	 }
	
	public function get_all_users()
	 {
	  $r = $this->db->query("select t1.*,t2.role_name from master_users t1,master_roles t2 where t1.status=1 and t2.role_id=t1.role_id and t2.status=1 order by t1.user_name");
	  if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
	 }
	 public function add_user_role($insert_arr)
	 {
        $this->db->insert("master_roles" , $insert_arr);
        return $this->db->insert_id();
	 
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
	  
}
