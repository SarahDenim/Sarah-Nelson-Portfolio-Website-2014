<?php
session_start();
//Logs out if past expiry time
if(!isset($_SESSION['expiryTime']) || time() > $_SESSION['expiryTime']){
	header('location: logout.php');
}
$did = $_GET['did'];
include('connectMySQL.php'); // make sure the path is correct
$db = new MySQLDatabase(); // create a Database object
$db->connect("infs", "3202", "sarahnel_deals");

$sql = "SELECT * FROM deals WHERE DID LIKE '%$did%'";
$result = mysql_query($sql);

if(mysql_num_rows($result)!=0)
{
	$row=mysql_fetch_array($result);
	$description = $row['description'];
	$lat = $row['lat'];
	$longitude = $row['longitude'];
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="style.css">
<script src="js/jquery-1.10.2.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAIX4AT2R2Ml42n6hCusQ25Pql3qQ0xUGk&sensor=false"></script>
<title>Prac 6</title>

<script>

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
		document.getElementById("display_comments").innerHTML=xmlhttp.responseText;
		}
}

xmlhttp.open("GET", "comments.php?did=" + <?php echo $_GET['did']; ?>, true);
xmlhttp.send();
}

function add_comment(){
var xmlhttp;
if (window.XMLHttpRequest){
	xmlhttp = new XMLHttpRequest();
}
else 
{
	xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
}

var new_comment = document.getElementById('new_comment').value;
var JSONObj = { "DID": <?php echo $_GET['did'] ?>,
				"name": "infs",
				"comment": new_comment};
JSONObj = JSON.stringify(JSONObj);

document.getElementById('new_comment').value = "";

xmlhttp.onreadystatechange=function()
	{
	if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		document.getElementById("display_comments").innerHTML=xmlhttp.responseText;
		}
	}

xmlhttp.open("GET", "add_comment.php?comment=" + JSONObj, true);
xmlhttp.send();
}



//Map
var map;
function initialize() {
var mapOptions = {
    zoom: 12
  };
  map = new google.maps.Map(document.getElementById('directions'),
      mapOptions);

  // Try HTML5 geolocation
  if(navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
    	var pos = new google.maps.LatLng(position.coords.latitude,
                                       position.coords.longitude);

    	map.setCenter(pos);


   		var directionsService = new google.maps.DirectionsService();
	
		var start = pos;
   		var end = new google.maps.LatLng(<?php echo "$lat , $longitude" ?>);

	    var directionsDisplay = new google.maps.DirectionsRenderer();// also, constructor can get "DirectionsRendererOptions" object
	    directionsDisplay.setMap(map); 

	    var request = {
	        origin : start,
	        destination : end,
	        travelMode : google.maps.TravelMode.DRIVING
	    };
	    
    directionsService.route(request, function(response, status) {
        if (status == google.maps.DirectionsStatus.OK) {
            directionsDisplay.setDirections(response);
        }
    });

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

function des_display(){
	$("#description").show();
	$("#comments").hide();
	$("#directions").hide();
}

function com_display(){
	$("#description").hide();
	$("#comments").show();
	$("#directions").hide();
	loadXMLDoc();
}

function dir_display(){
	$("#description").hide();
	$("#comments").hide();
	$("#directions").show();
	initialize();
}

function clear_text(){
	document.getElementById('new_comment').value = "";
}


</script>

</head>
<body>
	<div id="header"></div>
	<div id="left_col">
		<img src="img/logo.png" alt="logo" width="200">
		<a class="button" href="logout.php">Logout</a>
		<a class="button" href="index.php">Back</a>
	</div>
	
	<div id="right_col">
	
	<h2>Deal of the day</h2>
	<h3>Today's sales</h3>

	<?php echo "<img src=" . $row['image_path'] . "><br><br><br><br><br><br><br><br>"; ?>

	<div id="tabs">
		<a id="tab" onclick="des_display()">Description</a>
		<a id="tab" onclick="com_display()">Comments</a>	
		<a id="Dtab" onclick="dir_display()">Directions</a>	
	</div>

	<div id="description">
		<p><?php echo $description; ?></p>
	</div>

	<div id="comments">
		<div id="display_comments"></div>
		<textarea id="new_comment"></textarea><br>
		<button onclick="add_comment()">Add Comment</button>
		<button onclick="clear_text()">Cancel</button>
	</div>

	<div id="directions">

	</div>
	
	</div>
	
</body>
</html>