
<style>
    body, html {
        height:100%;
      }
      /*
       * Off Canvas sidebar at medium breakpoint
       * --------------------------------------------------
       */
      @media screen and (max-width: 992px) {
      
        .row-offcanvas {
          position: relative;
          -webkit-transition: all 0.25s ease-out;
          -moz-transition: all 0.25s ease-out;
          transition: all 0.25s ease-out;
        }
      
        .row-offcanvas-left
        .sidebar-offcanvas {
          left: -33%;
        }
      
        .row-offcanvas-left.active {
          left: 33%;
          margin-left: -6px;
        }
      
        .sidebar-offcanvas {
          position: absolute;
          top: 0;
          width: 33%;
          height: 100%;
        }
      }
      
      /*
       * Off Canvas wider at sm breakpoint
       * --------------------------------------------------
       */
      @media screen and (max-width: 34em) {
        .row-offcanvas-left
        .sidebar-offcanvas {
          left: -45%;
        }
      
        .row-offcanvas-left.active {
          left: 45%;
          margin-left: -6px;
        }
        
        .sidebar-offcanvas {
          width: 45%;
        }
      }
      
      .card {
          overflow:hidden;
      }
      
      .card-body .rotate {
          z-index: 8;
          float: right;
          height: 100%;
      }
      
      .card-body .rotate i {
          color: rgba(20, 20, 20, 0.15);
          position: absolute;
          left: 0;
          left: auto;
          right: -10px;
          bottom: 0;
          display: block;
          -webkit-transform: rotate(-44deg);
          -moz-transform: rotate(-44deg);
          -o-transform: rotate(-44deg);
          -ms-transform: rotate(-44deg);
          transform: rotate(-44deg);
      }
      
    </style>
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
                            { extend: 'print', exportOptions: { columns: ':visible' }  },
                            { extend: 'pdfHtml5', orientation: 'landscape', pageSize: 'LEGAL',  download: 'open' },
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
              <li class="breadcrumb-item active">Dash board</li>
            </ol>
          </div>
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
   <?php if($role_id == 2 || $role_id == 5 || $role_id == 6 || $role_id == 7) { ?>
    <section class="content">
      <div class="container-fluid">
        
        <div class="card card-default color-palette-box">
          <div class="card-header">
<div class="row">
<div class="col-md-6">
<h4 class="modal-title">Advisor Dashboard</h4>
</div>
<div class="col-md-4">&nbsp;&nbsp;</div>
<div class="col-md-2">
                  <select class="form-control form-control-sm" id="month" name="month" onchange="get_adviser_monthly_data(this.value);">
                    <option value="0">--Month Select --</option>
                    <option value="1">January</option>
                    <option value="2">Febuary</option>
                    <option value="3">March</option>
                    <option value="4">Apirl</option>
                    <option value="5">May</option>
                    <option value="6">June</option>
                    <option value="7">July</option>
                    <option value="8">August</option>
                    <option value="9">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                  </select>
</div>
</div>
           </div>
          <div class="card-body">
            
          <div class="container-fluid" id="main">
        <div class="row row-offcanvas row-offcanvas-left">
            <div class="col main pt-5 mt-3">
                
                <div class="row mb-3">
                    <div class="col-xl-2 col-sm-4 py-1">
                        <div class="card bg-success text-white h-100">
                            <div class="card-body bg-success">
                                <div class="rotate">
                                    <i class="fa fa-user fa-4x"></i>
                                </div>
                                <h6 class="text-uppercase">Quality Score</h6>
                                <h1 class="display-4"><span id='adviser_quality'>%</span></h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-sm-4 py-1">
                        <div class="card text-white bg-danger h-100">
                            <div class="card-body bg-danger">
                                <div class="rotate">
                                    <i class="fa fa-list fa-4x"></i>
                                </div>
                                <h6 class="text-uppercase">Total Audits</h6>
                                <h1 class="display-4"><span id='total_audit'></span></h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-sm-4 py-1">
                        <div class="card text-white bg-info h-100">
                            <div class="card-body bg-info">
                                <div class="rotate">
                                    <i class="fa fa-twitter fa-4x"></i>
                                </div>
                                <h6 class="text-uppercase">Fatal Count</h6>
                                <h1 class="display-4"><span id='fatal_count'></span></h1>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    
          </div>
        </div>
        
      </div><!--/. container-fluid -->
    </section>
<?php }?>















			<?php if($role_id == 2 || $role_id == 5 || $role_id == 6 || $role_id == 7 || $role_id == 4) { ?>
			<section class="content">
			<div class="container-fluid">
        
            <div class="card card-default color-palette-box">
            <div class="card-header">
			<div class="row">
			<div class="col-md-6">
			<h5 class="modal-title"><b>Adviser Performance</b></h5>
			</div>
			<div class="form-group" style="display: flex; align-items: center; margin-left: -115px;">
			<label for="a_start_date" style="margin-right: 5px;">From:</label>
			<input type="date" id="a_start_date" class="form-control" name="a_start_date" placeholder="Select a date" />
			</div>
			<div class="form-group" style="display: flex; align-items: center;">
			<label for="a_end_date" style="margin-right: 5px;">To:</label>
			<input type="date" id="a_end_date" class="form-control" name="a_end_date" placeholder="Select a date" />
	
			<div class="col-md-3">
			<button type="button" id="adh_btn" name="adh_btn" style="height:38px;" class="btn btn-sm btn-warning pull-right" onclick="get_my_performance();">Search</button>
			</div>
	
</div>

              
	

<div class="col-md-4">&nbsp;&nbsp;</div>

</div>
           </div>
          <div class="card-body">
            
          <div class="container-fluid" id="main">
        <div class="row row-offcanvas row-offcanvas-left">
            <div class="col main pt-5 mt-3">
                
                <div class="row mb-3">
                    <div class="col-xl-2 col-sm-4 py-1">
                        <div class="card bg-success text-white h-100">
                            <div class="card-body bg-success">
                                <div class="rotate">
                                    <i class="fa fa-user fa-4x"></i>
                                </div>
                                <h6 class="text-uppercase">C.SAT</h6>
                                <h1 class="display-4"><span id='csat'>%</span></h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-sm-4 py-1">
                        <div class="card text-white bg-danger h-100">
                            <div class="card-body bg-danger">
                                <div class="rotate">
                                    <i class="fa fa-list fa-4x"></i>
                                </div>
                                <h6 class="text-uppercase">FCR</h6>
                                <h1 class="display-4"><span id='fcr'>%</span></h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-sm-4 py-1">
                        <div class="card text-white bg-info h-100">
                            <div class="card-body bg-info">
                                <div class="rotate">
                                    <i class="fa fa-twitter fa-4x"></i>
                                </div>
                                <h6 class="text-uppercase">Resolution</h6>
                                <h1 class="display-4"><span id='resolution'>%</span></h1>
                            </div>
                        </div>
                    </div>
                    
               
				
				
				
				
                    <div class="col-xl-2 col-sm-4 py-1">
                        <div class="card bg-success text-white h-100">
                            <div class="card-body bg-success">
                                <div class="rotate">
                                    <i class="fa fa-user fa-4x"></i>
                                </div>
                                <h6 class="text-uppercase">Reopen</h6>
                                <h1 class="display-4"><span id='reopen'>%</span></h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-sm-4 py-1">
                        <div class="card text-white bg-danger h-100">
                            <div class="card-body bg-danger">
                                <div class="rotate">
                                    <i class="fa fa-list fa-4x"></i>
                                </div>
                                <h6 class="text-uppercase">Average</h6>
                                <h1 class="display-4"><span id='avr'></span></h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-sm-4 py-1">
                        <div class="card text-white bg-info h-100">
                            <div class="card-body bg-info">
                                <div class="rotate">
                                    <i class="fa fa-twitter fa-4x"></i>
                                </div>
                                <h6 class="text-uppercase">AHT</h6>
                                <h6 class="display-4"><span id='aht'></span></h6>
                            </div>
                        </div>
                    </div>
                    
                </div>
				
				
				
            </div>
        </div>
    </div>
    
          </div>
        </div>
        
      </div><!--/. container-fluid -->
    </section>
<?php }?>






















