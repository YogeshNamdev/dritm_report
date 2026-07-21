<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo APP_TITLE." | ".$title; ?></title>

<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<link rel="stylesheet" href="<?php echo base_url(); ?>includes/plugins/fontawesome-free/css/all.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>includes/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>includes/dist/css/adminlte.min.css">

<script src="<?php echo base_url(); ?>includes/plugins/jquery/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>includes/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<style>
body.login-page{
    background: linear-gradient(135deg,#eef4ff 0%,#f8fbff 40%,#eef8f2 100%);
    min-height:100vh;
    font-family:'Source Sans Pro',sans-serif;
}

/* Wrapper */
.auth-wrap{
    width:100%;
    max-width:430px;
    margin:auto;
}

/* Card */
.auth-card{
    background:#fff;
    border:1px solid #e8eaf0;
    border-radius:18px;
    box-shadow:0 18px 60px rgba(17,24,39,.08);
    overflow:hidden;
}

/* Header */
.auth-head{
    padding:28px 28px 18px;
    text-align:center;
    border-bottom:1px solid #f1f3f8;
}
.auth-logo{
    width:140px;
    margin-bottom:12px;
}
.auth-title{
    font-size:22px;
    font-weight:700;
    color:#1a1a2e;
    margin-bottom:4px;
}
.auth-sub{
    font-size:14px;
    color:#8a8fa3;
    margin:0;
}

/* Body */
.auth-body{
    padding:26px 28px 28px;
}

/* Fields */
.db-field{
    margin-bottom:16px;
}
.db-label{
    display:block;
    font-size:12px;
    font-weight:700;
    color:#6b7280;
    margin-bottom:6px;
    text-transform:uppercase;
    letter-spacing:.4px;
}
.input-wrap{
    position:relative;
}
.db-input{
    width:100%;
    height:46px;
    border:1px solid #dde3ec;
    border-radius:10px;
    padding:0 42px 0 14px;
    font-size:14px;
    color:#111827;
    outline:none;
    background:#fff;
    transition:.2s;
}
.db-input:focus{
    border-color:#1a5fa5;
    box-shadow:0 0 0 3px rgba(26,95,165,.08);
}
.input-icon{
    position:absolute;
    right:14px;
    top:50%;
    transform:translateY(-50%);
    color:#9aa3b2;
    font-size:14px;
}

/* Buttons */
.db-btn{
    width:100%;
    height:46px;
    border:none;
    border-radius:10px;
    background:#1a5fa5;
    color:#fff;
    font-size:15px;
    font-weight:700;
    transition:.2s;
}
.db-btn:hover{
    background:#154d86;
}
.db-btn:disabled{
    opacity:.7;
    cursor:not-allowed;
}

/* Footer links */
.auth-links{
    margin-top:18px;
    text-align:center;
    font-size:14px;
    line-height:1.9;
}
.auth-links a{
    color:#1a5fa5;
    font-weight:600;
}
.auth-links a:hover{
    text-decoration:none;
}

/* Remember */
.remember-row{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:10px;
    margin-bottom:14px;
}

/* Error */
#err_msg{
    min-height:22px;
    text-align:center;
    margin-top:10px;
    font-size:14px;
}

.logo-wrap{
    display:flex;
    align-items:center;
    justify-content:center;
    gap:20px;
    margin-bottom:10px;
    flex-wrap:wrap;
}

.auth-logo{
    width:135px;
    height:135px;
    object-fit:contain;
    background:#fff;
    padding:5px;
    border-radius:10px;
}

/* Mobile */
@media(max-width:576px){
    .auth-wrap{max-width:100%;padding:14px;}
    .auth-head,.auth-body{padding:20px;}
}
</style>
</head>

<body class="hold-transition login-page">

<div class="auth-wrap">
    <div class="auth-card">

        <!-- Header -->
        <div class="auth-head">
           <a href="<?php echo base_url(); ?>" class="logo-wrap">

    <img src="<?php echo base_url(); ?>includes/dist/img/drit-logo.gif"
         class="auth-logo">

    <img src="<?php echo base_url(); ?>includes/dist/img/logo1.png"
         class="auth-logo">

