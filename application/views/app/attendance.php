  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-10">
            <h1 class="m-0">Parent Enquiry List</h1>
          </div><!-- /.col -->
          <div class="col-sm-2">
          <a  style="margin-left: 90px;" class="btn btn-sm btn-success"  href="<?php echo base_url(); ?>app/attendance/export_enquiry">Export</a>
          </div>
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
      
       <div class="form-group row">    
				   <div class="col-sm-6">
					  <label for="sp_staff_id">Grade : </label>
					  <div class="input-validation">
					   <input type="hidden" id="classId" name="classId" />
					   	<select class="form-control input-sm" id='sel_class_id' onchange='get_batch(this.value);'>
                             <option value="">Select</opton>
                             <?php
                           
                            foreach($grade_list as $grow)
                             {  
                              echo "<option value=".$grow->grade_id.">".$grow->grade_name."</option>";
                             }
                             ?>
                         </select>
					 	
					 </div>
				   </div>    
              </div>
              <div class="form-group row">    
				   <div class="col-sm-6">
					  <label for="sp_staff_id">Batch: </label>
					  <div class="input-validation">
					    <select class="form-control input-sm" id='sel_grade_id' onchange="get_all_enquiry_details(this.value)";>
                             <option value="0">-- Select --</option>
                         </select>
					 	
					 </div>
				   </div>    
              </div>
              
         <div id="enquiry_list" class="col-sm-12"></div>
          
      </div><!--/. container-fluid -->
      <script>
      
      function get_all_enquiry_details() {
              $("#enquiry_list").html("<center><font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Wait, Loading...</b></font></center>");
               var grade_id = $('#sel_grade_id').val();
              //console.log(grade_id);
             
              
              var base_url = '<?php echo base_url(); ?>';
              $.ajax({
                  type: "POST",
                  dataType: "JSON",
                  url: base_url + "app/home/get_all_enquiry_details",
                  data: {"grade_id":grade_id},
                  cache: false,
                 // contentType: false,
                 // processData: false,
                  success: function(data, textStatus, jqXHR) {

                      if (data.response == true) {
                          var txt = "<table class='table table-bordered table-md'>";
                          txt += "<thead>";
                          txt += "<tr>";
                          txt += "<th width='' style='text-align:center'>Sr.No.</th>";
                          txt += "<th width='' style='text-align:center'>Mother/Father Name</th>";
                          txt += "<th width='' style='text-align:center'>Child Name</th>";
                          txt += "<th width='' style='text-align:center'>Grade</th>";
                          txt += "<th width='' style='text-align:center'>Email Id</th>";
                          txt += "<th width='' style='text-align:center'>Whatsapp No.</th>";
                          txt += "<th width='' style='text-align:center'>Enquiry Time</th>";
                          txt += "<th width='' style='text-align:center'>Delete</th>";
                          txt += "</tr>";
                          txt += "</thead>";
                          txt += "<tbody>";
                          for (var i = 0; i < data.total_record; i++) {
                              txt += "<tr>";
                              txt += "<td>" + parseInt(i + 1) + "</td>";
                              txt += "<td>" + data.all_record[i].name + "</td>";
                              txt += "<td>" + data.all_record[i].childname + "</td>";
                              txt += "<td>" + data.all_record[i].grade + "</td>";
                              txt += "<td>" + data.all_record[i].email + "</td>";
                              txt += "<td>" + data.all_record[i].contact + "</td>";
                              txt += "<td>" + data.all_record[i].eat + "</td>";
                              txt += "<td align='center'><span style='color:red;cursor:pointer;cursor:hand;' onclick='delete_enquiry(" + data.all_record[i].enquiry_id + ");'><i class='fa fa-times'></i></span></td>";
                              //txt += "<td>" + data.all_record[i].grade + "</td>";
                             
                              txt += "</tr>";
                          }
                          txt += "</tbody>";
                          txt += "</table>";
                          $("#enquiry_list").html(txt);
                      } else {
                          $("#enquiry_list").html("<center><font color='red'><b>" + data.message + "</b></font></center>");
                      }

                  }
              });
          }
          
          
