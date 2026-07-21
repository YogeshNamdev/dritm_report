  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-12">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>app/home">Home</a></li>
              <li class="breadcrumb-item active">User Roles</li>
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
              <h5 class="m-0">User Roles</h5>
            </h3>
          </div>
          <div class="card-body">
            
            <div class="row">
             <div class="col-sm-3">
              
              <div class="form-group">
                <label for="role_name">Role Name</label>
                <input type="text" class="form-control" id="role_name" placeholder="Enter role name." />
              </div>
              
              <div class="row">
                <div class="col-sm-12">
                  <button type="button" class="btn btn-primary" id="ar_btn" onclick="add_user_role();">Submit</button>
                </div>
              </div>
              
              <div class="row"><div class="col-sm-12">&nbsp;</div></div>
              
              <div class="row">
                <div class="col-sm-12" id="err_msg">
                  &nbsp;
                </div>
              </div>
              
             </div>
             <div class="col-sm-9" id="role_list_div">
               
             </div>
            </div>
            
          </div>
        </div>
        
      </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <div class="modal fade" id="edit_user_role_modal">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Edit User Role</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <input type="hidden" id="edit_role_id" />
              <div class="form-group">
                <label for="edit_role_name">Role Name</label>
                <input type="text" class="form-control" id="edit_role_name" placeholder="Enter role name." />
              </div>
              <div class="form-group" id="edit_err_msg"></div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" id="er_btn" onclick="edit_user_role();">Submit</button>
            </div>
          </div>
        </div>
  </div>
  
  <script>
   function open_edit_user_role_modal(role_id, role_name)
    {
     $("#edit_role_id").val(role_id);
     $("#edit_role_name").val(role_name);
     $("#edit_err_msg").html("");
     $("#edit_user_role_modal").modal("show");
    }

   function edit_user_role()
    {
     var role_id = $("#edit_role_id").val();
     var role_name = $("#edit_role_name").val();
     if(role_id == 0 || role_id.replace(/ /gi , "") == "")
      {
       $("#edit_err_msg").html("<font color='red'><b>Invalid role.</b></font>");
      }
     else if(role_name.replace(/ /gi , "") == "")
      {
       $("#edit_role_name").focus();
       $("#edit_err_msg").html("<font color='red'><b>Enter user role name.</b></font>");
      }
     else
      {
       $("#er_btn").prop("disabled" , true);
       $("#edit_err_msg").html("<font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Wait, Validating...</b></font>");
       var base_url = '<?php echo base_url(); ?>';
           $.ajax({
                   type: "POST",
                   dataType: "JSON",
                   url: base_url+"app/users/edit_user_role",
                   data: {
                       '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',
                       'role_id':role_id,
                       'role_name':role_name
                   },
                   cache: false,
                   success: function (data) {
                    if(data.response == true)
                     {
                      $("#edit_err_msg").html("<font color='green'><b>"+data.message+"</b></font>");
                      get_all_user_roles();
                     }
                    else
                     {
                      $("#edit_err_msg").html("<font color='red'><b>"+data.message+"</b></font>");
                     }
                    $("#er_btn").prop("disabled" , false);
	           }
                 });
      }
    }

   function delete_user_role(role_id)
    {
     var conf = confirm("Are you sure to delete this user role?");
     if(conf == true)
      {
       var base_url = '<?php echo base_url(); ?>';
           $.ajax({
                   type: "POST",
                   dataType: "JSON",
                   url: base_url+"app/users/delete_user_role",
                   data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' , 'role_id':role_id },
                   cache: false,
                   success: function (data, textStatus, jqXHR) {
                            
                            if(data.response == true)
                             {
                             $("#err_msg").html("<font color='green'><b>"+data.message+"</b></font>");
                              get_all_user_roles();
                              
                             }
                            else
                             {
                              alert(data.message);
                             }
                            
                   }
                 });
      }
    }
   
   
   function get_all_user_roles()
    {
     $("#role_list_div").html("<center><font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Wait, Loading...</b></font></center>");
     var base_url = '<?php echo base_url(); ?>';
         $.ajax({
                 type: "POST",
                 dataType: "JSON",
                 url: base_url+"app/users/get_all_user_roles",
                 data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' },
                 cache: false,
                 success: function (data, textStatus, jqXHR) {
	                  
	                  if(data.response == true)
	                   {
	                    var txt = "<table class='table table-bordered table-sm'>";
	                     txt += "<thead>";
	                      txt += "<tr>";
	                       txt += "<th width='10%'>Sr.No.</th>";
	                       txt += "<th width='74%'>Role Name</th>";
	                       txt += "<th width='16%'>Action</th>";
	                      txt += "</tr>";
	                     txt += "</thead>";
	                     txt += "<tbody>";
	                      for(var i = 0; i < data.total_record; i++)
	                       {
	                        txt += "<tr>";
	                         txt += "<td>"+parseInt(i+1)+"</td>";
                             var roleName = String(data.all_record[i].role_name).replace(/'/g, "\\'");
	                         txt += "<td>"+data.all_record[i].role_name+"</td>";
	                         txt += "<td align='center'>";
                              txt += "<i class='fa fa-edit' title='Edit Role' style='color:blue;cursor:pointer;cursor:hand;' onclick=\"open_edit_user_role_modal("+data.all_record[i].role_id+", '"+roleName+"');\"></i>";
                              txt += "&nbsp;";
                              txt += "<i class='fa fa-times' title='Delete Role' style='color:red;cursor:pointer;cursor:hand;' onclick='delete_user_role("+data.all_record[i].role_id+");'></i>";
                             txt += "</td>";
	                        txt += "</tr>";
	                       }
	                     txt += "</tbody>";
	                    txt += "</table>";
	                    $("#role_list_div").html(txt);
	                   }
	                  else
	                   {
	                    $("#role_list_div").html("<center><font color='red'><b>"+data.message+"</b></font></center>");
	                   }
	                    
	         }
               });
    }
   
   
   function add_user_role()
    {
     var role_name = $("#role_name").val();
     //console.log(role_name);
     if(role_name.replace(/ /gi , "") == "")
      {
       $("#role_name").focus();
       $("#err_msg").html("<font color='red'><b>Enter user role name.</b></font>");
      }
     else
      {
       $("#ar_btn").prop("disabled" , true);
       $("#err_msg").html("<font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Wait, Validating...</b></font>");
       var base_url = '<?php echo base_url(); ?>';
           $.ajax({
                   type: "POST",
                   dataType: "JSON",
                   url: base_url+"app/users/add_user_role",
                   data: {
                       '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',
                       'role_name':role_name
                   },
                   cache: false,
                   success: function (data, textStatus, jqXHR) {
	                    
	                    if(data.response == true)
	                     {
	                      $("#err_msg").html("<font color='green'><b>"+data.message+"</b></font>");
	                      get_all_user_roles();
	                     }
	                    else
	                     {
	                      $("#err_msg").html("<font color='red'><b>"+data.message+"</b></font>");
	                     }
	                    $("#ar_btn").prop("disabled" , false);
	                    
	           }
                 });
      }
    }
   
   
   get_all_user_roles();
  </script>
