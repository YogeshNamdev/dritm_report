<link href="<?php echo base_url(); ?>includes/plugins/advance_datatable/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>includes/plugins/advance_datatable/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>includes/plugins/select2/css/select2.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>includes/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>includes/plugins/datatables-responsive/css/responsive.bootstrap4.min.css" rel="stylesheet">
<script src="<?php echo base_url(); ?>includes/plugins/advance_datatable/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>includes/plugins/advance_datatable/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url(); ?>includes/plugins/advance_datatable/buttons.print.min.js"></script>
<script src="<?php echo base_url(); ?>includes/plugins/advance_datatable/buttons.colVis.min.js"></script>
<script src="<?php echo base_url(); ?>includes/plugins/advance_datatable/pdfmake.min.js"></script>
<script src="<?php echo base_url(); ?>includes/plugins/advance_datatable/vfs_fonts.js"></script>
<script src="<?php echo base_url(); ?>includes/plugins/advance_datatable/buttons.html5.min.js"></script>
<script src="<?php echo base_url(); ?>includes/plugins/advance_datatable/jszip.min.js"></script>
<script src="<?php echo base_url(); ?>includes/plugins/select2/js/select2.full.min.js"></script>
<script src="<?php echo base_url(); ?>includes/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url(); ?>includes/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>

