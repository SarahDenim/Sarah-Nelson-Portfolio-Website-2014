<?php 
	include 'include/head.php'; 
	include 'include/topnav.php'; 
	include 'include/dbconn.php';
	include 'include/functions.php';
	
	$error = $_REQUEST['errorNo'];
	
?>

<div class="container">
<?php
	switch ($error) {
		case 4:
			echo "Error 4: The requested user profile does not exist<br>";
			break;
		case 5:
			echo "Error 5: The requested loan does not exist.<br>";
			break;
		case 6:
			echo "Error 6: This loan has already been marked as completed.<br>";
			break;
		case 7:
			echo "Error 7: This user is not authorized to view this page.<br>";
			break;
		case 8:
			echo "Error 8: Insufficient data passed through. Please make sure you filled out all required fields.<br>";
			break;
		case 9:
			echo "Error 9: Invalid file selected. Please try another file.<br>";
			break;
		default:
			echo "Error: Not specified/expected. Please tell Bastian how this happened.<br>";
	}
?>

<div class="clear"></div>
</div><!-- /container -->


<?php include 'include/footer.php'; ?>