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


/* Verify global variable to see if the person that want to access the page is logged in 
   That prevents the error when someone just copy the link into the browser and wants to access the page.*/

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: main.php");
    exit;
}

$sql = "select * from locations";

$result = mysqli_query($link,$sql);

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
}

?>

<!DOCTYPE html>
<html>
<head>
        <title> MAP VIEW </title>
        <link rel="stylesheet" type="text/css" href="CSS2.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  
</head>
<body>


<div class="header">
    <div class="title"> <span style="font-size: 24pt; font-weight: bold; color: White;" >HOTELS FROM BRASOV</span>
    </div>
</div>

  
  <div class="btn-group" style = "margin-top: 20px;margin-left:1050px float: float">
  <button onclick="myMap('green',5)" type="button" class="btn btn-primary">5 stele</button>
  <button onclick="myMap('red',4)" type="button" class="btn btn-primary">4 stele</button>
  <button onclick="myMap('purple',3)" type="button" class="btn btn-primary">3 stele</button>
  <button onclick="myMap('blue',2)" type="button" class="btn btn-primary">2 stele</button>
  <button onclick="myMap('others',1)" type="button" class="btn btn-primary">1 stele</button>
  <button onclick="myMap('',6)" type="button" class="btn btn-primary">Toate hotelurile</button>
</div>
<script>

</script>
<button type="button" class="btn btn-secondary" data-container="body" data-toggle="popover" data-placement="bottom" title="CALITATEA SERVICIILOR" 
        style = "margin-top: 20px; margin-left:150px; float: left"
        data-content="<div>
         <b>Verde</b>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp-&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp5 stele <br>
         <b>Rosu</b>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp-&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp4 stele <br>
         <b>Mov</b>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp-&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp3 stele <br>
         <b>Albastru</b>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp-&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp2 stele <br>
         <b>Altele</b>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp-&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp1 stele <br>
        
        
        </div>" 
        data-html="true">
  Legenda
</button>
<!-- <p id="demo"></p> -->
<div id="map" style="width:50%;height:550px; margin-top: 15px; margin-left:390px"></div>

<script>
function myMap(stars,flag) {
    // document.getElementById("demo").innerHTML = "Welcome " + flag ;
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
    /* Search for max values of latitude and longitude (marginal points) */
    center_x_max= Math.max.apply(Math, lat_array);
    center_x_min= Math.min.apply(Math, lat_array);

    center_y_max= Math.max.apply(Math, long_array);
    center_y_min= Math.min.apply(Math, long_array);

    /* Choose the most optimal point to be the center of the map */
    var center_x = (center_x_max + center_x_min) /2;
    var center_y = (center_y_max + center_y_min) /2;

    var location;
    var zoom_bounds;
    var infowindow = new google.maps.InfoWindow();


    var map = new google.maps.Map(document.getElementById('map'), {
     
      center: new google.maps.LatLng(center_x, center_y),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });
    

    var bounds = new google.maps.LatLngBounds();

    /* Loop through every latitude and longitude value */
    for (var i=0; i< lat_array.length; i++)
     {
        if (stars == color_array[i] || flag == undefined || flag == 6 || ((flag == 1) && color_array[i] != 'green' && color_array[i] != 'blue' &&
                                                                           color_array[i] != 'red' && color_array[i] != 'purple')){
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
}
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCwKV83Ug8_e9RD0z53qbts1pEi9XJ7RKg&callback=myMap"></script>

</body>
</html>

<!-- Legend button script -->
<script>
$(function () {
  $('[data-toggle="popover"]').popover()
})
</script>




