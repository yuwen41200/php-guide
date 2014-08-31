<?php
	require_once('connect.php');
	if(!function_exists('SQLValue')) {
		function SQLValue($value, $type) {
			global $connect;
			$value = htmlentities($value, ENT_QUOTES, 'UTF-8');
			$value = $connect->real_escape_string($value);
			switch($type) {
				case "varchar":
				case "text":
					$value = ($value != "") ? "'".$value."'" : "NULL";
					break;
				case "int":
					$value = ($value != "") ? intval($value) : "NULL";
					break;
				case "double":
					$value = ($value != "") ? floatval($value) : "NULL";
					break;
			}
			return $value;
		}
	}
	$selector = isset($_GET['key']) ? $_GET['key'] : -1;
	$query = sprintf("SELECT * FROM resource WHERE id = %d",
		SQLValue($selector, "int"));
	$result = $connect->query($query) or die('Error: '.$connect->error);
	$row = $result->fetch_assoc();
	if(empty($row))
		die('Error: Invalid Query String');
	if(!file_exists($row['location']))
		die('Error: The Requested File Does Not Exist');
	header('Location: '.$row['location']);
	exit();
?>