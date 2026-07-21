<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Scholarship_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    public function get_all_scholarships()
    {
        return $this->db
            ->order_by('id', 'DESC')
            ->get_where('scholarships', ['status' => 1])
            ->result();
    }

    public function get_scholarship_by_id($id)
    {
        return $this->db
            ->where('id', $id)
            ->get('scholarships')
            ->row();
    }

    public function insert_scholarship($data)
    {
        $this->db->insert('scholarships', $data);
        return $this->db->insert_id();
    }

    public function insert_student_discount($data)
    {
        $this->db->insert('student_fee_discounts', $data);
        return $this->db->insert_id();
    }

    public function insert_student_scholarship($data)
    {
        return $this->db->insert('student_scholarship', $data);
    }

    public function get_discounts_by_admission($admission_id, $academic_year_no = null)
    {
        $this->db->select("
            d.*,
            s.name as scholarship_name
        ");
        $this->db->from('student_fee_discounts d');
        $this->db->join('scholarships s', 's.id = d.scholarship_id', 'left');
        $this->db->where('d.admission_id', $admission_id);
        if($academic_year_no !== null){
            $this->db->where('d.academic_year_no', $academic_year_no);
        }
        $this->db->where('d.status', 1);
        $this->db->order_by('d.id', 'DESC');
        return $this->db->get()->result();
    }

    public function get_total_discount_by_admission($admission_id, $academic_year_no = null)
    {
        $this->db->select('IFNULL(SUM(discount_amount),0) as total_discount')
            ->where('admission_id', $admission_id)
            ->where('status', 1);

        if($academic_year_no !== null){
            $this->db->where('academic_year_no', $academic_year_no);
        }

        $row = $this->db->get('student_fee_discounts')->row();

        return $row ? (float)$row->total_discount : 0;
    }

    public function get_ledger_by_admission($admission_id)
    {
        return $this->db
            ->where('admission_id', $admission_id)
            ->get('student_fee_ledger')
            ->row();
    }

    public function update_ledger($ledger_id, $data)
    {
        return $this->db
            ->where('id', $ledger_id)
            ->update('student_fee_ledger', $data);
    }

    public function get_pending_installments($ledger_id)
    {
        return $this->db
            ->where('ledger_id', $ledger_id)
            ->where('status !=', 'PAID')
            ->order_by('due_date', 'ASC')
            ->get('fee_installments')
            ->result();
    }

    public function update_installment($installment_id, $data)
    {
        return $this->db
            ->where('id', $installment_id)
            ->update('fee_installments', $data);
    }

    public function get_discount_base_for_year($ledger_id, $academic_year_no, $applicable_on = 'TUITION')
    {
        $this->db->select('IFNULL(SUM(gross_amount),0) as total_amount');
        $this->db->where('ledger_id', $ledger_id);
        $this->db->where('academic_year_no', $academic_year_no);

        if($applicable_on !== 'ALL'){
            $this->db->where('component_group', $applicable_on);
        }

        $row = $this->db->get('fee_installments')->row();
        return $row ? (float)$row->total_amount : 0;
    }

    public function get_installments_by_year($ledger_id, $academic_year_no, $applicable_on = 'TUITION')
    {
        $this->db->where('ledger_id', $ledger_id);
        $this->db->where('academic_year_no', $academic_year_no);

        if($applicable_on !== 'ALL'){
            $this->db->where('component_group', $applicable_on);
        }

        return $this->db->order_by('semester_no', 'ASC')->get('fee_installments')->result();
    }
// function new  added after 15-04-2026
    public function get_pending_installments_filtered($ledger_id, $academic_year_no, $applicable_on = 'ALL')
{
    $this->db->where('ledger_id', $ledger_id);
    $this->db->where('academic_year_no', $academic_year_no);
    $this->db->where('status !=', 'PAID');

    if ($applicable_on !== 'ALL') {
        $this->db->where('component_group', $applicable_on);
    }

    return $this->db
        ->order_by('semester_no', 'ASC')
        ->order_by('due_date', 'ASC')
        ->get('fee_installments')
        ->result();
}
}
