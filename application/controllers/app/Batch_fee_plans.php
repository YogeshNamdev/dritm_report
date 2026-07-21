<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Batch_fee_plans extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if(!isset($_SESSION['userdata']) || $_SESSION['userdata']['user_logged_status'] != TRUE)
        {
            redirect('app/logout');
        }
        $this->load->model('batch_fee_plan_model');
    }

    public function index($page = 'list')
    {
        $data = [];
        if(!file_exists(APPPATH.'views/batch_fee_plans/'.$page.'.php'))
        {
            show_404();
        }

        $data['title'] = 'Batch Fee Plans';
        $data['batches'] = $this->batch_fee_plan_model->get_batches();
        $data['fee_types'] = $this->batch_fee_plan_model->get_fee_types();

        $this->load->view('app/templates/header', $data);
        $this->load->view('app/templates/side_panel', $data);
        $this->load->view('batch_fee_plans/'.$page, $data);
        $this->load->view('app/templates/footer', $data);
    }

    public function save()
    {
        if(!$this->input->post()){
            echo json_encode(['response' => FALSE, 'message' => 'Invalid request.']);
            return;
        }

        $batch_id = (int)$this->input->post('batch_id', TRUE);
        $course_id = (int)$this->input->post('course_id', TRUE);
        $tuition_fee_type_id = (int)$this->input->post('tuition_fee_type_id', TRUE);
        $admission_fee_type_id = (int)$this->input->post('admission_fee_type_id', TRUE);
        $tuition_fee_yearly = (float)$this->input->post('tuition_fee_yearly', TRUE);
        $admission_fee = (float)$this->input->post('admission_fee', TRUE);
        $misc_charge_mode = strtoupper(trim($this->input->post('misc_charge_mode', TRUE)));
        $effective_from = trim($this->input->post('effective_from', TRUE));
        $misc_json = $this->input->post('misc_rows_json', TRUE);

        if($batch_id <= 0 || $course_id <= 0 || $tuition_fee_yearly <= 0){
            echo json_encode(['response' => FALSE, 'message' => 'Batch, course and yearly tuition fee are required.']);
            return;
        }

        $misc_rows = [];
        if($misc_json){
            $decoded = json_decode($misc_json, TRUE);
            if(is_array($decoded)){
                foreach($decoded as $row){
                    $fee_type_id = (int)($row['fee_type_id'] ?? 0);
                    $academic_year_no = (int)($row['academic_year_no'] ?? 0);
                    $semester_no = isset($row['semester_no']) && $row['semester_no'] !== '' ? (int)$row['semester_no'] : NULL;
                    $amount = (float)($row['amount'] ?? 0);
                    $charge_mode = strtoupper(trim($row['charge_mode'] ?? $misc_charge_mode));

                    if($fee_type_id <= 0 || $academic_year_no <= 0 || $amount <= 0){
                        continue;
                    }

                    $misc_rows[] = [
                        'academic_year_no' => $academic_year_no,
                        'semester_no' => $charge_mode === 'SEMESTER' ? ($semester_no ?: 1) : NULL,
                        'fee_type_id' => $fee_type_id,
                        'amount' => $amount,
                        'charge_mode' => $charge_mode ?: 'YEARLY',
                        'remarks' => trim($row['remarks'] ?? '')
                    ];
                }
            }
        }

        $plan_id = $this->batch_fee_plan_model->insert_plan([
            'batch_id' => $batch_id,
            'course_id' => $course_id,
            'tuition_fee_type_id' => $tuition_fee_type_id ?: NULL,
            'admission_fee_type_id' => $admission_fee_type_id ?: NULL,
            'tuition_fee_yearly' => $tuition_fee_yearly,
            'admission_fee' => $admission_fee,
            'misc_charge_mode' => in_array($misc_charge_mode, ['YEARLY', 'SEMESTER']) ? $misc_charge_mode : 'YEARLY',
            'effective_from' => $effective_from ?: NULL,
            'created_by' => isset($_SESSION['userdata']['user_id']) ? (int)$_SESSION['userdata']['user_id'] : NULL,
            'status' => 1
        ], $misc_rows);

        echo json_encode([
            'response' => $plan_id > 0,
            'message' => $plan_id > 0 ? 'Batch fee plan saved successfully.' : 'Unable to save batch fee plan.'
        ]);
    }

    public function get_all()
    {
        $rows = $this->batch_fee_plan_model->get_all_plans();
        foreach($rows as $row){
            $row->misc_items = $this->batch_fee_plan_model->get_plan_misc_items($row->id);
        }

        echo json_encode([
            'response' => !empty($rows),
            'message' => !empty($rows) ? count($rows).' Record Found.' : 'No Record Found.',
            'all_record' => $rows
        ]);
    }
}
