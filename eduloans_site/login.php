<?php
include 'include/dbconn.php';
include 'include/functions.php';



// no need to sanitise these inputs, because they are not used
// as part of a SQL query
$email = $_REQUEST['Email'];
$pwd = $_REQUEST['Password'];

//login_user($email, $pwd);

// Check 1: Is the entered email valid?
if (isEmail($email)) {	
	// Check 2: Is the email registered in the system?
	if (is_registered($email)) {
		$id = fetch_id_from_mail($email);
		$dbPwdHash = fetch_hash_from_id($id);

		// Check 3: Is the password correct?
		if ($dbPwdHash === PwdHash($pwd, substr($dbPwdHash,0,9))) {
			// All checks passed successfully: set session.
		
			$_SESSION['email']=$email;
			header ("Location: overview.php");
			die();
		} else {
			// invalid password
		}
	} else {
		// not registered
	}
} else {
	// not a valid email
}
// If we get here, the login failed to pass a check.
header ("Location: loginform.php");	// invalid email
mysqli_close($link);

?>