<style>
.psm-page .filter-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(160px, 1fr));
    gap: 10px;
}
.psm-page .filter-field label {
    display: block;
    margin-bottom: 4px;
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    color: #6b7280;
}
.psm-page .metric-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(140px, 1fr));
    gap: 12px;
    margin: 14px 0;
}
.psm-page .metric-box {
    border: 1px solid #dce3ec;
    border-radius: 8px;
    padding: 12px;
    background: #fff;
}
.psm-page .metric-label {
    color: #64748b;
    font-size: 12px;
    font-weight: 800;
    text-transform: uppercase;
}
.psm-page .metric-value {
    margin-top: 6px;
    color: #123047;
    font-size: 26px;
    line-height: 1;
    font-weight: 900;
}
.psm-page .history-cell {
    min-width: 160px;
    text-align: center;
}
.psm-page .remark-cell {
    min-width: 320px;
    max-width: 520px;
    white-space: normal;
}
.psm-page .table-sm td,
.psm-page .table-sm th {
    vertical-align: middle;
}
.psm-timeline-summary {
    display: grid;
    grid-template-columns: repeat(4, minmax(120px, 1fr));
    gap: 10px;
    margin-bottom: 14px;
}
.psm-timeline-stat {
    border: 1px solid #dbe5ef;
    border-radius: 8px;
    background: #f8fafc;
    padding: 10px;
}
.psm-timeline-stat span {
    display: block;
    color: #64748b;
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
}
.psm-timeline-stat strong {
    display: block;
    margin-top: 4px;
    color: #123047;
    font-size: 16px;
}
.psm-timeline {
    position: relative;
    padding-left: 28px;
}
.psm-timeline:before {
    content: "";
    position: absolute;
    top: 8px;
    bottom: 8px;
    left: 11px;
    width: 2px;
    background: #d6e0ea;
}
.psm-timeline-item {
    position: relative;
    margin-bottom: 14px;
    border: 1px solid #dbe5ef;
    border-radius: 8px;
    background: #fff;
    padding: 12px 14px;
}
.psm-timeline-item:before {
    content: "";
    position: absolute;
    top: 16px;
    left: -24px;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    border: 3px solid #fff;
    background: #2563eb;
    box-shadow: 0 0 0 2px #2563eb;
}
.psm-timeline-item.psm-action:before { background: #f59e0b; box-shadow: 0 0 0 2px #f59e0b; }
.psm-timeline-item.current-owner:before { background: #16a34a; box-shadow: 0 0 0 2px #16a34a; }
.psm-timeline-title {
    color: #123047;
    font-size: 14px;
    font-weight: 900;
}
.psm-timeline-time {
    color: #64748b;
    font-size: 12px;
    font-weight: 700;
}
.psm-timeline-meta {
    display: grid;
    grid-template-columns: repeat(2, minmax(160px, 1fr));
    gap: 6px 14px;
    margin-top: 8px;
    color: #334155;
    font-size: 13px;
}
.psm-timeline-remark {
    margin-top: 8px;
    color: #475569;
    line-height: 1.45;
}
@media(max-width: 1100px) {
    .psm-page .filter-grid { grid-template-columns: repeat(2, minmax(160px, 1fr)); }
    .psm-page .metric-grid { grid-template-columns: repeat(2, minmax(140px, 1fr)); }
    .psm-timeline-summary { grid-template-columns: repeat(2, minmax(120px, 1fr)); }
    .psm-timeline-meta { grid-template-columns: 1fr; }
}
@media(max-width: 640px) {
    .psm-page .filter-grid,
    .psm-page .metric-grid,
    .psm-timeline-summary { grid-template-columns: 1fr; }
}
</style>

<div class="content-wrapper psm-page">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url(); ?>app/home">Home</a></li>
                        <li class="breadcrumb-item active">PSM Audit Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header">
                    <h5 class="m-0"><b>PSM Audit & Management Tracking Report</b></h5>
                </div>
                <div class="card-body">
                    <div class="filter-grid">
                        <div class="filter-field">
                            <label>From Date</label>
                            <input type="date" id="from_date" class="form-control form-control-sm">
                        </div>
                        <div class="filter-field">
                            <label>To Date</label>
                            <input type="date" id="to_date" class="form-control form-control-sm">
                        </div>
                        <div class="filter-field">
                            <label>Complaint Number</label>
                            <input type="number" id="complaint_no" class="form-control form-control-sm" placeholder="Complaint ID">
                        </div>
                        <div class="filter-field">
                            <label>Department</label>
                            <select id="department" class="form-control form-control-sm select2">
                                <option value="">All Departments</option>
                                <?php if($department_list != FALSE){ foreach($department_list as $department){ echo "<option value='".$department->Departid."'>".$department->Departname_E."</option>"; }} ?>
                            </select>
                        </div>
                        <div class="filter-field">
                            <label>Officer</label>
                            <select id="officer" class="form-control form-control-sm select2">
                                <option value="">All PSM Officers</option>
                                <?php if($officer_list != FALSE){ foreach($officer_list as $officer){ echo "<option value='".$officer->officerid."'>".$officer->officername." (".$officer->loginuserid.")</option>"; }} ?>
                            </select>
                        </div>
                        <div class="filter-field">
                            <label>Current Officer</label>
                            <select id="current_officer" class="form-control form-control-sm select2">
                                <option value="">All Current Officers</option>
                                <?php if($officer_list != FALSE){ foreach($officer_list as $officer){ echo "<option value='".$officer->officerid."'>".$officer->officername." (".$officer->loginuserid.")</option>"; }} ?>
                            </select>
                        </div>
                        <div class="filter-field">
                            <label>Login User ID</label>
                            <input type="text" id="login_user_id" class="form-control form-control-sm" placeholder="Officer login">
                        </div>
                        <div class="filter-field">
                            <label>Complaint Status</label>
                            <select id="complaint_status" class="form-control form-control-sm">
                                <option value="">All Statuses</option>
                                <option value="Open">Open</option>
                                <option value="PC">PC</option>
                                <option value="WIP">WIP</option>
                                <option value="OWA">OWA</option>
                                <option value="Closed">Closed</option>
                                <option value="Force Closed">Force Closed</option>
                            </select>
                        </div>
                        <div class="filter-field">
                            <label>District</label>
                            <select id="district" class="form-control form-control-sm select2">
                                <option value="">All Districts</option>
                                <?php if($district_list != FALSE){ foreach($district_list as $district){ echo "<option value='".$district->District_Code."'>".$district->District_Name_E."</option>"; }} ?>
                            </select>
                        </div>
                        <div class="filter-field">
                            <label>&nbsp;</label>
                            <button type="button" class="btn btn-sm btn-primary" onclick="refreshCurrentReport();"><i class="fas fa-sync-alt"></i> Refresh</button>
                            <button type="button" class="btn btn-sm btn-secondary" onclick="resetPsmFilters();"><i class="fas fa-undo"></i> Reset</button>
                        </div>
                    </div>

                    <div class="metric-grid">
                        <div class="metric-box"><div class="metric-label">Active Report</div><div class="metric-value" id="metric_active_report">-</div></div>
                        <div class="metric-box"><div class="metric-label">Loaded Rows</div><div class="metric-value" id="metric_loaded_rows">-</div></div>
                        <div class="metric-box"><div class="metric-label">Cached Tabs</div><div class="metric-value" id="metric_cached_tabs">0</div></div>
                        <div class="metric-box"><div class="metric-label">Filter State</div><div class="metric-value" id="metric_filter_state">Ready</div></div>
                    </div>

                    <ul class="nav nav-tabs" id="psm_tabs" role="tablist">
                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" data-report="audit" href="#audit_tab">Detailed Audit</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" data-report="history" href="#history_tab">Complete History</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" data-report="officer" href="#officer_tab">Officer Wise</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" data-report="department" href="#department_tab">Department Wise</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" data-report="repeated" href="#repeated_tab">Top Repeated</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" data-report="same_officer" href="#same_officer_tab">Same Officer Repeated</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" data-report="complaint_summary" href="#complaint_summary_tab">Complaint Summary</a></li>
                    </ul>

                    <div class="tab-content pt-3">
                        <div class="tab-pane fade show active" id="audit_tab"><div id="audit_div"></div></div>
                        <div class="tab-pane fade" id="history_tab"><div id="history_div"></div></div>
                        <div class="tab-pane fade" id="officer_tab"><div id="officer_div"></div></div>
                        <div class="tab-pane fade" id="department_tab"><div id="department_div"></div></div>
                        <div class="tab-pane fade" id="repeated_tab"><div id="repeated_div"></div></div>
                        <div class="tab-pane fade" id="same_officer_tab"><div id="same_officer_div"></div></div>
                        <div class="tab-pane fade" id="complaint_summary_tab"><div id="complaint_summary_div"></div></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="psm_timeline_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>Complaint Movement Timeline</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="psm_timeline_summary"></div>
                <div id="psm_timeline_body"></div>
            </div>
        </div>
    </div>
</div>

<script>
var baseUrl = "<?php echo base_url(); ?>";
var csrfName = "<?php echo $this->security->get_csrf_token_name(); ?>";
var csrfHash = "<?php echo $this->security->get_csrf_hash(); ?>";
var psmTables = [];
var psmTabCache = {};
var psmHistoryCache = {};
var currentFilterSignature = "";
var activeReportType = "audit";

function escapeHtml(value) {
    return $("<div>").text(value == null ? "" : value).html();
}

function filterPayload() {
    var payload = {};
    payload[csrfName] = csrfHash;
    payload.from_date = $("#from_date").val();
    payload.to_date = $("#to_date").val();
    payload.complaint_no = $("#complaint_no").val();
    payload.department = $("#department").val();
    payload.officer = $("#officer").val();
    payload.current_officer = $("#current_officer").val();
    payload.login_user_id = $("#login_user_id").val();
    payload.complaint_status = $("#complaint_status").val();
    payload.district = $("#district").val();
    return payload;
}

function formatDateTime(value) {
    if(!value) return "";
    var parts = value.split(" ");
    if(parts.length < 2) return value;
    var date = parts[0].split("-");
    return date.length === 3 ? date[2]+"-"+date[1]+"-"+date[0]+" "+parts[1].substring(0,5) : value;
}

function destroyPsmTables() {
    psmTables.forEach(function(tableId) {
        var selector = "#" + tableId;
        if($.fn.DataTable.isDataTable(selector)) {
            $(selector).DataTable().destroy();
        }
    });
    psmTables = [];
}

function makeTable(tableId, order) {
    if($.fn.DataTable.isDataTable("#" + tableId)) {
        $("#" + tableId).DataTable().destroy();
    }
    if(psmTables.indexOf(tableId) === -1) {
        psmTables.push(tableId);
    }
    return $("#" + tableId).DataTable({
        destroy: true,
        ordering: true,
        responsive: false,
        scrollX: true,
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        dom: "Bfrtip",
        order: order || [],
        buttons: [
            "copyHtml5",
            { extend: "csvHtml5", exportOptions: { columns: ":visible" } },
            { extend: "excelHtml5", exportOptions: { columns: ":visible" } },
            { extend: "pdfHtml5", orientation: "landscape", pageSize: "LEGAL", download: "open", exportOptions: { columns: ":visible" } },
            { extend: "print", exportOptions: { columns: ":visible" } },
            "colvis"
        ]
    });
}

function noRecordHtml(message) {
    return "<div class='text-center text-danger font-weight-bold py-3'>" + escapeHtml(message || "No Record Found.") + "</div>";
}

// function renderAudit(rows) {
//     if(!rows || rows.length === 0) { $("#audit_div").html(noRecordHtml()); return; }
//     var html = "<table class='table table-bordered table-striped table-sm' id='psm_audit_table' style='font-size:12px; width:100%;'>";
//     html += "<thead><tr><th>Complaint ID</th><th>Date & Time</th><th>Officer ID</th><th>Officer Name</th><th>Login User ID</th><th>Mobile Number</th><th>Current Officer</th><th>Current Login</th><th>Status</th><th>Department</th><th>District</th><th>Remark</th></tr></thead><tbody>";
//     rows.forEach(function(row) {
//         html += "<tr>";
//         html += "<td>"+escapeHtml(row.CompId)+"</td>";
//         html += "<td>"+escapeHtml(formatDateTime(row.StDate))+"</td>";
//         html += "<td>"+escapeHtml(row.OfficerId)+"</td>";
//         html += "<td>"+escapeHtml(row.officername)+"</td>";
//         html += "<td>"+escapeHtml(row.loginuserid)+"</td>";
//         html += "<td>"+escapeHtml(row.officerno)+"</td>";
//         html += "<td>"+escapeHtml(row.CurrentOfficer)+"</td>";
//         html += "<td>"+escapeHtml(row.CurrentLogin)+"</td>";
//         html += "<td>"+escapeHtml(row.complaint_status || row.psm_status)+"</td>";
//         html += "<td>"+escapeHtml(row.department_name)+"</td>";
//         html += "<td>"+escapeHtml(row.district_name)+"</td>";
//         html += "<td class='remark-cell'>"+escapeHtml(row.Remarks)+"</td>";
//         html += "</tr>";
//     });
//     html += "</tbody></table>";
//     $("#audit_div").html(html);
//     makeTable("psm_audit_table", [[0, "asc"], [1, "asc"]]);
// }


function renderAudit(rows) {
    if (!rows || rows.length === 0) {
        $("#audit_div").html(noRecordHtml());
        return;
    }

    var html = "<table class='table table-bordered table-striped table-sm' id='psm_audit_table' style='font-size:12px; width:100%;'>";

    html += "<thead><tr>";
    html += "<th>Complaint ID</th>";
    html += "<th>Date & Time</th>";
    html += "<th>Officer Details</th>";
    html += "<th>Current Officer</th>";
    html += "<th>Status</th>";
    html += "<th>Department</th>";
    html += "</tr></thead><tbody>";

    rows.forEach(function (row) {

        var officerDetails =
            "<strong>" + escapeHtml(row.officername || '') + "</strong><br>" +
            escapeHtml(row.officerno || '');

        var currentOfficerDetails =
            "<strong>" + escapeHtml(row.CurrentOfficer || '') + "</strong><br>" +
            escapeHtml(row.CurrentLogin || '');

        html += "<tr>";
        html += "<td>" + escapeHtml(row.CompId) + "</td>";
        html += "<td>" + escapeHtml(formatDateTime(row.StDate)) + "</td>";
        html += "<td>" + officerDetails + "</td>";
        html += "<td>" + currentOfficerDetails + "</td>";
        html += "<td>" + escapeHtml(row.complaint_status || row.psm_status) + "</td>";
        html += "<td>" + escapeHtml(row.department_name) + "</td>";
        html += "</tr>";
    });

    html += "</tbody></table>";

    $("#audit_div").html(html);

    makeTable("psm_audit_table", [[0, "asc"], [1, "asc"]]);
}
// function renderHistory(rows) {
//     if(!rows || rows.length === 0) { $("#history_div").html(noRecordHtml("No repeated PSM complaint found.")); return; }
//     var html = "<table class='table table-bordered table-striped table-sm' id='psm_history_table' style='font-size:12px; width:100%;'>";
//     html += "<thead><tr><th>Complaint ID</th><th>Total PSM</th><th>Unique PSM Officers</th><th>Same Officer Repeated</th><th>Current Officer</th><th>Current Login</th><th>Current Mobile</th><th>Status</th><th>Department</th><th>District</th><th>PSM History</th></tr></thead><tbody>";
//     rows.forEach(function(row) {
//         html += "<tr>";
//         html += "<td>"+escapeHtml(row.CompId)+"</td>";
//         html += "<td>"+escapeHtml(row.Total_PSM)+"</td>";
//         html += "<td>"+escapeHtml(row.Unique_PSM_Officers)+"</td>";
//         html += "<td>"+escapeHtml(row.Same_Officer_Repeated)+"</td>";
//         html += "<td>"+escapeHtml(row.CurrentOfficer)+"</td>";
//         html += "<td>"+escapeHtml(row.CurrentLogin)+"</td>";
//         html += "<td>"+escapeHtml(row.CurrentMobile)+"</td>";
//         html += "<td>"+escapeHtml(row.complaint_status)+"</td>";
//         html += "<td>"+escapeHtml(row.department_name)+"</td>";
//         html += "<td>"+escapeHtml(row.district_name)+"</td>";
//         html += "<td class='history-cell'>"+escapeHtml(row.PSM_History)+"</td>";
//         html += "</tr>";
//     });
//     html += "</tbody></table>";
//     $("#history_div").html(html);
//     makeTable("psm_history_table", [[1, "desc"]]);
// }


function renderHistory(rows) {
    if(!rows || rows.length === 0) { $("#history_div").html(noRecordHtml("No repeated PSM complaint found.")); return; }
    var html = "<table class='table table-bordered table-striped table-sm' id='psm_history_table' style='font-size:12px; width:100%;'>";
    html += "<thead><tr>\
        <th>Complaint ID</th>\
        <th>Total PSM</th>\
        <th>Unique PSM Officers</th>\
        <th>Same Officer Repeated</th>\
        <th>Current Officer</th>\
        <th>Current Login</th>\
        <th>Current Mobile</th>\
        <th>Status</th>\
        <th>Department</th>\
        <th>PSM History</th>\
        </tr></thead><tbody>";
    rows.forEach(function(row, index) {

    var historyId = "history_" + index;

    html += "<tr>";
    html += "<td>"+escapeHtml(row.CompId)+"</td>";
    html += "<td>"+escapeHtml(row.Total_PSM)+"</td>";
    html += "<td>"+escapeHtml(row.Unique_PSM_Officers)+"</td>";
    html += "<td>"+escapeHtml(row.Same_Officer_Repeated)+"</td>";
    html += "<td>"+escapeHtml(row.CurrentOfficer)+"</td>";
    html += "<td>"+escapeHtml(row.CurrentLogin)+"</td>";
    html += "<td>"+escapeHtml(row.CurrentMobile)+"</td>";
    html += "<td>"+escapeHtml(row.complaint_status)+"</td>";
    html += "<td>"+escapeHtml(row.department_name)+"</td>";

    html += "<td style='text-align:center;'>\
                <button type='button' class='btn btn-sm btn-info toggle-history' data-target='"+historyId+"'>\
                    ▼ View\
                </button>\
             </td>";

    html += "</tr>";

    html += "<tr id='"+historyId+"' class='history-row' style='display:none;'>\
                <td colspan='10' style='background:#f8f9fa;'>\
                    <div style='padding:10px;white-space:normal;word-break:break-word;'>"
                        + escapeHtml(row.PSM_History) +
                    "</div>\
                </td>\
             </tr>";
});
    $("#history_div").html(html);

    $("#history_div").off("click", ".toggle-history");

$("#history_div").on("click", ".toggle-history", function () {

    var target = $(this).data("target");

    $("#" + target).toggle();

    if ($("#" + target).is(":visible")) {
        $(this).html("▲ Hide");
    } else {
        $(this).html("▼ View");
    }
});
    makeTable("psm_history_table", [[1, "desc"]]);
}

function renderHistory(rows) {
    if(!rows || rows.length === 0) { $("#history_div").html(noRecordHtml("No repeated PSM complaint found.")); return; }
    var html = "<table class='table table-bordered table-striped table-sm' id='psm_history_table' style='font-size:12px; width:100%;'>";
    html += "<thead><tr>\
        <th>Complaint ID</th>\
        <th>Total PSM</th>\
        <th>Unique PSM Officers</th>\
        <th>Same Officer Repeated</th>\
        <th>First PSM</th>\
        <th>Last PSM</th>\
        <th>Age</th>\
        <th>Current Officer</th>\
        <th>Status</th>\
        <th>Department</th>\
        <th>Journey</th>\
        </tr></thead><tbody>";
    rows.forEach(function(row) {
        var officerDetails =
            "<strong>" + escapeHtml(row.CurrentOfficer || "-") + "</strong><br>" +
            "<span class='text-muted'>" + escapeHtml(row.CurrentLogin || "") + "</span>";
        var ageText = row.Complaint_Age_Days ? row.Complaint_Age_Days + " day(s)" : "-";

        html += "<tr>";
        html += "<td>"+escapeHtml(row.CompId)+"</td>";
        html += "<td><span class='badge badge-warning'>"+escapeHtml(row.Total_PSM)+"</span></td>";
        html += "<td>"+escapeHtml(row.Unique_PSM_Officers)+"</td>";
        html += "<td>"+escapeHtml(row.Same_Officer_Repeated)+"</td>";
        html += "<td>"+escapeHtml(formatDateTime(row.First_PSM_Date))+"</td>";
        html += "<td>"+escapeHtml(formatDateTime(row.Last_PSM_Date))+"</td>";
        html += "<td>"+escapeHtml(ageText)+"</td>";
        html += "<td>"+officerDetails+"</td>";
        html += "<td>"+escapeHtml(row.complaint_status || "-")+"</td>";
        html += "<td>"+escapeHtml(row.department_name || "-")+"</td>";
        html += "<td class='history-cell'><button type='button' class='btn btn-sm btn-info view-psm-timeline' data-complaint='"+escapeHtml(row.CompId)+"'><i class='fa fa-stream'></i> View Timeline</button></td>";
        html += "</tr>";
    });
    html += "</tbody></table>";
    $("#history_div").html(html);

    $("#history_div").off("click", ".view-psm-timeline");
    $("#history_div").on("click", ".view-psm-timeline", function () {
        openPsmTimeline($(this).data("complaint"));
    });
    makeTable("psm_history_table", [[1, "desc"]]);
}

function timelineStat(label, value) {
    return "<div class='psm-timeline-stat'><span>"+escapeHtml(label)+"</span><strong>"+escapeHtml(value || "-")+"</strong></div>";
}

function renderPsmTimelineSummary(summary, complaintNo) {
    summary = summary || {};
    var ageText = summary.Complaint_Age_Days ? summary.Complaint_Age_Days + " day(s)" : "-";
    var html = "<div class='psm-timeline-summary'>";
    html += timelineStat("Complaint", complaintNo);
    html += timelineStat("Current Status", summary.complaint_status);
    html += timelineStat("Total PSM", summary.Total_PSM);
    html += timelineStat("Officers Involved", summary.Unique_PSM_Officers);
    html += timelineStat("Current Owner", summary.CurrentOfficer);
    html += timelineStat("Department", summary.department_name);
    html += timelineStat("Complaint Age", ageText);
    html += timelineStat("Last Movement", formatDateTime(summary.Last_PSM_Date));
    html += "</div>";
    $("#psm_timeline_summary").html(html);
}

function renderPsmTimeline(events, summary, complaintNo) {
    renderPsmTimelineSummary(summary, complaintNo);
    if(!events || events.length === 0) {
        $("#psm_timeline_body").html(noRecordHtml("No PSM movement found for this complaint."));
        return;
    }

    var html = "<div class='psm-timeline'>";
    events.forEach(function(row, index) {
        var title = index === 0 ? "Complaint Registered / First PSM Action" : "PSM to Another Officer";
        var status = row.psm_status || row.complaint_status || "-";
        html += "<div class='psm-timeline-item psm-action'>";
        html += "<div class='d-flex justify-content-between flex-wrap'><div class='psm-timeline-title'>"+escapeHtml(title)+"</div><div class='psm-timeline-time'>"+escapeHtml(formatDateTime(row.StDate))+"</div></div>";
        html += "<div class='psm-timeline-meta'>";
        html += "<div><b>Officer:</b> "+escapeHtml(row.officername || "-")+"</div>";
        html += "<div><b>Login:</b> "+escapeHtml(row.loginuserid || "-")+"</div>";
        html += "<div><b>Department:</b> "+escapeHtml(row.department_name || "-")+"</div>";
        html += "<div><b>Status:</b> "+escapeHtml(status)+"</div>";
        html += "</div>";
        if(row.Remarks) {
            html += "<div class='psm-timeline-remark'><b>Remark:</b> "+escapeHtml(row.Remarks)+"</div>";
        }
        html += "</div>";
    });

    if(summary && (summary.CurrentOfficer || summary.complaint_status)) {
        html += "<div class='psm-timeline-item current-owner'>";
        html += "<div class='psm-timeline-title'>Assigned to Current Officer</div>";
        html += "<div class='psm-timeline-meta'>";
        html += "<div><b>Officer:</b> "+escapeHtml(summary.CurrentOfficer || "-")+"</div>";
        html += "<div><b>Login:</b> "+escapeHtml(summary.CurrentLogin || "-")+"</div>";
        html += "<div><b>Department:</b> "+escapeHtml(summary.department_name || "-")+"</div>";
        html += "<div><b>Current Status:</b> "+escapeHtml(summary.complaint_status || "-")+"</div>";
        html += "</div></div>";
    }
    html += "</div>";
    $("#psm_timeline_body").html(html);
}

function openPsmTimeline(complaintNo) {
    if(!complaintNo) return;
    $("#psm_timeline_summary").html("");
    $("#psm_timeline_body").html("<div class='text-center text-primary font-weight-bold py-3'><i class='fa fa-spinner fa-spin'></i> Loading complaint timeline...</div>");
    $("#psm_timeline_modal").modal("show");

    if(psmHistoryCache[complaintNo]) {
        renderPsmTimeline(psmHistoryCache[complaintNo].all_record, psmHistoryCache[complaintNo].summary, complaintNo);
        return;
    }

    var payload = {};
    payload[csrfName] = csrfHash;
    payload.complaint_no = complaintNo;

    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: baseUrl + "app/reports/get_psm_complaint_timeline",
        data: payload,
        cache: false,
        success: function(data) {
            if(data.response === true) {
                psmHistoryCache[complaintNo] = data;
                renderPsmTimeline(data.all_record || [], data.summary || {}, complaintNo);
            } else {
                $("#psm_timeline_body").html(noRecordHtml(data.message));
            }
        },
        error: function() {
            $("#psm_timeline_body").html(noRecordHtml("Failed to load complaint timeline."));
        }
    });
}

