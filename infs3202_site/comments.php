<?php

$did = $_GET['did'];
include('connectMySQL.php'); // make sure the path is correct
$db = new MySQLDatabase(); // create a Database object
$db->connect("infs", "3202", "sarahnel_deals");

$sql = "SELECT * FROM comments WHERE DID LIKE '%$did%'";
$result = mysql_query($sql);

if(mysql_num_rows($result)!=0)
{
	while($row=mysql_fetch_array($result)){
		echo "<div>" . $row['username'] . ": " . $row['comment'] . "</div>";
	}
}
?>