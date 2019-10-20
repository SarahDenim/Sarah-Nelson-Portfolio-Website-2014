<?php 
	include 'include/head.php'; 
	include 'include/topnav.php'; 
	include 'include/dbconn.php';
	include 'include/functions.php';
	
	Check_Login();

	$userID = $_REQUEST['id'];
	//echo "userID:".$userID."<br>";
	if ($userID == 0) {
		//echo "is current user!<br>";
		$user = fetch_current_user();
	} else {
		$user = fetch_user_from_id($userID);
	}

	if (is_null($user)){
		//invalid user id
		header("Location: error.php?errorNo=4");
	}



	$name = $user['firstname'];
	$surname = $user['surname'];
	$uqID = $user['uqid'];
	$email = $user['email'];
	$active_loans = Get_Active_Num($uqID);
	$owing = Get_Currently_Owing($uqID);
	$largestPaidBack = Get_Largest_PaidBack($uqID);
	$owed = Get_Currently_Owed($uqID);
	$avgBorrowed = Get_Avg_Borrowed($uqID);
	$avgLent = Get_Avg_Lent($uqID);
	$borrowTotal = Get_Borrowed_Total($uqID);
	$lentTotal = Get_Lent_Total($uqID);
	$numLenders = Get_Num_Lenders($uqID);
	$reliability = get_reliability($uqID) * 100;
	$timeliness = get_timeliness($uqID) * 100;
	$communication = get_communication($uqID) * 100;
	$score = get_score($uqID) * 100;
	$numLoans = Num_Loans_Completed($uqID);
	$numDefaulted = Num_Loans_Defaulted($uqID);
	$imgUrl = $user['imgUrl'];
?>





<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
<link rel="stylesheet" href="/resources/demos/style.css">
<script>
$(function() {
$( "#tabs" ).tabs();
});
</script>




