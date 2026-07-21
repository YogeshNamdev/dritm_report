<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Fee_structure_model extends CI_Model {

    public function __construct()
    {
    $this->load->database();
    }
      function insert($data){
        return $this->db->insert('fee_structures',$data);
    }

    function get_fee_structure_detail_by_id($course_id){
        return $this->db->where('course_id',$course_id)
        ->get('fee_structures')->result();
    }

    public function get_all_fee_structure()
	 {
	  $r = $this->db->query("select fee_structures.*, courses.course_name,fee_types.fee_name from fee_structures left join courses on fee_structures.course_id=courses.id left join fee_types on fee_types.id=fee_structures.fee_type_id where fee_structures.status = 1 order by fee_structures.id desc;");
	  if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
	 }

    public function update_fee_structure($course_id, $type_id, $update_arr)
    {
        $this->db->where('course_id', $course_id);
        $this->db->where('fee_type_id', $type_id);

        $res = $this->db->update('fee_structures', $update_arr); 
        return $res;   // TRUE / FALSE return करो
    }

    public function delete_fee_structure($course_id, $update_arr)
    {
        $this->db->where('course_id', $course_id);
        
        $res = $this->db->update('fee_structures', $update_arr); 
        return $res;   // TRUE / FALSE return करो
    }
}
