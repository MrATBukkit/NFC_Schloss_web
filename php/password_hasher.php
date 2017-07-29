<html>
<head>
</head>
<body>
<form method="POST">
Password<input type="password" name="pword">
<input type="submit">
</form>
<?php
require_once('../login/funktions.php');
if (isset($_POST['pword'])) {
	echo password_hash($_POST['pword'], PASSWORD_DEFAULT);
}
?>
</body>
</html>