function renderSimpleTable(target, tableId, columns, rows, order) {
    if(!rows || rows.length === 0) { $("#" + target).html(noRecordHtml()); return; }
    var html = "<table class='table table-bordered table-striped table-sm' id='"+tableId+"' style='font-size:12px; width:100%;'><thead><tr>";
    columns.forEach(function(col) { html += "<th>"+escapeHtml(col.label)+"</th>"; });
    html += "</tr></thead><tbody>";
    rows.forEach(function(row) {
        html += "<tr>";
        columns.forEach(function(col) { html += "<td>"+escapeHtml(row[col.key])+"</td>"; });
        html += "</tr>";
    });
    html += "</tbody></table>";
    $("#" + target).html(html);
    makeTable(tableId, order);
}

function renderSameOfficerRepeated(rows) {
    if(!rows || rows.length === 0) { $("#same_officer_div").html(noRecordHtml("No same-officer repeated PSM found.")); return; }
    var html = "<table class='table table-bordered table-striped table-sm' id='psm_same_officer_table' style='font-size:12px; width:100%;'>";
    html += "<thead><tr><th>Complaint ID</th><th>Officer ID</th><th>Officer Name</th><th>Login User ID</th><th>Mobile Number</th><th>PSM Count</th><th>First PSM Date</th><th>Last PSM Date</th></tr></thead><tbody>";
    rows.forEach(function(row) {
        html += "<tr>";
        html += "<td>"+escapeHtml(row.CompId)+"</td>";
        html += "<td>"+escapeHtml(row.OfficerId)+"</td>";
        html += "<td>"+escapeHtml(row.officername)+"</td>";
        html += "<td>"+escapeHtml(row.loginuserid)+"</td>";
        html += "<td>"+escapeHtml(row.officerno)+"</td>";
        html += "<td>"+escapeHtml(row.PSM_Count)+"</td>";
        html += "<td>"+escapeHtml(formatDateTime(row.First_PSM_Date))+"</td>";
        html += "<td>"+escapeHtml(formatDateTime(row.Last_PSM_Date))+"</td>";
        html += "</tr>";
    });
    html += "</tbody></table>";
    $("#same_officer_div").html(html);
    makeTable("psm_same_officer_table", [[5, "desc"]]);
}

