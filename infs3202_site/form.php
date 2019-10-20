<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="style.css">
<script src="js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="js/util-functions.js"></script>
<script type="text/javascript" src="js/clear-default-text.js"></script>
<title>Edit Form</title>

<script>
function validateForm(){
	var x=document.getElementById("name").value;
	var y=document.getElementById("category").value;
	
	if(x=="" || x==null){
		alert("Please enter a name");
		return false;
		}
	if(x.length < 5)
		{
		alert("Name must be at least 6 letters");
		return false;
		}
	if(y=="" || y==null){
		alert("Please enter a category");
		return false;
		}
	return true;
}
</script>

<?php
//database stuff
include('connectMySQL.php'); // make sure the path is correct
$db = new MySQLDatabase(); // create a Database object
$db->connect("infs", "3202", "sarahnel_deals");
// do some database jobs.
$row_id = $_GET['id'];
$query = "SELECT DID, name, category, price, due_time, location, image_path, description, reviews FROM deals WHERE DID = $row_id";
$result = mysql_query($query);

if($result){
	$values = mysql_fetch_array($result);
}
else{
	die(mysql_error()); // useful for debugging
}

$db->disconnect(); // disconnect after use is a good habit
?>
</head>

<body id="formstyle">
<form style="width: 400px" name="form" method="post" action="formstuff.php" onsubmit="return validateForm();">
<label>ID: </label><input type="text" name="DID" min="6" value="<?php echo $values['DID']; ?>" class="cleardefault">
<label>Name: </label><input type="text" id="name" name="name" min="6" value="<?php echo $values['name']; ?>" class="cleardefault">
<label>Category: </label><input type="text" id="category" name="category" value="<?php echo $values['category']; ?>" class="cleardefault">
<label>Price: </label><input type="text" name="price" value="<?php echo $values['price']; ?>" class="cleardefault">
<label>Due Time: </label><input type="text" name="due_time" value="<?php echo $values['due_time']; ?>" class="cleardefault">
<label>Location: </label><input type="text" name="location" value="<?php echo $values['location']; ?>" class="cleardefault">
<label>Image path: </label><input type="text" name="image_path" value="<?php echo $values['image_path']; ?>" class="cleardefault">
<label>Description: </label><input type="text" name="description" value="<?php echo $values['description']; ?>" class="cleardefault">
<label>Review(s): </label><input type="text" name="reviews" value="<?php echo $values['reviews']; ?>" class="cleardefault">
<input type="submit" value="submit">
</form>

</body>
</html>
