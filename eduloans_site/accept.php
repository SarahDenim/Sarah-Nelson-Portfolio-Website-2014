<?php
	include_once 'include/dbconn.php';
	include 'include/functions.php';
	
if(isset($_POST['submit'])) {
	if($_POST['submit'] == 'Submit') {
    	foreach($_POST as $key => $value) {
    		$data[$key] = filter($value);
    		echo $key." data: ".$data[$key]."<br>";
    		if ($data[$key] == NULL) {
    			$err = 1;
				header("Location: /development/error.php?errorNo=8");
    		}
    	}
	}
	$loanId = $_REQUEST['id'];
	echo $loanId."<br>";

	$currentUser = fetch_current_user();
	

	$query = "SELECT * FROM Loans WHERE id='". $loanId . "';";
	$result = mysqli_query($link, $query);
	$loan = mysqli_fetch_assoc($result);

	if (is_null($loan['borrowerID'])) { // loan is of type lend 
		// if entry already exists, update instead...
		if (Application_Exists($currentUser['uqid'], $loanId) != NULL) {
			echo "EXISTS<br>";
			$query = "UPDATE `Applications`
						SET amount='$data[amount]', rate='$data[rate]',purpose='$data[purpose]',paybackDate='$data[payback]',message='$data[message]'
						WHERE uqid= $currentUser[uqid]
						AND loanId= $loanId;";
					
			echo "query: ".$query."<br>";
			mysqli_query($link, $query);
		} else {

		$query = "INSERT INTO `Applications`
					(`uqid`, `loanId`, `amount`, `rate`, `purpose`, `paybackDate`, `message`)
					VALUES 
					( $currentUser[uqid], '$loanId', '$data[amount]', '$data[rate]', '$data[purpose]','$data[payback]', '$data[message]');";	
		echo "query: ".$query."<br>";
		mysqli_query($link, $query);
		}
		
		//FIND message details
		
		$getName = "SELECT firstname FROM ProfileData WHERE uqid = " . $currentUser['uqid'];
		$getNameResult = mysqli_query($link, $getName);
		$getNameResultArr = mysqli_fetch_row($getNameResult);


		$message = $getNameResultArr[0] . " showed interest in your loan offer:\n$" . $data['amount'] . " for " . $data['purpose'];
		send_notification($loan['lenderID'], $message, $loanId);

		header("Location: overview.php?msg=2");
	

	} else if (is_null($loan['lenderID'])) {
		// if entry already exists, update instead...
		if (Application_Exists($currentUser['uqid'], $loanId) != NULL) {
			echo "EXISTS<br>";
			$query = "UPDATE `Applications`
						SET amount='$data[amount]', rate='$data[rate]',paybackDate='$data[payback]',message='$data[message]'
						WHERE uqid= $currentUser[uqid]
						AND loanId= $loanId;";
					
			echo "query: ".$query."<br>";
			mysqli_query($link, $query);
		} else {

			$query = "INSERT INTO `Applications`
						(`uqid`, `loanId`, `amount`, `rate`, `paybackDate`, `message`)
						VALUES 
						( $currentUser[uqid], '$loanId', '$data[amount]', '$data[rate]', '$data[payback]', '$data[message]');";	
			echo "query: ".$query."<br>";
			mysqli_query($link, $query);
		}

		$getName = "SELECT firstname FROM ProfileData WHERE uqid = " . $currentUser['uqid'];
		$getNameResult = mysqli_query($link, $getName);
		$getNameResultArr = mysqli_fetch_row($getNameResult);


		$message = $getNameResultArr[0] . " has agreed to lend you money:\n$" . $data['amount'] . " for " . $loan['purpose'];
		send_notification($loan['borrowerID'], $message, $loanId);
		header("Location: overview.php?msg=2");
	} else {
		echo "loan already active";
	}
}
?>