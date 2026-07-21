<?php
include('header.php');
 
 
  
?>



<div class="container-fluid">
  
  <div class="jumbotron">
    <div class="row">
      <div class="col-sm-12">
    <div class="panel panel-primary">











       <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1></h1><a href="logout.php" style="text-align: right;"><h2>Logout</h2></a>
           
          </div>


<div class="panel-body">




      <button type="button" class="btn btn-danger btn-lg" data-toggle="modal" data-target="#myModal">
    Add Header
  </button>

  <!-- The Modal -->
  <div class="modal fade" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title" style="color:black">Enter Heading</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
          <form   method="post" enctype="multipart/form-data">

          
    <div class="form-group">
      <label for="email">User:</label>
      <input type="text" class="form-control" id="email"  name="title">
    </div>
    
    
     <input type="submit" name="submit" value="Save" class="btn btn-primary">

     
  </form>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>
  




  <!--
      <form method="post" action="view.php">
              <label for="birthday">Select Date:</label>

<input type="date" name="start_date" id="start_date">
<label>-</label>
<input type="date" name="end_date" id="end_date">
<input type="submit" name="show">
-->
<a href="download.php" style="margin-left: 80%;">
  <button type="button" class="btn btn-danger btn-lg">Download</button>
</a>
<h3></h3>
</form>




          <div class="card shadow mb-4">
           
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="tblUser">


                  <thead>
                    
                    <tr>
    <th>S.N.</th>
    <th>MSD ID</th>
    <th>DATE</th>
   
   
    <th>TIME</th>
     <th>STATUS</th>
    

     



   
  </tr>

                  </thead>

                 <tbody>
  <?php


 if (isset($_REQUEST['show'])) {
  
   $start_date = $_REQUEST['start_date'];
  $end_date = $_REQUEST['end_date'];



$q="select * from  login_history where date >= '$start_date' and date <= '$end_date' order by date desc ";

  
  
  

   
  $rs=mysqli_query($conn, $q);
  $i=0;
  while ($rs1=mysqli_fetch_array($rs)) {
    $i++;

    


?>


<tr>
      
    <td><?php echo $i; ?></td>
    <td><?php echo $rs1['msd_id']; ?></td>
   <td><?php echo $rs1['date']; ?></td>
 
   <td><?php echo $rs1['time']; ?></td>
  
   
  <?php
    $time =  $rs1['time'];


   $chech = "09:15:00";

   $t1 = strtotime($time);
   $t2 = strtotime($check);
   if ($t1 >= $t2) {
    ?>
     <td><?php echo 'Not Adhere'; ?></td> 

     <?php
   }

    else {

    ?>
  
    <td><?php echo 'Adhere'; ?></td>
   
    
  <?php }

  ?>
    
      
  
      
   
  
    
 
 
</tr>

<?php  } 
}

else {

?>


















     

   
    
     
  </tr>

  <?php


  
  
  



$q="select * from  login_history group by msd_id, date order by date desc";

  
  
  

   
  $rs=mysqli_query($conn, $q);
  $i=0;
  while ($rs1=mysqli_fetch_array($rs)) {
    $i++;

   


?>


<tr>
        
   <td><?php echo $i; ?></td>
    <td><?php echo $rs1['msd_id']; ?></td>
   <td><?php echo $rs1['date']; ?></td>
 
   <td><?php echo $rs1['time']; ?></td>
  
   <?php
    $time =  $rs1['time'];


   $check = "09:15:00";

   $t1 = strtotime($time);
   $t2 = strtotime($check);
   if ($t1 >= $t2) {
    ?>
     <td><?php echo 'Not Adhere'; ?></td> 

     <?php
   }

    else {

    ?>
  
    <td><?php echo 'Adhere'; ?></td>
   
    
  <?php }

  ?>
      
  
 
 
</tr>

<?php  } }

if(isset($_REQUEST['submit'])) {




  $title = $_REQUEST['title'];

  $q = "update heading set title='$title' where id=1";

  mysqli_query($conn, $q);



}



?>







</tbody>
                </table>
              </div>
            </div>
          </div>
          </div>
        </div>
      </div>
    </div>



<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.js"></script>
<script>
jQuery(document).ready(function($) {
    $('#tblUser').DataTable();
} );
</script>