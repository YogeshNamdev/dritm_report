<link href="<?php echo base_url(); ?>includes/plugins/advance_datatable/jquery.dataTables.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/advance_datatable/jquery.dataTables.min.js"></script>

<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url(); ?>app/attendance">Attendance</a></li>
                        <li class="breadcrumb-item active">Request Roster Change</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">

            <!-- NEW REQUEST -->
            <div class="card card-default">
                <div class="card-header"><h5 class="m-0"><b>New Request</b></h5></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Date</label>
                                <input type="date" id="req_date" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Mujhe chahiye</label>
                                <select class="form-control" id="req_code">
                                    <optgroup label="Shift">
                                        <?php if($shift_presets): foreach($shift_presets as $p): ?>
                                            <option value="SHIFT:<?= $p->id; ?>"><?= $p->shift_name; ?> (<?= substr($p->start_time,0,5); ?>-<?= substr($p->end_time,0,5); ?>)</option>
                                        <?php endforeach; endif; ?>
                                    </optgroup>
                                    <option value="AWO">Week Off (Agent Choice)</option>
                                    <option value="CL">CL</option>
                                    <option value="HD">Half Day</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Reason</label>
                                <input type="text" id="req_remark" class="form-control" placeholder="Kyu chahiye roster change...">
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="submit_request();">Submit Request</button>
                    <span id="req_msg" class="ml-2"></span>
                </div>
            </div>

            <!-- MY HISTORY -->
            <div class="card card-default">
                <div class="card-header"><h5 class="m-0"><b>My Requests</b></h5></div>
                <div class="card-body">
                    <div class="table-responsive" id="my_requests_div"></div>
                </div>
            </div>

        </div>
    </section>
</div>

<script>
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

function submit_request()
{
    var date = $("#req_date").val();
    var code = $("#req_code").val();
    var remark = $("#req_remark").val().trim();

    if(!date){ $("#req_msg").html("<font color='red'>Date select karo.</font>"); return; }
    if(remark == ""){ $("#req_msg").html("<font color='red'>Reason likho.</font>"); return; }

    $("#req_msg").html("<font color='blue'><i class='fa fa-spinner fa-spin'></i> Sending...</font>");

    $.ajax({
        type: "POST", dataType: "json",
        url: "<?php echo base_url(); ?>app/attendance/submit_roster_change_request",
        data: {
            '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',
            request_date: date, code: code, remark: remark
        },
        cache: false,
        success: function(data){
            var color = data.response ? "green" : "red";
            $("#req_msg").html("<span style='color:"+color+"'>"+data.message+"</span>");
            if(data.response){
                $("#req_date").val(""); $("#req_remark").val("");
                get_my_requests();
            }
        },
        error: function(){ $("#req_msg").html("<span style='color:red'>Server error</span>"); }
    });
}

function get_my_requests()
{
    $("#my_requests_div").html("<center><font color='blue'><i class='fa fa-spinner fa-spin'></i> Loading...</font></center>");
    $.ajax({
        type: "POST", dataType: "json",
        url: "<?php echo base_url(); ?>app/attendance/get_my_roster_requests",
        data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' },
        cache: false,
        success: function(data){
            if(!data.response){
                $("#my_requests_div").html("<center><font color='red'>"+data.message+"</font></center>");
                return;
            }
            var txt = "<table class='table table-bordered table-sm' id='tbl_my_req'>";
            txt += "<thead><tr><th>Date</th><th>Current</th><th>Requested</th><th>My Reason</th><th>Status</th><th>Admin Remark</th><th>Reviewed By</th></tr></thead><tbody>";
            for(var i = 0; i < data.total_record; i++){
                var r = data.all_record[i];
                txt += "<tr>";
                txt += "<td>"+r.request_date+"</td>";
                txt += "<td>"+escapeHtml(r.current_label)+"</td>";
                txt += "<td>"+escapeHtml(r.requested_label)+"</td>";
                txt += "<td>"+escapeHtml(r.agent_remark)+"</td>";
                txt += "<td>"+statusBadge(r.status)+"</td>";
                txt += "<td>"+escapeHtml(r.admin_remark || '-')+"</td>";
                txt += "<td>"+escapeHtml(r.reviewed_by_name || '-')+"</td>";
                txt += "</tr>";
            }
            txt += "</tbody></table>";
            $("#my_requests_div").html(txt);
            makeDataTable_Basic("tbl_my_req");
        }
    });
}

get_my_requests();
</script>