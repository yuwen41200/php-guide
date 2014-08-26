<?php
	// connect.php
	$hostname = "localhost";
	$username = "root";
	$password = "root";
	$database = "project";
	$connect = new mysqli('p:'.$hostname, $username, $password, $database);
	if($connect->connect_error)
		die('Connect Error ('.$connect->connect_errno.') '.$connect->connect_error);
	if(!$connect->set_charset('utf8'))
		die('Error: '.$connect->error);
	// insert.php
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
	if(isset($_POST['column1'])) {
		$notice = '';
		foreach($_POST as $value) {
			if(empty($value)) {
				$notice = '<p style="color:red;">請填寫所有欄位</p>';
				break;
			}
		}
		if(!$notice) {
			$insertSQL = sprintf("INSERT INTO table1 (column1, column2, column3) VALUES (%s, %d, %f)",
				SQLValue($_POST['column1'], "text"),
				SQLValue($_POST['column2'], "int"),
				SQLValue($_POST['column3'], "double"));
			if(!$connect->query($insertSQL))
				die('Error: '.$connect->error);
			header('Location: index.php');
			exit();
		}
	}
?>