</a>
            <div class="auth-title">Welcome Back</div>
            <p class="auth-sub">Sign in to continue your session</p>
        </div>

        <!-- Body -->
        <div class="auth-body">

            <!-- Role -->
            <div class="db-field">
                <label class="db-label">Login Role</label>
                <div class="input-wrap">
                    <select class="db-input" id="role_id" name="role_id" onchange="div_change(this.value)">
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
                    <span class="input-icon"><i class="fas fa-user"></i></span>
                </div>
            </div>

            <div id="admin_box">

                <div class="db-field">
                    <label class="db-label">Dritm-ID</label>
                    <div class="input-wrap">
                        <input type="text" class="db-input" id="user_msd" placeholder="Enter Dritm-ID">
                        <span class="input-icon"><i class="fas fa-envelope"></i></span>
                    </div>
                </div>

                <div class="db-field">
                    <label class="db-label">Password</label>
                    <div class="input-wrap">
                        <input type="password" class="db-input" id="user_password" placeholder="Enter Password">
                        <span class="input-icon"><i class="fas fa-lock"></i></span>
                    </div>
                </div>

            </div>

            <!-- Remember -->
            <div class="remember-row">
                <div class="icheck-primary m-0">
                    <input type="checkbox" id="remember">
                    <label for="remember">Remember Me</label>
                </div>
            </div>

            <!-- Button -->
            <button type="button" class="db-btn" id="login_btn" onclick="validate_login();">
                Sign In
            </button>

            <!-- Message -->
            <div id="err_msg">&nbsp;</div>

        </div>
    </div>
</div>

<script>
function div_change(role_id){
    $("#admin_box").show();
}

function validate_login()
{
    var role_id = $("#role_id").val();
    var user_msd = $("#user_msd").val().trim();
    var user_password = $("#user_password").val().trim();

    if(role_id === "0")
    {
        $("#role_id").focus();
        $("#err_msg").html("<span style='color:red;font-weight:bold'>Select Role First.</span>");
        return false;
    }

    if(user_msd === "")
    {
        $("#user_msd").focus();
        $("#err_msg").html("<span style='color:red;font-weight:bold'>Enter your MSD-ID.</span>");
        return false;
    }

    if(user_password === "")
    {
        $("#user_password").focus();
        $("#err_msg").html("<span style='color:red;font-weight:bold'>Enter your Password.</span>");
        return false;
    }

    $("#login_btn").prop("disabled", true);

    $("#err_msg").html(
        "<span style='color:blue;font-weight:bold'>" +
        "<i class='fa fa-spinner fa-spin'></i> Wait, Validating..." +
        "</span>"
    );

    var base_url = "<?php echo base_url(); ?>";

    $.ajax({
        type: "POST",
        url: base_url + "app/login/validate_user_login",
        dataType: "json",

        data: {
            '<?php echo $this->security->get_csrf_token_name(); ?>':
            '<?php echo $this->security->get_csrf_hash(); ?>',

            role_id: role_id,
            user_msd: user_msd,
            user_password: user_password
        },

        success: function(data)
        {
            if(data.response == true)
            {
                $("#err_msg").html(
                    "<span style='color:green;font-weight:bold'>" +
                    data.message +
                    "</span>"
                );

                // Redirect according to role_id returned from server
                if(data.role_id == '2' || data.role_id == '3')
                {
                    window.location.href =
                    base_url + "app/Reports/NoOrSameResolutionDetails";
                }else if(data.role_id == '4'){
                    window.location.href =
                    base_url + "app/Reports/OwaForm";
                }else if(data.role_id == '5'){
                    window.location.href =
                    base_url + "app/Reports/DisasterReport";
                }
                else
                {
                    window.location.href =
                    base_url + "app/users/roles";
                }
            }
            else
            {
                $("#login_btn").prop("disabled", false);

                $("#err_msg").html(
                    "<span style='color:red;font-weight:bold'>" +
                    data.message +
                    "</span>"
                );
            }
        },

        error: function(xhr, status, error)
        {
            $("#login_btn").prop("disabled", false);

            $("#err_msg").html(
                "<span style='color:red;font-weight:bold'>" +
                "Something went wrong. Please try again." +
                "</span>"
            );

            console.log(error);
        }
    });
}
</script>

<script src="<?php echo base_url(); ?>includes/dist/js/adminlte.min.js"></script>
</body>
</html>

