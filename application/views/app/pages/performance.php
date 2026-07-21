
 <!-- Content Wrapper for Performance -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header" style="margin-bottom: 20px;">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>app/home">Home</a></li>
            
			 <li class="breadcrumb-item"> 
			 <a class="btn btn-sm btn-warning pull-right export" id="export_att" onclick="get_export_att();">Export Performance</a>
			 </li>
			    <li class="breadcrumb-item">    
				 <a class="btn btn-sm btn-danger pull-right export" data-toggle="modal" data-target="#exampleModalLong" id="import_kpi">Import Data</a>
					</li>
          </ol>
        </div>
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->
  
  
  
  <!-- Modal -->
	<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle">Adviser Performance</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
							</button>
								</div>
									<div class="modal-body">
										<form action="http://10.180.47.43/gemaudit/app/Users/export_adviser_performance" enctype="multipart/form-data" method="post">
											<div class="row">
											<div class="col-md-4">
										<input type="file" name="file" class="form-control" required />
									</div>
								<div class="col-md-4">
							<input type="submit" name="submit" value="Submit" class="btn btn-primary">
						</div>
					</div>
				</form>
			  </div>
			</div>
		  </div>
		</div>
  
  
  
  
 
  <div class="container-fluid">
    <div class="card card-default color-palette-box">
      <div class="card-header">
        <h3 class="card-title">
          <h5 class="m-0">All TL & AM's Performance</h5>
        </h3>
      </div>
      <div class="card-body">
        <form method="post" id="for_vote" enctype="multipart/form-data" style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
		
		<label for="start_date" style="margin-right: 2px;">Designation</label>
          <select class="form-control form-control-sm" id="rol_id" name="rol_id" value="" class="centered-dropdown">
                    <option value="0">--Select --</option>
					
                    <center><option value="4">TL</option></center>
                    <center><option value="6">QA</option></center>
                    <center><option value="5">AM</option></center>
                    <center><option value="2">CSA</option></center>
                   
                  </select>
		
          <label for="start_date" style="margin-right: 2px;">From</label>
          <input type="date" id="start_date" class="form-control" name="start_date" style="margin-right: 10px;" />
		  
		   <label for="end_date" style="margin-right: 2px;">To</label>
          <input type="date" id="end_date" class="form-control" name="end_date" style="margin-right: 10px;" />
		  
          <button type="button" id="adh_btn" name="adh_btn" class="btn btn-sm btn-warning" onclick="perform_all();">Perform</button>
        </form>
        
      </div>
    </div>
  </div>

  
  <div id="perform_report_all" style="overflow-x: auto; overflow-y: hidden; white-space: nowrap; max-width: 100%; box-sizing: border-box;">
          <!-- Content goes here -->
		  
		
		  
		  
        </div>
  
  

  <!-- Main content -->
 
</div>

<!-- Content Wrapper for TL & AM Performance -->



  
  <!-- /.content-wrapper -->
  
  
   <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"/>
		<script src="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/jquery.table2excel.min.js"></script>



<script>

function perform_all() {
    var start_date = $("#start_date").val(); 
    var end_date = $("#end_date").val();  
    var rol_id = $("#rol_id").val();            

    var base_url = '<?php echo base_url(); ?>';
    var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
    var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';

    // Show loading indicator
    $("#perform_report_all").html('<center><i>Loading...</i></center>');

    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: base_url + "index.php/app/Users/perform_all",
        data: {
            [csrfName]: csrfHash,
            start_date: start_date,
            end_date: end_date,
            rol_id: rol_id
        },
        cache: false,
        success: function (data) {
          
           
               if(data.response == true)
	                   {
                    var txt = "<table class='table table-bordered table-sm' id='example' style='font-size:12px;'>";
                    txt += "<thead>";
                    txt += "<tr style='text-align:center'>";
                    txt += "<th>S.No</th>";
                    txt += "<th>NAME</th>";
                    txt += "<th>MSD-ID</th>";
                    txt += "<th>CSAT</th>";
                    txt += "<th>FCR</th>";
                    txt += "<th>RESOLUTION</th>";
                    txt += "<th>REOPEN</th>";
                    txt += "<th>AVR</th>";
                    txt += "<th>AHT</th>";
                    txt += "</tr>";
                    txt += "</thead>";
                    txt += "<tbody>";
                    
                    for(var i = 0; i < data.total_record; i++)
	                       {

                        txt += "<tr style='text-align:center'>";
                        txt += '<td>' + (i + 1) + '</td>';
                        txt += "<td>" + (data.all_record[i].name || 'N/A') + "</td>";
                        txt += "<td>" + (data.all_record[i].msdid || 'N/A') + "</td>";
                        txt += "<td>" + (data.all_record[i].c_sat || 'N/A') + "</td>";
                        txt += "<td>" + (data.all_record[i].fcr || 'N/A') + "</td>";
                        txt += "<td>" + (data.all_record[i].resolution || 'N/A') + "</td>";
                        txt += "<td>" + (data.all_record[i].reopen || 'N/A') + "</td>";
                        txt += "<td>" + (data.all_record[i].avr || 'N/A') + "</td>";
                        txt += "<td>" + (data.all_record[i].aht || 'N/A') + "</td>";
                        txt += "</tr>";
                    }
                    
                    txt += "</tbody>";
                    txt += "</table>";

                    $("#perform_report_all").html(txt);
                } else {
                    $("#perform_report_all").html("<center><font color='red'><b>No records found.</b></font></center>");
                }
           
        },
       
    });
}


</script>

<script>
					
					$(function() {
              $("#export_att").click(function(e){
              var table = $("#example");
              if(table && table.length){
              $(table).table2excel({
              exclude: ".noExl",
              name: "Excel Document Name",
              filename: "Performance" + new Date(),
              fileext: ".xls",
              exclude_img: true,
              exclude_links: true,
              exclude_inputs: true,
              preserveColors: false
            });
          }
        });
        
      });
					
 </script>