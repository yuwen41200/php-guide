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
	if(isset($_POST['column1'])) {
		$notice = '';
		foreach($_POST as $value) {
			if(empty($value)) {
				$notice = '<p style="color:red;">請填寫所有欄位</p>';
				break;
			}
		}
		if(!$notice) {
			$query = sprintf("UPDATE table1 SET column2=%f column3=%s WHERE column1=%d LIMIT 1",
				SQLValue($_POST['column2'], "double"),
				SQLValue($_POST['column3'], "text"),
				SQLValue($_POST['column1'], "int"));
			if(!$connect->query($query))
				die('Error: '.$connect->error);
			header('Location: index.php?key='.$_POST['column1']);
			exit();
		}
	}
	$selector = isset($_GET['key']) ? $_GET['key'] : -1;
	$query = sprintf("SELECT * FROM table1 WHERE column1 = %d",
		SQLValue($selector, "int"));
	$result = $connect->query($query) or die('Error: '.$connect->error);
	$row = $result->fetch_assoc();
	if(empty($row))
		die('Error: Invalid Query String');
?>
<!DOCTYPE html>
<html>
	<body>
		<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
			<p><input type="text" name="column2" value="<?php if(isset($_POST['column2'])) echo $_POST['column2']; else echo $row['column2']; ?>"/></p>
			<p><textarea name="column3"><?php if(isset($_POST['column3'])) echo $_POST['column3']; else echo $row['column3']; ?></textarea></p>
			<?php echo $notice; ?>
			<p><input type="submit" value="Update"/></p>
			<input type="hidden" name="column1" value="<?php echo $row['column1']; ?>"/>
		</form>
	</body>
</html>
<?php
	$result->close();
?>