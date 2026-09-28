<link href="<?php echo base_url(); ?>includes/plugins/advance_datatable/jquery.dataTables.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/advance_datatable/jquery.dataTables.min.js"></script>

<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url(); ?>app/attendance">Attendance</a></li>
                        <li class="breadcrumb-item active">Roster Change Requests</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-default color-palette-box">

                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="m-0"><b>Roster Change Requests</b></h5>
                        <select class="form-control form-control-sm" id="filter_status" style="width:150px;" onchange="get_requests();">
                            <option value="">All</option>
                            <option value="Pending" selected>Pending</option>
                            <option value="Approved">Approved</option>
                            <option value="Rejected">Rejected</option>
                        </select>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive" id="requests_div"></div>
                </div>

            </div>
        </div>
    </section>
</div>

<!-- REVIEW MODAL -->
<div class="modal fade" id="review_model">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">Review Request</h4>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>

            <div class="modal-body">
                <div id="review_summary" class="mb-2"></div>
                <div id="capacity_msg" class="mb-2"></div>

                <div class="form-group">
                    <label>Admin Remark (Approve ya Reject — dono ke liye zaroori)</label>
                    <textarea id="admin_remark" class="form-control" rows="3" placeholder="Reason likho..."></textarea>
                </div>

                <div class="form-group" id="override_div" style="display:none;">
                    <label style="font-weight:normal;"><input type="checkbox" id="force_override"> Capacity full hai phir bhi approve karo (override)</label>
                </div>

                <div id="review_msg"></div>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-danger" onclick="do_reject();">Reject</button>
                <button type="button" class="btn btn-success" id="approve_btn" onclick="do_approve();">Approve</button>
            </div>

        </div>
    </div>
</div>

<script>
var CURRENT_REQUEST = null;

function makeDataTable_Basic(tableID)
{
    var tableSelector = '#'+tableID;
    if (!$(tableSelector).length || !$.fn.DataTable) return null;
    if ($.fn.DataTable.isDataTable(tableSelector)) $(tableSelector).DataTable().destroy();
    return $(tableSelector).DataTable({ destroy: true, ordering: true });
}

function escapeHtml(value)
{
    return $("<div>").text(value == null ? "" : value).html();
}

function statusBadge(status)
{
    var map = { 'Pending':'warning', 'Approved':'success', 'Rejected':'danger' };
    return "<span class='badge badge-"+(map[status]||'secondary')+"'>"+status+"</span>";
}

function get_requests()
{
    var status = $("#filter_status").val();
    $("#requests_div").html("<center><font color='blue'><i class='fa fa-spinner fa-spin'></i> Loading...</font></center>");

    $.ajax({
        type: "POST", dataType: "json",
        url: "<?php echo base_url(); ?>app/attendance/get_all_roster_requests",
        data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>', status: status },
        cache: false,
        success: function(data){
            if(!data.response){
                $("#requests_div").html("<center><font color='red'>"+data.message+"</font></center>");
                return;
            }
            window.REQ_ROWS = data.all_record;
            var txt = "<table class='table table-bordered table-sm' id='tbl_requests'>";
            txt += "<thead><tr><th>Employee</th><th>Agent ID</th><th>Date</th><th>Current</th><th>Requested</th><th>Reason</th><th>Status</th><th>Admin Remark</th><th>Action</th></tr></thead><tbody>";
            for(var i = 0; i < data.total_record; i++){
                var r = data.all_record[i];
                txt += "<tr>";
                txt += "<td>"+escapeHtml(r.name)+"</td>";
                txt += "<td>"+escapeHtml(r.msd_id)+"</td>";
                txt += "<td>"+r.request_date+"</td>";
                txt += "<td>"+escapeHtml(r.current_label)+"</td>";
                txt += "<td>"+escapeHtml(r.requested_label)+"</td>";
                txt += "<td>"+escapeHtml(r.agent_remark)+"</td>";
                txt += "<td>"+statusBadge(r.status)+"</td>";
                txt += "<td>"+escapeHtml(r.admin_remark || '-')+"</td>";
                txt += "<td>"+(r.status == "Pending" ? "<button type='button' class='btn btn-sm btn-info' onclick='open_review("+r.id+")'>Review</button>" : "-")+"</td>";
                txt += "</tr>";
            }
            txt += "</tbody></table>";
            $("#requests_div").html(txt);
            makeDataTable_Basic("tbl_requests");
        }
    });
}

