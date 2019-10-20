<?php
session_start();
//Logs out if past expiry time
if(!isset($_SESSION['expiryTime']) || time() > $_SESSION['expiryTime']){
	header('location: logout.php');
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="style.css">
<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAIX4AT2R2Ml42n6hCusQ25Pql3qQ0xUGk&sensor=false"></script>
<title>Prac 5</title>

<script>
//Map
var map;
var markers = [];
function initialize() {
var mapOptions = {
    zoom: 12
  };
  map = new google.maps.Map(document.getElementById('map-canvas'),
      mapOptions);

  // Try HTML5 geolocation
  if(navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      var pos = new google.maps.LatLng(position.coords.latitude,
                                       position.coords.longitude);

      // var infowindow = new google.maps.InfoWindow({
      //   map: map,
      //   position: pos,
      //   content: 'Location found using HTML5.'
      // });

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

function getResults(){
	var table = document.getElementById("search_table");
	clearMarkers();
	for (var i = 1; i < table.rows.length; i++){
		addMarker(table.rows[i].cells[5].innerHTML, table.rows[i].cells[6].innerHTML);
	}
}

function addMarker(lat, longitude){
var position = new google.maps.LatLng(lat,longitude);
var marker = new google.maps.Marker({
	position: position,
	map: map
})
map.setCenter(position);
markers.push(marker);
}

function setAllMap(map){
	for (var i=0; i < markers.length; i++){
		markers[i].setMap(map);
	}
}

function clearMarkers(){
	setAllMap(null);
}

function showMarkers(){
	setAllMap(map);
}

function deleteMarkers(){
	clearMarkers();
	markers = [];
}

google.maps.event.addDomListener(window, 'load', initialize);



//Timer Stuff
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
	document.title = "Prac 5 - Time Out " + h + ":" + m + ":" + s2;
};

function loadXMLDoc(){
var xmlhttp;
if (window.XMLHttpRequest){
	xmlhttp = new XMLHttpRequest();
}
else 
{
	xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
}

xmlhttp.onreadystatechange=function()
	{
	if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		document.getElementById("search_results").innerHTML=xmlhttp.responseText;
		getResults();
		}
}

var name = document.getElementById('name').value;
var price = document.getElementById('price').value;
var location = document.getElementById('location').value;

if(!name && !price && !location){
	clearMarkers();
	document.getElementById("search_results").innerHTML="Please enter a search query";
	return;
}

xmlhttp.open("GET", "search_results.php?name="+name+"&price="+price+"&location="+location, true);
xmlhttp.send();
}



</script>

</head>

<body style="width: 1040px">
	<div id="container">
	<div id="left_col">
		<img src="img/logo.png" alt="logo" width="200">
		<a class="button" href="admin.php">Admin Page</a>
		<a class="button" href='index.php'>Deal Page</a>
		<a class="button" href='logout.php'>Logout</a>
	</div>
	<div id="right_col" style="width: 800px">

	<h2>Deal of the day</h2>

	<h3>Search page</h3>
	<div id="map-canvas"></div>
	<div style="width: 200px"> 
	<label>Name: </label><input type="text" id="name"><br>
	<label>Price: </label><input type="text" id="price"><br>
	<label>Location: </label><input type="text" id="location"><br>
	<button id="submit" onclick="loadXMLDoc()">Search</button>
	</div>

<div id="search_results"></div>

</div>
</div>

</body>
</html>