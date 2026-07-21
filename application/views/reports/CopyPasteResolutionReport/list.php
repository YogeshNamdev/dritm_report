<link href="<?php echo base_url(); ?>includes/plugins/advance_datatable/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>includes/plugins/advance_datatable/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>includes/plugins/select2/css/select2.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>includes/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/advance_datatable/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/advance_datatable/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/advance_datatable/buttons.print.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/advance_datatable/buttons.colVis.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/advance_datatable/pdfmake.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/advance_datatable/vfs_fonts.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/advance_datatable/buttons.html5.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/advance_datatable/jszip.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/select2/js/select2.full.min.js"></script>
<script src="<?= base_url(); ?>includes/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>

<script>
function makeDataTable_Basic(tableID)
 {
  var tableSelector = '#'+tableID;

  if (!$(tableSelector).length || !$.fn.DataTable) {
      return null;
  }

  if ($.fn.DataTable.isDataTable(tableSelector)) {
      $(tableSelector).DataTable().destroy();
  }

  return $(tableSelector).DataTable({
                        destroy: true,
                        ordering: true,
                        dom: 'Bfrtip',
                        buttons: [
                            'colvis',
                            { extend: 'print', exportOptions: { columns: ':visible' }  },
                            { extend: 'pdfHtml5', orientation: 'landscape', pageSize: 'LEGAL',  download: 'open' },
                            { extend: 'excelHtml5', customize: function( xlsx ) { var sheet = xlsx.xl.worksheets['sheet1.xml']; $('row c[r^="C"]', sheet).attr( 's', '2' ); }}
                          ]
                 });
 }
</script>
  <!-- Bootstrap Switch -->


<div class="content-wrapper">

    <!-- HEADER -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="<?= base_url(); ?>app/home">Home</a>
                        </li>
                        <li class="breadcrumb-item active">
                            Copy/Paste Resolution Report
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <section class="content">
        <div class="container-fluid">

            <!-- MAIN CARD -->
            <div class="card card-default color-palette-box">

                <!-- CARD HEADER -->
                <div class="card-header">

                    <div class="d-flex justify-content-between align-items-center flex-wrap">

                        <!-- Heading -->
                        <h5 class="m-0">
                            <b>Copy and Paste Resolution Report</b>
                        </h5>

                        <!-- Right Controls -->
                        <div class="d-flex align-items-center">

                            <?php if(in_array($_SESSION["userdata"]["role_id"], array(1,2,4, 3))): ?>
                            <div class="form-group mb-0 mr-2">
                                <select class="form-control select2"
                                        id="remark"
                                        name="remark"
                                        onchange="get_all_details();">

                                    <option value="0">-- Select --</option>
                                    <option value="L1 Officer call done">L1 Officer call done</option>
                                    <option value="Ringing">Ringing</option>
                                    <option value="Switch off">Switch off</option>
                                    <option value="Busy">Busy</option>
                                    <option value="other">Other</option>

                                </select>
                            </div>
                            <?php endif; ?>

                            <?php if(in_array($_SESSION["userdata"]["role_id"], array(1, 2, 4))): ?>
                            <button type="button"
                                    class="btn btn-sm btn-primary"
                                    data-toggle="modal"
                                    data-target="#add_detail_model">
                                Add Details
                            </button>
                            <?php endif; ?>

                        </div>

                    </div>

                </div>
                <!-- /.card-header -->

                <!-- CARD BODY -->
                <div class="card-body">

                    <!-- SHIFT TABS -->
                    <ul class="nav nav-tabs mb-3" id="shift_tabs" role="tablist">

                        <li class="nav-item">
                            <a class="nav-link active"
                               href="javascript:void(0);"
                               data-shift="morning"
                               onclick="setShift('morning', this);">
                                Morning Shift
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link"
                               href="javascript:void(0);"
                               data-shift="evening"
                               onclick="setShift('evening', this);">
                                Evening Shift
                            </a>
                        </li>

                    </ul>

                    <!-- DATA SECTION -->
                    <div class="row">
                        <div class="col-md-12" id="students_list_div">

                        </div>
                    </div>

                </div>
                <!-- /.card-body -->

            </div>
            <!-- /.card -->

        </div>
        <!-- /.container-fluid -->
    </section>

</div>
<!-- /.content-wrapper -->


