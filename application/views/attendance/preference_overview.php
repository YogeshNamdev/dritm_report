<link href="<?php echo base_url(); ?>includes/plugins/advance_datatable/jquery.dataTables.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/advance_datatable/jquery.dataTables.min.js"></script>

<style>
    .pov-row-ok { }
    .pov-row-over { background: #fdecea; }
    .pov-badge-ok { background:#e7f6ea; color:#1e7b34; border:1px solid #bfe6c8; }
    .pov-badge-over { background:#fdeaea; color:#c0392b; border:1px solid #f5c2be; }
    .pov-badge { display:inline-block; padding:3px 10px; border-radius:20px; font-size:12px; }
</style>

<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url(); ?>app/attendance">Attendance</a></li>
                        <li class="breadcrumb-item active">WO / CL Preference Overview</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">

                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="m-0"><b>Date-wise WO / CL Preference</b></h5>
                        <div class="d-flex align-items-center">
                            <input type="month" id="ov_month" class="form-control mr-2" style="width:180px;">
                            <button type="button" class="btn btn-dark" onclick="get_overview();">Load</button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="mb-2" style="font-size:13px;color:#8a94a3;">
                        "Over Cap" tab red hota hai jab us din ke WO+CL requests us din ke max-leave-cap (Roster Compliance wala) se zyada ho jate hain — matlab sabko WO nahi diya ja sakta, kuch agents ko manually adjust karna padega.
                    </div>
                    <div class="table-responsive" id="overview_div"></div>
                </div>

            </div>
        </div>
    </section>
</div>

<!-- DETAIL MODAL -->
<div class="modal fade" id="detail_model">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detail_title"><b>Agent List</b></h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6><b>WO maangne wale</b></h6>
                        <div id="detail_wo_div"></div>
                    </div>
                    <div class="col-md-6">
                        <h6><b>CL maangne wale</b></h6>
                        <div id="detail_cl_div"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
var OV_ROWS = [];

function makeDataTable_Basic(tableID)
{
    var tableSelector = '#'+tableID;
    if (!$(tableSelector).length || !$.fn.DataTable) return null;
    if ($.fn.DataTable.isDataTable(tableSelector)) $(tableSelector).DataTable().destroy();
    return $(tableSelector).DataTable({ destroy: true, ordering: true, paging: false, searching: false, info: false });
}

function escapeHtml(value)
{
    return $("<div>").text(value == null ? "" : value).html();
}

function defaultNextMonth()
{
    var d = new Date();
    d.setMonth(d.getMonth() + 1);
    var y = d.getFullYear();
    var m = ("0" + (d.getMonth()+1)).slice(-2);
    return y + "-" + m;
}

function get_overview()
{
    var for_month = $("#ov_month").val();
    if(!for_month){ alert("Month select karo."); return; }

    $("#overview_div").html("<center><font color='blue'><i class='fa fa-spinner fa-spin'></i> Loading...</font></center>");

    $.ajax({
        type: "POST", dataType: "json",
        url: "<?php echo base_url(); ?>app/attendance/get_preference_overview",
        data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>', for_month: for_month },
        cache: false,
        success: function(data){
            if(!data.response){
                $("#overview_div").html("<center><font color='red'>"+data.message+"</font></center>");
                return;
            }
            if(data.total_record == 0){
                $("#overview_div").html("<center class='text-muted p-3'>Is month ke liye abhi tak koi preference submit nahi hui.</center>");
                return;
            }

            OV_ROWS = data.all_record;

            var txt = "<table class='table table-bordered table-sm' id='tbl_overview'>";
            txt += "<thead><tr><th>Date</th><th>Day Type</th><th>WO Requested</th><th>CL Requested</th><th>Total</th><th>Max Leave Cap</th><th>Status</th><th>Detail</th></tr></thead><tbody>";
            for(var i = 0; i < OV_ROWS.length; i++){
                var r = OV_ROWS[i];
                var rowClass = r.over_cap ? "pov-row-over" : "pov-row-ok";
                var badge = r.over_cap ? "<span class='pov-badge pov-badge-over'>Over Cap</span>" : "<span class='pov-badge pov-badge-ok'>OK</span>";
                txt += "<tr class='"+rowClass+"'>";
                txt += "<td>"+r.date+"</td>";
                txt += "<td>"+r.day_type+"</td>";
                txt += "<td>"+r.wo_requested+"</td>";
                txt += "<td>"+r.cl_requested+"</td>";
                txt += "<td><b>"+r.total_requested+"</b></td>";
                txt += "<td>"+(r.max_leave_cap !== null ? r.max_leave_cap : '-')+"</td>";
                txt += "<td>"+badge+"</td>";
                txt += "<td><button type='button' class='btn btn-sm btn-info' onclick=\"open_detail('"+r.date+"')\">View</button></td>";
                txt += "</tr>";
            }
            txt += "</tbody></table>";
            $("#overview_div").html(txt);
            makeDataTable_Basic("tbl_overview");
        },
        error: function(){ $("#overview_div").html("<center><font color='red'>Server error</font></center>"); }
    });
}

function agentListHtml(agents, showPriority)
{
    if(!agents || agents.length == 0){
        return "<p class='text-muted' style='font-size:13px;'>Koi nahi.</p>";
    }
    var txt = "<ul style='font-size:13px;padding-left:18px;'>";
    agents.forEach(function(a){
        txt += "<li>"+escapeHtml(a.name)+" ("+escapeHtml(a.msd_id)+")"+
               (a.tl_name ? " — TL: "+escapeHtml(a.tl_name) : "") +
               (showPriority && a.priority ? " <span class='badge badge-secondary'>Choice "+a.priority+"</span>" : "") +
               (a.remark ? "<br><small class='text-muted'>"+escapeHtml(a.remark)+"</small>" : "") +
               "</li>";
    });
    txt += "</ul>";
    return txt;
}

function open_detail(date)
{
    var row = null;
    OV_ROWS.forEach(function(r){ if(r.date == date) row = r; });
    if(!row) return;

    $("#detail_title").html("<b>"+date+"</b> ("+row.day_type+")");
    $("#detail_wo_div").html(agentListHtml(row.wo_agents, true));
    $("#detail_cl_div").html(agentListHtml(row.cl_agents, false));
    $("#detail_model").modal("show");
}

$(function(){
    $("#ov_month").val(defaultNextMonth());
    get_overview();
});
</script>