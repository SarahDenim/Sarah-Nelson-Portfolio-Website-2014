<?php
//For a given offer which refers to a specific loan, fill out
//the null party in the database with the offerer name to make 
//loan active.
//delete other offers for this loan.

include_once 'dbconn.php';
include_once 'functions.php';

//check if parameters loan id and user if are set to use this function
if(isset($_REQUEST['loanID']) && isset($_REQUEST['userID'])){	
	//sanitize data
	$loanid = filter($_REQUEST['loanID']);
	$uqid = filter($_REQUEST['userID']);

	//select the loan information from database
	$loan_type = "SELECT postType, borrowerID, lenderID FROM Loans WHERE id = " . $loanid . ";";
	$type_result = mysqli_query($link, $loan_type);
	$loan_arr = mysqli_fetch_row($type_result);
	$verified = 1;

	//0=borrow, 1=lend
	// find if the borrower ID (lenderID) is null for lend (borrow)
	// if this is not the case, then the offer is not for a valid loan
	// direct to error page
	if($loan_arr[0] == 0) {
		if (fetch_current_userId() != $loan_arr[1]) {
			$verified = 0;
			header("Location: ../error.php?errorNo=7");
		}
	} else if ($loan_arr[0] == 1) {
		if (fetch_current_userId() != $loan_arr[2]) {
			$verified = 0;
			header("Location: ../error.php?errorNo=7");
		}
	} else {
		header("Location: ../error.php?errorNo=8");
		break;
	}

	// if the offer is valid for a valid loan. (should be as previously verified flag was set)
	if($verified) {
		//select application offer information
		$param_query = "SELECT * FROM Applications WHERE loanId = " . $loanid . " AND uqid = " . $uqid. ";";
		$result = mysqli_query($link, $param_query);
		$row = mysqli_fetch_row($result);
		//sanitize
		foreach($row as $key => $value) {
			$data[$key] = filter($value);
		}

		//0=borrow, 1=lend
		// Make update statement to update name into the null value of the loan (borrowerID or lenderID)
		if ($loan_arr[0] == 0 && $loan_arr[2] == NULL) {

			$update_query = "UPDATE Loans SET amount = '" . $data[2] . 
					"', rate = '" . $data[3] . 
					//"', purpose = '" . $data[4] . 
					"', paybackDate = '" . $data[5] .
					"', lenderID = '" . $data[0] .
					"' WHERE id = '" . $loanid . "';";
		} else if ($loan_arr[0] == 1 && $loan_arr[1] == NULL){
			$update_query = "UPDATE Loans SET amount = '" . $data[2] . 
					"', rate = '" . $data[3] . 
					"', purpose = '" . $data[4] . 
					"', paybackDate = '" . $data[5] .
					"', borrowerID = '" . $data[0] .
					"' WHERE id = '" . $loanid . "';";
			echo $update_query;
		} else { //theoretically should not reach here... unless the loan is already active or the db is not accurate
			header("Location: ../error.php?errorNo=6");
			break;
		}
		//run update statement
		mysqli_query($link, $update_query);
		//delete all offers from applications table as these offers are no longer valid
		$delete_query = "DELETE FROM Applications WHERE loanId = " . $loanid . ";"; 
		mysqli_query($link, $delete_query);
		//send notification to owner of the loan
		$message = "Offerer has accepted your application for loan: " . $loanid;
		send_notification($uqid, $message, $loanid);
		header("Location: ../loan.php?loanId=" . $loanid);
	} else {
		// not verified, so direct to error page
		header("Location: ../error.php?errorNo=7");
	}
} else { // not a valid loan for this offer
	header("Location: ../error.php?errorNo=8");
}

?>