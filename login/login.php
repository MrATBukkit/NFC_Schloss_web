<?php
require_once('funktions.php');
require_once('../php/datenbank.php');
session_start();
$hash='';
if (isset($_POST['email'])) {
	$email = $_POST['email'];
}
else {
	exit("	<div class='alert alert-warning'>\n
				<strong>Email!</strong> Bitte Email eingeben.\n
			</div>");}
if (isset($_POST['password'])) {
	$password = $_POST['password'];
}
else {
	exit("Bitte Passwort eingeben!");
}
$sql = "SELECT * FROM user WHERE email='".$email."';";
$db_erg = mysqli_query( $db_link, $sql );
if (!$db_erg) {
	exit("	<div class='alert alert-warning'>\n
				<strong>Email oder Passwort ist Falsch</strong>
			</div>");
}
while ($zeile = mysqli_fetch_array($db_erg)) {
	$hash = $zeile['pword'];
	$id = $zeile['id'];
	$aktiv = $zeile['aktiv'];
}
if (password_verify($password, $hash)) {
	if ($aktiv == 0) {
		exit("	<div class='alert alert-warning'>\n
				<strong>Ihr Account wurde noch nicht Aktiviert sie werden verst&auml;ndigt
				wenn sie Aktiviert wurden</strong>\n
			</div>");
	}
	$_SESSION['eingeloggt'] = false;
	$_SESSION['email'] = $email;
	$_SESSION['uid'] = $id;
	echo "success";
}
else {
	exit("	<div class='alert alert-warning'>\n
				<strong>Email oder Passwort ist Falsch</strong>\n
			</div>");
}
?>