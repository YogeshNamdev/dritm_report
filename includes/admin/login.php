<?php


if (isset($_POST['login'])) {

// Define $username and $password

$user=$_POST['user'];
$pass=$_POST['pass'];
// Establishing Connection with Server by passing server_name, user_id and password as a parameter
// To protect MySQL injection for Security purpose



// Selecting Database
// SQL query to fetch information of registerd users and finds user match.

if ($user=="admin" && $pass=="admin")
 {
 
 header("location: view.php"); // Redirecting To Other Page
}

   
 else 
 {
  $error = "Username or Password is invalid";
}
//mysql_close($connection); // Closing Connection

}

?>