<?php

session_start();  // start session

/* Start Define DB informations */

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'db_project');

/* End Define DB informations */
 
/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

$password_err = "";


if(isset($_POST['user']))
{

  $uname= $_POST['user'];
  $password=$_POST['pass'];


  $sql = "select * from users where user='".$uname."' AND password='".$password."'
  limit 1";

  $result = mysqli_query($link,$sql);

  if(mysqli_num_rows($result))

  {
    session_start();
    $_SESSION["loggedin"] = true;
    $_SESSION["id"] = $uname;
    $_SESSION["username"] = $password;  

    $query = "select admin from users where user='".$uname."' limit 1";    // query for admin column of user
    $query_response = mysqli_query($link,$query) or die(mysqli_error());  // fetch row for username
    
    $result = $query_response->fetch_assoc();                            //  Fetch a result row as an associative array
   
     
     if( $result["admin"] == 1)                                       //decide if user is administrator or not
   {
     header ("location: interface1.php");                            //admin interface
   }
    else
  {
    header ("location: interface0.php");                             //user interface
  
   }
  }
  else{
    $password_err = "The password/user you entered was not valid.";       // in case of unmatch print the information
    
   
  }
  mysqli_close($link);
}


?>
<!DOCTYPE html>
<html>
<head>
      <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="CSS1.css">
   <title> PROIECT TIM </title>
</head>
<body>

<div class="login-page">
        <div class="form">
          <form class="login-form" method="POST">


            <input type="text" name="user" placeholder="username" required/>
            <input type="password" name="pass" placeholder="password" required/>
            <button>login</button>
            <span class="help-block" style="color: rgb(153, 19, 26); font-size: 12px;" > <?php echo $password_err; ?></span>
          </form>
        </div>
      </div>
</body>
</html>


