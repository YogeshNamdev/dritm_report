<?php 


class Studentauth extends CI_Controller{

    function login(){
        $this->load->view('students/login');
    }

    function login_check(){
        
        $mobile=$this->input->post('mobile');
        $dob=$this->input->post('dob');

        $this->db->where('mobile',$mobile);
        $this->db->where('dob',$dob);

        $r=$this->db->get('students')->row();

        if($r){

        $this->session->set_userdata('student_id',$r->id);

        echo json_encode([
        "response"=>true
        ]);

        }else{

        echo json_encode([
        "response"=>false,
        "message"=>"Invalid login"
        ]);

        }

    }

}





?>