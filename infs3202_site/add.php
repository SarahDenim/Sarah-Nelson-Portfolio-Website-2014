<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="style.css">
<script src="js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="js/util-functions.js"></script>
<script type="text/javascript" src="js/clear-default-text.js"></script>
<title>Add Form</title>

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

$db->disconnect();
?>
</head>

<body id="formstyle">
<form style="width: 400px" name="form" method="post" action="addstuff.php" onsubmit="return validateForm();">
<label>ID: </label><input type="text" name="DID" min="6" class="cleardefault">
<label>Name: </label><input type="text" id="name" name="name" min="6" class="cleardefault">
<label>Category: </label><input type="text" id="category" name="category" class="cleardefault">
<label>Price: </label><input type="text" name="price" class="cleardefault">
<label>Due Time: </label><input type="text" name="due_time" class="cleardefault">
<label>Location: </label><input type="text" name="location" class="cleardefault">
<label>Image path: </label><input type="text" name="image_path" class="cleardefault">
<label>Description: </label><input type="text" name="description" class="cleardefault">
<label>Review(s): </label><input type="text" name="reviews" class="cleardefault">
<input type="submit" value="submit">
</form>

</body>
</html>
