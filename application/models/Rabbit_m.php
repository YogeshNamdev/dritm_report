<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
defined('BASEPATH') OR exit('No direct script access allowed');

class Rabbit_m extends CI_Model {


	public function __construct()
	 {
	  $this->load->database();
	 }
	public function save_enquiry_form($insert_array)
	 {
	  $this->db->insert("master_enquiry_form" , $insert_array);
	  return $this->db->insert_id();
	 }
	 
	 public function save_want_us_to_contact_you($insert_array)
	 {
	  $this->db->insert("want_us_to_contact_you" , $insert_array);
	  return $this->db->insert_id();
	 }
	 
	 public function users_downloaded_syllabus($insert_array)
	 {
	  $this->db->insert("download_detailed_syllabus" , $insert_array);
	  return $this->db->insert_id();
	 }
	
	public function get_all_enquiry_details($grade_id)
	 {
	  $r = $this->db->query("select * from master_enquiry_form where grade = '".$grade_id."' and status=1 order by eat desc");
	  if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
	 }
	 
	 public function get_all_grade()
	 {
	  $r = $this->db->query("select * from master_grade where status=1");
	  if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
	 }
	 
	public function get_all_grade_time($grade)
	 {
	  $r = $this->db->query("select * from master_grade_and_demo_link where status=1 and grade_id=".$grade."");
	  if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
	 }
	 
	 public function get_all_grade_info($grade)
	 {
	  $r = $this->db->query("select * from master_grade_and_demo_link where status=1 and grade_id=".$grade."");
	  if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
	 }

	/*public function export_enquiry($enquiry_id)
	{
	 $r = $this->db->get_where("master_enquiry_form", array("status"=> 1 , "enquiry_id" => $enquiry_id));
	 if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
	}*/

       public function update_Enquiry($enquiry_id , $update_arr)
	 {
	  $this->db->where("enquiry_id" , $enquiry_id);
	  $this->db->update("master_enquiry_form" , $update_arr);
	  return $this->db->affected_rows();
	 }
	
	public function get_all_syllabus_downloaded_data()
	 {
	  //$r = $this->db->query("select * from download_detailed_syllabus where status=1 order by eat desc");
	  $r = $this->db->query("select t1.* , t2.grade_name from download_detailed_syllabus t1 join master_grade t2 where t1.status=1 and t1.grade_id = t2.grade_id and t2.status=1 order by eat desc");
	  if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
	 }
	 
	  public function update_syllabus_download_record($syllabus_id , $update_arr)
	 {
	  $this->db->where("syllabus_id" , $syllabus_id);
	  $this->db->update("download_detailed_syllabus" , $update_arr);
	  return $this->db->affected_rows();
	 }
    public function grade_list()
    {
         $sql = "SELECT t1.grade_id, t1.grade_name FROM master_grade t1 WHERE t1.status = 1";
         $rs = $this->db->query($sql);
         if($rs->num_rows() == 0) {
            return false;
         }
         else {
            return $rs->result();
         }
	}
	
	public function get_batch_details($grade_id)
    {
         $sql = "SELECT grade_id, grade_time, batch_start_time, batch_end_time FROM master_grade_and_demo_link WHERE status = 1 and grade_id = '".$grade_id."'";
         $rs = $this->db->query($sql);
         if($rs->num_rows() == 0) {
            return false;
         }
         else {
            return $rs->result();
         }
	}
	
}
