<?php
	$hostname = "localhost";
	$username = "root";
	$password = "root";
	$database = "project";
	$connect = new mysqli('p:'.$hostname, $username, $password, $database);
	if($connect->connect_error)
		die('Connect Error ('.$connect->connect_errno.') '.$connect->connect_error);
	if(!$connect->set_charset('utf8'))
		die('Error: '.$connect->error);
?>