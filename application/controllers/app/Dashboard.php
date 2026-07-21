<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    private $dashboard_roles = array(1, 2, 3);

    public function __construct(){
        parent::__construct();
        if(!isset($_SESSION['userdata']) || $_SESSION['userdata']['user_logged_status'] != TRUE)
        {
            redirect('app/logout');
        }
        if(!in_array((int)$_SESSION['userdata']['role_id'], $this->dashboard_roles))
        {
            show_error('You are not authorized to access dashboard.', 403);
        }
    }

    private function role_id()
    {
        return (int)$_SESSION['userdata']['role_id'];
    }

    private function emp_id()
    {
        return (int)$_SESSION['userdata']['emp_id'];
    }

    private function dashboard_filters()
    {
        return array(
            'start_date' => $this->input->post('start_date', TRUE),
            'end_date' => $this->input->post('end_date', TRUE),
            'agent_id' => $this->input->post('agent_id', TRUE),
            'department' => $this->input->post('department', TRUE),
            'shift' => $this->input->post('shift', TRUE),
            'report' => $this->input->post('report', TRUE),
            'attribute' => $this->input->post('attribute', TRUE),
            'status' => $this->input->post('status', TRUE)
        );
    }

    private function normalize_filters($filters)
    {
        foreach($filters as $key => $value)
        {
            $filters[$key] = trim((string)$value);
        }
        return $filters;
    }

    private function shift_expr($field)
    {
        return "CASE WHEN TIME(".$field.") >= '07:00:00' AND TIME(".$field.") <= '14:30:00' THEN 'Morning Shift' WHEN TIME(".$field.") > '14:30:00' AND TIME(".$field.") <= '22:00:00' THEN 'Evening Shift' ELSE 'Other' END";
    }

    private function base_union_sql()
    {
        $callback_shift = $this->shift_expr('t1.created_at');
        $name_shift = $this->shift_expr('t1.created_at');
        $added_shift = $this->shift_expr('t1.added_at');
        $owa_shift = $this->shift_expr('t1.added_at');
        $high_status = "CASE t1.type WHEN 1 THEN 'Pending' WHEN 2 THEN 'Move To Next Level' WHEN 3 THEN 'Closed' WHEN 4 THEN 'High Rated Tag' ELSE 'Unknown' END";

        $sql = array();

        $sql[] = "SELECT 'callback' report_key, 'Callback Report' report_name, 'report' analytics_group, t1.id record_id, t1.date entry_date, t1.phone_number phone_number, '' complaint_number, t1.department department_id, dept.Departname_E department_name, NULL attribute_id, '' attribute_name, t1.assigned_agent_id agent_id, agent.user_name agent_name, agent.msd_id agent_msd_id, t1.created_by created_by, creator.user_name created_by_name, t1.created_at created_at, t1.updated_by updated_by, updater.user_name updated_by_name, t1.updated_at updated_at, CASE WHEN t1.updated_at IS NULL THEN 'Pending' ELSE 'Completed' END status_label, t1.latest_remark latest_remark, (SELECT COUNT(*) FROM callback_report_history h WHERE h.callback_id=t1.id AND h.action_type IN ('update','assign')) updates_count, ".$callback_shift." shift_name FROM callback_report t1 LEFT JOIN master_users agent ON t1.assigned_agent_id=agent.emp_id LEFT JOIN master_users creator ON t1.created_by=creator.emp_id LEFT JOIN master_users updater ON t1.updated_by=updater.emp_id LEFT JOIN department dept ON t1.department=dept.Departid WHERE t1.status=1";

        $sql[] = "SELECT 'name_change' report_key, 'Name Change Report' report_name, 'report' analytics_group, t1.id record_id, t1.date entry_date, t1.phone_number phone_number, t1.complaint_number complaint_number, NULL department_id, '' department_name, NULL attribute_id, '' attribute_name, t1.agent_id agent_id, agent.user_name agent_name, agent.msd_id agent_msd_id, t1.created_by created_by, creator.user_name created_by_name, t1.created_at created_at, NULL updated_by, '' updated_by_name, NULL updated_at, 'Completed' status_label, t1.remark latest_remark, 0 updates_count, ".$name_shift." shift_name FROM name_change_report t1 LEFT JOIN master_users agent ON t1.agent_id=agent.emp_id LEFT JOIN master_users creator ON t1.created_by=creator.emp_id WHERE t1.status=1";

        $sql[] = "SELECT 'no_same' report_key, 'No/Same Resolution Report' report_name, 'report' analytics_group, t1.id record_id, t1.date entry_date, '' phone_number, t1.complaint_no complaint_number, t1.department department_id, dept.Departname_E department_name, NULL attribute_id, '' attribute_name, t1.agent_id agent_id, agent.user_name agent_name, agent.msd_id agent_msd_id, t1.added_by created_by, creator.user_name created_by_name, t1.added_at created_at, t1.updated_by updated_by, updater.user_name updated_by_name, t1.updated_at updated_at, CASE WHEN t1.helpdesk_remark IS NULL AND t1.call_date IS NULL THEN 'Pending' ELSE 'Completed' END status_label, IFNULL(t1.helpdesk_remark,''), CASE WHEN t1.updated_at IS NULL THEN 0 ELSE 1 END updates_count, ".$added_shift." shift_name FROM no_same_resolution_report t1 LEFT JOIN master_users agent ON t1.agent_id=agent.emp_id LEFT JOIN master_users creator ON t1.added_by=creator.emp_id LEFT JOIN master_users updater ON t1.updated_by=updater.emp_id LEFT JOIN department dept ON t1.department=dept.Departid WHERE t1.status=1";

        $sql[] = "SELECT 'copy_paste' report_key, 'Copy/Paste Resolution Report' report_name, 'report' analytics_group, t1.id record_id, t1.date entry_date, '' phone_number, t1.complaint_no complaint_number, t1.department department_id, dept.Departname_E department_name, NULL attribute_id, '' attribute_name, t1.agent_id agent_id, agent.user_name agent_name, agent.msd_id agent_msd_id, t1.added_by created_by, creator.user_name created_by_name, t1.added_at created_at, t1.updated_by updated_by, updater.user_name updated_by_name, t1.updated_at updated_at, CASE WHEN t1.helpdesk_remark IS NULL AND t1.call_date IS NULL THEN 'Pending' ELSE 'Completed' END status_label, IFNULL(t1.helpdesk_remark,'') latest_remark, CASE WHEN t1.updated_at IS NULL THEN 0 ELSE 1 END updates_count, ".$added_shift." shift_name FROM copy_paste_wrong_resolution_report t1 LEFT JOIN master_users agent ON t1.agent_id=agent.emp_id LEFT JOIN master_users creator ON t1.added_by=creator.emp_id LEFT JOIN master_users updater ON t1.updated_by=updater.emp_id LEFT JOIN department dept ON t1.department=dept.Departid WHERE t1.status=1";

        $sql[] = "SELECT 'direction' report_key, 'Direction Report' report_name, 'report' analytics_group, t1.id record_id, t1.date entry_date, '' phone_number, t1.complaint_no complaint_number, t1.department department_id, dept.Departname_E department_name, NULL attribute_id, '' attribute_name, t1.agent_id agent_id, agent.user_name agent_name, agent.msd_id agent_msd_id, t1.added_by created_by, creator.user_name creator_name, t1.added_at created_at, t1.updated_by updated_by, updater.user_name updated_by_name, t1.updated_at updated_at, CASE WHEN t1.helpdesk_remark IS NULL AND t1.call_date IS NULL THEN 'Pending' ELSE 'Completed' END status_label, IFNULL(t1.helpdesk_remark,'') latest_remark, CASE WHEN t1.updated_at IS NULL THEN 0 ELSE 1 END updates_count, ".$added_shift." shift_name FROM direction_report t1 LEFT JOIN master_users agent ON t1.agent_id=agent.emp_id LEFT JOIN master_users creator ON t1.added_by=creator.emp_id LEFT JOIN master_users updater ON t1.updated_by=updater.emp_id LEFT JOIN department dept ON t1.department=dept.Departid WHERE t1.status=1";

        $sql[] = "SELECT 'complaint_creation' report_key, 'Complaint Creation' report_name, 'complaint' analytics_group, t1.id record_id, DATE(t1.added_at) entry_date, '' phone_number, t1.comp_id complaint_number, t1.department department_id, dept.Departname_E department_name, t1.attribute attribute_id, attr.attribname_E attribute_name, t1.added_by agent_id, agent.user_name agent_name, agent.msd_id agent_msd_id, t1.added_by created_by, agent.user_name created_by_name, t1.added_at created_at, NULL updated_by, '' updated_by_name, NULL updated_at, 'Created' status_label, '' latest_remark, 0 updates_count, ".$added_shift." shift_name FROM complaint_creation t1 LEFT JOIN master_users agent ON t1.added_by=agent.emp_id LEFT JOIN department dept ON t1.department=dept.Departid LEFT JOIN complaintattrib attr ON t1.attribute=attr.attribID";

        $sql[] = "SELECT 'high_complaint' report_key, 'High Rated Tag / Status' report_name, 'complaint_status' analytics_group, t1.id record_id, DATE(t1.added_at) entry_date, '' phone_number, t1.comp_id complaint_number, cc.department department_id, dept.Departname_E department_name, cc.attribute attribute_id, attr.attribname_E attribute_name, t1.added_by agent_id, agent.user_name agent_name, agent.msd_id agent_msd_id, t1.added_by created_by, agent.user_name created_by_name, t1.added_at created_at, NULL updated_by, '' updated_by_name, NULL updated_at, ".$high_status." status_label, ".$high_status." latest_remark, 0 updates_count, ".$added_shift." shift_name FROM high_complaint_creation t1 LEFT JOIN complaint_creation cc ON t1.comp_id=cc.comp_id LEFT JOIN master_users agent ON t1.added_by=agent.emp_id LEFT JOIN department dept ON cc.department=dept.Departid LEFT JOIN complaintattrib attr ON cc.attribute=attr.attribID";

        $sql[] = "SELECT 'owa' report_key, 'OWA Report' report_name, 'owa' analytics_group, t1.id record_id, t1.date entry_date, '' phone_number, t1.complaint_no complaint_number, t1.new_department department_id, new_dept.Departname_E department_name, t1.new_attribute attribute_id, new_attr.attribname_E attribute_name, t1.agent_id agent_id, agent.user_name agent_name, agent.msd_id agent_msd_id, t1.added_by created_by, creator.user_name created_by_name, t1.added_at created_at, NULL updated_by, '' updated_by_name, NULL updated_at, 'Created' status_label, t1.owa_reason latest_remark, 0 updates_count, ".$owa_shift." shift_name FROM owa_report t1 LEFT JOIN master_users agent ON t1.agent_id=agent.emp_id LEFT JOIN master_users creator ON t1.added_by=creator.emp_id LEFT JOIN department new_dept ON t1.new_department=new_dept.Departid LEFT JOIN complaintattrib new_attr ON t1.new_attribute=new_attr.attribID WHERE t1.status=1";

        return "(".implode(" UNION ALL ", $sql).") dash";
    }

    private function filtered_rows($filters, $limit = NULL)
    {
        $filters = $this->normalize_filters($filters);
        $where = array();
        $params = array();

        if($filters['start_date'] !== '')
        {
            $where[] = "dash.entry_date >= ?";
            $params[] = $filters['start_date'];
        }
        if($filters['end_date'] !== '')
        {
            $where[] = "dash.entry_date <= ?";
            $params[] = $filters['end_date'];
        }
        if($filters['agent_id'] !== '' && $filters['agent_id'] !== '0')
        {
            $where[] = "dash.agent_id = ?";
            $params[] = $filters['agent_id'];
        }
        if($filters['department'] !== '' && $filters['department'] !== '0')
        {
            $where[] = "dash.department_id = ?";
            $params[] = $filters['department'];
        }
        if($filters['shift'] !== '' && $filters['shift'] !== 'all')
        {
            $where[] = "dash.shift_name = ?";
            $params[] = ($filters['shift'] == 'morning') ? 'Morning Shift' : 'Evening Shift';
        }
        if($filters['report'] !== '' && $filters['report'] !== 'all')
        {
            $where[] = "dash.report_key = ?";
            $params[] = $filters['report'];
        }
        if(isset($filters['attribute']) && $filters['attribute'] !== '' && $filters['attribute'] !== '0')
        {
            $where[] = "dash.attribute_id = ?";
            $params[] = $filters['attribute'];
        }
        if(isset($filters['status']) && $filters['status'] !== '' && $filters['status'] !== 'all')
        {
            $status_map = array(
                'created' => 'Created',
                'pending' => 'Pending',
                'completed' => 'Completed',
                'closed' => 'Closed',
                'next_level' => 'Move To Next Level',
                'high_rated' => 'High Rated Tag'
            );
            if(isset($status_map[$filters['status']]))
            {
                $where[] = "dash.status_label = ?";
                $params[] = $status_map[$filters['status']];
            }
        }

        if($this->role_id() == 2)
        {
            $where[] = "dash.report_key IN ('callback','name_change','no_same','copy_paste','direction','complaint_creation','high_complaint','owa')";
            $where[] = "(dash.agent_id = ? OR dash.created_by = ? OR dash.updated_by = ?)";
            $params[] = $this->emp_id();
            $params[] = $this->emp_id();
            $params[] = $this->emp_id();
        }
        elseif($this->role_id() == 3)
        {
            $where[] = "dash.report_key IN ('no_same','copy_paste','direction','complaint_creation','high_complaint','owa')";
            $where[] = "(dash.agent_id = ? OR dash.created_by = ? OR dash.updated_by = ?)";
            $params[] = $this->emp_id();
            $params[] = $this->emp_id();
            $params[] = $this->emp_id();
        }

        $sql = "SELECT * FROM ".$this->base_union_sql();
        if(!empty($where))
        {
            $sql .= " WHERE ".implode(" AND ", $where);
        }
        $sql .= " ORDER BY dash.created_at DESC";
        if($limit !== NULL)
        {
            $sql .= " LIMIT ".(int)$limit;
        }

        return $this->db->query($sql, $params)->result();
    }

    private function summarize($rows)
    {
        $summary = array(
            'total' => 0,
            'today' => 0,
            'pending' => 0,
            'completed' => 0,
            'updates' => 0,
            'complaints_created' => 0,
            'high_rated' => 0,
            'next_level' => 0,
            'closed_complaints' => 0,
            'pending_complaints' => 0,
            'resolution_ratio' => 0,
            'pending_ratio' => 0,
            'closure_ratio' => 0,
            'report_counts' => array(),
            'shift_counts' => array(),
            'department_counts' => array(),
            'agent_counts' => array(),
            'date_counts' => array(),
            'attribute_counts' => array(),
            'status_counts' => array(),
            'report_creation_counts' => array(),
            'report_update_counts' => array(),
            'updater_counts' => array(),
            'owa_counts' => array()
        );
        $today = date('Y-m-d');
        $tracked_total = 0;
        $tracked_resolved = 0;

        foreach($rows as $row)
        {
            $summary['total']++;
            if($row->entry_date == $today) { $summary['today']++; }
            if($row->status_label == 'Pending') { $summary['pending']++; } else { $summary['completed']++; }
            $summary['updates'] += (int)$row->updates_count;
            $this->bump($summary['date_counts'], $row->entry_date);
            $this->bump($summary['status_counts'], $row->status_label);

            $this->bump($summary['report_counts'], $row->report_name);
            $this->bump($summary['shift_counts'], $row->shift_name);
            if($row->department_name != '') { $this->bump($summary['department_counts'], $row->department_name); }
            if($row->attribute_name != '') { $this->bump($summary['attribute_counts'], $row->attribute_name); }
            $agent = trim($row->agent_name) != '' ? $row->agent_name : 'Unassigned';
            if(!isset($summary['agent_counts'][$agent]))
            {
                $summary['agent_counts'][$agent] = array(
                    'records' => 0,
                    'updates' => 0,
                    'complaints' => 0,
                    'high_rated' => 0,
                    'pending' => 0,
                    'closed' => 0,
                    'next_level' => 0,
                    'contribution' => 0,
                    'resolution_ratio' => 0,
                    'pending_ratio' => 0,
                    'closure_ratio' => 0,
                    'reports' => array(),
                    'shifts' => array()
                );
            }
            $summary['agent_counts'][$agent]['records']++;
            $summary['agent_counts'][$agent]['updates'] += (int)$row->updates_count;
            $this->bump($summary['agent_counts'][$agent]['reports'], $row->report_name);
            $this->bump($summary['agent_counts'][$agent]['shifts'], $row->shift_name);

            if(in_array($row->report_key, array('no_same','copy_paste','direction')))
            {
                $this->bump($summary['report_creation_counts'], $row->report_name);
                if((int)$row->updates_count > 0)
                {
                    $summary['report_update_counts'][$row->report_name] = isset($summary['report_update_counts'][$row->report_name]) ? $summary['report_update_counts'][$row->report_name] + (int)$row->updates_count : (int)$row->updates_count;
                }
                if(trim($row->updated_by_name) != '')
                {
                    $summary['updater_counts'][$row->updated_by_name] = isset($summary['updater_counts'][$row->updated_by_name]) ? $summary['updater_counts'][$row->updated_by_name] + (int)$row->updates_count : (int)$row->updates_count;
                }
            }

            if($row->analytics_group == 'owa')
            {
                $this->bump($summary['owa_counts'], $agent);
            }

            if($row->analytics_group == 'complaint')
            {
                $summary['complaints_created']++;
                $summary['agent_counts'][$agent]['complaints']++;
            }

            if($row->analytics_group == 'complaint_status')
            {
                $tracked_total++;
                if($row->status_label == 'High Rated Tag')
                {
                    $summary['high_rated']++;
                    $summary['agent_counts'][$agent]['high_rated']++;
                }
                elseif($row->status_label == 'Move To Next Level')
                {
                    $summary['next_level']++;
                    $summary['agent_counts'][$agent]['next_level']++;
                }
                elseif($row->status_label == 'Closed')
                {
                    $summary['closed_complaints']++;
                    $summary['agent_counts'][$agent]['closed']++;
                    $tracked_resolved++;
                }
                elseif($row->status_label == 'Pending')
                {
                    $summary['pending_complaints']++;
                    $summary['agent_counts'][$agent]['pending']++;
                }
            }
        }

        foreach($summary['agent_counts'] as $agent_name => $agent_data)
        {
            $records = (int)$agent_data['records'];
            $agent_tracked = (int)$agent_data['high_rated'] + (int)$agent_data['pending'] + (int)$agent_data['closed'] + (int)$agent_data['next_level'];
            $summary['agent_counts'][$agent_name]['contribution'] = $summary['total'] > 0 ? round(($records / $summary['total']) * 100, 2) : 0;
            $summary['agent_counts'][$agent_name]['resolution_ratio'] = $agent_tracked > 0 ? round(((int)$agent_data['closed'] / $agent_tracked) * 100, 2) : 0;
            $summary['agent_counts'][$agent_name]['pending_ratio'] = $agent_tracked > 0 ? round(((int)$agent_data['pending'] / $agent_tracked) * 100, 2) : 0;
            $summary['agent_counts'][$agent_name]['closure_ratio'] = $summary['agent_counts'][$agent_name]['resolution_ratio'];
        }

        $summary['resolution_ratio'] = $tracked_total > 0 ? round(($tracked_resolved / $tracked_total) * 100, 2) : 0;
        $summary['pending_ratio'] = $tracked_total > 0 ? round(($summary['pending_complaints'] / $tracked_total) * 100, 2) : 0;
        $summary['closure_ratio'] = $summary['resolution_ratio'];

        return $summary;
    }

    private function bump(&$bucket, $key)
    {
        if($key === NULL || $key === '') { $key = 'Unknown'; }
        if(!isset($bucket[$key])) { $bucket[$key] = 0; }
        $bucket[$key]++;
    }

    public function get_dashboard()
    {
        $filters = $this->dashboard_filters();
        $rows = $this->filtered_rows($filters, NULL);
        $summary = $this->summarize($rows);
        $recent = array_slice($rows, 0, 500);

        echo json_encode(array(
            'response' => TRUE,
            'summary' => $summary,
            'rows' => $recent,
            'total_filtered' => count($rows),
            'shown_rows' => count($recent)
        ));
    }

    public function export_dashboard()
    {
        $filters = array(
            'start_date' => $this->input->get('start_date', TRUE),
            'end_date' => $this->input->get('end_date', TRUE),
            'agent_id' => $this->input->get('agent_id', TRUE),
            'department' => $this->input->get('department', TRUE),
            'shift' => $this->input->get('shift', TRUE),
            'report' => $this->input->get('report', TRUE),
            'attribute' => $this->input->get('attribute', TRUE),
            'status' => $this->input->get('status', TRUE)
        );
        $rows = $this->filtered_rows($filters, NULL);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="dashboard_export_'.date('Ymd_His').'.xls"');

        $out = fopen('php://output', 'w');
        fputcsv($out, array('Report', 'Date', 'Phone Number', 'Complaint Number', 'Department', 'Attribute', 'Agent', 'MSD ID', 'Status', 'Shift', 'Latest Remark', 'Updates', 'Created By', 'Created At', 'Updated By', 'Updated At'), "\t");
        foreach($rows as $row)
        {
            fputcsv($out, array($row->report_name, $row->entry_date, $row->phone_number, $row->complaint_number, $row->department_name, $row->attribute_name, $row->agent_name, $row->agent_msd_id, $row->status_label, $row->shift_name, $row->latest_remark, $row->updates_count, $row->created_by_name, $row->created_at, $row->updated_by_name, $row->updated_at), "\t");
        }
        fclose($out);
    }
}
