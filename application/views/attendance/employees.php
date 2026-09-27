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
                        <li class="breadcrumb-item active">Employees</li>
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
                        <h5 class="m-0"><b>Attendance Employees</b></h5>
                        <button type="button" class="btn btn-sm btn-primary" onclick="open_add_model();">Add Employee</button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive" id="employees_list_div"></div>
                </div>

            </div>
        </div>
    </section>
</div>

<!-- ADD / EDIT MODAL -->
<div class="modal fade" id="employee_model">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="employee_model_title">Add Employee</h4>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>

            <div class="modal-body">
                <form id="employee_form">

                    <input type="hidden" id="edit_mode" value="add">

                    <div class="form-group" id="user_select_div">
                        <label>Select Login User</label>
                        <select class="form-control select2" id="emp_id" style="width:100%;">
                            <option value="">-- Select --</option>
                        </select>
                        <small class="text-muted">Sirf wahi users dikh rahe hain jinka attendance-employee record abhi tak nahi bana.</small>
                    </div>

                    <div class="form-group" id="user_readonly_div" style="display:none;">
                        <label>User</label>
                        <input type="text" class="form-control" id="emp_display" readonly>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control" id="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>LOB / Campaign</label>
                                <input type="text" class="form-control" id="lob">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>FT / PT</label>
                                <select class="form-control" id="employment_type">
                                    <option value="FT">FT</option>
                                    <option value="PT">PT</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control" id="emp_status">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                    <option value="Nesting">Nesting</option>
                                    <option value="Training">Training</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Shift Group</label>
                                <select class="form-control" id="shift_group">
                                    <option value="">-- Not Set --</option>
                                    <option value="Morning">Morning</option>
                                    <option value="Evening">Evening</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>TL</label>
                                <select class="form-control select2" id="tl_emp_id" style="width:100%;">
                                    <option value="">-- No TL / Is TL --</option>
                                    <?php if($tl_list): foreach($tl_list as $t): ?>
                                        <option value="<?= $t->emp_id; ?>"><?= $t->name; ?></option>
                                    <?php endforeach; endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Gender</label>
                                <select class="form-control" id="gender">
                                    <option value="">-- Select --</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>DOJ</label>
                                <input type="date" class="form-control" id="doj">
                            </div>
                        </div>
                    </div>

                    <div class="form-group" id="employee_err_msg"></div>

                </form>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="employee_submit_btn" onclick="submit_employee();">Save</button>
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

function get_employees()
{
    $("#employees_list_div").html("<center><font color='blue'><i class='fa fa-spinner fa-spin'></i> Loading...</font></center>");
    $.ajax({
        type: "POST", dataType: "json",
        url: "<?php echo base_url(); ?>app/attendance/get_employee_list",
        data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' },
        cache: false,
        success: function(data){
            if(!data.response){
                $("#employees_list_div").html("<center><font color='red'>"+data.message+"</font></center>");
                return;
            }
            var txt = "<table class='table table-bordered table-sm' id='tbl_employees'>";
            txt += "<thead><tr><th>Name</th><th>Agent ID</th><th>LOB</th><th>TL</th><th>Type</th><th>Status</th><th>Shift Group</th><th>Action</th></tr></thead><tbody>";
            for(var i = 0; i < data.total_record; i++){
                var r = data.all_record[i];
                txt += "<tr>";
                txt += "<td>"+escapeHtml(r.name)+"</td>";
                txt += "<td>"+escapeHtml(r.msd_id)+"</td>";
                txt += "<td>"+escapeHtml(r.lob || '-')+"</td>";
                txt += "<td>"+escapeHtml(r.tl_name || '-')+"</td>";
                txt += "<td>"+r.employment_type+"</td>";
                txt += "<td>"+r.emp_status+"</td>";
                txt += "<td>"+(r.shift_group || '-')+"</td>";
                txt += "<td><button type='button' class='btn btn-sm btn-info' onclick='open_edit_model("+r.emp_id+")'>Edit</button></td>";
                txt += "</tr>";
            }
            txt += "</tbody></table>";
            $("#employees_list_div").html(txt);
            window.EMPLOYEE_ROWS = data.all_record;
            makeDataTable_Basic("tbl_employees");
        }
    });
}

