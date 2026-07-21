  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-12">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>app/home">Home</a></li>
              <li class="breadcrumb-item active">Edit Profile</li>
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
              <h5 class="m-0">Edit Profile</h5>
            </h3>
          </div>
          <div class="card-body">
            <div class="form-group">
              <label for="user_name">Name</label>
              <input type="text" class="form-control" id="user_name" placeholder="Enter your full name." value="<?php echo $_SESSION["userdata"]["user_name"]; ?>" />
            </div>
            <div class="form-group">
              <label for="user_email">Email-ID</label>
              <input type="email" class="form-control" id="user_email" placeholder="Enter your valid email-id." value="<?php echo $_SESSION["userdata"]["user_email"]; ?>" />
            </div>
            <div class="form-group">
              <label for="user_mobile">Mobile No.</label>
              <input type="number" class="form-control" id="user_mobile" placeholder="Enter your valid 10 digit mobile no." value="<?php echo $_SESSION["userdata"]["user_mobile"]; ?>" />
            </div>
            <div class="form-group">
              <label for="user_address">Address</label>
              <textarea class="form-control" id="user_address" placeholder="Enter your complete address." rows="5"><?php echo $_SESSION["userdata"]["user_address"]; ?></textarea>
            </div>
          </div>
          <div class="card-footer">
            <div class="row">
              <div class="col-sm-2">
                <button type="button" class="btn btn-primary" id="ep_btn" onclick="edit_profile();">Submit</button>
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
   function edit_profile()
    {
     var user_name = $("#user_name").val();
     var user_email = $("#user_email").val();
     var user_mobile = $("#user_mobile").val();
     var user_address = $("#user_address").val();
     var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
     if(user_name.replace(/ /gi , "") == "")
      {
       $("#user_name").focus();
       $("#err_msg").html("<font color='red'><b>Enter your full name.</b></font>");
      }
     else if(user_email.replace(/ /gi , "") == "" || re.test(user_email) == false)
      {
       $("#user_email").focus();
       $("#err_msg").html("<font color='red'><b>Enter your valid email-id.</b></font>");
      }
     else if(user_mobile.replace(/ /gi , "") == "" || user_mobile.length != 10)
      {
       $("#user_mobile").focus();
       $("#err_msg").html("<font color='red'><b>Enter your valid 10 digit mobile no.</b></font>");
      }
     else if(user_address.replace(/ /gi , "") == "")
      {
       $("#user_address").focus();
       $("#err_msg").html("<font color='red'><b>Enter your complete address.</b></font>");
      }
     else
      {
       $("#ep_btn").prop("disabled" , true);
       $("#err_msg").html("<font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Wait, Validating...</b></font>");
       var base_url = '<?php echo base_url(); ?>';
           $.ajax({
                   type: "POST",
                   dataType: "JSON",
                   url: base_url+"app/users/validate_edit_profile",
                   data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' , 'user_id':'<?php echo $_SESSION["userdata"]["user_id"]; ?>' , 'user_name':user_name , 'user_email':user_email , 'user_mobile':user_mobile , 'user_address':user_address },
                   cache: false,
                   success: function (data, textStatus, jqXHR) {
	                    
	                    if(data.response == true)
	                     {
	                      $("#err_msg").html("<font color='green'><b>"+data.message+"</b></font>");
	                     }
	                    else
	                     {
	                      $("#err_msg").html("<font color='red'><b>"+data.message+"</b></font>");
	                     }
	                    $("#ep_btn").prop("disabled" , false);
	                    
	           }
                 });
      }
    }
  </script>
