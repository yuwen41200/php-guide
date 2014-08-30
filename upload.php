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
	if(isset($_POST['filename'])) {
		$dir = 'files';
		$notice = '';
		if(empty($_POST['filename']))
			$notice = '<p style="color:red;">請輸入檔案名稱</p>';
		if($_FILES['uploadedFile']['error'] != 0)
			$notice .= '<p style="color:red;">上傳時發生錯誤</p>';
		if(!is_uploaded_file($_FILES['uploadedFile']['tmp_name']))
			$notice .= '<p style="color:red;">上傳的檔案無效</p>';
		if((!is_dir($dir)) || (!is_writable($dir)))
			$notice .= '<p style="color:red;">上傳目錄不存在或無法寫入</p>';
		if(!$notice) {
			$fileExt = explode(".", $_FILES['uploadedFile']['name']);
			$fileExt = $fileExt[count($fileExt)-1];
			$dest = $dir . "/" . substr(md5(uniqid(rand())), 0, 15) . "." . $fileExt;
			move_uploaded_file($_FILES['uploadedFile']['tmp_name'], $dest);
			$query = sprintf("INSERT INTO resource (filename, location) VALUES (%f, %s)",
				SQLValue($_POST['filename'], "text"),
				SQLValue($dest, "text"));
			if(!$connect->query($query))
				die('Error: '.$connect->error);
			header('Location: index.php');
			exit();
		}
	}
?>
<html>
	<body>
		<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" enctype="multipart/form-data">
			<p><input type="text" name="filename" value="<?php if(isset($_POST['filename'])) echo $_POST['filename']; ?>"/></p>
			<p><input type="file" name="uploadedFile"/></p>
			<?php echo $notice; ?>
			<p><input type="submit" value="Upload"></p>
		</form>
	</body>
</html>