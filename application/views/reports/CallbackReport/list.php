<link href="<?php echo base_url(); ?>includes/plugins/advance_datatable/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>includes/plugins/advance_datatable/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>includes/plugins/select2/css/select2.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>includes/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css" rel="stylesheet">
<script src="<?php echo base_url(); ?>includes/plugins/advance_datatable/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>includes/plugins/advance_datatable/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url(); ?>includes/plugins/advance_datatable/buttons.print.min.js"></script>
<script src="<?php echo base_url(); ?>includes/plugins/advance_datatable/buttons.colVis.min.js"></script>
<script src="<?php echo base_url(); ?>includes/plugins/advance_datatable/pdfmake.min.js"></script>
<script src="<?php echo base_url(); ?>includes/plugins/advance_datatable/vfs_fonts.js"></script>
<script src="<?php echo base_url(); ?>includes/plugins/advance_datatable/buttons.html5.min.js"></script>
<script src="<?php echo base_url(); ?>includes/plugins/advance_datatable/jszip.min.js"></script>
<script src="<?php echo base_url(); ?>includes/plugins/select2/js/select2.full.min.js"></script>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="<?= base_url(); ?>app/home">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Call back Report</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-default color-palette-box">
                <div class="card-header">
                    <h5 class="m-0 d-flex justify-content-between">
                        <b>Callback Report</b>
                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#add_detail_model">Add Details</button>
                    </h5>
                </div>
                <div class="card-body">
                    <div id="callback_list_div"></div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="add_detail_model">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Callback Details</h4>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="callback_details">
                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" name="date" id="date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" name="phone_number" id="phone_number" class="form-control" maxlength="20" required>
                    </div>
                    <?php if($_SESSION["userdata"]["role_id"] == 1): ?>
                    <div class="form-group">
                        <label>Agent ID</label>
                        <select class="form-control select2" id="agent_id" name="agent_id">
                            <option value="0">-- Select --</option>
                            <?php if($user_list != FALSE){ foreach($user_list as $userlist){ echo "<option value='".$userlist->emp_id."'>".$userlist->user_name." (".$userlist->msd_id.")</option>"; }} ?>
                        </select>
                    </div>
                    <?php endif; ?>
                    <div class="form-group">
                        <label>Department</label>
                        <select class="form-control select2" id="department" name="department">
                            <option value="0">-- Select --</option>
                            <?php if($department_list != FALSE){ foreach($department_list as $departmentList){ echo "<option value='".$departmentList->Departid."'>".$departmentList->Departname_E."</option>"; }} ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Remark</label>
                        <select class="form-control" id="remark_type" name="remark_type" onchange="toggleOtherRemark('#remark_type', '#remark_other_box');">
                            <option value="0">-- Select --</option>
                            <option value="Callback">Callback</option>
                            <option value="User Busy">User Busy</option>
                            <option value="Call Disconnected">Call Disconnected</option>
                            <option value="Switched Off">Switched Off</option>
                            <option value="Not Reachable">Not Reachable</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="form-group" id="remark_other_box" style="display:none;">
                        <label>Other Remark</label>
                        <textarea class="form-control" id="remark_other" name="remark_other"></textarea>
                    </div>
                    <div id="err_msg"></div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="add_btn" onclick="add_callback_details();">Submit</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="update_detail_model">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Callback Details</h4>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="update_callback_details">
                    <input type="hidden" id="hidden_id" name="hidden_id">
                    <?php if($_SESSION["userdata"]["role_id"] == 1): ?>
                    <div class="form-group">
                        <label>Assign Agent</label>
                        <select class="form-control select2" id="update_agent_id" name="agent_id">
                            <option value="0">-- Select --</option>
                            <?php if($user_list != FALSE){ foreach($user_list as $userlist){ echo "<option value='".$userlist->emp_id."'>".$userlist->user_name." (".$userlist->msd_id.")</option>"; }} ?>
                        </select>
                    </div>
                    <?php endif; ?>
                    <div class="form-group">
                        <label>Remark</label>
                        <select class="form-control" id="update_remark_type" name="remark_type" onchange="toggleOtherRemark('#update_remark_type', '#update_remark_other_box');">
                            <option value="0">-- Select --</option>
                            <option value="Callback">Callback</option>
                            <option value="User Busy">User Busy</option>
                            <option value="Call Disconnected">Call Disconnected</option>
                            <option value="Switched Off">Switched Off</option>
                            <option value="Not Reachable">Not Reachable</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="form-group" id="update_remark_other_box" style="display:none;">
                        <label>Other Remark</label>
                        <textarea class="form-control" id="update_remark_other" name="remark_other"></textarea>
                    </div>
                    <div id="up_err_msg"></div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="update_btn" onclick="update_callback_details();">Update</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="history_model">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Callback History</h4>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body" id="history_list_div"></div>
        </div>
    </div>
