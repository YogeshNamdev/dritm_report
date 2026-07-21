<link href="<?php echo base_url(); ?>includes/plugins/advance_datatable/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>includes/plugins/advance_datatable/buttons.dataTables.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/advance_datatable/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/advance_datatable/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/advance_datatable/buttons.print.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/advance_datatable/buttons.colVis.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/advance_datatable/pdfmake.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/advance_datatable/vfs_fonts.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/advance_datatable/buttons.html5.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/advance_datatable/jszip.min.js"></script>
<script src="<?= base_url(); ?>includes/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>

<script>
function makeDataTable_Basic(tableID)
 {
  var tableMDT = $('#'+tableID).DataTable({
                   	 "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                     		// Bold the grade for all 'A' grade browsers
                     	 if ( aData[4] == "A" )
                     	  {
                       	   $('td:eq(4)', nRow).html( '<b>A</b>' );
                     	  }
                   	},
                        dom: 'Bfrtip',
                        buttons: [
                            'colvis',
                            { extend: 'print', exportOptions: { columns: ':visible' }  },
                            { extend: 'pdfHtml5', orientation: 'landscape', pageSize: 'LEGAL',  download: 'open' },
                            { extend: 'excelHtml5', customize: function( xlsx ) { var sheet = xlsx.xl.worksheets['sheet1.xml']; $('row c[r^="C"]', sheet).attr( 's', '2' ); }}
                          ]
                 });
 }
</script>
  <!-- Bootstrap Switch -->


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
                        <li class="breadcrumb-item active">Students Master</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <section class="content">
      <div class="container-fluid">
        
        <div class="card card-default color-palette-box">
          <div class="card-header">
    <h5 class="m-0 d-flex justify-content-between">
        <b>Student Dashboard</b>
        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#add_student_modal">Add Student</button>
    </h5>
</div>
          <div class="card-body">
            
            <div class="row">
             <div class="col-md-12" id="students_list_div">

            </div>
            </div>
            
          </div>
        </div>
        
      </div><!--/. container-fluid -->
    </section>
</div>

