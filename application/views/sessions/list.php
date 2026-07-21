
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
                        <li class="breadcrumb-item active">Session Master</li>
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
                    <h3 class="card-title m-0">Session Master</h3>
                </div>

                <div class="card-body">
                        <div class="row">
                            <!-- LEFT FORM -->
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Session Name</label>
                                    <input type="text" name="session_name" id="session_name"
                                           class="form-control"
                                           placeholder="Example: 2025-26"
                                           required>
                                </div>
                                <div class="form-group">
                                     <button type="button" class="btn btn-primary" id="au_btn" onclick="add_session();">Submit</button>
                                    
                                </div>

                                <div id="err_msg"></div>

                            </div>


                            <!-- RIGHT TABLE -->
                            <div class="col-sm-9" id="session_list_div">

                            </div>

                        </div>

                 

                </div>
            </div>

        </div>
    </section>

</div>

<div class="modal fade" id="edit_session_modal">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Edit Session</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              
              <input type="hidden" id="edit_session_id" />
              
              <div class="form-group">
                <label>Course Name</label>
                <input type="text" name="edit_session_name" id="edit_session_name"
                        class="form-control"
                        placeholder="Example: 2025-26"
                        required>
            </div>
              <div class="form-group" id="edit_err_msg"></div>
              
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" id="eau_btn" onclick="edit_sessions_details();">Submit</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
  </div>
  <script>
    function add_session()
    {
     var session_name = $("#session_name").val();
     
     if(session_name.replace(/ /gi , "") == "")
      {
          $("#session_name").focus();
          $("#err_msg").html("<font color='red'><b>Enter session name.</b></font>");
      }
     
     else
      {
          $("#au_btn").prop("disabled" , true);
          $("#err_msg").html("<font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Wait, Validating...</b></font>");
       var base_url = '<?php echo base_url(); ?>';
           $.ajax({
                   type: "POST",
                   dataType: "JSON",
                   url: base_url+"app/sessions/save",
                   data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' , 'session_name':session_name },
                   cache: false,
                   success: function (data, textStatus, jqXHR) {
	                    
	                    if(data.response == true)
	                     {
	                      $("#err_msg").html("<font color='green'><b>"+data.message+"</b></font>");
	                      get_all_sessions();
                            $("#session_name").val("");
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

    function get_all_sessions()
    {
     $("#session_list_div").html("<center><font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Wait, Loading...</b></font></center>");
     var base_url = '<?php echo base_url(); ?>';
         $.ajax({
                 type: "POST",
                 dataType: "JSON",
                 url: base_url+"app/sessions/get_all_sessions",
                 data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' },
                 cache: false,
                 success: function (data, textStatus, jqXHR) {
	                  
	                  if(data.response == true)
	                   {
	                    var txt = "<table class='table table-bordered table-sm' style='font-size:15px;'>";
	                     txt += "<thead>";
	                      txt += "<tr>";
	                       txt += "<th width='5%'>Sr.No.</th>";
	                       txt += "<th width='10%'>Session Name</th>";
	                       txt += "<th width='5%'>Action</th>";
	                      txt += "</tr>";
	                     txt += "</thead>";
	                     txt += "<tbody>";
	                      for(var i = 0; i < data.total_record; i++)
	                       {
                            txt += "<tr>";
                            txt += "<td>"+parseInt(i+1)+"</td>";
                            txt += "<td>"+data.all_record[i].session_name+"</td>";
	                         txt += "<td align='center'>";
	                          txt += "<i class='fa fa-edit' title='Edit session' style='color:blue;cursor:pointer;cursor:hand;' data-toggle='modal' data-target='#edit_session_modal' onclick='get_session_details_by_id("+data.all_record[i].id+");'></i>";
	                          txt += "&nbsp;";
	                          txt += "<i class='fa fa-times' title='Delete session' style='color:red;cursor:pointer;cursor:hand;' onclick='delete_session("+data.all_record[i].id+");'></i>";
	                         txt += "</td>";
	                        txt += "</tr>";
	                       }
	                     txt += "</tbody>";
	                    txt += "</table>";
	                    $("#session_list_div").html(txt);
	                    
	                    $("input[data-bootstrap-switch]").each(function(){
                                $(this).bootstrapSwitch();
                            });
	                   }
	                  else
	                   {
	                    $("#session_list_div").html("<center><font color='red'><b>"+data.message+"</b></font></center>");
	                   }
	                    
	         }
               });
    }
   
   function get_session_details_by_id(session_id)
    {
     $("#edit_session_id").val(session_id);
     $("#edit_session_name").val("");
     var base_url = '<?php echo base_url(); ?>';
         $.ajax({
                 type: "POST",
                 dataType: "JSON",
                 url: base_url+"app/sessions/get_session_details_by_id",
                 data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' , 'session_id':session_id },
                 cache: false,
                 success: function (data, textStatus, jqXHR) {
                          
                          if(data.response == true)
                           {
                            $("#edit_session_name").val(data.all_record[0].session_name);
                           }
                          
                 }
               });
    }
   
    function edit_sessions_details()
    {
     var session_name = $("#edit_session_name").val();
    
     var session_id = $("#edit_session_id").val();
    
     if(session_id == 0 || session_id.replace(/ /gi , "") == "")
      {
       $("#edit_session_id").focus();
       $("#edit_err_msg").html("<font color='red'><b>Invalid .</b></font>");
      }
     else if(session_name.replace(/ /gi , "") == "")
      {
       $("#edit_session_name").focus();
       $("#edit_err_msg").html("<font color='red'><b>Enter your session name.</b></font>");
      }
    
     else
      {
       $("#eau_btn").prop("disabled" , true);
       $("#edit_err_msg").html("<font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Wait, Validating...</b></font>");
       var base_url = '<?php echo base_url(); ?>';
           $.ajax({
                   type: "POST",
                   dataType: "JSON",
                   url: base_url+"app/sessions/edit_session_details",
                   data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' , 'session_id':session_id , 'session_name':session_name},
                   cache: false,
                   success: function (data, textStatus, jqXHR) {
	                    
	                    if(data.response == true)
	                     {
	                      $("#edit_err_msg").html("<font color='green'><b>"+data.message+"</b></font>");
	                      
	                      get_all_sessions();
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
   
   function delete_session(session_id)
    {
     var conf = confirm("Are you sure to delete this user?");
     if(conf == true)
      {
       var base_url = '<?php echo base_url(); ?>';
           $.ajax({
                   type: "POST",
                   dataType: "JSON",
                   url: base_url+"app/sessions/delete_sessions",
                   data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' , 'session_id':session_id },
                   cache: false,
                   success: function (data, textStatus, jqXHR) {
                            
                            if(data.response == true)
                             {
                             get_all_sessions();
                             }
                            else
                             {
                              alert(data.message);
                             }
                            
                   }
                 });
      }
    }
   
   

   
   
   get_all_sessions();
   
  
  </script>
