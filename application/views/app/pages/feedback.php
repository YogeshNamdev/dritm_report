<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">
    <link rel="icon" href="<?=base_url();?>/assets/Magnum_Logo1.png">
    <title>Paytm || Magnum</title>

<!--

Breezed Template

https://templatemo.com/tm-543-breezed

-->
    <!-- Additional CSS Files -->
    
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>web/css/bootstrap.min.css">
    
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>web/css/font-awesome.css">

    <link rel="stylesheet" href="<?php echo base_url(); ?>web/css/templatemo-breezed.css">

    <link rel="stylesheet" href="<?php echo base_url(); ?>web/css/owl-carousel.css">

    <link rel="stylesheet" href="<?php echo base_url(); ?>web/css/lightbox.css">

    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>web/css/main.css">

<style>
    .tablink {
  background-color: #555;
  color: white;
  float: left;
  border: none;
  outline: none;
  cursor: pointer;
  padding: 14px 16px;
  font-size: 17px;
  width: 33.3%;
}

.tablink:hover {
  background-color: #777;
}

/* Style the tab content (and add height:100% for full page content) */
.tabcontent {
  color: white;
  display: none;
  padding: 100px 20px;
  height: 100%;
}


</style>
    </head>
    
    <body>
    
    <!-- ***** Preloader Start ***** -->
    <div id="preloader">
        <div class="jumper">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>  
    <!-- ***** Preloader End ***** -->
    
    
    <!-- ***** Header Area Start ***** -->
    <header class="header-area header-sticky">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="main-nav">
                        <!-- ***** Logo Start ***** -->
                        <a href="#" class="logo">
                          <img width="150px" src="<?php echo base_url(); ?>includes/dist/img/Magnum_Logo.png" alt="<?php echo APP_TITLE; ?>" class="brand-image elevation-3">
                          <img width="150px" src="<?php echo base_url(); ?>includes/dist/img/Paytm_Logo.png" alt="<?php echo APP_TITLE; ?>" class="brand-image elevation-3">
                        </a>
                        <!-- ***** Logo End ***** -->
                        <!-- ***** Menu Start ***** -->
                        <ul class="nav">
                            <li class="submenu">
                                <a href="javascript:;"> <?php echo $_SESSION["userdata"]["user_name"]." (".$_SESSION["userdata"]["role_name"].")"; ?></a>
                                <ul>
                                    <li><a href="<?php echo base_url(); ?>app/logout">LogOut</a></li>
                                </ul>
                            </li>
                        </ul>       
                        <!-- ***** Menu End ***** -->
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <!-- ***** Header Area End ***** -->

    <footer>
        <button class="tablink" onclick="openPage('Newly', this, '#79ab87')">Newly Added</button>
        <button class="tablink" onclick="openPage('Today', this, '#79ab87')" id="defaultOpen">Today's Briefing</button>
        <button class="tablink" onclick="openPage('Previous', this, '#79ab87')">Previous Briefing</button>
        <div id="Newly" class="tabcontent">
        <div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        
        <div class="card card-default color-palette-box">
          <div class="card-body">
            
            <div class="row">
            <div class="col-md-12">
            <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th class="text-left">DESCRIPTION</th>

                                            <th class="text-right">Date</th> 
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $j = 1;
                                        foreach ($quality_feedback as $feedback) {
                                            if($feedback->briefing_type=="Newly"){
                                            ?>
                                            <tr>
                                                <td class="no"><span class="label label-success"><?= $j; ?></span></td>
                                                <td class="text-left">

                                                    <div class="user-block">
                                                        <!--<img class="img-circle img-bordered-sm" src="<?= base_url(); ?>assets/admin/dist/img//user1-128x128.jpg" alt="User Image">-->
                                                        <span class="username">
                                                            <a href="#"><?= $feedback->title; ?></a>
                                                            <a href="#" class="pull-right btn-box-tool"><i class="fa fa-times"></i></a>
                                                        </span>
                                                        <!--<span class="description">Briefing: <?php echo date("d-M-Y", strtotime($feedback->eat)); ?></span>-->
                                                    </div>
                                                    <p>
    <?= $feedback->description; ?>
                                                    </p>         



                                                    <?php if ($feedback->feedback_link != '') { ?>
                                                        <a href="<?= base_url(); ?>web_components/pdf/feedback/<?= $feedback->id; ?>.<?= $feedback->feedback_link; ?>" style="font-size:18px;color:orange" target="_blank">Download </a>
    <?php } ?>
                                                </td>

                                                <td><span class="badge bg-yellow"><?php echo date("d-M-Y", strtotime($feedback->eat)); ?></span></td>
                                            </tr>
                                            <?php $j++;
                                        }}
                                        ?>
                                    </tbody>
                                </table>
            </div>
            </div>
            
          </div>
        </div>
        
      </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
  </div>
        </div>

        <div id="Today" class="tabcontent">
        <div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        
        <div class="card card-default color-palette-box">
          
          <div class="card-body">
            
            <div class="row">
            <div class="col-md-12" >
            <table class="table table-bordered table-striped" >
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th class="text-left">DESCRIPTION</th>

                                            <th class="text-right">Date</th> 
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $j = 1;
                                        foreach ($quality_feedback as $feedback) {
                                            if($feedback->briefing_type=="Today"){
                                            ?>
                                            <tr>
                                                <td class="no"><span class="label label-success"><?= $j; ?></span></td>
                                                <td class="text-left">

                                                    <div class="user-block">
                                                        <!--<img class="img-circle img-bordered-sm" src="<?= base_url(); ?>assets/admin/dist/img//user1-128x128.jpg" alt="User Image">-->
                                                        <span class="username">
                                                            <a href="#"><?= $feedback->title; ?></a>
                                                            <a href="#" class="pull-right btn-box-tool"><i class="fa fa-times"></i></a>
                                                        </span>
                                                        <!--<span class="description">Briefing: <?php echo date("d-M-Y", strtotime($feedback->eat)); ?></span>-->
                                                    </div>
                                                    <p>
    <?= $feedback->description; ?>
                                                    </p>         



                                                    <?php if ($feedback->feedback_link != '') { ?>
                                                        <a href="<?= base_url(); ?>web_components/pdf/feedback/<?= $feedback->id; ?>.<?= $feedback->feedback_link; ?>" style="font-size:18px;color:orange" target="_blank">Download </a>
    <?php } ?>
                                                </td>

                                                <td><span class="badge bg-yellow"><?php echo date("d-M-Y", strtotime($feedback->eat)); ?></span></td>
                                            </tr>
                                            <?php $j++;
                                        }}
                                        ?>
                                    </tbody>
                                </table>
            </div>
            </div>
            
          </div>
        </div>
        
      </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
  </div>
        </div>

        <div id="Previous" class="tabcontent">
        <div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        
        <div class="card card-default color-palette-box">
          
          <div class="card-body">
            
            <div class="row">
             <div class="col-md-12">
             <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th class="text-left">DESCRIPTION</th>

                                            <th class="text-right">Date</th> 
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $j = 1;
                                        foreach ($quality_feedback as $feedback) {
                                            if($feedback->briefing_type=="Previous"){
                                            ?>
                                            <tr>
                                                <td class="no"><span class="label label-success"><?= $j; ?></span></td>
                                                <td class="text-left">

                                                    <div class="user-block">
                                                        <!--<img class="img-circle img-bordered-sm" src="<?= base_url(); ?>assets/admin/dist/img//user1-128x128.jpg" alt="User Image">-->
                                                        <span class="username">
                                                            <a href="#"><?= $feedback->title; ?></a>
                                                            <a href="#" class="pull-right btn-box-tool"><i class="fa fa-times"></i></a>
                                                        </span>
                                                        <!--<span class="description">Briefing: <?php echo date("d-M-Y", strtotime($feedback->eat)); ?></span>-->
                                                    </div>
                                                    <p>
    <?= $feedback->description; ?>
                                                    </p>         



                                                    <?php if ($feedback->feedback_link != '') { ?>
                                                        <a href="<?= base_url(); ?>web_components/pdf/feedback/<?= $feedback->id; ?>.<?= $feedback->feedback_link; ?>" style="font-size:18px;color:orange" target="_blank">Download </a>
    <?php } ?>
                                                </td>

                                                <td><span class="badge bg-yellow"><?php echo date("d-M-Y", strtotime($feedback->eat)); ?></span></td>
                                            </tr>
                                            <?php $j++;
                                        }}
                                        ?>
                                    </tbody>
                                </table>
            </div>
            </div>
            
          </div>
        </div>
        
      </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
  </div>
        </div>

    </footer>




     <!-- jQuery -->
 <script src="<?php echo base_url(); ?>web/js/jquery-2.1.0.min.js"></script>