</div>


<script>
var callbackRows = {};
var isAdmin = <?php echo ($_SESSION["userdata"]["role_id"] == 1) ? "true" : "false"; ?>;
var currentUpdateAgentId = 0;

function escapeHtml(value) {
    return $("<div>").text(value == null ? "" : value).html();
}

function makeDataTable_Basic(tableID) {
    var tableSelector = "#" + tableID;
    if (!$(tableSelector).length || !$.fn.DataTable) return null;
    if ($.fn.DataTable.isDataTable(tableSelector)) $(tableSelector).DataTable().destroy();
    return $(tableSelector).DataTable({
        destroy: true,
        ordering: true,
        dom: 'Bfrtip',
        buttons: ['colvis', { extend: 'print', exportOptions: { columns: ':visible' } }, { extend: 'excelHtml5' }]
    });
}

function initSelect2() {
    if (!$.fn.select2) return;
    $(".select2").select2({ theme: "bootstrap4", width: "100%" });
    $("#add_detail_model .select2").select2({ theme: "bootstrap4", width: "100%", dropdownParent: $("#add_detail_model") });
    $("#update_detail_model .select2").select2({ theme: "bootstrap4", width: "100%", dropdownParent: $("#update_detail_model") });
}

function toggleOtherRemark(selectId, boxId) {
    if ($(selectId).val() == "Other") {
        $(boxId).show();
    } else {
        $(boxId).hide().find("textarea").val("");
    }
}

function get_all_details() {
    $("#callback_list_div").html("<center><font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Wait, Loading...</b></font></center>");
    var base_url = '<?php echo base_url(); ?>';
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: base_url + "app/reports/get_all_callback_details",
        data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' },
        cache: false,
        success: function(data) {
            if(data.response == true) {
                callbackRows = {};
                var txt = "<table class='table table-bordered table-sm' style='font-size:13px;' id='tbl_callback'>";
                txt += "<thead><tr><th>Sr.No.</th><th>Date</th><th>Phone Number</th><th>Agent</th><th>Department</th><th>Remark</th><th>Created By</th><th>Created At</th><th>Action</th></tr></thead><tbody>";
                for(var i = 0; i < data.total_record; i++) {
                    var row = data.all_record[i];
                    callbackRows[row.id] = row;
                    txt += "<tr>";
                    txt += "<td>"+(i+1)+"</td>";
                    txt += "<td>"+escapeHtml(row.date)+"</td>";
                    txt += "<td>"+escapeHtml(row.phone_number)+"</td>";
                    txt += "<td>"+escapeHtml(row.assigned_agent_name)+" ("+escapeHtml(row.assigned_msd_id)+")</td>";
                    txt += "<td>"+escapeHtml(row.Departname_E)+"</td>";
                    txt += "<td>"+escapeHtml(row.latest_remark)+"</td>";
                    txt += "<td>"+escapeHtml(row.created_by_name)+"</td>";
                    txt += "<td>"+escapeHtml(row.created_at)+"</td>";
                    
                    txt += "<td class='text-nowrap'>";
                     if(data.emp_id == row.created_by || isAdmin){
                        txt += "<button type='button' class='btn btn-primary btn-sm mr-1' onclick='open_update_model("+row.id+")'>Update</button>";
                    }
                        // if(isAdmin) {
                        txt += "<button type='button' class='btn btn-info btn-sm' onclick='open_history("+row.id+")'>History</button>";
                    // }
                    txt += "</td></tr>";
                }
                txt += "</tbody></table>";
                $("#callback_list_div").html(txt);
                makeDataTable_Basic("tbl_callback");
            } else {
                $("#callback_list_div").html("<center><font color='red'><b>"+data.message+"</b></font></center>");
            }
        }
    });
}

function validateRemark(prefix) {
    var remarkType = $(prefix + "remark_type").val();
    var other = $(prefix + "remark_other").val();
    if(remarkType == "0" || remarkType == "") return false;
    if(remarkType == "Other" && other.replace(/ /g, "") == "") return false;
    return true;
}

