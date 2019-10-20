<?php 
include 'include/head.php';
?>

<?php 
	if(session_id() == '' || !isset($_SESSION)) {
    	// session isn't started
    	session_start();
	}
 ?>
 <style>
 body, html {
 	background: none;
 }
 </style>
<body>

	<div class="parallax-image-wrapper parallax-image-wrapper-100" 
		data-anchor-target="#top + .gap" 
		data-bottom-top="transform:translate3d(0px, 200%, 0px)"
		data-top-bottom="transform:translate3d(0px, 0%, 0px)">

		<div
			class="parallax-image parallax-image-100"
			style="background-image:url(images/UQ_pic_edited_top.jpg)"
			data-anchor-target="#top + .gap"
			data-bottom-top="transform: translate3d(0px, -80%, 0px);"
			data-top-bottom="transform: translate3d(0px, 80%, 0px);"
		></div>
	</div>

	<div class="parallax-image-wrapper parallax-image-wrapper-100" 
		data-anchor-target="#what + .gap" 
		data-bottom-top="transform:translate3d(0px, 200%, 0px)"
		data-top-bottom="transform:translate3d(0px, 0%, 0px)">

		<div
			class="parallax-image parallax-image-100"
			style="background-image:url(images/UQ_pic2_small.jpg)"
			data-anchor-target="#what + .gap"
			data-bottom-top="transform: translate3d(0px, -80%, 0px);"
			data-top-bottom="transform: translate3d(0px, 80%, 0px);"
		></div>
	</div>

	<div class="parallax-image-wrapper parallax-image-wrapper-100" 
		data-anchor-target="#why + .gap" 
		data-bottom-top="transform:translate3d(0px, 200%, 0px)"
		data-top-bottom="transform:translate3d(0px, 0%, 0px)">

		<div
			class="parallax-image parallax-image-100"
			style="background-image:url(images/UQ_pic7_small.jpg)"
			data-anchor-target="#why + .gap"
			data-bottom-top="transform: translate3d(0px, -80%, 0px);"
			data-top-bottom="transform: translate3d(0px, 80%, 0px);"
		></div>
	</div>

	<div class="parallax-image-wrapper parallax-image-wrapper-100" 
		data-anchor-target="#lend + .gap" 
		data-bottom-top="transform:translate3d(0px, 200%, 0px)"
		data-top-bottom="transform:translate3d(0px, 0%, 0px)">

		<div
			class="parallax-image parallax-image-100"
			style="background-image:url(images/UQ_pic3_small.jpg)"
			data-anchor-target="#lend + .gap"
			data-bottom-top="transform: translate3d(0px, -80%, 0px);"
			data-top-bottom="transform: translate3d(0px, 80%, 0px);"
		></div>
	</div>

	<div class="parallax-image-wrapper parallax-image-wrapper-100" 
		data-anchor-target="#borrow + .gap" 
		data-bottom-top="transform:translate3d(0px, 200%, 0px)"
		data-top-bottom="transform:translate3d(0px, 0%, 0px)">

		<div
			class="parallax-image parallax-image-100"
			style="background-image:url(images/UQ_pic5_small.jpg)"
			data-anchor-target="#borrow + .gap"
			data-bottom-top="transform: translate3d(0px, -80%, 0px);"
			data-top-bottom="transform: translate3d(0px, 80%, 0px);"
		></div>
	</div>

	<div class="parallax-image-wrapper parallax-image-wrapper-100" 
		data-anchor-target="#how + .gap" 
		data-bottom-top="transform:translate3d(0px, 200%, 0px)"
		data-top-bottom="transform:translate3d(0px, 0%, 0px)">

		<div
			class="parallax-image parallax-image-100"
			style="background-image:url(images/UQ_pic4_small.jpg)"
			data-anchor-target="#how + .gap"
			data-bottom-top="transform: translate3d(0px, -80%, 0px);"
			data-top-bottom="transform: translate3d(0px, 80%, 0px);"
		></div>
	</div>


<div id="skrollr-body">