<!-- Bootstrap -->
<script src="<?php echo base_url(); ?>web/js/popper.js"></script>
<script src="<?php echo base_url(); ?>web/js/bootstrap.min.js"></script>

<!-- Plugins -->
<script src="<?php echo base_url(); ?>web/js/owl-carousel.js"></script>
<script src="<?php echo base_url(); ?>web/js/scrollreveal.min.js"></script>
<script src="<?php echo base_url(); ?>web/js/waypoints.min.js"></script>
<script src="<?php echo base_url(); ?>web/js/jquery.counterup.min.js"></script>
<script src="<?php echo base_url(); ?>web/js/imgfix.min.js"></script> 
<script src="<?php echo base_url(); ?>web/js/slick.js"></script> 
<script src="<?php echo base_url(); ?>web/js/lightbox.js"></script> 
<script src="<?php echo base_url(); ?>web/js/isotope.js"></script> 

<!-- Global Init -->
<script src="<?php echo base_url(); ?>web/js/custom.js"></script>
<script>

$(function() {
    var selectedClass = "";
    $("p").click(function(){
    selectedClass = $(this).attr("data-rel");
    $("#portfolio").fadeTo(50, 0.1);
        $("#portfolio div").not("."+selectedClass).fadeOut();
    setTimeout(function() {
      $("."+selectedClass).fadeIn();
      $("#portfolio").fadeTo(50, 1);
    }, 500);
        
    });
});
function openPage(pageName,elmnt,color) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablink");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].style.backgroundColor = "";
  }
  document.getElementById(pageName).style.display = "block";
  elmnt.style.backgroundColor = color;
}

// Get the element with id="defaultOpen" and click on it
document.getElementById("defaultOpen").click();




</script>



 <!-- ***** Footer Start ***** -->
 

    
    
</body>
</html>

   

