<?php

include_once 'dbconn.php';
if(session_id() == '' || !isset($_SESSION)) {
    	// session isn't started
    	session_start();
	}


if (isset($_SESSION['email'])) {
	$user_email = $_SESSION['email'];
	$request = "SELECT uqid
		FROM ProfileData
		WHERE email = '" . $user_email . "';";
	if (isset($_REQUEST['notification'])) {
		//session email check

		$result = mysqli_query($link, $request) or die ("Search for ID Failed:" . mysqli_error($link));
		$row = mysqli_fetch_array($result);
		$uqid = $row['uqid'];

		$update = "UPDATE Notifications SET seen = 1 
					WHERE id = " . $_REQUEST['notification'] . " AND uqid = " . $uqid;
		mysqli_query($link, $update) or die ("Search for ID Failed:" . mysqli_error($link));
	} else {
		echo "notification not specified";
	}
	
} else {
	echo "No Session";
}

?>