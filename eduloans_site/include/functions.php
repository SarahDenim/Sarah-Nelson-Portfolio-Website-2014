<?php
if(session_id() == '' || !isset($_SESSION)) {
    	// session isn't started
    	session_start();
	}

if (!defined('COOKIE_TIME_OUT')) {	// avoid superfluous defines
	define("COOKIE_TIME_OUT", 10); //specify cookie timeout in days (default is 10 days)
	define('SALT_LENGTH', 9); // salt for password

	/* Specify user levels */
	define ("ADMIN_LEVEL", 5);
	define ("USER_LEVEL", 1);
	define ("GUEST_LEVEL", 0);
}


if (!defined('DB_HOST')) {
	define ("DB_HOST", "localhost"); // set database host
	define ("DB_USER", "studio3_site"); // set database user
	define ("DB_PASS", "jasper123"); // set database password
	define ("DB_NAME", "studio3_site"); // set database name	
}



/**** PAGE PROTECT CODE  ********************************
This code protects pages to only logged in users. If users have not logged in then it will redirect to login page.
If you want to add a new page and want to login protect, COPY this from this to END marker.
Remember this code must be placed on very top of any html or php page.
********************************************************/

/* This function determines if a user is logged in, and redirects to the loginform is he is not.
 */
function Check_Login() {
	if (!isset($_SESSION['email'])) {
		header("Location: loginform.php");
	}
}

/* Function to sanitise inputs to protect against SQL injection
 */
function filter($data) {
	global $link; 
	$data = trim(htmlentities(strip_tags($data)));	
	if (get_magic_quotes_gpc()) {
		$data = stripslashes($data);	
	}
	$data = mysqli_real_escape_string($link, $data);	
	return $data;
}

function EncodeURL($url) {
$new = strtolower(ereg_replace(' ','_',$url));
return($new);
}

function DecodeURL($url) {
$new = ucwords(ereg_replace('_',' ',$url));
return($new);
}

/* This function shortens a string to a given length.
 *
 *  Input:
 *    $str: the string to shorten
 *	  $len: the max length the string should have
 *
 * Returns:
 *    $str: the shortened string
 */
function ChopStr($str, $len) {
    if (strlen($str) < $len)
        return $str;

    $str = substr($str,0,$len);
    if ($spc_pos = strrpos($str," "))
            $str = substr($str,0,$spc_pos);

    return $str . "...";
}	

/* Check if the input is an email address.
 *
 *  Input:
 *    $email: a string, which may or may not be an email address
 *
 * Returns:
 *    TRUE:  if $email matches the emailaddress pattern
 *    FALSE: if $email does not match the pattern
 */
function isEmail($email){
  return preg_match('/^\S+@[\w\d.-]{2,}\.[\w]{2,6}$/iU', $email) ? TRUE : FALSE;
}

/* Check if the input matches the pattern for username.
 *
 *  Input:
 *    $username: a string, which may or may not be a valid username
 *
 * Returns:
 *    TRUE:  if $username matches the username pattern
 *    FALSE: if $username does not match the pattern
 */
function isUserID($username) {
	if (preg_match('/^[a-z\d_]{5,20}$/i', $username)) {
		return true;
	} else {
		return false;
	}
 }	

/* Check if the input matches the pattern for username.
 *
 *  Input:
 *    $url: a string, which may or may not be a valid url
 *
 * Returns:
 *    TRUE:  if $url matches the url pattern
 *    FALSE: if $url does not match the pattern
 */ 
function isURL($url) {
	if (preg_match('/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i', $url)) {
		return true;
	} else {
		return false;
	}
} 

/* Check if the 2 input strings are equal.
 *
 *  Input:
 *    $x: password 1
 *    $y: password 2
 *
 * Returns:
 *    TRUE:  the passwords match
 *    FALSE: the passwords dont match
 */ 
function checkPwd($x,$y) {
	if(empty($x) || empty($y) ) { return false; }
	if (strlen($x) < 4 || strlen($y) < 4) { return false; }
	if (strcmp($x,$y) != 0) {
		return false;
	} 
	return true;
}


