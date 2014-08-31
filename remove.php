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
	if(isset($_GET['remove']) && $_GET['remove']=="true") {
		if(!isset($_GET['key']))
			die('Error: Invalid Query String');
		$selector = $_GET['key'];
		$query = sprintf("SELECT * FROM resource WHERE id = %d",
			SQLValue($selector, "int"));
		$result = $connect->query($query) or die('Error: '.$connect->error);
		$row = $result->fetch_assoc();
		if(empty($row))
			die('Error: Invalid Query String');
		if(file_exists($row['location']))
			unlink($row['location']);
		$query = sprintf("DELETE FROM resource WHERE id=%d LIMIT 1",
			SQLValue($selector, "int"),
		if(!$connect->query($query))
			die('Error: '.$connect->error);
		header('Location: index.php');
		exit();
	}
?>