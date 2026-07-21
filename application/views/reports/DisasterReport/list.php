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
                        <b>Disaster Report</b>
                        <span>
                            <button type="button" class="btn btn-sm btn-info" onclick="openIsatModal();">ISAT Number DEOCs</button>
                            <button type="button" class="btn btn-sm btn-success" onclick="openDeocsModal();">DEOCs Numbers</button>
                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#add_detail_model">Add Details</button>
                        </span>
                    </h5>
                </div>
                <div class="card-body">
                    <div id="disaster_list_div"></div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="add_detail_model">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Disaster Details</h4>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="disaster_details">
                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" name="date" id="date" class="form-control" value="<?php echo date('Y-m-d'); ?>"  required>
                    </div>
                    <div class="form-group">
                        <label>Case Number</label>
                        <input type="text" name="case_no" id="case_no" class="form-control" maxlength="20" required>
                    </div>
                    <div class="form-group" >
                        <label>Sub Situation</label>
                        <textarea class="form-control" id="sub_situation" name="sub_situation"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Citizen Name</label>
                        <input type="text" name="citizen_name" id="citizen_name" class="form-control" maxlength="100" required>
                    </div>
                    <div class="form-group">
                        <label>Citizen Phone Number</label>
                        <input type="text" name="citizen_mobile_no" id="citizen_mobile_no" class="form-control" maxlength="20" required>
                    </div>
                    <div class="form-group">
                        <label>District</label>
                        <select class="form-control select2" id="district" name="district">
                            <option value="0">-- Select --</option>
                            <?php if($district_list != FALSE){ foreach($district_list as $districtList){ echo "<option value='".$districtList->District_Code."'>".$districtList->District_Name_E."</option>"; }} ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Agent</label>
                        <select class="form-control select2" id="agent_id" name="agent_id">
                            <option value="0">-- Select --</option>
                            <?php 
                            $allowed_ids = array(27048,27029,27015,27034);

                            if($user_list != FALSE){ 
                                foreach($user_list as $userList){ 

                                    if(in_array($userList->emp_id, $allowed_ids))
                                    {
                                        echo "<option value='".$userList->emp_id."'>".$userList->user_name." (".$userList->msd_id.")</option>"; 
                                    }

                                } 
                            } 
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Case Remark</label>
                        <select class="form-control" id="case_remark" name="case_remark" onchange="toggleOtherRemark('#case_remark', '#remark_other_box');">
                            <option value="">-- Select --</option>
                            <option value="DEOC Call Done">DEOC Call Done</option>
                            <option value="Closed">Closed</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="form-group" id="remark_other_box" style="display:none;">
                        <label>Extra Remark</label>
                        <textarea class="form-control" id="other_remark" name="other_remark"></textarea>
                    </div>
                    <div id="err_msg"></div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="add_btn" onclick="add_disaster_details();">Submit</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="isat_numbers_model">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">ISAT Number DEOCs</h4>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h5 class="m-0"><span id="isat_form_title">New Entry</span></h5>
                    </div>
                    <div class="card-body">
                        <form id="isat_number_form">
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                            <input type="hidden" name="id" id="isat_id" value="0">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>District Officer</label>
                                        <input type="text" name="district_officer" id="isat_district_officer" class="form-control" maxlength="255">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Mobile Number</label>
                                        <input type="text" name="mobile_number" id="isat_mobile_number" class="form-control" maxlength="52">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Other Number</label>
                                        <input type="text" name="other_number" id="isat_other_number" class="form-control" maxlength="52">
                                    </div>
                                </div>
                            </div>
                            <div id="isat_msg"></div>
                            <button type="button" class="btn btn-primary btn-sm" id="isat_save_btn" onclick="saveIsatNumber();">Save</button>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="resetIsatForm();">New Entry</button>
                        </form>
                    </div>
                </div>
                <div id="isat_numbers_list"></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deocs_numbers_model">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">DEOCs Numbers</h4>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="card card-outline card-success">
                    <div class="card-header">
                        <h5 class="m-0"><span id="deocs_form_title">New Entry</span></h5>
                    </div>
                    <div class="card-body">
                        <form id="deocs_number_form">
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                            <input type="hidden" name="id" id="deocs_id" value="0">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>EOCS Name</label>
                                        <input type="text" name="eocs_name" id="deocs_eocs_name" class="form-control" maxlength="128">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Incharge Name</label>
                                        <input type="text" name="incharge_name" id="deocs_incharge_name" class="form-control" maxlength="128">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Incharge Mobile</label>
                                        <input type="text" name="incharge_mobile" id="deocs_incharge_mobile" class="form-control" maxlength="128">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Toll Free No.</label>
                                        <input type="text" name="toll_free_no" id="deocs_toll_free_no" class="form-control" maxlength="32">
                                    </div>
                                </div>
                            </div>
                            <label>Other Contact Numbers</label>
                            <div id="deocs_other_numbers_box"></div>
                            <button type="button" class="btn btn-outline-success btn-sm mb-2" onclick="addDeocsOtherNumberField('');"><i class="fa fa-plus"></i></button>
                            <div id="deocs_msg"></div>
                            <button type="button" class="btn btn-primary btn-sm" id="deocs_save_btn" onclick="saveDeocsNumber();">Save</button>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="resetDeocsForm();">New Entry</button>
                        </form>
                    </div>
                </div>
                <div id="deocs_numbers_list"></div>
            </div>
        </div>
    </div>
