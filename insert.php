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
	if(isset($_POST['column2'])) {
		$notice = '';
		foreach($_POST as $value) {
			if(empty($value)) {
				$notice = '<p style="color:red;">請填寫所有欄位</p>';
				break;
			}
		}
		if(!$notice) {
			$query = sprintf("INSERT INTO table1 (column2, column3) VALUES (%f, %s)",
				SQLValue($_POST['column2'], "double"),
				SQLValue($_POST['column3'], "text"));
			if(!$connect->query($query))
				die('Error: '.$connect->error);
			header('Location: index.php');
			exit();
		}
	}
?>
<html>
	<body>
		<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
			<p><input type="text" name="column2" value="<?php if(isset($_POST['column2'])) echo $_POST['column2']; ?>"/></p>
			<p><textarea name="column3"><?php if(isset($_POST['column3'])) echo $_POST['column3']; ?></textarea></p>
			<?php echo $notice; ?>
			<p><input type="submit" value="Insert"></p>
		</form>
	</body>
</html>