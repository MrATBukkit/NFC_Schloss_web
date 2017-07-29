<?php
require_once('../php/datenbank.php');
require_once('GoogleAuthenticator.php');
session_start();
$ga = new PHPGangsta_GoogleAuthenticator();
if (isset($_POST['authcode'])) {
	$authcode = $_POST['authcode']; }
else {
	exit("	<div class='alert alert-warning'>\n<strong>Code!</strong> Bitte Code eingeben.\n</div>");}
$sql = "SELECT * FROM user WHERE id='".$_SESSION['uid']."';";
$db_erg = mysqli_query( $db_link, $sql );
$zeile = mysqli_fetch_assoc($db_erg);
$secret = $zeile['securitykey'];
$sql = "SELECT * FROM blacklist WHERE userid='".$_SESSION['uid']."';";
$db_erg = mysqli_query( $db_link, $sql );
while ($zeile = mysqli_fetch_array($db_erg)){
	if ($authcode == $zeile['code']) {
		exit("	<div class='alert alert-warning'>\n
					<strong>Code!</strong> Die&szlig;er Code wurde schon verwendet.\n
				</div>"); }
}
$sql = "INSERT INTO blacklist (`code`, `userid`) 
		VALUES ('".$authcode."', '".$_SESSION['uid']."');";
mysqli_query($db_link, $sql) or die("Code konnte nicht in die Datenbank geladen werden: " . mysql_error());
$sql = "SELECT count(userid) as versuche FROM blacklist WHERE userid='".$_SESSION['uid']."';";
$db_erg = mysqli_query( $db_link, $sql );
$zeile = mysqli_fetch_assoc($db_erg);
if ($zeile['versuche'] > 4) {
	exit("	<div class='alert alert-danger'>\n
				<strong>Logins!</strong> Zu viele Login versuche
				in den Letzten paar Minuten bitte versuchen sie es sp&auml;ter erneut.\n
			</div>\n"); }
if ($ga->verifyCode($secret, $authcode, 2)) {
	$_SESSION['eingeloggt'] = true;
	echo "success"; }
else {
	echo "	<div class='alert alert-warning'>\n<strong>Code!</strong> Falscher Code eingeben.\n</div>\n"; }
?>