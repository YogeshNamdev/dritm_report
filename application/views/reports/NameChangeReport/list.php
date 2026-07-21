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
                        <li class="breadcrumb-item active">Name Change report</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="card card-default color-palette-box">
                <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="m-0">
                        <b>Name Change Report</b>
                    </h5>

                    <div>
                         <button type="button" class="btn btn-sm btn-warning ml-2"
                            data-toggle="modal"
                            data-target="#details_number_change_model" onclick="get_all_number_change_details();">
                            Show Number Change Details
                        </button>
                        <button type="button" class="btn btn-sm btn-success"
                            data-toggle="modal"
                            data-target="#add_number_change_request_model">
                            Add Number Change Request
                        </button>

                        <button type="button" class="btn btn-sm btn-primary ml-2"
                            data-toggle="modal"
                            data-target="#add_detail_model">
                            Add Details
                        </button>

                    </div>
                </div>
            </div>
                <div class="card-body">
                    <div id="name_change_list_div"></div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="details_number_change_model">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Number Change Details</h4>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <div id="number_change_list_div"></div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="add_detail_model">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Name Change Details</h4>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="name_change_details">
                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" name="date" id="date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" name="phone_number" id="phone_number" class="form-control" maxlength="20" required>
                    </div>
                    <div class="form-group">
                        <label>Complaint Number</label>
                        <input type="text" name="complaint_number" id="complaint_number" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Old Name</label>
                        <input type="text" name="old_name" id="old_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>New Name</label>
                        <input type="text" name="new_name" id="new_name" class="form-control" required>
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
                    <!-- <div class="form-group">
                        <label>Remark</label>
                        <input type="text" class="form-control" value="DONE" readonly>
                    </div> -->
                    <div id="err_msg"></div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="add_btn" onclick="add_name_change_details();">Submit</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add_number_change_request_model">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Number Change Request</h4>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="number_change_details">
                    
                    <div class="form-group">
                        <label>Old Phone Number</label>
                        <input type="text" oninput="this.value=this.value.replace(/[^0-9]/g,'')" maxlength="10" name="old_phone_number" id="old_phone_number" class="form-control"  required>
                    </div>
                    <div class="form-group">
                        <label>New Phone Number</label>
                        <input type="text" oninput="this.value=this.value.replace(/[^0-9]/g,'')" maxlength="10" name="new_phone_number" id="new_phone_number" class="form-control" required>
                    </div>
                   
                    <div class="form-group">
                    <label>Complaint No</label>
                            <input type="text"
                                name="other_complaint_no"
                                id="other_complaint_no"
                                class="form-control"
                                maxlength="20"
                                oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                                required>
                    </div>
                    <div id="number_err_msg"></div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="number_add_btn" onclick="add_number_change_request();">Submit</button>
            </div>
        </div>
    </div>
</div>

<script>
var isAdmin = <?php echo ($_SESSION["userdata"]["role_id"] == 1) ? "true" : "false"; ?>;

function get_all_number_change_details(){
    $("#number_change_list_div").html("<center><font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Wait, Loading...</b></font></center>");
    var base_url = '<?php echo base_url(); ?>';
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: base_url + "app/reports/get_all_number_change_details",
        data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' },
        cache: false,
        success: function(data) {
            if(data.response == true) {
                 var txt = "<table class='table table-bordered table-sm' style='font-size:13px;' id='tbl_number_change'>";

                txt += "<thead><tr>";
                txt += "<th>Sr.No.</th>";
                txt += "<th>Complaint</th>";
                txt += "<th>Old Number</th>";
                txt += "<th>New Number</th>";
                txt += "<th>Agent</th>";
                if(isAdmin == true){
                    txt += "<th>Update</th>";
                }else if(!isAdmin == true){
                    txt += "<th>Status</th>";
                }

                txt += "</tr></thead><tbody>";

                for(var i = 0; i < data.total_record; i++) {

                    var row = data.all_record[i];

                    txt += "<tr>";

                    txt += "<td>"+(i+1)+"</td>";
                    txt += "<td>"+escapeHtml(row.complaint_no)+"</td>";
                    txt += "<td>"+escapeHtml(row.oldPhone)+"</td>";
                    txt += "<td>"+escapeHtml(row.newPhone)+"</td>";
                    txt += "<td>"+escapeHtml(row.agent_name)+" ("+escapeHtml(row.agent_msd_id)+")</td>";
                    
                    // Admin update button
                    if(isAdmin == true && escapeHtml(row.updated_by) == "0"){
                        txt += "<td>";
                        txt += "<button type='button' class='btn btn-sm btn-primary' ";
                        txt += "onclick='update_number_change_detail("+row.id+")'>";
                        txt += "Update";
                        txt += "</button>";
                        txt += "</td>";
                    }else if(!isAdmin == true && escapeHtml(row.updated_by) != "0"){
                        txt += "<td>";
                        txt += "<span style='color:green;font-weight:bold'>Updated</span>";
                        txt += "</td>";
                    }

                    txt += "</tr>";
                }

                txt += "</tbody></table>";
                $("#number_change_list_div").html(txt);
                makeDataTable_Basic("tbl_number_change");
            } else {
                $("#number_change_list_div").html("<center><font color='red'><b>"+data.message+"</b></font></center>");
            }
        }
    });
}



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
    $("#add_detail_model .select2").select2({ theme: "bootstrap4", width: "100%", dropdownParent: $("#add_detail_model") });
}

