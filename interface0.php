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
</head>
<body>


<div class="header">
    <div class="title"> <span style="font-size: 24pt; font-weight: bold; color: White">HOTELS FROM BRASOV</span>

        <div id="login" class="loginDisplay"><?php echo "You are logged as: "; echo htmlspecialchars($_SESSION["id"]); ?>
        <br><br>
        <form method="post">
        <input type="submit" name="test" id="test" value="Logout" /><br/>
        </form>
        </div>
       
    </div>
</div>



    
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


