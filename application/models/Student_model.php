<?php
class Student_model extends CI_Model {
    
    public function __construct()
    {
    $this->load->database();
    }
    function get_all_students(){
        return $this->db->order_by('id','DESC')
        ->get('students')->result();
    }

    function insert($data){
        $this->db->insert('students',$data);
        return $this->db->insert_id();
    }

    function insert_doc($data){
        return $this->db->insert('student_documents',$data);
    }

    function get_student_full($student_id){

        $this->db->where('student_id',$student_id);
        $a=$this->db->get('admissions')->row();

        $this->db->where('admission_id',$a->id);
        $l=$this->db->get('student_fee_ledger')->row();

        $this->db->select("
        fee_installments.*,
        IFNULL((SELECT SUM(amount) FROM payments 
        WHERE installment_id=fee_installments.id),0) paid
        ");

        $this->db->where('ledger_id',$l->id);
        $inst=$this->db->get('fee_installments')->result();


        $this->db->join('student_fee_ledger l','l.id=p.ledger_id');
        $this->db->where('l.admission_id',$a->id);

        $pay=$this->db->get('payments p')->result();


        $this->db->where('student_id',$student_id);
        $docs=$this->db->get('student_documents')->result();


        return [

        'admission'=>$a,
        'ledger'=>$l,
        'installments'=>$inst,
        'payments'=>$pay,
        'documents'=>$docs

        ];

    }

    public function get_student_details($student_id)
{

    /* ========= ADMISSION + COURSE + BATCH ========= */

    $this->db->select('a.*, c.course_name, b.batch_name');
    $this->db->from('admissions a');
    $this->db->join('courses c','c.id = a.course_id','left');
    $this->db->join('batches b','b.id = a.batch_id','left');
    $this->db->where('a.student_id',$student_id);

    $admission = $this->db->get()->row();

    /* ========= STUDENT ========= */

    $this->db->where('id',$student_id);
    $student = $this->db->get('students')->row();

    /* ========= DEFAULT EMPTY DATA ========= */

    $ledger = null;
    $installments = [];
    $payments = [];
    $documents = [];

    /* ========= IF ADMISSION FOUND ========= */

        if($admission){

        /* ========= LEDGER ========= */

        $this->db->where('admission_id',$admission->id);
        $ledger = $this->db->get('student_fee_ledger')->row();

        /* ========= IF LEDGER FOUND ========= */

        if($ledger){

            /* ========= INSTALLMENTS ========= */

            $sql = "
                SELECT 
                fi.*,
                IFNULL((
                    SELECT SUM(amount)
                    FROM payments p
                    WHERE p.installment_id = fi.id
                ),0) AS paid
                FROM fee_installments fi
                WHERE fi.ledger_id = ?
            ";

            $installments = $this->db->query($sql, [$ledger->id])->result();


            /* ========= PAYMENTS (FAST QUERY) ========= */

            $payments = $this->db
                ->where('ledger_id',$ledger->id)
                ->get('payments')
                ->result();

            $refund = $this->db
                ->where('admission_id',$admission->id)
                ->get('refunds')
                ->result();

            $discounts = $this->db
                ->select("d.*, s.name as scholarship_name")
                ->from('student_fee_discounts d')
                ->join('scholarships s', 's.id = d.scholarship_id', 'left')
                ->where('d.admission_id', $admission->id)
                ->where('d.status', 1)
                ->order_by('d.id', 'DESC')
                ->get()
                ->result();
                
        }
    }

    /* ========= DOCUMENTS ========= */

    $documents = $this->db
        ->where('student_id',$student_id)
        ->get('student_documents')
        ->result();

    /* ========= RETURN FINAL ========= */

    return [

        'ledger'       => $ledger,
        'admission'    => $admission,
        'student'      => $student,
        'installments' => $installments,
        'payments'     => $payments,
        'documents'    => $documents,
        'refund'       => $refund,
        'discounts'    => $discounts ?? []

    ];
}


public function get_student_status($mobile,$dob)
{
    $this->db->select("s.*,a.id as admission_id,a.admission_no");
    $this->db->from("students s");
    $this->db->join("admissions a","a.student_id=s.id","left");
    $this->db->where("s.mobile",$mobile);
    $this->db->where("s.dob",$dob);

    $student=$this->db->get()->row();

    if(!$student) return false;

    $docs=$this->db
    ->where("student_id",$student->id)
    ->get("student_documents")
    ->result();

    return [
    "student"=>$student,
    "docs"=>$docs
    ];
}
    
}

?>