function get_all_details() {
    $("#name_change_list_div").html("<center><font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Wait, Loading...</b></font></center>");
    var base_url = '<?php echo base_url(); ?>';
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: base_url + "app/reports/get_all_name_change_details",
        data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' },
        cache: false,
        success: function(data) {
            if(data.response == true) {
                 var txt = "<table class='table table-bordered table-sm' style='font-size:13px;' id='tbl_name_change'>";

                txt += "<thead><tr>";
                txt += "<th>Sr.No.</th>";
                txt += "<th>Date</th>";
                txt += "<th>Phone</th>";
                txt += "<th>Complaint</th>";
                txt += "<th>Old Name</th>";
                txt += "<th>New Name</th>";
                txt += "<th>Agent</th>";
                txt += "<th>Remark</th>";
                txt += "<th>Created By</th>";
                txt += "<th>Created At</th>";

                // Admin column
                if(isAdmin == true){
                    txt += "<th>Update</th>";
                }

                txt += "</tr></thead><tbody>";

                for(var i = 0; i < data.total_record; i++) {

                    var row = data.all_record[i];

                    txt += "<tr>";

                    txt += "<td>"+(i+1)+"</td>";
                    txt += "<td>"+escapeHtml(row.date)+"</td>";
                    txt += "<td>"+escapeHtml(row.phone_number)+"</td>";
                    txt += "<td>"+escapeHtml(row.complaint_number)+"</td>";
                    txt += "<td>"+escapeHtml(row.old_name)+"</td>";
                    txt += "<td>"+escapeHtml(row.new_name)+"</td>";
                    txt += "<td>"+escapeHtml(row.agent_name)+" ("+escapeHtml(row.agent_msd_id)+")</td>";
                    txt += "<td>"+escapeHtml(row.remark)+"</td>";
                    txt += "<td>"+escapeHtml(row.created_by_name)+"</td>";
                    txt += "<td>"+escapeHtml(row.created_at)+"</td>";

                    // Admin update button
                    if(isAdmin == true && escapeHtml(row.remark) != "DONE"){
                        txt += "<td>";
                        txt += "<button type='button' class='btn btn-sm btn-primary' ";
                        txt += "onclick='update_name_change_detail("+row.id+")'>";
                        txt += "Update";
                        txt += "</button>";
                        txt += "</td>";
                    }

                    txt += "</tr>";
                }

                txt += "</tbody></table>";
                $("#name_change_list_div").html(txt);
                makeDataTable_Basic("tbl_name_change");
            } else {
                $("#name_change_list_div").html("<center><font color='red'><b>"+data.message+"</b></font></center>");
            }
        }
    });
}


function update_name_change_detail(id)
{
    
    if(!confirm("Are you sure you want to update this record?"))
    {
        return false;
    }

    var base_url = '<?php echo base_url(); ?>';

    $.ajax({
        type: "POST",
        url: base_url + "app/reports/update_name_change_detail",
        data: { "id" : id },
        dataType: "json",
        cache: false,

        success: function(data) {

            $("#err_msg").html(
                "<span style='color:"+
                (data.response ? "green" : "red")+
                ";font-weight:bold'>"+
                data.message+
                "</span>"
            );

            get_all_details();
        }
    });
}


