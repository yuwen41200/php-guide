<?php
	require_once('connect.php');
	if(!function_exists('SQLValue')) {
		function SQLValue($value, $type) {
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
	if(!isset($_GET['key']))
		die('Error: Invalid Query String');
	$selector = $_GET['key'];
	$query = sprintf("DELETE FROM table1 WHERE column1=%d LIMIT 1",
		SQLValue($selector, "int"),
	if(!$connect->query($query))
		die('Error: '.$connect->error);
	header('Location: index.php');
	exit();
?>