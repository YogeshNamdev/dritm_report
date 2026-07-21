<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Batches_model extends CI_Model {

    public function __construct()
    {
    $this->load->database();
    }
    public function get_all_session_list()
    {
    $r = $this->db->get_where("sessions" , array("status" => 1));
    if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
    }
    public function get_all_course_list()
    {
    $r = $this->db->get_where("courses" , array("status" => 1));
    if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
    }
    public function insert($data){
        return $this->db->insert('batches',$data);
    }

   public function exists($name, $session_id, $course_id, $exclude_id = null)
    {
        $this->db->where([
            'batch_name' => $name,
            'session_id' => $session_id,
            'course_id'  => $course_id
        ]);

        if($exclude_id){
            $this->db->where('id !=', $exclude_id);
        }

        return $this->db->count_all_results('batches') > 0;
    }
	
	public function get_all_batches()
	 {
	  $r = $this->db->query("select batches.*,sessions.session_name,courses.course_name from batches left join sessions on session_id=sessions.id left join courses on courses.id=course_id where batches.status = 1 order by batches.id desc; ");
	  if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
	 }
     public function get_batch_details_by_id($batche_id)
	 {
	  $r = $this->db->get_where("batches" , array("status" => 1 , "id" => $batche_id));
	  if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
	 }
     public function update_batch($batche_id , $update_arr)
	 {
	  $this->db->where("id" , $batche_id);
	  $this->db->update("batches" , $update_arr);
	  return $this->db->affected_rows();
	 }
}