</div>

<script>
var callbackRows = {};
var isatRows = {};
var deocsRows = {};
var deocsTable = null;
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

function csrfData(extraData) {
    extraData = extraData || {};
    extraData['<?php echo $this->security->get_csrf_token_name(); ?>'] = '<?php echo $this->security->get_csrf_hash(); ?>';
    return extraData;
}

function initSelect2() {
    if (!$.fn.select2) return;
    $(".select2").select2({ theme: "bootstrap4", width: "100%" });
    $("#add_detail_model .select2").select2({ theme: "bootstrap4", width: "100%", dropdownParent: $("#add_detail_model") });
    $("#update_detail_model .select2").select2({ theme: "bootstrap4", width: "100%", dropdownParent: $("#update_detail_model") });
}

function toggleOtherRemark(selectId, boxId) {
    if ($(selectId).val() != "") {
        $(boxId).show();
    } else {
        $(boxId).hide().find("textarea").val("");
    }
}

function openIsatModal() {
    resetIsatForm();
    $("#isat_numbers_model").modal("show");
    getIsatNumbers();
}

function resetIsatForm() {
    $("#isat_number_form")[0].reset();
    $("#isat_id").val("0");
    $("#isat_form_title").text("New Entry");
    $("#isat_msg").html("");
    $("#isat_save_btn").prop("disabled", false);
}

function getIsatNumbers() {
    $("#isat_numbers_list").html("<center><font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Wait, Loading...</b></font></center>");
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: '<?php echo base_url(); ?>app/reports/get_all_isat_number_deocs',
        data: csrfData(),
        cache: false,
        success: function(data) {
            if(data.response == true) {
                isatRows = {};
                var txt = "<table class='table table-bordered table-sm' style='font-size:13px;' id='tbl_isat_numbers'>";
                txt += "<thead><tr><th>Sr.No.</th><th>District Officer</th><th>Mobile Number</th><th>Other Number</th><th>Added At</th><th>Action</th></tr></thead><tbody>";
                for(var i = 0; i < data.total_record; i++) {
                    var row = data.all_record[i];
                    isatRows[row.id] = row;
                    txt += "<tr>";
                    txt += "<td>"+(i+1)+"</td>";
                    txt += "<td>"+escapeHtml(row.district_officer)+"</td>";
                    txt += "<td>"+escapeHtml(row.mobile_number)+"</td>";
                    txt += "<td>"+escapeHtml(row.other_number)+"</td>";
                    txt += "<td>"+escapeHtml(row.added_at)+"</td>";
                    txt += "<td><button type='button' class='btn btn-xs btn-info mr-1' onclick='editIsatNumber("+row.id+");'><i class='fa fa-edit'></i></button><button type='button' class='btn btn-xs btn-danger' onclick='deleteIsatNumber("+row.id+");'><i class='fa fa-trash'></i></button></td>";
                    txt += "</tr>";
                }
                txt += "</tbody></table>";
                $("#isat_numbers_list").html(txt);
                makeDataTable_Basic("tbl_isat_numbers");
            } else {
                $("#isat_numbers_list").html("<center><font color='red'><b>"+data.message+"</b></font></center>");
            }
        }
    });
}

