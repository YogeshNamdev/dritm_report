
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
                        <li class="breadcrumb-item active">Batch Master</li>
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
                    <h3 class="card-title m-0">Batch Master</h3>
                </div>

                <div class="card-body">
                        <div class="row">
                            <!-- LEFT FORM -->
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="role_id">Course</label>
                                    <select class="form-control" id="course_id">
                                    <option value="0">-- Select --</option>
                                    <?php
                                    if($course_list != FALSE)
                                        {
                                        foreach($course_list as $courselist)
                                        {
                                        echo "<option value='".$courselist->id."'>".$courselist->course_name."</option>";
                                        }
                                        }
                                    ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="role_id">Session</label>
                                    <select class="form-control" id="session_id">
                                    <option value="0">-- Select --</option>
                                    <?php
                                    if($session_list != FALSE)
                                        {
                                        foreach($session_list as $sessionlist)
                                        {
                                        echo "<option value='".$sessionlist->id."'>".$sessionlist->session_name."</option>";
                                        }
                                        }
                                    ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Batch Name</label>
                                    <input type="text" name="batch_name" id="batch_name"
                                           class="form-control"
                                           placeholder="Example: BCA-S"
                                           required>
                                </div>
                                <div class="form-group">
                                    <label>Batch Start</label>
                                    <input type="date" name="start_date" id="start_date"
                                           class="form-control"
                                           required>
                                </div>
                                <div class="form-group">
                                    <label>Batch End</label>
                                    <input type="date" name="end_date" id="end_date"
                                           class="form-control"
                                           required>
                                </div>
                                <div class="form-group">
                                     <button type="button" class="btn btn-primary" id="au_btn" onclick="add_batch();">Submit</button>
                                    
                                </div>

                                <div id="err_msg"></div>

                            </div>


                            <!-- RIGHT TABLE -->
                            <div class="col-sm-9" id="batch_list_div">

                            </div>

                        </div>

                 

                </div>
            </div>

        </div>
    </section>

</div>

