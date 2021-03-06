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
	if(isset($_POST['rtIndex'])) {
		$notice = '';
		foreach($_POST as $value) {
			if(empty($value)) {
				$notice = '<p style="color:red;">請輸入內容及暱稱</p>';
				break;
			}
		}
		if(!$notice) {
			$query = sprintf("INSERT INTO reply (rtIndex, rName, rContent) VALUES (%d, %s, %s)",
				SQLValue($_POST['rtIndex'], "int"),
				SQLValue($_POST['rName'], "text"),
				SQLValue($_POST['rContent'], "text"));
			if(!$connect->query($query))
				die('Error: '.$connect->error);
			header('Location: forum.php');
			exit();
		}
	}
	$query = "SELECT * FROM topic ORDER BY tIndex DESC";
	$tResult = $connect->query($query) or die('Error: '.$connect->error);
?>
<!DOCTYPE html>
<html>
	<body>
		<?php while($tRow = $tResult->fetch_assoc()) { ?>
			<h1>Topic</h1>
			<p><?php echo $tRow['tTitle']; ?></p>
			<p><?php echo $tRow['tContent']; ?></p>
			<p><?php echo $tRow['tName']; ?></p>
			<p><?php echo $tRow['tTime']; ?></p>
			<h2>Reply</h2>
			<?php $query = sprintf("SELECT * FROM reply WHERE rtIndex = %d ORDER BY rIndex DESC", SQLValue($tRow['tIndex'], "int"));
			$rResult = $connect->query($query) or die('Error: '.$connect->error);
			while($rRow = $rResult->fetch_assoc()) { ?>
				<p><?php echo $rRow['rContent']; ?></p>
				<p><?php echo $rRow['rName']; ?></p>
				<p><?php echo $rRow['rTime']; ?></p>
			<?php } $rResult->close(); ?>
			<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
				<p><input type="text" name="rContent" value="<?php if($tRow['tIndex'] == $_POST['rtIndex']) echo $_POST['rContent']; ?>"/></p>
				<p><input type="text" name="rName" value="<?php if($tRow['tIndex'] == $_POST['rtIndex']) echo $_POST['rName']; ?>"/></p>
				<?php if($tRow['tIndex'] == $_POST['rtIndex']) echo $notice; ?>
				<p><input type="submit" value="Send"/></p>
				<input type="hidden" name="rtIndex" value="<?php echo $tRow['tIndex']; ?>"/>
			</form>
		<?php } ?>
	</body>
</html>
<?php
	$tResult->close();
?>