function editIsatNumber(id) {
    var row = isatRows[id];
    if(!row) return;
    $("#isat_id").val(row.id);
    $("#isat_district_officer").val(row.district_officer);
    $("#isat_mobile_number").val(row.mobile_number);
    $("#isat_other_number").val(row.other_number);
    $("#isat_form_title").text("Edit Entry");
    $("#isat_msg").html("");
}

function saveIsatNumber() {
    if($("#isat_district_officer").val().replace(/ /g, "") == "") { $("#isat_msg").html("<font color='red'><b>Enter District Officer.</b></font>"); return; }
    if($("#isat_mobile_number").val().replace(/ /g, "") == "") { $("#isat_msg").html("<font color='red'><b>Enter Mobile Number.</b></font>"); return; }
    $("#isat_save_btn").prop("disabled", true);
    $.ajax({
        type: "POST",
        url: '<?php echo base_url(); ?>app/reports/save_isat_number_deocs',
        data: new FormData(document.getElementById("isat_number_form")),
        dataType: "json",
        processData: false,
        contentType: false,
        cache: false,
        success: function(data) {
            $("#isat_msg").html("<span style='color:"+(data.response ? "green" : "red")+";font-weight:bold'>"+data.message+"</span>");
            if(data.response) {
                resetIsatForm();
                getIsatNumbers();
            }
            $("#isat_save_btn").prop("disabled", false);
        }
    });
}

function deleteIsatNumber(id) {
    if(!confirm("Delete this ISAT/DEOC number?")) return;
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: '<?php echo base_url(); ?>app/reports/delete_isat_number_deocs',
        data: csrfData({ id: id }),
        cache: false,
        success: function(data) {
            $("#isat_msg").html("<span style='color:"+(data.response ? "green" : "red")+";font-weight:bold'>"+data.message+"</span>");
            if(data.response) {
                resetIsatForm();
                getIsatNumbers();
            }
        }
    });
}

function openDeocsModal() {
    resetDeocsForm();
    $("#deocs_numbers_model").modal("show");
    getDeocsNumbers();
}

function splitContactNumbers(numbers) {
    if(!numbers) return [""];
    var parts = numbers.split(",");
    var clean = [];
    for(var i = 0; i < parts.length; i++) {
        var value = $.trim(parts[i]);
        if(value != "") clean.push(value);
    }
    return clean.length ? clean : [""];
}

function addDeocsOtherNumberField(value) {
    var index = $("#deocs_other_numbers_box .deocs-number-row").length + 1;
    var row = "<div class='input-group input-group-sm mb-2 deocs-number-row'>";
    row += "<div class='input-group-prepend'><span class='input-group-text'>Contact Number "+index+"</span></div>";
    row += "<input type='text' name='other_numbers[]' class='form-control deocs-other-number' value='"+escapeHtml(value)+"'>";
    row += "<div class='input-group-append'><button type='button' class='btn btn-outline-danger' onclick='removeDeocsOtherNumberField(this);'><i class='fa fa-minus'></i></button></div>";
    row += "</div>";
    $("#deocs_other_numbers_box").append(row);
}