<!-- ADD DETAIL MODAL -->
<div class="modal fade" id="add_detail_model">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">Add Details</h4>

                <button type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>
            </div>

            <div class="modal-body">

                <form id="report_details"
                      method="post"
                      enctype="multipart/form-data">

                    <div class="form-group">
                        <label>Date</label>

                        <input type="date"
                               name="date"
                               id="date"
                               class="form-control"
                               required>
                    </div>

                    <div class="form-group">
                        <label>Agent ID</label>

                        <select class="form-control select2"
                                id="agent_id"
                                name="agent_id">

                            <option value="0">-- Select --</option>

                            <?php
                            if($user_list != FALSE)
                            {
                                foreach($user_list as $userlist)
                                {
                                    echo "<option value='".$userlist->emp_id."'>
                                            ".$userlist->user_name." (".$userlist->msd_id.")
                                          </option>";
                                }
                            }
                            ?>

                        </select>
                    </div>

                    <div class="form-group">
                        <label>Complaint No</label>

                        <!-- <input type="number"
                               name="complaint_no"
                               id="complaint_no"
                               class="form-control"
                               required> -->
                               <input type="text"
                                    name="complaint_no"
                                    id="complaint_no"
                                    class="form-control"
                                    maxlength="20"
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                                    required>
                    </div>

                    <div class="form-group">
                        <label>Department</label>

                        <select class="form-control select2"
                                id="department"
                                name="department">

                            <option value="0">-- Select --</option>

                            <?php
                            if($department_list != FALSE)
                            {
                                foreach($department_list as $departmentList)
                                {
                                    echo "<option value='".$departmentList->Departid."'>
                                            ".$departmentList->Departname_E."
                                          </option>";
                                }
                            }
                            ?>

                        </select>
                    </div>

                    <div class="form-group">
                        <label>District</label>

                        <select class="form-control select2"
                                id="district"
                                name="district">

                            <option value="0">-- Select --</option>

                            <?php
                            if($district_list != FALSE)
                            {
                                foreach($district_list as $districtlist)
                                {
                                    echo "<option value='".$districtlist->District_Code."'>
                                            ".$districtlist->District_Name_E."
                                          </option>";
                                }
                            }
                            ?>

                        </select>
                    </div>

                    <div class="form-group">
                        <label>Issue</label>

                        <select class="form-control select2"
                                id="issue"
                                name="issue">

                            <option value="0">-- Select --</option>

                            <?php
                            if($issue_list != FALSE)
                            {
                                foreach($issue_list as $issuelist)
                                {
                                    echo "<option value='".$issuelist->id."'>
                                            ".$issuelist->issue."
                                          </option>";
                                }
                            }
                            ?>

                        </select>
                    </div>

                    <div class="form-group">
                        <label>Complaint Status</label>
                        <select class="form-control select2" id="complaint_status" name="complaint_status">
                            <option value="0">-- Select --</option>
                            <option value="Open">Open</option>
                            <option value="PC">PC</option>
                            <option value="WIP">WIP</option>
                            <option value="OWA">OWA</option>
                            <option value="Closed">Closed</option>
                        <option value="Force Closed">Force Closed</option>
                        </select>
                    </div>

                    <div class="form-group" id="err_msg"></div>

                </form>

            </div>

            <div class="modal-footer justify-content-between">

                <button type="button"
                        class="btn btn-danger"
                        data-dismiss="modal">
                    Close
                </button>

                <button type="button"
                        class="btn btn-primary"
                        id="au_btn"
                        onclick="add_details();">
                    Submit
                </button>

            </div>

        </div>

    </div>

</div>


