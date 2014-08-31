<?php
	session_start();
	if(isset($_POST['logout']) && $_POST['logout']=="true") {
		unset($_SESSION['LoginUser']);
		header('Location: index.php');
		exit();
	}
?>
<!DOCTYPE html>
<html>
	<body>
		<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
			<p><?php echo $_SESSION['LoginUser']; ?></p>
			<p><input type="submit" value="Logout"/></p>
			<input type="hidden" name="logout" value="true"/>
		</form>
	</body>
</html>