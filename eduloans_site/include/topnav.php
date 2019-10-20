<?php 
	session_start();
 ?>
<body>
<script src="js/notification.js"></script>
<!-- wrapper starts -->
<div class="wrapper"> 
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
