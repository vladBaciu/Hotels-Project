<!-- my API key xxg-->

<!-- File name: interface0.php
     Description: implements the interface of the user. The interface shows the table with
                  the location from database and the MAP button -->
<?php
session_start();


/* XAMPP database informations */

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'db_project');

/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);


/* Verify global variable to see if the person that want to access the page is logged in 
   That prevents the error when someone just copy the link into the browser and wants to access the page.*/

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: main.php");
    exit;
}

/* When user press Logout button Logout_function  is called */
function Logout_function()
{
    session_destroy();
    header("location: main.php");
}

/* When logout button is pressed POST['text'] is created */
if(array_key_exists('test',$_POST)){
   Logout_function();
}

/* Read the locations table from database */
$sql = "select * from locations";

$result = mysqli_query($link,$sql);

/* declare arrays to store the database entries */
$elemente = array();
$name_of_location=array();
$latitudine = array();
$longitudine=array();
$color=array();
$size=array();
$price=array();

/* Store each column into an array */
while(($row = mysqli_fetch_array($result))) {

        $latitudine[] = $row['Latitude'];
        $longitudine[]= $row['Longitude'];
        $name_of_location[]=$row['Name'];
        $color[]=$row['Color'];
        $size[]=$row['size'];
        $price[]=$row['price'];
        $elemente[] = $row;
}

?>

<!DOCTYPE html>
<html>
<head>
        <title> MAP VIEW </title>
        <link rel="stylesheet" type="text/css" href="CSS2.css">
        <link rel="stylesheet" type="text/css" href="tabel_and_others.css">
</head>
<body>


<div class="header">
    <div class="title"> <span style="font-size: 24pt; font-weight: bold; color: White">HOTELS FROM BRASOV</span>

        <!-- Display the current user id -->
        <div id="login" class="loginDisplay"><?php echo "You are logged as: "; echo htmlspecialchars($_SESSION["id"]); ?>
        <br><br>
        <form method="post">

        <!-- Logout - Logout_function() function is called -->
        <input type="submit" name="test" id="test" value="Logout" /><br/>
        </form>
        </div>
       
    </div>
</div>
<div class="btn" style="margin-top: 20px;margin-right: 20px; float: float">
                    <button id="mapBtn" style="width:100px; height: 40px">MAP</button>  
</div>
<!-- Implement table of locations -->
<table class = "table table-bordered">
    <tr>
            <th>Latitudine</th>
            <th>Longitudine</th>
            <th>Nume</th>
            <th>Culoare</th>
            <th>Marime</th>
            <th>Pret/zi</th>
    </tr>
    <?php 
/* Print all entries from the database into the table */
    foreach ($elemente as $row) { ?> 
    <tr> 
            <td><?php echo $row['Latitude']; ?></td> 
            <td><?php echo $row['Longitude']; ?></td> 
            <td><?php echo $row['Name']; ?></td> 
            <td><?php echo $row['Color']; ?></td> 
            <td><?php echo $row['size']; ?></td> 
            <td><?php echo $row['price']; ?></td> 
            <td></td> 
    </tr> 
    <?php } ?> 
</table>
</body>
</html>

<!-- Redirect the user when the map button is pressed -->
<script type="text/javascript">
    document.getElementById("mapBtn").onclick = function () {
        location.href = "MapView.php";
    };
</script>