// Password and salt generation
function PwdHash($pwd, $salt = null) {
    if ($salt === null)     {
        $salt = substr(md5(uniqid(rand(), true)), 0, SALT_LENGTH);
    }
    else     {
        $salt = substr($salt, 0, SALT_LENGTH);
    }
    return $salt . sha1($pwd . $salt);
}

/* Fetch the user id given an email address as input
 *
 *  Input:
 *    $email: the email address to lookup
 *
 * Returns:
 *    $row['uqid']: userid associated with given email address
 */ 
function fetch_id_from_mail($email) {
  global $link;
  $query = "SELECT * FROM `ProfileData` WHERE email='".$email."';";
  $result = mysqli_query($link, $query);
  $num = mysqli_num_rows($result);  
  if(isset($result) && ($num == 1)) {
    $row = mysqli_fetch_assoc($result);
    return $row['uqid'];
  } else {
    return NULL;
    // If there was an error, let us know:
    die(mysqli_error($link)); 
  }
  header ("Location: error.php");
  return NULL;
}

/* Fetch the pw hash given a user id
 *
 *  Input:
 *    $id: the user id
 *
 * Returns:
 *    $row['pwd']: pwd associated with given user id
 */
function fetch_hash_from_id($id) {
  global $link;
  $query = "SELECT * FROM `LoginData` WHERE uqid='".$id."';";
  $result = mysqli_query($link, $query);
  $num = mysqli_num_rows($result);  
  if(isset($result) && ($num == 1)) {
    $row = mysqli_fetch_assoc($result);
    return $row['pwd'];
  } else {
    // If there was an error, let us know:
    die(mysqli_error($link)); 
  }
  header ("Location: error.php");
  return NULL;
}

/* Check if a given email address is registered within the system
 *
 *  Input:
 *    $email: the email address to lookup
 *
 * Returns:
 *    TRUE:  if the email is found in the system
 *	  FALSE: if the email is not found within the system
 */
function is_registered($email) {
  global $link;
  $query = "SELECT * FROM `ProfileData` WHERE email='".$email."';";
  $result = mysqli_query($link, $query);
  $num = mysqli_num_rows($result);  
  if(isset($result) && ($num == 1)) {
    $row = mysqli_fetch_assoc($result);
    return true;
  } else {
    return false;
  }

  // never gets here
  return false;
}

/*  This function determines who is currently logged in, and queries their
 *  data entry from the data-base and returns it as a sql row.
 */
function fetch_current_user() {
	global $link;
	$query = "SELECT * FROM `ProfileData` WHERE email='".$_SESSION['email']."';";
	$result = mysqli_query($link, $query);
	//TODO: do some check on the number of rows?
	// $num should always be 1, because users are unique.
	$num = mysqli_num_rows($result);  
	if(isset($result) && ($num == 1)) {
	$row = mysqli_fetch_assoc($result);
	return $row;
	} else {
		// If there was an error, let us know:
		die(mysqli_error($link)); 
	}
	header ("Location: error.php");
	return NULL;
}

/* This function looks up the currently logged in users id.
 */
function fetch_current_userId() {
	$row = fetch_current_user();
	return $row['uqid'];
}


/* This function fetches the table-row associated with a user of given id.
 *
 * Inputs:
 *		$id: The uqid of an user
 *
 * Returns:
 *		$row: The row associated with a user of given id.
 *		NULL: if there is no user with the given id.
 */
function fetch_user_from_id($id) {
	global $link;
	$query = "SELECT * FROM `ProfileData` WHERE uqid='".$id."';";
	$result = mysqli_query($link, $query);
	// $num should always be 1, because users are unique.
	$num = mysqli_num_rows($result);  
	if(isset($result) && ($num == 1)) {
		$row = mysqli_fetch_assoc($result);
		return $row;
	} else {
		// If there was an error, let us know:
		return NULL;
		die(mysqli_error($link)); 
	}

	header ("Location: error.php");
	return NULL;
}


/* This function determines whether a loan is pending or active.
 *
 * Returns:
 *		0: The loan is pending
 *		1: The loan is active
 *	   -1: An error occurred
 */
