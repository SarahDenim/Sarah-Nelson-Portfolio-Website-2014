<?php 
include_once 'dbconn.php';
include_once 'functions.php';

/*
 * This file processes the feedback submitted upon loan completion and updates
 * relevant fields in the database.
 */


if(isset($_POST['submit'])) {
	if($_POST['submit'] == 'Submit') {
    	foreach($_POST as $key => $value) {
    		$data[$key] = filter($value);
    		echo $key." data: ".$data[$key]."<br>";
    		if ($data[$key] == NULL) {
    			$err = 1;
				header("Location: ../error.php?errorNo=8");
    		}
    	}
	}

	$err = 0;
	$loanId = $_REQUEST['loanId'];
	echo "Loan id: ".$loanId."<br>";


	$loan = fetch_loan_by_id($loanId);		// lookup requested loan
	if (!is_null($loan)) {					// check if the loan exists
		// The queried loan exists, determine user level
		/*
			0: not involved
			1: lender
			2: borrower
		*/
		$level = check_loan_user_level($loanId);
		echo "level: ".$level."<br>";
	} else {
		// The queried loan does not exist.
		$err = 1;
		header("Location: ../error.php?errorNo=5");
	}

	if ($loan['isCompleted']==1) {
		$err = 1;
		header("Location: ../error.php?errorNo=6");
	}

	$currentUser = fetch_current_user();
	$isActive = check_loan_status($loanId);
	echo "Is active: ".$isActive."<br>";

	// check that loan is active and user is authorized
	if ($isActive == 0 || $level != 1) {
		// user is not authorized
		$err = 1;
		header("Location: ../error.php?errorNo=7");
	}
	
	// compute feedback
	if ($err == 0) {
		$date = date('Y-m-d');
		// money due:
		$due = $loan['amount'] * (1+$loan['rate']/100);
		$interest = $due - $loan['amount'];
		echo "due: ".$due." interest: ".$interest."<br>";

		// outstanding balance:
		$outstanding = $due - $data['moneyBack'];

		// determine score and maxScore
		$maxScore = $due;
		$score = $data['moneyBack']; 

		$sql_update_feedback = "UPDATE `Loans`
	  			SET isCompleted = 1, completeDate = \"$date\", communication = $data[comm], reliability = $data[reliab], timeliness = $data[time], comment = \"$data[comment]\", scoreMax = $maxScore, score = $score
	  			WHERE id = $loanId;";
	  	echo $sql_update_feedback."<br>";
	  	mysqli_query($link, $sql_update_feedback) or die("Insertion Failed:" . mysqli_error($link));
	  	mysqli_close($link); 
  	}
}
	header("Location: ../overview.php");
	echo "All done successfully. See DB for changes.<br>";

	
?>