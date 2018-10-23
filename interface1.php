<!-- my API key AIzaSyCwKV83Ug8_e9RD0z53qbts1pEi9XJ7RKg-->

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

$name_of_location=array();
$latitudine = array();
$longitudine=array();
$color=array();
$size=array();
$price=array();

while(($row = mysqli_fetch_array($result))) {
    $latitudine[] = $row['Latitude'];
    $longitudine[]= $row['Longitude'];
    $name_of_location[]=$row['Name'];
    $color[]=$row['Color'];
    $size[]=$row['size'];
    $price[]=$row['price'];
}

?>

<!DOCTYPE html>
<html>
<head>
        <title> MAP VIEW </title>
        <link rel="stylesheet" type="text/css" href="CSS2.css">
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

<div class="btn" style="margin-top: 530px;margin-right: 200px; float: right">
                    <button id="myBtn" style="width:100px; height: 40px">Adauga Hotel</button>  
</div>



<div id="myModal" class="modal" style="float:center">

<!-- Modal content -->
<div class="modal-content">
  <div class="modal-header">
    <span class="close">&times;</span>
    <h2>Formular adaugare informatii</h2>
  </div>
  <div class="modal-body">
  <div class="form-modal">
          <form class="login-form" method="POST" style="margin-top: 20px">

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
            <input type="text" name="color" placeholder="culoare" id="color" required/> <br><br>

            <label for="size">Marime cerc:  </label>
            <input type="number" name="marime" placeholder="marime" id="size" required/> <br><br>


            <label for="price">Pret/zi:   &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp </label>
            <input type="number" name="longitudine" placeholder="pret" id="price" required/> <br><br>
        </div>

        <div class="button-form" style="float: right;margin-top: 21px; margin-left: 300px ">
            <button style="width: 100px"> Submit</button>
         </div>   
          </form>
        </div>
  </div>
  <div class="modal-footer" style="margin-top: 140px">
  </div>
</div>

</div>

<!-- JS for modal form -->

<script>  process_modal_form(); </script>


<!-- JS for modal form -->
    
<div id="map" style="width:50%;height:550px; margin-top: 22px; margin-left:390px"></div>

<script>
function myMap() {

   // save php variables to js variables with json_encode //
     var lat_array = <?php echo json_encode($latitudine); ?>;
     var long_array = <?php echo json_encode($longitudine); ?>;
     var nof_array = <?php echo json_encode($name_of_location); ?>;
     var color_array = <?php echo json_encode($color); ?>;
     var size_array= <?php echo json_encode($size); ?>;
     var price_array=<?php echo json_encode($price); ?>;
    // save php variables to js variables with json_encode //

    var lat;
    var long;
    var nof;
    var color;


    var center_x_max;
    var center_x_min;
    var center_y_max;
    var center_y_min;

    center_x_max= Math.max.apply(Math, lat_array);
    center_x_min= Math.min.apply(Math, lat_array);

    center_y_max= Math.max.apply(Math, long_array);
    center_y_min= Math.min.apply(Math, long_array);


    var center_x = (center_x_max + center_x_min) /2;
    var center_y = (center_y_max + center_y_min) /2;

    var location;
    var infowindow = new google.maps.InfoWindow();


    var map = new google.maps.Map(document.getElementById('map'), {
    
      center: new google.maps.LatLng(center_x, center_y),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });
    

    var bounds = new google.maps.LatLngBounds();

    for (var i=0; i< lat_array.length; i++)
     {

   location = new google.maps.LatLng(lat_array[i], long_array[i]);
 
  var wellCircle = new google.maps.Circle({
        strokeColor: color_array[i],
        strokeOpacity: 1,
        strokeWeight: 2,
        fillColor: color_array[i],
        fillOpacity: 1,
        map: map,
        title: nof_array[i] + ' '+'<br>Pret: ' + price_array[i].toString() + ' lei',
        center: new google.maps.LatLng(lat_array[i], long_array[i]),
        radius: parseFloat(size_array[i])
    });


        bounds.extend(location);
        google.maps.event.addListener(wellCircle, 'click', function(e) {
                infowindow.setContent(this.title);
                infowindow.setPosition(this.getCenter());
                infowindow.open(map);
            });
    }

        map.fitBounds(bounds);


}
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCwKV83Ug8_e9RD0z53qbts1pEi9XJ7RKg&callback=myMap"></script>
<!--
To use this code on your website, get a free API key from Google.
Read more at: https://www.w3schools.com/graphics/google_maps_basic.asp
-->

</body>
</html>


