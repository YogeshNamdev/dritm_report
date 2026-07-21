<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<link href="<?php echo base_url(); ?>includes/plugins/advance_datatable/jquery.dataTables.min.css" rel="stylesheet">
<script src="<?php echo base_url(); ?>includes/plugins/advance_datatable/jquery.dataTables.min.js"></script>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url(); ?>app/home">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="dash-shell">
                <div class="dash-title-row">
                    <div>
                        <h4>Operations Dashboard</h4>
                        <p>Role-based productivity analytics for complaints, OWA, and report activity.</p>
                    </div>
                    <a href="javascript:void(0);" class="btn btn-success" onclick="exportDashboard();">
                        <i class="fas fa-file-excel mr-1"></i> Export Excel
                    </a>
                </div>

                <div class="dash-filter">
                    <div class="filter-field">
                        <label>Start Date</label>
                        <input type="date" id="start_date" class="form-control">
                    </div>
                    <div class="filter-field">
                        <label>End Date</label>
                        <input type="date" id="end_date" class="form-control">
                    </div>
                    <div class="filter-field">
                        <label>Agent</label>
                        <select id="agent_id" class="form-control">
                            <option value="0">All Agents</option>
                            <?php if($_SESSION["userdata"]["role_id"] == 1 && $agent_list != FALSE){ foreach($agent_list as $agent){ echo "<option value='".$agent->emp_id."'>".$agent->user_name." (".$agent->msd_id.")</option>"; }} ?>
                        </select>
                    </div>
                    <div class="filter-field">
                        <label>Department</label>
                        <select id="department" class="form-control">
                            <option value="0">All Departments</option>
                            <?php if($department_list != FALSE){ foreach($department_list as $department){ echo "<option value='".$department->Departid."'>".$department->Departname_E."</option>"; }} ?>
                        </select>
                    </div>
                    <div class="filter-field">
                        <label>Shift</label>
                        <select id="shift" class="form-control">
                            <option value="all">All Shifts</option>
                            <option value="morning">Morning Shift</option>
                            <option value="evening">Evening Shift</option>
                        </select>
                    </div>
                    <div class="filter-field">
                        <label>Report</label>
                        <select id="report" class="form-control">
                            <option value="all">All Reports</option>
                            <?php if($_SESSION["userdata"]["role_id"] != 3): ?>
                            <option value="callback">Callback Report</option>
                            <option value="name_change">Name Change Report</option>
                            <?php endif; ?>
                            <option value="complaint_creation">Complaint Creation</option>
                            <option value="high_complaint">High Rated / Status</option>
                            <option value="owa">OWA Report</option>
                            <option value="no_same">No/Same Resolution</option>
                            <option value="copy_paste">Copy/Paste Resolution</option>
                            <option value="direction">Direction Report</option>
                        </select>
                    </div>
                    <div class="filter-field">
                        <label>Attribute</label>
                        <select id="attribute" class="form-control">
                            <option value="0">All Attributes</option>
                            <?php if($attribute_list != FALSE){ foreach($attribute_list as $attribute){ echo "<option value='".$attribute->attribID."'>".$attribute->attribname_E."</option>"; }} ?>
                        </select>
                    </div>
                    <div class="filter-field">
                        <label>Status</label>
                        <select id="status" class="form-control">
                            <option value="all">All Status</option>
                            <option value="created">Created</option>
                            <option value="pending">Pending</option>
                            <option value="completed">Completed</option>
                            <option value="closed">Closed</option>
                            <option value="next_level">Move To Next Level</option>
                            <option value="high_rated">High Rated Tag</option>
                        </select>
                    </div>
                    <div class="filter-actions">
                        <button type="button" class="btn btn-primary" onclick="loadDashboard();">Apply</button>
                        <button type="button" class="btn btn-secondary" onclick="resetDashboardFilters();">Reset</button>
                    </div>
                </div>

                <div class="stat-grid">
                    <div class="stat-card"><span>Total Records</span><strong id="stat_total">0</strong></div>
                    <div class="stat-card"><span>Today's Records</span><strong id="stat_today">0</strong></div>
                    <div class="stat-card"><span>Pending</span><strong id="stat_pending">0</strong></div>
                    <div class="stat-card"><span>Completed</span><strong id="stat_completed">0</strong></div>
                    <div class="stat-card"><span>Updates / Remarks</span><strong id="stat_updates">0</strong></div>
                    <div class="stat-card"><span>Complaints Created</span><strong id="stat_complaints">0</strong></div>
                    <div class="stat-card"><span>High Rated Tag</span><strong id="stat_high_rated">0</strong></div>
                    <div class="stat-card"><span>Move Next Level</span><strong id="stat_next_level">0</strong></div>
                    <div class="stat-card"><span>Closed Complaints</span><strong id="stat_closed_complaints">0</strong></div>
                    <div class="stat-card"><span>Pending Complaints</span><strong id="stat_pending_complaints">0</strong></div>
                </div>

                <div class="ratio-grid">
                    <div class="ratio-card"><span>Resolution Ratio</span><strong id="stat_resolution_ratio">0%</strong></div>
                    <div class="ratio-card"><span>Pending Ratio</span><strong id="stat_pending_ratio">0%</strong></div>
                    <div class="ratio-card"><span>Closure Ratio</span><strong id="stat_closure_ratio">0%</strong></div>
                </div>

                <div class="dash-grid">
                    <div class="dash-card">
                        <div class="dash-card-head">Report-wise Counts</div>
                        <canvas id="reportChart" height="145"></canvas>
                    </div>
                    <div class="dash-card">
                        <div class="dash-card-head">Shift-wise Counts</div>
                        <canvas id="shiftChart" height="145"></canvas>
                    </div>
                </div>

                <div class="dash-grid">
                    <div class="dash-card">
                        <div class="dash-card-head">Department-wise Counts</div>
                        <div id="department_counts" class="mini-list"></div>
                    </div>
                    <div class="dash-card">
                        <div class="dash-card-head">Agent Productivity</div>
                        <div id="agent_counts" class="mini-list"></div>
                    </div>
                </div>

                <div class="dash-grid">
                    <div class="dash-card">
                        <div class="dash-card-head">Complaint Creation Analytics</div>
                        <div class="split-list">
                            <div><b>Date-wise</b><div id="date_counts" class="mini-list compact"></div></div>
                            <div><b>Attribute-wise</b><div id="attribute_counts" class="mini-list compact"></div></div>
                        </div>
                    </div>
                    <div class="dash-card">
                        <div class="dash-card-head">High Rated Tag & Status Analytics</div>
                        <div id="status_counts" class="mini-list"></div>
                    </div>
                </div>

                <div class="dash-grid">
                    <div class="dash-card">
                        <div class="dash-card-head">Report Creation / Update Analytics</div>
                        <div class="split-list">
                            <div><b>Created</b><div id="report_creation_counts" class="mini-list compact"></div></div>
                            <div><b>Updated</b><div id="report_update_counts" class="mini-list compact"></div></div>
                        </div>
                    </div>
                    <div class="dash-card">
                        <div class="dash-card-head">OWA Agent Analytics</div>
                        <div id="owa_counts" class="mini-list"></div>
                    </div>
                </div>

                <div class="dash-card">
                    <div class="dash-card-head d-flex justify-content-between">
                        <span>Filtered Activity Details</span>
                        <small id="row_count_label" class="text-muted"></small>
                    </div>
                    <div id="activity_table_wrap"></div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
