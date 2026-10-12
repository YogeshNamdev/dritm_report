<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance_model extends CI_Model {

    // Excel se compare hue thresholds (RawAttendance sheet, column V formula)
    const HD_MIN_MINUTES = 240;
    const P_MIN_MINUTES  = 420;

    public function __construct()
    {
        parent::__construct();
    }

    // ------------------------------------------------------------------
    // MASTER / LOOKUP HELPERS
    // ------------------------------------------------------------------

    // ---- LEFT / INACTIVE RULE ----
    // Employee 'Inactive' hone par admin 'inactive_from' date deta hai.
    // inactive_from se pehle ke din normal (roster/attendance/headcount me
    // count), us din aur uske baad 'Left' — kisi bhi count me nahi.
    // (Inactive par inactive_from khali ho to poori tarah left maana jata hai.)

    /** SQL: employee $alias, $date_expr wale din left hai */
    private function sql_left_on($alias, $date_expr)
    {
        return "({$alias}.emp_status = 'Inactive' AND ({$alias}.inactive_from IS NULL OR {$date_expr} >= {$alias}.inactive_from))";
    }

    /** SQL: employee us din active (count hone layak) hai */
    private function sql_active_on($alias, $date_expr)
    {
        return "NOT ".$this->sql_left_on($alias, $date_expr);
    }

    private function is_left_on($emp, $date)
    {
        return $emp->emp_status == 'Inactive' && (empty($emp->inactive_from) || $date >= $emp->inactive_from);
    }

    public function get_all_att_employees()
    {
        $this->db->select('e.emp_id, e.name, e.lob, e.employment_type, u.msd_id, u.user_name')
                  ->from('att_employees e')
                  ->join('master_users u', 'u.emp_id = e.emp_id')
                  ->where('e.tl_emp_id IS NOT NULL', null, false) // TL agent nahi hai
                  ->order_by('e.name', 'ASC');
        return $this->db->get()->result();
    }

    /**
     * Poori employee-master list (management page ke liye) — sab columns.
     */
    public function get_employee_master_list()
    {
        $this->db->select('e.*, u.msd_id, u.user_name, u.role_id, t.name as tl_name')
                  ->from('att_employees e')
                  ->join('master_users u', 'u.emp_id = e.emp_id')
                  ->join('att_employees t', 't.emp_id = e.tl_emp_id', 'left')
                  ->order_by('e.name', 'ASC');
        return $this->db->get()->result();
    }

    /**
     * master_users me se wo log jo abhi tak att_employees me nahi add
     * hue — "Add Employee" dropdown ke liye (taaki koi already-added
     * employee dobara na chuna jaye).
     */
    public function get_unlinked_master_users()
    {
        $this->db->select('u.emp_id, u.user_name, u.msd_id, u.role_id')
                  ->from('master_users u')
                  ->where('NOT EXISTS (SELECT 1 FROM att_employees e WHERE e.emp_id = u.emp_id)', null, false)
                  ->order_by('u.user_name', 'ASC');
        return $this->db->get()->result();
    }

    public function add_employee($data)
    {
        $this->db->where('emp_id', $data['emp_id']);
        $exists = $this->db->get('att_employees')->row();
        if($exists) return FALSE; // pehle se hai, add_employee dobara nahi

        return $this->db->insert('att_employees', $data);
    }

    public function update_employee($emp_id, $data)
    {
        $old = $this->db->where('emp_id', $emp_id)->get('att_employees')->row();

        $result = $this->db->where('emp_id', $emp_id)->update('att_employees', $data);

        // Status / inactive_from badla ho to is employee ki saari attendance dobara compute karo
        // taaki 'Left' / normal status sahi dino par lage (reactivate par wapas normal ho jaye).
        $new_status = isset($data['emp_status']) ? $data['emp_status'] : ($old ? $old->emp_status : null);
        if(($old && $old->emp_status == 'Inactive') || $new_status == 'Inactive')
        {
            $this->db->select('attendance_date')->from('att_attendance_daily')->where('emp_id', $emp_id);
            $dates = $this->db->get()->result();
            foreach($dates as $d)
            {
                $this->recompute_attendance_daily($emp_id, $d->attendance_date);
            }
        }
        return $result;
    }

    public function get_employee($emp_id)
    {
        $this->db->select('e.*, u.msd_id, u.user_name')
                  ->from('att_employees e')
                  ->join('master_users u', 'u.emp_id = e.emp_id')
                  ->where('e.emp_id', $emp_id);
        return $this->db->get()->row();
    }

    public function get_distinct_lob()
{
    $this->db->distinct();
    $this->db->select('lob');
    $this->db->from('att_employees');
    $this->db->where('lob IS NOT NULL');
    $this->db->order_by('lob', 'ASC');

    return $this->db->get()->result();
}

    public function get_all_tl_list()
{
    $this->db->select('e.tl_emp_id AS emp_id, t.name');
    $this->db->distinct();
    $this->db->from('att_employees e');
    $this->db->join('att_employees t', 't.emp_id = e.tl_emp_id', 'left');
    $this->db->where('e.tl_emp_id IS NOT NULL');
    $this->db->where('e.tl_emp_id !=', '');
    $this->db->order_by('t.name', 'ASC');

    $query = $this->db->get();

    if (!$query) {
        echo '<pre>';
        print_r($this->db->error());
        echo '</pre>';
        die;
    }

    return $query->result();
}

    /**
     * TL-select dropdown ke liye saare active employees (koi bhi kisi
     * ka TL ban sakta hai — abhi tak koi TL assigned na ho tab bhi).
     */
    public function get_all_employees_for_tl_dropdown()
    {
        // sirf TL (tl_emp_id NULL) hi kisi ka TL ban sakta hai
        $this->db->select('emp_id, name')->from('att_employees')
                  ->where('tl_emp_id IS NULL', null, false)
                  ->order_by('name', 'ASC');
        return $this->db->get()->result();
    }

    private function find_att_employee_by_username($username)
    {
        $this->db->select('emp_id')->from('master_users')->where('msd_id', $username);
        $row = $this->db->get()->row();
        return $row ? (int)$row->emp_id : FALSE;
    }

    private function find_emp_id_by_name($name)
    {
        $name = trim($name);
        if($name == '') return FALSE;
        $this->db->select('emp_id')->from('att_employees')->where('name', $name)->limit(1);
        $row = $this->db->get()->row();
        return $row ? (int)$row->emp_id : FALSE;
    }

    // ------------------------------------------------------------------
    // FILE UPLOAD BATCH TRACKING
    // ------------------------------------------------------------------

    public function create_upload_batch($data)
    {
        $this->db->insert('att_file_uploads', $data);
        return $this->db->insert_id();
    }

    public function update_upload_batch($id, $data)
    {
        $this->db->where('id', $id)->update('att_file_uploads', $data);
        return $this->db->affected_rows();
    }

    public function get_recent_uploads($limit = 15)
    {
        $this->db->select('fu.*, u.user_name as uploaded_by_name')
                  ->from('att_file_uploads fu')
                  ->join('master_users u', 'u.emp_id = fu.uploaded_by', 'left')
                  ->order_by('fu.created_at', 'DESC')
                  ->limit($limit);
        return $this->db->get()->result();
    }

    // ------------------------------------------------------------------
    // RAW REPORT PROCESSING (dialer export -> att_raw_call_logs)
    // Expected columns (Raw Report sheet headers):
    //   'Date Time' (e.g. '01-09-2026 TO 01-09-2026'), 'Agent Username',
    //   'Campaigns', 'Net Login Duration' (H:i:s)
    // ------------------------------------------------------------------

    public function process_raw_report_rows($rows, $upload_id)
    {
        $success_count = 0;
        $skipped_count = 0;
        $errors = array();
        $row_num = 1;
        $dates_seen = array(); // upload me jo bhi dates aayi, un sabke liye baad me poora recompute karenge

        foreach($rows as $row)
        {
            $row_num++;

            $username = trim($row['Agent Username'] ?? '');
            $date_raw = trim($row['Date Time'] ?? '');
            $campaign = trim($row['Campaigns'] ?? '');
            $net_login = $row['Net Login Duration'] ?? null;

            if($username == '' || $date_raw == '')
            {
                $skipped_count++;
                $errors[] = "Row {$row_num}: Date ya Agent Username khali hai — skipped.";
                continue;
            }

            $emp_id = $this->find_att_employee_by_username($username);
            if($emp_id === FALSE)
            {
                $skipped_count++;
                $errors[] = "Row {$row_num}: Agent Username '{$username}' not found in system — login banao pehle.";
                continue;
            }

            // 'Date Time' format: '01-09-2026 TO 01-09-2026' -> sirf pehla date
            $date_part = trim(explode('TO', $date_raw)[0]);
            $log_date = $this->parse_ddmmyyyy($date_part);
            if($log_date === FALSE)
            {
                $skipped_count++;
                $errors[] = "Row {$row_num}: Date parse nahi hui ('{$date_raw}') — skipped.";
                continue;
            }

            $net_login_minutes = $this->time_str_to_minutes($net_login);

            $data = array(
                'upload_batch_id' => $upload_id,
                'log_date' => $log_date,
                'agent_username' => $username,
                'campaign' => $campaign,
                'net_login_duration_minutes' => $net_login_minutes
            );

            // upsert on (log_date, agent_username, campaign)
            $this->db->where('log_date', $log_date)
                      ->where('agent_username', $username)
                      ->where('campaign', $campaign);
            $exists = $this->db->get('att_raw_call_logs')->row();

            if($exists)
            {
                $this->db->where('id', $exists->id)->update('att_raw_call_logs', $data);
            }
            else
            {
                $this->db->insert('att_raw_call_logs', $data);
            }

            // seedha att_attendance_daily.net_login_minutes bhi refresh kar do (agar ek se zyada campaign ho to sum)
            $this->refresh_net_login_for_emp_date($emp_id, $log_date);
            $this->recompute_attendance_daily($emp_id, $log_date);
            $dates_seen[$log_date] = TRUE;

            $success_count++;
        }

        // FIX: kisi bhi agent ka us din Raw Report me record hi na ho (jaise CL/WO/LP
        // wale din login hota hi nahi), to bhi uska status roster ke hisaab se sahi
        // dikhna chahiye — isliye upload ki poori date-range ke liye HAR active agent
        // ka status ek baar force-recompute kar dete hain (login na ho to bhi roster
        // wala WO/CL/LP/HD ya AA sahi ban jayega, blank nahi rahega).
        if(!empty($dates_seen))
        {
            $this->recompute_all_for_dates(array_keys($dates_seen));
        }

        return array(
            'total_rows' => $row_num - 1,
            'success_count' => $success_count,
            'skipped_count' => $skipped_count,
            'errors' => $errors
        );
    }

    /**
     * Har active agent (TL chhod ke) ke liye di gayi dates pe status
     * recompute karta hai — chahe us din login record ho ya na ho.
     */
    private function recompute_all_for_dates($dates)
    {
        $this->db->select('emp_id')->from('att_employees')->where('tl_emp_id IS NOT NULL', null, false);
        $employees = $this->db->get()->result();

        foreach($dates as $date)
        {
            foreach($employees as $e)
            {
                $this->recompute_attendance_daily($e->emp_id, $date);
            }
        }
    }

    private function refresh_net_login_for_emp_date($emp_id, $date)
    {
        // agar din me multiple campaign rows hain to sabka net login minutes sum karo
        $this->db->select_sum('net_login_duration_minutes', 'total_minutes')
                  ->from('att_raw_call_logs')
                  ->join('master_users u', "u.msd_id = att_raw_call_logs.agent_username")
                  ->where('u.emp_id', $emp_id)
                  ->where('log_date', $date);
        $row = $this->db->get()->row();
        $total = $row ? (float)$row->total_minutes : 0;

        $this->db->where('emp_id', $emp_id)->where('attendance_date', $date);
        $exists = $this->db->get('att_attendance_daily')->row();

        if($exists)
        {
            $this->db->where('id', $exists->id)->update('att_attendance_daily', array('net_login_minutes' => $total));
        }
        else
        {
            $this->db->insert('att_attendance_daily', array(
                'emp_id' => $emp_id,
                'attendance_date' => $date,
                'net_login_minutes' => $total
            ));
        }
    }

    // ------------------------------------------------------------------
    // ROSTER / EMPLOYEE MASTER ROWS PROCESSING
    // Expected columns (RawAttendance sheet headers):
    //   'Emp Code', 'Name', 'TL NAME', 'Process', 'FT / PT', 'Status',
    //   'LOB', 'DOJ', 'Roster Shift', 'Date'
    // ------------------------------------------------------------------

    // ------------------------------------------------------------------
    // ROSTER PROCESSING — WIDE GRID FORMAT
    // (Morning_Shift / Evening_Shift sheets: 1 row per agent, 1 column
    //  per date. Cell value = shift time-range e.g. '7:00-14:30', ya
    //  ek code: 'Agent Choice WO' / 'TL Choice WO' / 'CL' / 'HD' / 'LP'.
    //  Header layout: row with 'Agent id' text = header row; usi row
    //  ke baad wali columns me se jinki header value ek valid date ho
    //  wahi din-wise data columns hain — baaki (jaise 'Wk -1' summary
    //  block) automatically ignore ho jaate hain.)
    // ------------------------------------------------------------------

    public function process_shift_roster_grid($grid, $upload_id, $shift_group)
    {
        $success_employees = 0;
        $success_cells = 0;
        $skipped_count = 0;
        $errors = array();

        if(empty($grid))
        {
            return array('total_rows' => 0, 'success_count' => 0, 'skipped_count' => 0, 'errors' => array('Sheet khali hai ya read nahi hui.'));
        }

        $row_numbers = array_keys($grid);
        sort($row_numbers);

        // header row dhoondo (jisme kisi column ki value 'Agent id' ho)
        $header_row_num = null;
        foreach($row_numbers as $rn)
        {
            foreach($grid[$rn] as $col => $val)
            {
                if(trim((string)$val) === 'Agent id')
                {
                    $header_row_num = $rn;
                    break 2;
                }
            }
        }

        if($header_row_num === null)
        {
            return array('total_rows' => 0, 'success_count' => 0, 'skipped_count' => 0, 'errors' => array("'Agent id' header nahi mila — sheet format check karo."));
        }

        $header = $grid[$header_row_num];

        // fixed info columns (header text se dhoondo, letter hardcode nahi)
        $col_agent_id = $col_name = $col_tl = $col_gender = $col_campaign = null;
        $date_cols = array(); // col_letter => 'Y-m-d'

        foreach($header as $col_letter => $val)
        {
            $val = trim((string)$val);
            if($val === 'Agent id') $col_agent_id = $col_letter;
            else if(stripos($val, 'Agent Name') === 0) $col_name = $col_letter;
            else if($val === 'TL Name') $col_tl = $col_letter;
            else if($val === 'Gender') $col_gender = $col_letter;
            else if($val === 'Campaign') $col_campaign = $col_letter;
            else if(preg_match('/^\d{4}-\d{2}-\d{2}$/', $val)) $date_cols[$col_letter] = $val; // library ne already Y-m-d bana diya hoga
        }

        if($col_agent_id === null)
        {
            return array('total_rows' => 0, 'success_count' => 0, 'skipped_count' => 0, 'errors' => array("'Agent id' column mila par letter resolve nahi hua."));
        }
        if(empty($date_cols))
        {
            return array('total_rows' => 0, 'success_count' => 0, 'skipped_count' => 0, 'errors' => array("Koi date column nahi mili — sheet me date-header row check karo."));
        }

        $total_employee_rows = 0;

        // header row ke baad ki saari rows me se, jinme agent_id column non-empty hai wahi data rows hain
        // (isse beech ki sub-header row — day-name/'Agent Choice WO' labels — apne aap skip ho jaati hai
        //  kyunki uski agent_id column khali hoti hai)
        foreach($row_numbers as $rn)
        {
            if($rn <= $header_row_num) continue;

            $row = $grid[$rn];
            $agent_id = isset($row[$col_agent_id]) ? trim((string)$row[$col_agent_id]) : '';
            if($agent_id === '') continue; // sub-header ya blank row, skip

            $total_employee_rows++;

            $emp_id = $this->find_att_employee_by_username($agent_id);
            if($emp_id === FALSE)
            {
                $skipped_count++;
                $errors[] = "Row {$rn}: Agent id '{$agent_id}' not found in system — login banao pehle.";
                continue;
            }

            $name = $col_name ? trim((string)($row[$col_name] ?? '')) : '';
            $tl_name = $col_tl ? trim((string)($row[$col_tl] ?? '')) : '';
            $gender = $col_gender ? trim((string)($row[$col_gender] ?? '')) : '';
            $campaign = $col_campaign ? trim((string)($row[$col_campaign] ?? '')) : '';

            $this->upsert_att_employee_from_roster($emp_id, $name, $tl_name, $gender, $campaign, $shift_group);

            foreach($date_cols as $col_letter => $roster_date)
            {
                $cell_value = isset($row[$col_letter]) ? trim((string)$row[$col_letter]) : '';
                if($cell_value === '') continue; // us din ka roster diya hi nahi gaya, skip

                $classified = $this->classify_roster_cell($cell_value);
                if($classified === FALSE)
                {
                    $errors[] = "Row {$rn}, {$roster_date}: '{$cell_value}' samajh nahi aaya — skipped.";
                    continue;
                }

                $roster_data = array_merge($classified, array(
                    'emp_id' => $emp_id,
                    'roster_date' => $roster_date,
                    'roster_value' => $cell_value
                ));

                $this->db->where('emp_id', $emp_id)->where('roster_date', $roster_date);
                $exists = $this->db->get('att_roster_plan')->row();

                if($exists)
                {
                    $this->db->where('id', $exists->id)->update('att_roster_plan', $roster_data);
                }
                else
                {
                    $this->db->insert('att_roster_plan', $roster_data);
                }

                $this->recompute_attendance_daily($emp_id, $roster_date);
                $success_cells++;
            }

            $success_employees++;
        }

        return array(
            'total_rows' => $total_employee_rows,
            'success_count' => $success_employees,
            'skipped_count' => $skipped_count,
            'success_cells' => $success_cells,
            'errors' => $errors
        );
    }

    private function upsert_att_employee_from_roster($emp_id, $name, $tl_name, $gender, $campaign, $shift_group)
    {
        $data = array(
            'name' => $name,
            'lob' => $campaign,
            'gender' => $gender,
            'shift_group' => $shift_group
        );

        if($tl_name != '')
        {
            $tl_emp_id = $this->find_emp_id_by_name($tl_name);
            if($tl_emp_id !== FALSE)
            {
                $data['tl_emp_id'] = $tl_emp_id;
            }
        }

        $this->db->where('emp_id', $emp_id);
        $exists = $this->db->get('att_employees')->row();

        if($exists)
        {
            $this->db->where('emp_id', $emp_id)->update('att_employees', $data);
        }
        else
        {
            $data['emp_id'] = $emp_id;
            $data['emp_status'] = 'Active'; // pehli baar dikha hai to default Active — Status field yahan nahi aata
            $data['employment_type'] = 'FT'; // roster grid me FT/PT nahi bataya jaata, default FT (baad me manually update kar sakte ho)
            $this->db->insert('att_employees', $data);
        }
    }

    /**
     * Roster cell ki raw text ko roster_type + (agar shift time hai to)
     * shift_start/shift_end/login_target_minutes me classify karta hai.
     * Return FALSE = unrecognized value (na koi known code, na valid time-range).
     */
    private function classify_roster_cell($value)
    {
        if($value === 'Agent Choice WO')
        {
            return array('roster_type' => 'WO', 'wo_type' => 'Agent', 'shift_start' => null, 'shift_end' => null, 'login_target_minutes' => null);
        }
        if($value === 'TL Choice WO')
        {
            return array('roster_type' => 'WO', 'wo_type' => 'TL', 'shift_start' => null, 'shift_end' => null, 'login_target_minutes' => null);
        }
        if($value === 'CL')
        {
            return array('roster_type' => 'CL', 'wo_type' => null, 'shift_start' => null, 'shift_end' => null, 'login_target_minutes' => null);
        }
        if($value === 'HD')
        {
            return array('roster_type' => 'HD', 'wo_type' => null, 'shift_start' => null, 'shift_end' => null, 'login_target_minutes' => null);
        }
        if($value === 'LP')
        {
            return array('roster_type' => 'LP', 'wo_type' => null, 'shift_start' => null, 'shift_end' => null, 'login_target_minutes' => null);
        }

        // shift time-range: '7:00-14:30', '14:30-22:00' etc.
        if(preg_match('/^(\d{1,2}):(\d{2})\s*-\s*(\d{1,2}):(\d{2})$/', $value, $m))
        {
            $start_min = ((int)$m[1] * 60) + (int)$m[2];
            $end_min = ((int)$m[3] * 60) + (int)$m[4];
            if($end_min <= $start_min) $end_min += 1440; // raat paar karne wali shift

            return array(
                'roster_type' => 'SHIFT',
                'wo_type' => null,
                'shift_start' => sprintf('%02d:%02d:00', (int)$m[1], (int)$m[2]),
                'shift_end' => sprintf('%02d:%02d:00', (int)$m[3], (int)$m[4]),
                'login_target_minutes' => $end_min - $start_min
            );
        }

        return FALSE;
    }

    // ------------------------------------------------------------------
    // EXCEPTIONS
    // ------------------------------------------------------------------

    public function get_exceptions($date_from = null, $date_to = null)
    {
        $this->db->select('ex.*, e.name, u.msd_id')
                  ->from('att_exceptions ex')
                  ->join('att_employees e', 'e.emp_id = ex.emp_id')
                  ->join('master_users u', 'u.emp_id = ex.emp_id')
                  ->order_by('ex.exception_date', 'DESC');
        if($date_from) $this->db->where('ex.exception_date >=', $date_from);
        if($date_to)   $this->db->where('ex.exception_date <=', $date_to);
        return $this->db->get()->result();
    }

    public function upsert_exception($data)
    {
        $this->db->where('emp_id', $data['emp_id'])->where('exception_date', $data['exception_date']);
        $exists = $this->db->get('att_exceptions')->row();

        if($exists)
        {
            return $this->db->where('id', $exists->id)->update('att_exceptions', $data);
        }
        return $this->db->insert('att_exceptions', $data);
    }

    // ------------------------------------------------------------------
    // ROSTER BUILDER (manual roster creation — admin UI)
    // ------------------------------------------------------------------

    public function get_shift_presets()
    {
        $this->db->order_by('start_time', 'ASC');
        return $this->db->get('att_shifts')->result();
    }

    public function save_shift_preset($shift_name, $start_time, $end_time)
    {
        $start_min = $this->hhmm_to_minutes($start_time);
        $end_min = $this->hhmm_to_minutes($end_time);
        if($end_min <= $start_min) $end_min += 1440;

        $this->db->insert('att_shifts', array(
            'shift_name' => $shift_name,
            'start_time' => $start_time.':00',
            'end_time'   => $end_time.':00',
            'login_target_minutes' => $end_min - $start_min
        ));
        return $this->db->insert_id();
    }

    public function delete_shift_preset($id)
    {
        return $this->db->where('id', $id)->delete('att_shifts');
    }

    /**
     * Roster-builder grid: employees (filtered) + un-sabka har date ka
     * current roster value (edit karne ke liye), Attendance Matrix se
     * alag — wahan computed final_status dikhta hai, yahan editable
     * roster_plan dikhta hai.
     */
    public function get_roster_grid_data($month, $shift_group = null, $lob = null, $tl_id = null)
    {
        $start = $month.'-01';
        $end = date('Y-m-t', strtotime($start));

        $this->db->select('e.emp_id, e.name, e.lob, e.shift_group, e.emp_status, e.inactive_from, u.msd_id, t.name as tl_name')
                  ->from('att_employees e')
                  ->join('master_users u', 'u.emp_id = e.emp_id')
                  ->join('att_employees t', 't.emp_id = e.tl_emp_id', 'left')
                  ->where('e.tl_emp_id IS NOT NULL', null, false) // TL roster grid me nahi aayenge
                  ->where($this->sql_active_on('e', $this->db->escape($start)), null, false) // pura mahina left ho chuka to row hi nahi
                  ->order_by('e.name', 'ASC');

        if($shift_group) $this->db->where('e.shift_group', $shift_group);
        if($lob) $this->db->where('e.lob', $lob);
        if($tl_id) $this->db->where('e.tl_emp_id', $tl_id);

        $employees = $this->db->get()->result();

        $date_columns = array();
        $cursor = strtotime($start);
        $end_ts = strtotime($end);
        while($cursor <= $end_ts)
        {
            $date_columns[] = date('Y-m-d', $cursor);
            $cursor = strtotime('+1 day', $cursor);
        }

        if(empty($employees))
        {
            return array('rows' => array(), 'date_columns' => $date_columns);
        }

        $emp_ids = array_map(function($e){ return $e->emp_id; }, $employees);

        $this->db->select('emp_id, roster_date, roster_type, wo_type, roster_value, shift_start, shift_end')
                  ->from('att_roster_plan')
                  ->where_in('emp_id', $emp_ids)
                  ->where('roster_date >=', $start)
                  ->where('roster_date <=', $end);
        $roster_rows = $this->db->get()->result();

        $roster_map = array();
        foreach($roster_rows as $r)
        {
            $roster_map[$r->emp_id][$r->roster_date] = $r;
        }

        $rows = array();
        foreach($employees as $e)
        {
            $row = array(
                'emp_id' => $e->emp_id,
                'name' => $e->name,
                'msd_id' => $e->msd_id,
                'lob' => $e->lob,
                'tl_name' => $e->tl_name,
                'shift_group' => $e->shift_group,
                'days' => array()
            );
            foreach($date_columns as $d)
            {
                if($this->is_left_on($e, $d))
                {
                    $row['days'][$d] = array('left' => TRUE);
                }
                else if(isset($roster_map[$e->emp_id][$d]))
                {
                    $r = $roster_map[$e->emp_id][$d];
                    $row['days'][$d] = array(
                        'roster_type' => $r->roster_type,
                        'wo_type' => $r->wo_type,
                        'roster_value' => $r->roster_value,
                        'shift_start' => $r->shift_start,
                        'shift_end' => $r->shift_end
                    );
                }
                else
                {
                    $row['days'][$d] = null;
                }
            }
            $rows[] = $row;
        }

        return array('rows' => $rows, 'date_columns' => $date_columns);
    }

    /**
     * Ek employee ke ek din ka roster manually set karta hai.
     * $code ek in me se ek format me aata hai (UI dropdown se):
     *   'SHIFT:<shift_id>'        -> att_shifts se preset uthao
     *   'CUSTOM:HH:MM-HH:MM'      -> manual time range
     *   'AWO' | 'TWO' | 'CL' | 'HD' | 'LP'
     *   'CLEAR'                   -> us din ka roster hata do
     */
    public function apply_roster_value($emp_id, $date, $code)
    {
        $emp = $this->db->select('emp_status, inactive_from')->where('emp_id', $emp_id)->get('att_employees')->row();
        if($emp && $this->is_left_on($emp, $date))
        {
            return array('ok' => FALSE, 'message' => 'Employee is date par left hai — roster nahi ban sakta.');
        }

        $this->db->where('emp_id', $emp_id)->where('roster_date', $date);
        $existing = $this->db->get('att_roster_plan')->row();

        // Agar pehle CL thi aur ab kuch aur ban rahi hai (ya CLEAR ho rahi hai),
        // to us CL ko refund kar do — warna balance galat ho jayega.
        if($existing && $existing->roster_type == 'CL' && $code !== 'CL')
        {
            $this->refund_cl($emp_id, $date);
        }

        if($code === 'CLEAR')
        {
            $this->db->where('emp_id', $emp_id)->where('roster_date', $date)->delete('att_roster_plan');
            $this->recompute_attendance_daily($emp_id, $date);
            return array('ok' => TRUE, 'message' => 'Cleared.');
        }

        // WO cap check (sirf jab naya WO lagaya ja raha ho, pehle se WO nahi tha)
        if(($code === 'AWO' || $code === 'TWO') && (!$existing || $existing->roster_type != 'WO'))
        {
            $wo_check = $this->check_wo_cap($emp_id, $date);
            if(!$wo_check['ok']) return $wo_check;
        }

        // CL eligibility + balance check (sirf jab naya CL lagaya ja raha ho)
        if($code === 'CL' && (!$existing || $existing->roster_type != 'CL'))
        {
            $cl_check = $this->check_and_deduct_cl($emp_id, $date);
            if(!$cl_check['ok']) return $cl_check;
        }

        $classified = $this->resolve_roster_code($code);
        if($classified === FALSE) return array('ok' => FALSE, 'message' => "'{$code}' samajh nahi aaya.");

        $roster_data = array_merge($classified, array(
            'emp_id' => $emp_id,
            'roster_date' => $date
        ));

        if($existing)
        {
            $this->db->where('id', $existing->id)->update('att_roster_plan', $roster_data);
        }
        else
        {
            $this->db->insert('att_roster_plan', $roster_data);
        }

        $this->recompute_attendance_daily($emp_id, $date);
        return array('ok' => TRUE, 'message' => 'Saved.');
    }

    /**
     * Ek value ko multiple employees x date-range (optionally sirf kuch
     * weekdays) par ek saath apply karta hai — poore hafte/mahine ka
     * roster ek click me bharne ke liye. Har (emp,date) ke liye WO-cap /
     * CL-balance validation apply_roster_value() khud handle karta hai —
     * jo fail hote hain unke error yahan collect ho kar return hote hain.
     */
    public function bulk_apply_roster($emp_ids, $date_from, $date_to, $weekdays, $code)
    {
        $applied = 0;
        $skipped = array();
        $cursor = strtotime($date_from);
        $end_ts = strtotime($date_to);

        while($cursor <= $end_ts)
        {
            $date = date('Y-m-d', $cursor);
            $dow = (int)date('N', $cursor); // 1=Mon..7=Sun

            if(empty($weekdays) || in_array($dow, $weekdays))
            {
                foreach($emp_ids as $emp_id)
                {
                    $result = $this->apply_roster_value((int)$emp_id, $date, $code);
                    if($result['ok'])
                    {
                        $applied++;
                    }
                    else
                    {
                        $skipped[] = "Emp {$emp_id} / {$date}: {$result['message']}";
                    }
                }
            }
            $cursor = strtotime('+1 day', $cursor);
        }

        return array('applied' => $applied, 'skipped' => $skipped);
    }

    // ------------------------------------------------------------------
    // WO CAP (max 4 / month / employee)
    // ------------------------------------------------------------------

    const MAX_WO_PER_MONTH = 4;

    private function check_wo_cap($emp_id, $date)
    {
        $month_start = date('Y-m-01', strtotime($date));
        $month_end = date('Y-m-t', strtotime($date));

        $this->db->where('emp_id', $emp_id)
                  ->where('roster_type', 'WO')
                  ->where('roster_date >=', $month_start)
                  ->where('roster_date <=', $month_end);
        $count = $this->db->count_all_results('att_roster_plan');

        if($count >= self::MAX_WO_PER_MONTH)
        {
            return array('ok' => FALSE, 'message' => "Is employee ke is month me already {$count} WO lag chuki hain (max ".self::MAX_WO_PER_MONTH.").");
        }
        return array('ok' => TRUE, 'message' => '');
    }

    // ------------------------------------------------------------------
    // CL ENGINE (eligibility: DOJ se 6 mahine; balance cap: max 3)
    // ------------------------------------------------------------------

    const CL_MAX_BALANCE = 3;
    const CL_ELIGIBILITY_MONTHS = 6;

    public function is_cl_eligible($emp_id, $as_of_date = null)
    {
        $as_of_date = $as_of_date ?: date('Y-m-d');
        $this->db->select('doj')->from('att_employees')->where('emp_id', $emp_id);
        $emp = $this->db->get()->row();
        if(!$emp || empty($emp->doj)) return FALSE;

        $months_since_doj = (strtotime($as_of_date) - strtotime($emp->doj)) / (30.44 * 86400);
        return $months_since_doj >= self::CL_ELIGIBILITY_MONTHS;
    }

    private function ensure_cl_balance_row($emp_id)
    {
        $this->db->where('emp_id', $emp_id);
        $row = $this->db->get('att_cl_balance')->row();
        if(!$row)
        {
            $this->db->insert('att_cl_balance', array('emp_id' => $emp_id, 'cl_balance' => 0));
            return (object)array('emp_id' => $emp_id, 'cl_balance' => 0, 'last_accrual_month' => null);
        }
        return $row;
    }

    private function check_and_deduct_cl($emp_id, $date)
    {
        if(!$this->is_cl_eligible($emp_id, $date))
        {
            return array('ok' => FALSE, 'message' => 'Employee CL ke liye eligible nahi hai (6 mahine se kam tenure).');
        }

        $balance_row = $this->ensure_cl_balance_row($emp_id);
        if($balance_row->cl_balance < 1)
        {
            return array('ok' => FALSE, 'message' => 'Employee ke paas CL balance nahi bacha hai.');
        }

        $new_balance = $balance_row->cl_balance - 1;
        $this->db->where('emp_id', $emp_id)->update('att_cl_balance', array('cl_balance' => $new_balance));
        $this->db->insert('att_cl_ledger', array(
            'emp_id' => $emp_id, 'ledger_date' => $date, 'txn_type' => 'USED',
            'amount' => -1, 'balance_after' => $new_balance, 'note' => "CL used on {$date}"
        ));

        return array('ok' => TRUE, 'message' => '');
    }

    private function refund_cl($emp_id, $date)
    {
        $balance_row = $this->ensure_cl_balance_row($emp_id);
        $new_balance = min($balance_row->cl_balance + 1, self::CL_MAX_BALANCE);

        $this->db->where('emp_id', $emp_id)->update('att_cl_balance', array('cl_balance' => $new_balance));
        $this->db->insert('att_cl_ledger', array(
            'emp_id' => $emp_id, 'ledger_date' => $date, 'txn_type' => 'ADJUST',
            'amount' => ($new_balance - $balance_row->cl_balance), 'balance_after' => $new_balance,
            'note' => "CL refunded — {$date} ka roster badal diya gaya"
        ));
    }

    /**
     * Har mahine ke start me chalao (cron/manual button) — sabhi eligible
     * active employees ko +1 CL accrue karta hai, max 3 balance cap ke
     * saath (cap se upar jo bhi jaata, wo lapse ho jaata hai).
     */
    public function run_monthly_cl_accrual($month)
    {
        $month_start = $month.'-01';

        $this->db->select('emp_id')->from('att_employees')->where('emp_status', 'Active')->where('tl_emp_id IS NOT NULL', null, false);
        $employees = $this->db->get()->result();

        $accrued = 0;
        $lapsed = 0;

        foreach($employees as $e)
        {
            if(!$this->is_cl_eligible($e->emp_id, $month_start)) continue;

            $balance_row = $this->ensure_cl_balance_row($e->emp_id);
            if($balance_row->last_accrual_month === $month) continue; // is month already accrue ho chuka

            $raw_new_balance = $balance_row->cl_balance + 1;
            $capped_balance = min($raw_new_balance, self::CL_MAX_BALANCE);
            $lapse_amount = $raw_new_balance - $capped_balance;

            $this->db->where('emp_id', $e->emp_id)->update('att_cl_balance', array(
                'cl_balance' => $capped_balance,
                'last_accrual_month' => $month
            ));

            $this->db->insert('att_cl_ledger', array(
                'emp_id' => $e->emp_id, 'ledger_date' => $month_start, 'txn_type' => 'ACCRUAL',
                'amount' => 1, 'balance_after' => $capped_balance, 'note' => "Monthly accrual for {$month}"
            ));
            $accrued++;

            if($lapse_amount > 0)
            {
                $this->db->insert('att_cl_ledger', array(
                    'emp_id' => $e->emp_id, 'ledger_date' => $month_start, 'txn_type' => 'LAPSE',
                    'amount' => -$lapse_amount, 'balance_after' => $capped_balance, 'note' => "Cap se upar — {$lapse_amount} lapse"
                ));
                $lapsed++;
            }
        }

        return array('accrued' => $accrued, 'lapsed' => $lapsed);
    }

    /**
     * CL Tracker — har employee ka DOJ, tenure, eligibility, current
     * balance, aur is month ka usage ek saath.
     */
    public function get_cl_tracker()
    {
        $this->db->select('e.emp_id, e.name, e.doj, e.emp_status, u.msd_id, cb.cl_balance, cb.last_accrual_month')
                  ->from('att_employees e')
                  ->join('master_users u', 'u.emp_id = e.emp_id')
                  ->join('att_cl_balance cb', 'cb.emp_id = e.emp_id', 'left')
                  ->where('e.tl_emp_id IS NOT NULL', null, false)
                  ->order_by('e.name', 'ASC');
        $rows = $this->db->get()->result();

        $month_start = date('Y-m-01');
        $month_end = date('Y-m-t');

        foreach($rows as $r)
        {
            $r->cl_balance = $r->cl_balance !== null ? (float)$r->cl_balance : 0;
            $r->eligible = $this->is_cl_eligible($r->emp_id) ? 'Yes' : 'No';

            $this->db->where('emp_id', $r->emp_id)->where('roster_type', 'CL')
                      ->where('roster_date >=', $month_start)->where('roster_date <=', $month_end);
            $r->cl_used_this_month = $this->db->count_all_results('att_roster_plan');
        }

        return $rows;
    }

    // ------------------------------------------------------------------
    // HOLIDAY / DAY-TYPE (existing `holidays` table + 2nd Saturday rule)
    // ------------------------------------------------------------------

    public function is_holiday($date)
    {
        $this->db->where('Holiday_date', $date);
        return $this->db->count_all_results('holidays') > 0;
    }

    private function is_second_saturday($date)
    {
        $ts = strtotime($date);
        if(date('N', $ts) != 6) return FALSE; // 6 = Saturday

        $day = (int)date('j', $ts);
        return ($day >= 8 && $day <= 14); // month ka doosra Saturday hamesha 8-14 ke beech aata hai
    }

    public function get_day_type($date)
    {
        if($this->is_holiday($date) || $this->is_second_saturday($date)) return 'Holiday';
        if(date('N', strtotime($date)) == 7) return 'Sunday'; // 7 = Sunday
        return 'Weekday';
    }

    // ------------------------------------------------------------------
    // ROSTER COMPLIANCE CHECK (actual roster vs policy targets, ek din ke liye)
    // ------------------------------------------------------------------

    public function check_roster_compliance($date)
    {
        $day_type = $this->get_day_type($date);
        $report = array('date' => $date, 'day_type' => $day_type, 'overall' => array(), 'shift_group' => array(), 'lob' => array());

        // --- OVERALL ---
        $this->db->where('day_type', $day_type);
        $target = $this->db->get('att_daily_target')->row();

        $total_headcount = $this->db->where('tl_emp_id IS NOT NULL', null, false)
                                    ->where("(emp_status = 'Active' OR (emp_status = 'Inactive' AND inactive_from IS NOT NULL AND inactive_from > ".$this->db->escape($date)."))", null, false)
                                    ->count_all_results('att_employees');
        $planned_present = $this->count_roster($date, 'SHIFT');
        $planned_leave = $this->count_roster($date, array('WO','CL','HD','LP'));

        if($target)
        {
            $report['overall'][] = array('label' => 'Total Headcount', 'target' => (int)$target->total_headcount, 'actual' => $total_headcount, 'ok' => $total_headcount == $target->total_headcount);
            $report['overall'][] = array('label' => 'Present (planned)', 'target' => (int)$target->total_present_target, 'actual' => $planned_present, 'ok' => $planned_present >= $target->total_present_target);
            $report['overall'][] = array('label' => 'Leave (WO+CL+HD+LP)', 'target' => (int)$target->max_leave_count, 'actual' => $planned_leave, 'ok' => $planned_leave <= $target->max_leave_count);
        }

        // --- SHIFT GROUP ---
        $this->db->where('day_type', $day_type);
        $shift_targets = $this->db->get('att_shift_group_target')->result();

        foreach($shift_targets as $st)
        {
            $this->db->select('COUNT(*) as cnt')->from('att_roster_plan rp')
                      ->join('att_employees e', 'e.emp_id = rp.emp_id')
                      ->where('rp.roster_date', $date)->where('rp.roster_type', 'SHIFT')
                      ->where('e.tl_emp_id IS NOT NULL', null, false)
                      ->where($this->sql_active_on('e', 'rp.roster_date'), null, false)
                      ->where('e.shift_group', $st->shift_group);

            if($st->shift_start !== null) $this->db->where('rp.shift_start', $st->shift_start);
            if($st->shift_end !== null) $this->db->where('rp.shift_end', $st->shift_end);

            $actual = (int)$this->db->get()->row()->cnt;
            $label = $st->shift_group.($st->shift_start ? ' ('.substr($st->shift_start,0,5).($st->shift_end ? '-'.substr($st->shift_end,0,5) : '+').')' : ' (Total)');
            $report['shift_group'][] = array('label' => $label, 'target' => (int)$st->target_agents, 'actual' => $actual, 'ok' => $actual == $st->target_agents);
        }

        // --- LOB ---
        $this->db->where('day_type', $day_type);
        $lob_targets = $this->db->get('att_lob_shift_target')->result();

        foreach($lob_targets as $lt)
        {
            $this->db->select('COUNT(*) as cnt')->from('att_roster_plan rp')
                      ->join('att_employees e', 'e.emp_id = rp.emp_id')
                      ->where('rp.roster_date', $date)->where('rp.roster_type', 'SHIFT')
                      ->where('e.tl_emp_id IS NOT NULL', null, false)
                      ->where($this->sql_active_on('e', 'rp.roster_date'), null, false)
                      ->where('e.lob', $lt->lob);

            if($lt->shift_start !== null) $this->db->where('rp.shift_start', $lt->shift_start);
            if($lt->shift_end !== null) $this->db->where('rp.shift_end', $lt->shift_end);

            $actual = (int)$this->db->get()->row()->cnt;
            $label = $lt->lob.($lt->shift_start ? ' ('.substr($lt->shift_start,0,5).'-'.substr($lt->shift_end,0,5).')' : ($lt->note ? ' ('.$lt->note.')' : ''));
            $report['lob'][] = array('label' => $label, 'target' => (int)$lt->target_agents, 'actual' => $actual, 'ok' => $actual == $lt->target_agents);
        }

        // Catch-all pool LOBs — koi fixed target nahi, jo bhi bacha wo yahan
        // aata hai. Sirf actual count info ke liye dikhate hain, kabhi red nahi hota.
        $catch_all_lobs = array('Inbound', 'Himseva manual Inbound', 'Himseva manual preview');
        $this->db->select('COUNT(*) as cnt')->from('att_roster_plan rp')
                  ->join('att_employees e', 'e.emp_id = rp.emp_id')
                  ->where('rp.roster_date', $date)->where('rp.roster_type', 'SHIFT')
                  ->where('e.tl_emp_id IS NOT NULL', null, false)
                  ->where($this->sql_active_on('e', 'rp.roster_date'), null, false)
                  ->where_in('e.lob', $catch_all_lobs);
        $catch_all_actual = (int)$this->db->get()->row()->cnt;
        $report['lob'][] = array('label' => 'Inbound / Himseva manual Inbound / Himseva manual preview (no cap)', 'target' => '-', 'actual' => $catch_all_actual, 'ok' => TRUE);

        return $report;
    }

    private function count_roster($date, $roster_types)
    {
        // TL (tl_emp_id NULL) kabhi count nahi hote — sirf agents
        $this->db->from('att_roster_plan rp')
                  ->join('att_employees e', 'e.emp_id = rp.emp_id')
                  ->where('e.tl_emp_id IS NOT NULL', null, false)
                  ->where($this->sql_active_on('e', 'rp.roster_date'), null, false)
                  ->where('rp.roster_date', $date);
        if(is_array($roster_types))
        {
            $this->db->where_in('rp.roster_type', $roster_types);
        }
        else
        {
            $this->db->where('rp.roster_type', $roster_types);
        }
        return $this->db->count_all_results();
    }

    // ------------------------------------------------------------------
    // AGENT SELF-SERVICE: apna roster dekhna (read-only)
    // ------------------------------------------------------------------

    public function get_employee_roster_month($emp_id, $month)
    {
        $start = $month.'-01';
        $end = date('Y-m-t', strtotime($start));

        $this->db->select('roster_date, roster_type, wo_type, roster_value, shift_start, shift_end')
                  ->from('att_roster_plan')
                  ->where('emp_id', $emp_id)
                  ->where('roster_date >=', $start)
                  ->where('roster_date <=', $end)
                  ->order_by('roster_date', 'ASC');
        return $this->db->get()->result();
    }

    // ------------------------------------------------------------------
    // ROSTER CHANGE REQUESTS (agent → admin approval workflow)
    // ------------------------------------------------------------------

    /**
     * Ek code (SHIFT:id / CUSTOM:.. / AWO/TWO/CL/HD/LP/CLEAR) ko
     * insaano ke padhne layak label me badalta hai — request list me
     * dikhane ke liye.
     */
    private function describe_code($code)
    {
        if(empty($code) || $code === 'CLEAR') return '- (Blank)';
        if($code === 'AWO') return 'Agent Choice WO';
        if($code === 'TWO') return 'TL Choice WO';
        if(in_array($code, array('CL','HD','LP'))) return $code;

        if(strpos($code, 'SHIFT:') === 0)
        {
            $shift_id = (int)substr($code, 6);
            $this->db->where('id', $shift_id);
            $shift = $this->db->get('att_shifts')->row();
            return $shift ? ($shift->shift_name.' ('.substr($shift->start_time,0,5).'-'.substr($shift->end_time,0,5).')') : 'Deleted preset';
        }
        if(strpos($code, 'CUSTOM:') === 0)
        {
            return 'Custom '.substr($code, 7);
        }
        return $code;
    }

    /**
     * Us din ka roster abhi kya hai, uske adhar par current_code snapshot
     * banata hai — request submit karte waqt "pehle kya tha" record ho.
     */
    private function get_current_roster_code($emp_id, $date)
    {
        $this->db->where('emp_id', $emp_id)->where('roster_date', $date);
        $existing = $this->db->get('att_roster_plan')->row();
        if(!$existing) return 'CLEAR';

        if($existing->roster_type == 'SHIFT')
        {
            $this->db->where('start_time', $existing->shift_start)->where('end_time', $existing->shift_end);
            $preset = $this->db->get('att_shifts')->row();
            return $preset ? ('SHIFT:'.$preset->id) : ('CUSTOM:'.substr($existing->shift_start,0,5).'-'.substr($existing->shift_end,0,5));
        }
        if($existing->roster_type == 'WO')
        {
            return ($existing->wo_type == 'TL') ? 'TWO' : 'AWO';
        }
        return $existing->roster_type; // CL / HD / LP
    }

    public function create_roster_change_request($emp_id, $date, $requested_code, $agent_remark)
    {
        $current_code = $this->get_current_roster_code($emp_id, $date);

        $this->db->insert('att_roster_change_request', array(
            'emp_id' => $emp_id,
            'request_date' => $date,
            'current_code' => $current_code,
            'requested_code' => $requested_code,
            'agent_remark' => $agent_remark,
            'status' => 'Pending'
        ));
        return $this->db->insert_id();
    }

    public function get_my_roster_requests($emp_id)
    {
        $this->db->select('rcr.*, u.user_name as reviewed_by_name')
                  ->from('att_roster_change_request rcr')
                  ->join('master_users u', 'u.emp_id = rcr.reviewed_by', 'left')
                  ->where('rcr.emp_id', $emp_id)
                  ->order_by('rcr.created_at', 'DESC');
        $rows = $this->db->get()->result();
        foreach($rows as $r)
        {
            $r->current_label = $this->describe_code($r->current_code);
            $r->requested_label = $this->describe_code($r->requested_code);
        }
        return $rows;
    }

    public function get_all_roster_requests($status = null)
    {
        $this->db->select('rcr.*, e.name, u.msd_id, ru.user_name as reviewed_by_name')
                  ->from('att_roster_change_request rcr')
                  ->join('att_employees e', 'e.emp_id = rcr.emp_id')
                  ->join('master_users u', 'u.emp_id = rcr.emp_id')
                  ->join('master_users ru', 'ru.emp_id = rcr.reviewed_by', 'left')
                  ->order_by('rcr.status', 'ASC') // Pending pehle (P < A/R alphabetically... explicit order neeche)
                  ->order_by('rcr.created_at', 'DESC');

        if($status) $this->db->where('rcr.status', $status);

        $rows = $this->db->get()->result();
        foreach($rows as $r)
        {
            $r->current_label = $this->describe_code($r->current_code);
            $r->requested_label = $this->describe_code($r->requested_code);
        }

        // Pending sabse upar dikhane ke liye reorder (Query builder me CASE order easy nahi, isliye PHP me)
        usort($rows, function($a, $b){
            if($a->status == $b->status) return strtotime($b->created_at) - strtotime($a->created_at);
            return ($a->status == 'Pending') ? -1 : (($b->status == 'Pending') ? 1 : 0);
        });

        return $rows;
    }

    private function is_leave_code($code)
    {
        return in_array($code, array('AWO','TWO','CL','HD','LP'));
    }

    /**
     * Weekday/Sunday/Holiday ke max_leave_count cap ke against check karta
     * hai — sirf tab jab change se ek NAYA leave (WO/CL/HD/LP) add ho raha
     * ho (Present -> Leave). Leave -> Present hamesha allowed hai (isse
     * headcount improve hi hota hai).
     */
    public function check_capacity_for_leave_change($emp_id, $date, $new_code)
    {
        $current_code = $this->get_current_roster_code($emp_id, $date);
        $was_leave = $this->is_leave_code($current_code);
        $will_be_leave = $this->is_leave_code($new_code);

        if($will_be_leave && !$was_leave)
        {
            $day_type = $this->get_day_type($date);
            $this->db->where('day_type', $day_type);
            $target = $this->db->get('att_daily_target')->row();
            $current_leave_count = $this->count_roster($date, array('WO','CL','HD','LP'));

            if($target && ($current_leave_count + 1) > $target->max_leave_count)
            {
                return array(
                    'ok' => FALSE,
                    'message' => "Is din ({$day_type}) capacity full hai — already {$current_leave_count}/{$target->max_leave_count} leave/WO/CL diye ja chuke hain."
                );
            }
        }

        return array('ok' => TRUE, 'message' => '');
    }

    public function approve_roster_request($request_id, $admin_emp_id, $admin_remark, $force = FALSE)
    {
        $this->db->where('id', $request_id);
        $req = $this->db->get('att_roster_change_request')->row();

        if(!$req) return array('ok' => FALSE, 'message' => 'Request nahi mili.');
        if($req->status != 'Pending') return array('ok' => FALSE, 'message' => 'Ye request pehle hi '.$req->status.' ho chuki hai.');

        if(!$force)
        {
            $cap_check = $this->check_capacity_for_leave_change($req->emp_id, $req->request_date, $req->requested_code);
            if(!$cap_check['ok']) return $cap_check;
        }

        // apply_roster_value apne aap WO-cap / CL-balance bhi check karega
        $apply_result = $this->apply_roster_value($req->emp_id, $req->request_date, $req->requested_code);
        if(!$apply_result['ok']) return $apply_result;

        $this->db->where('id', $request_id)->update('att_roster_change_request', array(
            'status' => 'Approved',
            'admin_remark' => $admin_remark,
            'reviewed_by' => $admin_emp_id,
            'reviewed_at' => date('Y-m-d H:i:s')
        ));

        return array('ok' => TRUE, 'message' => 'Approved aur roster update ho gaya.');
    }

    public function reject_roster_request($request_id, $admin_emp_id, $admin_remark)
    {
        $this->db->where('id', $request_id);
        $req = $this->db->get('att_roster_change_request')->row();

        if(!$req) return array('ok' => FALSE, 'message' => 'Request nahi mili.');
        if($req->status != 'Pending') return array('ok' => FALSE, 'message' => 'Ye request pehle hi '.$req->status.' ho chuki hai.');

        $this->db->where('id', $request_id)->update('att_roster_change_request', array(
            'status' => 'Rejected',
            'admin_remark' => $admin_remark,
            'reviewed_by' => $admin_emp_id,
            'reviewed_at' => date('Y-m-d H:i:s')
        ));

        return array('ok' => TRUE, 'message' => 'Request reject kar di gayi.');
    }

    // ------------------------------------------------------------------
    // WO / CL PREFERENCE COLLECTION (roster banne se pehle, planning ke liye)
    // ------------------------------------------------------------------

    /**
     * Agent ki is mahine ke liye submission save karta hai — resubmit karne
     * par purani entries replace ho jaati hain (overwrite), reference/
     * planning data hai, isse roster khud nahi banta.
     */
    public function save_wo_cl_preferences($emp_id, $for_month, $wo_dates, $cl_dates, $remark)
    {
        $this->db->where('emp_id', $emp_id)->where('for_month', $for_month)->delete('att_wo_cl_preference');

        $priority = 1;
        foreach($wo_dates as $d)
        {
            $d = trim($d);
            if($d == '') continue;
            $this->db->insert('att_wo_cl_preference', array(
                'emp_id' => $emp_id, 'for_month' => $for_month, 'pref_type' => 'WO',
                'pref_date' => $d, 'priority' => $priority, 'remark' => $remark
            ));
            $priority++;
        }

        foreach($cl_dates as $d)
        {
            $d = trim($d);
            if($d == '') continue;
            $this->db->insert('att_wo_cl_preference', array(
                'emp_id' => $emp_id, 'for_month' => $for_month, 'pref_type' => 'CL',
                'pref_date' => $d, 'priority' => null, 'remark' => $remark
            ));
        }

        return TRUE;
    }

    public function get_my_wo_cl_preferences($emp_id, $for_month)
    {
        $this->db->where('emp_id', $emp_id)->where('for_month', $for_month)
                  ->order_by('pref_type', 'ASC')->order_by('priority', 'ASC');
        return $this->db->get('att_wo_cl_preference')->result();
    }

    /**
     * Admin ke liye consolidated view — mahine ki har date pe kitne agents
     * WO/CL maang rahe hain, us din ka leave-cap (att_daily_target) ke
     * saath compare karke "over cap" flag bhi deta hai, aur har date ke
     * liye agents ki poori list (naam, TL, LOB) bhi deta hai.
     */
    public function get_preference_overview($for_month)
    {
        $this->db->select('p.pref_date, p.pref_type, p.priority, p.remark, e.name, u.msd_id, e.lob, t.name as tl_name')
                  ->from('att_wo_cl_preference p')
                  ->join('att_employees e', 'e.emp_id = p.emp_id')
                  ->join('master_users u', 'u.emp_id = p.emp_id')
                  ->join('att_employees t', 't.emp_id = e.tl_emp_id', 'left')
                  ->where('p.for_month', $for_month)
                  ->order_by('p.pref_date', 'ASC');
        $rows = $this->db->get()->result();

        $by_date = array();
        foreach($rows as $r)
        {
            if(!isset($by_date[$r->pref_date]))
            {
                $by_date[$r->pref_date] = array('WO' => array(), 'CL' => array());
            }
            $by_date[$r->pref_date][$r->pref_type][] = array(
                'name' => $r->name, 'msd_id' => $r->msd_id, 'lob' => $r->lob,
                'tl_name' => $r->tl_name, 'priority' => $r->priority, 'remark' => $r->remark
            );
        }

        $result = array();
        foreach($by_date as $date => $types)
        {
            $day_type = $this->get_day_type($date);
            $this->db->where('day_type', $day_type);
            $target = $this->db->get('att_daily_target')->row();

            $wo_count = count($types['WO']);
            $cl_count = count($types['CL']);
            $cap = $target ? (int)$target->max_leave_count : null;

            $result[] = array(
                'date' => $date,
                'day_type' => $day_type,
                'wo_requested' => $wo_count,
                'cl_requested' => $cl_count,
                'total_requested' => $wo_count + $cl_count,
                'max_leave_cap' => $cap,
                'over_cap' => ($cap !== null) ? (($wo_count + $cl_count) > $cap) : FALSE,
                'wo_agents' => $types['WO'],
                'cl_agents' => $types['CL']
            );
        }

        usort($result, function($a, $b){ return strcmp($a['date'], $b['date']); });
        return $result;
    }

    private function resolve_roster_code($code)
    {
        if($code === 'AWO') return array('roster_type'=>'WO','wo_type'=>'Agent','roster_value'=>'Agent Choice WO','shift_start'=>null,'shift_end'=>null,'login_target_minutes'=>null);
        if($code === 'TWO') return array('roster_type'=>'WO','wo_type'=>'TL','roster_value'=>'TL Choice WO','shift_start'=>null,'shift_end'=>null,'login_target_minutes'=>null);
        if($code === 'CL')  return array('roster_type'=>'CL','wo_type'=>null,'roster_value'=>'CL','shift_start'=>null,'shift_end'=>null,'login_target_minutes'=>null);
        if($code === 'HD')  return array('roster_type'=>'HD','wo_type'=>null,'roster_value'=>'HD','shift_start'=>null,'shift_end'=>null,'login_target_minutes'=>null);
        if($code === 'LP')  return array('roster_type'=>'LP','wo_type'=>null,'roster_value'=>'LP','shift_start'=>null,'shift_end'=>null,'login_target_minutes'=>null);

        if(strpos($code, 'SHIFT:') === 0)
        {
            $shift_id = (int)substr($code, 6);
            $this->db->where('id', $shift_id);
            $shift = $this->db->get('att_shifts')->row();
            if(!$shift) return FALSE;

            return array(
                'roster_type' => 'SHIFT',
                'wo_type' => null,
                'roster_value' => substr($shift->start_time, 0, 5).'-'.substr($shift->end_time, 0, 5),
                'shift_start' => $shift->start_time,
                'shift_end' => $shift->end_time,
                'login_target_minutes' => $shift->login_target_minutes
            );
        }

        if(strpos($code, 'CUSTOM:') === 0)
        {
            $range = substr($code, 7); // 'HH:MM-HH:MM'
            $result = $this->classify_roster_cell($range);
            if($result === FALSE) return FALSE;
            $result['roster_value'] = $range;
            return $result;
        }

        return FALSE;
    }

    private function hhmm_to_minutes($hhmm)
    {
        if(preg_match('/^(\d{1,2}):(\d{2})$/', trim($hhmm), $m))
        {
            return ((int)$m[1] * 60) + (int)$m[2];
        }
        return 0;
    }

    // ------------------------------------------------------------------
    // CORE STATUS COMPUTATION (Excel column V formula ka equivalent)
    // ------------------------------------------------------------------

    const DEFAULT_LOGIN_TARGET_MINUTES = 415; // 7 hr — jo campaign list me nahi hai unke liye

    public function recompute_attendance_daily($emp_id, $date)
    {
        // employee master
        $this->db->select('emp_status, employment_type, inactive_from')->from('att_employees')->where('emp_id', $emp_id);
        $emp = $this->db->get()->row();
        if(!$emp) return FALSE;

        // roster — sirf WO/CL/LP/HD wale din ke liye status force karta hai
        $this->db->select('roster_value, roster_type, wo_type, shift_start')
                  ->from('att_roster_plan')->where('emp_id', $emp_id)->where('roster_date', $date);
        $roster = $this->db->get()->row();

        // net login + exception
        $this->db->select('net_login_minutes')->from('att_attendance_daily')->where('emp_id', $emp_id)->where('attendance_date', $date);
        $att = $this->db->get()->row();
        $net_login_minutes = $att ? (float)$att->net_login_minutes : 0;

        $this->db->select('exception_minutes')->from('att_exceptions')->where('emp_id', $emp_id)->where('exception_date', $date);
        $exc = $this->db->get()->row();
        $exception_minutes = $exc ? (int)$exc->exception_minutes : 0;

        $total_login_minutes = $net_login_minutes + $exception_minutes;

        // Us din ka dominant campaign (Raw Report se) aur usi ke hisaab se target
        $dominant_campaign = $this->get_dominant_campaign($emp_id, $date);
        $campaign_key = $this->match_campaign_key($dominant_campaign, $roster);
        $day_type = $this->get_day_type($date);
        $applied_target = $campaign_key ? $this->resolve_campaign_target($campaign_key, $day_type) : self::DEFAULT_LOGIN_TARGET_MINUTES;

        $final_status = $this->compute_final_status($emp, $date, $roster, $total_login_minutes, $applied_target);
        $weight = ($emp->employment_type == 'PT') ? 0.5 : 1.0;

        $data = array(
            'exception_minutes' => $exception_minutes,
            'total_login_minutes' => $total_login_minutes,
            'roster_value' => $roster ? $roster->roster_value : '',
            'roster_type' => $roster ? $roster->roster_type : null,
            'wo_type' => ($roster && isset($roster->wo_type)) ? $roster->wo_type : null,
            'applied_campaign' => $dominant_campaign,
            'applied_target_minutes' => $applied_target,
            'final_status' => $final_status,
            'weight' => $weight
        );

        $this->db->where('emp_id', $emp_id)->where('attendance_date', $date);
        $exists = $this->db->get('att_attendance_daily')->row();

        if($exists)
        {
            return $this->db->where('id', $exists->id)->update('att_attendance_daily', $data);
        }
        $data['emp_id'] = $emp_id;
        $data['attendance_date'] = $date;
        return $this->db->insert('att_attendance_daily', $data);
    }

    /** Us din agent ne jis campaign par sabse zyada net-login diya, wahi 'dominant' campaign maana jata hai */
    private function get_dominant_campaign($emp_id, $date)
    {
        $this->db->select('rcl.campaign')
                  ->from('att_raw_call_logs rcl')
                  ->join('master_users u', 'u.msd_id = rcl.agent_username')
                  ->where('u.emp_id', $emp_id)
                  ->where('rcl.log_date', $date)
                  ->order_by('rcl.net_login_duration_minutes', 'DESC')
                  ->limit(1);
        $row = $this->db->get()->row();
        return $row ? $row->campaign : null;
    }

    /**
     * Raw Report ki campaign string ko att_campaign_login_target ki key se
     * match karta hai (spaces/underscore hata ke, lowercase, contains-check).
     * 'Night Shift' sirf tab match hota hai jab campaign HRTC/Disaster ho
     * AUR roster shift 22:00 se start hoti ho (raw data me literal 'night
     * shift' naam nahi aata, ye waqt + campaign dono se pehchana jata hai).
     */
    private function match_campaign_key($raw_campaign, $roster)
    {
        if(empty($raw_campaign)) return null;
        $norm = strtolower(preg_replace('/[^a-z0-9]/i', '', $raw_campaign));

        if(strpos($norm, 'foodsupply') !== false || strpos($norm, 'fcs') !== false) return 'FCS';
        if(strpos($norm, 'officerhelpdesk') !== false) return 'Officer Helpdesk';
        if(strpos($norm, 'ddtg') !== false) return 'DDTG_Helpline';

        $is_hrtc_or_disaster = (strpos($norm, 'hrtc') !== false || strpos($norm, 'disaster') !== false);
        if($is_hrtc_or_disaster && $roster && !empty($roster->shift_start) && substr($roster->shift_start, 0, 2) == '22')
        {
            return 'Night Shift';
        }

        return null; // list me nahi — default 7hr fallback lagega
    }

    private function resolve_campaign_target($campaign_key, $day_type)
    {
        $this->db->where('campaign_key', $campaign_key)
                  ->where("(day_type = {$this->db->escape($day_type)} OR day_type IS NULL)", null, false)
                  ->order_by("(day_type IS NULL)", "ASC", false)
                  ->limit(1);
        $row = $this->db->get('att_campaign_login_target')->row();
        return $row ? (int)$row->target_minutes : self::DEFAULT_LOGIN_TARGET_MINUTES;
    }

    // Status logic:
    //   Left / Inactive   -> forced
    //   roster_type WO/CL/LP/HD -> roster jaisa hi bana rehta hai (login count check nahi hota)
    //   baaki sab din -> $target (campaign-based, upar resolve hua) ke against P/HD/AA
    private function compute_final_status($emp, $date, $roster, $total_login_minutes, $target)
    {
        if($this->is_left_on($emp, $date)) return 'Left';

        $roster_type = $roster ? $roster->roster_type : null;

        if($roster_type == 'WO') return 'WO';
        if($roster_type == 'CL') return 'CL';
        if($roster_type == 'LP') return 'Leave';
        if($roster_type == 'HD') return 'HD';

        $half_target = $target / 2;

        if($total_login_minutes >= $half_target && $total_login_minutes < $target) return 'HD';
        if($total_login_minutes > $target) return 'P';
        return 'AA';
    }

    // ------------------------------------------------------------------
    // ATTENDANCE MATRIX (Agent x Date grid — Excel "Attendance" sheet equivalent)
    // ------------------------------------------------------------------

    public function get_attendance_matrix($month, $filter_emp_id = null, $lob = null, $tl_id = null)
    {
        $start = $month.'-01';
        $end = date('Y-m-t', strtotime($start));

        $this->db->select('e.emp_id, e.name, e.lob, e.emp_status, e.inactive_from, u.msd_id, t.name as tl_name')
                  ->from('att_employees e')
                  ->join('master_users u', 'u.emp_id = e.emp_id')
                  ->join('att_employees t', 't.emp_id = e.tl_emp_id', 'left')
                  ->where('e.tl_emp_id IS NOT NULL', null, false) // TL matrix me nahi
                  ->where($this->sql_active_on('e', $this->db->escape($start)), null, false) // pichle mahine left ho chuke ho to nahi
                  ->order_by('e.name', 'ASC');

        if($filter_emp_id)
        {
            $this->db->where('e.emp_id', $filter_emp_id);
        }
        if($lob)
        {
            $this->db->where('e.lob', $lob);
        }
        if($tl_id)
        {
            $this->db->where('e.tl_emp_id', $tl_id);
        }

        $employees = $this->db->get()->result();

        if(empty($employees))
        {
            return array('rows' => array(), 'date_columns' => array());
        }

        $emp_ids = array_map(function($e){ return $e->emp_id; }, $employees);

        $this->db->select('emp_id, attendance_date, final_status')
                  ->from('att_attendance_daily')
                  ->where_in('emp_id', $emp_ids)
                  ->where('attendance_date >=', $start)
                  ->where('attendance_date <=', $end);
        $daily_rows = $this->db->get()->result();

        $status_map = array();
        foreach($daily_rows as $d)
        {
            $status_map[$d->emp_id][$d->attendance_date] = $d->final_status;
        }

        $date_columns = array();
        $cursor = strtotime($start);
        $end_ts = strtotime($end);
        while($cursor <= $end_ts)
        {
            $date_columns[] = date('Y-m-d', $cursor);
            $cursor = strtotime('+1 day', $cursor);
        }

        $rows = array();
        foreach($employees as $e)
        {
            $row = array(
                'emp_id' => $e->emp_id,
                'name' => $e->name,
                'msd_id' => $e->msd_id,
                'lob' => $e->lob,
                'tl_name' => $e->tl_name,
                'days' => array(),
                'present_count' => 0,
                'absent_count' => 0,
                'hd_count' => 0,
                'wo_count' => 0
            );
            foreach($date_columns as $d)
            {
                $status = isset($status_map[$e->emp_id][$d]) ? $status_map[$e->emp_id][$d] : '-';
                if($this->is_left_on($e, $d)) $status = 'Left';
                $row['days'][$d] = $status;
                if($status == 'P') $row['present_count']++;
                if($status == 'AA') $row['absent_count']++;
                if($status == 'HD') $row['hd_count'] += 0.5;
                if($status == 'WO') $row['wo_count']++;
            }
            $rows[] = $row;
        }

        return array('rows' => $rows, 'date_columns' => $date_columns);
    }

    // ------------------------------------------------------------------
    // TL WISE SHRINKAGE (Excel "TL Wise Shrinkage1" pivot equivalent)
    // Shrinkage% (per TL, per date) = (HD+AA+Leave weighted) / Roster(=manpower)
    // ------------------------------------------------------------------

    public function get_tl_wise_shrinkage($month)
    {
        $start = $month.'-01';
        $end = date('Y-m-t', strtotime($start));

        $sql = "SELECT
                    t.name AS tl_name,
                    ad.attendance_date,
                    SUM(ad.weight) AS headcount,
                    SUM(CASE WHEN ad.final_status IN ('AA','Leave') THEN ad.weight WHEN ad.final_status = 'HD' THEN ad.weight * 0.5 ELSE 0 END) AS shrink_weight
                FROM att_attendance_daily ad
                JOIN att_employees e ON e.emp_id = ad.emp_id
                LEFT JOIN att_employees t ON t.emp_id = e.tl_emp_id
                WHERE ad.attendance_date BETWEEN ? AND ?
                  AND e.tl_emp_id IS NOT NULL
                  AND {$this->sql_active_on('e','ad.attendance_date')}
                GROUP BY t.name, ad.attendance_date
                ORDER BY t.name, ad.attendance_date";

        $result = $this->db->query($sql, array($start, $end))->result();

        $pivot = array();
        foreach($result as $r)
        {
            $tl = $r->tl_name ?: 'Unassigned';
            if(!isset($pivot[$tl]))
            {
                $pivot[$tl] = array('tl_name' => $tl, 'dates' => array());
            }
            $shrinkage_pct = ($r->headcount > 0) ? round(($r->shrink_weight / $r->headcount), 4) : 0;
            $pivot[$tl]['dates'][$r->attendance_date] = $shrinkage_pct;
        }

        return array_values($pivot);
    }

    // ------------------------------------------------------------------
    // SHRINKAGE OVERVIEW (Excel "Shrinkage Overview" pivots equivalent)
    // group_by: 'lob' | 'tl' | 'shift'
    // Metrics: HC, Roster, WO, P, HD, AB, Leave, WO%, UPL%(=Shrinkage%)
    // ------------------------------------------------------------------

    public function get_shrinkage_overview($group_by = 'lob', $date_from = null, $date_to = null)
    {
        $group_col = "e.lob";
        $join_extra = "";
        if($group_by == 'tl')
        {
            $group_col = "t.name";
            $join_extra = "LEFT JOIN att_employees t ON t.emp_id = e.tl_emp_id";
        }
        else if($group_by == 'shift')
        {
            $group_col = "e.shift_group";
        }

        $sql = "SELECT
                    {$group_col} AS group_label,
                    SUM(ad.weight) AS hc,
                    SUM(CASE WHEN ad.final_status = 'WO' THEN ad.weight ELSE 0 END) AS wo,
                    SUM(CASE WHEN ad.final_status = 'P' THEN ad.weight ELSE 0 END) AS p_count,
                    SUM(CASE WHEN ad.final_status = 'HD' THEN ad.weight * 0.5 ELSE 0 END) AS hd_count,
                    SUM(CASE WHEN ad.final_status = 'AA' THEN ad.weight ELSE 0 END) AS ab_count,
                    SUM(CASE WHEN ad.final_status = 'Leave' THEN ad.weight ELSE 0 END) AS leave_count
                FROM att_attendance_daily ad
                JOIN att_employees e ON e.emp_id = ad.emp_id
                {$join_extra}
                WHERE ad.attendance_date BETWEEN ? AND ?
                  AND e.tl_emp_id IS NOT NULL
                  AND {$this->sql_active_on('e','ad.attendance_date')}
                GROUP BY {$group_col}
                ORDER BY {$group_col}";

        $result = $this->db->query($sql, array($date_from, $date_to))->result();

        $rows = array();
        foreach($result as $r)
        {
            $roster = $r->hc; // is dataset me Roster == HC (Excel me bhi hamesha equal dekha gaya)
            $wo_pct = ($roster > 0) ? round($r->wo / $roster, 4) : 0;
            $shrinkage_pct = ($roster > 0) ? round(($r->hd_count + $r->ab_count + $r->leave_count) / $roster, 4) : 0;

            $rows[] = array(
                'group_label' => $r->group_label ?: 'Unassigned',
                'hc' => (float)$r->hc,
                'roster' => (float)$roster,
                'wo' => (float)$r->wo,
                'p' => (float)$r->p_count,
                'hd' => (float)$r->hd_count,
                'ab' => (float)$r->ab_count,
                'leave' => (float)$r->leave_count,
                'wo_percent' => $wo_pct,
                'upl_percent' => $shrinkage_pct,
                'shrinkage_percent' => round($wo_pct + $shrinkage_pct, 4)
            );
        }

        return $rows;
    }

    public function get_daily_shrinkage_trend($date_from, $date_to)
    {
        $sql = "SELECT
                    ad.attendance_date,
                    SUM(ad.weight) AS hc,
                    SUM(CASE WHEN ad.final_status = 'WO' THEN ad.weight ELSE 0 END) AS wo,
                    SUM(CASE WHEN ad.final_status = 'HD' THEN ad.weight * 0.5 ELSE 0 END) AS hd_count,
                    SUM(CASE WHEN ad.final_status = 'AA' THEN ad.weight ELSE 0 END) AS ab_count,
                    SUM(CASE WHEN ad.final_status = 'Leave' THEN ad.weight ELSE 0 END) AS leave_count
                FROM att_attendance_daily ad
                JOIN att_employees e ON e.emp_id = ad.emp_id
                WHERE ad.attendance_date BETWEEN ? AND ?
                  AND e.tl_emp_id IS NOT NULL
                  AND {$this->sql_active_on('e','ad.attendance_date')}
                GROUP BY ad.attendance_date
                ORDER BY ad.attendance_date";

        $result = $this->db->query($sql, array($date_from, $date_to))->result();

        $rows = array();
        foreach($result as $r)
        {
            $shrinkage_pct = ($r->hc > 0) ? round(($r->wo + $r->hd_count + $r->ab_count + $r->leave_count) / $r->hc, 4) : 0;
            $rows[] = array(
                'date' => $r->attendance_date,
                'hc' => (float)$r->hc,
                'shrinkage_percent' => $shrinkage_pct
            );
        }
        return $rows;
    }

    // ------------------------------------------------------------------
    // HELPERS
    // ------------------------------------------------------------------

    private function parse_ddmmyyyy($value, $allow_excel_date = FALSE)
    {
        $value = trim((string)$value);
        if($value == '') return FALSE;

        // '01-09-2026'
        if(preg_match('/^(\d{2})-(\d{2})-(\d{4})$/', $value, $m))
        {
            return "{$m[3]}-{$m[2]}-{$m[1]}";
        }
        // already 'Y-m-d'
        if(preg_match('/^\d{4}-\d{2}-\d{2}$/', $value))
        {
            return $value;
        }
        // FALLBACK: agar library style-detect na kar payi aur raw Excel
        // date serial (integer, plausible range ~1954-2064) aa gaya ho
        if(is_numeric($value) && (float)$value >= 20000 && (float)$value <= 60000)
        {
            $base_ts = -2209161600; // 1899-12-30 00:00:00 UTC
            $ts = $base_ts + round((float)$value * 86400);
            return gmdate('Y-m-d', $ts);
        }
        if($allow_excel_date)
        {
            $ts = strtotime($value);
            if($ts !== FALSE) return date('Y-m-d', $ts);
        }
        return FALSE;
    }

    private function time_str_to_minutes($value)
    {
        if($value === null || $value === '') return 0;
        $value = trim((string)$value);

        // 'H:i:s' format (library ne already convert kar diya ho)
        if(preg_match('/^(\d{1,2}):(\d{2}):(\d{2})$/', $value, $m))
        {
            return round((int)$m[1] * 60 + (int)$m[2] + ((int)$m[3] / 60), 2);
        }

        // FALLBACK: agar library style-detect na kar payi aur raw Excel
        // serial fraction (0 se 1 ke beech, poore din ka fraction) aa gaya —
        // isko bhi time-of-day maan kar minutes me convert karo.
        if(is_numeric($value))
        {
            $num = (float)$value;
            if($num > 0 && $num < 1)
            {
                return round($num * 1440, 2); // 1440 minutes in a day
            }
            // agar already plain minutes ke roop me diya gaya ho
            return $num;
        }

        return 0;
    }

}