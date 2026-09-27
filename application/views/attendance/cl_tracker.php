<link href="<?php echo base_url(); ?>includes/plugins/advance_datatable/jquery.dataTables.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/advance_datatable/jquery.dataTables.min.js"></script>

<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url(); ?>app/attendance">Attendance</a></li>
                        <li class="breadcrumb-item active">CL Tracker</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-default color-palette-box">

                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h5 class="m-0"><b>CL Tracker</b></h5>
                        <div>
                            <small class="text-muted mr-2">Eligibility: DOJ se 6+ mahine. Max balance: 3. Har month +1 accrue (cap se upar lapse).</small>
                            <button type="button" class="btn btn-sm btn-warning" onclick="run_accrual();">Run This Month's Accrual</button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div id="accrual_msg" class="mb-2"></div>
                    <div class="table-responsive" id="cl_list_div"></div>
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

function get_cl_tracker()
{
    $("#cl_list_div").html("<center><font color='blue'><i class='fa fa-spinner fa-spin'></i> Loading...</font></center>");
    $.ajax({
        type: "POST", dataType: "json",
        url: "<?php echo base_url(); ?>app/attendance/get_cl_tracker",
        data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' },
        cache: false,
        success: function(data){
            if(!data.response){
                $("#cl_list_div").html("<center><font color='red'>"+data.message+"</font></center>");
                return;
            }
            var txt = "<table class='table table-bordered table-sm' id='tbl_cl'>";
            txt += "<thead><tr><th>Name</th><th>Agent ID</th><th>Status</th><th>DOJ</th><th>Eligible</th><th>Current Balance</th><th>Used This Month</th><th>Last Accrual</th></tr></thead><tbody>";
            for(var i = 0; i < data.total_record; i++){
                var r = data.all_record[i];
                var elig_badge = r.eligible == "Yes" ? "<span class='badge badge-success'>Yes</span>" : "<span class='badge badge-secondary'>No</span>";
                var bal_badge = r.cl_balance >= 1 ? "<span class='badge badge-info'>"+r.cl_balance+"</span>" : "<span class='badge badge-light'>"+r.cl_balance+"</span>";
                txt += "<tr>";
                txt += "<td>"+escapeHtml(r.name)+"</td>";
                txt += "<td>"+escapeHtml(r.msd_id)+"</td>";
                txt += "<td>"+r.emp_status+"</td>";
                txt += "<td>"+(r.doj || '-')+"</td>";
                txt += "<td>"+elig_badge+"</td>";
                txt += "<td>"+bal_badge+"</td>";
                txt += "<td>"+r.cl_used_this_month+"</td>";
                txt += "<td>"+(r.last_accrual_month || '-')+"</td>";
                txt += "</tr>";
            }
            txt += "</tbody></table>";
            $("#cl_list_div").html(txt);
            makeDataTable_Basic("tbl_cl");
        }
    });
}

function run_accrual()
{
    if(!confirm("Is month ka CL accrual chalana hai? (eligible active employees ko +1 CL milega, max 3 cap ke saath)")) return;

    $("#accrual_msg").html("<font color='blue'><i class='fa fa-spinner fa-spin'></i> Running...</font>");
    $.ajax({
        type: "POST", dataType: "json",
        url: "<?php echo base_url(); ?>app/attendance/run_cl_accrual",
        data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' },
        cache: false,
        success: function(data){
            var color = data.response ? "green" : "red";
            $("#accrual_msg").html("<span style='color:"+color+"'>"+data.message+"</span>");
            get_cl_tracker();
        },
        error: function(){ $("#accrual_msg").html("<span style='color:red'>Server error</span>"); }
    });
}

get_cl_tracker();
</script>