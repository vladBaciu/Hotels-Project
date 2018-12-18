<?php
session_start();

/* Start Define DB informations */

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'db_project');

/* End Define DB informations */
 
/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}



$location_name=$_POST['place_name'];
$latitude=$_POST['latitudine'];
$longitude=$_POST['longitudine'];
$color=$_POST['color'];
$size=$_POST['marime'];
$price=$_POST['pret'];


$result_maxID = mysqli_query($link,"SELECT MAX(ID) FROM locations");
$row = mysqli_fetch_row($result_maxID);
$highest_id = $row[0] + 1;


$sql = " INSERT INTO locations (ID,Name,Latitude,Longitude,Color,Size,Price)
          VALUES ('$highest_id', '$location_name', '$latitude','$longitude','$color','$size','$price')"; 

$result = mysqli_query($link,$sql);

$_SESSION['writeDB']=1;
if($result)
{
    $_SESSION['done'] = 1;
}
else
{

    $_SESSION['done'] = 0;
}
header("location: interface1.php");

?>