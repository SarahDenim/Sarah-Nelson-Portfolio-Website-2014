<?php

if (!defined('DB_HOST')) { // avoid superfluous defines
	define ("DB_HOST", "localhost"); // set database host
	define ("DB_USER", "sarahnel_sarah"); // set database user
	define ("DB_PASS", "Panachegirl!1"); // set database password
	define ("DB_NAME", "sarahnel_eduloansDB"); // set database name	
}

$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

?>