var psmReportConfig = {
    audit: {
        label: "Detailed Audit",
        div: "audit_div",
        table: "psm_audit_table",
        render: function(rows) { renderAudit(rows); }
    },
    history: {
        label: "Complete History",
        div: "history_div",
        table: "psm_history_table",
        render: function(rows) { renderHistory(rows); }
    },
    officer: {
        label: "Officer Wise",
        div: "officer_div",
        table: "psm_officer_table",
        render: function(rows) {
            renderSimpleTable("officer_div", "psm_officer_table", [
                { label: "Officer ID", key: "officerid" },
                { label: "Officer Name", key: "officername" },
                { label: "Login User ID", key: "loginuserid" },
                { label: "Mobile Number", key: "officerno" },
                { label: "Total PSM", key: "Total_PSM" },
                { label: "Unique Complaints", key: "Unique_Complaints" }
            ], rows, [[4, "desc"]]);
        }
    },
    department: {
        label: "Department Wise",
        div: "department_div",
        table: "psm_department_table",
        render: function(rows) {
            renderSimpleTable("department_div", "psm_department_table", [
                { label: "Department", key: "Departname_E" },
                { label: "Total PSM", key: "Total_PSM" },
                { label: "Total Complaints", key: "Total_Complaints" }
            ], rows, [[1, "desc"]]);
        }
    },
    repeated: {
        label: "Top Repeated",
        div: "repeated_div",
        table: "psm_repeated_table",
        render: function(rows) {
            renderSimpleTable("repeated_div", "psm_repeated_table", [
                { label: "Complaint ID", key: "CompId" },
                { label: "Total PSM", key: "Total_PSM" },
                { label: "Current Officer", key: "CurrentOfficer" },
                { label: "Status", key: "complaint_status" }
            ], rows, [[1, "desc"]]);
        }
    },
    same_officer: {
        label: "Same Officer Repeated",
        div: "same_officer_div",
        table: "psm_same_officer_table",
        render: function(rows) { renderSameOfficerRepeated(rows); }
    },
    complaint_summary: {
        label: "Complaint Summary",
        div: "complaint_summary_div",
        table: "psm_complaint_summary_table",
        render: function(rows) {
            renderSimpleTable("complaint_summary_div", "psm_complaint_summary_table", [
                { label: "Complaint ID", key: "CompId" },
                { label: "Total PSM Count", key: "Total_PSM" },
                { label: "Officers Involved", key: "Officers" }
            ], rows, [[1, "desc"]]);
        }
    }
};

