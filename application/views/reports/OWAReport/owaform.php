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
                        <li class="breadcrumb-item active">Direction Report</li>
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
                <h5 class="m-0">
                    <b>OWA FORM</b>
                </h5>
            </div>

            <div class="card-body">

                <form id="report_details" method="post" enctype="multipart/form-data">

                    <div class="row">

                        <!-- LEFT SIDE -->
                        <div class="col-md-6">

                            <div class="form-group">
                                <label>Date</label>
                                <input type="date" name="date" id="date"
                                    class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Agent ID</label>
                                <select class="form-control select2" id="agent_id" name="agent_id">
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
                            <div class="form-group" >
                                <label>Other Agent</label>
                                <input type="text" name="other_agent" id="other_agent"
                                    class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Complaint No</label>
                                <!-- <input type="number" name="complaint_no" id="complaint_no"
                                    class="form-control" required> -->
                                    <input type="text"
       name="complaint_no"
       id="complaint_no"
       class="form-control"
       maxlength="20"
       oninput="this.value=this.value.replace(/[^0-9]/g,'')"
       required>
                            </div>

                            <div class="form-group">
                                <label>Old Department</label>
                                <select class="form-control select2" id="old_department" name="old_department">
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
                                <label>New Department</label>
                                <select class="form-control select2" id="new_department" name="new_department">
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

                            

                            

                        </div>

                        <!-- RIGHT SIDE -->
                        <div class="col-md-6">

                            
                            <div class="form-group">
                                <label>OWA Reason</label>
                                <select class="form-control select2" id="owa_reason" name="owa_reason">
                                    <option value="0">-- Select --</option>
                                    <option value="Agent's Error">Agent's Error</option>
                                    <option value="Officer Error as complaint Pertains to same Department / Officer">Officer Error as complaint Pertains to same Department / Officer</option>
                                    <option value="Online Registered">Online Registered</option>
                                    <option value="Wrong Department">Wrong Department</option>
                                    <option value="Wrong Sub Division/Section/Thana/Range/Tehsil etc.">Wrong Sub Division/Section/Thana/Range/Tehsil etc.</option>
                                    <option value="Suggested by Officer/Where complaint mapped as per officer's suggestion">Suggested by Officer/Where complaint mapped as per officer's suggestion</option>
                                    <option value="Other Issue">Other Issue</option>
                                    <option value="Send to Lower level officer by (L4/L3/L2)">Send to Lower level officer by (L4/L3/L2)</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Other OWA issue</label>
                                <input type="text" name="other_owa_reason" id="other_owa_reason"
                                    class="form-control" required>
                            </div>


                            <div class="form-group">
                                <label>Old Attribute</label>
                                <select class="form-control select2" id="old_attribute" name="old_attribute">
                                    <option value="0">-- Select --</option>

                                    <?php
                                    if($attribute_list != FALSE)
                                    {
                                        foreach($attribute_list as $attributelist)
                                        {
                                            echo "<option value='".$attributelist->attribID."'>
                                            ".$attributelist->attribname."
                                            </option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>New Attribute</label>
                                <select class="form-control select2" id="new_attribute" name="new_attribute">
                                    <option value="0">-- Select --</option>

                                    <?php
                                    if($attribute_list != FALSE)
                                    {
                                        foreach($attribute_list as $attributelist)
                                        {
                                           echo "<option value='".$attributelist->attribID."'>
                                            ".$attributelist->attribname."
                                            </option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>SME's Remark</label>
                                <textarea name="sme_remark" id="sme_remark"
                                    class="form-control" rows="3"></textarea>
                            </div>
                            <div class="form-group" id="err_msg">
                                
                            </div>

                        </div>

                    </div>

                    <!-- BUTTON -->
                    <div class="row">
                        <div class="col-md-12 text-center mt-3">
                            <button type="button" id="au_btn" onclick="add_details()" class="btn btn-primary">
                                Submit
                            </button>
                        </div>
                    </div>

                </form>

            </div>
        </div>

    </div>
</section>
</div>
   
  <script>
   
    function initializeSelect2()
    {
        if (!$.fn.select2) {
            return;
        }

        $("#report_details .select2").select2({
            theme: "bootstrap4",
            width: "100%",
            dropdownParent: $("#report_details"),
            placeholder: "-- Select --"
        });
    }

    function add_details()
    {
    var date = $("#date").val();
    var agent_id = $("#agent_id").val();
    var complaint_no = $("#complaint_no").val();
    var new_department = $("#new_department").val();
    var old_department = $("#old_department").val();
    var new_attribute = $("#new_attribute").val();
    var old_attribute = $("#old_attribute").val();
    var owa_reason = $("#owa_reason").val();
    var sme_remark = $("#sme_remark").val();
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
    }else if(old_department.replace(/ /gi , "") == 0)
    {
        $("#old_department").focus();
        $("#err_msg").html("<font color='red'><b>Select Old Department...</b></font>");
    }else if(new_department.replace(/ /gi , "") == 0)
    {
        $("#new_department").focus();
        $("#err_msg").html("<font color='red'><b>Select New Department..</b></font>");
    }else if(owa_reason.replace(/ /gi , "") == 0)
    {
        $("#owa_reason").focus();
        $("#err_msg").html("<font color='red'><b>Select OWA Reason..</b></font>");
    }else if(old_attribute.replace(/ /gi , "") == 0)
    {
        $("#old_attribute").focus();
        $("#err_msg").html("<font color='red'><b>Select Old Attribute...</b></font>");
    }else if(new_attribute.replace(/ /gi , "") == 0)
    {
        $("#new_attribute").focus();
        $("#err_msg").html("<font color='red'><b>Select New Attribute..</b></font>");
    }else if(sme_remark.replace(/ /gi , "") == 0)
    {
        $("#sme_remark").focus();
        $("#err_msg").html("<font color='red'><b>Enter SME remark.</b></font>");
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
            url: base_url+"app/reports/add_owa_details",
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
    

   initializeSelect2();
  
  </script>
