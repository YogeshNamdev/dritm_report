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
              <li class="breadcrumb-item active">List of Adviser</li>
            </ol>
          </div>
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
&nbsp;
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        
        <div class="card card-default color-palette-box">
          <div class="card-header">
            <h3 class="card-title">
              <h5 class="m-0">List of Adviser</h5>
            </h3>
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
  
  <div class="modal fade" id="adviser_profile_modal">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Adviser Details</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
             <div id="list_of_adviser_details"></div>
            </div>
            <div class="modal-footer justify-content-between">
            
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
  </div>
      <!-- /.modal -->
  
  <script>
  
    function get_all_advisers()
    {
     $("#user_list_div").html("<center><font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Wait, Loading...</b></font></center>");
     var base_url = '<?php echo base_url(); ?>';
         $.ajax({
                 type: "POST",
                 dataType: "JSON",
                 url: base_url+"app/users/get_all_advisers",
                 data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' },
                 cache: false,
                 success: function (data, textStatus, jqXHR) {
	                  
	                  if(data.response == true)
	                   {
	                    var txt = "<table class='table table-bordered table-sm' style='font-size:12px;'>";
	                     txt += "<thead>";
	                      txt += "<tr style='background-color:#a4eda9'>";
	                       txt += "<th width='3%'>Sr.No.</th>";
	                       txt += "<th width='60%'>Adviser Name</th>";
	                       txt += "<th width='20%'>Adviser MSD-ID</th>";
                         txt += "<th width='10%'>Action</th>";
	                      txt += "</tr>";
	                     txt += "</thead>";
	                     txt += "<tbody>";
	                      for(var i = 0; i < data.total_record; i++)
	                       {
                          if(data.all_record[i].role_id == 2 || data.all_record[i].role_id == "2" ){
                            txt += "<tr>";
                            txt += "<td>"+parseInt(i+1)+"</td>";
                            if(data.all_record[i].total_audit  > 1  || data.all_record[i].left != 0 || data.all_record[i].fatal_count != 0){
                              //txt += "<td><b>"+data.all_record[i].user_name+"</span></b></td>";
			      txt += "<td><b>"+data.all_record[i].user_name+" &nbsp; &nbsp; &nbsp; &nbsp; <span style='color:red' class='blink'>fatal count : "+data.all_record[i].fatal_count+" out of "+data.all_record[i].total_audit+"</span>&nbsp; &nbsp; &nbsp; &nbsp; <span  style='color:blue' class='blink'> Left : "+data.all_record[i].left+" </b></td>";
                            }else{
                              txt += "<td><b>"+data.all_record[i].user_name+"</span></b></td>";
                            }
                            
                            txt += "<td><b>"+data.all_record[i].msd_id+"</b></td>";
                            txt += "<td><button type='button' class='btn btn-success btn-xs' id='accept_"+data.all_record[i].msd_id+"' name='accept_"+data.all_record[i].msd_id+"' onclick=get_adviser_data('"+data.all_record[i].msd_id+"');$('#adviser_profile_modal').modal('show');  >Details</button></td>";
                            txt += "</tr>";
                          }
                            
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
   
    function get_adviser_data(msd_id)
    {
     
     $("#list_of_adviser_details").html("<center><font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Wait, Loading...</b></font></center>");
     var base_url = '<?php echo base_url(); ?>';
         $.ajax({
                 type: "POST",
                 dataType: "JSON",
                 url: base_url+"app/users/get_adviser_data_by_msd_id",
                 data: {'msd_id':msd_id},
                 cache: false,
                 success: function (data, textStatus, jqXHR) {
                  if(data.response == true)
	                   {
	                    var txt = "<table class='table table-bordered table-sm' style='font-size:12px;'>";
	                     txt += "<thead>";
	                      txt += "<tr style='background-color:#a4eda9'>";
	                       txt += "<th width='3%'>Sr.No.</th>";
	                      // txt += "<th width='8%'>Call Date</th>";
	                       txt += "<th width='8%'>Audit date</th>";
	                       txt += "<th width='12%'>Issue Type</th>";
	                       txt += "<th width='25%'>Summary</th>";
                         txt += "<th width='12%'>Fatal Reason</th>";
	                       txt += "<th width='25%'>Fatal Sub Reason</th>";
txt += "<th width='10%'>Accuracy Score</th>";

                         txt += "<th width='10%'>Action</th>";
                         txt += "<th width='10%'>Comment</th>";
	                      txt += "</tr>";
	                     txt += "</thead>";
	                     txt += "<tbody>";
	                      for(var i = 0; i < data.total_record; i++)
	                       {
                            txt += "<tr>";
                            txt += "<td>"+parseInt(i+1)+"</td>";
                            if(data.all_record[i].no == "0" || data.all_record[i].no == 0){
                              //txt += "<td><b>"+data.all_record[i].call_date+"</b></td>";
                              txt += "<td><b>"+data.all_record[i].audit_date+"</b></td>";
                              txt += "<td><b>"+data.all_record[i].issue_type+"</b></td>";
                              txt += "<td><b>"+data.all_record[i].summary+"</b></td>";
                              txt += "<td><b>"+data.all_record[i].fatal_reason+"</b></td>";
                              txt += "<td><b>"+data.all_record[i].fatal_sub_reason+"</b></td>";
                              txt += "<td><b>"+data.all_record[i].accuracy_score+"</b></td>";

                            }else{
                                //txt += "<td style='color:blue'><b>"+data.all_record[i].call_date+"</b></td>";
                                txt += "<td style='color:blue'><b>"+data.all_record[i].audit_date+"</b></td>";
                                txt += "<td><b>"+data.all_record[i].issue_type+"</b></td>";
                                txt += "<td><b>"+data.all_record[i].summary+"</b></td>";
                                txt += "<td><b>"+data.all_record[i].fatal_reason+"</b></td>";
                                txt += "<td><b>"+data.all_record[i].fatal_sub_reason+"</b></td>";
				txt += "<td><b>"+data.all_record[i].accuracy_score+"</b></td>";

                             
                            }
                            
                            /*if(data.all_record[i].no == "0" || data.all_record[i].no == 0){
                              txt += "<td align='center'>";
                              txt += "</td>";
                            }else */if(data.all_record[i].action_value == "1" || data.all_record[i].action_value == 1){
                              txt += "<td align='center'><span style='color:green'><b>Accpeted</b></span></td>";
                            }else if(data.all_record[i].action_value == "2" || data.all_record[i].action_value == 2){
                              txt += "<td align='center'><span style='color:red'><b>Rejected</b></span></td>";
                            }else{
                              txt += "<td align='center'>";
                              txt += "</td>";
                            }
                           /* if(data.all_record[i].no == "0" || data.all_record[i].no == 0){
                              txt += "<td align='center'>";
                              txt += "</td>";
                            }else */if(data.all_record[i].action_value != "0"){
                              txt += "<td align='center'>"+data.all_record[i].comment+"</td>";
                            }else{
                              txt += "<td align='center'>";
                                
                              txt += "</td>";
                            }
                            
                            txt += "</tr>";
                          } 
                            txt += "</tbody>";
                            txt += "</table>";
                      
	                    $("#list_of_adviser_details").html(txt);
	                    
	                    $("input[data-bootstrap-switch]").each(function(){
                                $(this).bootstrapSwitch();
                            });
                          
	                   }
	                  else
	                   {
	                    $("#list_of_adviser_details").html("<center><font color='red'><b>"+data.message+"</b></font></center>");
	                   }
	                    
	         }
               });
    }
    function blink_text() {
      $('.blink').fadeOut(500);
      $('.blink').fadeIn(500);
      }
      setInterval(blink_text,2000);
    get_all_advisers();
  </script>
