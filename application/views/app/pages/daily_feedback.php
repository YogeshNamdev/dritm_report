  <!-- Bootstrap Switch -->
  <script src="<?php echo base_url(); ?>includes/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
  <script src="<?= base_url(); ?>includes/ckeditor/ckeditor.js"></script>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-12">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>app/home">Home</a></li>
              <li class="breadcrumb-item active">Daily Briefing</li>
            </ol>
          </div>
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        
        <div class="card card-default color-palette-box">
          <div class="card-header">
            <h3 class="card-title">
              <h5 class="m-0">Daily Feedbacks</h5>
            </h3>
          </div>
          <div class="card-body">
             <div class="col-md-12" id="user_list_div">
               
             </div>
            </div>
            
          </div>
        </div>
        
      </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
  <div class="modal fade" id="edit_feedback_profile_modal">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Edit User Profile</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              
              
              
              <form id="feedback_form_details" method="post" enctype= "multipart/form-data" >
              <input type="hidden" id="feedback_id" name="feedback_id"/>
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="Enter Tittle." />
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="txtEditor" name="description"></textarea> 
            </div>
            <div class="form-group">
                <label for="link">Link</label>
                <input type="file" class="form-control" id="link" name="link"/>
            </div>
            <div class="form-group">
                <label for="briefing_type">Briefing Status</label>
                <select name="briefing_type" id="briefing_type" class="form-control">
                    <option value="">---Select---</option>
                    <option value="Newly">Newly</option>
                    <option value="Today">Today</option>
                    <option value="Previous">Previous</option>
                </select>
            </div>
              </form>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" id="eau_btn" onclick="edit_feedback();">Submit</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
  </div>
      <!-- /.modal -->
  
  <script>
  
              CKEDITOR.replace( 'txtEditor'); 
          
   function get_feedback_details_by_id(id)
    {
     $("#feedback_id").val(id);
     $("#title").val("");
     $("#txtEditor").val("");
     $("#briefing_type").val("");
     var base_url = '<?php echo base_url(); ?>';
         $.ajax({
                 type: "POST",
                 dataType: "JSON",
                 url: base_url+"app/users/get_feedback_details_by_id",
                 data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' , 'id':id },
                 cache: false,
                 success: function (data, textStatus, jqXHR) {
                          
                          if(data.response == true)
                           {
                            $("#title").val(data.all_record[0].title);
                            $("#txtEditor").val(data.all_record[0].description);
                            CKEDITOR.instances['txtEditor'].setData(data.all_record[0].description);
                            $("#briefing_type").val(data.all_record[0].briefing_type);
                            
                           }
                          
                 }
               });
     //          alert(id);
    }
    function edit_feedback(){
     var title = $("#title").val();
     var txtEditor = CKEDITOR.instances['txtEditor'].getData();
     var link = $("#link").val();
     var briefing_type = $("#briefing_type").val();
     
     if(title.replace(/ /gi , "") == "")
      {
        $("#title").focus();
        $("#err_msg").html("<font color='red'><b>Enter Title..</b></font>");
      }else if(briefing_type == ""){
        $("#briefing_type").focus();
        $("#err_msg").html("<font color='red'><b>Enter Briefing Status..</b></font>");
      }else{
      
       $("#err_msg").html("<font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Wait, Validating...</b></font>");
       var base_url = '<?php echo base_url(); ?>';
        let myForm = document.getElementById('feedback_form_details');
        let formData = new FormData(myForm);
          formData.append("txtEditor",txtEditor);
        $("#submit").prop("disabled", true);

           $.ajax({
                  type: "POST",
                  dataType: "JSON",
                  url: base_url+"app/users/edit_quality_feedback",
                  data: formData,
                  processData: false,
                  contentType: false,
                  cache:false,
                   success: function (data, textStatus, jqXHR) {
	                    
	                    if(data.response == true)
	                     {
	                      $("#err_msg").html("<font color='green'><b>"+data.message+"</b></font>");
                        $("#title").val("");
                        $("#link").val("");
                        $("#briefing_type").val("");
                        CKEDITOR.instances['txtEditor'].setData('');
                        $("#edit_feedback_profile_modal").modal('hide');
                        get_all_feedbacks();
	                     }
	                    else
	                     {
	                      $("#err_msg").html("<font color='red'><b>"+data.message+"</b></font>");
	                     }
	                    $("#submit").prop("disabled" , false);
	                    
	           }
                 });
      }
    }
   
   function delete_feedback(id)
    {
     var conf = confirm("Are you sure to delete this user?");
     if(conf == true)
      {
       var base_url = '<?php echo base_url(); ?>';
           $.ajax({
                   type: "POST",
                   dataType: "JSON",
                   url: base_url+"app/users/delete_feedback",
                   data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' , 'id':id },
                   cache: false,
                   success: function (data, textStatus, jqXHR) {
                            
                            if(data.response == true)
                             {
                              get_all_feedbacks();
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
   
    function get_all_feedbacks()
    {
     $("#user_list_div").html("<center><font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Wait, Loading...</b></font></center>");
     var base_url = '<?php echo base_url(); ?>';
         $.ajax({
                 type: "POST",
                 dataType: "JSON",
                 url: base_url+"app/users/get_all_feedbacks",
                 data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' },
                 cache: false,
                 success: function (data, textStatus, jqXHR) {
	                  
	                  if(data.response == true)
	                   {
	                    var txt = "<table class='table table-bordered table-sm' style='font-size:12px;'>";
	                     txt += "<thead>";
	                      txt += "<tr>";
	                       txt += "<th width='5%'>Sr.No.</th>";
	                       txt += "<th width='8%'>Title</th>";
	                       txt += "<th width='70%'>Description</th>";
	                       txt += "<th width='7%'>Created date</th>";
	                       txt += "<th width='5%'>Briefing Type</th>";
                         txt += "<th width='5%'>Status</th>";
	                       txt += "<th width='5%'>Action</th>";
	                      txt += "</tr>";
	                     txt += "</thead>";
	                     txt += "<tbody>";
	                      for(var i = 0; i < data.total_record; i++)
	                       {
                            txt += "<tr>";
                            txt += "<td>"+parseInt(i+1)+"</td>";
                            txt += "<td>"+data.all_record[i].title+"</td>";
                            txt += "<td>"+data.all_record[i].description+"</td>";
                            txt += "<td>"+data.all_record[i].eat+"</td>";
                            txt += "<td>"+data.all_record[i].briefing_type+"</td>";
                            txt += "<td align='center'><input type='checkbox' name='my-checkbox' data-bootstrap-switch data-off-color='danger' data-on-color='success' "+((data.all_record[i].login_status == 1)?"checked":"")+" onchange='manage_login_status_of_user("+data.all_record[i].id+",this);' /></td>";
                            txt += "<td align='center'>";
                            txt += "<i class='fa fa-edit' title='Edit Feedback Profile' style='color:blue;cursor:pointer;cursor:hand;' data-toggle='modal' data-target='#edit_feedback_profile_modal' onclick='get_feedback_details_by_id("+data.all_record[i].id+");'></i>";
                            txt += "&nbsp;";
                            txt += "<i class='fa fa-times' title='Delete feedback' style='color:red;cursor:pointer;cursor:hand;' onclick='delete_feedback("+data.all_record[i].id+");'></i>";
                            txt += "</td>";
                            txt += "</tr>";
                          } 
                            txt += "</tbody>";
                            txt += "</table>";
                      
	                    $("#user_list_div").html(txt);
	                    
	                    $("input[data-bootstrap-switch]").each(function(){
                                $(this).bootstrapSwitch();
                            });
                          
	                   }
	                  else
	                   {
	                    $("#user_list_div").html("<center><font color='red'><b>"+data.message+"</b></font></center>");
	                   }
	                    
	         }
               });
    }
   
   function add_user()
    {
     var role_id = $("#role_id").val();
     var user_name = $("#user_name").val();
     var msd_id = $("#msd_id").val();
     if(role_id == 0 || role_id.replace(/ /gi , "") == "")
      {
          $("#role_id").focus();
          $("#err_msg").html("<font color='red'><b>Select user role.</b></font>");
      }
     else if(user_name.replace(/ /gi , "") == "")
      {
          $("#user_name").focus();
          $("#err_msg").html("<font color='red'><b>Enter your full name.</b></font>");
      }
     else if(msd_id.replace(/ /gi , "") == "")
      {
          $("#user_email").focus();
          $("#err_msg").html("<font color='red'><b>Enter your valid MSD-id.</b></font>");
      }
     else
      {
          $("#au_btn").prop("disabled" , true);
          $("#err_msg").html("<font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Wait, Validating...</b></font>");
       var base_url = '<?php echo base_url(); ?>';
           $.ajax({
                   type: "POST",
                   dataType: "JSON",
                   url: base_url+"app/users/add_user",
                   data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' , 'role_id':role_id , 'user_name':user_name , 'msd_id':msd_id},
                   cache: false,
                   success: function (data, textStatus, jqXHR) {
	                    
	                    if(data.response == true)
	                     {
	                      $("#err_msg").html("<font color='green'><b>"+data.message+"</b></font>");
	                      
	                      $("#role_id").val(0);
                              $("#user_name").val("");
                              $("#msd_id").val("");
	                      
	                      get_all_users();
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
   
   
   function edit_user_profile()
    {
     var user_id = $("#edit_user_id").val();
     var role_id = $("#edit_role_id").val();
     var user_name = $("#edit_user_name").val();
     var msd_id = $("#edit_msd_id").val();
    
     if(user_id == 0 || user_id.replace(/ /gi , "") == "")
      {
       $("#edit_user_id").focus();
       $("#edit_err_msg").html("<font color='red'><b>Invalid User-ID.</b></font>");
      }
     else if(role_id == 0 || role_id.replace(/ /gi , "") == "")
      {
       $("#edit_role_id").focus();
       $("#edit_err_msg").html("<font color='red'><b>Select user role.</b></font>");
      }
     else if(user_name.replace(/ /gi , "") == "")
      {
       $("#edit_user_name").focus();
       $("#edit_err_msg").html("<font color='red'><b>Enter your full name.</b></font>");
      }
     else if(msd_id.replace(/ /gi , "") == "")
      {
       $("#edit_msd_id").focus();
       $("#edit_err_msg").html("<font color='red'><b>Enter your valid MSD-ID.</b></font>");
      }
     else
      {
       $("#eau_btn").prop("disabled" , true);
       $("#edit_err_msg").html("<font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Wait, Validating...</b></font>");
       var base_url = '<?php echo base_url(); ?>';
           $.ajax({
                   type: "POST",
                   dataType: "JSON",
                   url: base_url+"app/users/edit_user_profile",
                   data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' , 'user_id':user_id , 'role_id':role_id , 'user_name':user_name , 'msd_id':msd_id},
                   cache: false,
                   success: function (data, textStatus, jqXHR) {
	                    
	                    if(data.response == true)
	                     {
	                      $("#edit_err_msg").html("<font color='green'><b>"+data.message+"</b></font>");
	                      
	                      get_all_users();
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
   
    get_all_feedbacks();
  </script>
