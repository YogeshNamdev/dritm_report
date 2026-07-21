<?php

include('login.php'); // Includes Login Script



?>
<!DOCTYPE html>
<html lang="en">
<head>
 <link rel="icon" type="image/png" href="images/Censuslogo.png">
  <title></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>

<div class="container jumbotron" style="background-color: #002642;">
 <h2 style="text-align:center; color: white;"> </h2><br>
  <center>
<div class="panel panel-primary" style="width: 40%; text-align: left;">
      <div class="panel-heading"><h4>Login</h4></div>
      <div class="panel-body">

    <div class="panel panel-primary">
      
      <div class="panel-body">  

        <form   method="post" enctype="multipart/form-data">

          
    <div class="form-group">
      <label for="email">User:</label>
      <input type="text" class="form-control" id="email" placeholder="Enter User name" name="user">
    </div>
    <div class="form-group">
      <label for="pwd">Password:</label>
      <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="pass">
    </div>
    
     <input type="submit" name="login" value="login" class="btn btn-primary">

     
  </form>
    </div>
  </div>
 </div>
 </div>
</center>
 </div>
</body>
</html>