<!-- wrapper starts -->
<!--<div class="wrapper"> -->
<div class="whole-header" id="top">
  <!-- Header Starts -->
 <header>
    <div class="banner-container">
		<div class="logo"> <a href="index.php">
			<img src="images/logo_white.png" height="16%" width="16%"/></a> 
		</div>    
    <div class="clear"></div>	  
    </div>

  <!-- Nav start -->
  <?php if(!isset($_SESSION['email'])): ?> 
	<nav>
		<div class="nav-list">
		  <ul>
			<li style="margin-left: 0;"><a href="index.php">Home</a></li>
			<li><a href="lendinfo.php">Lend</a></li>
			<li><a href="borrowinfo.php">Borrow</a></li>
			<li><a href="how.php">How It Works</a></li>  
			<li><a href="about.php">About Us</a></li>     
		 </ul>	
		</div>   	
	    <!-- Login-Register Start --> 
        <div class="log-reg-widget">		
		  <ul>
		  <?php if(!isset($_SESSION['email'])): ?>
			<li id="login">
			  <a id="login-trigger" href="#">
				Log in <span>&#x25BC;</span>
			  </a>

			  <div id="login-content">
			  
				<form method="POST" action="login.php">
				  <fieldset id="inputs">
					<input id="username" type="email" name="Email" placeholder="Your email address" required>   
					<input id="password" type="password" name="Password" placeholder="Password" required>
				  </fieldset>
				  <fieldset id="actions">
					<input id="submit" type="submit" name="submit" value="Log in" >
					<label><input type="checkbox" checked="checked"> Stay signed in</label>
				  </fieldset>
				</form>
			  </div>
			                
			</li>
			
			<li id="signup">
			  <a href="register.php">Register</a>
			</li>
			<?php else: ?>
					<li>
						<div id="login">
							<div id="login-trigger">

							<a href="overview.php#tab-2">
								<img src="images/profile-icon3.png">
									Profile
							</a>
							</div>
						</div>
					</li>
					<li>
						<div id="signup">
							<a href="logout.php">Logout</a> 
						</div>
					</li>
			<?php endif ?>
		  </ul>
		</div>
		<!-- Login-Register End --> 
		<div class="clear"></div>  	
	</nav>
  <!-- Nav End --> 
  <!-- Member Nav Start -->
  <?php else: ?> 
	<nav>
		<div class="nav-list">
		  <ul>
			<li><a class="glow" href="overview.php">Dashboard</a></li>
			<li><a class="glow" href="lend.php">Lend</a></li>
			<li><a class="glow" href="borrow.php">Borrow</a></li>
			<li><a class="glow" href="about.php">About Us</a></li>
		  </ul>
		</div>  
	    <!-- Login-Register Start --> 
        <div class="log-reg-widget" style="margin-left: 150px;">		
		  <ul>
		  <?php if(!isset($_SESSION['email'])): ?>
			<li id="login">
			  <a id="login-trigger" href="#">
				Log in <span>&#x25BC;</span>
			  </a>

			  <div id="login-content">
			  
 
				<form method="POST" action="login.php">
				  <fieldset id="inputs">
					<input id="username" type="email" name="Email" placeholder="Your email address" required>   
					<input id="password" type="password" name="Password" placeholder="Password" required>
				  </fieldset>
				  <fieldset id="actions">
					<input id="submit" type="submit" name="submit" value="Log in" >
					<label><input type="checkbox" checked="checked">Stay signed in</label>
				  </fieldset>
				</form>
			  </div>
			                    
			</li>
			
			<li id="signup">
			  <a href="register.php">Register</a>
			</li>
			<?php else: ?>
				<!--
					<li>
						<div id="notification">
							<a href=Notification
						</div>
					</li>
				-->
					<li>
						<div id="login">
							<div id="login-trigger">

							<a href="overview.php#tabs-2">
								<img src="images/profile-icon3.png">
									Profile
								</a>								
							</div>
						</div>
					</li>
					<li>
						<div id="notifications">
							<?php include 'notifIcon.php'; ?>
						</div>
					</li>
					<li>
						<div id="signup">
							<a href="logout.php">Logout</a> 
						</div>
					</li>
			<?php endif ?>
		  </ul>
		</div>
		<!-- Login-Register End --> 

		<div class="clear"></div>
	</nav>

  <?php endif ?>
  <!-- Member Nav End -->   
</header> 
  <!-- Header ends --> 
