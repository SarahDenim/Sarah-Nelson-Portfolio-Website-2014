<?php

//database stuff
include('connectMySQL.php'); // make sure the path is correct
$db = new MySQLDatabase(); // create a Database object
$db->connect("infs", "3202", "sarahnel_deals");

$DID = $_POST["DID"];
$name = $_POST["name"];
$category = $_POST["category"];
$price = $_POST["price"];
$due_time = $_POST["due_time"];
$location = $_POST["location"];
$image_path = $_POST["image_path"];
$description = $_POST["description"];
$reviews = $_POST["reviews"];

$sql = "INSERT INTO deals VALUES ('$DID', '$name', '$category', '$price', '$due_time', 
	   '$location', '$image_path', '$description', '$reviews')";

$result = mysql_query($sql);
if(! $result)
{
die('Could not enter data: ' . mysql_error());
}

$db->disconnect(); // disconnect after use is a good habit

?>

<script>
window.close();
window.opener.location.reload();
</script>