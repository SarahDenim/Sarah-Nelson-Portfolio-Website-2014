<?php include 'include/head.php'; include 'include/topnav.php'; ?>

<div class="container">
<!-- ADD CONTENT HERE -->

<?php
include "include/functions.php";
if (isset($_SESSION['email'])) {

	if (isset($_REQUEST['count'])) {
		$count = filter($_REQUEST['count']);
	} else {
		$count = 15;
	}

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
			WHERE `uqid` = " . $uqid . " ORDER BY timestamp DESC limit " . $count . ";";
	$result = mysqli_query($link, $request) or die ("Search for notifications Failed:" . mysqli_error($link));
//echo $request;
	if(mysqli_num_rows($result) > 0) {
		echo "<table>";
			echo "<tr>";
				echo "<th>";
					echo "Notifications";
				echo "</th>";
			echo "</tr>";

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
				echo "</tr>";
			}
			if (mysqli_num_rows($result) >= $count) {
				echo "<tr><td><a href=\"notificationList.php?count=" . ($count + 15) ."\"> See more...</a></td></tr>";
			}
		echo "</table>";
	} else {
		echo "No New Notifications";
	}
	
} else {
	echo "No Session";
}

?>

<div class="clear"></div>
</div>


<?php include 'include/footer.php'; ?>