<!-- UPDATE DETAIL MODAL -->
<div class="modal fade" id="update_detail_model">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h4 class="modal-title">Update Details</h4>

                <button type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <div class="modal-body">

                <form id="update_report_details"
                      method="post"
                      enctype="multipart/form-data">

                    <input type="hidden"
                           name="hidden_id"
                           id="hidden_id"
                           required>

                    <div class="form-group">

                        <label>Help-Desk Remark</label>

                        <select class="form-control select2"
                                id="helpdesk_remark"
                                name="helpdesk_remark"
                                onchange="chage_remarks(this.value);">

                            <option value="">-- Select --</option>
                            <option value="L1 Officer call done">L1 Officer call done</option>
                            <option value="Ringing">Ringing</option>
                            <option value="Switch off">Switch off</option>
                            <option value="Busy">Busy</option>
                            <option value="other">Other</option>

                        </select>

                    </div>

                    <div class="form-group other_remark_div" style="display:none;">

                        <label>Other Remark</label>

                        <textarea name="other_remark"
                                  id="other_remark"
                                  class="form-control"
                                  required></textarea>

                    </div>

                    <div class="form-group">

                        <label>Call Date</label>

                        <input type="date"
                               name="call_date"
                               id="call_date"
                               class="form-control"
                               required>

                    </div>

                    <div class="form-group">

                        <label>Officer Mobile No</label>

                        <input type="number"
                               name="officer_mobile_no"
                               id="officer_mobile_no"
                               class="form-control"
                               required>

                    </div>

                    <div class="form-group">
                        <label>Complaint Status</label>
                        <select class="form-control select2" id="update_complaint_status" name="complaint_status">
                            <option value="0">-- Select --</option>
                            <option value="Open">Open</option>
                            <option value="PC">PC</option>
                            <option value="WIP">WIP</option>
                            <option value="OWA">OWA</option>
                            <option value="Closed">Closed</option>
                        <option value="Force Closed">Force Closed</option>
                        </select>
                    </div>

                    <div class="form-group" id="up_err_msg"></div>

                </form>

            </div>

            <div class="modal-footer justify-content-between">

                <button type="button"
                        class="btn btn-danger"
                        data-dismiss="modal">
                    Close
                </button>

                <button type="button"
                        class="btn btn-primary"
                        id="au_btn"
                        onclick="Update_details();">
                    Update
                </button>

            </div>

        </div>

    </div>

</div>

<div class="modal fade" id="status_update_model">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Complaint Status</h4>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="status_record_id">
                <div class="form-group">
                    <label>Complaint Current Status</label>
                    <select class="form-control" id="popup_complaint_status">
                        <option value="0">-- Select --</option>
                        <option value="Open">Open</option>
                        <option value="PC">PC</option>
                        <option value="WIP">WIP</option>
                        <option value="OWA">OWA</option>
                        <option value="Closed">Closed</option>
                        <option value="Force Closed">Force Closed</option>
                    </select>
                </div>
                <div id="status_update_msg"></div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="status_update_btn" onclick="updateComplaintStatus();">Update</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="history_model">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Complaint History</h4>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div id="history_summary" class="mb-2"></div>
                <div id="history_list_div"></div>
            </div>
        </div>
    </div>
