<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RegistrationStatus extends CI_Controller {

    public function index(){
        redirect('web/pages/registrationStatus');
    }

    public function reupload(){

    $doc_id = $this->input->post("doc_id");

    // ===== GET OLD FILE NAME FIRST =====
    $old = $this->db->get_where("student_documents",["id"=>$doc_id])->row();

    if(!$old){
        echo json_encode(["status"=>0,"msg"=>"Document not found"]);
        return;
    }

    // ===== UPLOAD CONFIG =====
    $config['upload_path']   = './uploads/student_docs/';
    $config['allowed_types'] = 'jpg|jpeg|png|pdf';
    $config['encrypt_name']  = TRUE;   // security (random file name)

    $this->load->library('upload',$config);

    if($this->upload->do_upload('file')){

        $data = $this->upload->data();
        $new_file = $data['file_name'];

        // ===== DELETE OLD FILE =====
        if(!empty($old->document_name)){
            $old_path = './uploads/student_docs/'.$old->document_name;

            if(file_exists($old_path)){
                unlink($old_path);
            }
        }

        // ===== UPDATE DATABASE =====
        $this->db->where("id",$doc_id);
        $this->db->update("student_documents",[
            "document_name"=>$new_file,
            "status"=>"pending",
            "reject_reason"=>NULL,
            "created_at"=>date("Y-m-d H:i:s"),
            "version"=>$old->version+1
        ]);

        echo json_encode([
            "status"=>1,
            "msg"=>"File uploaded successfully"
        ]);

    }else{

        echo json_encode([
            "status"=>0,
            "msg"=>$this->upload->display_errors()
        ]);

    }
}
public function check_status()
    {
        $mobile=$this->input->post("mobile");
        $dob=$this->input->post("dob");
        $this->load->model('student_model');
        $r=$this->student_model->get_student_status($mobile,$dob);

        if(!$r){
            echo json_encode(["status"=>false]);
        }else{
            echo json_encode(["status"=>true,"data"=>$r]);
        }
    }

    
}