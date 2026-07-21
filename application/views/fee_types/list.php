
  <!-- Bootstrap Switch -->
<script src="<?= base_url(); ?>includes/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>

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
                        <li class="breadcrumb-item active">Fee Type Master</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <section class="content">
        <div class="container-fluid">

            <div class="card card-default">

                <div class="card-header">
                    <h3 class="card-title m-0">Fee Type Master</h3>
                </div>

                <div class="card-body">
                        <div class="row">
                            <!-- LEFT FORM -->
                            <div class="col-sm-3">
                                
                                
                                <div class="form-group">
                                    <label>Fee Type Name</label>
                                    <input type="text" name="fee_name" id="fee_name"
                                           class="form-control"
                                           placeholder="Example: Admission Fee "
                                           required>
                                </div>
                                <div class="form-group">
                                    <label>Fee Category</label>
                                    <select class="form-control" id="fee_category">
                                        <option value="ADMISSION">Admission</option>
                                        <option value="TUITION">Tuition</option>
                                        <option value="MISC">Miscellaneous</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Charge Mode</label>
                                    <select class="form-control" id="charge_mode">
                                        <option value="ONE_TIME">One Time</option>
                                        <option value="YEARLY">Yearly</option>
                                        <option value="SEMESTER">Semester Wise</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label><input type="checkbox" id="discount_allowed"> Discount Allowed</label>
                                </div>
                                <div class="form-group">
                                     <button type="button" class="btn btn-primary" id="au_btn" onclick="add_fee_types();">Submit</button>
                                    
                                </div>

                                <div id="err_msg"></div>

                            </div>


                            <!-- RIGHT TABLE -->
                            <div class="col-sm-9" id="fee_types_list_div">

                            </div>

                        </div>

                 

                </div>
            </div>

        </div>
    </section>

</div>

