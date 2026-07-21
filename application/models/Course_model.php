<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Course_model extends CI_Model {

    public function __construct()
    {
    $this->load->database();
    }

	
    // public function getAll(){
    //     return $this->db->order_by('id','desc')->get('courses')->result();
    // }

    public function insert($data){
        return $this->db->insert('courses',$data);
    }

    public function exists($name){
        return $this->db->where('course_name',$name)->get('courses')->row();
    }
	
	public function get_all_courses()
	 {
	  $r = $this->db->query("select * from courses where status = 1 order by id desc ");
	  if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
	 }
     public function get_course_details_by_id($course_id)
	 {
	  $r = $this->db->get_where("courses" , array("status" => 1 , "id" => $course_id));
	  if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
	 }
     public function update_course($course_id , $update_arr)
	 {
	  $this->db->where("id" , $course_id);
	  $this->db->update("courses" , $update_arr);
	  return $this->db->affected_rows();
	 }
}
