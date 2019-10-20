<?php
//deletes the loan owned by current user from the list of loans
include_once 'dbconn.php';
include_once 'functions.php';


//check if parameters are set to use this function
if(isset($_REQUEST['loanID'])) {
	//sanitize data
	$loanid = filter($_REQUEST['loanID']);
	//retrieve loan information
	$select_query = "SELECT postType, borrowerID, lenderID FROM Loans WHERE id = " . $loanid . " ;";
	$id_check_result = mysqli_query($link, $select_query);
	$id_arr = mysqli_fetch_row($id_check_result);
	$owner = 0;

	//0=borrow, 1=lend
	//check if current user is the owner of the loan
	if ($id_arr[0]) {
		if($id_arr[2] == fetch_current_userId()) {
			$owner = 1;
		}
	} else {
		if($id_arr[1] == fetch_current_userId()) {
			$owner = 1;
		}
	}

	if ($owner) { //current user is the loan, proceed deleting the loan from database
		$delete_query = "DELETE FROM Loans WHERE id =" . $loanid . ";";
		mysqli_query($link, $delete_query);

		header("Location: ../overview.php");
	} else {
		//not owner of the loan
		header("Location: ../error.php?errorNo=7");
	}
} else {
	//invalid parameters provided
	header("Location: ../error.php?errorNo=8");
}

?>