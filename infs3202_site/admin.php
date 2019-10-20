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
<script src="js/jquery-1.10.2.min.js"></script>
<title>Prac 5</title>

<script>

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

function popUp(x){
	window.open("form.php?id=" + x, "_blank", "scrollbars=1,resizable=1,height=420,width=450");
}

function removePopUp(x){
	if(window.confirm("Are you sure you want to delete this entry?")==true){
		window.location="remove.php?id=" + x;
	}
}

function addPopUp(){
	window.open("add.php", "_blank", "scrollbars=1,resizable=1,height=420,width=450");
}
	 
</script>
</head>
<body style="height: auto; width: auto;">
	<div id="container">
	<div id="left_col">
		<img src="img/logo.png" alt="logo" width="200">
		<a class="button" href='search.php'>Search</a>
		<a class="button" href='index.php'>Deal Page</a>
		<a class="button" href='logout.php'>Logout</a>
	</div>
	<div id="right_col" style="width: auto; height: auto;">

	<h2>Deal of the day</h2>

	<h3>Admin page</h3>
	
<?php
//database stuff
include('connectMySQL.php'); // make sure the path is correct
$db = new MySQLDatabase(); // create a Database object
$db->connect("infs", "3202", "sarahnel_deals");
// do some database jobs.

$query = "SELECT DID, name, category, price, due_time, location, image_path, description, reviews FROM deals";
$result = mysql_query($query);


print "<table id='database'>";
if($result){
	while($row = mysql_fetch_array($result)){
	print "<tr><td>{$row['DID']}</td><td>{$row['name']}</td>
		   <td>{$row['category']}</td><td>{$row['price']}</td><td>{$row['due_time']}</td>
		   <td>{$row['location']}</td><td><img src='{$row['image_path']}'></td><td>{$row['description']}</td>
		   <td>{$row['reviews']}</td><td><a href='javascript:;' class='edit' onclick = popUp({$row['DID']})>Edit</a></td><td><a href='javascript:;' class='edit' onclick = removePopUp({$row['DID']})>Remove</a></td></tr>";
}
print "</table>";
print "<a class='button' onclick = addPopUp()>Add</a>";
}
else{
die(mysql_error()); // useful for debugging
}

$db->disconnect(); // disconnect after use is a good habit
?>
	
</div>
</div>
</body>
</html>