<?php
$name = $_GET['name'];
$price = $_GET['price'];
$location = $_GET['location'];
include('connectMySQL.php'); // make sure the path is correct
$db = new MySQLDatabase(); // create a Database object
$db->connect("infs", "3202", "sarahnel_deals");

$sql = "SELECT * FROM deals WHERE (name LIKE '%$name%' AND price LIKE '%$price%' AND location LIKE '%$location%')";
$result = mysql_query($sql);
if(mysql_num_rows($result) !=0){

echo "<table id='search_table' border='1'>
<tr>
<th>ID</th>
<th>Name</th>
<th>Category</th>
<th>Price</th>
<th>Location</th>
<th>Latitude</th>
<th>Longitude</th>
<th>Image</th>
<th>Description</th>
<th>Reviews</th>
</tr>";

while($row=mysql_fetch_array($result)){
	echo "<tr>";
	echo "<td>" . $row['DID'] . "</td>";
	echo "<td>" . $row['name'] . "</td>";
	echo "<td>" . $row['category'] . "</td>";
	echo "<td>" . $row['price'] . "</td>";
	echo "<td>" . $row['location'] . "</td>";
	echo "<td>" . $row['lat'] . "</td>";
	echo "<td>" . $row['longitude'] . "</td>";
	echo "<td><img src=" . $row['image_path'] . "></td>";
	echo "<td>" . $row['description'] . "</td>";
	echo "<td>" . $row['reviews'] . "</td>";
	echo "</tr>";
}
echo "</table>";

}

else {
	echo "Sorry, no results matching your search were found";
}
?>