</div>



   
  <script>
    var detailRows = {};
    var detailRowsById = {};
    var reportKey = "copy_paste";
    var activeShift = getCurrentShift();

    function chage_remarks(value){
        if(value == "")
        {
            $(".other_remark_div").hide();
            $("#other_remark").prop("required", false);
           
        }else{
            $(".other_remark_div").show();
            $("#other_remark").prop("required", true);
        }
    }

    function getCurrentShift()
    {
        var now = new Date();
        var minutes = (now.getHours() * 60) + now.getMinutes();
        return (minutes > 870 && minutes <= 1320) ? "evening" : "morning";
    }

    function initializeShiftTabs()
    {
        $("#shift_tabs .nav-link").removeClass("active");
        $("#shift_tabs .nav-link[data-shift='" + activeShift + "']").addClass("active");
    }

    function setShift(shift, tab)
    {
        activeShift = shift;
        $("#shift_tabs .nav-link").removeClass("active");
        $(tab).addClass("active");
        get_all_details();
    }

    function escapeHtml(value)
    {
        return $("<div>").text(value == null ? "" : value).html();
    }

    function captureDataTableState()
    {
        if(!$.fn.DataTable || !$.fn.DataTable.isDataTable("#tbl_students")) {
            return null;
        }
        var table = $("#tbl_students").DataTable();
        var columnSearch = [];
        table.columns().every(function(index) {
            columnSearch[index] = this.search();
        });
        return {
            page: table.page(),
            length: table.page.len(),
            search: table.search(),
            order: table.order(),
            columnSearch: columnSearch
        };
    }

    function restoreDataTableState(table, state)
    {
        if(!table || !state) {
            return;
        }
        table.page.len(state.length);
        table.search(state.search || "");
        if(state.order) {
            table.order(state.order);
        }
        if(state.columnSearch) {
            table.columns().every(function(index) {
                this.search(state.columnSearch[index] || "");
            });
        }
        table.draw(false);
        var pageInfo = table.page.info();
        table.page(Math.min(state.page || 0, Math.max(pageInfo.pages - 1, 0))).draw(false);
    }

    function formatResolutionDetails(record)
    {
        return "<div class='p-3' style='background:#f4f6f9;'>" +
                    "<div class='row'>" +
                        "<div class='col-md-4'>" +
                            "<label><b>Call Date</b></label>" +
                            "<div>" + escapeHtml(record.call_date) + "</div>" +
                        "</div>" +
                        "<div class='col-md-4'>" +
                            "<label><b>Officer Mobile No</b></label>" +
                            "<div>" + escapeHtml(record.officer_mobile_no) + "</div>" +
                        "</div>" +
                        "<div class='col-md-4'>" +
                            "<label><b>Complaint Status</b></label>" +
                            "<div>" + escapeHtml(record.complaint_status || "Open") + "</div>" +
                        "</div>" +
                        "<div class='col-md-12 mt-3'>" +
                            "<label><b>Helpdesk Remark</b></label>" +
                            "<div style='background:#fff;padding:10px;border-radius:5px;border:1px solid #ddd;'>" +
                                escapeHtml(record.helpdesk_remark) +
                            "</div>" +
                        "</div>" +
                        "<div class='col-md-12 mt-3'>" +
                            "<label><b>Other Remark</b></label>" +
                            "<div style='background:#fff;padding:10px;border-radius:5px;border:1px solid #ddd;'>" +
                                escapeHtml(record.remark) +
                            "</div>" +
                        "</div>" +
                    "</div>" +
                "</div>";
    }

    function initializeSelect2()
    {
        if (!$.fn.select2) {
            return;
        }

        $("#add_detail_model .select2").select2({
            theme: "bootstrap4",
            width: "100%",
            dropdownParent: $("#add_detail_model"),
            placeholder: "-- Select --"
        });
        $("#update_detail_model .select2").select2({
            theme: "bootstrap4",
            width: "100%",
            dropdownParent: $("#update_detail_model"),
            placeholder: "-- Select --"
        });
    }

    function isL1OfficerCallDone(value)
    {
        return (value || "").toLowerCase().indexOf("l1 officer call done") !== -1;
    }

    function add_details()
    {
    var date = $("#date").val();
    var agent_id = $("#agent_id").val();
    var complaint_no = $("#complaint_no").val();
    var department = $("#department").val();
    var district = $("#district").val();
    var issue = $("#issue").val();
    if(date.replace(/ /gi , "") == "")
    {
        $("#date").focus();
        $("#err_msg").html("<font color='red'><b>Select Date..</b></font>");
    }else if(agent_id.replace(/ /gi , "") == 0)
    {
        $("#agent_id").focus();
        $("#err_msg").html("<font color='red'><b>Select Agent.</b></font>");
    }else if(complaint_no.replace(/ /gi , "") <= 0 || complaint_no.replace(/ /gi , "") == "" )
    {
        $("#complaint_no").focus();
        $("#err_msg").html("<font color='red'><b>Enter complaint Number..</b></font>");
    }else if(department.replace(/ /gi , "") == 0)
    {
        $("#department").focus();
        $("#err_msg").html("<font color='red'><b>Select Department..</b></font>");
    }else if(district.replace(/ /gi , "") == 0)
    {
        $("#district").focus();
        $("#err_msg").html("<font color='red'><b>Select District..</b></font>");
    }else if(issue.replace(/ /gi , "") == 0)
    {
        $("#issue").focus();
        $("#err_msg").html("<font color='red'><b>Select issue..</b></font>");
    }
    else
      {
        $("#au_btn").prop("disabled" , true);
        $("#err_msg").html("<font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Wait, Validating...</b></font>");
        var base_url = '<?php echo base_url(); ?>';

        let myForm = document.getElementById('report_details');
        let formData = new FormData(myForm);

        

        $.ajax({
            type: "POST",
            url: base_url+"app/reports/add_details_of_copypaste_resolutions",
            data: formData,
            dataType: "json",
            processData: false,
            contentType: false,
            cache:false,

            success:function(data){

                if(data.response == true){
                    $("#err_msg").html("<span style='color:green;font-weight:bold'>Saved Successfully</span>");
                    $("#report_details")[0].reset();
                    $("#report_details .select2").val("0").trigger("change");
                    get_all_details(true);
                }else{
                    $("#err_msg").html("<span style='color:red;font-weight:bold'>"+data.message+"</span>");
                }

                $("#au_btn").prop("disabled", false);
            },

            error:function(){
                $("#err_msg").html("<span style='color:red'>Server error</span>");
                $("#au_btn").prop("disabled", false);
            }

        });
      }
    }
    
