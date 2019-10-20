<?php
session_start();

if(isset($_SESSION['logged_in'])) { 
	session_destroy();
	$status = " by user";
	if ($_SESSION['expiryTime'] <= time()){
		$status = " by timer";
	}
	file_put_contents("/tmp/log", date('Y/m/d H:i:s ') . $_SESSION['username'] ." Logout" . $status . "\n" , FILE_APPEND);
	header("Location: login.php");
}
else {
	header("Location: login.php");
}
?>