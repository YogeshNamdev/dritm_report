<link href="<?php echo base_url(); ?>includes/plugins/advance_datatable/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>includes/plugins/select2/css/select2.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>includes/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/advance_datatable/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/select2/js/select2.full.min.js"></script>

<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url(); ?>app/attendance">Attendance</a></li>
                        <li class="breadcrumb-item active">Shrinkage Overview</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">

            <!-- FILTERS -->
            <div class="card card-default">
                <div class="card-body">
                    <div class="d-flex align-items-center flex-wrap">

                        <div class="form-group mb-0 mr-2">
                            <label class="mr-1 mb-0">From</label>
                            <input type="date" id="filter_date_from" class="form-control" value="<?= date('Y-m-01'); ?>" onchange="loadAll();">
                        </div>

                        <div class="form-group mb-0 mr-2">
                            <label class="mr-1 mb-0">To</label>
                            <input type="date" id="filter_date_to" class="form-control" value="<?= date('Y-m-d'); ?>" onchange="loadAll();">
                        </div>

                        <div class="form-group mb-0">
                            <select class="form-control select2" id="filter_group_by" style="width:160px;" onchange="loadGrouped();">
                                <option value="lob">Group: LOB</option>
                                <option value="tl">Group: TL</option>
                                <option value="shift">Group: Shift</option>
                            </select>
                        </div>

                    </div>
                </div>
            </div>

            <!-- GROUPED SUMMARY -->
            <div class="card card-default">
                <div class="card-header"><h5 class="m-0" id="grouped_title"><b>LOB Wise Shrinkage</b></h5></div>
                <div class="card-body">
                    <div class="table-responsive" id="grouped_div"></div>
                </div>
            </div>

            <!-- DAILY TREND -->
            <div class="card card-default">
                <div class="card-header"><h5 class="m-0"><b>Daily Wise Shrinkage</b></h5></div>
                <div class="card-body">
                    <div class="table-responsive" id="daily_div"></div>
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

function pct(v)
{
    return (v * 100).toFixed(2) + "%";
}

function loadGrouped()
{
    var group_by = $("#filter_group_by").val();
    var date_from = $("#filter_date_from").val();
    var date_to = $("#filter_date_to").val();

    var titles = { lob: "LOB Wise Shrinkage", tl: "TL Wise Shrinkage", shift: "Shift Wise Shrinkage" };
    $("#grouped_title").text(titles[group_by] || "Shrinkage");

    $("#grouped_div").html("<center><font color='blue'><i class='fa fa-spinner fa-spin'></i> Loading...</font></center>");

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url(); ?>app/attendance/get_shrinkage_overview",
        data: {
            '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',
            group_by: group_by, date_from: date_from, date_to: date_to
        },
        cache: false,
        success: function(data){
            if(!data.response){
                $("#grouped_div").html("<center><font color='red'>"+data.message+"</font></center>");
                return;
            }

            var txt = "<table class='table table-bordered table-sm' id='tbl_grouped'>";
            txt += "<thead><tr><th>Group</th><th>HC</th><th>Roster</th><th>WO</th><th>P</th><th>HD</th><th>AB</th><th>Leave</th><th>WO%</th><th>UPL%</th><th>Shrinkage%</th></tr></thead><tbody>";

            var totals = { hc:0, roster:0, wo:0, p:0, hd:0, ab:0, leave:0 };

            for(var i = 0; i < data.total_record; i++){
                var r = data.all_record[i];
                txt += "<tr>";
                txt += "<td>"+r.group_label+"</td>";
                txt += "<td>"+r.hc+"</td>";
                txt += "<td>"+r.roster+"</td>";
                txt += "<td>"+r.wo+"</td>";
                txt += "<td>"+r.p+"</td>";
                txt += "<td>"+r.hd+"</td>";
                txt += "<td>"+r.ab+"</td>";
                txt += "<td>"+r.leave+"</td>";
                txt += "<td>"+pct(r.wo_percent)+"</td>";
                txt += "<td>"+pct(r.upl_percent)+"</td>";
                txt += "<td><b>"+pct(r.shrinkage_percent)+"</b></td>";
                txt += "</tr>";

                totals.hc += r.hc; totals.roster += r.roster; totals.wo += r.wo;
                totals.p += r.p; totals.hd += r.hd; totals.ab += r.ab; totals.leave += r.leave;
            }

            var grand_wo_pct = totals.roster > 0 ? (totals.wo / totals.roster) : 0;
            var grand_shrink_pct = totals.roster > 0 ? ((totals.hd + totals.ab + totals.leave) / totals.roster) : 0;

            txt += "<tr style='font-weight:bold;background:#f4f6f9;'>";
            txt += "<td>Grand Total</td><td>"+totals.hc+"</td><td>"+totals.roster+"</td><td>"+totals.wo+"</td><td>"+totals.p+"</td><td>"+totals.hd+"</td><td>"+totals.ab+"</td><td>"+totals.leave+"</td>";
            txt += "<td>"+pct(grand_wo_pct)+"</td><td>"+pct(grand_shrink_pct)+"</td><td>"+pct(grand_wo_pct + grand_shrink_pct)+"</td>";
            txt += "</tr>";

            txt += "</tbody></table>";
            $("#grouped_div").html(txt);
            makeDataTable_Basic("tbl_grouped");
        },
        error: function(){
            $("#grouped_div").html("<center><font color='red'>Server error</font></center>");
        }
    });
}

function loadDaily()
{
    var date_from = $("#filter_date_from").val();
    var date_to = $("#filter_date_to").val();

    $("#daily_div").html("<center><font color='blue'><i class='fa fa-spinner fa-spin'></i> Loading...</font></center>");

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url(); ?>app/attendance/get_daily_shrinkage_trend",
        data: {
            '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',
            date_from: date_from, date_to: date_to
        },
        cache: false,
        success: function(data){
            if(!data.response){
                $("#daily_div").html("<center><font color='red'>"+data.message+"</font></center>");
                return;
            }

            var txt = "<table class='table table-bordered table-sm' id='tbl_daily'>";
            txt += "<thead><tr><th>Date</th><th>HC</th><th>Shrinkage%</th></tr></thead><tbody>";
            for(var i = 0; i < data.total_record; i++){
                var r = data.all_record[i];
                txt += "<tr><td>"+r.date+"</td><td>"+r.hc+"</td><td>"+pct(r.shrinkage_percent)+"</td></tr>";
            }
            txt += "</tbody></table>";
            $("#daily_div").html(txt);
            makeDataTable_Basic("tbl_daily");
        },
        error: function(){
            $("#daily_div").html("<center><font color='red'>Server error</font></center>");
        }
    });
}

function loadAll()
{
    loadGrouped();
    loadDaily();
}

$(function(){
    if($.fn.select2){
        $(".select2").select2({ theme: "bootstrap4", minimumResultsForSearch: -1 });
    }
    loadAll();
});
</script>