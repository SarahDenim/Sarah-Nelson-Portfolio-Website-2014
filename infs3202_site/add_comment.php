<?php

include('connectMySQL.php'); // make sure the path is correct
$db = new MySQLDatabase(); // create a Database object
$db->connect("infs", "3202", "sarahnel_deals");

$comment = json_decode($_GET['comment'], true);
$DID = $comment['DID'];
$name = $comment['name'];
$new_comment = $comment['comment'];


$sql = "INSERT INTO comments (DID, username, comment) VALUES ('$DID', '$name', '$new_comment')";
$result = mysql_query($sql);


$sql ="SELECT * FROM comments WHERE DID LIKE '%$DID%'";
$result = mysql_query($sql);
if(mysql_num_rows($result)!=0)
{
	while($row=mysql_fetch_array($result)){
		echo "<div>" . $row['username'] . ": " . $row['comment'] . "</div>";
	}
}
?>