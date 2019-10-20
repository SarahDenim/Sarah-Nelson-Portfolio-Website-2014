<?php 
	include 'include/head.php';
	include 'include/topnav.php';
	include 'include/dbconn.php';
	include 'include/functions.php';

	Check_Login();
	$row = fetch_current_user();
	$uqID = $row['uqid'];
?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="js/ajaxRequests.js"></script>
<script src="js/jquery.nouislider.all.js"></script>

<div class="container">
	<h2>BORROW</h2>
	<p>Welcome to the borrow page. Here, you can browse available loans which
	have been posted by other users. Use the filters to limit the results to 
	terms which are acceptable to you.</p>

	
	<div id="searchAmount"><h3> Search Loan Ammount </h3><br />
		<div id="slider"></div><br />
		<form>
			MIN: <input type="text" id="filter_minsum" onchange="ajax_lend_filter()"> 
			MAX: <input type="text" id="filter_maxsum" onchange="ajax_lend_filter()"> 	
		</form>
	</div>
	
	<div id="searchRate"><h3> Rate (%)</h3><br />
		<div id="slider2"></div><br />
		<form>
			MIN%: <input type="text" id="filter_minsum2" onchange="ajax_lend_filter()"> 
			MAX%: <input type="text" id="filter_maxsum2" onchange="ajax_lend_filter()"> 	
		</form>
	</div>		
	

<script>
$('#slider').noUiSlider({
	start: [ 0, 500 ],
	connect: true,
	range: {
		'min': 0,
		'max': 1000
	}
});

$('#slider').Link('lower').to($('#filter_minsum'), null, wNumb({
	decimals: 0

}));

$('#slider').Link('upper').to($('#filter_maxsum'), null, wNumb({
	decimals: 0
}));

$("#slider").on({
	change: function(){
		ajax_lend_filter();
	}
});

$("#slider").on({
	slide: function(){
		ajax_lend_filter();
	}
});
</script>

<script>
$('#slider2').noUiSlider({
	start: [ 0, 10 ],
	connect: true,
	range: {
		'min': 0,
		'max': 30
	}
});

$('#slider2').Link('lower').to($('#filter_minsum2'), null);

$('#slider2').Link('upper').to($('#filter_maxsum2'), null);

$("#slider2").on({
	change: function(){
		ajax_lend_filter();
	}
});

$("#slider2").on({
	slide: function(){
		ajax_lend_filter();
	}
});
</script>

	<p id="lendToday"><img src="images/borrow2.jpg"><br/>
	Apply for a Loan Here:
	<br/><button class="lendTodayButton" id="popup" onclick ="div_show()">Apply</button>	
	</p>
<script>
	function div_show(){ 
	document.getElementById('popUpApply').style.display = "block";
	}

	function div_hide(){ 
	document.getElementById('popUpApply').style.display = "none";
	}
</script>
	
 <div id="popUpApply">
	 <!-- Popup div starts here -->
	<div id="popUpForm"> 
	
		<form action="postloan.php" method="post" name="loanForm" id="form">	
			<img src="images/close.png" id="close" onclick ="div_hide()" />
			<h2>Loan Form</h2><hr/>		
		<!--<form action="postloan.php" method="post" name="loanForm" id="inputs">-->
			<select name="offerType" id="offerType">
				<option value="borrow" selected="selected">I want to borrow money.</option>
				<option value="lend">I want to lend money.</option>
			</select>
			<br>

			<label for="amount">How much money do you wish to <span class="lend">lend</span><span class="borrow">borrow</span>:</label>
			<br>
			<input type="number" class="dollar" id="amount" name="amount" placeholder="i.e. 400" min="10" max="1000" text="Minimum amount $10, Max $1000" required pattern="[0-9]{1,}">
			<br>

			<span class="borrow">
				<label for="purpose">Please provide the purpose of this loan:</label>
				<br>
				<input type="text" id="purpose" name="purpose" placeholder="i.e. Groceries">
				<br>
			</span><!--/borrow-->

			<label for="rate">Specify the percentage rate <span class="borrow"> maximum at which you are willing to pay back</span><span class="lend">minimum you expect to receive in return</span>:</label>
			<br>
			<input type="number" step="any" class="percent" id="rate" name="rate" placeholder="i.e. 7.5" min="0" max="30" text="Must set a rate in numbers less than 30" required pattern="[0-9]{1,}">
			<br>

			<label for="payback">Specify the date by which you <span class="borrow">can pay back the loan</span><span class="lend">want to be paid back</span>:</label>
			<br>
			<?php
				// determine min and max dates for payback: 
				$minDate = date("Y-m-d", time()+86400);			// tomorrow
				$maxDate = date("Y-m-d", time()+(86400*365));	// 1 year from now
			?>
			<input type="date" id="payback" name="payback" min="<?php echo $minDate;?>" max="<?php echo $maxDate;?>" required>
			<br>

			<button name ="submit" type="submit" id="lendSubmit" value="Submit">Submit</button>	
		</form>

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
	</div> 
 <!-- Popup div ends here -->
 </div>


	<h2 id="h2borrow">LENDERS OFFERING LOANS</h2>
	<div id="sorters"> <label>Sort by:</label>
		<select id="sortBy"  onchange="ajax_lend_filter()">
			<option value="paybackDate">Due Date</option>
  			<option value="amount">Loan Size</option>
  			<option value="rate">Rate</option>
			</select>
		<select id="sortDir"  onchange="ajax_lend_filter()">
			<option value="DESC">Descending</option>
  			<option value="ASC">Ascending</option>
		</select>	
	</div>

	<div id="tableOfResults"></div>
<!-- FINISH CONTENT HERE -->
	
 	<div class="clear"></div>
</div><!-- /container -->
<script>
	ajax_borrow_filter();
</script>

<?php include 'include/footer.php'; ?>