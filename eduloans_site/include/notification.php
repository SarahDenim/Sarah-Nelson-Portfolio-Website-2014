<?php

include_once 'dbconn.php';
if(session_id() == '' || !isset($_SESSION)) {
    	// session isn't started
    	session_start();
	}


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
			WHERE `uqid` = " . $uqid . " ORDER BY timestamp DESC limit 5;";
	$result = mysqli_query($link, $request) or die ("Search for notifications Failed:" . mysqli_error($link));
//echo $request;
	
	echo "<table>";
		echo "<tr>";
			echo "<th>";
				echo "Notifications";
				echo "<img ID='notifArrow' src='images/upArrow.png' width=20px/>";
			echo "</th>";
		echo "</tr>";
		if(mysqli_num_rows($result) > 0) {
			while ($arr = mysqli_fetch_array($result)) {
				//if ($arr['seen'] == 0) {
					echo "<tr onclick=\"viewNotification(" . $arr['loanid'] . ", " . $arr['id'] . ")\" style=\"cursor:pointer\">";
				//} else {
				//	echo "<tr onclick=\"viewNotification(" . $arr['loanid'] . ", " . $arr['id'] . ")\" style=\"cursor:pointer\">";
				//}
					if ($arr['seen'] == 0) {
						echo "<td style=\"background:#EB99FF;\" onmouseover=\"notificationHoverIn(this)\" onmouseout=\"notificationHoverOut(this)\">";
						echo "<b>";
							echo $arr['text'];
						echo "</b>";
						echo "</td>";
					} else {
						echo "<td>";
						echo $arr['text'];
						echo "</td>";
					}
			}
			echo "</tr>";
				echo "<tr>";
				echo "<td><a href=\"notificationList.php?\">See more...</a></td>";
				echo "</tr>";
		} else {
			echo "<td>No New Notifications</td>";
		}
	
	echo "</table>";

	
} else {
	echo "No Session";
}

?>