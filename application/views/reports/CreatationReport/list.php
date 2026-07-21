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
                        <li class="breadcrumb-item active">Creation Report</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <section class="content">
      <div class="container-fluid">
        
        <div class="card card-default color-palette-box">
         <div class="card-header">

    <div class="d-flex justify-content-between align-items-center flex-wrap">

        <h5 class="m-0">
            <b>Creation Report</b>
        </h5>

        <div class="d-flex align-items-center">
            <?php if(in_array($_SESSION["userdata"]["role_id"], array(1, 2 , 3 , 4))): ?>
            <button type="button"
                class="btn btn-primary mr-2 px-4 py-2"
                style="min-width:250px;"
                data-toggle="modal"
                data-target="#show_other_rated_detail_model" onclick="get_other_related_complaints();">
            Show other Related Complaints
            </button>
            <button type="button"
                class="btn btn-success mr-2 px-4 py-2"
                style="min-width:250px;"
                data-toggle="modal"
                data-target="#add_high_rated_detail_model">
            Add other related Complaints
            </button>

            <?php endif; ?>

            <input type="date" class="form-control form-control-sm mr-2" id="from_date">

            <input type="date" class="form-control form-control-sm mr-2" id="to_date">

            <button type="button"
                    class="btn btn-sm btn-info mr-2"
                    onclick="get_all_details();">
                Filter
            </button>

            <button type="button"
                    class="btn btn-sm btn-secondary mr-2"
                    onclick="refreshCurrentTab();">
                Reset
            </button>

            <?php if(in_array($_SESSION["userdata"]["role_id"], array(1, 2 , 3 , 4))): ?>

            <button type="button"
                    class="btn btn-sm btn-primary"
                    data-toggle="modal"
                    data-target="#add_detail_model">
                Add Complaints
            </button>

            <?php endif; ?>

        </div>

    </div>

</div>
          <div class="card-body">
            
            
            
            <div class="row">
    <div class="col-md-12">

        <ul class="nav nav-tabs" id="reportTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active"
                   id="complaints-tab"
                   data-toggle="tab"
                   href="#complaints_div"
                   role="tab">
                    Complaints
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link"
                   id="demand-tab"
                   data-toggle="tab"
                   href="#demand_div"
                   role="tab">
                    Demand / Suggestion
                </a>
            </li>
        </ul>

        <div class="tab-content pt-3">

            <!-- Complaints Tab -->
            <div class="tab-pane fade show active"
                 id="complaints_div"
                 role="tabpanel">

                <div id="students_list_div"></div>

            </div>

            <!-- Demand/Suggestion Tab -->
            <div class="tab-pane fade"
                 id="demand_div"
                 role="tabpanel">

                <div id="demand_suggestion_div"></div>

            </div>

        </div>

    </div>
</div>
            
          </div>
        </div>
        
      </div><!--/. container-fluid -->
    </section>
</div>

