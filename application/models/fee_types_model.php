<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Fee_types_model extends CI_Model {

    public function __construct()
    {
    $this->load->database();
    }
    public function get_all_fee_types()
    {
    $r = $this->db->order_by("id","DESC")->get_where("fee_types" , array("status" => 1));
    if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
    }
    
    public function insert($data){
        return $this->db->insert('fee_types',$data);
    }
    public function exists($name){
        return $this->db->where('fee_name',$name)->get('fee_types')->row();
    }
    public function get_fee_types_details_by_id($fee_types_id)
    {
    $r = $this->db->get_where("fee_types" , array("status" => 1 , "id" => $fee_types_id));
    if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
    }
    public function update_batch($fee_types_id , $update_arr)
    {
    $this->db->where("id" , $fee_types_id);
    $this->db->update("fee_types" , $update_arr);
    return $this->db->affected_rows();
    }
}
