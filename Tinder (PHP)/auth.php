<?php

session_start();

if (!isset($_SESSION['user']['login'])) {
	echo "You are not logged in.";
	exit;
}

$connect = mysqli_connect('localhost','st2014','progress','st2014')or die(mysqli_error());  /*localhost pass */

?>