function refreshDeocsOtherNumberLabels() {
    $("#deocs_other_numbers_box .deocs-number-row").each(function(index) {
        $(this).find(".input-group-text").text("Contact Number " + (index + 1));
    });
}

function removeDeocsOtherNumberField(button) {
    if($("#deocs_other_numbers_box .deocs-number-row").length <= 1) {
        $(button).closest(".deocs-number-row").find("input").val("");
        return;
    }
    $(button).closest(".deocs-number-row").remove();
    refreshDeocsOtherNumberLabels();
}

function resetDeocsForm() {
    $("#deocs_number_form")[0].reset();
    $("#deocs_id").val("0");
    $("#deocs_form_title").text("New Entry");
    $("#deocs_msg").html("");
    $("#deocs_other_numbers_box").html("");
    addDeocsOtherNumberField("");
    $("#deocs_save_btn").prop("disabled", false);
}

function formatDeocsDetails(numbers) {
    var contacts = splitContactNumbers(numbers);
    var txt = "<div class='p-2 bg-light border'><b>Other Contact Numbers</b><ol class='mb-0 mt-1'>";
    for(var i = 0; i < contacts.length; i++) {
        txt += "<li>"+escapeHtml(contacts[i])+"</li>";
    }
    txt += "</ol></div>";
    return txt;
}

function getDeocsNumbers() {
    $("#deocs_numbers_list").html("<center><font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Wait, Loading...</b></font></center>");
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: '<?php echo base_url(); ?>app/reports/get_all_deocs_numbers',
        data: csrfData(),
        cache: false,
        success: function(data) {
            if(data.response == true) {
                deocsRows = {};
                var txt = "<table class='table table-bordered table-sm' style='font-size:13px;' id='tbl_deocs_numbers'>";
                txt += "<thead><tr><th>Sr.No.</th><th>EOCS Name</th><th>Incharge Name</th><th>Incharge Mobile</th><th>Toll Free No.</th><th>More Information</th><th>Action</th></tr></thead><tbody>";
                for(var i = 0; i < data.total_record; i++) {
                    var row = data.all_record[i];
                    deocsRows[row.id] = row;
                    txt += "<tr data-id='"+row.id+"'>";
                    txt += "<td>"+(i+1)+"</td>";
                    txt += "<td>"+escapeHtml(row.eocs_name)+"</td>";
                    txt += "<td>"+escapeHtml(row.incharge_name)+"</td>";
                    txt += "<td>"+escapeHtml(row.incharge_mobile)+"</td>";
                    txt += "<td>"+escapeHtml(row.toll_free_no)+"</td>";
                    txt += "<td><button type='button' class='btn btn-xs btn-secondary deocs-more-btn'><i class='fa fa-info-circle'></i></button></td>";
                    txt += "<td><button type='button' class='btn btn-xs btn-info mr-1' onclick='editDeocsNumber("+row.id+");'><i class='fa fa-edit'></i></button><button type='button' class='btn btn-xs btn-danger' onclick='deleteDeocsNumber("+row.id+");'><i class='fa fa-trash'></i></button></td>";
                    txt += "</tr>";
                }
                txt += "</tbody></table>";
                $("#deocs_numbers_list").html(txt);
                deocsTable = makeDataTable_Basic("tbl_deocs_numbers");
            } else {
                $("#deocs_numbers_list").html("<center><font color='red'><b>"+data.message+"</b></font></center>");
            }
        }
    });
}

$(document).on("click", ".deocs-more-btn", function() {
    if(!deocsTable) return;
    var tr = $(this).closest("tr");
    var row = deocsTable.row(tr);
    var rowData = deocsRows[tr.data("id")];
    if(row.child.isShown()) {
        $("div", row.child()).slideUp(150, function() {
            row.child.hide();
        });
    } else if(rowData) {
        row.child(formatDeocsDetails(rowData.other_numbers)).show();
        $("div", row.child()).hide().slideDown(150);
    }
});

