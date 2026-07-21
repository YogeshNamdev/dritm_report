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
              <li class="breadcrumb-item active">Monthy Audit</li>
              
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
            <div class='row'>
              <div class='col-md-2'>
            <h3 class="card-title">
              <h5 class="m-0"><b>Monthy Audit</b></h5>
            </h3>
            </div>
            <div class='col-md-3'>
              <h2>Total Audits :  <span class="blink" style="color: blue;"><?php echo $total_audit ;?></span><h2>
            </div>
            <div class='col-md-4'>
              <h2>Total Fatal Count : <span class="blink" style="color: green;"><?php echo $fatal_count ;?></span><h2>
            </div>
            <div class='col-md-3'>
              <h2>Fatal Left : <span class="blink" style="color: red;"><?php echo $left ;?></span><h2>
            </div>
            </div>
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
  
  
  
  <script>
  
    function get_all_feedbacks()
    {
     $("#user_list_div").html("<center><font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Wait, Loading...</b></font></center>");
     var base_url = '<?php echo base_url(); ?>';
         $.ajax({
                 type: "POST",
                 dataType: "JSON",
                 url: base_url+"app/users/get_monthly_audit",
                 data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' },
                 cache: false,
                 success: function (data, textStatus, jqXHR) {
	                  
	                  if(data.response == true)
	                   {
	                    var txt = "<table class='table table-bordered table-sm' style='font-size:12px;'>";
								txt += "<thead>";
								txt += "<tr style='background-color:#a4eda9'>";
								txt += "<th width='3%'>Sr.No.</th>";
								txt += "<th width='8%'>Call Date</th>";
								txt += "<th width='8%'>Audit date</th>";
								txt += "<th width='8%'>Ticket Id</th>";
								txt += "<th width='8%'>Mobile No</th>";
								txt += "<th width='12%'>Issue Type</th>";
								txt += "<th width='25%'>Summary</th>";
								txt += "<th width='12%'>AOI</th>";
								// txt += "<th width='25%'>Fatal Sub Reason</th>";
								txt += "<th width='25%'>Accuracy Scrore</th>";
								txt += "<th width='10%'>Call Accept</th>";

								txt += "<th width='10%'>Action</th>";
								txt += "<th width='10%'>Comment</th>";
								txt += "</tr>";
								txt += "</thead>";
								txt += "<tbody>";
	                      for(var i = 0; i < data.total_record; i++)
	                       {
var disabled = "";
if(data.all_record[i].accept_status == 0 || data.all_record[i].accept_status == "0"){
disabled = "disabled";
}
                            txt += "<tr>";
                            txt += "<td>"+parseInt(i+1)+"</td>";
                            if(data.all_record[i].no == "0" || data.all_record[i].no == 0){
                              txt += "<td><b>"+data.all_record[i].call_date+"</b></td>";
                            }else{ 
				txt += "<td style='color:blue'><b>"+data.all_record[i].call_date+"</b></td>";
			     }
                            //txt += "<td><b>"+data.all_record[i].call_date+"</b></td>";
                            txt += "<td><b>"+data.all_record[i].audit_date+"</b></td>";
							txt += "<td><b>"+data.all_record[i].ticket_id+"</b></td>";
							txt += "<td><b>"+data.all_record[i].caller_no+"</b></td>";
                            txt += "<td><b>"+data.all_record[i].issue_type+"</b></td>";
                            txt += "<td><b>"+data.all_record[i].summary+"</b></td>";
                            txt += "<td><b>"+data.all_record[i].fatal_reason+"</b></td>";
                           // txt += "<td><b>"+data.all_record[i].fatal_sub_reason+"</b></td>";
txt += "<td><b>"+data.all_record[i].accuracy_score+"</b></td>";
if(data.all_record[i].accept_status == 0 || data.all_record[i].accept_status == "0"){
txt += "<td align='center'>";
txt += "<input type='checkbox' id='call_accept"+data.all_record[i].audit_emp_id+"' name='call_accept"+data.all_record[i].audit_emp_id+"' value='1' onclick=update_call_acceptance("+data.all_record[i].audit_emp_id+",1,'"+data.all_record[i].msd_id+"');>";
txt += "</td>";
}else{
txt += "<td align='center'><b>I have listen the Call.</b></td>";
}


                            /*if(data.all_record[i].no == "0" || data.all_record[i].no == 0){
                              txt += "<td align='center'>";
                              txt += "</td>";
                            }else */if(data.all_record[i].action_value == "1" || data.all_record[i].action_value == 1){
                              txt += "<td align='center'><span style='color:green'><b>Accepted</b></span></td>";
                            }else if(data.all_record[i].action_value == "2" || data.all_record[i].action_value == 2){
                              txt += "<td align='center'><span style='color:red'><b>Rejected</b></span></td>";
                            }else{
                              txt += "<td align='center'>";
                                txt += "<button type='button' class='btn btn-success btn-xs' id='accept_"+data.all_record[i].audit_emp_id+"' name='accept_"+data.all_record[i].audit_emp_id+"' onclick=update_action("+data.all_record[i].audit_emp_id+",1,'"+data.all_record[i].msd_id+"'); value='1' "+disabled+" >Accept</button>";
                                txt += "&nbsp;";
                                txt += "<button type='button' class='btn btn-danger btn-xs' id='reject_"+data.all_record[i].audit_emp_id+"' name='reject_"+data.all_record[i].audit_emp_id+"' onclick=update_action("+data.all_record[i].audit_emp_id+",2,'"+data.all_record[i].msd_id+"'); value='2' "+disabled+">Reject</button>";
                              txt += "</td>";
                            }
                            /*if(data.all_record[i].no == "0" || data.all_record[i].no == 0){
                              txt += "<td align='center'>";
                              txt += "</td>";
                            }else */if(data.all_record[i].action_value != "0"){
                              txt += "<td align='center'>"+data.all_record[i].comment+"</td>";
                            }else{
                              txt += "<td align='center'>";
                                txt += "<textarea id='comment"+data.all_record[i].audit_emp_id+"' name='comment"+data.all_record[i].audit_emp_id+"' rows='2' cols='10' "+disabled+"></textarea>";
                              txt += "</td>";
                            }
                            
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

   function update_call_acceptance(audit_emp_id,action,msd_id)
    {
           var base_url = '<?php echo base_url(); ?>';
           $.ajax({
                   type: "POST",
                   dataType: "JSON",
                   url: base_url+"app/users/update_call_acceptance",
                   data: { 'audit_emp_id':audit_emp_id , 'action':action , 'msd_id':msd_id},
                   cache: false,
                   success: function (data, textStatus, jqXHR) {
	                    
	                    if(data.response == true)
	                     {
                        	location.reload(true);
	                      get_all_feedbacks();
	                     }
	                    else
	                     {
	                      alert(data.message);
	                     }
	                    //$("#eau_btn").prop("disabled" , false);
	                    
	           }
                 });
      
    }

   function update_action(audit_emp_id,action,msd_id)
    {
     var comment  = $("#comment"+audit_emp_id).val();
       $("#accept_"+audit_emp_id).prop("disabled" , true);
       $("#reject_"+audit_emp_id).prop("disabled" , true);
       var base_url = '<?php echo base_url(); ?>';
           $.ajax({
                   type: "POST",
                   dataType: "JSON",
                   url: base_url+"app/users/update_action",
                   data: { 'audit_emp_id':audit_emp_id , 'action':action , 'msd_id':msd_id , 'comment':comment},
                   cache: false,
                   success: function (data, textStatus, jqXHR) {
	                    
	                    if(data.response == true)
	                     {
                        location.reload(true);
	                      get_all_feedbacks();
	                     }
	                    else
	                     {
	                      alert(data.message);
	                     }
	                    $("#eau_btn").prop("disabled" , false);
	                    
	           }
                 });
      
    }

      function blink_text() {
      $('.blink').fadeOut(500);
      $('.blink').fadeIn(500);
      }
      setInterval(blink_text, 1000);
    get_all_feedbacks();
  </script>
