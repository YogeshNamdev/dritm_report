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
                        <li class="breadcrumb-item active">OLD OWA Report</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <section class="content">
      <div class="container-fluid">
        
        <div class="card card-default color-palette-box">
          <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
    
                <!-- Left Side Heading -->
                <h5 class="m-0">
                    <b>Old OWA REPORT</b>
                </h5>

                <!-- Right Side Filters -->
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <div class="form-group mb-0">
                        <select class="form-control select2" id="agent_id" name="agent_id" onchange="get_all_details();">
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
                    <div class="form-group mb-0">
                        <input type="date" name="start_date" id="start_date"
                            class="form-control" title="Start Date" required>
                    </div>

                    <div class="form-group mb-0">
                        <input type="date" name="end_date" id="end_date"
                            class="form-control" title="End Date" onchange="get_all_details();" required>
                    </div>

                    

                </div>

            </div>
          <div class="card-body">
            
            <div class="row">
             <div class="col-md-12" id="students_list_div">

            </div>
            </div>
            
          </div>
        </div>
        
      </div><!--/. container-fluid -->
    </section>
</div>
<div class="modal fade" id="edit_sme_remark_modal" tabindex="-1" role="dialog" aria-labelledby="edit_sme_remark_modal_label" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Edit SME Remark</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              
              <input type="hidden" id="edit_owa_id" />
                <div class="form-group">
                    <label>SME's Remark</label>
                    <textarea name="sme_remark" id="sme_remark"
                        class="form-control" rows="3"></textarea>
                </div>
              
              <div class="form-group" id="edit_err_msg"></div>
              
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" id="eau_btn" onclick="update_sme_remark();">Update</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
  </div>
      <!-- /.modal -->
   
  <script>
    var detailRows = {};

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

    

    function get_all_details()
    {
     var start_date = $("#start_date").val();
     var end_date = $("#end_date").val();
     var agent_id = $("#agent_id").val();
     if(end_date != ""){
        if(start_date == ""){
            alert("Select Start Date");
            $("#end_date").val("");
            return;
        }
     }
     $("#students_list_div").html("<center><font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Wait, Loading...</b></font></center>");
     var base_url = '<?php echo base_url(); ?>';
         $.ajax({
                 type: "POST",
                 dataType: "JSON",
                 url: base_url+"app/reports/get_all_owa_details_old",
                 data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>', 'start_date': start_date, 'end_date':end_date , 'agent_id':agent_id },
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
                            txt += "<th width='5%'>Complaint No</th>";
                            txt += "<th width='15%'>Old Department</th>";
                            txt += "<th width='15%'>New Department</th>";
                            txt += "<th width='15%'>OWA Reason</th>";
                            txt += "<th width='15%'>Old Attribute</th>";
                            txt += "<th width='15%'>New Attribute</th>";
                            txt += "<th width='15%'>SME Remark</th>";
                           // txt += "<th width='15%'>Action</th>";
                            txt += "</tr>";
	                        txt += "</thead>";
	                        txt += "<tbody>";
	                     for(var i = 0; i < data.total_record; i++)
                        {
                            var rowId = "details_row_" + i;
                            detailRows[rowId] = data.all_record[i];

                            txt += "<tr>";

                            txt += "<td>"+parseInt(i+1)+"</td>";
                            txt += "<td>"+data.all_record[i].date+"</td>";
                            txt += "<td>"+data.all_record[i].msd_id+"</td>";
                            txt += "<td>"+data.all_record[i].complaint_no+"</td>";
                            txt += "<td>"+data.all_record[i].old_department_name+"</td>";
                            txt += "<td>"+data.all_record[i].new_department_name+"</td>";
                            txt += "<td>"+data.all_record[i].owa_reason+"</td>";
                            
                           
                            txt += "<td>"+data.all_record[i].old_attribute_name+"</td>";
                             txt += "<td>"+data.all_record[i].new_attribute_name+"</td>";
                            txt += "<td>"+data.all_record[i].sme_remark+"</td>";
                            //txt += "<td><i class='fa fa-edit' title='Edit SME Remark' style='color:blue;cursor:pointer;cursor:hand;' data-toggle='modal' data-target='#edit_sme_remark_modal' onclick='get_owa_details_by_id("+data.all_record[i].id+");'></i></td>";
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
   
   function get_owa_details_by_id(owa_id)
   {
     var base_url = '<?php echo base_url(); ?>';
         $.ajax({
                 type: "POST",
                 dataType: "JSON",
                 url: base_url+"app/reports/get_owa_details_by_id",
                 data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>', 'owa_id': owa_id },
                 cache: false,
                 success: function (data, textStatus, jqXHR) {
                      
                      if(data.response == true)
                       {
                        $("#edit_owa_id").val(owa_id);
                        $("#sme_remark").val(data.all_record[0].sme_remark);
                        
                       }
                      else
                       {
                        alert(data.message);
                       }
             }
               });
   }

   function update_sme_remark()
   {
     var owa_id = $("#edit_owa_id").val();
     var sme_remark = $("#sme_remark").val();
     if(sme_remark == "")
     {
        alert("Enter SME Remark");
        return;
     }
     var base_url = '<?php echo base_url(); ?>';
         $.ajax({
                 type: "POST",
                 dataType: "JSON",
                 url: base_url+"app/reports/update_sme_remark",
                 data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>', 'id': owa_id, 'sme_remark':sme_remark },
                 cache: false,
                 success: function (data, textStatus, jqXHR) {
                      
                      if(data.response == true)
                       {
                        alert(data.message);
                        $("#edit_sme_remark_modal").modal("hide");
                        get_all_details();
                       }
                      else
                       {
                        alert(data.message);
                       }
             }
               });
   }

   get_all_details();
   
  
  </script>
