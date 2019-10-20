<?php
/* 	This code gets executed as part of an ajax implementation which allows
 * 	a filtered search on the available loans.
 *
 *	The produced result is a table containing informatin on loans based on
 *	the users filter settings.
 */
include 'dbconn.php';
include 'functions.php';

Check_Login();
$IDrow = fetch_current_user();
$uqID = $IDrow['uqid'];

foreach($_REQUEST as $key => $value) {
    $data[$key] = filter($value);
}

$filterString = "SELECT * FROM `Loans` WHERE lenderID NOT LIKE $uqID AND borrowerID IS NULL";
$first = 1;
if ($data['filter_minsum'] != ""|| $data['filter_maxsum'] != "") {
	if ($data['filter_minsum'] != "") {
		$filterString = $filterString . " AND `amount` >= " . $data['filter_minsum'];
	}
	if ($data['filter_maxsum'] != "") {
		$filterString = $filterString . " AND `amount` <= " . $data['filter_maxsum'];
	}
	if ($data['filter_minrate'] != "") {
		$filterString = $filterString . " AND `rate` >= " . $data['filter_minrate'];
	}
	if ($data['filter_maxrate'] != "") {
		$filterString = $filterString . " AND `rate` <= " . $data['filter_maxrate'];
	}
}

if (isset($data['sortBy']) && isset($data['sortDir'])) {
	$filterString = $filterString. " ORDER BY " . $data['sortBy'] . " " . $data['sortDir'];
}

//echo $filterString;
$results = mysqli_query($link, $filterString);
if (isset($results)) {
	echo "<table border='1'>";
			echo "<tr>";
				echo "<th>Lender</th>";
				echo "<th>Amount</th>";
				echo "<th>incl. Interest</th>";
				echo "<th>Rate</th>";
				echo "<th>Due Date</th>";
				echo "<th>Details</th>";
			echo "</tr>";
	while($row = mysqli_fetch_array($results)) {
		$thisUser = fetch_user_from_id($row['lenderID']);
		$fullName = $thisUser['firstname']." ".$thisUser['surname'];
		echo "<tr>";
			echo "<td><a href='profile.php?id=".$row['lenderID']."'>$fullName</a></td>";
			echo "<td>$row[amount]</td>";
			echo "<td>".$row['amount']*(1+$row['rate']/100)."</td>";
			echo "<td>$row[rate]</td>";
			echo "<td>$row[paybackDate]</td>";
			echo "<td><a href='loan.php?loanId=".$row['id']."'>Click for loan details</a></td>";
		echo "</tr>";
	}
	echo "</table>";
} else {
	echo "No results";
}

?>