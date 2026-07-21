<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Scholarships extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        if(!isset($_SESSION['userdata']) || $_SESSION['userdata']['user_logged_status'] != TRUE)
        {
            redirect('app/logout');
        }

        $this->load->model('admission_model');
        $this->load->model('scholarship_model');
    }

    public function index($page = 'list')
    {
        $data = array();

        if(!file_exists(APPPATH.'views/scholarships/'.$page.'.php'))
        {
            show_404();
        }

        $data['title'] = 'Scholarship & Discount Module';
        $data['admission_list'] = $this->admission_model->get_all_admissions();
        $data['scholarship_list'] = $this->scholarship_model->get_all_scholarships();

        $this->load->view('app/templates/header', $data);
        $this->load->view('app/templates/side_panel', $data);
        $this->load->view('scholarships/'.$page, $data);
        $this->load->view('app/templates/footer', $data);
    }

    public function get_all()
    {
        $rows = $this->scholarship_model->get_all_scholarships();

        echo json_encode([
            'response' => !empty($rows),
            'message' => !empty($rows) ? count($rows).' Record Found.' : 'No Record Found.',
            'all_record' => $rows
        ]);
    }

    public function save_master()
    {
        if(!$this->input->post()){
            echo json_encode(['response' => FALSE, 'message' => 'Invalid request.']);
            return;
        }

        $name = trim($this->input->post('name', TRUE));
        $percent = (float)$this->input->post('discount_percent', TRUE);
        $code = trim($this->input->post('code', TRUE));
        $description = trim($this->input->post('description', TRUE));

        if($name === '' || $percent <= 0){
            echo json_encode(['response' => FALSE, 'message' => 'Name and valid discount percent are required.']);
            return;
        }

        $id = $this->scholarship_model->insert_scholarship([
            'name' => $name,
            'code' => $code ?: NULL,
            'discount_percent' => $percent,
            'description' => $description ?: NULL,
            'status' => 1
        ]);

        echo json_encode([
            'response' => $id > 0,
            'message' => $id > 0 ? 'Scholarship saved successfully.' : 'Unable to save scholarship.',
            'id' => $id
        ]);
    }

    // public function assign_discount()
    // {

    //     //echo "<pre>";print_r($_POST);die;
    //     if(!$this->input->post()){
    //         echo json_encode(['response' => FALSE, 'message' => 'Invalid request.']);
    //         return;
    //     }
    //     // Yogesh //
    //     // Array
    //     // (
    //     //     [admission_id] => 1
    //     //     [discount_category] => SCHOLARSHIP
    //     //     [academic_year_no] => 1
    //     //     [applicable_on] => TUITION
    //     //     [scholarship_id] => 1
    //     //     [discount_label] => Scholarship Discount
    //     //     [reason] => Scholarship Discount 
    //     //     [discount_type] => PERCENT
    //     //     [discount_value] => 10
    //     // )

    //     $admission_id = (int)$this->input->post('admission_id', TRUE);
    //     $category = strtoupper(trim($this->input->post('discount_category', TRUE)));
    //     $discount_type = strtoupper(trim($this->input->post('discount_type', TRUE)));
    //     $discount_value = (float)$this->input->post('discount_value', TRUE);
    //     $academic_year_no = (int)$this->input->post('academic_year_no', TRUE);
    //     $reason = trim($this->input->post('reason', TRUE));
    //     $label = trim($this->input->post('discount_label', TRUE));
    //     $scholarship_id = (int)$this->input->post('scholarship_id', TRUE);
    //     $applicable_on = strtoupper(trim($this->input->post('applicable_on', TRUE)));

    //     $ledger = $this->scholarship_model->get_ledger_by_admission($admission_id);
    //     if(!$ledger){
    //         echo json_encode(['response' => FALSE, 'message' => 'Ledger not found for this admission.']);
    //         return;
    //     }

    //     if($academic_year_no <= 0){
    //         $academic_year_no = 1;
    //     }

    //     if($applicable_on === ''){
    //         $applicable_on = 'TUITION';
    //     }

    //     $discount_base = $this->scholarship_model->get_discount_base_for_year($ledger->id, $academic_year_no, $applicable_on);
    //     if($discount_base <= 0){
    //         echo json_encode(['response' => FALSE, 'message' => 'Discount base amount not found for selected academic year.']);
    //         return;
    //     }

    //     if($category === 'SCHOLARSHIP' && $scholarship_id > 0){
    //         $sch = $this->scholarship_model->get_scholarship_by_id($scholarship_id);
    //         if(!$sch){
    //             echo json_encode(['response' => FALSE, 'message' => 'Scholarship not found.']);
    //             return;
    //         }

    //         if($discount_value <= 0){
    //             $discount_value = (float)$sch->discount_percent;
    //         }

    //         if($label === ''){
    //             $label = 'Scholarship Discount';
    //         }
    //     }

    //     if($category === ''){
    //         $category = 'OTHER';
    //     }

    //     if($discount_type === ''){
    //         $discount_type = 'AMOUNT';
    //     }

    //     if($discount_value <= 0){
    //         echo json_encode(['response' => FALSE, 'message' => 'Valid discount value is required.']);
    //         return;
    //     }

    //     $discount_amount = $discount_type === 'PERCENT'
    //         ? round(($discount_base * $discount_value) / 100, 2)
    //         : round($discount_value, 2);

    //     $existing_discount = $this->scholarship_model->get_total_discount_by_admission($admission_id, $academic_year_no);
    //     $existing_total_discount = $this->scholarship_model->get_total_discount_by_admission($admission_id);
    //     $remaining_discount_room = $discount_base - $existing_discount;

    //     if($discount_amount > $remaining_discount_room){
    //         $discount_amount = max($remaining_discount_room, 0);
    //     }

    //     if($discount_amount <= 0){
    //         echo json_encode(['response' => FALSE, 'message' => 'Discount exceeds available fee amount.']);
    //         return;
    //     }

    //     $user_id = isset($_SESSION['userdata']['user_id']) ? (int)$_SESSION['userdata']['user_id'] : NULL;

    //     $this->scholarship_model->insert_student_discount([
    //         'admission_id' => $admission_id,
    //         'ledger_id' => $ledger->id,
    //         'academic_year_no' => $academic_year_no,
    //         'semester_no' => NULL,
    //         'applicable_on' => $applicable_on,
    //         'discount_category' => $category,
    //         'scholarship_id' => $scholarship_id ?: NULL,
    //         'discount_label' => $label ?: 'Special Discount',
    //         'reason' => $reason ?: NULL,
    //         'discount_type' => $discount_type,
    //         'discount_value' => $discount_value,
    //         'discount_amount' => $discount_amount,
    //         'valid_from' => date('Y-m-d'),
    //         'valid_to' => date('Y-m-d', strtotime('+1 year -1 day')),
    //         'created_by' => $user_id,
    //         'status' => 1
    //     ]);

    //     if($category === 'SCHOLARSHIP' && $scholarship_id > 0){
    //         $this->scholarship_model->insert_student_scholarship([
    //             'admission_id' => $admission_id,
    //             'scholarship_id' => $scholarship_id,
    //             'discount_amount' => $discount_amount
    //         ]);
    //     }

    //     $new_year_discount = $existing_discount + $discount_amount;
    //     $new_total_discount = $existing_total_discount + $discount_amount;
    //     $gross_fee = (float)($ledger->gross_fee ?: $ledger->total_fee);
    //     $net_fee = $gross_fee - $new_total_discount;
    //     $paid = (float)$ledger->paid;
    //     $balance = max($net_fee - $paid, 0);

    //     $this->scholarship_model->update_ledger($ledger->id, [
    //         'gross_fee' => $gross_fee,
    //         'total_discount' => $new_total_discount,
    //         'net_fee' => $net_fee,
    //         'total_fee' => $net_fee,
    //         'balance' => $balance
    //     ]);

    //     $year_installments = $this->scholarship_model->get_installments_by_year($ledger->id, $academic_year_no, $applicable_on);
    //     $year_gross_total = 0;
    //     foreach($year_installments as $inst){
    //         $year_gross_total += (float)($inst->gross_amount ?: $inst->amount);
    //     }

    //     if($year_gross_total > 0){
    //         $allocated = 0;
    //         $count = count($year_installments);

    //         foreach($year_installments as $index => $inst){
    //             $gross_amount = (float)($inst->gross_amount ?: $inst->amount);
    //             if($count === 1){
    //                 $installment_discount = $new_year_discount;
    //             } else if($index === $count - 1){
    //                 $installment_discount = round($new_year_discount - $allocated, 2);
    //             } else {
    //                 $installment_discount = round(($gross_amount / $year_gross_total) * $new_year_discount, 2);
    //             }

    //             $allocated += $installment_discount;
    //             $net_amount = max($gross_amount - $installment_discount, 0);

    //             $this->scholarship_model->update_installment($inst->id, [
    //                 'discount_amount' => $installment_discount,
    //                 'net_amount' => $net_amount,
    //                 'amount' => $net_amount
    //             ]);
    //         }
    //     }

    //     $pending_installments = $this->scholarship_model->get_pending_installments($ledger->id);
    //     if($paid <= 0 && !empty($pending_installments)){
    //         $count = count($pending_installments);
    //         $per = round($net_fee / $count, 2);
    //         $running = 0;

    //         foreach($pending_installments as $index => $inst){
    //             $amount = ($index === $count - 1) ? round($net_fee - $running, 2) : $per;
    //             $running += $amount;
    //             $this->scholarship_model->update_installment($inst->id, ['amount' => $amount]);
    //         }
    //     }

    //     echo json_encode([
    //         'response' => TRUE,
    //         'message' => 'Discount assigned successfully.',
    //         'discount_amount' => $discount_amount,
    //         'net_fee' => $net_fee
    //     ]);
    // }

