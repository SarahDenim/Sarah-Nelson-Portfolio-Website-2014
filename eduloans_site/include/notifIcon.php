<?php

include_once 'dbconn.php';
session_start();

?>
<div id="notification-icon" onclick="toggleVis()" style="position:relative">

<?php 
if (isset($_SESSION['email'])) {
	

	//session email check
	$user_email = $_SESSION['email'];

	$request = "SELECT uqid
			FROM ProfileData
			WHERE email = '" . $user_email . "';";


	$result = mysqli_query($link, $request) or die ("Search for ID Failed:" . mysqli_error($link));
	$row = mysqli_fetch_array($result);
	$uqid = $row['uqid'];
	$request = "SELECT *
			FROM Notifications
			WHERE `uqid` = " . $uqid . " AND `seen` = 0;";
	$result = mysqli_query($link, $request) or die ("Search for notifications Failed:" . mysqli_error($link));
//echo $request;
	if(mysqli_num_rows($result) > 0) {
		//image used from https://www.iconfinder.com/icons/46157/open_source_icon
		echo "<img id=\"notificationICON\" src=\"images/notifL.gif\" />";
		echo "<div id=\"messageCount\" style=\"padding-left:1px;padding-right:1px;color:white;position:absolute;right:0px;top:0px;background-color:red;\"><b>";
			echo mysqli_num_rows($result);
		echo "</b></div>";

	} else {
		//image used from https://www.iconfinder.com/icons/34820/open_source_icon
		echo "<img id=\"notificationICON\" src=\"images/notifU.gif\" />";
	}
	
} else {
	echo "No Session";
}


?>

</div>
<div id="listNotifications" style="visibility:hidden;position:absolute;z-index:10;">
</div>