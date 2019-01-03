<!-- my API key AIzaSyCwKV83Ug8_e9RD0z53qbts1pEi9XJ7RKg-->

<!-- File name: interface1.php
     Description: implements the interface of the administrator. The interface shows the table with
                  the location from database,the MAP button and the add hotels button. -->
<?php

session_start();
/* XAMPP database informations */

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'db_project');
 
/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

/* The attempt of accessing the page unlogged is unallowed */
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: main.php");
    exit;
}

function test()
{
    session_destroy();
    header("location: main.php");
}

if(array_key_exists('test',$_POST)){
   test();
}

$sql = "select * from locations";

$result = mysqli_query($link,$sql);
/* Declare arrays to store DB entries */
$elemente = array() ;
$name_of_location=array();
$latitudine = array();
$longitudine=array();
$color=array();
$size=array();
$price=array();

/* Fetch the data from database */
while(($row = mysqli_fetch_array($result))) {
    $latitudine[] = $row['Latitude'];
    $longitudine[]= $row['Longitude'];
    $name_of_location[]=$row['Name'];
    $color[]=$row['Color'];
    $size[]=$row['size'];
    $price[]=$row['price'];
    $elemente[] = $row;
}

/* Show alert message when a new location is added */
$_SESSION['dbElements'] = $row;
if ($_SESSION['writeDB']==1 && $_SESSION['done']==1 )
{
    $_SESSION['writeDB']=-1;
    $message = "Locatia a fost adaugata in baza de date.";
    echo "<script type='text/javascript'>alert('$message');</script>";
}
else if ($_SESSION['writeDB']==1 && $_SESSION['done']==0 )
{
    $_SESSION['writeDB']=-1;
    $message = "Eroare de scriere in baza de date.";
    echo "<script type='text/javascript'>alert('$message');</script>";
}
else{};

?>

<!DOCTYPE html>
<html>
<head>
        <title> MAP VIEW </title>
        <link rel="stylesheet" type="text/css" href="CSS2.css">
        <link rel="stylesheet" type="text/css" href="tabel_and_others.css">
        <script src="ModalForm.js" type="text/javascript"></script> 
</head>
<body>


<div class="header">
    <div class="title"> <span style="font-size: 24pt; font-weight: bold; color: White">HOTELS FROM BRASOV</span>

        <div id="login" class="loginDisplay"><?php echo "You are logged as: "; echo htmlspecialchars($_SESSION["id"]); echo '<span style="color: red; font-weight: bold"> (admin) </span>' ?>
        <br><br>
        <form method="post">
        <input type="submit" name="test" id="test" value="Logout" /><br/>
        </form>
        </div>
       
    </div>
</div>

<div class="btn" style="margin-top: 20px;margin-right: 20px; float: float">
                    <button id="myBtn" style="width:100px; height: 40px">Adauga Hotel</button> 
                    <button id="mapBtn" style="width:100px; height: 40px">MAP</button>  
</div>

<!-- Implement table  -->
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
/* Add the elements from DB into de columns */
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

<div id="myModal" class="modal" style="float:center">
<!-- Modal content -->
<div class="modal-content">
  <div class="modal-header">
    <span class="close">&times;</span>
    <h2>Formular adaugare informatii</h2>
  </div>
  <div class="modal-body">
  <div class="form-modal">
          <!-- Set method as POST and action as writeToDb.php -->
          <form class="login-form" method="POST" action="writeToDb.php" style="margin-top: 20px">

        <div class="left-form" style="float:left">
            <label for="place">Nume locatie: </label>
            <input type="text" name="place_name" placeholder="nume" id="place" required/> <br><br>

            <label for="lat">Latitudine: &nbsp&nbsp&nbsp&nbsp </label>
            <input type="float" name="latitudine" placeholder="Latitudine" id="lat" required/> <br><br>


            <label for="long">Longitudine: &nbsp </label>
            <input type="float" name="longitudine" placeholder="Longitudine" id="long" required/> <br><br>
        </div>

        <div class="right-form" style="float:right">
            <label for="color">Culoare cerc:  </label>
            <select name="color">  
                                <option value="green">green</option>
                                <option value="red">red</option>
                                <option value="yellow">yellow</option>
                                <option value="black">black</option>
                                <option value="brown">brown</option>
                                <option value="purple">purple</option>
                                <option value="blue">blue</option>
            </select><br><br>
            <label for="size">Marime cerc:  </label>
            <input type="number" name="marime" placeholder="marime" id="size" required/> <br><br>


            <label for="price">Pret/zi:   &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp </label>
            <input type="number" name="pret" placeholder="pret" id="price" required/> <br><br>
        </div>

        <div class="button-form" style="float: right;margin-top: 21px; margin-left: 300px ">
            <button style="width: 100px"> Adauga</button>
         </div>   
          </form>
        </div>
  </div>
  <div class="modal-footer" style="margin-top: 140px">
  </div>
</div>

</div>
</div>
</body>
</html>

<script type="text/javascript">
    document.getElementById("mapBtn").onclick = function () {
        location.href = "MapView.php";
    };
</script>

<script> 

process_modal_form();

</script>