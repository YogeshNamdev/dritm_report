<!DOCTYPE html>
<html>
<head>

<title>Check Admission Status</title>

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style>

body{
background:#f4f6f9;
}

.card{
border-radius:12px;
box-shadow:0 3px 12px rgba(0,0,0,0.08);
}

.status-approved{color:green;font-weight:bold;}
.status-pending{color:orange;font-weight:bold;}
.status-rejected{color:red;font-weight:bold;}

</style>

</head>
<body>


<div class="container mt-5">


<div class="card p-4">

<h3 class="text-center mb-4">Check Admission Status</h3>

<div class="row">

<div class="col-md-5">

<input class="form-control" type="text"
id="mobile"
placeholder="Enter Mobile" pattern="[0-9]{10}">

</div>

<div class="col-md-5">

<input type="date"
class="form-control"
id="dob">

</div>

<div class="col-md-2">

<button class="btn btn-primary btn-block"
onclick="checkStatus()">

Check

</button>

</div>

</div>

</div>


<div id="resultBox" class="mt-4"></div>


</div>


<script>


function checkStatus(){

var mobile=$("#mobile").val().trim();
var dob=$("#dob").val();

if(mobile=="" || mobile.length!=10){
alert("Enter valid mobile");
return;
}

if(dob==""){
alert("Select DOB");
return;
}


$.post("<?=base_url()?>app/RegistrationStatus/check_status",
{
mobile:mobile,
dob:dob
},
function(res){

if(!res.status){

$("#resultBox").html(
"<div class='alert alert-danger'>Student not found</div>"
);
return;

}


var s=res.data.student;
var docs=res.data.docs;



var html="";


/* ---------- PROFILE CARD ---------- */

html+=`
<div class="card p-3 mb-3">
<h4>${s.name}</h4>
Mobile : ${s.mobile}<br>
Admission No : ${s.admission_no??'Not Generated'}
</div>
`;


/* ---------- DOCUMENT TABLE ---------- */

html+=`
<div class="card p-3">
<h5>Documents</h5>

<table class="table table-bordered">

<tr>
<th>Document</th>
<th>Status</th>
<th>Action</th>
</tr>
`;


var allApproved=true;

docs.forEach(function(d){

html+="<tr>";

html+="<td>"+d.document_type+"</td>";


if(d.status=="approved"){

html+="<td class='status-approved'>APPROVED</td>";
html+="<td>Locked</td>";

}

else if(d.status=="pending"){

allApproved=false;

html+="<td class='status-pending'>PENDING</td>";
html+="<td>Waiting Admin</td>";

}

else{

allApproved=false;

html+="<td class='status-rejected'>REJECTED<br>Reason : "+d.reject_reason+"</td>";

html+=`
<td>
<input type="file"
onchange="reupload(${d.id},this)">
</td>
`;

}

html+="</tr>";

});


html+="</table>";
html+="</div>";



/* ---------- LOGIN MESSAGE ---------- */

if(allApproved && s.admission_no != null){

html+=`

<div class="alert alert-success mt-3">

🎉 All documents approved  

Your admission is confirmed.

Login using Mobile + DOB.

</div>

`;

}

$("#resultBox").html(html);


},'json');

}



/* ---------- REUPLOAD ---------- */

function reupload(doc_id,input){

var fd=new FormData();

fd.append("doc_id",doc_id);
fd.append("file",input.files[0]);

$.ajax({

url:"<?=base_url()?>app/RegistrationStatus/reupload",
type:"POST",
data:fd,
contentType:false,
processData:false,

success:function(res){
    var r = JSON.parse(res);
    
    alert(r.msg);


    checkStatus();

}

});

}


</script>

</body>
</html>