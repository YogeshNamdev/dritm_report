<!DOCTYPE html>

<html>
<head>

<title>Student Registration</title>

<meta name="viewport" content="width=device-width, initial-scale=1">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style>

/* ===== BODY ===== */

body{
background:#f4f6f9;
font-family:Arial;
}

/* ===== CONTAINER ===== */

.main-box{
width:900px;
margin:40px auto;
background:white;
padding:30px;
border-radius:10px;
box-shadow:0 0 20px rgba(0,0,0,0.08);
}

/* ===== HEADINGS ===== */

h3{
margin-bottom:20px;
}

/* ===== GRID ===== */

.row{
display:flex;
gap:25px;
}

.col{
flex:1;
}

/* ===== INPUT ===== */

label{
font-weight:bold;
display:block;
margin-top:12px;
margin-bottom:4px;
}

input, textarea{
width:100%;
padding:10px;
border:1px solid #ccc;
border-radius:6px;
}

textarea{height:70px}

/* ===== DOC BOX ===== */

.doc-box{
background:#f9f9f9;
padding:15px;
border-radius:8px;
margin-top:10px;
}

/* ===== BUTTON ===== */

button{
background:#28a745;
color:white;
border:none;
padding:12px 30px;
border-radius:6px;
cursor:pointer;
font-size:16px;
margin-top:20px;
}

button:hover{
background:#218838;
}

/* ===== CHECKBOX ===== */

.doc-item{
display:inline-block;
margin-right:18px;
margin-top:6px;
}

/* ===== FILE INPUT BLOCK ===== */

.file-input{
margin-top:12px;
}

</style>

</head>

<body>

    <div class="main-box">

        <h3>Student Registration</h3>

        <form id="students_details" method="post"  enctype="multipart/form-data">

            <div class="row">

                <div class="col">

                    <label>Student Name</label> <input type="text" name="name" id="name" required>

                    <label>Father Name</label> <input type="text" name="father_name" id="father_name" required>

                    <label>DOB</label> <input type="date" name="dob" id="dob"  required>

                    <label>Email</label> <input type="email" name="email" id="email" >

                </div>

                <div class="col">

                    <label>Mobile</label> <input type="text" name="mobile" id="mobile"  required>

                    <label>Address</label>

                    <textarea name="address" name="address" id="address"></textarea>

                    <label>Photo</label> <input type="file" name="photo" id="photo"  required>

                </div>

            </div>

            <hr>

            <h4>Select Documents</h4>

                <div id="docCheckboxes" class="doc-box"></div>

            <hr>

            <h4>Upload Selected Documents</h4>

            <div id="docInputs"></div>

            <button type="button" id="au_btn" onclick="saveStudent()">Register Student</button>
            <div id="err_msg"></div>

        </form>

    </div>

<script>

/* ===== PREDEFINED DOCUMENTS ===== */

var docList=[
"Aadhar Card",
"10th Marksheet",
"12th Marksheet",
"PAN Card",
"Transfer Certificate",
"Migration Certificate"
];


/* ===== SHOW CHECKBOX ===== */

docList.forEach(function(d){

$("#docCheckboxes").append(

'<label class="doc-item">'+
'<input type="checkbox" class="docCheck" value="'+d+'"> '+d+
'</label>'

);

});


/* ===== WHEN CHECK SHOW FILE ===== */

$(document).on("change",".docCheck",function(){

var html="";

$(".docCheck:checked").each(function(){

var name=$(this).val();

html+=`
<div class="file-input">
<label>${name}</label>
<input type="file" name="docs[]">
<input type="hidden" name="doc_names[]" value="${name}">
</div>
`;

});

$("#docInputs").html(html);

});


/* ===== SAVE AJAX ===== */

// function saveStudent()
// {
//     var formData = new FormData(
//         document.getElementById("students_details")
//     );
// var base_url = '<?php echo base_url(); ?>';
//     $.ajax({