<div class="container">
<!-- ADD CONTENT HERE -->
<div class="profile">

	
	<div id="tabs">
		  <ul>
			
			<li><a class="bold" href="#tabs-2">PROFILE</a></li>
			<li><a class="bold" href="#tabs-3">STATS</a></li>
			<li><a class="bold" href="#tabs-4">HISTORY</a></li>	
		  </ul>
		
				
				
				<div id="tabs-2">
						<div id="profileInfo">
							<div class="profileImage">
								<?php echo "<img src='$imgUrl'>"; ?>	
							</div>
							<div>
								<h1>
									<?php echo "$name $surname";?>
								</h1>	
								<p>Student number: <?php echo "$uqID";?></p>
								<p>Email: <?php echo "$email";?></p>
								<p>Mobile: <?php echo $userID == 0? $user['phone'] : '**********';?></p>
								
							</div>
						</div>
						<div id="profileh2"><h2>PROFILE <!--<button id ="editButton" onClick="location.href='editProfile.php'">EDIT</button>--></h2></div>
					
					<table class="profileTable">
						<h3>Financial Situation</h3>
						<tr>
							<td>Employment Status:</td>
							<td>

							<?php 
								switch ($user['employment']) {
									case 0:
										echo "I am currently unemployed.";
										break;
									case 1:
										echo "I have casual employment.";
										break;
									case 2:
										echo "I have part-time employment.";
										break;
									case 3:
										echo "I have full-time employment.";
										break;
									default:
										echo "No information provided / found";
								} 
							?>

							</td>
						</tr>
						<tr>
							<td>Primary source of income:</td>
							<td><?php echo $user['jobTitle'];?></td>
						</tr>			
						<tr>
							<td>Weekly income:</td>
							<td>$<?php echo $user['income'];?></td>
						</tr>		
					</table>
					<table class="profileTable">
						<h3>Additional Information</h3>
						<tr>
							<td>Degree:</td>
							<td>
							<?php 
								switch ($user['degree']) {
									case 0:
										echo "Bachelor of Business Management.";
										break;
									case 1:
										echo "Bachelor of Information Technology.";
										break;
									case 2:
										echo "Bachelor of Arts.";
										break;
									case 3:
										echo "Bachelor of Engineering";
										break;
									case 4:
										echo "Bachelor of Commerce";
										break;
									case 5:
										echo "Bachelor of Science";
										break;
									
									default:
										echo "No information provided / found";
								} 
							?>
							</td>
						</tr>	
						<tr>
							<td>Semester:</td>
							<td><?php echo $user['semester'];?></td>
						</tr>	
						<tr>
							<td>Renting Situation</td>
							<td>

							<?php 
								switch ($user['rent']) {
									case 0:
										echo "I am paying rent.";
										break;
									case 1:
										echo "I am not paying rent.";
										break;
									default:
										echo "No information provided / found";
								} 
							?>

							</td>
						</tr>	
						<tr>
							<td>About me:</td>
							<td><?php echo $user['aboutMe'];?></td>
						</tr>					
					</table>	
				</div>

				<div id="tabs-3">
					<h2>QUICK STATS</h2>
					
					
					<div class="sqblocks">
						<div id="block">
							<h4>Payback Ratio</h4> <h5> <?php echo $score;?>% </h5>
						</div>
						<div id="block">
							<h4>Currently owing</h4> <h5>$<?php echo$owing;?></h5>
						</div>
						<div id="block">						
							<h4>Currently owed</h4>  <h5>$<?php echo $owed;?></h5>
						</div>
						<div id="block">
							<h4>Active Loans</h4> <h5> <?php echo $active_loans;?> </h5>
						</div>	
					</div>
					
					
					
					
					
					
					
					<h3>Conduct</h3>
					<table class="profileTable">	
						<tr>
							<td>Reliability</td>
							<td><?php echo $reliability;?>%</td>		
						</tr>
						<tr>
							<td>Timeliness</td>
							<td><?php echo $timeliness;?>%</td>
						</tr>
						<tr>
							<td>Communication</td>
							<td><?php echo $communication;?>%</td>
						</tr>
					</table>	
					<h3>Borrowing</h3>	
					<table class="profileTable">	
						<tr>
							<td>Average Loan Amount:</td>
							<td><?php echo $avgBorrowed;?></td>		
						</tr>
					
						<tr>
							<td>Number of Lenders borrowed from:</td>
							<td><?php echo $numLenders;?></td>		
						</tr>
						
						<tr>
							<td>Currently owing on eduloans:</td>
							<td>$<?php echo$owing;?></td>		
						</tr>
						
						<tr>
							<td>Money Borrowed</td>
							<td><?php echo $borrowTotal;?></td>
						</tr>
						
						
						<tr>
							<td>Largest amount paid back</td>
							<td><?php echo $largestPaidBack;?></td>
						</tr>
						
						<tr>
							<td>Loans paid back</td>
							<td><?php echo $numLoans;?></td>
						</tr>
						<tr>
							<td>Loans defaulted</td>
							<td><?php echo $numDefaulted;?></td>
						</tr>
					</table>
					<h3>Lending:</h3>					
					<table class="profileTable">	
						<tr>
							<td>Average Loan Amount:</td>
							<td><?php echo $avgLent;?></td>		
						</tr>
						
						<tr>
							<td>Currently lending on eduloans:</td>
							<td><?php echo $owed;?></td>		
						</tr>
						<tr>
							<td>Total money lent:</td>
							<td><?php echo $lentTotal;?></td>		
						</tr>					
						
					</table>							
				</div>
				
				<div id="tabs-4">
					<h2>HISTORY</h2>
					<h3>Borrowing</h3>
					<table border="1">
						<tr>
							
							<th>Completion Date</th>
							<th>Amount</th>
							<th>incl. Interest</th>
							<th>Repayment Made</th>
							<th>Lender</th>	
							<th>Comment</th>						
							
						</tr>
					<?php
						$query =   "SELECT * 
									FROM `Loans`
									WHERE borrowerID = $uqID
									AND isCompleted = '1'";
									
						$result = mysqli_query($link, $query);
						if($result) {
									while($row = mysqli_fetch_assoc($result)) {
										$thisUser = fetch_user_from_id($row['lenderID']);
										$fullName = $thisUser['firstname']." ".$thisUser['surname'];
										echo "<tr onClick=\"document.location='loan.php?loanId=".$row['id']."';\">";
											echo "<td>$row[completeDate]</td>";
											echo "<td>$row[amount]</td>";
											echo "<td>".$row['amount']*(1+$row['rate']/100)." @ $row[rate]"."%</td>";
											echo "<td>$row[score]</td>";
											echo "<td><a href='profile.php?id=".$row['lenderID']."'>$fullName</a></td>";
											echo "<td>$row[comment]</td>";
										echo "</tr>";
									}
								}	
					?>			
					</table>
					<h3>Lending</h3>
					<table border="1">
						<tr>
							
							<th>Completion Date</th>
							<th>Amount</th>
							<th>incl. Interest</th>
							<th>Repayment Made</th>
							<th>Borrower</th>	
							<th>Comment</th>						
							
						</tr>
					<?php
						$query =   "SELECT * 
									FROM `Loans`
									WHERE lenderID = $uqID
									AND isCompleted = '1'";
									
						$result = mysqli_query($link, $query);
						if($result) {
									while($row = mysqli_fetch_assoc($result)) {
										$thisUser = fetch_user_from_id($row['borrowerID']);
										$fullName = $thisUser['firstname']." ".$thisUser['surname'];
										echo "<tr onClick=\"document.location='loan.php?loanId=".$row['id']."';\">";
											echo "<td>$row[completeDate]</td>";
											echo "<td>$row[amount]</td>";
											echo "<td>".$row['amount']*(1+$row['rate']/100)." @ $row[rate]"."%</td>";
											echo "<td>$row[score]</td>";
											echo "<td><a href='profile.php?id=".$row['borrowerID']."'>$fullName</a></td>";
											echo "<td>$row[comment]</td>";
										echo "</tr>";
									}
								}	
					?>			
					</table>
			</div>	  			
		</div><!--end tabs div-->
</div >	 <!--end profile div-->



<!-- FINISH CONTENT HERE -->
	
<div class="clear"></div>
</div>


<?php include 'include/footer.php'; ?>