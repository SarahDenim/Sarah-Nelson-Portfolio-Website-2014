<?php 
	include_once 'include/head.php'; 
	include_once 'include/topnav.php'; 
	include_once 'include/dbconn.php';
	include_once 'include/functions.php';
	
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
		$lender = fetch_lender($loanId);
		$borrower = fetch_borrower($loanId);
		$level = check_loan_user_level($loanId);
		//echo "level: ".$level."<br>";
		$due = $loan['amount'] * (1+$loan['rate']/100);
	} else {
		// The queried loan does not exist.
		header("Location: error.php?errorNo=5");
	}
	
	$currentUser = fetch_current_user();
	$isActive = check_loan_status($loanId);
	//echo "Is active: ".$isActive."<br>";
	
?>

<div class="container">
<?php if($isActive == 1 && $level == 0): /* Uninvolved user attempting to view active loan */?>
	<h1>An error occurred:</h1>
	<p>You either are not allowed to see this loan, or it does not exist</p>
<?php else: ?>
	<?php if ($isActive): /* loan is active */?>
		<h2>LOAN OVERVIEW</h2>
		<h5>Loan ID: <?php echo $loanId; ?> </h5>
		<h5>Status: Active</h5>
		<div class="loanBoxes">
		
		<div class="loanBoxLeft">
		<?php if ($level == 1): /* user is the lender */?>
			<?php $imgUrl = $borrower['imgUrl']; ?>
			<h3>About the Borrower</h3>
			<?php echo "<img src='$imgUrl'>"; ?>
			<ul>
				<li>Name: <?php echo $borrower['firstname']." ".$borrower['surname'];?></li>
				<li>Income: <?php echo $borrower['income'];?></li>
				<li>Score/Rating: <?php 
									$score = get_score($borrower['uqid']) * 100;
									echo $score;
									?>
									</li>
				<li>About Me: <?php echo $borrower['aboutMe'];?></li>
			</ul>

			<br/><br/><br/><br/><br/>			
			<h3>CONTACT DETAILS</h3>
			<?php echo "<p>e-mail: ".$borrower['email']."<p>";?>
			<?php echo "<p>phone: ".$borrower['phone']."<p>";?>
			<button TYPE="button" VALUE="Complete Loan" onClick="location.href='http://deco3801-02.uqcloud.net/development/complete.php?loanId=<?php echo $loanId;?>'">Complete Loan</button>
		<?php else: /* user is the borrower */?>
				<?php $imgUrl = $lender['imgUrl']; ?>
				<h3>About the Lender</h3>
				<?php echo "<img src='$imgUrl'>"; ?>
				<ul>
					<li>Name: <?php echo $lender['firstname']." ".$lender['surname'];?></li>
					<li>Income: <?php echo $lender['income'];?></li>
					<li>About Me: <?php echo $lender['aboutMe'];?></li>
				</ul>

			<br/><br/><br/><br/><br/>
			<h3>CONTACT DETAILS</h3>
			<?php echo "<p>e-mail: ".$lender['email']."<p>";?>
			<?php echo "<p>phone: ".$lender['phone']."<p>";?>
				
			<?php endif ?>
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
		



	<?php else: /* loan is still pending */?>
		<h2>LOAN OVERVIEW</h2>
		<h5>Loan ID: <?php echo $loanId; ?> </h5>
		<h5>Status: Pending</h5>
		<div class="loanBoxes">
		<div class="loanBoxLeft">
		<?php if ($loan['postType'] == 0): /* loan is of type borrow */?>
			<?php $imgUrl = $borrower['imgUrl']; ?>
			<h3>About the Applicant</h3>
			<?php echo "<img src='$imgUrl'>"; ?>
			<ul>
				<li><p>Name: <?php echo $borrower['firstname']." ".$borrower['surname'];?></p></li>
				<li><p>Income: <?php echo $borrower['income'];?></p></li>
				<li><p>Score/Rating: <?php echo $borrower['income'];?></p></li>
				<li><p>About Me: <?php echo $borrower['aboutMe'];?></p></li>
			</ul>

		<?php else: /* loan is of type lend */?>
			<?php $imgUrl = $lender['imgUrl']; ?>
			<h3>About the Lender</h3>
			<?php echo "<img src='$imgUrl'>"; ?>
			<ul>
				<li>Name: <?php echo $lender['firstname']." ".$lender['surname'];?></li>
				<li>Income: <?php echo $lender['income'];?></li>
				<li>Score/Rating: <?php echo $borrower['income'];?></li>
				<li>About Me: <?php echo $lender['aboutMe'];?></li>
			</ul>

			<h5>Apply for Loan</h5>

		<?php endif ?>
		</div>
		
		<div class="loanBoxRight">
		<h3>Proposed loan terms</h3>
		<ul>
			<li><p>Amount: $<?php echo $loan['amount']." at ". $loan['rate']."%";?></p></li>
			<li><p>Payback Date: <?php echo $loan['paybackDate'];?></p></li>
			<li><p>Listed Purpose: <?php echo $loan['purpose'];?></p></li>
		</ul>
		</div>
		</div>
		<div style="clear: both;"></div>
		
		<?php if($level != 0): /* user is poster of this inactive loan */ ?>
			<p>You are the poster. </p>
			<?php
				$query =   "SELECT uqid, amount, rate, purpose, paybackDate, message  
									FROM `Applications`
									WHERE loanId = $loanId;";
				$result = mysqli_query($link, $query);
				$num = mysqli_num_rows($result); 
			?>
			Click here to <b><a href= <?php echo "\"include/deleteLoan.php?loanID=" . $loanId . "\""?> >DELETE</a></b> this loan.
			<br />
			<br /><br />

			<?php if($num != 0):?>
			<table border ="1">
				<tr>
					<th>Offer By</th>
					<th>Amount</th>
					<th>Rate</th>
					<th>Date</th>
					<th>Loan Score</th>
					<th>Default Ratio</th>
					<th>Message</th>
					<th>Respond</th>
				</tr>
				<?php
					
					if($result) {
						while ($row = mysqli_fetch_assoc($result)) {
							$thisUser = fetch_user_from_id($row['uqid']);
							$fullName = $thisUser['firstname']." ".$thisUser['surname'];
							$score = get_score($uqID) * 100;
							$numLoans = Num_Loans_Completed($row['uqid']);
							$numDefaulted = Num_Loans_Defaulted($row['uqid']);
							echo "<tr>";
								echo "<td>$fullName</td>";
								echo "<td>$row[amount]</td>";
								echo "<td>$row[rate]</td>";
								echo "<td>$row[paybackDate]</td>";
								echo "<td>$score"."%</td>";
								echo "<td>$numDefaulted of $numLoans</td>";
								echo "<td>$row[message]</td>";
								echo "<td id=\"buttonTd\">
								<button id=\"formButton\" onclick=\"window.location='include/acceptOffer.php?userID=" . $row['uqid'] . "&loanID=" . $loanId . "';\">Yes</button>
								<button id=\"formButton\" onclick=\"window.location='include/declineOffer.php?userID=" . $row['uqid'] . "&loanID=" . $loanId . "';\">No</button>
								</td>";
							echo "</tr>";
						}
					}
				?>

			</table>
			<?php endif ?>
		<?php else: ?>
			<h3>Indicate Interest</h3>
		<?php endif ?>
	<?php endif ?>
	
		<?php if ($level == 0 && $isActive == 0): /* Uninvolved user attempting to view inactive loan */?>	

			<span id="spanId"></span>
			<form action="accept.php?id=<?php echo $loanId;?>" method="post" name="acceptForm" class="inputs"><!-- id="app"> -->
				<label for="amount">Amount:</label><br>
				<input type="number" class="dollar" id="amount" name="amount" placeholder="i.e. 400" min="10" max="1000" text="Minimum amount $10, Max $1000" value="<?php if (isset($loan['amount'])) echo $loan['amount'];?>" required pattern="[0-9]{1,}">

				<br>

				<label for="rate">Rate:</label><br>
				<input type="number" step="any" class="percent" id="rate" name="rate" placeholder="i.e. 7.5" max="30.0" text="Must set a rate in numbers less than 30" value="<?php if (isset($loan['rate'])) echo $loan['rate'];?>" required pattern="[0-9]{1,}">									
				
				<br>

				<?php if ($loan['postType'] == 1): ?>
					<label for="purpose">Purpose:</label> <br>
					<input type="text" id="purpose" name="purpose" maxlength="40" required>
					<br>
				<?php endif ?>
				<?php 
					$date = $loan['paybackDate'];
					//echo "test";
					$minDate = date("Y-m-d", time()+86400);
				?>
				<label for="payback">Payback Date:</label><br>
				<input type="date" id="payback" name="payback" min="<?php echo $minDate;?>" value="<?php echo $date;?>" required>
				<br>

				<label for="message">Message (300 characters max):</label><br>
				<textarea name="message" placeholder="Leave a message..." rows="4" cols="50" maxlength="300"></textarea><br>

				<button name ="submit" type="submit" id="submit" value="Submit">Submit Offer</button>
			</form>


				<?php /*echo "<button type='button' onClick='location.href=\"accept.php?loanId=".$loanId."\"'>Accept Terms</button>" */ ?>
		<?php endif ?>
<?php endif ?>
	 <button TYPE="button" VALUE="Back" onClick="history.go(-1);">Back</button>

<div class="clear"></div>
</div><!-- /container -->


<?php include 'include/footer.php'; ?>



