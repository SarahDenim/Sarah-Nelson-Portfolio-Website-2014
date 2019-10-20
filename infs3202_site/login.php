<?php

session_start();

if(isset($_SESSION['logged_in']))
{
	header("Location: index.php");
}

if (!empty($_POST['username']) && !empty($_POST['password'])) {

	$_SESSION['username'] = $_POST['username'];
	if ((trim($_POST['username']) === 'infs' || 'INFS') && trim($_POST['password']) === '3202') {
	$_SESSION['logged_in'] = true;
	file_put_contents("/tmp/log", date('Y/m/d H:i:s ') . $_SESSION['username'] ." Login" . "\n" , FILE_APPEND);

$selectedoption = $_POST['timer'];
$time = 30;

if ($selectedoption === 'value2') {
	$time =86400;
}



$_SESSION['timeValue'] = $time;

$_SESSION['expiryTime'] = time() + $time;

	header("Location: index.php");
	}
	else {
		$message = 'Incorrect username/password.';
	}
} 

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="style.css">
<script src="js/jquery-1.10.2.min.js"></script>
<title>Prac 6</title>
</head>
<body>
	<div id="left_col">
		<img src="img/logo.png" alt="logo" width="200">

	</div>
	<div id="right_col">

	<h2>Login page</h2>
	<p><?php echo $message; ?></p>
	<form action="" method="post">
		<div style="width: 200px;">
		<label for="username">Username:</label>
		<input type="text" name="username" id="username" maxlength="50">
		<label for="password">Password:</label>
		<input type="text" name="password" id="password" maxlength="50">
		<input type="submit" name="Submit" value="Submit">

	
	<p>Stay logged in for:</p>
	<select name="timer" >
		<option value="" disabled="disabled" selected="selected">Please select a time</option>
		<option value="value1">30 Sec</option>
		<option value="value2">1 Day</option>
	</select>
</div>
	</form>
	</div>
	

</body>
</html>