.dash-shell { display:flex; flex-direction:column; gap:16px; }
.dash-title-row { display:flex; align-items:flex-start; justify-content:space-between; gap:14px; }
.dash-title-row h4 { margin:0; font-weight:800; color:#1a1a2e; }
.dash-title-row p { margin:4px 0 0; color:#777; font-size:13px; }
.dash-filter { display:grid; grid-template-columns:repeat(4,minmax(150px,1fr)) auto; gap:10px; align-items:end; background:#fff; border:1px solid #e8eaf0; border-radius:12px; padding:14px; }
.filter-field label { display:block; font-size:11px; font-weight:700; color:#777; margin-bottom:4px; text-transform:uppercase; }
.filter-actions { display:flex; gap:8px; }
.stat-grid { display:grid; grid-template-columns:repeat(5,minmax(130px,1fr)); gap:12px; }
.stat-card { background:#fff; border:1px solid #e8eaf0; border-radius:12px; padding:16px; box-shadow:0 1px 6px rgba(0,0,0,.05); }
.stat-card span { display:block; font-size:12px; color:#777; font-weight:700; text-transform:uppercase; }
.stat-card strong { display:block; margin-top:6px; font-size:28px; color:#1a5fa5; }
.ratio-grid { display:grid; grid-template-columns:repeat(3,minmax(160px,1fr)); gap:12px; }
.ratio-card { background:#fff; border:1px solid #dcebdc; border-radius:12px; padding:14px 16px; box-shadow:0 1px 6px rgba(0,0,0,.04); }
.ratio-card span { display:block; font-size:12px; color:#61705f; font-weight:700; text-transform:uppercase; }
.ratio-card strong { display:block; margin-top:4px; font-size:24px; color:#1a7a4a; }
.dash-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
.dash-card { background:#fff; border:1px solid #e8eaf0; border-radius:12px; padding:16px; box-shadow:0 1px 6px rgba(0,0,0,.05); }
.dash-card-head { font-size:14px; font-weight:800; color:#1a1a2e; margin-bottom:12px; }
.mini-list { display:flex; flex-direction:column; gap:8px; max-height:280px; overflow:auto; }
.mini-row { display:flex; align-items:center; justify-content:space-between; gap:12px; border-bottom:1px solid #f1f2f6; padding:7px 0; font-size:13px; }
.mini-row strong { color:#1a5fa5; }
.mini-row small { display:block; color:#777; margin-top:2px; }
.split-list { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
.split-list b { display:block; margin-bottom:8px; font-size:12px; text-transform:uppercase; color:#6b7280; }
.compact { max-height:220px; }
@media(max-width:1100px){ .dash-filter,.stat-grid,.dash-grid,.ratio-grid{grid-template-columns:1fr 1fr;} .filter-actions{grid-column:1/-1;} }
@media(max-width:640px){ .dash-filter,.stat-grid,.dash-grid,.ratio-grid,.split-list{grid-template-columns:1fr;} .dash-title-row{flex-direction:column;} }
</style>

<script>
var reportChart = null;
var shiftChart = null;

function filters() {
    return {
        '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',
        start_date: $("#start_date").val(),
        end_date: $("#end_date").val(),
        agent_id: $("#agent_id").val(),
        department: $("#department").val(),
        shift: $("#shift").val(),
        report: $("#report").val(),
        attribute: $("#attribute").val(),
        status: $("#status").val()
    };
}

function escapeHtml(value) {
    return $("<div>").text(value == null ? "" : value).html();
}

function objectKeys(obj) {
    return Object.keys(obj || {});
}

function chartDataFromObject(obj) {
    var labels = objectKeys(obj);
    var values = labels.map(function(key){ return obj[key]; });
    return { labels: labels, values: values };
}

function renderBarChart(canvasId, existing, counts, color) {
    var data = chartDataFromObject(counts);
    if(existing) existing.destroy();
    return new Chart(document.getElementById(canvasId), {
        type: 'bar',
        data: { labels: data.labels, datasets: [{ data: data.values, backgroundColor: color, borderRadius: 6 }] },
        options: { plugins:{ legend:{ display:false } }, scales:{ y:{ beginAtZero:true, ticks:{ precision:0 } } } }
    });
}

function renderMiniList(target, counts, agentMode) {
    var keys = objectKeys(counts);
    if(keys.length === 0) {
        $(target).html("<div class='text-muted'>No data found</div>");
        return;
    }
    keys.sort(function(a,b){
        var av = agentMode ? counts[a].records : counts[a];
        var bv = agentMode ? counts[b].records : counts[b];
        return bv - av;
    });
    var html = "";
    keys.slice(0, 12).forEach(function(key){
        if(agentMode) {
            var reports = Object.keys(counts[key].reports || {}).join(", ");
            var meta = "Contribution "+counts[key].contribution+"% | Closed "+counts[key].closure_ratio+"% | Pending "+counts[key].pending_ratio+"%";
            html += "<div class='mini-row'><div>"+escapeHtml(key)+"<small>"+escapeHtml(reports)+"</small><small>"+escapeHtml(meta)+"</small></div><strong>"+counts[key].records+" / "+counts[key].updates+"</strong></div>";
        } else {
            html += "<div class='mini-row'><span>"+escapeHtml(key)+"</span><strong>"+counts[key]+"</strong></div>";
        }
    });
    $(target).html(html);
}

function renderActivityTable(rows) {
    var html = "<table class='table table-bordered table-sm' id='activity_table' style='font-size:12px;'>";
    html += "<thead><tr><th>Report</th><th>Date</th><th>Agent</th><th>Department</th><th>Attribute</th><th>Complaint/Phone</th><th>Status</th><th>Shift</th><th>Remark</th><th>Updates</th><th>Created At</th></tr></thead><tbody>";
    rows.forEach(function(row){
        var reference = row.complaint_number || row.phone_number || "";
        html += "<tr>";
        html += "<td>"+escapeHtml(row.report_name)+"</td>";
        html += "<td>"+escapeHtml(row.entry_date)+"</td>";
        html += "<td>"+escapeHtml(row.agent_name)+"<br><small>"+escapeHtml(row.agent_msd_id)+"</small></td>";
        html += "<td>"+escapeHtml(row.department_name)+"</td>";
        html += "<td>"+escapeHtml(row.attribute_name)+"</td>";
        html += "<td>"+escapeHtml(reference)+"</td>";
        html += "<td>"+escapeHtml(row.status_label)+"</td>";
        html += "<td>"+escapeHtml(row.shift_name)+"</td>";
        html += "<td>"+escapeHtml(row.latest_remark)+"</td>";
        html += "<td>"+escapeHtml(row.updates_count)+"</td>";
        html += "<td>"+escapeHtml(row.created_at)+"</td>";
        html += "</tr>";
    });
    html += "</tbody></table>";
    $("#activity_table_wrap").html(html);
    if($.fn.DataTable) {
        $("#activity_table").DataTable({ pageLength: 25, ordering: true });
    }
}

function loadDashboard() {
    $("#activity_table_wrap").html("<center><font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Loading dashboard...</b></font></center>");
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "<?php echo base_url(); ?>app/dashboard/get_dashboard",
        data: filters(),
        cache: false,
        success: function(data) {
            if(!data.response) return;
            var s = data.summary;
            $("#stat_total").text(s.total);
            $("#stat_today").text(s.today);
            $("#stat_pending").text(s.pending);
            $("#stat_completed").text(s.completed);
            $("#stat_updates").text(s.updates);
            $("#stat_complaints").text(s.complaints_created);
            $("#stat_high_rated").text(s.high_rated);
            $("#stat_next_level").text(s.next_level);
            $("#stat_closed_complaints").text(s.closed_complaints);
            $("#stat_pending_complaints").text(s.pending_complaints);
            $("#stat_resolution_ratio").text(s.resolution_ratio + "%");
            $("#stat_pending_ratio").text(s.pending_ratio + "%");
            $("#stat_closure_ratio").text(s.closure_ratio + "%");
            $("#row_count_label").text("Showing "+data.shown_rows+" of "+data.total_filtered+" filtered records");
            reportChart = renderBarChart("reportChart", reportChart, s.report_counts, "#1a5fa5");
            shiftChart = renderBarChart("shiftChart", shiftChart, s.shift_counts, "#1a7a4a");
            renderMiniList("#department_counts", s.department_counts, false);
            renderMiniList("#agent_counts", s.agent_counts, true);
            renderMiniList("#date_counts", s.date_counts, false);
            renderMiniList("#attribute_counts", s.attribute_counts, false);
            renderMiniList("#status_counts", s.status_counts, false);
            renderMiniList("#report_creation_counts", s.report_creation_counts, false);
            renderMiniList("#report_update_counts", s.report_update_counts, false);
            renderMiniList("#owa_counts", s.owa_counts, false);
            renderActivityTable(data.rows || []);
        }
    });
}

function resetDashboardFilters() {
    $("#start_date,#end_date").val("");
    $("#agent_id,#department,#attribute").val("0");
    $("#shift,#report,#status").val("all");
    loadDashboard();
}

function exportDashboard() {
    var query = $.param({
        start_date: $("#start_date").val(),
        end_date: $("#end_date").val(),
        agent_id: $("#agent_id").val(),
        department: $("#department").val(),
        shift: $("#shift").val(),
        report: $("#report").val(),
        attribute: $("#attribute").val(),
        status: $("#status").val()
    });
    window.location = "<?php echo base_url(); ?>app/dashboard/export_dashboard?" + query;
}

loadDashboard();
setInterval(loadDashboard, 120000);
</script>
