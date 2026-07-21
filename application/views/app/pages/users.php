  <!-- Bootstrap Switch -->
  <script src="<?php echo base_url(); ?>includes/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-12">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>app/home">Home</a></li>
              <li class="breadcrumb-item active">Application Users</li>
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
              <h5 class="m-0">Application Users</h5>
            </h3>
          </div>
          <div class="card-body">
            
            <div class="row">
             <div class="col-sm-3">
              
              <div class="form-group">
                <label for="role_id">Role</label>
                <select class="form-control" id="role_id">
                  <option value="0">-- Select --</option>
                  <?php
                   if($role_list != FALSE)
                    {
                     foreach($role_list as $rolelist)
                      {
                       echo "<option value='".$rolelist->role_id."'>".$rolelist->role_name."</option>";
                      }
                    }
                  ?>
                </select>
              </div>
              
              <div class="form-group">
                <label for="user_name">Name</label>
                <input type="text" class="form-control" id="user_name" placeholder="Enter full name." />
              </div>
              
              <div class="form-group">
                <label for="msd_id">Dritm-ID</label>
                <input type="text" class="form-control" id="msd_id" placeholder="Enter valid Dritm-ID." />
              </div>

              <div class="form-group">
                <label for="user_password">Password</label>
                <input type="password" class="form-control" id="user_password" placeholder="Enter password." />
              </div>
              <div class="row">
                <div class="col-sm-12">
                  <button type="button" class="btn btn-primary" id="au_btn" onclick="add_user();">Submit</button>
                </div>
              </div>
              
              <div class="row"><div class="col-sm-12">&nbsp;</div></div>
              
              <div class="row">
                <div class="col-sm-12" id="err_msg">
                  &nbsp;
                </div>
              </div>
              
             </div>
             <div class="col-sm-9" id="user_list_div">
               
             </div>
            </div>
            
          </div>
        </div>
        
      </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
  <div class="modal fade" id="edit_user_profile_modal">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Edit User Profile</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              
              <input type="hidden" id="edit_user_id" />
              
              <div class="form-group">
                <label for="edit_role_id">Role</label>
                <select class="form-control" id="edit_role_id">
                  <option value="0">-- Select --</option>
                  <?php
                   if($role_list != FALSE)
                    {
                     foreach($role_list as $rolelist)
                      {
                       echo "<option value='".$rolelist->role_id."'>".$rolelist->role_name."</option>";
                      }
                    }
                  ?>
                </select>
              </div>
              
              <div class="form-group">
                <label for="edit_user_name">Name</label>
                <input type="text" class="form-control" id="edit_user_name" placeholder="Enter full name." />
              </div>
              
              <div class="form-group">
                <label for="edit_msd_id">MSD-ID</label>
                <input type="text" class="form-control" id="edit_msd_id" placeholder="Enter valid MSD-ID." />
              </div>

              <div class="form-group">
                <label for="edit_user_password">Password</label>
                <input type="password" class="form-control" id="edit_user_password" placeholder="Leave blank to keep current password." />
              </div>
              
              <div class="form-group" id="edit_err_msg"></div>
              
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" id="eau_btn" onclick="edit_user_profile();">Submit</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
  </div>
      <!-- /.modal -->
  
  <script>
   function get_user_details_by_id(user_id)
    {
     $("#edit_user_id").val(user_id);
     $("#edit_role_id").val(0);
     $("#edit_user_name").val("");
     $("#edit_user_email").val("");
     $("#edit_user_mobile").val("");
     $("#edit_user_address").val("");
     $("#edit_user_password").val("");
     
     var base_url = '<?php echo base_url(); ?>';
         $.ajax({
                 type: "POST",
                 dataType: "JSON",
                 url: base_url+"app/users/get_user_details_by_id",
                 data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' , 'user_id':user_id },
                 cache: false,
                 success: function (data, textStatus, jqXHR) {
                          
                          if(data.response == true)
                           {
                            $("#edit_role_id").val(data.all_record[0].role_id);
                            $("#edit_user_name").val(data.all_record[0].user_name);
                            $("#edit_msd_id").val(data.all_record[0].msd_id);
                            
                           }
                          
                 }
               });
    }
   
   
   function delete_user(user_id)
    {
     var conf = confirm("Are you sure to delete this user?");
     if(conf == true)
      {
       var base_url = '<?php echo base_url(); ?>';
           $.ajax({
                   type: "POST",
                   dataType: "JSON",
                   url: base_url+"app/users/delete_user",
                   data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' , 'user_id':user_id },
                   cache: false,
                   success: function (data, textStatus, jqXHR) {
                            
                            if(data.response == true)
                             {
                              get_all_users();
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
   
   
   function get_all_users()
    {
     $("#user_list_div").html("<center><font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Wait, Loading...</b></font></center>");
     var base_url = '<?php echo base_url(); ?>';
         $.ajax({
                 type: "POST",
                 dataType: "JSON",
                 url: base_url+"app/users/get_all_users",
                 data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' },
                 cache: false,
                 success: function (data, textStatus, jqXHR) {
	                  
	                  if(data.response == true)
	                   {
	                    var txt = "<table class='table table-bordered table-sm' style='font-size:12px;'>";
	                     txt += "<thead>";
	                      txt += "<tr>";
	                       txt += "<th width='5%'>Sr.No.</th>";
	                       txt += "<th width='10%'>Role</th>";
	                       txt += "<th width='15%'>Name</th>";
	                       txt += "<th width='20%'>MSD-ID</th>";
	                       txt += "<th width='5%'>Login Status</th>";
	                       txt += "<th width='5%'>Action</th>";
	                      txt += "</tr>";
	                     txt += "</thead>";
	                     txt += "<tbody>";
	                      for(var i = 0; i < data.total_record; i++)
	                       {
	                        txt += "<tr>";
	                         txt += "<td>"+parseInt(i+1)+"</td>";
	                         txt += "<td>"+data.all_record[i].role_name+"</td>";
	                         txt += "<td>"+data.all_record[i].user_name+"</td>";
	                         txt += "<td>"+data.all_record[i].msd_id+"</td>";
							 
	                         txt += "<td align='center'><input type='checkbox' name='my-checkbox' data-bootstrap-switch data-off-color='danger' data-on-color='success' "+((data.all_record[i].login_status == 1)?"checked":"")+" onchange='manage_login_status_of_user("+data.all_record[i].user_id+",this);' /></td>";
	                         txt += "<td align='center'>";
	                          txt += "<i class='fa fa-edit' title='Edit User Profile' style='color:blue;cursor:pointer;cursor:hand;' data-toggle='modal' data-target='#edit_user_profile_modal' onclick='get_user_details_by_id("+data.all_record[i].user_id+");'></i>";
	                          txt += "&nbsp;";
	                          txt += "<i class='fa fa-times' title='Delete User' style='color:red;cursor:pointer;cursor:hand;' onclick='delete_user("+data.all_record[i].user_id+");'></i>";
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
     var user_password = $("#user_password").val();
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
          $("#msd_id").focus();
          $("#err_msg").html("<font color='red'><b>Enter your valid MSD-ID.</b></font>");
      }
     else if(user_password.replace(/ /gi , "") == "")
      {
          $("#user_password").focus();
          $("#err_msg").html("<font color='red'><b>Enter user password.</b></font>");
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
                   data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' , 'role_id':role_id , 'user_name':user_name , 'msd_id':msd_id, 'user_password':user_password},
                   cache: false,
                   success: function (data, textStatus, jqXHR) {
	                    
	                    if(data.response == true)
	                     {
	                      $("#err_msg").html("<font color='green'><b>"+data.message+"</b></font>");
	                      
	                      $("#role_id").val(0);
                              $("#user_name").val("");
                              $("#msd_id").val("");
                              $("#user_password").val("");
	                      
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
     var user_password = $("#edit_user_password").val();
    
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
                   data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' , 'user_id':user_id , 'role_id':role_id , 'user_name':user_name , 'msd_id':msd_id, 'user_password':user_password},
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
   
   get_all_users();
  </script>
