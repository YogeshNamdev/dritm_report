<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Masters_m extends CI_Model {


	public function __construct()
	 {
	  $this->load->database();
	 }
	
	
	public function validate_login($user_email , $user_password)
	 {
	  $r = $this->db->query("select t1.*,t2.role_name from master_users t1,master_roles t2 where t1.status=1 and t1.login_status=1 and t1.user_email='".$user_email."' and t1.user_password='".$user_password."' and t2.role_id=t1.role_id and t2.status=1");
	  if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
	 }
	
	
	public function check_class_exists($class_name)
	 {
	  $r = $this->db->get_where("master_class" , array("status" => 1 , "class_name" => trim($class_name)));
	  if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
	 }
	
	
	public function add_class($insert_arr)
	 {
	  $this->db->insert("master_class" , $insert_arr);
	  return $this->db->insert_id();
	 }
	
	
	public function get_all_class()
	 {
	  $r = $this->db->get_where("master_class" , array("status" => 1));
	  if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
	 }
	
	
	public function update_class($class_id , $update_arr)
	 {
	  $this->db->where("class_id" , $class_id);
	  $this->db->update("master_class" , $update_arr);
	  return $this->db->affected_rows();
	 }
	
	
	// public function check_email_exists($user_email)
	//  {
	//   $r = $this->db->query("select * from master_users where status=1 and user_email='".$user_email."'");
	//   if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
	//  }
	
	
	// public function add_user($insert_arr)
	//  {
	//   $this->db->insert("master_users" , $insert_arr);
	//   return $this->db->insert_id();
	//  }
	
	
	// public function get_all_users()
	//  {
	//   $r = $this->db->query("select t1.*,t2.role_name from master_users t1,master_roles t2 where t1.status=1 and t2.role_id=t1.role_id and t2.status=1");
	//   if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
	//  }
	
	
	// public function update_user($user_id , $update_arr)
	//  {
	//   $this->db->where("user_id" , $user_id);
	//   $this->db->update("master_users" , $update_arr);
	//   return $this->db->affected_rows();
	//  }
	

	public function check_subject_exists($subject_name)
	 {
	  $r = $this->db->get_where("master_subject" , array("status" => 1 , "subject_name" => trim($subject_name)));
	  if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
	 }
	

	 public function add_subject($insert_arr)
	 {
	  $this->db->insert("master_subject" , $insert_arr);
	  return $this->db->insert_id();
	 }

	 public function get_all_subject()
	 {
	  $r = $this->db->get_where("master_subject" , array("status" => 1));
	  if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
	 }

	 public function update_subject($subject_id , $update_arr)
	 {
	  $this->db->where("subject_id" , $subject_id);
	  $this->db->update("master_subject" , $update_arr);
	  return $this->db->affected_rows();
	 }
}
