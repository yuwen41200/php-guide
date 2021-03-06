<?php
	session_start();
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
	if(isset($_POST['usr'])) {
		$notice = '';
		foreach($_POST as $value) {
			if(empty($value)) {
				$notice = '<p style="color:red;">請輸入帳號及密碼</p>';
				break;
			}
		}
		if(!$notice) {
			$_POST['pwd'] = md5($_POST['pwd']);
			$query = sprintf("SELECT * FROM user WHERE username=%s AND password=%s",
				SQLValue($_POST['usr'], "varchar"),
				SQLValue($_POST['pwd'], "varchar"));
			$result = $connect->query($query) or die('Error: '.$connect->error);
			if($result->num_rows) {
				session_regenerate_id(true);
				$_SESSION['LoginUser'] = $_POST['usr'];
				header('Location: index.php');
				exit();
			}
			else {
				$notice = '<p style="color:red;">登入失敗</p>';
				$result->close();
			}
		}
	}
?>
<!DOCTYPE html>
<html>
	<body>
		<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
			<p><input type="text" name="usr" value="<?php if(isset($_POST['usr'])) echo $_POST['usr']; ?>"/></p>
			<p><input type="password" name="pwd" value="<?php if(isset($_POST['pwd'])) echo $_POST['pwd']; ?>"/></p>
			<?php echo $notice; ?>
			<p><input type="submit" value="Login"/></p>
		</form>
	</body>
</html>