//         url:"<?=base_url()?>studentRegistration/save_full",

//         type:"POST",

//         data:formData,

//         contentType:false,
//         processData:false,

//         dataType:"json",   // VERY IMPORTANT

//         success:function(res)
//         {
//             if(res.status==true)
//             {
//                 alert(res.msg);

//                 $("#students_details")[0].reset();
//             }
//             else
//             {
//                 alert(res.msg);
//             }
//         },

//         error:function(xhr)
//         {
//             console.log(xhr.responseText);   // ⭐ THIS SHOWS REAL ERROR
//             alert("SERVER ERROR → check console");
//         }

//     });
// }


function saveStudent()
{
    var name = $("#name").val();
    var father_name = $("#father_name").val();
    var dob = $("#dob").val();
    var email = $("#email").val();
    var mobile = $("#mobile").val();
    var address = $("#address").val();
    var photo = $("#photo").val();
    var today = new Date();
    var birthDate = new Date(dob);
    email_pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    var age = today.getFullYear() - birthDate.getFullYear();
    var m = today.getMonth() - birthDate.getMonth();

    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
    age--;
    }
    if(name.replace(/ /gi , "") == "")
    {
        $("#name").focus();
        $("#err_msg").html("<font color='red'><b>Enter name..</b></font>");
    }else if(father_name.replace(/ /gi , "") == "")
    {
        $("#father_name").focus();
        $("#err_msg").html("<font color='red'><b>Enter father name..</b></font>");
    }else if(dob.replace(/ /gi , "") == "")
    {
        $("#dob").focus();
        $("#err_msg").html("<font color='red'><b>Select Date of Birth..</b></font>");
    }else if(age < 18)
    {
        $("#dob").focus();
        $("#err_msg").html("<font color='red'><b>Age must be 18 years or above.</b></font>");
    }else if(email.replace(/ /gi , "") == "")
    {
        $("#email").focus();
        $("#err_msg").html("<font color='red'><b>Enter Email..</b></font>");
    }else if(!email_pattern.test(email))
    {
        $("#email").focus();
        $("#err_msg").html("<font color='red'><b>Enter valid Email address...</b></font>");
    }else if(mobile.replace(/ /gi , "") == "")
    {
        $("#mobile").focus();
        $("#err_msg").html("<font color='red'><b>Enter Number..</b></font>");
    }else if(mobile.length != 10){
        $("#mobile").focus();
        $("#err_msg").html("<font color='red'><b>Enter Valid Mobile number..</b></font>");
    }else if(address.replace(/ /gi , "") == "")
    {
        $("#address").focus();
        $("#err_msg").html("<font color='red'><b>Enter Address..</b></font>");
    }else if(photo.replace(/ /gi , "") == "")
    {
        $("#photo").focus();
        $("#err_msg").html("<font color='red'><b>Select File..</b></font>");
    }
    else
      {
        $("#au_btn").prop("disabled" , true);
        $("#err_msg").html("<font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Wait, Validating...</b></font>");
        var base_url = '<?php echo base_url(); ?>';

        let myForm = document.getElementById('students_details');
        let formData = new FormData(myForm);

        $.ajax({
            type: "POST",
            url: base_url+"app/StudentRegistration/save_full",
            data: formData,
            dataType: "json",
            processData: false,
            contentType: false,
            cache:false,

            success:function(data){

                if(data.status == true){
                    $("#err_msg").html("<span style='color:green;font-weight:bold'>Saved Successfully</span>");
                    $("#students_details")[0].reset();
                }else{
                    $("#err_msg").html("<span style='color:red;font-weight:bold'>"+data.msg+"</span>");
                }

                $("#au_btn").prop("disabled", false);
            },

            error:function(){
                $("#err_msg").html("<span style='color:red'>Server error</span>");
                $("#au_btn").prop("disabled", false);
            }

        });
      }
    }

</script>

</body>
</html>