function reset_employee_form()
{
    $("#employee_form")[0].reset();
    $("#emp_id").val("").trigger("change");
    $("#tl_emp_id").val("").trigger("change");
    $("#employee_err_msg").html("");
}

function open_add_model()
{
    reset_employee_form();
    $("#edit_mode").val("add");
    $("#employee_model_title").text("Add Employee");
    $("#user_select_div").show();
    $("#user_readonly_div").hide();

    $("#emp_id").html("<option value=''>-- Loading... --</option>");
    $.ajax({
        type: "POST", dataType: "json",
        url: "<?php echo base_url(); ?>app/attendance/get_unlinked_users",
        data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' },
        cache: false,
        success: function(data){
            var html = "<option value=''>-- Select --</option>";
            if(data.response){
                for(var i = 0; i < data.total_record; i++){
                    var u = data.all_record[i];
                    html += "<option value='"+u.emp_id+"' data-name='"+escapeHtml(u.user_name)+"'>"+escapeHtml(u.user_name)+" ("+escapeHtml(u.msd_id)+")</option>";
                }
            }
            $("#emp_id").html(html);
            if($.fn.select2){ $("#emp_id").select2({ theme: "bootstrap4", dropdownParent: $("#employee_model"), placeholder: "-- Select --" }); }
        }
    });

    $("#employee_model").modal("show");
}

$(document).on("change", "#emp_id", function(){
    var name = $(this).find(":selected").data("name");
    if(name){ $("#name").val(name); }
});

function open_edit_model(emp_id)
{
    var row = null;
    (window.EMPLOYEE_ROWS || []).forEach(function(r){ if(r.emp_id == emp_id) row = r; });
    if(!row){ alert("Record nahi mila."); return; }

    reset_employee_form();
    $("#edit_mode").val("edit");
    $("#employee_model_title").text("Edit Employee");
    $("#user_select_div").hide();
    $("#user_readonly_div").show();

    $("#emp_display").val(row.name + " (" + row.msd_id + ")");
    $("#emp_id").html("<option value='"+row.emp_id+"' selected>"+row.name+"</option>");
    $("#name").val(row.name);
    $("#lob").val(row.lob);
    $("#employment_type").val(row.employment_type);
    $("#emp_status").val(row.emp_status);
    $("#shift_group").val(row.shift_group || "");
    $("#gender").val(row.gender || "");
    $("#doj").val(row.doj || "");
    $("#tl_emp_id").val(row.tl_emp_id || "").trigger("change");

    $("#employee_model").modal("show");
}

function submit_employee()
{
    var emp_id = $("#emp_id").val();
    var name = $("#name").val().trim();

    if(!emp_id){
        $("#employee_err_msg").html("<font color='red'><b>User select karo.</b></font>");
        return;
    }
    if(name == ""){
        $("#employee_err_msg").html("<font color='red'><b>Naam bharo.</b></font>");
        return;
    }

    var mode = $("#edit_mode").val();
    var url = (mode == "edit") ? "update_employee" : "add_employee";

    $("#employee_submit_btn").prop("disabled", true);
    $("#employee_err_msg").html("<font color='blue'><i class='fa fa-spinner fa-spin'></i> Saving...</font>");

    $.ajax({
        type: "POST", dataType: "json",
        url: "<?php echo base_url(); ?>app/attendance/"+url,
        data: {
            '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',
            emp_id: emp_id, name: name, lob: $("#lob").val(),
            employment_type: $("#employment_type").val(), emp_status: $("#emp_status").val(),
            shift_group: $("#shift_group").val(), tl_emp_id: $("#tl_emp_id").val(),
            gender: $("#gender").val(), doj: $("#doj").val()
        },
        cache: false,
        success: function(data){
            var color = data.response ? "green" : "red";
            $("#employee_err_msg").html("<span style='color:"+color+";font-weight:bold'>"+data.message+"</span>");
            if(data.response){
                get_employees();
                setTimeout(function(){ $("#employee_model").modal("hide"); }, 700);
            }
            $("#employee_submit_btn").prop("disabled", false);
        },
        error: function(){
            $("#employee_err_msg").html("<span style='color:red'>Server error</span>");
            $("#employee_submit_btn").prop("disabled", false);
        }
    });
}

get_employees();
</script>