<div class="modal fade" id="add_detail_model">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Add Complaint</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">
            <form id="report_details" method="post" enctype= "multipart/form-data" >
                <div class="form-group">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="comp_type_complaint" name="comp_type"
                            class="custom-control-input" value="1" checked>
                        <label class="custom-control-label" for="comp_type_complaint">
                            Complaint
                        </label>
                    </div>

                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="comp_type_demand" name="comp_type"
                            class="custom-control-input" value="2">
                        <label class="custom-control-label" for="comp_type_demand">
                            Demand
                        </label>
                    </div>

                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="comp_type_suggestion" name="comp_type"
                            class="custom-control-input" value="3">
                        <label class="custom-control-label" for="comp_type_suggestion">
                            Suggestion
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label>Complaint No</label>
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
                    <select class="form-control select2" id="department" name="department" >
                    <option value="0">-- Select --</option>
                    <?php
                    if($department_list != FALSE)
                        {
                        foreach($department_list as $departmentList)
                        {
                        echo "<option value='".$departmentList->Departid."'>".$departmentList->Departname_E."</option>";
                        }
                        }
                    ?>
                    </select>
                </div>
                <!-- <div class="form-group">
                    <label>Attribute</label>

                    <select class="form-control select2" id="attribute" name="attribute">
                        <option value="0">-- Select --</option>

                        <?php
                        if(count($attribute_list) != 0)
                        {
                            foreach($attribute_list as $attributelist)
                            {
                                echo "<option value='".$attributelist->attribID."'>
                                        ".$attributelist->attribname_E."
                                    </option>";
                            }
                        }
                        ?>
                    </select>
                </div> -->
                <div class="form-group">
                    <label>Attribute</label>

                    <select class="form-control select2" id="attribute" name="attribute">
                        <option value="0">-- Select --</option>
                    </select>
                </div>
              <div class="form-group" id="err_msg"></div>
            </form>
              
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" id="au_btn" onclick="add_details();">Submit</button>
            </div>
          </div>
          
        </div>
      
  </div>

  <div class="modal fade" id="add_high_rated_detail_model">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Add other Rated Complaint</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">
            <form id="high_report_details" method="post" enctype= "multipart/form-data" >
                <div class="form-group">
                    <label>Complaint No</label>
                    <!-- <input type="number" name="other_complaint_no" id="other_complaint_no"
                            class="form-control"
                            required> -->
                            <input type="text"
                                name="other_complaint_no"
                                id="other_complaint_no"
                                class="form-control"
                                maxlength="20"
                                oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                                required>
                </div>
                <div class="form-group">
                    <select class="form-control select2"
                            id="type"
                            name="type" required
                        >
                        <option value="0">-- Select --</option>
                        <option value="1">Pending</option>
                        <option value="2">Move to next level</option>
                        <option value="3">Close</option>
                        <option value="4">High rated Tag</option>
                    </select>
                </div>
              <div class="form-group" id="high_err_msg"></div>
            </form>
              
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" id="au_btn" onclick="add_high_rated_details();">Submit</button>
            </div>
          </div>
          
        </div>
      
  </div>

  <div class="modal fade" id="show_other_rated_detail_model">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Other Rated Complaint</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">
                    <div id="div_other_related_complaints"></div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            
            </div>
          </div>
          
        </div>
      
  </div>
 


   
  <script>
    var detailRows = {};


    $('#department').change(function () {

    var department_id = $(this).val();

    get_attribute(department_id);

});
    function get_attribute(department_id)
{
    var base_url = '<?php echo base_url(); ?>';

    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: base_url + "app/reports/get_attribute",
        data: {
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>',
            'department' : department_id
        },
        cache: false,
        success: function (data)
        {
            $('#attribute').html('<option value="0">-- Select --</option>');

            if(data.response == true)
            {
                $.each(data.all_record, function(index, value){

                    $('#attribute').append(
                        '<option value="'+value.attribID+'">'+
                            value.attribname_E+
                        '</option>'
                    );

                });
            }

            $('#attribute').trigger('change');
        }
    });
}


    function escapeHtml(value)
    {
        return $("<div>").text(value == null ? "" : value).html();
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
                        "<div class='col-md-12 mt-3'>" +
                            "<label><b>Helpdesk Remark</b></label>" +
                            "<div style='background:#fff;padding:10px;border-radius:5px;border:1px solid #ddd;'>" +
                                escapeHtml(record.helpdesk_remark) +
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
    }

    function add_details()
    {
    var comp_type = $("input[name='comp_type']:checked").val();
    var complaint_no = $("#complaint_no").val();
    var department = $("#department").val();
    var attribute = $("#attribute").val();
    if(complaint_no.replace(/ /gi , "") <= 0 || complaint_no.replace(/ /gi , "") == "" )
    {
        $("#complaint_no").focus();
        $("#err_msg").html("<font color='red'><b>Enter complaint Number..</b></font>");
    }else if(department.replace(/ /gi , "") == 0)
    {
        $("#department").focus();
        $("#err_msg").html("<font color='red'><b>Select Department..</b></font>");
    }else if(attribute.replace(/ /gi , "") == 0)
    {
        $("#attribute").focus();
        $("#err_msg").html("<font color='red'><b>Select Attribute..</b></font>");
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
            url: base_url+"app/reports/add_complaint_details",
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
                    get_all_details();
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

    function add_high_rated_details()
    {
    
    
    var complaint_no = $("#other_complaint_no").val();
    var type = $("#type").val();
    
    if(complaint_no.replace(/ /gi , "") <= 0 || complaint_no.replace(/ /gi , "") == "" )
    {
        $("#complaint_no").focus();
        $("#high_err_msg").html("<font color='red'><b>Enter complaint Number..</b></font>");
    }else if(type == 0){
        $("#type == 0").focus();
        $("#high_err_msg").html("<font color='red'><b>Select Type..</b></font>");
    }
    else
      {
        $("#au_btn").prop("disabled" , true);
        $("#high_err_msg").html("<font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Wait, Validating...</b></font>");
        var base_url = '<?php echo base_url(); ?>';

        let myForm = document.getElementById('high_report_details');
        let formData = new FormData(myForm);

        

        $.ajax({
            type: "POST",
            url: base_url+"app/reports/add_high_complaint_details",
            data: formData,
            dataType: "json",
            processData: false,
            contentType: false,
            cache:false,

            success:function(data){

                if(data.response == true){
                    $("#high_err_msg").html("<span style='color:green;font-weight:bold'>"+data.message+"</span>");
                    $("#high_report_details")[0].reset();
                    $("#high_report_details .select2").val("0").trigger("change");
                    get_all_details();
                }else{
                    $("#high_err_msg").html("<span style='color:red;font-weight:bold'>"+data.message+"</span>");
                }

                $("#au_btn").prop("disabled", false);
            },

            error:function(){
                $("#high_err_msg").html("<span style='color:red'>Server error</span>");
                $("#au_btn").prop("disabled", false);
            }

        });
      }
    }
    


    function get_all_details()
    {
     $("#students_list_div").html("<center><font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Wait, Loading...</b></font></center>");
     var base_url = '<?php echo base_url(); ?>';
         $.ajax({
                 type: "POST",
                 dataType: "JSON",
                 url: base_url+"app/reports/get_all_complaints",
                 data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',  'from_date': $("#from_date").val(), 'to_date': $("#to_date").val() },
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
                            
                            txt += "<th width='3%'>Complaint No</th>";
                            txt += "<th width='15%'>Department</th>";
                            txt += "<th width='15%'>Attribute</th>";
                            
                            txt += "</tr>";
	                        txt += "</thead>";
	                        txt += "<tbody>";
	                     for(var i = 0; i < data.total_record; i++)
                        {
                            var rowId = "details_row_" + i;
                            detailRows[rowId] = data.all_record[i];

                            txt += "<tr>";

                            txt += "<td>"+parseInt(i+1)+"</td>";
                            txt += "<td>"+data.all_record[i].added_at+"</td>";
                            txt += "<td>"+data.all_record[i].comp_id+"</td>";
                            txt += "<td>"+data.all_record[i].department_name+"</td>";
                            txt += "<td>"+data.all_record[i].attribute_name+"</td>";
                            

                            

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

    function get_demand_suggestion_details()
    {
        $("#demand_suggestion_div").html(
            "<center><i class='fa fa-spinner fa-spin'></i> Loading...</center>"
        );
        var base_url = '<?php echo base_url(); ?>';
         $.ajax({
                 type: "POST",
                 dataType: "JSON",
                 url: base_url+"app/reports/get_demand_suggestion_details",
                 data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',  'from_date': $("#from_date").val(), 'to_date': $("#to_date").val() },
                 cache: false,
                 success: function (data, textStatus, jqXHR) {
	                  
	                  if(data.response == true)
	                   {
                        detailRows = {};
	                    var txt = "<table class='table table-bordered table-sm' style='font-size:15px;' id='tbl_students1'>";
                            txt += "<thead>";
                            txt += "<tr>";
                            txt += "<th width='1%'>Sr.No.</th>";
                            txt += "<th width='5%'>Date</th>";
                            
                            txt += "<th width='3%'>Complaint No</th>";
                            txt += "<th width='15%'>Department</th>";
                            txt += "<th width='15%'>Attribute</th>";
                            txt += "<th width='5%'>Complaint Type</th>";
                            txt += "</tr>";
	                        txt += "</thead>";
	                        txt += "<tbody>";
	                     for(var i = 0; i < data.total_record; i++)
                        {
                            var rowId = "details_row_" + i;
                            detailRows[rowId] = data.all_record[i];

                            txt += "<tr>";

                            txt += "<td>"+parseInt(i+1)+"</td>";
                            txt += "<td>"+data.all_record[i].added_at+"</td>";
                            txt += "<td>"+data.all_record[i].comp_id+"</td>";
                            txt += "<td>"+data.all_record[i].department_name+"</td>";
                            txt += "<td>"+data.all_record[i].attribute_name+"</td>";
                            if(data.all_record[i].comp_type == 2)
                            {
                                txt += "<td>Demand</td>";
                            }
                            else if(data.all_record[i].comp_type == 3)
                            {
                                txt += "<td>Suggestion</td>";
                            }

                            txt += "</tr>";
                        }
	                     txt += "</tbody>";
	                    txt += "</table>";
	                    $("#demand_suggestion_div").html(txt);
	                    
	                    $("input[data-bootstrap-switch]").each(function(){
                                $(this).bootstrapSwitch();
                            });
	                   }
	                  else
	                   {
	                    $("#demand_suggestion_div").html("<center><font color='red'><b>"+data.message+"</b></font></center>");
	                   }
                       var tableMDT = makeDataTable_Basic("tbl_students1");

                       if (tableMDT) {
                           $("#tbl_students1 tbody").off("click", ".js-detail-toggle").on("click", ".js-detail-toggle", function() {
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

        // AJAX call yahan karna
    }


    function get_other_related_complaints()
{
    $("#div_other_related_complaints").html(
        "<center><font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Wait, Loading...</b></font></center>"
    );

    var base_url = '<?php echo base_url(); ?>';

    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: base_url + "app/reports/get_other_related_complaints",
        data: {
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>',
            'from_date' : $("#from_date").val(),
            'to_date' : $("#to_date").val()
        },
        cache: false,

        success: function (data)
        {
            if(data.response == true)
            {
                var txt = "";

                txt += "<table class='table table-bordered table-sm' style='font-size:14px;' id='tbl_other_related_complaints'>";
                txt += "<thead>";
                txt += "<tr>";
                txt += "<th width='5%'>S.No.</th>";
                txt += "<th width='25%'>Complaint No</th>";
                txt += "<th width='30%'>Type</th>";
                txt += "<th width='40%'>Date</th>";
                txt += "</tr>";
                txt += "</thead>";
                txt += "<tbody>";

                for(var i = 0; i < data.total_record; i++)
                {
                    var type_text = "";

                    if(data.all_record[i].type == 1)
                    {
                        type_text = "Pending";
                    }
                    else if(data.all_record[i].type == 2)
                    {
                        type_text = "Move to next level";
                    }
                    else if(data.all_record[i].type == 3)
                    {
                        type_text = "Close";
                    }
                    else if(data.all_record[i].type == 4)
                    {
                        type_text = "High rated Tag";
                    }

                    txt += "<tr>";

                    txt += "<td>"+parseInt(i + 1)+"</td>";
                    txt += "<td>"+data.all_record[i].comp_id+"</td>";
                    txt += "<td>"+type_text+"</td>";
                    txt += "<td>"+data.all_record[i].added_at+"</td>";

                    txt += "</tr>";
                }

                txt += "</tbody>";
                txt += "</table>";

                $("#div_other_related_complaints").html(txt);

                makeDataTable_Basic("tbl_other_related_complaints");

                $("#show_other_rated_detail_model").modal("show");
            }
            else
            {
                $("#div_other_related_complaints").html(
                    "<center><font color='red'><b>"+data.message+"</b></font></center>"
                );
            }
        }
    });
}

function refreshCurrentTab()
{
    $("#from_date").val("");
        $("#to_date").val("");
    if($("#complaints-tab").hasClass("active"))
    {
        get_all_details();
    }
    else
    {
        get_demand_suggestion_details();
    }
}
    
 
   initializeSelect2();
  
   get_all_details();
   
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {

        var target = $(e.target).attr("href");

        if(target == "#demand_div")
        {
            get_demand_suggestion_details();
        }
    });

    


  </script>
