<?php
include 'functions.php';
// set allowed file extensions:
$allowedExts = array("gif", "jpeg", "jpg", "png");

// single out filename and extension
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);

// Fetch associated user id
$uqID = $_REQUEST['id'];
echo "ID: ".$uqID."<br>";
echo "Filename before: ".$_FILES["file"]["name"]."<br>";
$_FILES["file"]["name"] = $uqID.".".$extension;
echo "Filename after: ".$_FILES["file"]["name"]."<br>";

// Check file type:
if ((($_FILES["file"]["type"] == "image/gif")
|| ($_FILES["file"]["type"] == "image/jpeg")
|| ($_FILES["file"]["type"] == "image/jpg")
|| ($_FILES["file"]["type"] == "image/pjpeg")
|| ($_FILES["file"]["type"] == "image/x-png")
|| ($_FILES["file"]["type"] == "image/png"))
&& ($_FILES["file"]["size"] < 200000)
&& in_array($extension, $allowedExts)) {
	if ($_FILES["file"]["error"] > 0) {
		echo "Error: " . $_FILES["file"]["error"] . "<br>";
	} else {
		// echo file info to screen
		echo "Upload: " . $_FILES["file"]["name"] . "<br>";
		echo "Type: " . $_FILES["file"]["type"] . "<br>";
		echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
		echo "Stored in: " . $_FILES["file"]["tmp_name"] . "<br>";

		// if a previous image exists, it must be removed:
		if (file_exists("../images/userimg/" . $_FILES["file"]["name"])) {	
      		echo $_FILES["file"]["name"] . " already exists. ";
      		//remove the file
    		unlink("../images/userimg/" . $_FILES["file"]["name"]); 
    	} 

    	// move new image from temp folder into the img folder
    	// keep URL updated (it may change if a file with different extension is chosen)
      	Update_ImgUrl($_FILES["file"]["name"], $uqID);
      	move_uploaded_file($_FILES["file"]["tmp_name"],
      		"../images/userimg/" . $_FILES["file"]["name"]);
      	echo "Stored in: " . "../images/userimg/" . $_FILES["file"]["name"];

      	// return to editProfile.php
    	header('Location: ../editProfile.php');
   
	}
} else { // file has wrong extension
	echo "Invalid file";

	// redirect to error page
	header("Location: ../error.php?errorNo=9");
}
?>