function check_loan_status($id) {
  global $link;
  $query = "SELECT * FROM `Loans` WHERE id='".$id."';";
  $result = mysqli_query($link, $query);
  $num = mysqli_num_rows($result);  
  if(isset($result) && ($num == 1)) {
  	$row = mysqli_fetch_assoc($result);
  	if (is_null($row['borrowerID'])) {
  		return 0;	// no borrower -> is pending
  	} else if(is_null($row['lenderID'])){
  		return 0;	// no lender -> is pending
  	} else {
  		return 1; // is active
  	}
  } else {
	// If there was an error, let us know:
	return -1;		
	die(mysqli_error($link)); 
  }

  header ("Location: error.php");
  return -1;
}

/*
 * This function fetches the sql row associated with the lender of a loan
 */
function fetch_lender($loanId) {
	global $link;
	//echo "loan id :".$loanId."<br>";
	$query = "SELECT * FROM `Loans` WHERE id='". $loanId . "';";
	$result = mysqli_query($link, $query);
	$loan = mysqli_fetch_assoc($result);
	$userId = $loan['lenderID'];
	//echo "Userid: ".$userId."<br>";
	if ($userId == NULL) return NULL;
	$result = fetch_user_from_id($userId);

	return $result;
}

/*
 * This function fetches the sql row associated with the borrower of a loan
 */
function fetch_borrower($loanId) {
	global $link;
	//echo "loan id :".$loanId."<br>";
	$query = "SELECT * FROM `Loans` WHERE id='". $loanId . "';";
	$result = mysqli_query($link, $query);
	$loan = mysqli_fetch_assoc($result);
	$userId = $loan['borrowerID'];
	//echo "Userid: ".$userId."<br>";
	if ($userId == NULL) return NULL;
	$result = fetch_user_from_id($userId);

	return $result;
}



/* This function determines the current users association
 * with a given loanId.
 *
 * Input:
 * 		$loanId: the id of a loan
 *
 * Returns:
 * 		0: user is not an entity
 * 		1: user is the lender
 * 		2: user is the borrower
 */
function check_loan_user_level($loanId) {
	global $link;
	$query = "SELECT * FROM `Loans` WHERE id='". $loanId . "';";
	$result = mysqli_query($link, $query);
	$loan = mysqli_fetch_assoc($result);
	$userId = fetch_current_userId();
	if ($loan['lenderID'] === $userId) {
		return 1;	  // the user is the lender.
	} else if ($loan['borrowerID'] === $userId) {
		return 2; 	// the user is the borrower.
	} 
	// If we get here, the user is not involved in the loan.
	return 0;
}

/* This function fetches a loan from the table by id.
 *
 *  Input:
 *    $id: the id of a loan
 *
 * Returns:
 *    $row: the table-row associated with the loan id
 *    NULL: if no loan of given id was found
 */
function fetch_loan_by_id($id) {
 	global $link;
	$query = "SELECT * FROM `Loans` WHERE id='".$id."';";
	$result = mysqli_query($link, $query);
	//TODO: do some check on the number of rows?
	// $num should always be 1, because users are unique.
	$num = mysqli_num_rows($result);  
	if(isset($result) && ($num == 1)) {
	    $row = mysqli_fetch_assoc($result);
	    return $row;
  	} else {
  		// $result is not set, or number of rows != 1. return NULL
    	return NULL;
    	// If there was an error, let us know:
    	die(mysqli_error($link)); 
 	}
	// never gets here.
	header ("Location: error.php");
	return NULL;
}

/* This function fetches a given users reliability rating.
 *
 *  Input:
 *    $id: the id of a user
 *
 * Returns:
 *    $rating: reliability rating associated with the user
 *    NULL: if the user was not found
 */
function get_reliability($id) {
	global $link;

	$query =   "SELECT * 
				FROM `Loans` 
				WHERE borrowerID='".$id."' 
				AND isCompleted='1'";

	$result = mysqli_query($link, $query);
	$num = mysqli_num_rows($result); 
	$sum = 0; 
	if(isset($result) && ($num > 0)) {
	    while($row = mysqli_fetch_assoc($result)) {
	    	$sum = $sum + $row['reliability'];
	    }
	    
  	} else {
  		// $result is not set, or number of rows = 0. return 0
    	return 0;
    	// If there was an error, let us know:
    	die(mysqli_error($link)); 
 	}
 	if ($sum == 0) return 0;
	$rating = round(($sum / $num), 2);
	

	return $rating;
}