// updated function added after 15-04-2026
    public function assign_discount()
{
    if (!$this->input->post()) {
        echo json_encode([
            'response' => FALSE,
            'message'  => 'Invalid request.'
        ]);
        return;
    }

    /* =====================================================
       INPUTS
    ===================================================== */
    $admission_id      = (int)$this->input->post('admission_id', TRUE);
    $category          = strtoupper(trim($this->input->post('discount_category', TRUE)));
    $discount_type     = strtoupper(trim($this->input->post('discount_type', TRUE)));
    $discount_value    = (float)$this->input->post('discount_value', TRUE);
    $academic_year_no  = (int)$this->input->post('academic_year_no', TRUE);
    $reason            = trim($this->input->post('reason', TRUE));
    $label             = trim($this->input->post('discount_label', TRUE));
    $scholarship_id    = (int)$this->input->post('scholarship_id', TRUE);
    $applicable_on     = strtoupper(trim($this->input->post('applicable_on', TRUE)));

    if ($admission_id <= 0) {
        echo json_encode([
            'response' => FALSE,
            'message'  => 'Admission is required.'
        ]);
        return;
    }

    if ($academic_year_no <= 0) {
        $academic_year_no = 1;
    }

    if ($category == '') {
        $category = 'OTHER';
    }

    if ($discount_type == '') {
        $discount_type = 'AMOUNT';
    }

    if ($applicable_on == '') {
        $applicable_on = 'TUITION';
    }

    if ($label == '') {
        $label = ($category == 'SCHOLARSHIP')
            ? 'Scholarship Discount'
            : 'Special Discount';
    }

    /* =====================================================
       GET LEDGER
    ===================================================== */
    $ledger = $this->scholarship_model->get_ledger_by_admission($admission_id);

    if (!$ledger) {
        echo json_encode([
            'response' => FALSE,
            'message'  => 'Ledger not found.'
        ]);
        return;
    }

    /* =====================================================
       START TRANSACTION
    ===================================================== */
    $this->db->trans_begin();

    try {

        /* =================================================
           SCHOLARSHIP MASTER CHECK
        ================================================= */
        if ($category == 'SCHOLARSHIP' && $scholarship_id > 0) {

            $sch = $this->scholarship_model->get_scholarship_by_id($scholarship_id);

            if (!$sch) {
                throw new Exception('Scholarship not found.');
            }

            // prevent duplicate scholarship same year
            $exists = $this->db
                ->where('admission_id', $admission_id)
                ->where('academic_year_no', $academic_year_no)
                ->where('scholarship_id', $scholarship_id)
                ->where('status', 1)
                ->count_all_results('student_fee_discounts');

            if ($exists > 0) {
                throw new Exception('This scholarship already assigned.');
            }

            if ($discount_value <= 0) {
                $discount_value = (float)$sch->discount_percent;
            }
        }

        if ($discount_value <= 0) {
            throw new Exception('Discount value must be greater than zero.');
        }

        /* =================================================
           GET TARGET INSTALLMENTS
        ================================================= */
        $target_installments = $this->scholarship_model
            ->get_pending_installments_filtered(
                $ledger->id,
                $academic_year_no,
                $applicable_on
            );

        if (empty($target_installments)) {
            throw new Exception('No pending installments found.');
        }

        /* =================================================
           DISCOUNT BASE
        ================================================= */
        $discount_base = 0;

        foreach ($target_installments as $row) {
            $discount_base += (float)(
                $row->gross_amount > 0
                    ? $row->gross_amount
                    : $row->amount
            );
        }

        if ($discount_base <= 0) {
            throw new Exception('Discount base amount invalid.');
        }

        /* =================================================
           CALCULATE DISCOUNT
        ================================================= */
        if ($discount_type == 'PERCENT') {
            $discount_amount = round(
                ($discount_base * $discount_value) / 100,
                2
            );
        } else {
            $discount_amount = round($discount_value, 2);
        }

        /* =================================================
           CHECK EXISTING YEAR DISCOUNT
        ================================================= */
        $existing_year_discount =
            $this->scholarship_model
            ->get_total_discount_by_admission(
                $admission_id,
                $academic_year_no
            );

        $remaining_room = $discount_base - $existing_year_discount;

        if ($discount_amount > $remaining_room) {
            $discount_amount = $remaining_room;
        }

        if ($discount_amount <= 0) {
            throw new Exception('Discount exceeds available fee.');
        }

        /* =================================================
           SAVE DISCOUNT HISTORY
        ================================================= */
        $user_id = isset($_SESSION['userdata']['user_id'])
            ? (int)$_SESSION['userdata']['user_id']
            : NULL;

        $this->scholarship_model->insert_student_discount([
            'admission_id'      => $admission_id,
            'ledger_id'         => $ledger->id,
            'academic_year_no'  => $academic_year_no,
            'semester_no'       => NULL,
            'applicable_on'     => $applicable_on,
            'discount_category' => $category,
            'scholarship_id'    => $scholarship_id ?: NULL,
            'discount_label'    => $label,
            'reason'            => $reason,
            'discount_type'     => $discount_type,
            'discount_value'    => $discount_value,
            'discount_amount'   => $discount_amount,
            'valid_from'        => date('Y-m-d'),
            'valid_to'          => date('Y-m-d', strtotime('+1 year -1 day')),
            'created_by'        => $user_id,
            'status'            => 1
        ]);

        if ($category == 'SCHOLARSHIP' && $scholarship_id > 0) {
            $this->scholarship_model->insert_student_scholarship([
                'admission_id'    => $admission_id,
                'scholarship_id'  => $scholarship_id,
                'discount_amount' => $discount_amount
            ]);
        }

        /* =================================================
           UPDATE INSTALLMENTS (PROPORTIONAL)
        ================================================= */
        $allocated = 0;
        $count     = count($target_installments);

        foreach ($target_installments as $index => $inst) {

            $gross = (float)(
                $inst->gross_amount > 0
                    ? $inst->gross_amount
                    : $inst->amount
            );

            if ($index == ($count - 1)) {
                $inst_discount = round(
                    $discount_amount - $allocated,
                    2
                );
            } else {
                $inst_discount = round(
                    ($gross / $discount_base) * $discount_amount,
                    2
                );
            }

            $allocated += $inst_discount;

            $old_discount = (float)$inst->discount_amount;
            $new_discount = $old_discount + $inst_discount;

            $net = max($gross - $new_discount, 0);

            $this->scholarship_model->update_installment(
                $inst->id,
                [
                    'discount_amount' => $new_discount,
                    'net_amount'      => $net,
                    'amount'          => $net
                ]
            );
        }

        /* =================================================
           UPDATE LEDGER TOTALS
        ================================================= */
        $gross_fee = (float)(
            $ledger->gross_fee > 0
                ? $ledger->gross_fee
                : $ledger->total_fee
        );

        $existing_total_discount =
            $this->scholarship_model
            ->get_total_discount_by_admission($admission_id);

        $net_fee = max($gross_fee - $existing_total_discount, 0);

        $paid    = (float)$ledger->paid;
        $balance = max($net_fee - $paid, 0);

        $this->scholarship_model->update_ledger(
            $ledger->id,
            [
                'gross_fee'       => $gross_fee,
                'total_discount'  => $existing_total_discount,
                'net_fee'         => $net_fee,
                'total_fee'       => $net_fee,
                'balance'         => $balance
            ]
        );

        /* =================================================
           COMMIT
        ================================================= */
        if ($this->db->trans_status() === FALSE) {
            throw new Exception('Transaction failed.');
        }

        $this->db->trans_commit();

        echo json_encode([
            'response'        => TRUE,
            'message'         => 'Discount assigned successfully.',
            'discount_amount' => $discount_amount,
            'gross_fee'       => $gross_fee,
            'net_fee'         => $net_fee,
            'balance'         => $balance
        ]);
    }
    catch (Exception $e) {

        $this->db->trans_rollback();

        echo json_encode([
            'response' => FALSE,
            'message'  => $e->getMessage()
        ]);
    }
}


    public function get_admission_discounts()
    {
        $admission_id = (int)$this->input->post('admission_id', TRUE);
        $academic_year_no = (int)$this->input->post('academic_year_no', TRUE);

        if($admission_id <= 0){
            echo json_encode([
                'response' => FALSE,
                'message' => 'Invalid admission id.'
            ]);
            return;
        }

        $rows = $this->scholarship_model->get_discounts_by_admission($admission_id, $academic_year_no > 0 ? $academic_year_no : 1);
        $ledger = $this->scholarship_model->get_ledger_by_admission($admission_id);

        echo json_encode([
            'response' => TRUE,
            'message' => count($rows).' Record Found.',
            'all_record' => $rows,
            'ledger' => $ledger
        ]);
    }
}
