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
.lower-page .filter-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(160px, 1fr));
    gap: 10px;
}
.lower-page .filter-field label {
    display: block;
    margin-bottom: 4px;
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    color: #6b7280;
}
.lower-page .metric-grid {
    display: grid;
    grid-template-columns: repeat(5, minmax(130px, 1fr));
    gap: 12px;
    margin: 14px 0;
}
.lower-page .metric-box {
    border: 1px solid #dce3ec;
    border-radius: 8px;
    padding: 12px;
    background: #fff;
}
.lower-page .report-card {
    cursor: pointer;
    transition: border-color .15s ease, box-shadow .15s ease, transform .15s ease;
}
.lower-page .report-card:hover,
.lower-page .report-card.active {
    border-color: #2563eb;
    box-shadow: 0 6px 16px rgba(37, 99, 235, .12);
    transform: translateY(-1px);
}
.lower-page .metric-label {
    color: #64748b;
    font-size: 12px;
    font-weight: 800;
    text-transform: uppercase;
}
.lower-page .metric-value {
    margin-top: 6px;
    color: #123047;
    font-size: 24px;
    line-height: 1;
    font-weight: 900;
}
.lower-page .history-cell {
    min-width: 160px;
    text-align: center;
}
.lower-page .remark-cell {
    min-width: 320px;
    max-width: 520px;
    white-space: normal;
}
.lower-page .table-sm td,
.lower-page .table-sm th {
    vertical-align: middle;
}
.lower-timeline-summary {
    display: grid;
    grid-template-columns: repeat(4, minmax(120px, 1fr));
    gap: 10px;
    margin-bottom: 14px;
}
.lower-timeline-stat {
    border: 1px solid #dbe5ef;
    border-radius: 8px;
    background: #f8fafc;
    padding: 10px;
}
.lower-timeline-stat span {
    display: block;
    color: #64748b;
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
}
.lower-timeline-stat strong {
    display: block;
    margin-top: 4px;
    color: #123047;
    font-size: 16px;
}
.lower-timeline {
    position: relative;
    padding-left: 28px;
}
.lower-timeline:before {
    content: "";
    position: absolute;
    top: 8px;
    bottom: 8px;
    left: 11px;
    width: 2px;
    background: #d6e0ea;
}
.lower-timeline-item {
    position: relative;
    margin-bottom: 14px;
    border: 1px solid #dbe5ef;
    border-radius: 8px;
    background: #fff;
    padding: 12px 14px;
}
.lower-timeline-item:before {
    content: "";
    position: absolute;
    top: 16px;
    left: -24px;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    border: 3px solid #fff;
    background: #0ea5e9;
    box-shadow: 0 0 0 2px #0ea5e9;
}
.lower-timeline-item.lower-action:before { background: #f59e0b; box-shadow: 0 0 0 2px #f59e0b; }
.lower-timeline-item.current-owner:before { background: #16a34a; box-shadow: 0 0 0 2px #16a34a; }
.lower-timeline-title {
    color: #123047;
    font-size: 14px;
    font-weight: 900;
}
.lower-timeline-time {
    color: #64748b;
    font-size: 12px;
    font-weight: 700;
}
.lower-timeline-meta {
    display: grid;
    grid-template-columns: repeat(2, minmax(160px, 1fr));
    gap: 6px 14px;
    margin-top: 8px;
    color: #334155;
    font-size: 13px;
}
.lower-timeline-remark {
    margin-top: 8px;
    color: #475569;
    line-height: 1.45;
}
@media(max-width: 1200px) {
    .lower-page .metric-grid { grid-template-columns: repeat(3, minmax(130px, 1fr)); }
}
@media(max-width: 1100px) {
    .lower-page .filter-grid { grid-template-columns: repeat(2, minmax(160px, 1fr)); }
    .lower-timeline-summary { grid-template-columns: repeat(2, minmax(120px, 1fr)); }
    .lower-timeline-meta { grid-template-columns: 1fr; }
}
@media(max-width: 640px) {
    .lower-page .filter-grid,
    .lower-page .metric-grid,
    .lower-timeline-summary { grid-template-columns: 1fr; }
}
</style>

<div class="content-wrapper lower-page">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url(); ?>app/home">Home</a></li>
                        <li class="breadcrumb-item active">Send to Lower Level Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header">
                    <h5 class="m-0"><b>Send to Lower Level Dashboard</b></h5>
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
                                <option value="">All Sending Officers</option>
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
                            <button type="button" class="btn btn-sm btn-secondary" onclick="resetLowerFilters();"><i class="fas fa-undo"></i> Reset</button>
                        </div>
                    </div>

                    <div class="metric-grid">
                        <div class="metric-box report-card active" data-report="officer">
                            <div class="metric-label">Officer Wise</div>
                            <div class="metric-value" id="card_officer">Click</div>
                        </div>
                        <div class="metric-box report-card" data-report="same_officer">
                            <div class="metric-label">Same Officer Repeated</div>
                            <div class="metric-value" id="card_same_officer">Click</div>
                        </div>
                        <div class="metric-box report-card" data-report="complaint_summary">
                            <div class="metric-label">Complaint Summary</div>
                            <div class="metric-value" id="card_complaint_summary">Click</div>
                        </div>
                        <div class="metric-box report-card" data-report="audit">
                            <div class="metric-label">Detailed Audit</div>
                            <div class="metric-value" id="card_audit">Click</div>
                        </div>
                        <div class="metric-box report-card" data-report="history">
                            <div class="metric-label">Complete History</div>
                            <div class="metric-value" id="card_history">Click</div>
                        </div>
                    </div>

                    <ul class="nav nav-tabs" id="lower_tabs" role="tablist">
                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" data-report="officer" href="#officer_tab">Officer Wise</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" data-report="same_officer" href="#same_officer_tab">Same Officer Repeated</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" data-report="complaint_summary" href="#complaint_summary_tab">Complaint Summary</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" data-report="audit" href="#audit_tab">Detailed Audit</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" data-report="history" href="#history_tab">Complete History</a></li>
                    </ul>

                    <div class="tab-content pt-3">
                        <div class="tab-pane fade show active" id="officer_tab"><div id="officer_div"></div></div>
                        <div class="tab-pane fade" id="same_officer_tab"><div id="same_officer_div"></div></div>
                        <div class="tab-pane fade" id="complaint_summary_tab"><div id="complaint_summary_div"></div></div>
                        <div class="tab-pane fade" id="audit_tab"><div id="audit_div"></div></div>
                        <div class="tab-pane fade" id="history_tab"><div id="history_div"></div></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="lower_timeline_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>Send to Lower Level Timeline</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="lower_timeline_summary"></div>
                <div id="lower_timeline_body"></div>
            </div>
        </div>
    </div>
</div>

<script>
var baseUrl = "<?php echo base_url(); ?>";
var csrfName = "<?php echo $this->security->get_csrf_token_name(); ?>";
var csrfHash = "<?php echo $this->security->get_csrf_hash(); ?>";
var lowerTables = [];
var lowerTabCache = {};
var lowerHistoryCache = {};
var currentFilterSignature = "";
var activeReportType = "officer";

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

function formatDateTime(value) {
    if(!value) return "";
    var parts = value.split(" ");
    if(parts.length < 2) return value;
    var date = parts[0].split("-");
    return date.length === 3 ? date[2]+"-"+date[1]+"-"+date[0]+" "+parts[1].substring(0,5) : value;
}

function destroyLowerTables() {
    lowerTables.forEach(function(tableId) {
        if($.fn.DataTable.isDataTable("#" + tableId)) {
            $("#" + tableId).DataTable().destroy();
        }
    });
    lowerTables = [];
}

function destroyReportTable(reportType) {
    var config = lowerReportConfig[reportType];
    if(config && $.fn.DataTable.isDataTable("#" + config.table)) {
        $("#" + config.table).DataTable().destroy();
    }
}

function makeTable(tableId, order) {
    if($.fn.DataTable.isDataTable("#" + tableId)) {
        $("#" + tableId).DataTable().destroy();
    }
    if(lowerTables.indexOf(tableId) === -1) {
        lowerTables.push(tableId);
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

function promptHtml(reportType) {
    return "<div class='text-center text-muted font-weight-bold py-3'>Click <b>"+escapeHtml(lowerReportConfig[reportType].label)+"</b> or press Refresh to load this report.</div>";
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

function renderAudit(rows) {
    if(!rows || rows.length === 0) { $("#audit_div").html(noRecordHtml()); return; }
    var html = "<table class='table table-bordered table-striped table-sm' id='lower_audit_table' style='font-size:12px; width:100%;'>";
    html += "<thead><tr><th>Complaint ID</th><th>Date & Time</th><th>Status Remark ID</th><th>Officer ID</th><th>Officer Name</th><th>Login User ID</th><th>Mobile Number</th><th>Current Officer</th><th>Status</th><th>Department</th><th>District</th><th>Remark</th></tr></thead><tbody>";
    rows.forEach(function(row) {
        html += "<tr>";
        html += "<td>"+escapeHtml(row.CompId)+"</td>";
        html += "<td>"+escapeHtml(formatDateTime(row.StDate))+"</td>";
        html += "<td>"+escapeHtml(row.StatusRemarkId)+"</td>";
        html += "<td>"+escapeHtml(row.OfficerId)+"</td>";
        html += "<td>"+escapeHtml(row.officername)+"</td>";
        html += "<td>"+escapeHtml(row.loginuserid)+"</td>";
        html += "<td>"+escapeHtml(row.officerno)+"</td>";
        html += "<td>"+escapeHtml(row.CurrentOfficer)+"</td>";
        html += "<td>"+escapeHtml(row.complaint_status)+"</td>";
        html += "<td>"+escapeHtml(row.department_name)+"</td>";
        html += "<td>"+escapeHtml(row.district_name)+"</td>";
        html += "<td class='remark-cell'>"+escapeHtml(row.Remarks)+"</td>";
        html += "</tr>";
    });
    html += "</tbody></table>";
    $("#audit_div").html(html);
    makeTable("lower_audit_table", [[0, "asc"], [1, "asc"]]);
}

function renderHistory(rows) {
    if(!rows || rows.length === 0) { $("#history_div").html(noRecordHtml("No send-to-lower history found.")); return; }
    var html = "<table class='table table-bordered table-striped table-sm' id='lower_history_table' style='font-size:12px; width:100%;'>";
    html += "<thead><tr><th>Complaint ID</th><th>Total Actions</th><th>Officers Involved</th><th>First Action</th><th>Last Action</th><th>Age</th><th>Current Officer</th><th>Status</th><th>Department</th><th>District</th><th>Action History</th></tr></thead><tbody>";
    rows.forEach(function(row) {
        var officerDetails =
            "<strong>" + escapeHtml(row.CurrentOfficer || "-") + "</strong><br>" +
            "<span class='text-muted'>" + escapeHtml(row.CurrentLogin || "") + "</span>";
        var ageText = row.Complaint_Age_Days ? row.Complaint_Age_Days + " day(s)" : "-";

        html += "<tr>";
        html += "<td>"+escapeHtml(row.CompId)+"</td>";
        html += "<td><span class='badge badge-warning'>"+escapeHtml(row.Total_Reverted)+"</span></td>";
        html += "<td>"+escapeHtml(row.Unique_Officers || "-")+"</td>";
        html += "<td>"+escapeHtml(formatDateTime(row.First_Action_Date))+"</td>";
        html += "<td>"+escapeHtml(formatDateTime(row.Last_Action_Date))+"</td>";
        html += "<td>"+escapeHtml(ageText)+"</td>";
        html += "<td>"+officerDetails+"</td>";
        html += "<td>"+escapeHtml(row.complaint_status || "-")+"</td>";
        html += "<td>"+escapeHtml(row.department_name || "-")+"</td>";
        html += "<td>"+escapeHtml(row.district_name || "-")+"</td>";
        html += "<td class='history-cell'><button type='button' class='btn btn-sm btn-info view-lower-timeline' data-complaint='"+escapeHtml(row.CompId)+"'><i class='fa fa-stream'></i> View Timeline</button></td>";
        html += "</tr>";
    });
    html += "</tbody></table>";
    $("#history_div").html(html);

    $("#history_div").off("click", ".view-lower-timeline");
    $("#history_div").on("click", ".view-lower-timeline", function () {
        openLowerTimeline($(this).data("complaint"));
    });
    makeTable("lower_history_table", [[1, "desc"]]);
}

function lowerTimelineStat(label, value) {
    return "<div class='lower-timeline-stat'><span>"+escapeHtml(label)+"</span><strong>"+escapeHtml(value || "-")+"</strong></div>";
}

function officerLabel(row) {
    if(!row) return "-";
    var name = row.officername || row.CurrentOfficer || "-";
    var login = row.loginuserid || row.CurrentLogin || "";
    return login ? name + " (" + login + ")" : name;
}

function renderLowerTimelineSummary(summary, complaintNo) {
    summary = summary || {};
    var ageText = summary.Complaint_Age_Days ? summary.Complaint_Age_Days + " day(s)" : "-";
    var html = "<div class='lower-timeline-summary'>";
    html += lowerTimelineStat("Complaint", complaintNo);
    html += lowerTimelineStat("Current Status", summary.complaint_status);
    html += lowerTimelineStat("Total Actions", summary.Total_Reverted);
    html += lowerTimelineStat("Officers Involved", summary.Unique_Officers);
    html += lowerTimelineStat("Current Owner", summary.CurrentOfficer);
    html += lowerTimelineStat("Department", summary.department_name);
    html += lowerTimelineStat("Complaint Age", ageText);
    html += lowerTimelineStat("Last Action", formatDateTime(summary.Last_Action_Date));
    html += "</div>";
    $("#lower_timeline_summary").html(html);
}

function renderLowerTimeline(events, summary, complaintNo) {
    renderLowerTimelineSummary(summary, complaintNo);
    if(!events || events.length === 0) {
        $("#lower_timeline_body").html(noRecordHtml("No send-to-lower movement found for this complaint."));
        return;
    }

    var html = "<div class='lower-timeline'>";
    events.forEach(function(row, index) {
        var nextRow = events[index + 1] || null;
        var toOfficer = nextRow ? officerLabel(nextRow) : officerLabel({
            CurrentOfficer: summary ? summary.CurrentOfficer : "",
            CurrentLogin: summary ? summary.CurrentLogin : ""
        });
        var actionStatus = row.action_status || row.complaint_status || "-";

        html += "<div class='lower-timeline-item lower-action'>";
        html += "<div class='d-flex justify-content-between flex-wrap'><div class='lower-timeline-title'>Send to Lower Level</div><div class='lower-timeline-time'>"+escapeHtml(formatDateTime(row.StDate))+"</div></div>";
        html += "<div class='lower-timeline-meta'>";
        html += "<div><b>Complaint ID:</b> "+escapeHtml(row.CompId || complaintNo)+"</div>";
        html += "<div><b>Status Remark ID:</b> "+escapeHtml(row.StatusRemarkId || "-")+"</div>";
        html += "<div><b>From Officer:</b> "+escapeHtml(officerLabel(row))+"</div>";
        html += "<div><b>To Officer:</b> "+escapeHtml(toOfficer)+"</div>";
        html += "<div><b>Department:</b> "+escapeHtml(row.department_name || "-")+"</div>";
        html += "<div><b>Status:</b> "+escapeHtml(actionStatus)+"</div>";
        html += "</div>";
        if(row.Remarks) {
            html += "<div class='lower-timeline-remark'><b>Remarks:</b> "+escapeHtml(row.Remarks)+"</div>";
        }
        html += "</div>";
    });

    if(summary && (summary.CurrentOfficer || summary.complaint_status)) {
        html += "<div class='lower-timeline-item current-owner'>";
        html += "<div class='lower-timeline-title'>Current Complaint Owner</div>";
        html += "<div class='lower-timeline-meta'>";
        html += "<div><b>Officer:</b> "+escapeHtml(summary.CurrentOfficer || "-")+"</div>";
        html += "<div><b>Login:</b> "+escapeHtml(summary.CurrentLogin || "-")+"</div>";
        html += "<div><b>Department:</b> "+escapeHtml(summary.department_name || "-")+"</div>";
        html += "<div><b>Current Status:</b> "+escapeHtml(summary.complaint_status || "-")+"</div>";
        html += "</div></div>";
    }
    html += "</div>";
    $("#lower_timeline_body").html(html);
}

function openLowerTimeline(complaintNo) {
    if(!complaintNo) return;
    $("#lower_timeline_summary").html("");
    $("#lower_timeline_body").html("<div class='text-center text-primary font-weight-bold py-3'><i class='fa fa-spinner fa-spin'></i> Loading send-to-lower timeline...</div>");
    $("#lower_timeline_modal").modal("show");

    if(lowerHistoryCache[complaintNo]) {
        renderLowerTimeline(lowerHistoryCache[complaintNo].all_record, lowerHistoryCache[complaintNo].summary, complaintNo);
        return;
    }

    var payload = {};
    payload[csrfName] = csrfHash;
    payload.complaint_no = complaintNo;

    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: baseUrl + "app/reports/get_lower_level_complaint_timeline",
        data: payload,
        cache: false,
        success: function(data) {
            if(data.response === true) {
                lowerHistoryCache[complaintNo] = data;
                renderLowerTimeline(data.all_record || [], data.summary || {}, complaintNo);
            } else {
                $("#lower_timeline_body").html(noRecordHtml(data.message));
            }
        },
        error: function() {
            $("#lower_timeline_body").html(noRecordHtml("Failed to load send-to-lower timeline."));
        }
    });
}

var lowerReportConfig = {
    officer: {
        label: "Officer Wise",
        div: "officer_div",
        table: "lower_officer_table",
        card: "card_officer",
        render: function(rows) {
            renderSimpleTable("officer_div", "lower_officer_table", [
                { label: "Officer ID", key: "OfficerId" },
                { label: "Officer Name", key: "officername" },
                { label: "Login User ID", key: "loginuserid" },
                { label: "Mobile Number", key: "officerno" },
                { label: "Total Reverted", key: "Total_Reverted" }
            ], rows, [[4, "desc"]]);
        }
    },
    same_officer: {
        label: "Same Officer Repeated",
        div: "same_officer_div",
        table: "lower_same_officer_table",
        card: "card_same_officer",
        render: function(rows) {
            renderSimpleTable("same_officer_div", "lower_same_officer_table", [
                { label: "Complaint ID", key: "CompId" },
                { label: "Officer ID", key: "OfficerId" },
                { label: "Officer Name", key: "officername" },
                { label: "Login User ID", key: "loginuserid" },
                { label: "Mobile Number", key: "officerno" },
                { label: "Revert Count", key: "Revert_Count" }
            ], rows, [[5, "desc"]]);
        }
    },
    complaint_summary: {
        label: "Complaint Summary",
        div: "complaint_summary_div",
        table: "lower_complaint_summary_table",
        card: "card_complaint_summary",
        render: function(rows) {
            renderSimpleTable("complaint_summary_div", "lower_complaint_summary_table", [
                { label: "Complaint ID", key: "CompId" },
                { label: "Total Reverted", key: "Total_Reverted" },
                { label: "Officers Involved", key: "Officers" }
            ], rows, [[1, "desc"]]);
        }
    },
    audit: {
        label: "Detailed Audit",
        div: "audit_div",
        table: "lower_audit_table",
        card: "card_audit",
        render: function(rows) { renderAudit(rows); }
    },
    history: {
        label: "Complete History",
        div: "history_div",
        table: "lower_history_table",
        card: "card_history",
        render: function(rows) { renderHistory(rows); }
    }
};

function updateCards(reportType, rowCount) {
    $(".report-card").removeClass("active");
    $(".report-card[data-report='"+reportType+"']").addClass("active");
    if(lowerReportConfig[reportType]) {
        $("#" + lowerReportConfig[reportType].card).text(rowCount === null || rowCount === undefined ? "Click" : rowCount);
    }
}

function adjustActiveTable(reportType) {
    var config = lowerReportConfig[reportType];
    if(config && $.fn.DataTable.isDataTable("#" + config.table)) {
        setTimeout(function() {
            $("#" + config.table).DataTable().columns.adjust();
        }, 80);
    }
}

function clearReportCache(showPrompts) {
    destroyLowerTables();
    lowerTabCache = {};
    lowerHistoryCache = {};
    Object.keys(lowerReportConfig).forEach(function(reportType) {
        $("#" + lowerReportConfig[reportType].div).html(showPrompts ? promptHtml(reportType) : "");
        $("#" + lowerReportConfig[reportType].card).text("Click");
    });
}

function loadLowerReportTab(reportType, forceRefresh) {
    var config = lowerReportConfig[reportType];
    if(!config) return;

    activeReportType = reportType;
    var newSignature = filterSignature();
    if(currentFilterSignature !== newSignature) {
        currentFilterSignature = newSignature;
        clearReportCache(true);
    }

    if(lowerTabCache[reportType] && forceRefresh !== true) {
        updateCards(reportType, lowerTabCache[reportType].rowCount);
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
        url: baseUrl + "app/reports/get_lower_level_report_tab",
        data: payload,
        cache: false,
        success: function(data) {
            if(data.response === true) {
                var rows = data.all_record || [];
                config.render(rows);
                lowerTabCache[reportType] = { rowCount: rows.length };
                updateCards(reportType, rows.length);
                adjustActiveTable(reportType);
            } else {
                $("#" + config.div).html(noRecordHtml(data.message));
                lowerTabCache[reportType] = { rowCount: 0 };
                updateCards(reportType, 0);
            }
        },
        error: function() {
            $("#" + config.div).html(noRecordHtml("Failed to load "+config.label+"."));
            updateCards(reportType, 0);
        }
    });
}

function refreshCurrentReport() {
    delete lowerTabCache[activeReportType];
    loadLowerReportTab(activeReportType, true);
}

function handleLowerFilterChange() {
    var hadLoadedTabs = Object.keys(lowerTabCache).length > 0;
    currentFilterSignature = filterSignature();
    clearReportCache(true);
    if(hadLoadedTabs) {
        loadLowerReportTab(activeReportType, true);
    }
}

function resetLowerFilters() {
    $("#from_date,#to_date,#complaint_no,#login_user_id").val("");
    $("#department,#officer,#current_officer,#district").val("").trigger("change.select2");
    $("#complaint_status").val("");
    currentFilterSignature = filterSignature();
    clearReportCache(true);
}

$(".select2").select2({ theme: "bootstrap4", width: "100%" });
currentFilterSignature = filterSignature();
clearReportCache(true);

$("#lower_tabs a[data-report]").on("click", function() {
    if($(this).hasClass("active")) {
        loadLowerReportTab($(this).data("report"), false);
    }
});

$("#lower_tabs a[data-report]").on("shown.bs.tab", function() {
    loadLowerReportTab($(this).data("report"), false);
});

$(".report-card").on("click", function() {
    var reportType = $(this).data("report");
    var tabLink = $("#lower_tabs a[data-report='"+reportType+"']");
    if(tabLink.hasClass("active")) {
        loadLowerReportTab(reportType, false);
    } else {
        tabLink.tab("show");
    }
});

$("#from_date,#to_date,#department,#officer,#current_officer,#district,#complaint_status").on("change", handleLowerFilterChange);
$("#complaint_no,#login_user_id").on("change", handleLowerFilterChange);
</script>