<div class="modal fade" id="edit_batch_modal">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Edit batch</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              
              <input type="hidden" id="edit_batch_id" />
              
              <div class="form-group">
                    <label for="role_id">Course</label>
                    <select class="form-control" id="edit_course_id">
                    <option value="0">-- Select --</option>
                    <?php
                    if($course_list != FALSE)
                        {
                        foreach($course_list as $courselist)
                        {
                        echo "<option value='".$courselist->id."'>".$courselist->course_name."</option>";
                        }
                        }
                    ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="role_id">batch</label>
                    <select class="form-control" id="edit_session_id">
                    <option value="0">-- Select --</option>
                    <?php
                    if($session_list != FALSE)
                        {
                        foreach($session_list as $sessionlist)
                        {
                        echo "<option value='".$sessionlist->id."'>".$sessionlist->session_name."</option>";
                        }
                        }
                    ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Batch Name</label>
                    <input type="text" name="edit_batch_name" id="edit_batch_name"
                            class="form-control"
                            placeholder="Example: BCA-S"
                            required>
                </div>
                <div class="form-group">
                    <label>Batch Start</label>
                    <input type="date" name="edit_start_date" id="edit_start_date"
                            class="form-control"
                            required>
                </div>
                <div class="form-group">
                    <label>Batch End</label>
                    <input type="date" name="edit_end_date" id="edit_end_date"
                            class="form-control"
                            required>
                </div>
              <div class="form-group" id="edit_err_msg"></div>
              
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" id="eau_btn" onclick="edit_batches_details();">Submit</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
  </div>
  <script>
    function add_batch()
    {
     var batch_name = $("#batch_name").val();
     var session_id = $("#session_id").val();
     var course_id = $("#course_id").val();
     var start_date = $("#start_date").val();
     var end_date = $("#end_date").val();
     
     if(course_id.replace(/ /gi , "") == 0)
      {
          $("#course_id").focus();
          $("#err_msg").html("<font color='red'><b>Please Select course.</b></font>");
      }else if(session_id.replace(/ /gi , "") == "")
      {
          $("#batch_name").focus();
          $("#err_msg").html("<font color='red'><b>Please Select session..</b></font>");
      }else if(batch_name.replace(/ /gi , "") == "")
      {
          $("#batch_name").focus();
          $("#err_msg").html("<font color='red'><b>Enter batch name.</b></font>");
      }else if(start_date.replace(/ /gi , "") == "")
      {
          $("#start_date").focus();
          $("#err_msg").html("<font color='red'><b>Enter Select start Date.</b></font>");
      }else if(end_date.replace(/ /gi , "") == "")
      {
          $("#end_date").focus();
          $("#err_msg").html("<font color='red'><b>Enter Select End Date.</b></font>");
      }
     else
      {
          $("#au_btn").prop("disabled" , true);
          $("#err_msg").html("<font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Wait, Validating...</b></font>");
       var base_url = '<?php echo base_url(); ?>';
           $.ajax({
                   type: "POST",
                   dataType: "JSON",
                   url: base_url+"app/batches/save",
                   data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' ,
                    'batch_name':batch_name ,
                    'session_id':session_id ,
                    'course_id':course_id ,
                    'start_date':start_date ,
                    'end_date':end_date ,
                     },
                   cache: false,
                   success: function (data, textStatus, jqXHR) {
	                    
	                    if(data.response == true)
	                     {
	                      $("#err_msg").html("<font color='green'><b>"+data.message+"</b></font>");
	                      get_all_batches();
                            $("#batch_name").val("");
                            $("#session_id").val("0");
                            $("#course_id").val("0");
                            $("#start_date").val("");
                            $("#end_date").val("");
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

    function get_all_batches()
    {
     $("#batch_list_div").html("<center><font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Wait, Loading...</b></font></center>");
     var base_url = '<?php echo base_url(); ?>';
         $.ajax({
                 type: "POST",
                 dataType: "JSON",
                 url: base_url+"app/batches/get_all_batches",
                 data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' },
                 cache: false,
                 success: function (data, textStatus, jqXHR) {
	                  
	                  if(data.response == true)
	                   {
	                    var txt = "<table class='table table-bordered table-sm' style='font-size:15px;'>";
	                     txt += "<thead>";
	                      txt += "<tr>";
	                       txt += "<th width='5%'>Sr.No.</th>";
	                       txt += "<th width='10%'>Batch Name</th>";
	                       txt += "<th width='5%'>Course Name</th>";
                           txt += "<th width='5%'>Session Name</th>";
                           txt += "<th width='5%'>Action</th>";
	                      txt += "</tr>";
	                     txt += "</thead>";
	                     txt += "<tbody>";
	                      for(var i = 0; i < data.total_record; i++)
	                       {
                            txt += "<tr>";
                            txt += "<td>"+parseInt(i+1)+"</td>";
                            txt += "<td>"+data.all_record[i].batch_name+" ("+data.all_record[i].start_date+"-"+data.all_record[i].end_date+")</td>";
                            txt += "<td>"+data.all_record[i].course_name+"</td>";
                            txt += "<td>"+data.all_record[i].session_name+"</td>";
	                         txt += "<td align='center'>";
	                          txt += "<i class='fa fa-edit' title='Edit batch' style='color:blue;cursor:pointer;cursor:hand;' data-toggle='modal' data-target='#edit_batch_modal' onclick='get_batch_details_by_id("+data.all_record[i].id+");'></i>";
	                          txt += "&nbsp;";
	                          txt += "<i class='fa fa-times' title='Delete batch' style='color:red;cursor:pointer;cursor:hand;' onclick='delete_batch("+data.all_record[i].id+");'></i>";
	                         txt += "</td>";
	                        txt += "</tr>";
	                       }
	                     txt += "</tbody>";
	                    txt += "</table>";
	                    $("#batch_list_div").html(txt);
	                    
	                    $("input[data-bootstrap-switch]").each(function(){
                                $(this).bootstrapSwitch();
                            });
	                   }
	                  else
	                   {
	                    $("#batch_list_div").html("<center><font color='red'><b>"+data.message+"</b></font></center>");
	                   }
	                    
	         }
               });
    }
   
   function get_batch_details_by_id(batch_id)
    {
     $("#edit_batch_id").val(batch_id);
     $("#edit_batch_name").val("");
     var base_url = '<?php echo base_url(); ?>';
         $.ajax({
                 type: "POST",
                 dataType: "JSON",
                 url: base_url+"app/batches/get_batch_details_by_id",
                 data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' , 'batch_id':batch_id },
                 cache: false,
                 success: function (data, textStatus, jqXHR) {
                          
                          if(data.response == true)
                           {
                            $("#edit_batch_name").val(data.all_record[0].batch_name);
                            $("#edit_start_date").val(data.all_record[0].start_date);
                            $("#edit_end_date").val(data.all_record[0].end_date);
                            $("#edit_session_id").val(data.all_record[0].session_id);
                            $("#edit_course_id").val(data.all_record[0].course_id);
                           }
                          
                 }
               });
    }
   
    function edit_batches_details()
    {
     var batch_name = $("#edit_batch_name").val();
     var session_id = $("#edit_session_id").val();
     var course_id = $("#edit_course_id").val();
     var start_date = $("#edit_start_date").val();
     var end_date = $("#edit_end_date").val();
     var batch_id = $("#edit_batch_id").val();
    
     if(course_id.replace(/ /gi , "") == 0)
      {
          $("#edit_course_id").focus();
          $("#edit_err_msg").html("<font color='red'><b>Please Select course.</b></font>");
      }else if(session_id.replace(/ /gi , "") == "")
      {
          $("#edit_batch_name").focus();
          $("#edit_err_msg").html("<font color='red'><b>Please Select session..</b></font>");
      }else if(batch_name.replace(/ /gi , "") == "")
      {
          $("#edit_session_id").focus();
          $("#edit_err_msg").html("<font color='red'><b>Enter batch name.</b></font>");
      }else if(start_date.replace(/ /gi , "") == "")
      {
          $("#edit_start_date").focus();
          $("#edit_err_msg").html("<font color='red'><b>Enter Select start Date.</b></font>");
      }else if(end_date.replace(/ /gi , "") == "")
      {
          $("#edit_end_date").focus();
          $("#edit_err_msg").html("<font color='red'><b>Enter Select End Date.</b></font>");
      }
     else
      {
       $("#eau_btn").prop("disabled" , true);
       $("#edit_err_msg").html("<font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Wait, Validating...</b></font>");
       var base_url = '<?php echo base_url(); ?>';
           $.ajax({
                   type: "POST",
                   dataType: "JSON",
                   url: base_url+"app/batches/edit_batch_details",
                   data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' , 'batch_id':batch_id,'batch_name':batch_name ,
                    'session_id':session_id ,
                    'course_id':course_id ,
                    'start_date':start_date ,
                    'end_date':end_date },
                   cache: false,
                   success: function (data, textStatus, jqXHR) {
	                    
	                    if(data.response == true)
	                     {
	                      $("#edit_err_msg").html("<font color='green'><b>"+data.message+"</b></font>");
	                      
	                      get_all_batches();
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
   
   function delete_batch(batch_id)
    {
     var conf = confirm("Are you sure to delete this user?");
     if(conf == true)
      {
       var base_url = '<?php echo base_url(); ?>';
           $.ajax({
                   type: "POST",
                   dataType: "JSON",
                   url: base_url+"app/batches/delete_batch",
                   data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' , 'batch_id':batch_id },
                   cache: false,
                   success: function (data, textStatus, jqXHR) {
                            
                            if(data.response == true)
                             {
                             get_all_batches();
                             }
                            else
                             {
                              alert(data.message);
                             }
                            
                   }
                 });
      }
    }
   
   

   
   
   get_all_batches();
   
  
  </script>
