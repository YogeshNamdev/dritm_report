<link href="<?php echo base_url(); ?>includes/plugins/advance_datatable/jquery.dataTables.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/advance_datatable/jquery.dataTables.min.js"></script>

<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url(); ?>app/attendance">Attendance</a></li>
                        <li class="breadcrumb-item active">WO / CL Preference</li>
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
                        <h5 class="m-0"><b>Agle Mahine Ke Liye WO / CL Preference</b></h5>
                        <input type="month" id="pref_month" class="form-control" style="width:180px;" onchange="get_my_preferences();">
                    </div>
                </div>

                <div class="card-body">

                    <div class="alert alert-info" style="font-size:13px;">
                        <b>WO Preference:</b> Pure 4 dates deni zaroori hain (priority 1 se 4). <br>
                        <b>CL Preference:</b> Optional — jitni CL chahiye utni dates do (aapki balance CL Tracker me dikhti hai, yahan asal CL nahi lagti, sirf request hai).
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h6><b>WO Preference (4 dates)</b></h6>
                            <div class="form-group">
                                <label class="mb-0" style="font-size:12px;color:#8a94a3;">1st Choice</label>
                                <input type="date" class="form-control wo-date" id="wo_date_1">
                            </div>
                            <div class="form-group">
                                <label class="mb-0" style="font-size:12px;color:#8a94a3;">2nd Choice</label>
                                <input type="date" class="form-control wo-date" id="wo_date_2">
                            </div>
                            <div class="form-group">
                                <label class="mb-0" style="font-size:12px;color:#8a94a3;">3rd Choice</label>
                                <input type="date" class="form-control wo-date" id="wo_date_3">
                            </div>
                            <div class="form-group">
                                <label class="mb-0" style="font-size:12px;color:#8a94a3;">4th Choice</label>
                                <input type="date" class="form-control wo-date" id="wo_date_4">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h6><b>CL Preference (optional)</b></h6>
                            <div class="form-group">
                                <label class="mb-0" style="font-size:12px;color:#8a94a3;">CL Date 1</label>
                                <input type="date" class="form-control cl-date" id="cl_date_1">
                            </div>
                            <div class="form-group">
                                <label class="mb-0" style="font-size:12px;color:#8a94a3;">CL Date 2</label>
                                <input type="date" class="form-control cl-date" id="cl_date_2">
                            </div>
                            <div class="form-group">
                                <label class="mb-0" style="font-size:12px;color:#8a94a3;">CL Date 3</label>
                                <input type="date" class="form-control cl-date" id="cl_date_3">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Remark (optional)</label>
                        <input type="text" id="pref_remark" class="form-control" placeholder="Koi reason ya note...">
                    </div>

                    <button type="button" class="btn btn-primary" onclick="submit_preference();">Submit Preference</button>
                    <span id="pref_msg" class="ml-2"></span>

                </div>
            </div>

            <div class="card card-default">
                <div class="card-header"><h5 class="m-0"><b>Mera Submission (is month ka)</b></h5></div>
                <div class="card-body">
                    <div class="table-responsive" id="my_pref_div"></div>
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
    return $(tableSelector).DataTable({ destroy: true, ordering: true, paging: false, searching: false, info: false });
}

function defaultNextMonth()
{
    var d = new Date();
    d.setMonth(d.getMonth() + 1);
    var y = d.getFullYear();
    var m = ("0" + (d.getMonth()+1)).slice(-2);
    return y + "-" + m;
}

function submit_preference()
{
    var for_month = $("#pref_month").val();
    if(!for_month){ $("#pref_msg").html("<font color='red'>Month select karo.</font>"); return; }

    var wo_dates = [];
    $(".wo-date").each(function(){ if($(this).val()) wo_dates.push($(this).val()); });

    var cl_dates = [];
    $(".cl-date").each(function(){ if($(this).val()) cl_dates.push($(this).val()); });

    if(wo_dates.length > 0 && wo_dates.length < 4){
        $("#pref_msg").html("<font color='red'>WO preference me pure 4 dates do.</font>");
        return;
    }
    if(wo_dates.length == 0 && cl_dates.length == 0){
        $("#pref_msg").html("<font color='red'>Kam se kam 1 WO ya CL date do.</font>");
        return;
    }

    $("#pref_msg").html("<font color='blue'><i class='fa fa-spinner fa-spin'></i> Saving...</font>");

    $.ajax({
        type: "POST", dataType: "json",
        url: "<?php echo base_url(); ?>app/attendance/save_wo_cl_preference",
        data: {
            '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',
            for_month: for_month, wo_dates: wo_dates, cl_dates: cl_dates, remark: $("#pref_remark").val()
        },
        cache: false,
        success: function(data){
            var color = data.response ? "green" : "red";
            $("#pref_msg").html("<span style='color:"+color+"'>"+data.message+"</span>");
            if(data.response){ get_my_preferences(); }
        },
        error: function(){ $("#pref_msg").html("<span style='color:red'>Server error</span>"); }
    });
}

function get_my_preferences()
{
    var for_month = $("#pref_month").val();
    if(!for_month) return;

    $("#my_pref_div").html("<center><font color='blue'><i class='fa fa-spinner fa-spin'></i> Loading...</font></center>");

    $.ajax({
        type: "POST", dataType: "json",
        url: "<?php echo base_url(); ?>app/attendance/get_my_wo_cl_preference",
        data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>', for_month: for_month },
        cache: false,
        success: function(data){
            // form ko reset-prefill karo
            $(".wo-date, .cl-date").val("");

            if(!data.response || data.total_record == 0){
                $("#my_pref_div").html("<center class='text-muted p-3'>Is month ke liye abhi tak koi submission nahi hai.</center>");
                return;
            }

            var wo_i = 1, cl_i = 1;
            var txt = "<table class='table table-bordered table-sm'><thead><tr><th>Type</th><th>Date</th><th>Priority</th><th>Remark</th></tr></thead><tbody>";
            for(var i = 0; i < data.total_record; i++){
                var r = data.all_record[i];
                txt += "<tr><td>"+r.pref_type+"</td><td>"+r.pref_date+"</td><td>"+(r.priority || '-')+"</td><td>"+(r.remark || '-')+"</td></tr>";

                if(r.pref_type == "WO" && wo_i <= 4){ $("#wo_date_"+wo_i).val(r.pref_date); wo_i++; }
                if(r.pref_type == "CL" && cl_i <= 3){ $("#cl_date_"+cl_i).val(r.pref_date); cl_i++; }
            }
            txt += "</tbody></table>";
            $("#my_pref_div").html(txt);
        }
    });
}

$(function(){
    $("#pref_month").val(defaultNextMonth());
    get_my_preferences();
});
</script>