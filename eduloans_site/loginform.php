<?php 
  include 'include/head.php';
  include 'include/topnav.php'; 

  $flag = $_REQUEST['flag'];
?>


<div class="container">
<!-- ADD CONTENT HERE -->
<h1> Login </h1>
<?php
    switch ($flag) {
        case 1:
            break;
        case 2:
            break;
        default:
            echo "<p>You have entered incorrect login-credentials, or tried to access a page which is only accessible when logged in. <br>Please try again:</p>";
    }




    
?>
<form method="POST" action="login.php">
    <fieldset id="inputs">
    <input id="username" type="email" name="Email" placeholder="Your email address" required>   
    <input id="password" type="password" name="Password" placeholder="Password" required>
    </fieldset>
    <fieldset id="actions">
    <input type="submit" name="submit" value="Log in" >
    <label><input type="checkbox" checked="checked"> Keep me signed in</label>
    </fieldset>
</form>

<!-- FINISH CONTENT HERE -->
<div class="clear"></div>

</div>


<?php include 'include/footer.php'; ?>