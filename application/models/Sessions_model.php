<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Sessions_model extends CI_Model {

    public function __construct()
    {
    $this->load->database();
    }

    public function insert($data){
        return $this->db->insert('sessions',$data);
    }

    public function exists($name){
        return $this->db->where('session_name',$name)->get('sessions')->row();
    }
	
	public function get_all_sessions()
	 {
	  $r = $this->db->query("select * from sessions where status = 1 order by id desc ");
	  if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
	 }
     public function get_session_details_by_id($session_id)
	 {
	  $r = $this->db->get_where("sessions" , array("status" => 1 , "id" => $session_id));
	  if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
	 }
     public function update_session($session_id , $update_arr)
	 {
	  $this->db->where("id" , $session_id);
	  $this->db->update("sessions" , $update_arr);
	  return $this->db->affected_rows();
	 }
}
