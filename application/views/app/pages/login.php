<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo APP_TITLE." | ".$title; ?></title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>includes/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>includes/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>includes/dist/css/adminlte.min.css">
  <!-- jQuery -->
  <script src="<?php echo base_url(); ?>includes/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="<?php echo base_url(); ?>includes/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="<?php echo base_url(); ?>" class="h1"><img width="150px" src="<?php echo base_url(); ?>includes/dist/img/logo.png" /></a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Sign in to continue your session</p>

        <div class="input-group mb-3">
          <select class="form-control" id="role_id">
            <option value="0">-- Select Role --</option>
            <?php
             if(isset($role_list) && $role_list != FALSE)
              {
               foreach($role_list as $role)
                {
                 echo "<option value='".$role->role_id."'>".$role->role_name."</option>";
                }
              }
            ?>
          </select>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="text" class="form-control" id="user_msd" placeholder="MSD-ID">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" id="user_password" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember">
              <label for="remember">
                 Me
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="button" class="btn btn-primary btn-block" id="login_btn" onclick="validate_login();">Sign In</button>
          </div>
          <!-- /.col -->
        </div>

        <p class="mb-1 text-center" id="err_msg">
         &nbsp;
        </p>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->

<script>
 function validate_login()
  {
   var role_id = $("#role_id").val();
   var user_msd = $("#user_msd").val();
   var user_password = $("#user_password").val();
   if(role_id == 0 || role_id.replace(/ /gi , "") == "")
    {
     $("#role_id").focus();
     $("#err_msg").html("<font color='red'><b>Select Role First.</b></font>");
    }
   else if(user_msd.replace(/ /gi , "") == "")
    {
     $("#user_msd").focus();
     $("#err_msg").html("<font color='red'><b>Enter your valid MSD-ID.</b></font>");
    }
   else if(user_password.replace(/ /gi , "") == "")
    {
     $("#user_password").focus();
     $("#err_msg").html("<font color='red'><b>Enter your Password.</b></font>");
    }
   else
    {
     $("#login_btn").prop("disabled" , true);
     $("#err_msg").html("<font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Wait, Validating...</b></font>");
     var base_url = '<?php echo base_url(); ?>';
         $.ajax({
                 type: "POST",
                 dataType: "JSON",
                 url: base_url+"app/login/validate_user_login",
                 data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' , 'role_id':role_id , 'user_msd':user_msd , 'user_password':user_password },
                 cache: false,
                 success: function (data, textStatus, jqXHR) {
	                  
	                  if(data.response == true)
	                   {
	                    $("#err_msg").html("<font color='green'><b>"+data.message+"</b></font>");
	                    window.location = base_url+"app/Reports/NoOrSameResolutionDetails";
	                   }
	                  else
	                   {
	                    $("#login_btn").prop("disabled" , false);
	                    $("#err_msg").html("<font color='red'><b>"+data.message+"</b></font>");
	                   }
	                  
	         }
               });
    }
  }
</script>

<script src="<?php echo base_url(); ?>includes/dist/js/adminlte.min.js"></script>
</body>
</html>