</div>


		<div class="gap gap-100" style="background-image:url(images/UQ_pic_edited_top.jpg);" data-top="opacity: 1" data--200-top="opacity: 0">
		    	<div class="section-in" style="padding-top: 20%;">
		    		<h1>A new platform for student-to-student lending</h1>
					<div class="button_wrapper">
						<div class="index_button"><a href="#lend"><p>Lend</p></a></div>
						<div class="index_button" style="float: right;"><a href="#borrow"><p>Borrow</p></a></div>
					</div>
					<div class="arrow_wrapper">
						<img src="images/arrows.png">
					</div>
		  		</div>
  		</div><!-- /index_section -->
  		
  		<div class="content content-small" style="padding-top: 2em;" id="what">
				<div class="section-in">
					<p>Eduloans is a peer-to-peer loans service built by students, for students. <br>Low rates 
						are combined with the flexibility of student culture to create 
						a loan experience that is both convenient and trustworthy.</p>
						<a class="underline" href="about.php"><p>See more</p></a>
				</div>
		</div><!-- /index_section -->

		<div class="gap gap-50" style="background-image:url(images/UQ_pic2_small.jpg);"><div class="section-in"></div></div>

  		<div class="content content-medium" id="why">
				<div class="section-in">
					<h2>Why use Eduloans?</h2>
					<img class="section_img" src="images/bookbook.png">
					<p>Many students don't have the credit to secure much needed loans, which is why we 
						specialize in matching potential student loan partners. This direct approach loan 
						matching allows for better rates for both parties. No bank means no bank fees, and 
						flexible terms and conditions allow you to fit a loan or investment around your 
						lifestyle!</p>
				</div>
		</div><!-- /index_section -->

		<div class="gap gap-50" style="background-image:url(images/UQ_pic7_small.jpg);"><div class="section-in"></div></div>
		

  		<div class="content content-medium" id="lend">
				<div class="section-in">
					<h2>Lend </h2>
					<img class="section_img" src="images/pay.png">
					<p>EduLoans is not just an investment, but a contribution to the future of the student taking out the loan. For the investor, EduLoans offers great returns with minimal risk.</p>
					<a class="underline" href="lendinfo.php"><p>See more</p></a>
				</div>
		</div><!-- /index_section -->

		<div class="gap gap-50" style="background-image:url(images/UQ_pic2_small.jpg);"><div class="section-in"></div></div>
		
		<div class="content content-big" id="borrow">
				<div class="section-in">
					<h2>Borrow</h2>
					<img class="section_img" src="images/hand.png">
					<p>Borrowing money can be a harrowing experience. At EduLoans, 
						our goal is to match lenders with borrowers in a way that allows for personal 
						service and lower rates. Our quick and easy loan process allows you to pay off 
						debt while continuing on with your life.</p>
					<a class="underline" href="borrowinfo.php"><p>See more</p></a>
				</div><!-- /section-in -->
		</div><!-- /index_section -->

		<div class="gap gap-50" style="background-image:url(images/UQ_pic3_small.jpg);"></div>
  
		<div class="content content-big" id="how">
				<div class="section-in">
					<h2>How it Works</h2>
					<img class="section_img" src="images/gear.png">
					<p>At its heart, EduLoans is a peer-to-peer loan service that works by directly matching 
						investors with borrowers. Traditionally, banks will take hefty cuts from each loan 
						it sets up. At EduLoans, we take nothing. This means higher returns for investors, 
						and affordable payments for borrowers.</p>
					<a class="underline" href="how.php"><p>See more</p></a>
				</div><!-- /section-in -->
		</div><!-- /index_section -->		

		<div class="gap gap-50" style="background-image:url(images/UQ_pic4_small.jpg); padding-top: 5%;">
			<div class="section-in">
		    		<h1>Sound great? Register now!</h1>
					<div class="button_wrapper">
						<div class="index_button" style="float: none; margin: 0 auto;"><a href="register.php"><p>Register</p></a></div>
					</div>
		  		</div>
		</div>	

		  <!-- footer starts -->
  <div class="footer_push"></div>
  <div class="footer-index">
    <div class="footer-in">
      
      <div class="copy">
        <p>&copy; Copyright EduLoans 2014. All rights reserved.</p>
      </div>
      <div class="clear"></div>
    </div>
  </div>
  <!-- footer ends --> 


<!--</div><!-- /skrollr-body -->
</div> 

<script type="text/javascript" src="js/skrollr.min.js"></script>
<script type="text/javascript" src="js/skrollr.menu.min.js"></script>
    <script type="text/javascript">
    /*var s = skrollr.init();
    skrollr.menu.init(s, {
    	
    	animate: true,
    	smoothScrolling: false,
    	//How long the animation should take in ms.
    	//duration: function(currentTop, targetTop) {
        //By default, the duration is hardcoded at 500ms.
        return 1000;

        //But you could calculate a value based on the current scroll position (`currentTop`) and the target scroll position (`targetTop`).
        //return Math.abs(currentTop - targetTop) * 10;
    },
    });
	*/
	
	skrollr.init({
		smoothScrolling: false,
		mobileDeceleration: 0.004
	});
    </script>
  

</body>
</html>


<?php 
//include 'include/footer.php';
?>
