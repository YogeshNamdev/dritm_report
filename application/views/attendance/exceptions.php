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
                        <li class="breadcrumb-item active">Exceptions</li>
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
                        <h5 class="m-0"><b>Attendance Exceptions</b></h5>

                        <div class="d-flex align-items-center">
                            <div class="form-group mb-0 mr-2">
                                <label class="mr-1 mb-0">From</label>
                                <input type="date" id="filter_date_from" class="form-control" value="<?= date('Y-m-01'); ?>" onchange="get_exceptions();">
                            </div>
                            <div class="form-group mb-0 mr-2">
                                <label class="mr-1 mb-0">To</label>
                                <input type="date" id="filter_date_to" class="form-control" value="<?= date('Y-m-d'); ?>" onchange="get_exceptions();">
                            </div>

                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#add_exception_model">
                                Add Exception
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive" id="exceptions_list_div"></div>
                </div>

            </div>
        </div>
    </section>
</div>

<!-- ADD/EDIT EXCEPTION MODAL -->
<div class="modal fade" id="add_exception_model">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">Add / Update Exception</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>

            <div class="modal-body">
                <form id="exception_form" method="post">

                    <div class="form-group">
                        <label>Employee</label>
                        <select class="form-control select2" id="emp_id" name="emp_id">
                            <option value="0">-- Select --</option>
                            <?php if($user_list != FALSE): foreach($user_list as $u): ?>
                                <option value="<?= $u->emp_id; ?>"><?= $u->name; ?> (<?= $u->msd_id; ?>)</option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" name="exception_date" id="exception_date" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Exception Minutes</label>
                        <input type="number" name="exception_minutes" id="exception_minutes" class="form-control" value="0" required>
                        <small class="text-muted">Net login minutes me ye add hoga (approved grace / WFH / manual override ke liye).</small>
                    </div>

                    <div class="form-group">
                        <label>Reason</label>
                        <textarea name="reason" id="reason" class="form-control" required></textarea>
                    </div>

                    <div class="form-group" id="err_msg"></div>

                </form>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="au_btn" onclick="add_exception();">Submit</button>
            </div>

        </div>
    </div>
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

function add_exception()
{
    var emp_id = $("#emp_id").val();
    var exception_date = $("#exception_date").val();
    var exception_minutes = $("#exception_minutes").val();
    var reason = $("#reason").val();

    if(emp_id == "0" || emp_id == ""){
        $("#emp_id").focus();
        $("#err_msg").html("<font color='red'><b>Select Employee.</b></font>");
    } else if(exception_date.replace(/ /gi, "") == ""){
        $("#exception_date").focus();
        $("#err_msg").html("<font color='red'><b>Select Date.</b></font>");
    } else if(reason.replace(/ /gi, "") == ""){
        $("#reason").focus();
        $("#err_msg").html("<font color='red'><b>Enter Reason.</b></font>");
    } else {
        $("#au_btn").prop("disabled", true);
        $("#err_msg").html("<font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Wait, Saving...</b></font>");

        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>app/attendance/add_exception",
            dataType: "json",
            data: {
                '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',
                emp_id: emp_id, exception_date: exception_date,
                exception_minutes: exception_minutes, reason: reason
            },
            cache: false,
            success: function(data){
                if(data.response == true){
                    $("#err_msg").html("<span style='color:green;font-weight:bold'>Saved Successfully</span>");
                    $("#exception_form")[0].reset();
                    $("#emp_id").val("0").trigger("change");
                    get_exceptions();
                } else {
                    $("#err_msg").html("<span style='color:red;font-weight:bold'>"+data.message+"</span>");
                }
                $("#au_btn").prop("disabled", false);
            },
            error: function(){
                $("#err_msg").html("<span style='color:red'>Server error</span>");
                $("#au_btn").prop("disabled", false);
            }
        });
    }
}

function get_exceptions()
{
    var date_from = $("#filter_date_from").val();
    var date_to = $("#filter_date_to").val();

    $("#exceptions_list_div").html("<center><font color='blue'><i class='fa fa-spinner fa-spin'></i> Loading...</font></center>");

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url(); ?>app/attendance/get_exceptions",
        data: {
            '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',
            date_from: date_from, date_to: date_to
        },
        cache: false,
        success: function(data){
            if(!data.response){
                $("#exceptions_list_div").html("<center><font color='red'>"+escapeHtml(data.message)+"</font></center>");
                return;
            }

            var txt = "<table class='table table-bordered table-sm' id='tbl_exceptions'>";
            txt += "<thead><tr><th>Date</th><th>Name</th><th>Agent ID</th><th>Minutes</th><th>Reason</th><th>Added By</th></tr></thead><tbody>";
            for(var i = 0; i < data.total_record; i++){
                var r = data.all_record[i];
                txt += "<tr>";
                txt += "<td>"+r.exception_date+"</td>";
                txt += "<td>"+escapeHtml(r.name)+"</td>";
                txt += "<td>"+escapeHtml(r.msd_id)+"</td>";
                txt += "<td>"+r.exception_minutes+"</td>";
                txt += "<td>"+escapeHtml(r.reason)+"</td>";
                txt += "<td>"+r.added_by+"</td>";
                txt += "</tr>";
            }
            txt += "</tbody></table>";
            $("#exceptions_list_div").html(txt);
            makeDataTable_Basic("tbl_exceptions");
        },
        error: function(){
            $("#exceptions_list_div").html("<center><font color='red'>Server error</font></center>");
        }
    });
}

$(function(){
    if($.fn.select2){
        $("#add_exception_model .select2").select2({
            theme: "bootstrap4", width: "100%",
            dropdownParent: $("#add_exception_model"), placeholder: "-- Select --"
        });
    }
    get_exceptions();
});
</script>