<div class="modal fade" id="edit_fee_types_modal">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Edit fee_types</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              
              <input type="hidden" id="edit_fee_types_id" />
                <div class="form-group">
                    <label>Fee types Name</label>
                    <input type="text" name="edit_fee_name" id="edit_fee_name"
                            class="form-control"
                            placeholder="Example: Admission Fee "
                            required>
                </div>
                <div class="form-group">
                    <label>Fee Category</label>
                    <select class="form-control" id="edit_fee_category">
                        <option value="ADMISSION">Admission</option>
                        <option value="TUITION">Tuition</option>
                        <option value="MISC">Miscellaneous</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Charge Mode</label>
                    <select class="form-control" id="edit_charge_mode">
                        <option value="ONE_TIME">One Time</option>
                        <option value="YEARLY">Yearly</option>
                        <option value="SEMESTER">Semester Wise</option>
                    </select>
                </div>
                <div class="form-group">
                    <label><input type="checkbox" id="edit_discount_allowed"> Discount Allowed</label>
                </div>
               
              <div class="form-group" id="edit_err_msg"></div>
              
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" id="eau_btn" onclick="edit_fee_types_details();">Submit</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
  </div>
  <script>
    function add_fee_types()
    {
     var fee_name = $("#fee_name").val();
     var fee_category = $("#fee_category").val();
     var charge_mode = $("#charge_mode").val();
     var discount_allowed = $("#discount_allowed").is(":checked") ? 1 : 0;
     
      if(fee_name.replace(/ /gi , "") == "")
      {
          $("#fee_name").focus();
          $("#err_msg").html("<font color='red'><b>Enter Fee name.</b></font>");
      }
     else
      {
          $("#au_btn").prop("disabled" , true);
          $("#err_msg").html("<font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Wait, Validating...</b></font>");
       var base_url = '<?php echo base_url(); ?>';
           $.ajax({
                   type: "POST",
                   dataType: "JSON",
                   url: base_url+"app/fee_types/save",
                   data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' ,
                    'fee_name':fee_name, 'fee_category':fee_category, 'charge_mode':charge_mode, 'discount_allowed':discount_allowed },
                   cache: false,
                   success: function (data, textStatus, jqXHR) {
	                    
	                    if(data.response == true)
	                     {
	                      $("#err_msg").html("<font color='green'><b>"+data.message+"</b></font>");
	                        get_all_fee_types();
                            $("#fee_name").val("");
	                     }
	                    else
	                     {
	                      $("#err_msg").html("<font color='red'><b>"+data.message+"</b></font>");
	                     }
	                    $("#au_btn").prop("disabled" , false);
	                    
	           }
                 });
      }
    }

    function get_all_fee_types()
    {
     $("#fee_types_list_div").html("<center><font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Wait, Loading...</b></font></center>");
     var base_url = '<?php echo base_url(); ?>';
         $.ajax({
                 type: "POST",
                 dataType: "JSON",
                 url: base_url+"app/fee_types/get_all_fee_types",
                 data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' },
                 cache: false,
                 success: function (data, textStatus, jqXHR) {
	                  
	                  if(data.response == true)
	                   {
	                    var txt = "<table class='table table-bordered table-sm' style='font-size:15px;'>";
	                     txt += "<thead>";
	                      txt += "<tr>";
	                       txt += "<th width='5%'>Sr.No.</th>";
	                       txt += "<th width='10%'>Fee Name</th>";
                           txt += "<th width='10%'>Category</th>";
                           txt += "<th width='10%'>Charge Mode</th>";
                           txt += "<th width='10%'>Discount</th>";
                           txt += "<th width='5%'>Action</th>";
	                      txt += "</tr>";
	                     txt += "</thead>";
	                     txt += "<tbody>";
	                      for(var i = 0; i < data.total_record; i++)
	                       {
                            txt += "<tr>";
                            txt += "<td>"+parseInt(i+1)+"</td>";
                            txt += "<td>"+data.all_record[i].fee_name+"</td>";
                            txt += "<td>"+data.all_record[i].fee_category+"</td>";
                            txt += "<td>"+data.all_record[i].charge_mode+"</td>";
                            txt += "<td>"+(parseInt(data.all_record[i].discount_allowed) === 1 ? "Yes" : "No")+"</td>";
	                         txt += "<td align='center'>";
	                          txt += "<i class='fa fa-edit' title='Edit fee_types' style='color:blue;cursor:pointer;cursor:hand;' data-toggle='modal' data-target='#edit_fee_types_modal' onclick='get_fee_types_details_by_id("+data.all_record[i].id+");'></i>";
	                          txt += "&nbsp;";
	                          txt += "<i class='fa fa-times' title='Delete fee_types' style='color:red;cursor:pointer;cursor:hand;' onclick='delete_fee_types("+data.all_record[i].id+");'></i>";
	                         txt += "</td>";
	                        txt += "</tr>";
	                       }
	                     txt += "</tbody>";
	                    txt += "</table>";
	                    $("#fee_types_list_div").html(txt);
	                    
	                    $("input[data-bootstrap-switch]").each(function(){
                                $(this).bootstrapSwitch();
                            });
	                   }
	                  else
	                   {
	                    $("#fee_types_list_div").html("<center><font color='red'><b>"+data.message+"</b></font></center>");
	                   }
	                    
	         }
               });
    }
   
   function get_fee_types_details_by_id(fee_types_id)
    {
     $("#edit_fee_types_id").val(fee_types_id);
     $("#edit_fee_types_name").val("");
     var base_url = '<?php echo base_url(); ?>';
         $.ajax({
                 type: "POST",
                 dataType: "JSON",
                 url: base_url+"app/fee_types/get_fee_types_details_by_id",
                 data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' , 'fee_types_id':fee_types_id },
                 cache: false,
                 success: function (data, textStatus, jqXHR) {
                          
                          if(data.response == true)
                           {
                            $("#edit_fee_name").val(data.all_record[0].fee_name);
                            $("#edit_fee_category").val(data.all_record[0].fee_category);
                            $("#edit_charge_mode").val(data.all_record[0].charge_mode);
                            $("#edit_discount_allowed").prop("checked", parseInt(data.all_record[0].discount_allowed) === 1);
                           
                           }
                          
                 }
               });
    }
   
    function edit_fee_types_details()
    {
     var fee_name = $("#edit_fee_name").val();
     var fee_types_id = $("#edit_fee_types_id").val();
     var fee_category = $("#edit_fee_category").val();
     var charge_mode = $("#edit_charge_mode").val();
     var discount_allowed = $("#edit_discount_allowed").is(":checked") ? 1 : 0;
     if(fee_name.replace(/ /gi , "") == "")
      {
          $("#edit_fee_name").focus();
          $("#edit_err_msg").html("<font color='red'><b>Enter Fee name.</b></font>");
      }
     else
      {
       $("#eau_btn").prop("disabled" , true);
       $("#edit_err_msg").html("<font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Wait, Validating...</b></font>");
       var base_url = '<?php echo base_url(); ?>';
           $.ajax({
                   type: "POST",
                   dataType: "JSON",
                   url: base_url+"app/fee_types/edit_fee_types_details",
                   data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' ,'fee_types_id':fee_types_id ,
                    'fee_name':fee_name, 'fee_category':fee_category, 'charge_mode':charge_mode, 'discount_allowed':discount_allowed },
                   cache: false,
                   success: function (data, textStatus, jqXHR) {
	                    
	                    if(data.response == true)
	                     {
	                      $("#edit_err_msg").html("<font color='green'><b>"+data.message+"</b></font>");
	                      
	                      get_all_fee_types();
	                     }
	                    else
	                     {
	                      $("#edit_err_msg").html("<font color='red'><b>"+data.message+"</b></font>");
	                     }
	                    $("#eau_btn").prop("disabled" , false);
	                    
	           }
                 });
      }
    }
   
   function delete_fee_types(fee_types_id)
    {
     var conf = confirm("Are you sure to delete this user?");
     if(conf == true)
      {
       var base_url = '<?php echo base_url(); ?>';
           $.ajax({
                   type: "POST",
                   dataType: "JSON",
                   url: base_url+"app/fee_types/delete_fee_types",
                   data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' , 'fee_types_id':fee_types_id },
                   cache: false,
                   success: function (data, textStatus, jqXHR) {
                            
                            if(data.response == true)
                             {
                             get_all_fee_types();
                             }
                            else
                             {
                              alert(data.message);
                             }
                            
                   }
                 });
      }
    }
   
   

   
   
   get_all_fee_types();
   
  
  </script>
