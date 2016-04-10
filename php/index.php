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
	$query = sprintf("SELECT * FROM table1 WHERE column1 = %d",
		SQLValue($selector, "int"));
	$result = $connect->query($query) or die('Error: '.$connect->error);
	while($row = $result->fetch_assoc()) {
		echo $row['column1']."<br>";
		echo $row['column2']."<br>";
		echo nl2br($row['column3'])."<br>";
		echo date('Y/m/d H:i:s', strtotime($row['column4']))."<br><br>";
	}
	echo $result->num_rows;
	$result->close();
?>