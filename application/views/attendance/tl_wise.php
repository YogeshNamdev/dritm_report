<link href="<?php echo base_url(); ?>includes/plugins/advance_datatable/jquery.dataTables.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/advance_datatable/jquery.dataTables.min.js"></script>

<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url(); ?>app/attendance">Attendance</a></li>
                        <li class="breadcrumb-item active">TL Wise Shrinkage</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">

                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h5 class="m-0"><b>TL Wise Shrinkage %</b></h5>
                        <div class="form-group mb-0">
                            <input type="month" id="filter_month" class="form-control" value="<?= date('Y-m'); ?>" onchange="get_tl_wise();">
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive" id="tl_wise_div"></div>
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
    return $(tableSelector).DataTable({ destroy: true, ordering: true, scrollX: true, pageLength: 25 });
}

function pct(v)
{
    return (v * 100).toFixed(2) + "%";
}

function shrinkClass(v)
{
    if(v >= 0.25) return "background:#f8d7da;"; // high shrinkage - red-ish
    if(v >= 0.15) return "background:#fff3cd;"; // medium - yellow-ish
    return "background:#d4edda;"; // low - green-ish
}

function get_tl_wise()
{
    var month = $("#filter_month").val();
    if(!month){ return; }

    $("#tl_wise_div").html("<center><font color='blue'><i class='fa fa-spinner fa-spin'></i> Loading...</font></center>");

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url(); ?>app/attendance/get_tl_wise_shrinkage",
        data: {
            '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',
            month: month
        },
        cache: false,
        success: function(data){
            if(!data.response){
                $("#tl_wise_div").html("<center><font color='red'>"+data.message+"</font></center>");
                return;
            }
            if(data.total_record == 0){
                $("#tl_wise_div").html("<center><font color='gray'>No data for this month.</font></center>");
                return;
            }

            // saare unique dates nikal lo (kisi bhi ek TL row se, union le lo for safety)
            var dateSet = {};
            for(var i = 0; i < data.total_record; i++){
                var dates = data.all_record[i].dates;
                for(var d in dates){ dateSet[d] = true; }
            }
            var dateList = Object.keys(dateSet).sort();

            var txt = "<table class='table table-bordered table-sm' style='font-size:12px;' id='tbl_tl_wise'>";
            txt += "<thead><tr><th>TL Name</th>";
            dateList.forEach(function(d){ txt += "<th>"+d.substring(8,10)+"</th>"; });
            txt += "</tr></thead><tbody>";

            for(var i = 0; i < data.total_record; i++){
                var r = data.all_record[i];
                txt += "<tr><td>"+r.tl_name+"</td>";
                dateList.forEach(function(d){
                    var v = (r.dates[d] !== undefined) ? r.dates[d] : null;
                    if(v === null){
                        txt += "<td>-</td>";
                    } else {
                        txt += "<td style='"+shrinkClass(v)+"'>"+pct(v)+"</td>";
                    }
                });
                txt += "</tr>";
            }

            txt += "</tbody></table>";
            $("#tl_wise_div").html(txt);
            makeDataTable_Basic("tbl_tl_wise");
        },
        error: function(){
            $("#tl_wise_div").html("<center><font color='red'>Server error</font></center>");
        }
    });
}

get_tl_wise();
</script>