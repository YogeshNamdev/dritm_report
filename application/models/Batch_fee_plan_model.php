<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Batch_fee_plan_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    public function get_fee_types()
    {
        return $this->db->where('status', 1)->order_by('fee_name', 'ASC')->get('fee_types')->result();
    }

    public function get_batches()
    {
        return $this->db->query("
            SELECT b.*, c.course_name, c.duration, s.session_name
            FROM batches b
            LEFT JOIN courses c ON c.id = b.course_id
            LEFT JOIN sessions s ON s.id = b.session_id
            WHERE b.status = 1
            ORDER BY b.id DESC
        ")->result();
    }

    public function insert_plan($plan, $misc_rows = [])
    {
        $this->db->trans_start();
        $this->db->insert('batch_fee_plans', $plan);
        $plan_id = $this->db->insert_id();

        foreach($misc_rows as $row){
            $row['plan_id'] = $plan_id;
            $this->db->insert('batch_fee_plan_misc_items', $row);
        }

        $this->db->trans_complete();
        return $this->db->trans_status() ? $plan_id : 0;
    }

    public function get_plan_by_batch($batch_id)
    {
        return $this->db->query("
            SELECT p.*, b.batch_name, c.course_name, c.duration
            FROM batch_fee_plans p
            LEFT JOIN batches b ON b.id = p.batch_id
            LEFT JOIN courses c ON c.id = p.course_id
            WHERE p.batch_id = ? AND p.status = 1
            ORDER BY p.id DESC
            LIMIT 1
        ", [$batch_id])->row();
    }

    public function get_plan_misc_items($plan_id)
    {
        return $this->db->query("
            SELECT i.*, f.fee_name
            FROM batch_fee_plan_misc_items i
            LEFT JOIN fee_types f ON f.id = i.fee_type_id
            WHERE i.plan_id = ? AND i.status = 1
            ORDER BY i.academic_year_no ASC, i.semester_no ASC, i.id ASC
        ", [$plan_id])->result();
    }

    public function get_all_plans()
    {
        return $this->db->query("
            SELECT p.*, b.batch_name, c.course_name, c.duration
            FROM batch_fee_plans p
            LEFT JOIN batches b ON b.id = p.batch_id
            LEFT JOIN courses c ON c.id = p.course_id
            WHERE p.status = 1
            ORDER BY p.id DESC
        ")->result();
    }
}
