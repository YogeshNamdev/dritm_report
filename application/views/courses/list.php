
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
                        <li class="breadcrumb-item active">Course Master</li>
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
                    <h3 class="card-title m-0">Course Master</h3>
                </div>

                <div class="card-body">

                    

                        <div class="row">

                            <!-- LEFT FORM -->
                            <div class="col-sm-3">

                                <div class="form-group">
                                    <label>Course Name</label>
                                    <input type="text" name="course_name" id="course_name"
                                           class="form-control"
                                           placeholder="Course name"
                                           required>
                                </div>

                                <div class="form-group">
                                    <label>Course Duration</label>
                                    <input type="text" name="duration" id="duration"
                                           class="form-control"
                                           placeholder="Duration (Year)">
                                </div>

                                <div class="form-group">
                                    <label>Course Type</label>
                                    <input type="text" name="type" id="type"
                                           class="form-control"
                                           placeholder="Type">
                                </div>

                                <div class="form-group">
                                     <button type="button" class="btn btn-primary" id="au_btn" onclick="add_course();">Submit</button>
                                    
                                </div>

                                <div id="err_msg"></div>

                            </div>


                            <!-- RIGHT TABLE -->
                            <div class="col-sm-9" id="course_list_div">

                            </div>

                        </div>

                 

                </div>
            </div>

        </div>
    </section>

</div>

