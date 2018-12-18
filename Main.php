<?php

session_start();  // inc

/* Start Define DB informations */

/*flags */
$_SESSION['writeDB']=-1;
$_SESSION['done']=-1;

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

/* If user is set in username field verify if the password matches with the information from database */
if(isset($_POST['user']))
{
 /*Start  -Store login data from The global $_POST variable */
  $uname= $_POST['user'];
  $password=$_POST['pass'];
/* End    -Store login data from The global $_POST variable */

  /* SQL instruction in which we want to read the username and password to see if a match occure */
  $sql = "select * from users where user='".$uname."' AND password='".$password."'
  limit 1";                                                                               

  /* Send query to DB and store the result */
  $result = mysqli_query($link,$sql);

  /* If result is not empty that means that a match occured */
  if(mysqli_num_rows($result))

  {
  
    session_start();
    /* Store in php SESSION global variable the id and username for future use. Also, is set a flag that the user is logged in */
    $_SESSION["loggedin"] = true;              
    $_SESSION["id"] = $uname;
    $_SESSION["username"] = $password;  

    /* After the match from database, check if the Username corresponds to an admin or is just a simple user */
    $query = "select admin from users where user='".$uname."' limit 1";    // query for admin column of user
    $query_response = mysqli_query($link,$query) or die(mysqli_error());  // fetch row for username
    
    $result = $query_response->fetch_assoc();                            //  Fetch a result row as an associative array
   
     /* Redirect the admins to interface1.php and users to interface0.php */
     if( $result["admin"] == 1)                                       //decide if user is administrator or not
   {
     header ("location: interface1.php");                            //admin interface
   }
    else
  {
    header ("location: interface0.php");                             //user interface
  
   }
  }
  /* When a match was not found, store a error message in password_err variable. */
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
     <!-- Link CSS1 file to this file. -->
    <link rel="stylesheet" type="text/css" href="CSS1.css">   
   <title> PROIECT TIM </title>
</head>
<body>

<div class="login-page">
        <div class="form">
          <!-- The POST method transfers information via HTTP headers.-->
          <form class="login-form" method="POST"> 
            <!-- The POST method transfers information - USER-->
            <input type="text" name="user" placeholder="username" required/>
            <!-- The POST method transfers information - PASS-->  
            <input type="password" name="pass" placeholder="password" required/>
            <!--Login button -->
            <button>login</button>
             <!-- Error message. Login failed.-->
            <span class="help-block" style="color: rgb(153, 19, 26); font-size: 12px;" > <?php echo $password_err; ?></span>
          </form>
        </div>
      </div>
</body>
</html>


