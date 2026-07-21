
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-12">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>app/home">Home</a></li>
              <li class="breadcrumb-item active">Add Audit</li>
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
              <h5 class="m-0">Add New Audit</h5>
            </h3>
          </div>
          <div class="card-body">
      <form action="<?php echo base_url(); ?>ImportData/uploadSupportData/" enctype="multipart/form-data" method="post">
          <div class="row">
            <!--<div class="col-md-4">
              <input type="date" name="date" class="form-control" />
            </div>-->
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
     
        
      </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
   
