<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payments extends CI_Controller {

    function __construct(){
        parent::__construct();
         if(!isset($_SESSION['userdata']) || $_SESSION['userdata']['user_logged_status'] != TRUE)
	   {
	    redirect('app/logout');
	   }
        $this->load->model('admission_model');
        $this->load->model('student_model');
        $this->load->model('course_model');
        $this->load->model('batches_model');
        $this->load->model('fee_structure_model');
    }

    // public function save_payment()
    // {
    //     $installment_id = $this->input->post('installment_id');
    //     $ledger_id      = $this->input->post('ledger_id');
    //     $installment = $this->db->where('id',$installment_id)
    //                 ->get('fee_installments')
    //                 ->row();

    //     $amount = $installment->amount;
    //     if($installment->status=='PAID')
    //     {
    //         echo json_encode([
    //         "response"=>false,
    //         "message"=>"Already Paid"
    //         ]);
    //         return;
    //     }
    //     $mode           = $this->input->post('payment_mode');
    //     $date           = $this->input->post('payment_date');

    //     /* receipt number */
    //     $receipt = "RCPT".date("YmdHis");

    //     /* INSERT PAYMENT */
    //     $this->db->insert('payments',[
    //         'ledger_id'=>$ledger_id,
    //         'installment_id'=>$installment_id,
    //         'amount'=>$amount,
    //         'payment_date'=>$date,
    //         'payment_mode'=>$mode,
    //         'receipt_no'=>$receipt
    //     ]);

    //     $payment_id = $this->db->insert_id();

    //     /* INSERT PAYMENT LOG */
    //     $this->db->insert('payment_logs',[
    //         'payment_id'=>$payment_id,
    //         'action'=>'CREATED'
    //     ]);

    //     /* UPDATE INSTALLMENT STATUS */
    //     $this->db->where('id',$installment_id)
    //             ->update('fee_installments',['status'=>'PAID']);

    //     /* UPDATE LEDGER */

    //     $ledger = $this->db->where('id',$ledger_id)
    //                     ->get('student_fee_ledger')->row();

    //     $new_paid = $ledger->paid + $amount;
    //     $new_balance = $ledger->total_fee - $new_paid;

    //     $this->db->where('id',$ledger_id)
    //             ->update('student_fee_ledger',[
    //                 'paid'=>$new_paid,
    //                 'balance'=>$new_balance
    //             ]);

    //     echo json_encode([
    //         "response"=>true,
    //         "message"=>"Payment saved. Receipt: ".$receipt
    //     ]);
    // }
    public function save_payment()
    {
        $installment_id = $this->input->post('installment_id');
        $ledger_id      = $this->input->post('ledger_id');
        $amount         = $this->input->post('amount');
        $mode           = $this->input->post('payment_mode');
        $date           = $this->input->post('payment_date');

        /* GET INSTALLMENT */
        $installment = $this->db->where('id',$installment_id)
                                ->get('fee_installments')->row();

        /* SUM OLD PAYMENTS */
        $old = $this->db->select_sum('amount')
                        ->where('installment_id',$installment_id)
                        ->get('payments')->row()->amount;

        $total_paid = $old + $amount;

        /* CHECK STATUS */

        if($total_paid >= $installment->amount)
            $status="PAID";
        else if($total_paid>0)
            $status="PARTIAL";
        else
            $status="PENDING";

        /* RECEIPT */
        $receipt = "RCPT".date("YmdHis");

        /* INSERT PAYMENT */
        $this->db->insert('payments',[
            'ledger_id'=>$ledger_id,
            'installment_id'=>$installment_id,
            'amount'=>$amount,
            'payment_date'=>$date,
            'payment_mode'=>$mode,
            'receipt_no'=>$receipt
        ]);

        $payment_id = $this->db->insert_id();

        /* PAYMENT LOG */
        $this->db->insert('payment_logs',[
            'payment_id'=>$payment_id,
            'action'=>'CREATED'
        ]);

        /* UPDATE INSTALLMENT STATUS */
        $this->db->where('id',$installment_id)
                ->update('fee_installments',['status'=>$status]);

        /* UPDATE LEDGER TOTAL */

        $paid = $this->db->select_sum('amount')
                        ->where('ledger_id',$ledger_id)
                        ->get('payments')->row()->amount;

        $ledger = $this->db->where('id',$ledger_id)
                        ->get('student_fee_ledger')->row();

        $balance = $ledger->total_fee - $paid;

        $this->db->where('id',$ledger_id)
                ->update('student_fee_ledger',[
                    'paid'=>$paid,
                    'balance'=>$balance
                ]);

        echo json_encode([
            "response"=>true,
            "message"=>"Payment saved. Receipt ".$receipt
        ]);
    }


    public function history()
    {
        $id=$this->input->post('installment_id');

        echo json_encode(
        $this->db->where('installment_id',$id)->get('payments')->result()
        );
    }

    public function get_receipt()
    {
        $payment_id = $this->input->post('payment_id', true);

        if(!$payment_id){
            echo json_encode([
                "response"=>false
            ]);
            return;
        }
        

        $r = $this->admission_model->get_receipt($payment_id);
        
        echo json_encode([
            "response"=>true,
            "data"=>$r
        ]);
    }
     
}