function editDeocsNumber(id) {
    var row = deocsRows[id];
    if(!row) return;
    $("#deocs_id").val(row.id);
    $("#deocs_eocs_name").val(row.eocs_name);
    $("#deocs_incharge_name").val(row.incharge_name);
    $("#deocs_incharge_mobile").val(row.incharge_mobile);
    $("#deocs_toll_free_no").val(row.toll_free_no);
    $("#deocs_other_numbers_box").html("");
    var contacts = splitContactNumbers(row.other_numbers);
    for(var i = 0; i < contacts.length; i++) {
        addDeocsOtherNumberField(contacts[i]);
    }
    $("#deocs_form_title").text("Edit Entry");
    $("#deocs_msg").html("");
}

function saveDeocsNumber() {
    if($("#deocs_eocs_name").val().replace(/ /g, "") == "") { $("#deocs_msg").html("<font color='red'><b>Enter EOCS Name.</b></font>"); return; }
    if($("#deocs_incharge_name").val().replace(/ /g, "") == "") { $("#deocs_msg").html("<font color='red'><b>Enter Incharge Name.</b></font>"); return; }
    if($("#deocs_incharge_mobile").val().replace(/ /g, "") == "") { $("#deocs_msg").html("<font color='red'><b>Enter Incharge Mobile.</b></font>"); return; }
    if($("#deocs_toll_free_no").val().replace(/ /g, "") == "") { $("#deocs_msg").html("<font color='red'><b>Enter Toll Free No.</b></font>"); return; }
    var hasOtherNumber = false;
    $(".deocs-other-number").each(function() {
        if($(this).val().replace(/ /g, "") != "") hasOtherNumber = true;
    });
    if(!hasOtherNumber) { $("#deocs_msg").html("<font color='red'><b>Enter at least one other contact number.</b></font>"); return; }
    $("#deocs_save_btn").prop("disabled", true);
    $.ajax({
        type: "POST",
        url: '<?php echo base_url(); ?>app/reports/save_deocs_numbers',
        data: new FormData(document.getElementById("deocs_number_form")),
        dataType: "json",
        processData: false,
        contentType: false,
        cache: false,
        success: function(data) {
            $("#deocs_msg").html("<span style='color:"+(data.response ? "green" : "red")+";font-weight:bold'>"+data.message+"</span>");
            if(data.response) {
                resetDeocsForm();
                getDeocsNumbers();
            }
            $("#deocs_save_btn").prop("disabled", false);
        }
    });
}

function deleteDeocsNumber(id) {
    if(!confirm("Delete this DEOC number?")) return;
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: '<?php echo base_url(); ?>app/reports/delete_deocs_numbers',
        data: csrfData({ id: id }),
        cache: false,
        success: function(data) {
            $("#deocs_msg").html("<span style='color:"+(data.response ? "green" : "red")+";font-weight:bold'>"+data.message+"</span>");
            if(data.response) {
                resetDeocsForm();
                getDeocsNumbers();
            }
        }
    });
}