function open_review(id)
{
    var row = null;
    (window.REQ_ROWS || []).forEach(function(r){ if(r.id == id) row = r; });
    if(!row){ alert("Record nahi mila."); return; }

    CURRENT_REQUEST = row;
    $("#admin_remark").val("");
    $("#force_override").prop("checked", false);
    $("#override_div").hide();
    $("#review_msg").html("");

    $("#review_summary").html(
        "<b>"+escapeHtml(row.name)+"</b> ("+escapeHtml(row.msd_id)+")<br>" +
        "Date: <b>"+row.request_date+"</b><br>" +
        "Current: "+escapeHtml(row.current_label)+" &rarr; Requested: <b>"+escapeHtml(row.requested_label)+"</b><br>" +
        "Reason: <i>"+escapeHtml(row.agent_remark)+"</i>"
    );

    $("#capacity_msg").html("<font color='blue'><i class='fa fa-spinner fa-spin'></i> Capacity check ho raha hai...</font>");

    $.ajax({
        type: "POST", dataType: "json",
        url: "<?php echo base_url(); ?>app/attendance/check_request_capacity",
        data: {
            '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',
            emp_id: row.emp_id, request_date: row.request_date, code: row.requested_code
        },
        cache: false,
        success: function(data){
            if(data.capacity_ok){
                $("#capacity_msg").html("<span class='badge badge-success'>Capacity OK</span>");
            } else {
                $("#capacity_msg").html("<span class='badge badge-danger'>Capacity Issue</span> "+data.message);
                $("#override_div").show();
            }
        }
    });

    $("#review_model").modal("show");
}

function do_approve()
{
    var remark = $("#admin_remark").val().trim();
    if(remark == ""){ $("#review_msg").html("<font color='red'>Remark likho.</font>"); return; }

    var force = $("#force_override").is(":checked") ? 1 : 0;

    $("#review_msg").html("<font color='blue'><i class='fa fa-spinner fa-spin'></i> Approving...</font>");
    $.ajax({
        type: "POST", dataType: "json",
        url: "<?php echo base_url(); ?>app/attendance/approve_roster_request",
        data: {
            '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',
            request_id: CURRENT_REQUEST.id, admin_remark: remark, force: force
        },
        cache: false,
        success: function(data){
            var color = data.response ? "green" : "red";
            $("#review_msg").html("<span style='color:"+color+"'>"+data.message+"</span>");
            if(data.response){
                setTimeout(function(){ $("#review_model").modal("hide"); get_requests(); }, 800);
            }
        },
        error: function(){ $("#review_msg").html("<span style='color:red'>Server error</span>"); }
    });
}

function do_reject()
{
    var remark = $("#admin_remark").val().trim();
    if(remark == ""){ $("#review_msg").html("<font color='red'>Reject ka reason likhna zaroori hai.</font>"); return; }

    $("#review_msg").html("<font color='blue'><i class='fa fa-spinner fa-spin'></i> Rejecting...</font>");
    $.ajax({
        type: "POST", dataType: "json",
        url: "<?php echo base_url(); ?>app/attendance/reject_roster_request",
        data: {
            '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',
            request_id: CURRENT_REQUEST.id, admin_remark: remark
        },
        cache: false,
        success: function(data){
            var color = data.response ? "green" : "red";
            $("#review_msg").html("<span style='color:"+color+"'>"+data.message+"</span>");
            if(data.response){
                setTimeout(function(){ $("#review_model").modal("hide"); get_requests(); }, 800);
            }
        },
        error: function(){ $("#review_msg").html("<span style='color:red'>Server error</span>"); }
    });
}

get_requests();
</script>