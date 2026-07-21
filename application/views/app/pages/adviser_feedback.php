<!-- Bootstrap 4 -->
<!-- DataTables -->
<!-- ChartJS -->
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
                            { extend: 'excelHtml5', customize: function( xlsx ) { var sheet = xlsx.xl.worksheets['sheet1.xml']; $('row c[r^="C"]', sheet).attr( 's', '2' ); }}
                          ]
                 });
 }
</script> 

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
            <div class="row">
	<table class="table">
                                <tr>
					<td>
                                        <input type="date" title="Select From Date" name="start_date" id="start_date" class="form-control" placeholder="Enter Start Date" />
                                    </td>

                                    <td>
                                        <input type="date" name="end_date" title="Select To Date" id="end_date" onchange="get_adviser_feedback()" class="form-control" placeholder="Enter Start Date" />
                                    </td>
                                      <td>
                                        <select class="form-control input-sm" id="msd_id" name="msd_id" onchange="get_adviser_feedback();">
                                        <option value="">Select Adviser</option>
                                            <?php foreach($users as $i){ ?>
                                            <option value="<?=$i->msd_id ;?>"><?=$i->user_name;?></option>
                                            <?php } ?>                                        
					</select>
                                    </td>
                                     
                                </tr>
			 </table>
	    </div>          </div>
          <div class="card-body">
            
            <div class="row">
                         <div class="col-sm-12" id="role_list_div">
               
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
   function get_adviser_feedback()
    {
	var start_date = $("#start_date").val();
var end_date = $("#end_date").val();
var msd_id = $("#msd_id").val();

     $("#role_list_div").html("<center><font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Wait, Loading...</b></font></center>");
     var base_url = '<?php echo base_url(); ?>';
         $.ajax({
                 type: "POST",
                 dataType: "JSON",
                 url: base_url+"app/users/get_adviser_feedback",
                 data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>','start_date':start_date,'end_date':end_date,'msd_id':msd_id },
                 cache: false,
                 success: function (data, textStatus, jqXHR) {
	                  
	                  if(data.response == true)
	                   {
							var txt = "<table class='table table-bordered table-sm' style='font-size:12px;' id='myTable'>";
							txt += "<thead>";
							txt += "<tr style='background-color:#8193c1'>";
							txt += "<th width='3%'>Sr.No.</th>";
							txt += "<th width='8%'>Call Date</th>";
							txt += "<th width='8%'>Audit date</th>";
							txt += "<th width='8%'>MEM-ID</th>";
							txt += "<th width='12%'>Issue Type</th>";
							txt += "<th width='25%'>Summary</th>";
							txt += "<th width='12%'>AOI</th>";
							// txt += "<th width='25%'>Fatal Sub Reason</th>";
							txt += "<th width='10%'>Accuracy Score</th>";
							txt += "<th width='10%'>Call Accpect</th>";
							txt += "<th width='10%'>Accpect Date</th>";
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
							txt += "<td><b>"+data.all_record[i].call_date+"</b></td>";
							txt += "<td><b>"+data.all_record[i].audit_date+"</b></td>";
							txt += "<td><b>"+data.all_record[i].msd_id+"</b></td>";
                              txt += "<td><b>"+data.all_record[i].issue_type+"</b></td>";
                              txt += "<td><b>"+data.all_record[i].summary+"</b></td>";
                              txt += "<td><b>"+data.all_record[i].fatal_reason+"</b></td>";
                           //   txt += "<td><b>"+data.all_record[i].fatal_sub_reason+"</b></td>";
                              

                            }else{
                                txt += "<td style='color:blue'><b>"+data.all_record[i].call_date+"</b></td>";
                                txt += "<td><b>"+data.all_record[i].audit_date+"</b></td>";
                                txt += "<td><b>"+data.all_record[i].msd_id+"</b></td>";

                                txt += "<td><b>"+data.all_record[i].issue_type+"</b></td>";
                                txt += "<td><b>"+data.all_record[i].summary+"</b></td>";
                                txt += "<td><b>"+data.all_record[i].fatal_reason+"</b></td>";
                              //  txt += "<td><b>"+data.all_record[i].fatal_sub_reason+"</b></td>";
				
                             
                            }
if(data.all_record[i].accuracy_score == "0%"){
txt += "<td><b>0</b></td>";
}else{
txt += "<td><b>"+data.all_record[i].accuracy_score+"</b></td>";
}

                            if(data.all_record[i].accept_status == "0" || data.all_record[i].accept_status == 0){
                              txt += "<td align='center'></td>";
                            }else{
				txt += "<td align='center'>I have Listed the Call</td>";
			    }
txt += "<td><b>"+data.all_record[i].call_accepet_date+"</b></td>";

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
                      
	                    $("#role_list_div").html(txt);
	                    
	                    $("input[data-bootstrap-switch]").each(function(){
                                $(this).bootstrapSwitch();
                            });
                          
	                   }
	                  else
	                   {
	                    $("#role_list_div").html("<center><font color='red'><b>"+data.message+"</b></font></center>");
	                   }
	                    makeDataTable_Basic("myTable");
	         }
               });
    }
   
   
      
   
   get_adviser_feedback();
  </script>