/* This function fetches a given users timeliness rating.
 *
 *  Input:
 *    $id: the id of a user
 *
 * Returns:
 *    $rating: timeliness rating associated with the user
 *    NULL: if the user was not found
 */
function get_timeliness($id) {
	global $link;

	$query =   "SELECT * 
				FROM `Loans` 
				WHERE borrowerID='".$id."' 
				AND isCompleted='1'";

	$result = mysqli_query($link, $query);
	$num = mysqli_num_rows($result); 
	$sum = 0; 
	if(isset($result) && ($num > 0)) {
	    while($row = mysqli_fetch_assoc($result)) {
	    	$sum = $sum + $row['timeliness'];
	    }
	    
  	} else {
  		// $result is not set, or number of rows = 0. return 0
    	return 0;
    	// If there was an error, let us know:
    	die(mysqli_error($link)); 
 	}
 	if ($sum == 0) return 0;
	$rating = round(($sum / $num), 2);
	return $rating;
}

/* This function fetches a given users communication rating.
 *
 *  Input:
 *    $id: the id of a user
 *
 * Returns:
 *    $rating: communication rating associated with the user
 *    NULL: if the user was not found
 */
function get_communication($id) {
	global $link;

	$query =   "SELECT * 
				FROM `Loans` 
				WHERE borrowerID='".$id."' 
				AND isCompleted='1'";

	$result = mysqli_query($link, $query);
	$num = mysqli_num_rows($result); 
	$sum = 0; 
	if(isset($result) && ($num > 0)) {
	    while($row = mysqli_fetch_assoc($result)) {
	    	$sum = $sum + $row['communication'];
	    }
	    
  	} else {
  		// $result is not set, or number of rows = 0. return 0
    	return 0;
    	// If there was an error, let us know:
    	die(mysqli_error($link)); 
 	}
 	if ($sum == 0) return 0;
	$rating = round(($sum / $num), 2);
	
	return $rating;
}

/* Attempts to perform a login given email and password
 *
 * INPUTS:
 *		$email: user email
 *		$pwd: user password
 */
function login_user($email, $pwd) {
	global $link;
	$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	$flag;
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
				header ("Location: overview.php?msg=1");
				die();
			} else {
				// invalid password
				$flag = "pw";
			}
		} else {
			// not registered
			$flag = "not_registered:".$email;
		}
	} else {
		// not a valid email
		$flag = "not_a_email:".$email;
	}
	// If we get here, the login failed to pass a check.
	header ("Location: loginform.php?flag=$flag");	// invalid email
	mysqli_close($link);
}


/* This attempts to send a user, given a message and loan id.
 *
 *  Input:
 *    $recipientID: the id of a user
 *	  $message: the message sent to the user
 *	  $loanID: the loanID associated with the message
 *
 */
function send_notification($recipientID, $message, $loanID) {
	global $link;
	$data['target'] = filter($recipientID);
	$data['text'] = filter($message);

	echo $data['target'];
	echo $data['text'];
	if(isset($data['text']) && isset($data['target'])) {
		if (isset($loanID)) {
			$data['loanId'] = filter($loanID);
			echo $data['loanId'];
			$insert = "INSERT INTO Notifications (id, text, seen, uqid, loanid, timestamp) VALUES (null, '" . $data['text'] . "', '0', '" . $data['target'] ."', '" . $data['loanId'] . "', null);";
		} else {
			//system notification
			$insert = "INSERT INTO Notifications (id, text, seen, uqid, loanid, timestamp) VALUES (null, '" . $data['text'] . "', '0', '" . $data['target'] ."', null, null);";
		}
		echo $insert;
		
		mysqli_query($link, $insert) or die ("Search for ID Failed". mysqli_error($link));
		echo "Success";
	} else {
		echo "Error: Bad Arguments";
	}
}

