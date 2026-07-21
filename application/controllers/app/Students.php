<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Students extends CI_Controller {

    
    public function __construct(){
        parent::__construct();
        if(!isset($_SESSION['userdata']) || $_SESSION['userdata']['user_logged_status'] != TRUE)
        {
        redirect('app/logout');
        }
         $this->load->model('student_model');
    }


    // LIST
    public function index($page = "list"){
        $data = array();
		if(!file_exists(APPPATH.'views/students/'.$page.'.php'))
		{
		show_404();
		}
		$data["title"] = "Students";
		$this->load->view('app/templates/header' , $data);
		$this->load->view('app/templates/side_panel' , $data);
		$this->load->view('students/'.$page , $data);
		$this->load->view('app/templates/footer' , $data);
    }


    public function get_all_students()
    {
        $data = array();
        if($this->input->is_ajax_request())
        {
        
        $r = $this->student_model->get_all_students();
        if($r != FALSE)
            {
            $data["response"] = TRUE;
            $data["message"] = count($r)." Record Found.";
            $data["total_record"] = count($r);
            $data["all_record"] = $r;
            }
        else
            {
            $data["response"] = FALSE;
            $data["message"] = "No Record Found.";
            }
        }
        echo json_encode($data);
    }

     public function save(){
        
        if($this->input->post()){
             $photo="";

            // PHOTO UPLOAD
            if(!empty($_FILES['photo']['name'])){

                $config['upload_path']='uploads/students/';
                $config['allowed_types']='jpg|png|jpeg';
                $config['file_name']=time();

                $this->load->library('upload',$config);

                if($this->upload->do_upload('photo')){
                    $photo=$this->upload->data('file_name');
                }
            }

             $student_id=$this->student_model->insert([
                'name'=>$this->input->post('name'),
                'father_name'=>$this->input->post('father_name'),
                'mobile'=>$this->input->post('mobile'),
                'email'=>$this->input->post('email'),
                'dob'=>$this->input->post('dob'),
                'address'=>$this->input->post('address'),
                'photo'=>$photo
            ]);

            if(!empty($_FILES['documents']['name'][0])){

                foreach($_FILES['documents']['name'] as $k=>$v){

                    $_FILES['file']['name']=$_FILES['documents']['name'][$k];
                    $_FILES['file']['type']=$_FILES['documents']['type'][$k];
                    $_FILES['file']['tmp_name']=$_FILES['documents']['tmp_name'][$k];
                    $_FILES['file']['error']=$_FILES['documents']['error'][$k];
                    $_FILES['file']['size']=$_FILES['documents']['size'][$k];

                    $config['upload_path']='uploads/student_docs/';
                    $config['allowed_types']='jpg|png|pdf';
                    $config['file_name']=time().$k;

                    $this->upload->initialize($config);

                    if($this->upload->do_upload('file')){
                        $file=$this->upload->data('file_name');

                        $this->student_model->insert_doc([
                            'student_id'=>$student_id,
                            'document_name'=>'Document',
                            'file_path'=>$file
                        ]);
                    }
                }
            }
            $data["response"] = TRUE;
            $data["message"] = "Student Added.";
            echo json_encode($data);
            return;

        }else{
            $data["response"] = false;
            $data["message"] = "Invalid Request.";
            echo json_encode($data);
        }
    }
    public function upload_docs()
    {
        $student_id = $this->input->post('student_id');
        $docnames = $this->input->post('docnames');
        if(empty($student_id)){
            echo json_encode(["status"=>false,"msg"=>"Student ID missing"]);
            return;
        }

        $upload_path = FCPATH.'uploads/student_docs/';
        if(empty($_FILES['docs']['name'][0])){
        echo json_encode(["status"=>false,"msg"=>"No file selected"]);
        return;
        }
        if(!is_dir($upload_path)){
            mkdir($upload_path,0777,true);
        }

        $files = $_FILES['docs']['name'];
        $count = count($files);

        $this->load->database();

        for($i=0;$i<$count;$i++)
        {
            if($_FILES['docs']['name'][$i] != '')
            {
                $_FILES['file']['name']     = $_FILES['docs']['name'][$i];
                $_FILES['file']['type']     = $_FILES['docs']['type'][$i];
                $_FILES['file']['tmp_name'] = $_FILES['docs']['tmp_name'][$i];
                $_FILES['file']['error']    = $_FILES['docs']['error'][$i];
                $_FILES['file']['size']     = $_FILES['docs']['size'][$i];

                $config['upload_path']   = $upload_path;
                $config['allowed_types'] = 'jpg|jpeg|png|pdf|doc|docx';
                $config['encrypt_name']  = TRUE;

                $this->load->library('upload',$config);
                $this->upload->initialize($config);

                if($this->upload->do_upload('file'))
                {
                    $fileData = $this->upload->data();
                    $file_name = $fileData['file_name'];

                    $this->db->insert('student_documents',[
                        'student_id'=>$student_id,
                        'document_type'=>$docnames[$i],
                        'document_name'=>$file_name,
                        'file_path'=>'uploads/student_docs/'.$file_name
                    ]);
                }
            }
        }

        echo json_encode(["status"=>true,"msg"=>"Uploaded"]);
    }

    public function get_student_docs($student_id)
    {
        echo json_encode(
            $this->db->where('student_id',$student_id)
            ->get('student_documents')
            ->result()
        );
    }
    

    public function reject_doc()
    {
        $this->db->where('id',$this->input->post('id'))
        ->update('student_documents',[
            'status'=>'rejected',
            'reject_reason'=>$this->input->post('reason')
        ]);
    }

    public function approve_doc()
    {
        $this->db->where('id',$this->input->post('id'))
        ->update('student_documents',['status'=>'approved']);
    }

    
}