function filterSignature() {
    return JSON.stringify({
        from_date: $("#from_date").val(),
        to_date: $("#to_date").val(),
        complaint_no: $("#complaint_no").val(),
        department: $("#department").val(),
        officer: $("#officer").val(),
        current_officer: $("#current_officer").val(),
        login_user_id: $("#login_user_id").val(),
        complaint_status: $("#complaint_status").val(),
        district: $("#district").val()
    });
}

function destroyReportTable(reportType) {
    var config = psmReportConfig[reportType];
    if(config && $.fn.DataTable.isDataTable("#" + config.table)) {
        $("#" + config.table).DataTable().destroy();
    }
}

function promptHtml(reportType) {
    var label = psmReportConfig[reportType].label;
    return "<div class='text-center text-muted font-weight-bold py-3'>Click <b>"+escapeHtml(label)+"</b> or press Refresh to load this report.</div>";
}

function updateLazyMetrics(reportType, rowCount) {
    var cachedCount = Object.keys(psmTabCache).length;
    $("#metric_active_report").text(psmReportConfig[reportType] ? psmReportConfig[reportType].label : "-");
    $("#metric_loaded_rows").text(rowCount === null || rowCount === undefined ? "-" : rowCount);
    $("#metric_cached_tabs").text(cachedCount);
    $("#metric_filter_state").text("Ready");
}

