<?php
class MySQLDatabase{
var $link;
function connect($user, $password, $database){
	$this->link = mysql_connect('localhost', 'sarahnel_sarah', 'Panachegirl!1');
	if(!$this->link){
	die('Not connected : ' . mysql_error());
	}
	$db = mysql_select_db($database, $this->link);
	if(!$db){
	die ('Cannot use : ' . mysql_error());
	}
	return $this->link;
	}

function disconnect(){
	mysql_close($this->link);
}
}
?>