<div class="modal fade" id="edit_course_modal">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Edit User Profile</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              
              <input type="hidden" id="edit_course_id" />
              
              <div class="form-group">
                <label>Course Name</label>
                <input type="text" name="edit_course_name" id="edit_course_name"
                        class="form-control"
                        placeholder="Course name"
                        required>
            </div>

            <div class="form-group">
                <label>Course Duration</label>
                <input type="text" name="edit_duration" id="edit_duration"
                        class="form-control"
                        placeholder="Duration (months)">
            </div>

            <div class="form-group">
                <label>Course Type</label>
                <input type="text" name="edit_type" id="edit_type"
                        class="form-control"
                        placeholder="Type">
            </div>
              
              <div class="form-group" id="edit_err_msg"></div>
              
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" id="eau_btn" onclick="edit_course_details();">Submit</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
  </div>
  <script>
    function add_course()
    {
     var course_name = $("#course_name").val();
     var duration = $("#duration").val();
     var type = $("#type").val();
     if(course_name.replace(/ /gi , "") == "")
      {
          $("#course_name").focus();
          $("#err_msg").html("<font color='red'><b>Enter your course name.</b></font>");
      }
     else if(duration.replace(/ /gi , "") == "")
      {
          $("#duration").focus();
          $("#err_msg").html("<font color='red'><b>Enter Duration.</b></font>");
      }
       else if(type.replace(/ /gi , "") == "")
      {
          $("#type").focus();
          $("#err_msg").html("<font color='red'><b>Enter Type.</b></font>");
      }
     else
      {
          $("#au_btn").prop("disabled" , true);
          $("#err_msg").html("<font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Wait, Validating...</b></font>");
       var base_url = '<?php echo base_url(); ?>';
           $.ajax({
                   type: "POST",
                   dataType: "JSON",
                   url: base_url+"app/courses/save",
                   data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' , 'course_name':course_name , 'duration':duration , 'type':type},
                   cache: false,
                   success: function (data, textStatus, jqXHR) {
	                    
	                    if(data.response == true)
	                     {
	                      $("#err_msg").html("<font color='green'><b>"+data.message+"</b></font>");
	                      
                            $("#course_name").val("");
                            $("#duration").val("");
                            $("#type").val("");
	                      
	                      get_all_courses();
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

    function get_all_courses()
    {
     $("#course_list_div").html("<center><font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Wait, Loading...</b></font></center>");
     var base_url = '<?php echo base_url(); ?>';
         $.ajax({
                 type: "POST",
                 dataType: "JSON",
                 url: base_url+"app/courses/get_all_courses",
                 data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' },
                 cache: false,
                 success: function (data, textStatus, jqXHR) {
	                  
	                  if(data.response == true)
	                   {
	                    var txt = "<table class='table table-bordered table-sm' style='font-size:15px;'>";
	                     txt += "<thead>";
	                      txt += "<tr>";
	                       txt += "<th width='5%'>Sr.No.</th>";
	                       txt += "<th width='10%'>Course Name</th>";
	                       txt += "<th width='15%'>Duration</th>";
	                       txt += "<th width='20%'>Type</th>";
	                       txt += "<th width='5%'>Action</th>";
	                      txt += "</tr>";
	                     txt += "</thead>";
	                     txt += "<tbody>";
	                      for(var i = 0; i < data.total_record; i++)
	                       {
                            txt += "<tr>";
                            txt += "<td>"+parseInt(i+1)+"</td>";
                            txt += "<td>"+data.all_record[i].course_name+"</td>";
                            txt += "<td>"+data.all_record[i].duration+"</td>";
                            txt += "<td>"+data.all_record[i].type+"</td>";
                           
	                         txt += "<td align='center'>";
	                          txt += "<i class='fa fa-edit' title='Edit Course' style='color:blue;cursor:pointer;cursor:hand;' data-toggle='modal' data-target='#edit_course_modal' onclick='get_course_details_by_id("+data.all_record[i].id+");'></i>";
	                          txt += "&nbsp;";
	                          txt += "<i class='fa fa-times' title='Delete course' style='color:red;cursor:pointer;cursor:hand;' onclick='delete_course("+data.all_record[i].id+");'></i>";
	                         txt += "</td>";
	                        txt += "</tr>";
	                       }
	                     txt += "</tbody>";
	                    txt += "</table>";
	                    $("#course_list_div").html(txt);
	                    
	                    $("input[data-bootstrap-switch]").each(function(){
                                $(this).bootstrapSwitch();
                            });
	                   }
	                  else
	                   {
	                    $("#course_list_div").html("<center><font color='red'><b>"+data.message+"</b></font></center>");
	                   }
	                    
	         }
               });
    }
   
   function get_course_details_by_id(course_id)
    {
     $("#edit_course_id").val(course_id);
     $("#course_name").val("");
     $("#edit_duration").val("");
     $("#edit_type").val("");
     
     var base_url = '<?php echo base_url(); ?>';
         $.ajax({
                 type: "POST",
                 dataType: "JSON",
                 url: base_url+"app/courses/get_course_details_by_id",
                 data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' , 'course_id':course_id },
                 cache: false,
                 success: function (data, textStatus, jqXHR) {
                          
                          if(data.response == true)
                           {
                            $("#edit_course_name").val(data.all_record[0].course_name);
                            $("#edit_duration").val(data.all_record[0].duration);
                            $("#edit_type").val(data.all_record[0].type);
                           }
                          
                 }
               });
    }
   
    function edit_course_details()
    {
     var course_name = $("#edit_course_name").val();
     var duration = $("#edit_duration").val();
     var type = $("#edit_type").val();
     var course_id = $("#edit_course_id").val();
    
     if(course_id == 0 || course_id.replace(/ /gi , "") == "")
      {
       $("#edit_course_id").focus();
       $("#edit_err_msg").html("<font color='red'><b>Invalid .</b></font>");
      }
     else if(course_name.replace(/ /gi , "") == "")
      {
       $("#edit_course_name").focus();
       $("#edit_err_msg").html("<font color='red'><b>Enter your course name.</b></font>");
      }
     else if(duration.replace(/ /gi , "") == "")
      {
       $("#edit_duration").focus();
       $("#edit_err_msg").html("<font color='red'><b>Enter valid duration.</b></font>");
      }else if(type.replace(/ /gi , "") == "")
      {
       $("#edit_type").focus();
       $("#edit_err_msg").html("<font color='red'><b>Enter Type of course.</b></font>");
      }
     else
      {
       $("#eau_btn").prop("disabled" , true);
       $("#edit_err_msg").html("<font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Wait, Validating...</b></font>");
       var base_url = '<?php echo base_url(); ?>';
           $.ajax({
                   type: "POST",
                   dataType: "JSON",
                   url: base_url+"app/courses/edit_course_details",
                   data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' , 'course_id':course_id , 'type':type , 'duration':duration , 'course_name':course_name},
                   cache: false,
                   success: function (data, textStatus, jqXHR) {
	                    
	                    if(data.response == true)
	                     {
	                      $("#edit_err_msg").html("<font color='green'><b>"+data.message+"</b></font>");
	                      
	                      get_all_courses();
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
   
   function delete_course(course_id)
    {
     var conf = confirm("Are you sure to delete this user?");
     if(conf == true)
      {
       var base_url = '<?php echo base_url(); ?>';
           $.ajax({
                   type: "POST",
                   dataType: "JSON",
                   url: base_url+"app/courses/delete_course",
                   data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' , 'course_id':course_id },
                   cache: false,
                   success: function (data, textStatus, jqXHR) {
                            
                            if(data.response == true)
                             {
                             get_all_courses();
                             }
                            else
                             {
                              alert(data.message);
                             }
                            
                   }
                 });
      }
    }
   
   
   function manage_login_status_of_user(user_id,obj)
    {
     var login_status = (obj.checked == true)?1:0;
     var base_url = '<?php echo base_url(); ?>';
         $.ajax({
                 type: "POST",
                 dataType: "JSON",
                 url: base_url+"app/users/manage_login_status_of_user",
                 data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' , 'user_id':user_id , 'login_status':login_status },
                 cache: false,
                 success: function (data, textStatus, jqXHR) {
                          
                 }
               });
    }
   
   
   
   
   
   get_all_courses();
   
  
  </script>
