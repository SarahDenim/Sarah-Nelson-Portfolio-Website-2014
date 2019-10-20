<?php 
	include 'include/head.php'; 
	include 'include/topnav.php'; 
	include 'include/dbconn.php';
	include 'include/functions.php';

	Check_Login();
	//echo "Debug: <br>";
	
	$loanId = $_REQUEST['loanId'];
	//echo "Loan id: ".$loanId."<br>";
	
	$loan = fetch_loan_by_id($loanId);		// lookup requested loan
	if (!is_null($loan)) {					// check if the loan exists
		// The queried loan exists, determine user level
		/*
			0: not involved
			1: lender
			2: borrower
		*/
		$borrower = fetch_borrower($loanId);
		$level = check_loan_user_level($loanId);
		//echo "level: ".$level."<br>";
		$due = $loan['amount'] * (1+$loan['rate']/100);
	} else {
		// The queried loan does not exist. Show error page
		header("Location: error.php?errorNo=5");
	}
	
	$currentUser = fetch_current_user();
	$isActive = check_loan_status($loanId);
	
?>

<div class="container">
<?php if($isActive == 0 || $level != 1): ?>
	
	<h1>An error occurred:</h1>
	<p>You either are not allowed to complete this loan, or it does not exist</p>

<?php else: ?>
	
	<h2>COMPLETE LOAN</h2>
	<h5>Loan ID: <?php echo $loanId; ?> </h5>
	<h5>Status: Active</h5>
	
	<div class="loanBoxes">
		<div class="loanBoxLeft">

	<?php $imgUrl = $borrower['imgUrl']; ?>
			<h3>About the Borrower</h3>
			<?php echo "<img src='$imgUrl'>"; ?>
			<ul>
				<li>Name: <?php echo $borrower['firstname']." ".$borrower['surname'];?></li>
				<li>Income: <?php echo $borrower['income'];?></li>
				<li>Score/Rating: <?php echo $borrower['income'];?></li>
				<li>About Me: <?php echo $borrower['aboutMe'];?></li>
			</ul>
		</div>
			<div class="loanBoxRight">
			<h3>Loan Terms</h3>
			<ul>
				<li>Amount: $<?php echo $loan['amount']." at ". $loan['rate']."%";?></li>
				<li>Money Due: $<?php echo $due;?></li>
				<li>Payback Date: <?php echo $loan['paybackDate'];?></li>
				<li>Listed Purpose: <?php echo $loan['purpose'];?></li>
			</ul>
		</div>
		</div>
		<div style="clear:both;"></div>
		<div class="completeLoanForm">
	<?php echo '<form action="include/rating.php?loanId='.$loanId.'" method="post" name="rateForm" id="inputs">' ?><!-- id="regForm"> -->
	
		<h3>Please rate (borrowers name here):</h3>
		<label for="moneyBack">Please tell us how much money was paid back:</label><br>
		<input type="number" class="dollar" id="amount" step="any" name="moneyBack" min="0" max="<?php echo $due;?>" text="Minimum amount $0, Max $<?php echo $due;?>" required pattern="[0-9]{1,}">
		<br>

		<label for="comm">Communication:</label><br>
		<select name="comm">
			<option value="1">The communication was adequate.</option>
			<option value="0">The communication was inacceptable.</option>	
		</select>
		<br>

		<label for="reliab">Reliability:</label><br>
		<select name="reliab">
			<option value="1">The borrower was reliable.</option>
			<option value="0">The borrower was unreliable.</option>
		</select>
		<br>
		
		<label for="time">Timeliness:</label><br>
		<select name="time">
			<option value="1">I was paid back on or before the agreed due date.</option>
			<option value="0">I was paid after the due date.</option>
		</select>
		<br>
		<label for="comment">Please make a short comment on (borrowers name)'s conduct (max 300 characters):</label><br>
		<textarea name="comment" rows="4" cols="50" maxlength="300"></textarea>
		<br>
		<button name ="submit" type="submit" id="submit" value="Submit">Submit</button> 
	</form>
</div>
	
<?php endif ?>
	 <button TYPE="button" VALUE="Back" onClick="history.go(-1);">Back</button>

<div class="clear"></div>

</div><!-- /container -->


<?php include 'include/footer.php'; ?>