/* Creates a confirmation box popup for a user.
 */
function dialog_box($message) {
	//echo "<div id='dialogBox' style='position:absolute;top:50%;left:50%;margin-right:-50%;transform:translate(-50%, -50%);z-index:9999'>";
	echo "<div id='dialogBox' style='position:fixed;top:50%;left:50%;width:250px;background-color:white;border-style:solid;border-width:2px;margin-left:-125px;z-index:9999'>";
	echo "<h1>Confirmation Box</h1>";
	echo $message;
	echo "<br />";echo "<br />";echo "<br />";
	echo "<button onclick=\"getElementById('dialogBox').remove()\">OK</button>";
	echo "</div>";
}

/* Update the URL pointing at a given users profile picture.
 *
 * This is needed when the user uploads new picture with a different extension, i.e. changes from a .jpg to a .png
 */
function Update_ImgUrl($fileName, $id) {
	global $link;
	$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	$sql_update_profile = "UPDATE `ProfileData`
  			SET imgUrl='./images/userimg/$fileName'
  			WHERE uqid=$id;";

  	echo "query: ".$sql_update_profile."<br>";
  	mysqli_query($link, $sql_update_profile) or die ("update failed: ". mysqli_error($link));
  	mysqli_close($link);
}

/* This function fetches the sum of a given users loan-scores.
 *
 *  Input:
 *    $id: the id of a user
 *
 * Returns:
 *    $rating: total score of a user
 *    0: if the user was not found, or an error ocurred (lacking data, new user)
 */
function Get_Score($id) {
	global $link;
	$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	$query = "SELECT score, scoreMax
  			FROM `Loans`
  			WHERE borrowerID=$id
  			AND isCompleted='1';";

	$result = mysqli_query($link, $query);
	$num = mysqli_num_rows($result); 
	$sumScore = 0; 
	$sumScoreMax = 0;
	if(isset($result) && ($num > 0)) {
	    while($row = mysqli_fetch_assoc($result)) {
	    	$sumScore = $sumScore + $row['score'];
	    	$sumScoreMax = $sumScoreMax + $row['scoreMax'];
	    }
	    
  	} else {
  		// $result is not set, or number of rows = 0. return 0
    	return 0;
    	// If there was an error, let us know:
    	die(mysqli_error($link)); 
 	}
 	if ($sumScore == 0) return 0;
	

	$rating = round(($sumScore / $sumScoreMax), 4);
	//echo "rating: ".$rating."<br>";

	return $rating;
}

/* This function fetches the number of loans completed, by given userID.
 *
 *  Input:
 *    $id: the id of a user
 *
 * Returns:
 *    $row['NumCompleted']: number of loans ocmpleted by the user
 */
function Num_Loans_Completed($id) {
	global $link;
	$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	$query = "SELECT COUNT(*) AS NumCompleted
  			FROM `Loans`
  			WHERE borrowerID=$id
  			AND isCompleted='1';";

	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_assoc($result);

	//echo "completed: ".$row['NumCompleted']."<br>";
	
	return $row['NumCompleted'];
}

/* This function calculates the number of loans a user has failed to payback in full
 *
 *  Input:
 *    $id: the id of a user
 *
 * Returns:
 *    $sum: number of loans defaulted by the user
 */
function Num_Loans_Defaulted($id) {
	global $link;
	$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	$query = "SELECT score, scoreMax
  			FROM `Loans`
  			WHERE borrowerID=$id
  			AND isCompleted='1';";

	$result = mysqli_query($link, $query);
	$num = mysqli_num_rows($result); 
	$diff = 0;
	$sum = 0;
	if(isset($result) && ($num > 0)) {
	    while($row = mysqli_fetch_assoc($result)) {
	    	$diff = $row['scoreMax'] - $row['score'];
	    	if ($diff > 1) {
	    		$sum += 1;
	    	}
	    }
	    
  	} else {
  		// $result is not set, or number of rows = 0. return 0
    	return 0;
    	// If there was an error, let us know:
    	die(mysqli_error($link)); 
 	}
 	if ($sum == 0) return 0;
	

	return $sum;
}

