<?php 
include 'include/dbconn.php';
include 'include/head.php'; 
include 'include/topnav.php'; 
include 'include/functions.php';

$err = array();
Check_Login();
$row = fetch_current_user();
$imgUrl = $row['imgUrl'];
if(isset($_POST['submit'])) {
	if($_POST['submit'] == 'Submit') {
    	foreach($_POST as $key => $value) {
    		$data[$key] = filter($value);
    	}
	}

	// checks
	// - passwords match

	//echo 'studentno: '.$data['studentNo']."<br>";

	$sha1pass = PwdHash($data['pwd1']);

  	if(empty($err)) {		// there are no errors, registration success!
  		echo 'No Errors';
  

  		$sql_update_profile = "UPDATE `ProfileData`
  			SET phone=$data[phone], degree=$data[degree], semester=$data[semester], employment=$data[employment], jobTitle='$data[incomeSource]', income=$data[income], rent=$data[rent], aboutMe='$data[aboutMe]'
  			WHERE uqid=$row[uqid];";
  		echo $sql_update_profile;
  		
  		mysqli_query($link, $sql_update_profile) or die("Insertion Failed:" . mysqli_error($link));
  		mysqli_close($link); 
  		
  		echo "Complete.";
  		header("Location: profile.php?id=0");
  		//login_user($data['email'], $data['pwd1']) ;
  	} else {
  		echo "An error occurred.";
  	}
}



?>

<div class="container">
	<div class="registration">
	<!-- ADD CONTENT HERE -->
	<h2>EDIT PROFILE</h2>
	<p>Please keep your profile information up-to-date.</p>
	<p>Also note that the information you provide is what others will 
	use to decide whether or not to lend to or from you.</p>
	<p>While it is not necessary to fill out all the fields,
	a lack of information might deter others from lending to or from you.
	</p>
	<div class="loanBoxLeft fixedHeight">
	<div id="editImage" class="profileImage" style="float:none;">
	<?php echo "<img src='$imgUrl'>"; ?>
		
	</div>

	<form enctype="multipart/form-data" action="include/upload_file.php?id=<?php echo $row['uqid'];?>" method="POST">
			<input name="file" type="file" id="file">
			<input type="submit" value="Upload Image">
		</form>
	<form action="editProfile.php" method="post" name="editForm" id="inputs"><!-- id="inputs"> -->
	</div>

	<div class="loanBoxLeft fixedHeight">
	<h3>Financial Situation</h3>
		<label for="employment">Employment Status:</label><br>
		<select name="employment">


			<option value="0" <?php if ($row['employment'] == 0) echo 'selected="selected"';?>>I am currently unemployed.</option>
			<option value="1" <?php if ($row['employment'] == 1) echo 'selected="selected"';?>>I am casually employed.</option>
			<option value="2" <?php if ($row['employment'] == 2) echo 'selected="selected"';?>>I have part-time employment.</option>
			<option value="3" <?php if ($row['employment'] == 3) echo 'selected="selected"';?>>I have full-time employment.</option>
		</select>
		<br>

		<label for="incomeSource">Primary source of income:</label><br>
		<input type="text" id="incomeSource" name="incomeSource" value="<?php if (isset($row['jobTitle'])) echo $row['jobTitle'];?>">
		<br>

		<label for="income">Weekly income:</label><br>
		<input type="text" id="income" name="income" value=<?php if (isset($row['income'])) echo $row['income'];?>>
		<br>
	</div>
	
		<div class="loanBoxLeft fixedHeight">
	<h3>Additional Information</h3>
		<label for="degree">Degree:</label><br>
		<select name="degree">
			<option value="0" <?php if ($row['degree'] == 0) echo 'selected="selected"';?>>Bachelor of Business Management</option>degree
			<option value="1" <?php if ($row['degree'] == 1) echo 'selected="selected"';?>>Bachelor of Information Technology</option>
			<option value="2" <?php if ($row['degree'] == 2) echo 'selected="selected"';?>>Bachelor of Arts</option>
			<option value="3" <?php if ($row['degree'] == 3) echo 'selected="selected"';?>>Bachelor of Engineering</option>
			<option value="4" <?php if ($row['degree'] == 4) echo 'selected="selected"';?>>Bachelor of Commerce</option>
			<option value="5" <?php if ($row['degree'] == 5) echo 'selected="selected"';?>>Bachelor of Science</option>
		</select>
		<br>

		<label for="semester">Semester:</label><br>
		<input type="text" id="semester" name="semester" value="<?php if (isset($row['semester'])) echo $row['semester'];?>">
		<br>

		<label for="rent">Renting situation:</label><br>
		<select name="rent">
			<option value="0" <?php if ($row['rent'] == 0) echo 'selected="selected"';?>>I am paying rent.</option>
			<option value="1" <?php if ($row['rent'] == 1) echo 'selected="selected"';?>>I am not paying rent.</option>
		</select>
		<br>

		<label for="aboutMe">About me (300 characters max):</label><br>
		<textarea id="aboutText" name="aboutMe" placeholder="Leave a comment about yourself here..."><?php if (isset($row['aboutMe'])) echo $row['aboutMe'];?></textarea><br>

	</div>
	<div class="loanBoxLeft fixedHeight">
	<h3>Contact</h3>

		<label for="phone">Mobile #:</label><br>
		<input type="tel" id="phone" name="phone" placeholder="i.e. (04)07873411" value=<?php if (isset($row['phone'])) echo $row['phone'];?>>
		<br>
	
		<button name ="submit" type="submit" id="submit" value="Submit">Submit</button>
	</form>
</div>
	
</div>

<!-- FINISH CONTENT HERE -->
<div class="clear"></div>
</div>


<?php  include 'include/footer.php'; ?>