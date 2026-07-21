<link href="<?php echo base_url(); ?>includes/plugins/advance_datatable/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>includes/plugins/advance_datatable/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>includes/plugins/select2/css/select2.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>includes/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css" rel="stylesheet">
<script src="<?php echo base_url(); ?>includes/plugins/advance_datatable/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>includes/plugins/advance_datatable/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url(); ?>includes/plugins/advance_datatable/buttons.print.min.js"></script>
<script src="<?php echo base_url(); ?>includes/plugins/advance_datatable/buttons.colVis.min.js"></script>
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
                        <li class="breadcrumb-item active">Portal Issues Report</li>
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
                        <b>Portal Issues Report</b>
                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#add_detail_model">Add Details</button>
                    </h5>
                </div>
                <div class="card-body">
                    <div id="portal_issues_list_div"></div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="add_detail_model">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Portal Issues Details</h4>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="portal_issues_details">
                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" name="date" id="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Case Reason</label>
                        <textarea class="form-control" id="case_reason" name="case_reason" rows="8"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Reason Why case not registered</label>
                        <select class="form-control" id="reason_case_not_registered" name="reason_case_not_registered" onchange="toggleOtherRemark('#reason_case_not_registered', '#remark_other_box');">
                            <option value="">-- Select --</option>
                            <option value="Case not registered due to page issue">Case not registered due to page issue</option>
                            <option value="The case was not registered due to page issue but at the same time a call was made to the officer">The case was not registered due to page issue but at the same time a call was made to the officer</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="form-group" id="remark_other_box" style="display:none;">
                        <label>Extra Remark</label>
                        <textarea class="form-control" id="other_remark" name="other_remark"></textarea>
                    </div>
                   
                    <div class="form-group">
                        <label>Agent ID</label>
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


                    
                    <div id="err_msg"></div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="add_btn" onclick="add_portal_issue_details();">Submit</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="update_portal_model">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Portal Issues Details</h4>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="update_portal_issues_details">
                    <input type="hidden" name="update_id" id="update_id">
                    <div class="form-group">
                        <label>Follow Up Remark</label>
                        <textarea class="form-control" id="follow_up_remark" name="follow_up_remark" rows="8"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Agent ID</label>
                        <select class="form-control select2" id="follow_up_agent_id" name="follow_up_agent_id">
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
                    <div id="update_err_msg"></div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="update_btn" onclick="update_portal_issue_details();">Update</button>
            </div>`
        </div>
    </div>
</div>

<div class="modal fade" id="portal_issue_history">
    <div class="modal-dialog modal-lg">
        <!-- modal-lg / modal-xl use kar sakte ho -->

        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">Follow Up History</h4>

                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div id="portal_issue_history_div"></div>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    Close
                </button>
            </div>

        </div>
    </div>
</div>

<script>
var isAdmin = <?php echo ($_SESSION["userdata"]["role_id"] == 1) ? "true" : "false"; ?>;

function escapeHtml(value) {
    return $("<div>").text(value == null ? "" : value).html();
}

function toggleOtherRemark(selectId, boxId) {
    if ($(selectId).val() != "") {
        $(boxId).show();
    } else {
        $(boxId).hide().find("textarea").val("");
    }
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
    $("#add_detail_model .select2").select2({ theme: "bootstrap4", width: "100%", dropdownParent: $("#add_detail_model") });
}

function get_all_details() {
    $("#portal_issues_list_div").html("<center><font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Wait, Loading...</b></font></center>");
    var base_url = '<?php echo base_url(); ?>';
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: base_url + "app/reports/get_all_portal_issue_details",
        data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' },
        cache: false,
        success: function(data) {
            if(data.response == true) {
                 var txt = "<table class='table table-bordered table-sm' style='font-size:13px;' id='tbl_portal_issue'>";

                txt += "<thead><tr>";
                txt += "<th>Sr.No.</th>";
                txt += "<th>Date</th>";
                txt += "<th>Case Reason</th>";
                txt += "<th>Why not registered</th>";
                txt += "<th>Other Remark</th>";
                txt += "<th>Agent</th>";
                txt += "<th>Action</th>";
                txt += "</tr></thead><tbody>";

                for(var i = 0; i < data.total_record; i++) {

                    var row = data.all_record[i];

                    txt += "<tr>";

                    txt += "<td>"+(i+1)+"</td>";
                    txt += "<td>"+escapeHtml(row.date)+"</td>";
                    txt += "<td>"+escapeHtml(row.case_reason)+"</td>";
                    txt += "<td>"+escapeHtml(row.reason_case_not_registered)+"</td>";
                    txt += "<td>"+escapeHtml(row.other_remark)+"</td>";
                    txt += "<td>"+escapeHtml(row.user_name)+"</td>";
                      txt += "<td>";
                        // Update Icon Button
                        txt += "<button type='button' class='btn btn-sm btn-primary me-1' ";
                        txt += "onclick='update_portal_issue_details_set("+row.id+")' ";
                        txt += "title='Update'>";
                        txt += "<i class='fa fa-edit'></i>";
                        txt += "</button>";

                        // History Icon Button
                        txt += "<button type='button' class='btn btn-sm btn-success' ";
                        txt += "onclick='get_portal_issue_history("+row.id+")' ";
                        txt += "title='History'>";
                        txt += "<i class='fa fa-history'></i>";
                        txt += "</button>";

                        txt += "</td>";
                    txt += "</tr>";
                }

                txt += "</tbody></table>";
                $("#portal_issues_list_div").html(txt);
                makeDataTable_Basic("tbl_portal_issue");
            } else {
                $("#portal_issues_list_div").html("<center><font color='red'><b>"+data.message+"</b></font></center>");
            }
        }
    });
}


function update_portal_issue_details_set(id)
{
    
    $("#update_id").val(id);
    $("#update_err_msg").html("");
    $("#update_portal_model").modal("show");

}

function update_portal_issue_details() {
    var follow_up_remark = $("#follow_up_remark").val();
    var agent = $("#follow_up_agent_id").val();
    if(follow_up_remark == "") { $("#update_err_msg").html("<font color='red'><b>Enter Follow Up Remark.</b></font>"); return; }
    if(agent == "0") { $("#update_err_msg").html("<font color='red'><b>Select Agent.</b></font>"); return; }
    $("#update_btn").prop("disabled", true);
    var base_url = '<?php echo base_url(); ?>';
    $.ajax({
        type: "POST",
        url: base_url + "app/reports/update_portal_issue_details",
        data: new FormData(document.getElementById("update_portal_issues_details")),
        dataType: "json",
        processData: false,
        contentType: false,
        cache: false,
        success: function(data) {
            $("#update_err_msg").html("<span style='color:"+(data.response ? "green" : "red")+";font-weight:bold'>"+data.message+"</span>");
            if(data.response) {
                $("#update_portal_issues_details")[0].reset();
                $(".select2").val("0").trigger("change");
                get_all_details();
            }
            $("#update_btn").prop("disabled", false);
        }
    });
}

function add_portal_issue_details() {
    var date = $("#date").val();
    var case_reason = $("#case_reason").val();
    var reason_case_not_registered = $("#reason_case_not_registered").val();
    var other_remark = $("#other_remark").val();
    var agent = $("#agent_id").val();
    if(date == "") { $("#err_msg").html("<font color='red'><b>Select Date.</b></font>"); return; }
    if(case_reason == "") { $("#err_msg").html("<font color='red'><b>Enter Case Reason.</b></font>"); return; }
    if(reason_case_not_registered == "") { $("#err_msg").html("<font color='red'><b>Enter Reason for Not Registered.</b></font>"); return; }
    if(reason_case_not_registered == "Other" && other_remark == "") { $("#err_msg").html("<font color='red'><b>Enter Other Remark.</b></font>"); return; } 
    if(agent == "0") { $("#err_msg").html("<font color='red'><b>Select Agent.</b></font>"); return; }
    $("#add_btn").prop("disabled", true);
    var base_url = '<?php echo base_url(); ?>';
    $.ajax({
        type: "POST",
        url: base_url + "app/reports/add_portal_issue_details",
        data: new FormData(document.getElementById("portal_issues_details")),
        dataType: "json",
        processData: false,
        contentType: false,
        cache: false,
        success: function(data) {
            $("#err_msg").html("<span style='color:"+(data.response ? "green" : "red")+";font-weight:bold'>"+data.message+"</span>");
            if(data.response) {
                $("#portal_issues_details")[0].reset();
                $(".select2").val("0").trigger("change");
                get_all_details();
            }
            $("#add_btn").prop("disabled", false);
        }
    });
}

function get_portal_issue_history(id)
{
    $("#portal_issue_history").modal("show");

    $("#portal_issue_history_div").html(
        "<center><font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Wait, Loading...</b></font></center>"
    );

    var base_url = '<?php echo base_url(); ?>';

    $.ajax({
        type: "POST",
        url: base_url + "app/reports/get_portal_issue_history",
        data: {
            'id': id,
            '<?php echo $this->security->get_csrf_token_name(); ?>':
            '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "json",
        cache: false,

        success: function(data)
        {
            if(data.response)
            {
                var txt = "";

                txt += "<div class='table-responsive'>";
                txt += "<table class='table table-bordered table-striped table-hover' id='tbl_portal_issue_history' style='font-size:13px;'>";
                
                txt += "<thead class='table-dark'>";
                txt += "<tr>";
                
                txt += "<th>Date</th>";
                txt += "<th>Remark</th>";
                txt += "<th>Agent</th>";
                txt += "</tr>";
                txt += "</thead>";

                txt += "<tbody>";

                for(var i = 0; i < data.total_record; i++)
                {
                    var row = data.all_record[i];

                    txt += "<tr>";

                   
                    txt += "<td>"+escapeHtml(row.added_at)+"</td>";
                    txt += "<td>"+escapeHtml(row.follow_up_remark)+"</td>";
                    txt += "<td>"+escapeHtml(row.added_by_name)+"</td>";

                    txt += "</tr>";
                }

                txt += "</tbody>";
                txt += "</table>";
                txt += "</div>";

                $("#portal_issue_history_div").html(txt);
                makeDataTable_Basic("tbl_portal_issue_history");
            }
            else
            {
                $("#portal_issue_history_div").html(
                    "<center><font color='red'><b>"+data.message+"</b></font></center>"
                );
            }
        }
    });
}

initSelect2();
get_all_details();
</script>
