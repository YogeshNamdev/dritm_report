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
                        <li class="breadcrumb-item active">Matrix</li>
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
                        <h5 class="m-0"><b>Attendance Matrix</b></h5>

                        <div class="d-flex align-items-center">
                            <div class="form-group mb-0 mr-2">
                                <input type="month" id="filter_month" class="form-control" value="<?= date('Y-m'); ?>" onchange="get_matrix();">
                            </div>

                            <?php if(in_array((int)$_SESSION["userdata"]["role_id"], array(1))): ?>
                            <div class="form-group mb-0 mr-2">
                                <select class="form-control select2" id="filter_lob" style="width:160px;" onchange="get_matrix();">
                                    <option value="">-- All LOB --</option>
                                    <?php if($lob_list): foreach($lob_list as $l): ?>
                                        <option value="<?= $l->lob; ?>"><?= $l->lob; ?></option>
                                    <?php endforeach; endif; ?>
                                </select>
                            </div>

                            <div class="form-group mb-0">
                                <select class="form-control select2" id="filter_tl" style="width:160px;" onchange="get_matrix();">
                                    <option value="">-- All TL --</option>
                                    <?php if($tl_list): foreach($tl_list as $t): ?>
                                        <option value="<?= $t->emp_id; ?>"><?= $t->name; ?></option>
                                    <?php endforeach; endif; ?>
                                </select>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive" id="matrix_div"></div>
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

function statusBadge(status)
{
    var map = { 'P':'success', 'HD':'warning', 'AA':'danger', 'WO':'secondary', 'CL':'info', 'Leave':'primary', 'Inactive':'dark', '-':'light' };
    var cls = map[status] || 'light';
    return "<span class='badge badge-"+cls+"'>"+status+"</span>";
}

function get_matrix()
{
    var month = $("#filter_month").val();
    var lob = $("#filter_lob").length ? $("#filter_lob").val() : "";
    var tl_id = $("#filter_tl").length ? $("#filter_tl").val() : "";

    if(!month){ return; }

    $("#matrix_div").html("<center><font color='blue'><i class='fa fa-spinner fa-spin'></i> Loading...</font></center>");

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url(); ?>app/attendance/get_attendance_matrix",
        data: {
            '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',
            month: month, lob: lob, tl_id: tl_id
        },
        cache: false,
        success: function(data){
            if(!data.response){
                $("#matrix_div").html("<center><font color='red'>"+data.message+"</font></center>");
                return;
            }

            var txt = "<table class='table table-bordered table-sm' style='font-size:12px;' id='tbl_matrix'>";
            txt += "<thead><tr><th>Name</th><th>ID</th><th>LOB</th><th>TL</th>";
            data.date_columns.forEach(function(d){
                txt += "<th>"+d.substring(8,10)+"</th>";
            });
            txt += "<th>P</th><th>AA</th><th>HD</th><th>WO</th>";
            txt += "</tr></thead><tbody>";

            for(var i = 0; i < data.total_record; i++){
                var r = data.all_record[i];
                txt += "<tr>";
                txt += "<td>"+r.name+"</td>";
                txt += "<td>"+r.msd_id+"</td>";
                txt += "<td>"+(r.lob || '-')+"</td>";
                txt += "<td>"+(r.tl_name || '-')+"</td>";
                data.date_columns.forEach(function(d){
                    txt += "<td>"+statusBadge(r.days[d])+"</td>";
                });
                txt += "<td>"+r.present_count+"</td>";
                txt += "<td>"+r.absent_count+"</td>";
                txt += "<td>"+r.hd_count+"</td>";
                txt += "<td>"+r.wo_count+"</td>";
                txt += "</tr>";
            }
            txt += "</tbody></table>";
            $("#matrix_div").html(txt);
            makeDataTable_Basic("tbl_matrix");
        },
        error: function(){
            $("#matrix_div").html("<center><font color='red'>Server error</font></center>");
        }
    });
}

$(function(){
    if($.fn.select2){
        $(".select2").select2({ theme: "bootstrap4", placeholder: "-- Select --" });
    }
    get_matrix();
});
</script>
