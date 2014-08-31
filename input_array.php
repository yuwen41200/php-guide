<?php
	$num = 5;
	if(isset($_POST['column1'])) {
		for($i=0; $i<$num; $i++) {
			echo $_POST['column1'][$i]."<br>";
			echo $_POST['column2'][$i]."<br><br>";
		}
	}
?>
<!DOCTYPE html>
<html>
	<body>
		<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
			<?php for($i=0; $i<$num; $i++) { ?>
				<p><input type="text" name="column1[]" value="<?php if(isset($_POST['column1'][$i])) echo $_POST['column1'][$i]; ?>"/></p>
				<p><input type="text" name="column2[]" value="<?php if(isset($_POST['column2'][$i])) echo $_POST['column2'][$i]; ?>"/></p>
			<?php } ?>
			<p><input type="submit" value="Submit"/></p>
		</form>
	</body>
</html>