function add_callback_details() {
    var date = $("#date").val();
    var phone = $("#phone_number").val();
    var department = $("#department").val();
    var agent = isAdmin ? $("#agent_id").val() : "1";
    if(date == "") { $("#err_msg").html("<font color='red'><b>Select Date.</b></font>"); return; }
    if(phone.replace(/ /g, "") == "") { $("#err_msg").html("<font color='red'><b>Enter Phone Number.</b></font>"); return; }
    if(agent == "0") { $("#err_msg").html("<font color='red'><b>Select Agent.</b></font>"); return; }
    if(department == "0") { $("#err_msg").html("<font color='red'><b>Select Department.</b></font>"); return; }
    if(!validateRemark("#")) { $("#err_msg").html("<font color='red'><b>Select or enter remark.</b></font>"); return; }
    $("#add_btn").prop("disabled", true);
    var base_url = '<?php echo base_url(); ?>';
    $.ajax({
        type: "POST",
        url: base_url + "app/reports/add_callback_details",
        data: new FormData(document.getElementById("callback_details")),
        dataType: "json",
        processData: false,
        contentType: false,
        cache: false,
        success: function(data) {
            $("#err_msg").html("<span style='color:"+(data.response ? "green" : "red")+";font-weight:bold'>"+data.message+"</span>");
            if(data.response) {
                $("#callback_details")[0].reset();
                $(".select2").val("0").trigger("change");
                $("#remark_other_box").hide();
                get_all_details();
            }
            $("#add_btn").prop("disabled", false);
        }
    });
}

function open_update_model(id) {
    var row = callbackRows[id];
    currentUpdateAgentId = parseInt(row.assigned_agent_id, 10);
    $("#hidden_id").val(id);
    $("#update_remark_type").val("0");
    $("#update_remark_other").val("");
    $("#update_agent_id").val(row.assigned_agent_id).trigger("change");
    toggleOtherRemark("#update_remark_type", "#update_remark_other_box");
    $("#up_err_msg").html("");
    $("#update_detail_model").modal("show");
}

function update_callback_details() {
    if(isAdmin && $("#update_agent_id").val() == "0") { $("#up_err_msg").html("<font color='red'><b>Select Agent.</b></font>"); return; }
    var assignmentChanged = isAdmin && parseInt($("#update_agent_id").val(), 10) !== currentUpdateAgentId;
    var remarkSelected = $("#update_remark_type").val() != "0" && $("#update_remark_type").val() != "";
    if(!assignmentChanged && !remarkSelected) { $("#up_err_msg").html("<font color='red'><b>Select another agent or add a remark.</b></font>"); return; }
    if(remarkSelected && !validateRemark("#update_")) { $("#up_err_msg").html("<font color='red'><b>Select or enter remark.</b></font>"); return; }
    $("#update_btn").prop("disabled", true);
    var base_url = '<?php echo base_url(); ?>';
    $.ajax({
        type: "POST",
        url: base_url + "app/reports/update_callback_details",
        data: new FormData(document.getElementById("update_callback_details")),
        dataType: "json",
        processData: false,
        contentType: false,
        cache: false,
        success: function(data) {
            $("#up_err_msg").html("<span style='color:"+(data.response ? "green" : "red")+";font-weight:bold'>"+data.message+"</span>");
            if(data.response) get_all_details();
            $("#update_btn").prop("disabled", false);
        }
    });
}

function open_history(id) {
    $("#history_list_div").html("<center><font color='blue'><b>Loading...</b></font></center>");
    $("#history_model").modal("show");
    var base_url = '<?php echo base_url(); ?>';
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: base_url + "app/reports/get_callback_history",
        data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>', callback_id:id },
        cache: false,
        success: function(data) {
            if(data.response == true) {
                var txt = "<table class='table table-bordered table-sm'><thead><tr><th>Action</th><th>Remark</th><th>Previous Agent</th><th>New Agent</th><th>Updated By</th><th>Updated At</th></tr></thead><tbody>";
                for(var i = 0; i < data.total_record; i++) {
                    var row = data.all_record[i];
                    txt += "<tr><td>"+escapeHtml(row.action_type)+"</td><td>"+escapeHtml(row.remark_text)+"</td><td>"+escapeHtml(row.previous_agent_name)+"</td><td>"+escapeHtml(row.new_agent_name)+"</td><td>"+escapeHtml(row.updated_by_name)+"</td><td>"+escapeHtml(row.updated_at)+"</td></tr>";
                }
                txt += "</tbody></table>";
                $("#history_list_div").html(txt);
            } else {
                $("#history_list_div").html("<center><font color='red'><b>"+data.message+"</b></font></center>");
            }
        }
    });
}

initSelect2();
get_all_details();
</script>
