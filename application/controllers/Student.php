<?php 

class Student extends CI_Controller{

    function student_dashboard(){

        if(!$this->session->userdata('student_id')){
            redirect('student-login');
        }

        $this->load->view('students/dashboard');

    }
}


?>