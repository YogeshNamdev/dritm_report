<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Admission_model extends CI_Model {

    public function __construct()
    {
    $this->load->database();
    }
    public function get_all_students()
    {
        $r = $this->db->get_where("students" , array("status" => 1));
        if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
    }
    public function get_all_course_list()
    {
    $r = $this->db->get_where("courses" , array("status" => 1));
    if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
    }
    public function get_all_batch_list()
    {
    $r = $this->db->get_where("batches" , array("status" => 1));
    if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
    }
    public function insert($data){
        $this->db->insert('admissions',$data);
        return $this->db->insert_id();
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
	
	public function get_all_admissions()
	 {
	  $r = $this->db->query("select admissions.*,students.name,courses.course_name, batches.batch_name from admissions left join students on students.id=admissions.student_id left join courses on courses.id=admissions.course_id left join batches on batches.id=admissions.batch_id where admissions.status = 'active' order by admissions.id desc; ");
	  if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
	 }
     public function get_student_details_by_id($student_id)
	 {
	  $r = $this->db->get_where("admissions" , array("status" => 1 , "student_id" => $student_id));
	  if($r->num_rows() > 0){ return $r->result(); }else{ return FALSE; }
	 }
     public function update_batch($batche_id , $update_arr)
	 {
	  $this->db->where("id" , $batche_id);
	  $this->db->update("batches" , $update_arr);
	  return $this->db->affected_rows();
	 }

     function insert_ledger($data){
        $this->db->insert('student_fee_ledger',$data);
        return $this->db->insert_id();
    }

    function insert_installment($data){
        return $this->db->insert('fee_installments',$data);
    }

    function get_ledger_by_admission($admission_id)
    {
        return $this->db
            ->where('admission_id', $admission_id)
            ->get('student_fee_ledger')
            ->row();
    }

    function update_ledger($ledger_id, $data)
    {
        return $this->db
            ->where('id', $ledger_id)
            ->update('student_fee_ledger', $data);
    }

    function insert_fee_plan_snapshot($data)
    {
        $this->db->insert('student_fee_plan_snapshot', $data);
        return $this->db->insert_id();
    }

    // public function get_admission_details_by_id($admission_id)
    // {
    //     // admission + student
    //     $this->db->select('a.*,s.name,s.mobile');
    //     $this->db->from('admissions a');
    //     $this->db->join('students s','s.id=a.student_id','left');
    //     $this->db->where('a.id',$admission_id);

    //     $admission = $this->db->get()->row();

    //     // ledger
    //     $ledger = $this->db
    //         ->where('admission_id',$admission_id)
    //         ->get('student_fee_ledger')
    //         ->row();

    //     // installments
    //     $installments=[];
    //     if($ledger){
    //         $this->db->select("
    //         fee_installments.*,

    //         IFNULL(
    //         (SELECT SUM(amount) FROM payments 
    //         WHERE installment_id = fee_installments.id),0
    //         ) as paid_amount
    //         ");

    //         $this->db->where("ledger_id",$ledger->id);

    //         $installments=$this->db->get("fee_installments")->result();

    //         // $this->db->select("
    //         // fee_installments.*,

    //         // IFNULL(
    //         // (SELECT SUM(amount) FROM payments 
    //         // WHERE installment_id = fee_installments.id),0
    //         // ) as paid_amount

    //         // ");
    //         // $this->db->where("ledger_id",$ledger->id);
    //         //$installments=$this->db->get("fee_installments")->result();

    //         // $installments=$this->db
    //         //     ->where('ledger_id',$ledger->id)
    //         //     ->order_by('due_date','ASC')
    //         //     ->get('fee_installments')
    //         //     ->result();
    //         $refunds=$this->db
    //         ->where('admission_id',$admission_id)
    //         ->get('refunds')
    //         ->result();
    //     }

    //     return [
    //         'admission'=>$admission,
    //         'ledger'=>$ledger,
    //         'installments'=>$installments,
    //         'refunds'=>$refunds
    //     ];
    // }

    public function get_admission_details_by_id($admission_id)
    {
        // admission + student
        $this->db->select('a.*,s.name,s.mobile');
        $this->db->from('admissions a');
        $this->db->join('students s','s.id=a.student_id','left');
        $this->db->where('a.id',$admission_id);

        $admission = $this->db->get()->row();

        // ledger
        $ledger = $this->db
            ->where('admission_id',$admission_id)
            ->get('student_fee_ledger')
            ->row();

        $installments=[];
        $refunds=[];

        if($ledger){

            $this->db->select("
            fee_installments.*,

            IFNULL(
            (SELECT SUM(amount) FROM payments 
            WHERE installment_id = fee_installments.id),0
            ) as paid_amount
            ");

            $this->db->where("ledger_id",$ledger->id);

            $installments=$this->db->get("fee_installments")->result();

            /* ==============================
            ADD REMAINING + FINE HERE
            ============================== */

            $today = date("Y-m-d");

            foreach($installments as $i){

                // remaining amount
                $i->remaining = $i->amount - $i->paid_amount;

                // fine default
                $i->fine = 0;

                // calculate fine only if unpaid & overdue
                if($today > $i->due_date && $i->remaining > 0){

                    $late = floor(
                        (strtotime($today) - strtotime($i->due_date)) / 86400
                    );

                    $rule=$this->db
                    ->where("days_after <=", $late)
                    ->order_by("days_after","DESC")
                    ->limit(1)
                    ->get("fine_rules")
                    ->row();

                    if($rule){
                        $i->fine = $rule->fine_amount;
                    }
                }
            }

            // refunds
            $refunds=$this->db
            ->where('admission_id',$admission_id)
            ->get('refunds')
            ->result();

            $discounts = $this->db
            ->select("d.*, s.name as scholarship_name")
            ->from('student_fee_discounts d')
            ->join('scholarships s', 's.id = d.scholarship_id', 'left')
            ->where('d.admission_id', $admission_id)
            ->where('d.status', 1)
            ->order_by('d.id', 'DESC')
            ->get()
            ->result();
        }

        return [
            'admission'=>$admission,
            'ledger'=>$ledger,
            'installments'=>$installments,
            'refunds'=>$refunds,
            'discounts'=>$discounts ?? []
        ];
    }

    public function get_receipt($payment_id)
    {
        $this->db->select("
            p.*,
            s.name,
            s.mobile,
            a.admission_no,
            i.due_date
        ");

        $this->db->from('payments p');

        $this->db->join('fee_installments i','i.id=p.installment_id','left');
        $this->db->join('student_fee_ledger l','l.id=p.ledger_id','left');
        $this->db->join('admissions a','a.id=l.admission_id','left');
        $this->db->join('students s','s.id=a.student_id','left');

        $this->db->where('p.id',$payment_id);

        return $this->db->get()->row();
    }
}