/* Checks if a given user has already applied for a given loan.
 *
 *  Input:
 *    $id: the id of a user
 *
 * Returns:
 *    $row: the data-row associated with this loan application
 */
function Application_Exists($uqid, $loanId) {
	global $link;
	$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	$query = "SELECT *
  			FROM `Applications`
  			WHERE uqid ='$uqid'
  			AND loanId ='$loanId';";

  	$result = mysqli_query($link, $query);
  	$row = mysqli_fetch_assoc($result);

  	return $row;
}

/* Checks if a given user has already applied for a given loan.
 *
 *  Input:
 *    $id: the id of a user
 *
 * Returns:
 *    $row: the data-row associated with this loan application
 */
function Application_Count($loanId) {
	global $link;
	$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	$query = "SELECT COUNT(*) AS NumApp
  			FROM `Applications`
  			WHERE loanId=$loanId;";

	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_assoc($result);

	//echo "completed: ".$row['NumCompleted']."<br>";
	
	return $row['NumApp'];
}

/* Returns Amount that is currently owing */
function Get_Currently_Owing($uqid){
	global $link;
	$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	$query = "SELECT amount, rate
				FROM `Loans`
				WHERE borrowerID = $uqid
				AND isCompleted = '0'
				AND lenderID IS NOT NULL
				;";

	//echo $query."<br>";
	$result = mysqli_query($link, $query);	
	$sumOwing = 0;
	$num = mysqli_num_rows($result); 
	if(isset($result) && ($num > 0)) {
	    //echo "im in here<br>";
	    while($row = mysqli_fetch_assoc($result)) {
	    	$owing = $row['amount'] * (1+$row['rate']/100);
	    	$sumOwing += $owing;
	    	//echo "owing: ".$owing." sum: ".$sumOwing."<br>";
	    }
	    
  	} else {
  		// $result is not set, or number of rows = 0. return 0
    	return 0;
    	// If there was an error, let us know:
    	die(mysqli_error($link)); 
 	}

	return round($sumOwing, 2);
}

/* Returns Amount that is currently owed to the user */
function Get_Currently_Owed($uqid){
	global $link;
	$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	$query = "SELECT amount, rate
				FROM `Loans`
				WHERE lenderID = $uqid
				AND isCompleted = '0'
				AND borrowerID IS NOT NULL
				;";

	//echo $query."<br>";
	$result = mysqli_query($link, $query);	
	$sumOwing = 0;
	$num = mysqli_num_rows($result); 
	if(isset($result) && ($num > 0)) {
	    //echo "im in here<br>";
	    while($row = mysqli_fetch_assoc($result)) {
	    	$owing = $row['amount'] * (1+$row['rate']/100);
	    	$sumOwing += $owing;
	    	//echo "owing: ".$owing." sum: ".$sumOwing."<br>";
	    }
	    
  	} else {
  		// $result is not set, or number of rows = 0. return 0
    	return 0;
    	// If there was an error, let us know:
    	die(mysqli_error($link)); 
 	}

	return round($sumOwing, 2);
}

/* Determines the sum of money lent by a given user, from completed loans only.
 *
 *  Input:
 *    $uqid: the id of a user
 *
 * Returns:
 *    $sumLent: the sum of money lent by this user
 */
function Get_Lent_Total($uqid){
	global $link;
	$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	$query = "SELECT amount
				FROM `Loans`
				WHERE lenderID = $uqid
				AND isCompleted = '1'
				;";

	//echo $query."<br>";
	$result = mysqli_query($link, $query);	
	$sumLent = 0;
	$num = mysqli_num_rows($result); 
	if(isset($result) && ($num > 0)) {
	    //echo "im in here<br>";
	    while($row = mysqli_fetch_assoc($result)) {
	    	$lent = $row['amount'];
	    	$sumLent += $lent;
	    	//echo "owing: ".$owing." sum: ".$sumOwing."<br>";
	    }
	    
  	} else {
  		// $result is not set, or number of rows = 0. return 0
    	return 0;
    	// If there was an error, let us know:
    	die(mysqli_error($link)); 
 	}

	return round($sumLent, 2);
}

