<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance extends CI_Controller {

    // role_id = 1 => Admin/TL (full access)
    // role_id > 1 => Agent (apna data hi)
    private $view_roles      = array(1, 2, 3, 4, 5); // sab is module ko access kar sakte hain
    private $admin_roles     = array(1);             // upload / exception add-update sirf admin
    private $tl_view_roles   = array(1);             // TL-wise / Overview sirf admin ko

    public function __construct(){
        parent::__construct();
        if(!isset($_SESSION['userdata']) || $_SESSION['userdata']['user_logged_status'] != TRUE)
        {
            redirect('app/logout');
        }
        if(!$this->is_allowed($this->view_roles))
        {
            show_error('You are not authorized to access attendance module.', 403);
        }
        $this->load->model("Attendance_model");
    }

    private function is_allowed($roles)
    {
        return isset($_SESSION["userdata"]["role_id"]) && in_array((int)$_SESSION["userdata"]["role_id"], $roles);
    }

    private function deny_json($message = "You are not authorized to perform this action.")
    {
        echo json_encode(array("response" => FALSE, "message" => $message));
        return FALSE;
    }

    private function is_admin()
    {
        return $this->is_allowed($this->admin_roles);
    }

    private function current_role_id()
    {
        return (int)$_SESSION["userdata"]["role_id"];
    }

    private function current_emp_id()
    {
        return (int)$_SESSION["userdata"]["emp_id"];
    }

    // ------------------------------------------------------------------
    // PAGE LOADERS
    // ------------------------------------------------------------------

    public function index($page = "dashboard")
    {
        $data = array();
        if(!file_exists(APPPATH.'views/attendance/'.$page.'.php'))
        {
            show_404();
        }

        if(!$this->is_allowed($this->tl_view_roles))
        {
            // agent seedha apni matrix pe bhej do, overview nahi dikhana
            redirect('app/attendance/matrix');
        }

        $data["lob_list"]   = $this->Attendance_model->get_distinct_lob();
        $data["tl_list"]    = $this->Attendance_model->get_all_tl_list();
        $data["title"]      = "Shrinkage Overview";
        $this->load->view('app/templates/header', $data);
        $this->load->view('app/templates/side_panel', $data);
        $this->load->view('attendance/'.$page, $data);
        $this->load->view('app/templates/footer', $data);
    }

    public function upload_center($page = "upload")
    {
        if(!$this->is_admin())
        {
            show_error('You are not authorized to access this page.', 403);
        }
        $data = array();
        if(!file_exists(APPPATH.'views/attendance/'.$page.'.php'))
        {
            show_404();
        }
        $data["recent_uploads"] = $this->Attendance_model->get_recent_uploads(15);
        $data["title"] = "Attendance - Upload Center";
        $this->load->view('app/templates/header', $data);
        $this->load->view('app/templates/side_panel', $data);
        $this->load->view('attendance/'.$page, $data);
        $this->load->view('app/templates/footer', $data);
    }

    public function matrix($page = "matrix")
    {
        $data = array();
        if(!file_exists(APPPATH.'views/attendance/'.$page.'.php'))
        {
            show_404();
        }
        $data["lob_list"] = $this->Attendance_model->get_distinct_lob();
        $data["tl_list"]  = $this->Attendance_model->get_all_tl_list();
        $data["title"]    = "Attendance Matrix";
        $this->load->view('app/templates/header', $data);
        $this->load->view('app/templates/side_panel', $data);
        $this->load->view('attendance/'.$page, $data);
        $this->load->view('app/templates/footer', $data);
    }

    public function tl_wise($page = "tl_wise")
    {
        if(!$this->is_allowed($this->tl_view_roles))
        {
            show_error('You are not authorized to access this report.', 403);
        }
        $data = array();
        if(!file_exists(APPPATH.'views/attendance/'.$page.'.php'))
        {
            show_404();
        }
        $data["title"] = "TL Wise Shrinkage";
        $this->load->view('app/templates/header', $data);
        $this->load->view('app/templates/side_panel', $data);
        $this->load->view('attendance/'.$page, $data);
        $this->load->view('app/templates/footer', $data);
    }

    public function exceptions($page = "exceptions")
    {
        if(!$this->is_admin())
        {
            show_error('You are not authorized to access this page.', 403);
        }
        $data = array();
        if(!file_exists(APPPATH.'views/attendance/'.$page.'.php'))
        {
            show_404();
        }
        $data["user_list"] = $this->Attendance_model->get_all_att_employees();
        $data["title"] = "Attendance Exceptions";
        $this->load->view('app/templates/header', $data);
        $this->load->view('app/templates/side_panel', $data);
        $this->load->view('attendance/'.$page, $data);
        $this->load->view('app/templates/footer', $data);
    }

    public function roster_builder($page = "roster_builder")
    {
        if(!$this->is_admin())
        {
            show_error('You are not authorized to access this page.', 403);
        }
        $data = array();
        if(!file_exists(APPPATH.'views/attendance/'.$page.'.php'))
        {
            show_404();
        }
        $data["lob_list"] = $this->Attendance_model->get_distinct_lob();
        $data["tl_list"]  = $this->Attendance_model->get_all_tl_list();
        $data["shift_presets"] = $this->Attendance_model->get_shift_presets();
        $data["title"] = "Roster Builder";
        $this->load->view('app/templates/header', $data);
        $this->load->view('app/templates/side_panel', $data);
        $this->load->view('attendance/'.$page, $data);
        $this->load->view('app/templates/footer', $data);
    }

    public function employees($page = "employees")
    {
        if(!$this->is_admin())
        {
            show_error('You are not authorized to access this page.', 403);
        }
        $data = array();
        if(!file_exists(APPPATH.'views/attendance/'.$page.'.php'))
        {
            show_404();
        }
        $data["tl_list"] = $this->Attendance_model->get_all_employees_for_tl_dropdown();
        $data["title"] = "Attendance - Employees";
        $this->load->view('app/templates/header', $data);
        $this->load->view('app/templates/side_panel', $data);
        $this->load->view('attendance/'.$page, $data);
        $this->load->view('app/templates/footer', $data);
    }

    public function cl_tracker($page = "cl_tracker")
    {
        if(!$this->is_admin())
        {
            show_error('You are not authorized to access this page.', 403);
        }
        $data = array();
        if(!file_exists(APPPATH.'views/attendance/'.$page.'.php'))
        {
            show_404();
        }
        $data["title"] = "CL Tracker";
        $this->load->view('app/templates/header', $data);
        $this->load->view('app/templates/side_panel', $data);
        $this->load->view('attendance/'.$page, $data);
        $this->load->view('app/templates/footer', $data);
    }

    public function roster_compliance($page = "roster_compliance")
    {
        if(!$this->is_admin())
        {
            show_error('You are not authorized to access this page.', 403);
        }
        $data = array();
        if(!file_exists(APPPATH.'views/attendance/'.$page.'.php'))
        {
            show_404();
        }
        $data["title"] = "Roster Compliance";
        $this->load->view('app/templates/header', $data);
        $this->load->view('app/templates/side_panel', $data);
        $this->load->view('attendance/'.$page, $data);
        $this->load->view('app/templates/footer', $data);
    }

    public function my_roster($page = "my_roster")
    {
        $data = array();
        if(!file_exists(APPPATH.'views/attendance/'.$page.'.php'))
        {
            show_404();
        }
        $data["title"] = "My Roster";
        $this->load->view('app/templates/header', $data);
        $this->load->view('app/templates/side_panel', $data);
        $this->load->view('attendance/'.$page, $data);
        $this->load->view('app/templates/footer', $data);
    }

    public function request_roster_change($page = "request_roster_change")
    {
        $data = array();
        if(!file_exists(APPPATH.'views/attendance/'.$page.'.php'))
        {
            show_404();
        }
        $data["shift_presets"] = $this->Attendance_model->get_shift_presets();
        $data["title"] = "Roster Change Request";
        $this->load->view('app/templates/header', $data);
        $this->load->view('app/templates/side_panel', $data);
        $this->load->view('attendance/'.$page, $data);
        $this->load->view('app/templates/footer', $data);
    }

    public function roster_requests($page = "roster_requests")
    {
        if(!$this->is_admin())
        {
            show_error('You are not authorized to access this page.', 403);
        }
        $data = array();
        if(!file_exists(APPPATH.'views/attendance/'.$page.'.php'))
        {
            show_404();
        }
        $data["title"] = "Roster Change Requests";
        $this->load->view('app/templates/header', $data);
        $this->load->view('app/templates/side_panel', $data);
        $this->load->view('attendance/'.$page, $data);
        $this->load->view('app/templates/footer', $data);
    }

    // ------------------------------------------------------------------
    // AJAX: FILE UPLOAD (RAW REPORT / ROSTER)
    // ------------------------------------------------------------------

    public function upload_raw_report()
    {
        if(!$this->is_admin())
        {
            return $this->deny_json();
        }

        if(!isset($_FILES['raw_report_file']) || $_FILES['raw_report_file']['error'] !== 0)
        {
            echo json_encode(array("response" => FALSE, "message" => "File select karo."));
            return;
        }

        $config['upload_path']   = './uploads/attendance/';
        $config['allowed_types'] = 'xlsx|xls|csv';
        $config['max_size']      = 20480; // 20 MB
        $config['encrypt_name']  = TRUE;

        if(!is_dir($config['upload_path']))
        {
            mkdir($config['upload_path'], 0755, TRUE);
        }

        $this->load->library('upload', $config);

        if(!$this->upload->do_upload('raw_report_file'))
        {
            echo json_encode(array("response" => FALSE, "message" => $this->upload->display_errors('', '')));
            return;
        }

        $file_data = $this->upload->data();
        $file_path = $file_data['full_path'];

        // upload batch row
        $upload_id = $this->Attendance_model->create_upload_batch(array(
            'file_name'  => $file_data['orig_name'],
            'file_type'  => 'raw_report',
            'uploaded_by'=> $this->current_emp_id(),
            'status'     => 'processing'
        ));

        try
        {
            $this->load->library('excel');
            // Dialer se seedha export hui Raw Report file usually single-sheet hoti hai
            // (sheet ka naam har export me alag ho sakta hai — 'Sheet1', 'Report', etc.),
            // isliye sheet_name NULL pass kar rahe hain taaki library first/only sheet
            // khud pick kar le. Agar file me multiple sheets hain aur specific naam
            // chahiye to yahan naam pass kar dena.
            $rows = $this->excel->read_sheet($file_path, null);

            $result = $this->Attendance_model->process_raw_report_rows($rows, $upload_id);
        }
        catch(Exception $e)
        {
            $this->Attendance_model->update_upload_batch($upload_id, array(
                'status'    => 'failed',
                'error_log' => $e->getMessage()
            ));
            echo json_encode(array("response" => FALSE, "message" => "Upload fail: ".$e->getMessage()));
            return;
        }

        $this->Attendance_model->update_upload_batch($upload_id, array(
            'status'         => ($result['success_count'] > 0) ? 'success' : 'failed',
            'total_rows'     => $result['total_rows'],
            'success_count'  => $result['success_count'],
            'skipped_count'  => $result['skipped_count'],
            'error_log'      => implode("\n", $result['errors'])
        ));

        echo json_encode(array(
            "response" => TRUE,
            "message"  => "Processed {$result['total_rows']} rows: {$result['success_count']} success, {$result['skipped_count']} skipped.",
            "upload_id"=> $upload_id,
            "errors"   => $result['errors']
        ));
    }

    public function upload_roster()
    {
        if(!$this->is_admin())
        {
            return $this->deny_json();
        }

        if(!isset($_FILES['roster_file']) || $_FILES['roster_file']['error'] !== 0)
        {
            echo json_encode(array("response" => FALSE, "message" => "File select karo."));
            return;
        }

        $config['upload_path']   = './uploads/attendance/';
        $config['allowed_types'] = 'xlsx|xls|csv';
        $config['max_size']      = 20480;
        $config['encrypt_name']  = TRUE;

        $this->load->library('upload', $config);

        if(!$this->upload->do_upload('roster_file'))
        {
            echo json_encode(array("response" => FALSE, "message" => $this->upload->display_errors('', '')));
            return;
        }

        $file_data = $this->upload->data();
        $upload_id = $this->Attendance_model->create_upload_batch(array(
            'file_name'  => $file_data['orig_name'],
            'file_type'  => 'roster_plan',
            'uploaded_by'=> $this->current_emp_id(),
            'status'     => 'processing'
        ));

        // Admin ki asli roster file me ek hi workbook ke andar do sheets hoti hain —
        // Morning_Shift aur Evening_Shift (Both_Shift_Roster sirf formula-based merged
        // view hai, usko parse nahi karte). Dono sheets isi ek upload me process ho jaati hain.
        $sheet_shift_map = array(
            'Morning_Shift' => 'Morning',
            'Evening_Shift' => 'Evening'
        );

        $combined = array('total_rows' => 0, 'success_count' => 0, 'skipped_count' => 0, 'success_cells' => 0, 'errors' => array());
        $any_sheet_found = FALSE;

        try
        {
            $this->load->library('excel');

            foreach($sheet_shift_map as $sheet_name => $shift_group)
            {
                try
                {
                    $grid = $this->excel->read_raw_grid($file_data['full_path'], $sheet_name);
                }
                catch(Exception $inner)
                {
                    // is naam ki sheet file me nahi hai — skip, dusri sheet try karo
                    continue;
                }

                $any_sheet_found = TRUE;
                $result = $this->Attendance_model->process_shift_roster_grid($grid, $upload_id, $shift_group);

                $combined['total_rows']     += $result['total_rows'];
                $combined['success_count']  += $result['success_count'];
                $combined['skipped_count']  += $result['skipped_count'];
                $combined['success_cells']  += isset($result['success_cells']) ? $result['success_cells'] : 0;
                foreach($result['errors'] as $e)
                {
                    $combined['errors'][] = "[{$sheet_name}] {$e}";
                }
            }

            if(!$any_sheet_found)
            {
                throw new Exception("File me 'Morning_Shift' ya 'Evening_Shift' — koi bhi sheet nahi mili. Sheet names check karo.");
            }
        }
        catch(Exception $e)
        {
            $this->Attendance_model->update_upload_batch($upload_id, array(
                'status'    => 'failed',
                'error_log' => $e->getMessage()
            ));
            echo json_encode(array("response" => FALSE, "message" => "Upload fail: ".$e->getMessage()));
            return;
        }

        $result = $combined;

        $this->Attendance_model->update_upload_batch($upload_id, array(
            'status'         => ($result['success_count'] > 0) ? 'success' : 'failed',
            'total_rows'     => $result['total_rows'],
            'success_count'  => $result['success_count'],
            'skipped_count'  => $result['skipped_count'],
            'error_log'      => implode("\n", $result['errors'])
        ));

        echo json_encode(array(
            "response" => TRUE,
            "message"  => "Processed {$result['total_rows']} agents ({$result['success_cells']} roster-day entries): {$result['success_count']} agent-rows success, {$result['skipped_count']} skipped.",
            "errors"   => $result['errors']
        ));
    }

    public function get_recent_uploads()
    {
        if(!$this->is_admin())
        {
            return $this->deny_json();
        }
        $rows = $this->Attendance_model->get_recent_uploads(25);
        echo json_encode(array("response" => TRUE, "total_record" => count($rows), "all_record" => $rows));
    }

    // ------------------------------------------------------------------
    // AJAX: ATTENDANCE MATRIX
    // ------------------------------------------------------------------

    public function get_attendance_matrix()
    {
        $month = $this->input->post('month'); // '2026-09'
        $lob   = $this->input->post('lob');
        $tl_id = $this->input->post('tl_id');

        if(empty($month))
        {
            echo json_encode(array("response" => FALSE, "message" => "Month required."));
            return;
        }

        // role-based visibility
        $filter_emp_id = $this->is_allowed($this->tl_view_roles) ? NULL : $this->current_emp_id();

        $matrix = $this->Attendance_model->get_attendance_matrix($month, $filter_emp_id, $lob, $tl_id);

        echo json_encode(array(
            "response" => TRUE,
            "total_record" => count($matrix['rows']),
            "date_columns" => $matrix['date_columns'],
            "all_record" => $matrix['rows']
        ));
    }

    // ------------------------------------------------------------------
    // AJAX: TL WISE SHRINKAGE
    // ------------------------------------------------------------------

    public function get_tl_wise_shrinkage()
    {
        if(!$this->is_allowed($this->tl_view_roles))
        {
            return $this->deny_json();
        }
        $month = $this->input->post('month');
        $rows = $this->Attendance_model->get_tl_wise_shrinkage($month);
        echo json_encode(array("response" => TRUE, "total_record" => count($rows), "all_record" => $rows));
    }

    // ------------------------------------------------------------------
    // AJAX: SHRINKAGE OVERVIEW
    // ------------------------------------------------------------------

    public function get_shrinkage_overview()
    {
        if(!$this->is_allowed($this->tl_view_roles))
        {
            return $this->deny_json();
        }
        $group_by = $this->input->post('group_by'); // 'lob' | 'tl' | 'shift'
        $date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');

        $rows = $this->Attendance_model->get_shrinkage_overview($group_by, $date_from, $date_to);
        echo json_encode(array("response" => TRUE, "total_record" => count($rows), "all_record" => $rows));
    }

    public function get_daily_shrinkage_trend()
    {
        if(!$this->is_allowed($this->tl_view_roles))
        {
            return $this->deny_json();
        }
        $date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');
        $rows = $this->Attendance_model->get_daily_shrinkage_trend($date_from, $date_to);
        echo json_encode(array("response" => TRUE, "total_record" => count($rows), "all_record" => $rows));
    }

    // ------------------------------------------------------------------
    // AJAX: EXCEPTIONS (Admin only)
    // ------------------------------------------------------------------

    public function get_exceptions()
    {
        if(!$this->is_admin())
        {
            return $this->deny_json();
        }
        $date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');
        $rows = $this->Attendance_model->get_exceptions($date_from, $date_to);
        echo json_encode(array("response" => TRUE, "total_record" => count($rows), "all_record" => $rows));
    }

    public function add_exception()
    {
        if(!$this->is_admin())
        {
            return $this->deny_json();
        }

        $emp_id = (int)$this->input->post('emp_id');
        $exception_date = $this->input->post('exception_date');
        $exception_minutes = (int)$this->input->post('exception_minutes');
        $reason = trim($this->input->post('reason'));

        if($emp_id <= 0)
        {
            echo json_encode(array("response" => FALSE, "message" => "Select employee."));
            return;
        }
        if(empty($exception_date))
        {
            echo json_encode(array("response" => FALSE, "message" => "Select date."));
            return;
        }
        if($reason == "")
        {
            echo json_encode(array("response" => FALSE, "message" => "Reason likho."));
            return;
        }

        $result = $this->Attendance_model->upsert_exception(array(
            'emp_id'            => $emp_id,
            'exception_date'    => $exception_date,
            'exception_minutes' => $exception_minutes,
            'reason'            => $reason,
            'added_by'          => $this->current_emp_id()
        ));

        if($result)
        {
            // exception badalte hi us din ka final_status recompute karo
            $this->Attendance_model->recompute_attendance_daily($emp_id, $exception_date);
            echo json_encode(array("response" => TRUE, "message" => "Exception saved."));
        }
        else
        {
            echo json_encode(array("response" => FALSE, "message" => "Save nahi hua, dobara try karo."));
        }
    }

    // ------------------------------------------------------------------
    // AJAX: ROSTER BUILDER (manual roster creation)
    // ------------------------------------------------------------------

    public function get_shift_presets()
    {
        if(!$this->is_admin())
        {
            return $this->deny_json();
        }
        $rows = $this->Attendance_model->get_shift_presets();
        echo json_encode(array("response" => TRUE, "total_record" => count($rows), "all_record" => $rows));
    }

    public function save_shift_preset()
    {
        if(!$this->is_admin())
        {
            return $this->deny_json();
        }

        $shift_name = trim($this->input->post('shift_name'));
        $start_time = trim($this->input->post('start_time'));
        $end_time = trim($this->input->post('end_time'));

        if($shift_name == '' || $start_time == '' || $end_time == '')
        {
            echo json_encode(array("response" => FALSE, "message" => "Shift name, start aur end time — teeno chahiye."));
            return;
        }

        $id = $this->Attendance_model->save_shift_preset($shift_name, $start_time, $end_time);
        echo json_encode(array("response" => TRUE, "message" => "Shift preset add ho gaya.", "id" => $id));
    }

    public function delete_shift_preset()
    {
        if(!$this->is_admin())
        {
            return $this->deny_json();
        }
        $id = (int)$this->input->post('id');
        $this->Attendance_model->delete_shift_preset($id);
        echo json_encode(array("response" => TRUE, "message" => "Preset hata diya."));
    }

    public function get_roster_grid()
    {
        if(!$this->is_admin())
        {
            return $this->deny_json();
        }

        $month = $this->input->post('month');
        $shift_group = $this->input->post('shift_group');
        $lob = $this->input->post('lob');
        $tl_id = $this->input->post('tl_id');

        if(empty($month))
        {
            echo json_encode(array("response" => FALSE, "message" => "Month required."));
            return;
        }

        $grid = $this->Attendance_model->get_roster_grid_data($month, $shift_group, $lob, $tl_id);

        echo json_encode(array(
            "response" => TRUE,
            "total_record" => count($grid['rows']),
            "date_columns" => $grid['date_columns'],
            "all_record" => $grid['rows']
        ));
    }

    public function save_roster_cell()
    {
        if(!$this->is_admin())
        {
            return $this->deny_json();
        }

        $emp_id = (int)$this->input->post('emp_id');
        $date = $this->input->post('roster_date');
        $code = $this->input->post('code');

        if($emp_id <= 0 || empty($date) || empty($code))
        {
            echo json_encode(array("response" => FALSE, "message" => "Invalid request."));
            return;
        }

        $result = $this->Attendance_model->apply_roster_value($emp_id, $date, $code);
        echo json_encode(array("response" => $result['ok'], "message" => $result['message']));
    }

    public function bulk_apply_roster()
    {
        if(!$this->is_admin())
        {
            return $this->deny_json();
        }

        $emp_ids = $this->input->post('emp_ids'); // array
        $date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');
        $weekdays = $this->input->post('weekdays'); // array of 1-7, ya empty
        $code = $this->input->post('code');

        if(empty($emp_ids) || !is_array($emp_ids))
        {
            echo json_encode(array("response" => FALSE, "message" => "Kam se kam 1 employee select karo."));
            return;
        }
        if(empty($date_from) || empty($date_to))
        {
            echo json_encode(array("response" => FALSE, "message" => "Date range select karo."));
            return;
        }
        if(empty($code))
        {
            echo json_encode(array("response" => FALSE, "message" => "Value select karo (shift/WO/CL/HD/LP)."));
            return;
        }

        $weekdays = is_array($weekdays) ? array_map('intval', $weekdays) : array();

        $result = $this->Attendance_model->bulk_apply_roster($emp_ids, $date_from, $date_to, $weekdays, $code);

        $message = "{$result['applied']} roster entries apply ho gayi.";
        if(!empty($result['skipped']))
        {
            $message .= " ".count($result['skipped'])." skip ho gayi (WO cap / CL balance ki wajah se).";
        }

        echo json_encode(array(
            "response" => TRUE,
            "message"  => $message,
            "applied"  => $result['applied'],
            "skipped"  => $result['skipped']
        ));
    }

    // ------------------------------------------------------------------
    // AJAX: EMPLOYEE MASTER (manual employee add/edit)
    // ------------------------------------------------------------------

    public function get_employee_list()
    {
        if(!$this->is_admin())
        {
            return $this->deny_json();
        }
        $rows = $this->Attendance_model->get_employee_master_list();
        echo json_encode(array("response" => TRUE, "total_record" => count($rows), "all_record" => $rows));
    }

    public function get_unlinked_users()
    {
        if(!$this->is_admin())
        {
            return $this->deny_json();
        }
        $rows = $this->Attendance_model->get_unlinked_master_users();
        echo json_encode(array("response" => TRUE, "total_record" => count($rows), "all_record" => $rows));
    }

    public function add_employee()
    {
        if(!$this->is_admin())
        {
            return $this->deny_json();
        }

        $emp_id = (int)$this->input->post('emp_id');
        $name = trim($this->input->post('name'));
        $lob = trim($this->input->post('lob'));
        $employment_type = $this->input->post('employment_type');
        $emp_status = $this->input->post('emp_status');
        $shift_group = $this->input->post('shift_group');
        $tl_emp_id = $this->input->post('tl_emp_id');
        $gender = trim($this->input->post('gender'));
        $doj = $this->input->post('doj');
        $inactive_from = $this->input->post('inactive_from');

        if($emp_id <= 0 || $name == '')
        {
            echo json_encode(array("response" => FALSE, "message" => "User select karo aur naam bharo."));
            return;
        }
        if($emp_status == 'Inactive' && empty($inactive_from))
        {
            echo json_encode(array("response" => FALSE, "message" => "Inactive karne ke liye 'Inactive From' date daalo."));
            return;
        }

        $data = array(
            'emp_id' => $emp_id,
            'name' => $name,
            'lob' => $lob,
            'employment_type' => ($employment_type == 'PT') ? 'PT' : 'FT',
            'emp_status' => in_array($emp_status, array('Active','Inactive','Nesting','Training')) ? $emp_status : 'Active',
            'shift_group' => ($shift_group == 'Evening') ? 'Evening' : (($shift_group == 'Morning') ? 'Morning' : null),
            'gender' => $gender,
            'tl_emp_id' => (!empty($tl_emp_id)) ? (int)$tl_emp_id : null,
            'doj' => (!empty($doj)) ? $doj : null,
            'inactive_from' => ($emp_status == 'Inactive' && !empty($inactive_from)) ? $inactive_from : null
        );

        $result = $this->Attendance_model->add_employee($data);

        if($result)
        {
            echo json_encode(array("response" => TRUE, "message" => "Employee add ho gaya."));
        }
        else
        {
            echo json_encode(array("response" => FALSE, "message" => "Ye employee pehle se add hai."));
        }
    }

    public function update_employee()
    {
        if(!$this->is_admin())
        {
            return $this->deny_json();
        }

        $emp_id = (int)$this->input->post('emp_id');
        if($emp_id <= 0)
        {
            echo json_encode(array("response" => FALSE, "message" => "Invalid employee."));
            return;
        }

        $name = trim($this->input->post('name'));
        $lob = trim($this->input->post('lob'));
        $employment_type = $this->input->post('employment_type');
        $emp_status = $this->input->post('emp_status');
        $shift_group = $this->input->post('shift_group');
        $tl_emp_id = $this->input->post('tl_emp_id');
        $gender = trim($this->input->post('gender'));
        $doj = $this->input->post('doj');
        $inactive_from = $this->input->post('inactive_from');

        if($emp_status == 'Inactive' && empty($inactive_from))
        {
            echo json_encode(array("response" => FALSE, "message" => "Inactive karne ke liye 'Inactive From' date daalo."));
            return;
        }

        $data = array(
            'name' => $name,
            'lob' => $lob,
            'employment_type' => ($employment_type == 'PT') ? 'PT' : 'FT',
            'emp_status' => in_array($emp_status, array('Active','Inactive','Nesting','Training')) ? $emp_status : 'Active',
            'shift_group' => ($shift_group == 'Evening') ? 'Evening' : (($shift_group == 'Morning') ? 'Morning' : null),
            'gender' => $gender,
            'tl_emp_id' => (!empty($tl_emp_id)) ? (int)$tl_emp_id : null,
            'doj' => (!empty($doj)) ? $doj : null,
            'inactive_from' => ($emp_status == 'Inactive' && !empty($inactive_from)) ? $inactive_from : null
        );

        $this->Attendance_model->update_employee($emp_id, $data);
        echo json_encode(array("response" => TRUE, "message" => "Employee update ho gaya."));
    }

    // ------------------------------------------------------------------
    // AJAX: CL TRACKER
    // ------------------------------------------------------------------

    public function get_cl_tracker()
    {
        if(!$this->is_admin())
        {
            return $this->deny_json();
        }
        $rows = $this->Attendance_model->get_cl_tracker();
        echo json_encode(array("response" => TRUE, "total_record" => count($rows), "all_record" => $rows));
    }

    public function run_cl_accrual()
    {
        if(!$this->is_admin())
        {
            return $this->deny_json();
        }
        $month = $this->input->post('month');
        if(empty($month)) $month = date('Y-m');

        $result = $this->Attendance_model->run_monthly_cl_accrual($month);
        echo json_encode(array(
            "response" => TRUE,
            "message" => "{$result['accrued']} employees ko CL accrue hui, {$result['lapsed']} me se cap-se-upar lapse hua."
        ));
    }

    // ------------------------------------------------------------------
    // AJAX: ROSTER COMPLIANCE
    // ------------------------------------------------------------------

    public function get_roster_compliance()
    {
        if(!$this->is_admin())
        {
            return $this->deny_json();
        }
        $date = $this->input->post('date');
        if(empty($date))
        {
            echo json_encode(array("response" => FALSE, "message" => "Date select karo."));
            return;
        }
        $report = $this->Attendance_model->check_roster_compliance($date);
        echo json_encode(array("response" => TRUE, "report" => $report));
    }

    // ------------------------------------------------------------------
    // AJAX: MY ROSTER (agent apna roster dekhta hai — read only)
    // ------------------------------------------------------------------

    public function get_my_roster()
    {
        $month = $this->input->post('month');
        if(empty($month))
        {
            echo json_encode(array("response" => FALSE, "message" => "Month required."));
            return;
        }

        $rows = $this->Attendance_model->get_employee_roster_month($this->current_emp_id(), $month);
        echo json_encode(array("response" => TRUE, "total_record" => count($rows), "all_record" => $rows));
    }

    // ------------------------------------------------------------------
    // AJAX: ROSTER CHANGE REQUEST — AGENT SIDE
    // ------------------------------------------------------------------

    public function submit_roster_change_request()
    {
        $date = $this->input->post('request_date');
        $code = $this->input->post('code');
        $remark = trim($this->input->post('remark'));

        if(empty($date))
        {
            echo json_encode(array("response" => FALSE, "message" => "Date select karo."));
            return;
        }
        if(empty($code))
        {
            echo json_encode(array("response" => FALSE, "message" => "Kya chahiye wo select karo."));
            return;
        }
        if($remark == '')
        {
            echo json_encode(array("response" => FALSE, "message" => "Reason likho ki roster change kyu chahiye."));
            return;
        }

        // agent hamesha apna hi emp_id use karega, kisi aur ka POST se emp_id accept nahi karte
        $id = $this->Attendance_model->create_roster_change_request($this->current_emp_id(), $date, $code, $remark);

        echo json_encode(array("response" => TRUE, "message" => "Request bhej di gayi, admin approval ka wait karo.", "id" => $id));
    }

    public function get_my_roster_requests()
    {
        $rows = $this->Attendance_model->get_my_roster_requests($this->current_emp_id());
        echo json_encode(array("response" => TRUE, "total_record" => count($rows), "all_record" => $rows));
    }

    // ------------------------------------------------------------------
    // AJAX: ROSTER CHANGE REQUEST — ADMIN SIDE
    // ------------------------------------------------------------------

    public function get_all_roster_requests()
    {
        if(!$this->is_admin())
        {
            return $this->deny_json();
        }
        $status = $this->input->post('status'); // '' = sab, ya 'Pending'/'Approved'/'Rejected'
        $rows = $this->Attendance_model->get_all_roster_requests($status ?: null);
        echo json_encode(array("response" => TRUE, "total_record" => count($rows), "all_record" => $rows));
    }

    public function check_request_capacity()
    {
        if(!$this->is_admin())
        {
            return $this->deny_json();
        }
        $emp_id = (int)$this->input->post('emp_id');
        $date = $this->input->post('request_date');
        $code = $this->input->post('code');

        $result = $this->Attendance_model->check_capacity_for_leave_change($emp_id, $date, $code);
        echo json_encode(array("response" => TRUE, "capacity_ok" => $result['ok'], "message" => $result['message']));
    }

    public function approve_roster_request()
    {
        if(!$this->is_admin())
        {
            return $this->deny_json();
        }

        $request_id = (int)$this->input->post('request_id');
        $admin_remark = trim($this->input->post('admin_remark'));
        $force = (int)$this->input->post('force') === 1;

        if($request_id <= 0)
        {
            echo json_encode(array("response" => FALSE, "message" => "Invalid request."));
            return;
        }
        if($admin_remark == '')
        {
            echo json_encode(array("response" => FALSE, "message" => "Approval ka reason/remark likho."));
            return;
        }

        $result = $this->Attendance_model->approve_roster_request($request_id, $this->current_emp_id(), $admin_remark, $force);
        echo json_encode(array("response" => $result['ok'], "message" => $result['message']));
    }

    public function reject_roster_request()
    {
        if(!$this->is_admin())
        {
            return $this->deny_json();
        }

        $request_id = (int)$this->input->post('request_id');
        $admin_remark = trim($this->input->post('admin_remark'));

        if($request_id <= 0)
        {
            echo json_encode(array("response" => FALSE, "message" => "Invalid request."));
            return;
        }
        if($admin_remark == '')
        {
            echo json_encode(array("response" => FALSE, "message" => "Reject karne ka reason likhna zaroori hai."));
            return;
        }

        $result = $this->Attendance_model->reject_roster_request($request_id, $this->current_emp_id(), $admin_remark);
        echo json_encode(array("response" => $result['ok'], "message" => $result['message']));
    }

}