function adjustActiveTable(reportType) {
    var config = psmReportConfig[reportType];
    if(config && $.fn.DataTable.isDataTable("#" + config.table)) {
        setTimeout(function() {
            $("#" + config.table).DataTable().columns.adjust();
        }, 80);
    }
}

function clearReportCache(showPrompts) {
    destroyPsmTables();
    psmTabCache = {};
    psmHistoryCache = {};
    Object.keys(psmReportConfig).forEach(function(reportType) {
        $("#" + psmReportConfig[reportType].div).html(showPrompts ? promptHtml(reportType) : "");
    });
    updateLazyMetrics(activeReportType, null);
}

function loadPsmReportTab(reportType, forceRefresh) {
    var config = psmReportConfig[reportType];
    if(!config) return;

    activeReportType = reportType;
    var newSignature = filterSignature();
    if(currentFilterSignature !== newSignature) {
        currentFilterSignature = newSignature;
        clearReportCache(true);
    }

    if(psmTabCache[reportType] && forceRefresh !== true) {
        updateLazyMetrics(reportType, psmTabCache[reportType].rowCount);
        adjustActiveTable(reportType);
        return;
    }

    destroyReportTable(reportType);
    $("#" + config.div).html("<div class='text-center text-primary font-weight-bold py-3'><i class='fa fa-spinner fa-spin'></i> Loading "+escapeHtml(config.label)+"...</div>");

    var payload = filterPayload();
    payload.report_type = reportType;

    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: baseUrl + "app/reports/get_psm_report_tab",
        data: payload,
        cache: false,
        success: function(data) {
            if(data.response === true) {
                var rows = data.all_record || [];
                config.render(rows);
                psmTabCache[reportType] = { rowCount: rows.length };
                updateLazyMetrics(reportType, rows.length);
                adjustActiveTable(reportType);
            } else {
                $("#" + config.div).html(noRecordHtml(data.message));
                psmTabCache[reportType] = { rowCount: 0 };
                updateLazyMetrics(reportType, 0);
            }
        },
        error: function() {
            $("#" + config.div).html(noRecordHtml("Failed to load "+config.label+"."));
            updateLazyMetrics(reportType, 0);
        }
    });
}