/* Determines the sum of money borrowed by a given user, from completed loans only.
 *
 *  Input:
 *    $uqid: the id of a user
 *
 * Returns:
 *    $sumBorrow: the sum of money borrowed by this user
 */
function Get_Borrowed_Total($uqid){
	global $link;
	$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	$query = "SELECT amount
				FROM `Loans`
				WHERE borrowerID = $uqid
				AND isCompleted = '1'
				;";

	//echo $query."<br>";
	$result = mysqli_query($link, $query);	
	$sumBorrow = 0;
	$num = mysqli_num_rows($result); 
	if(isset($result) && ($num > 0)) {
	    //echo "im in here<br>";
	    while($row = mysqli_fetch_assoc($result)) {
	    	$borrow = $row['amount'];
	    	$sumBorrow += $borrow;
	    	//echo "owing: ".$owing." sum: ".$sumOwing."<br>";
	    }
	    
  	} else {
  		// $result is not set, or number of rows = 0. return 0
    	return 0;
    	// If there was an error, let us know:
    	die(mysqli_error($link)); 
 	}

	return round($sumBorrow, 2);
}

/* The largest loan this user has paid back */
function Get_Largest_PaidBack($uqid){
	global $link;
	$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	$query = "SELECT MAX(score) 
				AS HighestPayback 
				FROM Loans
				WHERE borrowerID = $uqid
				AND isCompleted = 1
				;";
	$result = mysqli_query($link, $query);	
	$row = mysqli_fetch_assoc($result);
	return round($row['HighestPayback'], 2);
}




/* Get the number of different lenders this person has borrowed from */
function Get_Num_Lenders($uqid){
	global $link;
	$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	$query = "SELECT DISTINCT lenderID 
				FROM Loans
				WHERE borrowerID = $uqid
				AND isCompleted = 1
				;";
	$result = mysqli_query($link, $query);
	$num = mysqli_num_rows($result); 

	return $num;
}

/* Get the number of different lenders this person has borrowed from */
function Get_Avg_Borrowed($uqid){
	global $link;
	$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	$query = "SELECT AVG(amount) 
				AS Average
				FROM Loans
				WHERE borrowerID = $uqid
				AND isCompleted = 1
				;";

	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_assoc($result);

	return round($row['Average'],2);
}

/* Determines the average amount lent by a given user, from completed loans only.
 *
 *  Input:
 *    $uqid: the id of a user
 *
 * Returns:
 *    $row['Average']: the average amount lent by a user
 */
function Get_Avg_Lent($uqid){
	global $link;
	$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	$query = "SELECT AVG(amount) 
				AS Average
				FROM Loans
				WHERE lenderID = $uqid
				AND isCompleted = 1
				;";

	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_assoc($result);

	return round($row['Average'],2);
}

/* The sum of money this user has paid back */
function Get_Sum_Borrowed($uqid){
	global $link;
	$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	$query = "SELECT SUM(score) 
				AS SumPayback 
				FROM Loans
				WHERE borrowerID = $uqid
				AND isCompleted = 1
				;";
	$result = mysqli_query($link, $query);	
	$row = mysqli_fetch_assoc($result);
	return round($row['SumPayback'], 2);
}

/* The sum of money this user has paid back */
function Get_Sum_Lent($uqid){
	global $link;
	$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	$query = "SELECT SUM(score) 
				AS SumPayback 
				FROM Loans
				WHERE borrowerID = $uqid
				AND isCompleted = 1
				;";
	$result = mysqli_query($link, $query);	
	$row = mysqli_fetch_assoc($result);
	return round($row['SumPayback'], 2);
}


/* Fetches the number of loans currently active for the given userID */
function Get_Active_Num($uqid){
	global $link;
	$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	$query = "SELECT COUNT(*) 
				AS CountActive 
				FROM Loans
				WHERE (borrowerID = $uqid
				OR lenderID = $uqid)
				AND borrowerID IS NOT NULL
				AND lenderID IS NOT NULL
				AND isCompleted = 0
				;";
	$result = mysqli_query($link, $query);	
	$row = mysqli_fetch_assoc($result);
	return $row['CountActive'];
}


?>