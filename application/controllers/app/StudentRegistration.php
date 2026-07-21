<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StudentRegistration extends CI_Controller {

    public function index(){
        redirect('web/pages/studentRegistration');
    }

    public function save_full()
    {
       
        /* ================= VALIDATION ================= */

        $name   = trim($this->input->post('name',TRUE));
        $father = trim($this->input->post('father_name',TRUE));
        $dob    = trim($this->input->post('dob',TRUE));
        $email  = trim($this->input->post('email',TRUE));
        $mobile = trim($this->input->post('mobile',TRUE));
        $address= trim($this->input->post('address',TRUE));

        /* REQUIRED CHECK */

        if($name=="" || $father=="" || $dob=="" || $mobile=="" || $address==""){
            echo json_encode(["status"=>false,"msg"=>"All fields are required"]);
            return;
        }

        /* MOBILE VALID */

        if(!preg_match('/^[6-9][0-9]{9}$/',$mobile)){
            echo json_encode(["status"=>false,"msg"=>"Invalid Mobile Number"]);
            return;
        }

        /* EMAIL VALID */

        if($email!="" && !filter_var($email,FILTER_VALIDATE_EMAIL)){
            echo json_encode(["status"=>false,"msg"=>"Invalid Email"]);
            return;
        }

        /* DOB VALID (NOT FUTURE) */

        if(strtotime($dob) > time()){
            echo json_encode(["status"=>false,"msg"=>"Invalid DOB"]);
            return;
        }

        /* ================= SAVE STUDENT ================= */

        $data=[
            'name'=>$name,
            'father_name'=>$father,
            'dob'=>$dob,
            'email'=>$email,
            'mobile'=>$mobile,
            'address'=>$address
        ];

        $this->db->insert('students',$data);

        $student_id=$this->db->insert_id();

        /* ================= PHOTO UPLOAD ================= */

        if(!empty($_FILES['photo']['name']))
        {
            $dir=FCPATH."uploads/students/";

            if(!is_dir($dir)){
                mkdir($dir,0777,true);
            }

            $ext=pathinfo($_FILES['photo']['name'],PATHINFO_EXTENSION);

            $allowed=['jpg','jpeg','png'];

            if(!in_array(strtolower($ext),$allowed)){
                echo json_encode(["status"=>false,"msg"=>"Photo must be JPG/PNG"]);
                return;
            }

            $photo="STU_".time().rand(100,999).".".$ext;

            move_uploaded_file($_FILES['photo']['tmp_name'],$dir.$photo);

            $this->db->where('id',$student_id)
                    ->update('students',['photo'=>$photo]);
        }

        /* ================= DOCUMENTS ================= */

        $doc_names=$_POST['doc_names'] ?? [];

        if(!empty($_FILES['docs']['name'][0]))
        {
            $dir=FCPATH."uploads/student_docs/";

            if(!is_dir($dir)){
                mkdir($dir,0777,true);
            }

            for($i=0;$i<count($_FILES['docs']['name']);$i++)
            {
                if($_FILES['docs']['name'][$i]!="")
                {
                    $ext=pathinfo($_FILES['docs']['name'][$i],PATHINFO_EXTENSION);

                    $allowed=['jpg','jpeg','png','pdf'];

                    if(!in_array(strtolower($ext),$allowed)){
                        continue;
                    }

                    $file="DOC_".time().rand(100,999).".".$ext;

                    move_uploaded_file(
                        $_FILES['docs']['tmp_name'][$i],
                        $dir.$file
                    );

                    $this->db->insert('student_documents',[
                        'student_id'=>$student_id,
                        'document_name'=>$file,
                        'document_type'=>$doc_names[$i] ?? 'OTHER',
                        'file_path'=>'uploads/student_docs/'.$file
                    ]);
                }
            }
        }

        echo json_encode([
            "status"=>true,
            "msg"=>"Student Registered Successfully"
        ]);
    }



    
}