function get_all_details() {
    $("#disaster_list_div").html("<center><font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Wait, Loading...</b></font></center>");
    var base_url = '<?php echo base_url(); ?>';
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: base_url + "app/reports/get_all_disasters_details",
        data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' },
        cache: false,
        success: function(data) {
            if(data.response == true) {
                callbackRows = {};
                var txt = "<table class='table table-bordered table-sm' style='font-size:13px;' id='tbl_callback'>";
                txt += "<thead><tr><th>Sr.No.</th><th>Date</th><th>Case No.</th><th>Sub Situation</th><th>Citizen Name</th><th>Citizen Mobile No.</th><th>District</th><th>Case Remark</th><th>Other Remark</th><th>Created By</th><th>Created At</th></tr></thead><tbody>";
                for(var i = 0; i < data.total_record; i++) {
                    var row = data.all_record[i];
                    callbackRows[row.id] = row;
                    txt += "<tr>";
                    txt += "<td>"+(i+1)+"</td>";
                    txt += "<td>"+escapeHtml(row.date)+"</td>";
                    txt += "<td>ACCIDENT"+escapeHtml(row.case_no)+"</td>";
                    txt += "<td>"+escapeHtml(row.sub_situation)+"</td>";
                    txt += "<td>"+escapeHtml(row.citizen_name)+"</td>";
                    txt += "<td>"+escapeHtml(row.citizen_mobile_no)+"</td>";
                    txt += "<td>"+escapeHtml(row.District_Name_E)+"</td>";
                    
                    txt += "<td>"+escapeHtml(row.case_remark)+"</td>";

                    txt += "<td>"+escapeHtml(row.other_remark)+"</td>";
                    txt += "<td>"+escapeHtml(row.user_name)+"</td>";
                    txt += "<td>"+escapeHtml(row.added_at)+"</td>";
                    
                    txt += "</tr>";
                }
                txt += "</tbody></table>";
                $("#disaster_list_div").html(txt);
                makeDataTable_Basic("tbl_callback");
            } else {
                $("#disaster_list_div").html("<center><font color='red'><b>"+data.message+"</b></font></center>");
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

function add_disaster_details() {
    var date = $("#date").val();
    var case_no = $("#case_no").val();
    var citizen_name = $("#citizen_name").val();
    var citizen_mobile_no = $("#citizen_mobile_no").val();
    var district = $("#district").val();

    var agent_id = $("#agent_id").val();
    var case_remark = $("#case_remark").val();
    var other_remark = $("#other_remark").val();
    var sub_situation = $("#sub_situation").val();
    
    if(date == "") { $("#err_msg").html("<font color='red'><b>Select Date.</b></font>"); return; }
    if(case_no.replace(/ /g, "") == "") { $("#err_msg").html("<font color='red'><b>Enter Case Number.</b></font>"); return; }
    if(sub_situation.replace(/ /g, "") == "") { $("#err_msg").html("<font color='red'><b>Enter Sub Situation.</b></font>"); return; }
    if(citizen_name.replace(/ /g, "") == "") { $("#err_msg").html("<font color='red'><b>Enter Citizen Name.</b></font>"); return; }
    if(citizen_mobile_no.replace(/ /g, "") == "") { $("#err_msg").html("<font color='red'><b>Enter Mobile Number.</b></font>"); return; }
    if(district == "0") { $("#err_msg").html("<font color='red'><b>Select District.</b></font>"); return; }
    if(agent_id == "0") { $("#err_msg").html("<font color='red'><b>Select Agent.</b></font>"); return; }
    if(case_remark == "") { $("#err_msg").html("<font color='red'><b>Select Case Remark.</b></font>"); return; }
    if(case_remark == "Other" && other_remark.replace(/ /g, "") == "") { $("#err_msg").html("<font color='red'><b>Enter Other Remark.</b></font>"); return; }
    $("#add_btn").prop("disabled", true);
    var base_url = '<?php echo base_url(); ?>';
    $.ajax({
        type: "POST",
        url: base_url + "app/reports/add_disaster_details",
        data: new FormData(document.getElementById("disaster_details")),
        dataType: "json",
        processData: false,
        contentType: false,
        cache: false,
        success: function(data) {
            $("#err_msg").html("<span style='color:"+(data.response ? "green" : "red")+";font-weight:bold'>"+data.message+"</span>");
            if(data.response) {
                $("#disaster_details")[0].reset();
                $(".select2").val("0").trigger("change");
                $("#remark_other_box").hide();
                get_all_details();
            }
            $("#add_btn").prop("disabled", false);
        }
    });
}



initSelect2();
get_all_details();
</script>
