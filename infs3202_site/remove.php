<?php
//database stuff
include('connectMySQL.php'); // make sure the path is correct
$db = new MySQLDatabase(); // create a Database object
$db->connect("infs", "3202", "sarahnel_deals");
// do some database jobs.
$row_id = $_GET['id'];
$query = "DELETE FROM deals WHERE DID = $row_id";
$result = mysql_query($query);
if (!$result){
die(mysql_error()); // useful for debugging
}

$db->disconnect(); // disconnect after use is a good habit

header("Location: admin.php");
?>