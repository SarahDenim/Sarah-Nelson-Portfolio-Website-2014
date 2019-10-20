<?php
session_start();
//Logs out if past expiry time
if(!isset($_SESSION['expiryTime']) || time() > $_SESSION['expiryTime']){
	header('location: logout.php');
}

include('connectMySQL.php'); // make sure the path is correct
$db = new MySQLDatabase(); // create a Database object
$db->connect("infs", "3202", "sarahnel_deals");

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
<link rel="stylesheet" type="text/css" href="style.css">
<script src="js/jquery-1.10.2.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAIX4AT2R2Ml42n6hCusQ25Pql3qQ0xUGk&sensor=false"></script>
<title>Prac 6</title>
<script>
//Map
var map;
function initialize() {
var mapOptions = {
    zoom: 12
  };
  map = new google.maps.Map(document.getElementById('map-canvas'),
      mapOptions);
  currentMarker = new google.maps.Marker({
  	position: null
  })

  // Try HTML5 geolocation
  if(navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      var pos = new google.maps.LatLng(position.coords.latitude,
                                       position.coords.longitude);

      map.setCenter(pos);
    }, function() {
      handleNoGeolocation(true);
    });
  } else {
    // Browser doesn't support Geolocation
    handleNoGeolocation(false);
  }
}

function handleNoGeolocation(errorFlag) {
  if (errorFlag) {
    var content = 'Error: The Geolocation service failed.';
  } else {
    var content = 'Error: Your browser doesn\'t support geolocation.';
  }

  var options = {
    map: map,
    position: new google.maps.LatLng(60, 105),
    content: content
  };

  var infowindow = new google.maps.InfoWindow(options);
  map.setCenter(options.position);
}

google.maps.event.addDomListener(window, 'load', initialize);

//Add Marker
function addMarker(lat, longitude){
var position = new google.maps.LatLng(lat,longitude);
map.setCenter(position);
currentMarker.setMap(map);
currentMarker.setPosition(position);
}


function myFunction(x,y){
var myLatlng = new google.maps.LatLng(-27.4702795,153.023036);
var mapOptions = {
    zoom: 12,
    center: myLatlng
}

var map = new google.maps.Map(document.getElementById('map-canvas'),
      mapOptions);

var marker = new google.maps.Marker({
      position: new google.maps.LatLng(x,y),
      map: map,
      title: 'Hello World!'
  });
}


//Title timer
var s = <?php echo $_SESSION['timeValue']; ?>;

function pad(n) {
	return (n < 10) ? ("0" + n ) : n;
}

var a = function(){
	s--;

	if(s==0){window.location='logout.php';}

	var h = Math.floor(s/3600);
	var s2 = s - (h * 3600);

	var m = Math.floor(s2/60);
	
	s2 = s2 - (m * 60);

	h = pad(h);
	m = pad(m);
	s2 = pad(s2);
	document.title = "Prac 6 - Time Out " + h + ":" + m + ":" + s2;
};


setInterval(a,1000);

var d1 = Math.random() * 10000;

var dealExpiry = function(){
	d1--;

	if(Math.floor(d1)==0){
		document.getElementById("deal1").style.display = 'none';
	}

	var h = Math.floor(d1/3600);
	var s3 = d1 - (h * 3600);

	var m = Math.floor(s3/60);
	
	s3 = Math.floor(s3 - (m * 60));

	h = pad(h);
	m = pad(m);
	s3 = pad(s3);

	document.getElementById("deal1timer").innerHTML = h + ":" + m + ":" + s3;
}

setInterval(dealExpiry, 1000);


var d2 = Math.random() * 1000;

var dealExpiry2 = function(){
	d2--;

	if(Math.floor(d2)==0){
		document.getElementById('deal2').style.display = 'none';
	}

	var h = Math.floor(d2/3600);
	var s3 = d2 - (h * 3600);

	var m = Math.floor(s3/60);
	
	s3 = Math.floor(s3 - (m * 60));

	h = pad(h);
	m = pad(m);
	s3 = pad(s3);

	document.getElementById("deal2timer").innerHTML = h + ":" + m + ":" + s3;
}

setInterval(dealExpiry2, 1000);


</script>
</head>



<body>
	<div id="container">
	<div id="left_col">
		<img src="img/logo.png" alt="logo" width="200">
		<a class="button" href='search.php'>Search</a>
		<a class="button" href="admin.php">Admin Page</a>
		<a class="button" href='logout.php'>Logout</a>
	</div>
	<div id="right_col">

	<h2>Deal of the day</h2>

	<h3>Today's sales</h3>

	<?php
	$sql = "SELECT lat, longitude FROM deals WHERE DID = 1"; 
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
	$lat = $row['lat'];
	$longitude = $row['longitude'];
	?>

	<div id="deal1">
	<p>HUGE SELECTION OF NIXON WATCHES! ALL 59.95!</p><div id="deal1timer"></div>
	<a href ="detail.php?did=1"><img src="img/nixon.png" alt="Nixon" width="300"></a>
	<?php echo "<button id=\"location\" onclick=\"addMarker($lat, $longitude)\">Location</button>"; ?>
	</div>

	<?php
	$sql = "SELECT lat, longitude FROM deals WHERE DID = 2"; 
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
	$lat = $row['lat'];
	$longitude = $row['longitude'];
	?>

	<div id="deal2">
	<p class = "promotion">ELECTRIC POLARISED SUNNIES! ALL 59.95!</p><div id="deal2timer"></div> 
	<a href ="detail.php?did=2"><img src="img/sunglasses.png" alt="Sunglasses" width="300"></a>
	<?php echo "<button id=\"location\" onclick=\"addMarker($lat, $longitude)\">Location</button>"; ?>
	</div>

	<?php
	$sql = "SELECT lat, longitude FROM deals WHERE DID = 3"; 
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
	$lat = $row['lat'];
	$longitude = $row['longitude'];
	?>

	<div id="deal3">
	<p class = "promotion">70s HAT</p><div id="deal2timer"></div> 
	<a href ="detail.php?did=3"><img src="img/70s-hat.jpg" alt="Sunglasses" width="300"></a>
	<?php echo "<button id=\"location\" onclick=\"addMarker($lat, $longitude)\">Location</button>"; ?>
	</div>
	
	<div id="map-canvas"></div>

	</div>
</div>
	

</body>
</html>