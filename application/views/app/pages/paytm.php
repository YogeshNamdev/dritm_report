
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
    get_all_logs();
  </script>
