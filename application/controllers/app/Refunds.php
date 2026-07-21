<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Refunds extends CI_Controller {

    function __construct(){
        parent::__construct();
         if(!isset($_SESSION['userdata']) || $_SESSION['userdata']['user_logged_status'] != TRUE)
	   {
	    redirect('app/logout');
	   }
        $this->load->database();
    }


    public function save()
    {
        
        $admission_id=$this->input->post('admission_id');
        $amount=$this->input->post('amount');
        $reason=$this->input->post('reason');
        $date=$this->input->post('refund_date');

        /* GET LEDGER */

        $ledger=$this->db->where('admission_id',$admission_id)
                        ->get('student_fee_ledger')->row();

        /* VALIDATION */

        if($amount>$ledger->paid){

            echo json_encode([
            "response"=>false,
            "message"=>"Refund exceeds paid amount"
            ]);
            return;
        }

        /* INSERT REFUND */

        $this->db->insert('refunds',[

            'admission_id'=>$admission_id,
            'amount'=>$amount,
            'refund_date'=>$date,
            'reason'=>$reason

        ]);

        /* UPDATE LEDGER */

        $new_paid=$ledger->paid-$amount;
        $new_balance=$ledger->balance+$amount;

        $this->db->where('id',$ledger->id)
        ->update('student_fee_ledger',[

            'paid'=>$new_paid,
            'balance'=>$new_balance

        ]);

        echo json_encode([
            "response"=>true,
            "message"=>"Refund processed successfully"
        ]);
    }
}
?>