function Update_details()
    {
    var hidden_id = $("#hidden_id").val();   
    var call_date = $("#call_date").val();
    var helpdesk_remark = $("#helpdesk_remark").val();
    var officer_mobile_no = $("#officer_mobile_no").val();
    
    if(hidden_id.replace(/ /gi , "") == 0){
        $("#up_err_msg").html("<font color='red'><b>Invalid Request..</b></font>");
    }else if(helpdesk_remark.replace(/ /gi , "") == "")
    {
        $("#helpdesk_remark").focus();
        $("#up_err_msg").html("<font color='red'><b>Enter Remark..</b></font>");
    }
    else if(call_date.replace(/ /gi , "") == "")
    {
        $("#call_date").focus();
        $("#up_err_msg").html("<font color='red'><b>Select Call Date.</b></font>");
    }else if(officer_mobile_no <= 0)
    {
        $("#officer_mobile_no").focus();
        $("#up_err_msg").html("<font color='red'><b>Enter Mobile Number..</b></font>");
    }
    else
      {
        if(helpdesk_remark == "other")
        {
            var other_remark = $("#other_remark").val();
            if(other_remark.replace(/ /gi , "") == "")
            {
                $("#other_remark").focus();
                $("#up_err_msg").html("<font color='red'><b>Enter Other Remark..</b></font>");
                return;
            }

        }
        $("#au_btn").prop("disabled" , true);
        $("#up_err_msg").html("<font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Wait, Validating...</b></font>");
        var base_url = '<?php echo base_url(); ?>';

        let myForm = document.getElementById('update_report_details');
        let formData = new FormData(myForm);
        $.ajax({
            type: "POST",
            url: base_url+"app/reports/update_details_of_copypaste_resolutions",
            data: formData,
            dataType: "json",
            processData: false,
            contentType: false,
            cache:false,

            success:function(data){

                if(data.response == true){
                    $("#up_err_msg").html("<span style='color:green;font-weight:bold'>Saved Successfully</span>");
                    $("#update_report_details")[0].reset();
                    get_all_details(true);
                }else{
                    $("#up_err_msg").html("<span style='color:red;font-weight:bold'>"+data.message+"</span>");
                }

                $("#au_btn").prop("disabled", false);
            },

            error:function(){
                $("#up_err_msg").html("<span style='color:red'>Server error</span>");
                $("#au_btn").prop("disabled", false);
            }

        });
      }
    }



    function get_all_details(preserveTableState)
    {
     var preservedTableState = preserveTableState ? captureDataTableState() : null;
     var remark = $("#remark").val();
     $("#students_list_div").html("<center><font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Wait, Loading...</b></font></center>");
     var base_url = '<?php echo base_url(); ?>';
         $.ajax({
                 type: "POST",
                 dataType: "JSON",
                 url: base_url+"app/reports/get_all_copypaste_details",
                 data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>', 'shift':activeShift ,'remark':remark },
                 cache: false,
                 success: function (data, textStatus, jqXHR) {
	                  
	                  if(data.response == true)
	                   {
                        detailRows = {};
	                    var txt = "<table class='table table-bordered table-sm' style='font-size:15px;' id='tbl_students'>";
                            txt += "<thead>";
                            txt += "<tr>";
                            txt += "<th width='1%'>Sr.No.</th>";
                            txt += "<th width='5%'>Date</th>";
                            txt += "<th width='3%'>Agent Id</th>";
                            txt += "<th width='3%'>Complaint No</th>";
                            txt += "<th width='4%'>Complaint Status</th>";
                            txt += "<th width='15%'>Department</th>";
                            txt += "<th width='5%'>District</th>";
                            
                            txt += "<th width='5%'>Issue</th>";
                           txt += "<th style='display:none;'>Helpdesk Remark</th>";
                            txt += "<th style='display:none;'>Other Remark</th>";
                            txt += "<th width='3%'>Action</th>";
                            txt += "</tr>";
	                        txt += "</thead>";
	                        txt += "<tbody>";
	                     for(var i = 0; i < data.total_record; i++)
                        {
                            var rowId = "details_row_" + i;
                            detailRows[rowId] = data.all_record[i];
                            detailRowsById[data.all_record[i].did] = data.all_record[i];

                            txt += "<tr>";

                            txt += "<td>"+parseInt(i+1)+"</td>";
                            txt += "<td>"+data.all_record[i].date+"</td>";
                            txt += "<td>"+data.all_record[i].msd_id+"</td>";
                            txt += "<td>"+data.all_record[i].complaint_no+"</td>";
                            txt += "<td>"+escapeHtml(data.all_record[i].complaint_status || "Open")+"</td>";
                            txt += "<td>"+data.all_record[i].Departname_E+"</td>";
                            txt += "<td>"+data.all_record[i].District_Name_E+"</td>";
                            
                            
                            txt += "<td>"+data.all_record[i].issue+"</td>";
                            txt += "<td style='display:none;'>" + 
                                    (data.all_record[i].helpdesk_remark != null 
                                        ? data.all_record[i].helpdesk_remark 
                                        : '') + 
                                "</td>";

                            txt += "<td style='display:none;'>" + 
                                    (data.all_record[i].remark != null 
                                        ? data.all_record[i].remark 
                                        : '') + 
                                "</td>";
                            // ACTION COLUMN
                            txt += "<td class='text-nowrap'>";
                            txt += "<div class='d-inline-flex align-items-center'>";

                            txt += "<button type='button' \
                                        class='btn btn-secondary btn-sm mr-1' \
                                        title='View History' \
                                        onclick='openHistoryModel("+data.all_record[i].did+")'>\
                                        <i class='fa fa-history'></i>\
                                    </button>";

                            if(data.all_record[i].helpdesk_remark != null || data.all_record[i].call_date != null)
                            {
                                txt += "<button type='button' \
                                            class='btn btn-info btn-sm mr-1 js-detail-toggle' \
                                            data-row-id='"+rowId+"'>\
                                            <i class='fa fa-angle-down'></i>\
                                        </button>";
                            }

                            if($.inArray(parseInt('<?php echo $_SESSION["userdata"]["role_id"]; ?>', 10), [1, 3]) !== -1)
                            {
                                txt += "<button type='button' \
                                            class='btn btn-primary btn-sm ml-1' \
                                            onclick='update_model("+data.all_record[i].did+")'>\
                                            Update\
                                        </button>";

                                if(isL1OfficerCallDone(data.all_record[i].helpdesk_remark))
                                {
                                    txt += "<button type='button' \
                                                class='btn btn-warning btn-sm ml-1' \
                                                title='Update Complaint Status' \
                                                onclick='openStatusUpdateModel("+data.all_record[i].did+")'>\
                                                <i class='fa fa-tasks'></i>\
                                            </button>";
                                }
                            }

                            txt += "</div>";
                            txt += "</td>";

                            txt += "</tr>";
                        }
	                     txt += "</tbody>";
	                    txt += "</table>";
	                    $("#students_list_div").html(txt);
	                    
	                    $("input[data-bootstrap-switch]").each(function(){
                                $(this).bootstrapSwitch();
                            });
	                   }
	                  else
	                   {
	                    $("#students_list_div").html("<center><font color='red'><b>"+data.message+"</b></font></center>");
	                   }
                       var tableMDT = makeDataTable_Basic("tbl_students");
                       restoreDataTableState(tableMDT, preservedTableState);

                       if (tableMDT) {
                           $("#tbl_students tbody").off("click", ".js-detail-toggle").on("click", ".js-detail-toggle", function() {
                               var button = $(this);
                               var tr = button.closest("tr");
                               var row = tableMDT.row(tr);
                               var rowId = button.data("row-id");

                               if (row.child.isShown()) {
                                   row.child.hide();
                                   button.find("i").removeClass("fa-angle-up").addClass("fa-angle-down");
                               } else {
                                   row.child(formatResolutionDetails(detailRows[rowId])).show();
                                   button.find("i").removeClass("fa-angle-down").addClass("fa-angle-up");
                               }
                           });
                       }
	                    
	         }
               });
    }
    function update_model(id)
    {
        var row = detailRowsById[id] || {};
        $("#hidden_id").val(id);
        $("#update_complaint_status").val(row.complaint_status || "Open").trigger("change");
        $("#update_detail_model").modal("show");
    } 

    function openStatusUpdateModel(id)
    {
        var row = detailRowsById[id] || {};
        $("#status_record_id").val(id);
        $("#popup_complaint_status").val(row.complaint_status || "Open");
        $("#status_update_msg").html("");
        $("#status_update_model").modal("show");
    }

    function updateComplaintStatus()
    {
        var recordId = $("#status_record_id").val();
        var complaintStatus = $("#popup_complaint_status").val();
        if(recordId == "" || recordId == "0")
        {
            $("#status_update_msg").html("<span style='color:red;font-weight:bold'>Invalid record.</span>");
            return;
        }
        if(complaintStatus == "0")
        {
            $("#status_update_msg").html("<span style='color:red;font-weight:bold'>Select complaint current status.</span>");
            return;
        }
        $("#status_update_btn").prop("disabled", true);
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>app/reports/update_resolution_complaint_status",
            dataType: "json",
            data: {
                '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',
                report_key: reportKey,
                record_id: recordId,
                complaint_status: complaintStatus
            },
            cache: false,
            success: function(data) {
                $("#status_update_msg").html("<span style='color:"+(data.response ? "green" : "red")+";font-weight:bold'>"+escapeHtml(data.message)+"</span>");
                if(data.response) {
                    $("#status_update_model").modal("hide");
                    get_all_details(true);
                }
                $("#status_update_btn").prop("disabled", false);
            },
            error: function() {
                $("#status_update_msg").html("<span style='color:red'>Server error</span>");
                $("#status_update_btn").prop("disabled", false);
            }
        });
    }

    function actionLabel(action)
    {
        if(action == "create") return "Record Created";
        if(action == "status_popup") return "Officer Follow-up Update";
        return "Record Updated";
    }

    function openHistoryModel(id)
    {
        $("#history_summary").html("");
        $("#history_list_div").html("<center><font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Loading history...</b></font></center>");
        $("#history_model").modal("show");
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo base_url(); ?>app/reports/get_resolution_complaint_history",
            data: {
                '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',
                report_key: reportKey,
                record_id: id
            },
            cache: false,
            success: function(data) {
                $("#history_summary").html("<b>"+escapeHtml(data.report_name || "")+"</b> | Complaint No: <b>"+escapeHtml(data.complaint_no || "")+"</b> | Current Status: <b>"+escapeHtml(data.current_status || "Open")+"</b>");
                if(!data.response) {
                    $("#history_list_div").html("<center><font color='red'><b>"+escapeHtml(data.message)+"</b></font></center>");
                    return;
                }
                var html = "<table class='table table-bordered table-sm' style='font-size:13px;'>";
                html += "<thead><tr><th>#</th><th>Action</th><th>Previous Status</th><th>New Status</th><th>Officer Worked</th><th>Updated By</th><th>Date & Time</th><th>Helpdesk Remark</th><th>Other Remark</th></tr></thead><tbody>";
                for(var i = 0; i < data.total_record; i++) {
                    var row = data.all_record[i];
                    html += "<tr>";
                    html += "<td>"+(i+1)+"</td>";
                    html += "<td>"+escapeHtml(actionLabel(row.action_type))+"</td>";
                    html += "<td>"+escapeHtml(row.previous_complaint_status || "-")+"</td>";
                    html += "<td>"+escapeHtml(row.new_complaint_status || "-")+"</td>";
                    html += "<td>"+(parseInt(row.officer_worked, 10) == 1 ? "<span class='badge badge-success'>Yes</span>" : "<span class='badge badge-secondary'>No</span>")+"</td>";
                    html += "<td>"+escapeHtml(row.updated_by_name || "")+"<br><small>"+escapeHtml(row.updated_by_msd_id || "")+"</small></td>";
                    html += "<td>"+escapeHtml(row.updated_at || "")+"</td>";
                    html += "<td>"+escapeHtml(row.helpdesk_remark || "")+"</td>";
                    html += "<td>"+escapeHtml(row.other_remark || "")+"</td>";
                    html += "</tr>";
                }
                html += "</tbody></table>";
                $("#history_list_div").html(html);
            }
        });
    }
   initializeSelect2();
   initializeShiftTabs();
   get_all_details();
   
  
  </script>
