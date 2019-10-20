<?php
//for a specific application offer for a loan, delete it.
include_once 'dbconn.php';
include_once 'functions.php';

//check if parameters for using this function is set
if(isset($_REQUEST['loanID']) && isset($_REQUEST['userID'])){	
	//sanitize data
	$loanid = filter($_REQUEST['loanID']);
	$uqid = filter($_REQUEST['userID']);
	//SQL query to retrieve loan information
	$loan_type = "SELECT postType, borrowerID, lenderID FROM Loans WHERE id = " . $loanid . ";";
	$type_result = mysqli_query($link, $loan_type);
	$loan_arr = mysqli_fetch_row($type_result);
	$verified = 1;

	//0=borrow, 1=lend
	// check if user is owner of this loan
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
	}

	if($verified) {
		//user is owner of the loan, and thus, can delete this application offer, make delete statement and run
		$delete_query = "DELETE FROM Applications WHERE loanId = " . $loanid . " AND uqid = " . $uqid . ";"; 
		mysqli_query($link, $delete_query);
		header("Location: ../loan.php?loanId=" . $loanid);

	} else {
		//nor the owner of the loan
		header("Location: ../error.php?errorNo=7");
	}
} else {
	//invalid parameters
	header("Location: ../error.php?errorNo=8");
}

?>