<?php
	ini_set('display_errors', '1');
	error_reporting(E_ALL);
	if(isset($_GET['apply']) && $_GET['apply']=="true")
		$notice = '<p style="color:blue;">報名成功，系統已寄出通知信至您所輸入的電子郵件信箱</p>';
	if(isset($_POST['name'])) {
		$notice = '';
		foreach($_POST as $key => $value) {
			if($key == "extra")
				continue;
			if(empty($value)) {
				$notice = '<p style="color:red;">您有部份必填欄位未填或輸入了無效的值</p>';
				break;
			}
			if(!preg_match('/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i', $_POST['email']))
				$notice = '<p style="color:red;">您有部份必填欄位未填或輸入了無效的值</p>';
			if(substr(md5($_POST['captcha']), 5, 10) != $_POST['answer'])
				$notice = '<p style="color:red;">您有部份必填欄位未填或輸入了無效的值</p>';
		}
		if(!$notice) {
			$To = htmlentities($_POST['email'], ENT_QUOTES, 'UTF-8');
			$Subject = '=?UTF-8?B?'. base64_encode('報名成功通知信'). '?=';
			$Body = '<p>本系統已收到您的報名資料，未來若有其他重要消息，將再寄出電子郵件通知。</p><br/>';
			$Body .= '<table width="500" cellpadding="3" cellspacing="0" border="1">';
			$Body .= '<tr><td width="100">姓名</td><td width="400">'. htmlentities($_POST['name'], ENT_QUOTES, 'UTF-8'). '</td></tr>';
			$Body .= '<tr><td>學校</td><td>'. htmlentities($_POST['school'], ENT_QUOTES, 'UTF-8'). '</td></tr>';
			$Body .= '<tr><td>生日</td><td>'. htmlentities($_POST['birthday_year'], ENT_QUOTES, 'UTF-8'). '/'.
			htmlentities($_POST['birthday_month'], ENT_QUOTES, 'UTF-8'). '/'.
			htmlentities($_POST['birthday_date'], ENT_QUOTES, 'UTF-8'). '</td></tr>';
			$Body .= '<tr><td>電子郵件信箱</td><td>'. htmlentities($_POST['email'], ENT_QUOTES, 'UTF-8'). '</td></tr>';
			$Body .= '<tr><td>身分證字號</td><td>'. htmlentities($_POST['id'], ENT_QUOTES, 'UTF-8'). '</td></tr>';
			$Body .= '<tr><td>特殊交代事項</td><td>'. nl2br(htmlentities($_POST['extra'], ENT_QUOTES, 'UTF-8')). '</td></tr>';
			$Body .= '</table>';
			$Body .= '<br/><p>本信由系統自動寄出，若您不曾報名本活動，或以上資料有誤，請回覆此信告知我們。</p>';
			$Header = "MIME-Version: 1.0\r\n". "Content-type: text/html; charset=UTF-8\r\n". "From:<ckefgisc17@gmail.com>\r\n";
			mail($To, $Subject, $Body, $Header);
			header('Location: mail.php?apply=true');
			exit();
		}
	}
	$number_1 = rand(1, 9);
	$number_2 = rand(1, 9);
	$answer = substr(md5($number_1+$number_2), 5, 10);
?>
<!DOCTYPE html>
<html>
	<body>
		<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
			<p><input type="text" name="name" value="<?php if(isset($_POST['name'])) echo $_POST['name']; ?>"/></p>
			<p><input type="text" name="school" value="<?php if(isset($_POST['school'])) echo $_POST['school']; ?>"/></p>
			<p><select name="birthday_year">
				<option value="1996" <?php if($_POST['birthday_year']=='1996') echo 'selected="selected"'; ?>>1996</option>
			</select><select name="birthday_month">
				<option value="07" <?php if($_POST['birthday_month']=='07') echo 'selected="selected"'; ?>>Jul</option>
			</select><select name="birthday_date">
				<option value="16" <?php if($_POST['birthday_date']=='16') echo 'selected="selected"'; ?>>16</option>
			</select></p>
			<p><input type="text" name="email" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>"/></p>
			<p><input type="text" name="id" value="<?php if(isset($_POST['id'])) echo $_POST['id']; ?>"/></p>
			<p><textarea name="extra"><?php if(isset($_POST['extra'])) echo $_POST['extra']; ?></textarea></p>
			<p><?php echo $number_1." + ".$number_2." = ?" ?><input type="text" name="captcha"/></p>
			<input type="hidden" name="answer" value="<?php echo $answer; ?>"/>
			<?php echo $notice; ?>
			<p><input type="submit" value="Send"/></p>
		</form>
	</body>
</html>