<div class="modal fade" id="add_student_modal">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Add Student</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">
            <form id="students_details" method="post" enctype= "multipart/form-data" >
                <div class="form-group">
                    <label>Student Name</label>
                    <input type="text" name="name" id="name"
                            class="form-control"
                            required>
                </div>
                <div class="form-group">
                    <label>Father Name</label>
                    <input type="text" name="father_name" id="father_name"
                            class="form-control"
                            required>
                </div>
                <div class="form-group">
                    <label>DOB</label>
                    <input type="date" name="dob" id="dob"
                            class="form-control"
                            required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" id="email"
                            class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Mobile</label>
                    <input type="number" name="mobile" id="mobile"
                            class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <textarea name="address" id="address" class="form-control" required></textarea>
                </div>
                <div class="form-group">
                    <label>photo</label>
                    <input type="file" name="photo" id="photo" class="form-control" required>
                </div>
               
              <div class="form-group" id="err_msg"></div>
            </form>
              
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" id="au_btn" onclick="add_student();">Submit</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
  </div>
  <div class="modal fade" id="docsModal">
        <div class="modal-dialog">
            <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">Upload Documents</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">

            <input type="hidden" id="student_id_for_docs">

            <label>Select Required Documents</label>

            <div id="docChecklist">

            <label><input type="checkbox" value="Aadhaar"> Aadhaar Card</label><br>

            <label><input type="checkbox" value="10th Marksheet"> 10th Marksheet</label><br>

            <label><input type="checkbox" value="12th Marksheet"> 12th Marksheet</label><br>

            <label><input type="checkbox" value="PAN Card"> PAN Card</label><br>

            <label><input type="checkbox" value="Photo"> Photo</label>

            </div>

            <hr>

            <div id="selectedDocInputs"></div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-primary" onclick="uploadStudentDocs()">Upload</button>
                <button class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="viewDocsModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h4>Student Documents</h4>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Document</th>
                                <th>Preview</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody id="studentDocsList"></tbody>

                    </table>

                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="imgPreviewModal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">

                <div class="modal-header">
                <h5>Document Preview</h5>
                <button class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body text-center">

                <img id="previewBigImg" style="max-width:100%;max-height:80vh;">

                </div>

            </div>
        </div>
    </div>
  <script>

    $("#docChecklist input[type=checkbox]").change(function(){

    var html = "";

    $("#docChecklist input:checked").each(function(){

        var doc = $(this).val();

        html += `
        <div class="mb-2">
            <label>${doc}</label>
            <input type="file" class="form-control docfile" data-doc="${doc}">
        </div>
        `;
    });

    $("#selectedDocInputs").html(html);

});
    function add_student()
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
            url: base_url+"app/students/save",
            data: formData,
            dataType: "json",
            processData: false,
            contentType: false,
            cache:false,

            success:function(data){

                if(data.response == true){
                    $("#err_msg").html("<span style='color:green;font-weight:bold'>Saved Successfully</span>");
                    $("#students_details")[0].reset();
                    get_all_students();
                }else{
                    $("#err_msg").html("<span style='color:red;font-weight:bold'>"+data.message+"</span>");
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
    function openDocsModal(id)
{
   $("#student_id_for_docs").val(id);
   $("#docsModal").modal("show");
}



function uploadStudentDocs()
{
    var id = $("#student_id_for_docs").val();

    var formData = new FormData();
    formData.append("student_id", id);

    $(".docfile").each(function(){

        var file = $(this)[0].files[0];
        var docname = $(this).data("doc");

        if(file){
            formData.append("docs[]", file);
            formData.append("docnames[]", docname);
        }

    });

    $.ajax({
        url:"<?php echo base_url();?>app/students/upload_docs",
        type:"POST",
        data:formData,
        contentType:false,
        processData:false,
        dataType:"json",
        success:function(res){

            if(res.status){
                alert("Uploaded Successfully");
                $("#docsModal").modal("hide");
                get_all_students();
            }else{
                alert(res.msg);
            }

        }
    });
}
    function get_all_students()
    {
     $("#students_list_div").html("<center><font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Wait, Loading...</b></font></center>");
     var base_url = '<?php echo base_url(); ?>';
         $.ajax({
                 type: "POST",
                 dataType: "JSON",
                 url: base_url+"app/students/get_all_students",
                 data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' },
                 cache: false,
                 success: function (data, textStatus, jqXHR) {
	                  
	                  if(data.response == true)
	                   {
	                    var txt = "<table class='table table-bordered table-sm' style='font-size:15px;' id='tbl_students'>";
                            txt += "<thead>";
                            txt += "<tr>";
                            txt += "<th width='5%'>Sr.No.</th>";
                            txt += "<th width='10%'>Name</th>";
                            txt += "<th width='5%'>Father Name</th>";
                            txt += "<th width='5%'>Mobile</th>";
                            txt += "<th width='5%'>Dob</th>";
                            txt += "<th width='5%'>Email</th>";
                            txt += "<th width='5%'>Documents</th>";
                            txt += "<th width='5%'>Created At</th>";
                            txt += "<th width='5%'>Action</th>";
                            txt += "</tr>";
	                        txt += "</thead>";
	                        txt += "<tbody>";
	                      for(var i = 0; i < data.total_record; i++)
	                       {
                            var img_path = base_url+"uploads/students/"+data.all_record[i].photo;
                            txt += "<tr>";
                            txt += "<td>"+parseInt(i+1)+"</td>";
                            txt += "<td>"+data.all_record[i].name+"</td>";
                            txt += "<td>"+data.all_record[i].father_name+"</td>";
                            txt += "<td>"+data.all_record[i].mobile+"</td>";
                            txt += "<td>"+data.all_record[i].dob+"</td>";
                            txt += "<td>"+data.all_record[i].email+"</td>";
                            txt += "<td>";

                                if(data.all_record[i].photo != ''){
                                    txt += "<img src='"+img_path+"' style='height:40px;width:40px;border-radius:50%;margin-right:12px;'>";
                                }
                            
                            txt += "</td>";
                             txt += "<td>"+data.all_record[i].created_at+"</td>";
                            txt += "<td>";
                                txt += "<i class='fa fa-plus-square text-primary' style='cursor:pointer;font-size:18px;margin-right:12px;' onclick='openDocsModal("+data.all_record[i].id+")'></i>";

                                txt += "<i class='fa fa-upload text-primary' style='cursor:pointer;font-size:18px;margin-right:5px;' onclick='viewDocument("+data.all_record[i].id+")'></i>";
                            txt += "</td>";
                           
                           
	                        txt += "</tr>";
	                       }
	                     txt += "</tbody>";
	                    txt += "</table>";
	                    $("#students_list_div").html(txt);
	                    
	                    $("input[data-bootstrap-switch]").each(function(){
                                $(this).bootstrapSwitch();
                            });
	                   }
	                  else
	                   {
	                    $("#students_list_div").html("<center><font color='red'><b>"+data.message+"</b></font></center>");
	                   }
                       makeDataTable_Basic("tbl_students");
	                    
	         }
               });
    }
   
   function get_fee_types_details_by_id(fee_types_id)
    {
     $("#edit_fee_types_id").val(fee_types_id);
     $("#edit_fee_types_name").val("");
     var base_url = '<?php echo base_url(); ?>';
         $.ajax({
                 type: "POST",
                 dataType: "JSON",
                 url: base_url+"app/fee_types/get_fee_types_details_by_id",
                 data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' , 'fee_types_id':fee_types_id },
                 cache: false,
                 success: function (data, textStatus, jqXHR) {
                          
                          if(data.response == true)
                           {
                            $("#edit_fee_name").val(data.all_record[0].fee_name);
                           
                           }
                          
                 }
               });
    }
   
    function edit_fee_types_details()
    {
     var fee_name = $("#edit_fee_name").val();
     var fee_types_id = $("#edit_fee_types_id").val();
     if(fee_name.replace(/ /gi , "") == "")
      {
          $("#edit_fee_name").focus();
          $("#edit_err_msg").html("<font color='red'><b>Enter Fee name.</b></font>");
      }
     else
      {
       $("#eau_btn").prop("disabled" , true);
       $("#edit_err_msg").html("<font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Wait, Validating...</b></font>");
       var base_url = '<?php echo base_url(); ?>';
           $.ajax({
                   type: "POST",
                   dataType: "JSON",
                   url: base_url+"app/fee_types/edit_fee_types_details",
                   data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' ,'fee_types_id':fee_types_id ,
                    'fee_name':fee_name },
                   cache: false,
                   success: function (data, textStatus, jqXHR) {
	                    
	                    if(data.response == true)
	                     {
	                      $("#edit_err_msg").html("<font color='green'><b>"+data.message+"</b></font>");
	                      
	                      get_all_fee_types();
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
   
   function delete_fee_types(fee_types_id)
    {
     var conf = confirm("Are you sure to delete this user?");
     if(conf == true)
      {
       var base_url = '<?php echo base_url(); ?>';
           $.ajax({
                   type: "POST",
                   dataType: "JSON",
                   url: base_url+"app/fee_types/delete_fee_types",
                   data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' , 'fee_types_id':fee_types_id },
                   cache: false,
                   success: function (data, textStatus, jqXHR) {
                            
                            if(data.response == true)
                             {
                             get_all_fee_types();
                             }
                            else
                             {
                              alert(data.message);
                             }
                            
                   }
                 });
      }
    }
   
   
function viewDocument(student_id)
{
    $("#viewDocsModal").modal("show");

    $.get("<?=base_url()?>app/students/get_student_docs/"+student_id,function(res){

        var html="";

        res.forEach(function(d){

            var preview="";

            if(d.document_name.match(/\.(jpg|jpeg|png)$/i)){
                preview="<img src='<?=base_url()?>uploads/student_docs/"+d.document_name+"' height='60' style='cursor:pointer' onclick='openImage(this.src)'>";
            }
            else{
                preview="<button class='btn btn-info btn-sm' onclick='openPDF(\"<?=base_url()?>uploads/student_docs/"+d.document_name+"\")'>View PDF</button>";
            }

            html+=`
            <tr>
            <td>${d.document_type}</td>
            <td>${preview}</td>
            <td>${d.status}</td>
            <td>

            ${d.status=='approved' ? 'Approved' : `
            <button class="btn btn-success btn-sm" onclick="approveDoc(${d.id})">Approve</button>

            <button class="btn btn-danger btn-sm" onclick="rejectDoc(${d.id})">Reject</button>
            `}

            </td>
            </tr>
            `;
        });

        $("#studentDocsList").html(html);

    },'json');
}
   function openImage(src)
    {
        $("#previewBigImg").attr("src",src);
        $("#imgPreviewModal").modal("show");
    } 

function approveDoc(id)
{
    $.post("<?=base_url()?>app/students/approve_doc",{id:id},function(){
        alert("Approved");
        location.reload();
    });
}

function rejectDoc(id)
{
    var reason = prompt("Enter reject reason");

    if(reason==null || reason=="") return;

    $.post("<?=base_url()?>app/students/reject_doc",{id:id,reason:reason},function(){
        alert("Rejected");
        location.reload();
    });
}
   
   get_all_students();
   
  
  </script>