function update_number_change_detail(id)
{
    
    if(!confirm("Are you sure you want to update this record?"))
    {
        return false;
    }

    var base_url = '<?php echo base_url(); ?>';

    $.ajax({
        type: "POST",
        url: base_url + "app/reports/update_number_change_detail",
        data: { "id" : id },
        dataType: "json",
        cache: false,

        success: function(data) {

            $("#err_msg").html(
                "<span style='color:"+
                (data.response ? "green" : "red")+
                ";font-weight:bold'>"+
                data.message+
                "</span>"
            );

            get_all_number_change_details();
        }
    });
}

function add_name_change_details() {
    var date = $("#date").val();
    var phone = $("#phone_number").val();
    var complaint = $("#complaint_number").val();
    var oldName = $("#old_name").val();
    var newName = $("#new_name").val();
    var agent = isAdmin ? $("#agent_id").val() : "1";
    if(date == "") { $("#err_msg").html("<font color='red'><b>Select Date.</b></font>"); return; }
    if(phone.replace(/ /g, "") == "") { $("#err_msg").html("<font color='red'><b>Enter Phone Number.</b></font>"); return; }
    if(complaint.replace(/ /g, "") == "") { $("#err_msg").html("<font color='red'><b>Enter Complaint Number.</b></font>"); return; }
    if(oldName.replace(/ /g, "") == "") { $("#err_msg").html("<font color='red'><b>Enter Old Name.</b></font>"); return; }
    if(newName.replace(/ /g, "") == "") { $("#err_msg").html("<font color='red'><b>Enter New Name.</b></font>"); return; }
    if(agent == "0") { $("#err_msg").html("<font color='red'><b>Select Agent.</b></font>"); return; }
    $("#add_btn").prop("disabled", true);
    var base_url = '<?php echo base_url(); ?>';
    $.ajax({
        type: "POST",
        url: base_url + "app/reports/add_name_change_details",
        data: new FormData(document.getElementById("name_change_details")),
        dataType: "json",
        processData: false,
        contentType: false,
        cache: false,
        success: function(data) {
            $("#err_msg").html("<span style='color:"+(data.response ? "green" : "red")+";font-weight:bold'>"+data.message+"</span>");
            if(data.response) {
                $("#name_change_details")[0].reset();
                $(".select2").val("0").trigger("change");
                get_all_details();
            }
            $("#add_btn").prop("disabled", false);
        }
    });
}

function add_number_change_request() {
  
    var oldPhone = $("#old_phone_number").val();
    var newPhone = $("#new_phone_number").val();
    var complaint = $("#other_complaint_no").val();
    
    
    if(oldPhone.replace(/ /g, "") == "") { $("#number_err_msg").html("<font color='red'><b>Enter Old Phone Number.</b></font>"); return; }
    
    if (!/^\d{10}$/.test(oldPhone)) {
        $("#number_err_msg").html("<font color='red'><b>Old Phone Number must be 10 digits.</b></font>");
        return;
    }
    if(newPhone.replace(/ /g, "") == "") { $("#number_err_msg").html("<font color='red'><b>Enter New Phone Number.</b></font>"); return; }
    if (!/^\d{10}$/.test(newPhone)) {
        $("#number_err_msg").html("<font color='red'><b>New Phone Number must be 10 digits.</b></font>");
        return;
    }

    if(complaint.replace(/ /g, "") == "") { $("#number_err_msg").html("<font color='red'><b>Enter Complaint Number.</b></font>"); return; }
    
    $("#number_add_btn").prop("disabled", true);
    var base_url = '<?php echo base_url(); ?>';
    $.ajax({
        type: "POST",
        url: base_url + "app/reports/add_number_change_request",
        data: new FormData(document.getElementById("number_change_details")),
        dataType: "json",
        processData: false,
        contentType: false,
        cache: false,
        success: function(data) {
            $("#number_err_msg").html("<span style='color:"+(data.response ? "green" : "red")+";font-weight:bold'>"+data.message+"</span>");
            if(data.response) {
                $("#number_change_details")[0].reset();
                $(".select2").val("0").trigger("change");
                get_all_details();
            }
            $("#number_add_btn").prop("disabled", false);
        }
    });
}

initSelect2();
get_all_details();
</script>
