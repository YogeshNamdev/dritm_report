  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-12">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>app/home">Home</a></li>
              <li class="breadcrumb-item active">Change Password</li>
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
              <h5 class="m-0">Change Password</h5>
            </h3>
          </div>
          <div class="card-body">
            <div class="form-group">
              <label for="old_password">Old Password</label>
              <input type="password" class="form-control" id="old_password" placeholder="Enter your old password." />
            </div>
            <div class="form-group">
              <label for="new_password">New Password</label>
              <input type="password" class="form-control" id="new_password" placeholder="Enter your new password." />
            </div>
            <div class="form-group">
              <label for="renew_password">Re-type New Password</label>
              <input type="password" class="form-control" id="renew_password" placeholder="Enter again your new password." />
            </div>
          </div>
          <div class="card-footer">
            <div class="row">
              <div class="col-sm-2">
                <button type="button" class="btn btn-primary" id="cp_btn" onclick="change_password();">Submit</button>
              </div>
              <div class="col-sm-10 text-right" id="err_msg">
                &nbsp;
              </div>
            </div>
          </div>
        </div>
        
      </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
  <script>
   function change_password()
    {
     var old_password = $("#old_password").val();
     var new_password = $("#new_password").val();
     var renew_password = $("#renew_password").val();
     if(old_password.replace(/ /gi , "") == "")
      {
       $("#old_password").focus();
       $("#err_msg").html("<font color='red'><b>Enter your old password.</b></font>");
      }
     else if(new_password.replace(/ /gi , "") == "")
      {
       $("#new_password").focus();
       $("#err_msg").html("<font color='red'><b>Enter your new password.</b></font>");
      }
     else if(renew_password.replace(/ /gi , "") == "")
      {
       $("#renew_password").focus();
       $("#err_msg").html("<font color='red'><b>Enter again your new password.</b></font>");
      }
     else if(new_password != renew_password)
      {
       $("#renew_password").focus();
       $("#err_msg").html("<font color='red'><b>Password not matched.</b></font>");
      }
     else if(old_password == renew_password)
      {
       $("#new_password").focus();
       $("#err_msg").html("<font color='red'><b>Old Password and new password should not be same.</b></font>");
      }
     else
      {
       $("#cp_btn").prop("disabled" , true);
       $("#err_msg").html("<font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Wait, Validating...</b></font>");
       var base_url = '<?php echo base_url(); ?>';
           $.ajax({
                   type: "POST",
                   dataType: "JSON",
                   url: base_url+"app/users/validate_change_password",
                   data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' , 'user_id':'<?php echo $_SESSION["userdata"]["user_id"]; ?>' , 'old_password':old_password , 'new_password':new_password , 'renew_password':renew_password },
                   cache: false,
                   success: function (data, textStatus, jqXHR) {
	                    
	                    if(data.response == true)
	                     {
	                      $("#err_msg").html("<font color='green'><b>"+data.message+"</b></font>");
	                      window.location = base_url;
	                     }
	                    else
	                     {
	                      $("#cp_btn").prop("disabled" , false);
	                      $("#err_msg").html("<font color='red'><b>"+data.message+"</b></font>");
	                     }
	                    
	           }
                 });
      }
    }
  </script>
  