<?php if($role_id == "1" && $role_id == 1){?>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        
        <div class="card card-default color-palette-box">
          <div class="card-header">
            <h3 class="card-title">
              <h5 class="m-0"><b>Dashboard</b></h5>
            </h3>
          </div>
          <div class="card-body">
            
            <div class="row">
             <div class="col-md-12" id="log_list">

            </div>
            </div>
            
          </div>
        </div>
        
      </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
	
	
	
	
	
	
	
	
	
  </div>
  <!-- /.content-wrapper -->
  <?php }?>
  
  
  <!--
  
  <?php if($role_id == "4"){?>


<section class="content">
      <div class="container-fluid">
        
        <div class="card card-default color-palette-box">
          <div class="card-header">
            <h3 class="card-title">
              <h5 class="m-0"><b>Team Leader performance</b></h5>
            </h3>
          </div>
          <div class="card-body">
            
            <div class="row">
            <div id="perform_report_all" style="overflow-x: auto; overflow-y: hidden; white-space: nowrap; max-width: 100%; box-sizing: border-box;">
          <!-- Content goes here -->
        </div>
            </div>
            
          </div>
        </div>
        
      </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->


  <?php }?>
  
  
  -->
  
  
  
  
  
  
  
  <script>
   function get_all_logs()
    {
     $("#role_list_div").html("<center><font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Wait, Loading...</b></font></center>");
     var base_url = '<?php echo base_url(); ?>';
         $.ajax({
                 type: "POST",
                 dataType: "JSON",
                 url: base_url+"app/users/get_all_logs",
                 data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' },
                 cache: false,
                 success: function (data, textStatus, jqXHR) {
	                  
	                  if(data.response == true)
	                   {
	                    var txt = "<table class='table table-bordered table-sm' id='tbl_students'>";
	                     txt += "<thead>";
	                      txt += "<tr>";
	                       txt += "<th width='10%'>Sr.No.</th>";
                         txt += "<th width='20%'>Role Name</th>";
	                       txt += "<th width='20%'>Name</th>";
                         txt += "<th width='10%'>MSDID</th>";
                         txt += "<th width='20%'>Login Date Time</th>";
                         txt += "<th width='20%'>Logout Date Time</th>";
	                      txt += "</tr>";
	                     txt += "</thead>";
	                     txt += "<tbody>";
	                      for(var i = 0; i < data.total_record; i++)
	                       {
	                        txt += "<tr>";
	                         txt += "<td>"+parseInt(i+1)+"</td>";
                           txt += "<td>"+data.all_record[i].role_name+"</td>";
	                         txt += "<td>"+data.all_record[i].user_name+"</td>";
                           txt += "<td>"+data.all_record[i].msd_id+"</td>";
                           txt += "<td>"+data.all_record[i].login_date_time+"</td>";
                           txt += "<td>"+data.all_record[i].logout_date_time+"</td>";
	                        txt += "</tr>";
	                       }
	                     txt += "</tbody>";
	                    txt += "</table>";
	                    $("#log_list").html(txt);
	                   }
	                  else
	                   {
	                    $("#log_list").html("<center><font color='red'><b>"+data.message+"</b></font></center>");
	                   }
                     makeDataTable_Basic("tbl_students");
	                    
	         }
               });
    }
	
	
	
	
	
	function get_my_performance(){
		
        var start_date = $("#a_start_date").val();
		var end_date = $("#a_end_date").val();
        var msd_id = '<?php echo $msd_id ; ?>';

      $("#list_of_monthly_details").html("<center><font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Wait, Loading...</b></font></center>");
     var base_url = '<?php echo base_url(); ?>';
         $.ajax({
                 type: "POST",
                 dataType: "JSON",
                 url: base_url+"app/users/get_my_performance",
                 data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>','msd_id':msd_id,'start_date':start_date,'end_date':end_date },
                 cache: false,
                 success: function (data, textStatus, jqXHR) {
	                  
	                $("#csat").html(data.c_sat+"%");
					$("#fcr").html(data.fcr+"%"); 
					$("#resolution").html(data.resolution+"%");
					$("#reopen").html(data.reopen+"%");
					$("#avr").html(data.avr);
					$("#aht").html(data.aht);
					//$("#").html(data.fatal_count);                   
	                  	                    
	         }
               });
    }
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

function get_adviser_monthly_data(month){
if(month == 0){
          var d = new Date();
          var month = d.getMonth()+1; 
      }
        var msd_id = '<?php echo $msd_id ; ?>';

      $("#list_of_monthly_details").html("<center><font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Wait, Loading...</b></font></center>");
     var base_url = '<?php echo base_url(); ?>';
         $.ajax({
                 type: "POST",
                 dataType: "JSON",
                 url: base_url+"app/users/get_adviser_monthly_data",
                 data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>','msd_id':msd_id,'month':month,'msd_id':msd_id },
                 cache: false,
                 success: function (data, textStatus, jqXHR) {
	                  
	              $("#adviser_quality").html(data.adviser_quality+"%");
					$("#total_audit").html(data.total_audit); 
					$("#fatal_count").html(data.fatal_count);                   
	                  	                    
	         }
               });
    }
var d = new Date();
    var month = d.getMonth()+1; 
get_adviser_monthly_data(month);
    get_all_logs();
    </script>