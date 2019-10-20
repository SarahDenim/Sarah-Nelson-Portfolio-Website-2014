<?php 
include 'include/dbconn.php';
include 'include/head.php'; 
include 'include/topnav.php'; 
include 'include/functions.php';

$err = 0;

if(isset($_POST['submit'])) {
	if($_POST['submit'] == 'Submit') {
    	foreach($_POST as $key => $value) {
    		$data[$key] = filter($value);
    	}
	}

	// checks
	// - passwords match
	// - required fields


	$sha1pass = PwdHash($data['pwd1']);

  	if($err == 0) {		// there are no errors, registration success!
  		echo 'No Errors';
  		$date = date('Y-m-d');
		$sql_insert_login = "INSERT into `LoginData`
  			(`uqid`, `pwd`)
  			VALUES
  			('$data[studentNo]', '$sha1pass')
  			";

  		$sql_insert_profile = "INSERT into `ProfileData`
  			(`uqid`, `firstname`, `surname`, `email`, `phone`, `degree`, `semester`, `employment`, `jobTitle`, `income`, `rent`, `regDate`, `aboutMe`)
  			VALUES
  			('$data[studentNo]', '$data[name]', '$data[surname]', '$data[email]', '$data[phone]', '$data[degree]', '$data[semester]', '$data[employment]', '$data[incomeSource]', '$data[income]', '$data[rent]', '$date', '$data[aboutMe]')
  			";
  		echo $sql_insert;
  		//mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
  		mysqli_query($link, $sql_insert_login) or die("Insertion Failed:" . mysqli_error($link));
  		mysqli_query($link, $sql_insert_profile) or die("Insertion Failed:" . mysqli_error($link));
  		mysqli_close($link); 
  		
  		echo "Complete.";
  		login_user($data['email'], $data['pwd1']) ;
  		
  		
  	} else {
  		echo "An error occurred.";
  	}
}



?>

<div class="container">
	<div class="registration">
	<!-- ADD CONTENT HERE -->
	<h1>Registration</h1>
	<p>Registering with eduLoans is as easy as filling out the form below.</p>
	<p>Please note that the information you provide is what others will 
	use to decide whether or not to lend to or from you.</p>
	<p>While it is not necessary to fill out all the fields,
	a lack of information might deter others from lending to or from you.
	</p><br>

	<form action="register.php" method="post" name="regForm" id="inputs"><!-- id="regForm"> -->
	<h3>Identity</h3>
		<label for="name">First Name* :</label><br>
		<input type="text" id="name" name="name" placeholder="i.e. John - required" title="Please use a Captial Letter." required pattern="[A-Z][a-z]{1,19}">
		<br>

		<label for="surname">Last Name* :</label><br>
		<input type="text" id="surname" name="surname" placeholder="i.e. Smith - required" title="Please use a Captial Letter." required pattern="[A-Z][a-z]{1,19}">
		<br>

		<label for="studentNo">UQ Student Number* :</label><br>
		<input type="text" id="studentNo" name="studentNo" placeholder="i.e. 41622680 - required" maxlength="8" title="Please enter your 8 student numbers." required pattern="[0-9]{8}">
		<br>
		<br>
		<h5>Please provide a scanned copy of your uq student ID. (File uploads currently unavailable)</h5>
		<img src='images/uqid.jpg'>
		<hr>
		<!-- ID end-->

	<h3>Financial Situation</h3>
		<label for="employment">Employment Status:</label><br>
		<select name="employment">
			<option value="0">I am currently unemployed.</option>
			<option value="1">I am casually employed.</option>
			<option value="2">I have part-time employment.</option>
			<option value="3">I have full-time employment.</option>
		</select>
		<br>

		<label for="incomeSource">Primary source of income:</label><br>
		<input type="text" id="incomeSource" name="incomeSource" placeholder="i.e. Pizza Delivery">
		<br>

		<label for="income">Weekly income:</label><br>
		<input type="text" id="income" name="income" placeholder="i.e. 400">
		<br>
		<hr>

	<h3>Additional Information</h3>
		<label for="degree">Degree:</label><br>
		<select name="degree">
			<option value="0">Bachelor of Business Management</option>
			<option value="1">Bachelor of Information Technology</option>
			<option value="2">Bachelor of Arts</option>
			<option value="3">Bachelor of Engineering</option>
			<option value="4">Bachelor of Commerce</option>
			<option value="5">Bachelor of Science</option>
		</select>
		<br>

		<label for="semester">Semester:</label><br>
		<input type="text" id="semester" name="semester" placeholder="i.e. 5">
		<br>

		<label for="rent">Renting situation:</label><br>
		<select name="rent">
			<option value="0">I am paying rent.</option>
			<option value="1">I am not paying rent.</option>
		</select>
		<br>

		<label for="aboutMe">About me (300 characters max):</label><br>
		<textarea name="aboutMe" placeholder="Leave a comment about yourself here..."></textarea><br>

		<hr>

	<h3>Contact</h3>
		<label for="email">Email address* :</label><br>
		<input type="email" id="email" name="email" placeholder="i.e. john91@gmail.com - required" required>
		<br>

		<label for="phone">Mobile # :</label><br>
		<input id="phone" name="phone" placeholder="i.e. (04)07873411">
		<br>
		<hr>
	<h3>Password</h3>
	<script type="text/javascript">
	
	function validatePass(pwd1, pwd2) {
		if (pwd1.value != pwd2.value || pwd1.value == '' || pwd2.value == '') {
			pwd2.setCustomValidity('Passwords do not match');
		} else {
			pwd2.setCustomValidity('');
		}
	}
	

	</script>
	
	
	<label for="password">Password* :</label><br>
	<!--<input type='password' id='pwd1' pattern=".{6,}"  title="Minimum 5 characters" required><br>
	<label for="password">Confirm Password* :</label><br>
	<input type='password' onfocus="validatePass(document.getElementById('pwd1'), this);" oninput="validatePass(document.getElementById('pwd1'), this);" required> 
	</br>-->
	

		<input type="password" id="pwd1" name="pwd1" title="Minimum 5 characters" pattern=".{6,}" required>
		<br>

	
	
	
	
	
	
		<!--
		<label for="pwd1">Password* :</label><br>
		<input type="password" id="pwd1" name="pwd1"  title="Minimum 5 characters" pattern=".{6,}" required>
		<br>		
		<label for="pwd2">Re-enter Password* :</label><br>
		<input type="password" id="pwd2" name="pwd2"  pattern=".{6,}" required
		
		onfocus="validatePass(document.getElementById('pwd1'), this);" oninput="validatePass(document.getElementById('pwd1'), this);">
		-->
		
		
		
		<input type="checkbox" required x-moz-errormessage="Need Check It" name="agreement" id="agreement" value="yes" /> I have read and agreed with the <a href="terms.php" target="_blank" style="text-decoration:underline !important;">Terms of Use.</a><br/>
		<button name ="submit" type="submit" id="submit" value="Submit">Submit</button>
	</form>
	</div>

<!-- FINISH CONTENT HERE -->
<div class="clear"></div>
</div>


<?php  include 'include/footer.php'; ?>