function refreshCurrentReport() {
    delete psmTabCache[activeReportType];
    loadPsmReportTab(activeReportType, true);
}

function handlePsmFilterChange() {
    var hadLoadedTabs = Object.keys(psmTabCache).length > 0;
    currentFilterSignature = filterSignature();
    clearReportCache(true);
    $("#metric_filter_state").text("Changed");
    if(hadLoadedTabs) {
        loadPsmReportTab(activeReportType, true);
    }
}

function resetPsmFilters() {
    $("#from_date,#to_date,#complaint_no,#login_user_id").val("");
    $("#department,#officer,#current_officer,#district").val("").trigger("change.select2");
    $("#complaint_status").val("");
    currentFilterSignature = filterSignature();
    clearReportCache(true);
}

$(".select2").select2({ theme: "bootstrap4", width: "100%" });
currentFilterSignature = filterSignature();
clearReportCache(true);

$("#psm_tabs a[data-report]").on("click", function() {
    if($(this).hasClass("active")) {
        loadPsmReportTab($(this).data("report"), false);
    }
});

$("#psm_tabs a[data-report]").on("shown.bs.tab", function() {
    loadPsmReportTab($(this).data("report"), false);
});

$("#from_date,#to_date,#department,#officer,#current_officer,#district,#complaint_status").on("change", handlePsmFilterChange);
$("#complaint_no,#login_user_id").on("change", handlePsmFilterChange);
</script>
