<?php 
	include 'include/head.php'; 
	include 'include/topnav.php'; 
	include 'include/dbconn.php';
	include 'include/functions.php';

	Check_Login();
	$row = fetch_current_user();
	$uqID = $row['uqid'];

	if(isset($_POST['submit'])) {
		if($_POST['submit'] == 'Submit') {
	    	foreach($_POST as $key => $value) {
	    		$data[$key] = filter($value);
	    	}
		}
		// show debug info:
		echo "Debug: <br>";
		$type = $data['offerType'];
		echo "Form type: ".$type."<br>";
		echo "Amount: ".$data['amount']."<br>";
		echo "Payback date: ".$data['payback']."<br>";
		echo "This uq id: ".$uqID."<br>";


		if ($type === "borrow") {
			$sql_insert_loan = "INSERT into `Loans`
  			(`postType`, `amount`, `rate`, `purpose`, `paybackDate`, `borrowerID`)
  			VALUES
  			('0', '$data[amount]', '$data[rate]', '$data[purpose]', '$data[payback]', '$uqID')
  			";

  			echo "<br>Resulting query: ".$sql_insert_loan."<br>";

  			mysqli_query($link, $sql_insert_loan) or die("Insertion Failed:" . mysqli_error($link));
		} else if ($type === "lend") {
			$sql_insert_loan = "INSERT into `Loans`
  			(`postType`, `amount`, `rate`, `paybackDate`, `lenderID`)
  			VALUES
  			('1', '$data[amount]', '$data[rate]', '$data[payback]', '$uqID')
  			";

  			echo "<br>Resulting query: ".$sql_insert_loan."<br>";

  			mysqli_query($link, $sql_insert_loan) or die("Insertion Failed:" . mysqli_error($link));
		}

		echo "Debug end.";
		header("Location: overview.php?msg=3");
	} // end if post
?>
<script>
	$(document).ready(function (e) {
	// Hide all conditional form elements.
	
	$(".lend").hide();
	$(".borrow").show();

		// Upon selecting the type of this offer, show the appropriate
		// form fields:
		$("#offerType").change(function () {
			var selected = $("#offerType option:selected").val();
			if (selected == "borrow") {
				$(".lend").hide();
				$(".borrow").show();
			} else {	// selected == "lend"
				$(".borrow").hide();
				$(".lend").show();
			}
		});

	});
</script>

<div class="container">
	<h2>Post a loan offer/application:</h2>
		<form action="postloan.php" method="post" name="loanForm" id="inputs">
			<select name="offerType" id="offerType">
				<option value="borrow" selected="selected">I want to borrow money.</option>
				<option value="lend">I want to lend money.</option>
			</select>
			<br>

			<label for="amount">How much money do you wish to <span class="lend">lend</span><span class="borrow">borrow</span>:</label>
			<br>
			<input type="text" id="amount" name="amount" placeholder="i.e. 400" required pattern="[0-9]{1,}">
			<br>

			<span class="borrow">
				<label for="purpose">Please provide the purpose of this loan:</label>
				<br>
				<input type="text" id="purpose" name="purpose" placeholder="i.e. Groceries">
				<br>
			</span><!--/borrow-->

			<label for="rate">Specify the rate <span class="borrow"> maximum at which you are willing to pay back</span><span class="lend">minimum you expect to receive in return</span>:</label>
			<br>
			<input type="text" id="rate" name="rate" placeholder="i.e. 7.5">
			<br>

			<label for="payback">Specify the date by which you <span class="borrow">can pay back the loan</span><span class="lend">want to be paid back</span>:</label>
			<br>
			<input type="date" id="payback" name="payback">
			<br>

			<button name ="submit" type="submit" id="submit" value="Submit">Submit</button>
		</form><!--/inputs-->

	<div class="clear"></div>
</div><!-- /container -->

<?php include 'include/footer.php'; ?>