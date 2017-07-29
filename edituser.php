<?php
require_once('./php/config.php');
session_start();
$db_link = mysqli_connect(MYSQL_HOST, MYSQL_BENUTZER, MYSQL_KENNWORT, MYSQL_DATENBANK);
mysqli_set_charset($db_link, 'utf8');
$sql = "SELECT * FROM user WHERE id='".$_SESSION['uid']."'";
$db_erg = mysqli_query($db_link, $sql) or die("Lesen der Rechte Fehlgeschlagen: " . mysql_error());
$erg = mysqli_fetch_assoc($db_erg);
if ($erg['admin'] == 0)
{
	echo "Kein Admin";
	exit(0);
}
if (!(isset($_GET['uid'])))
{
	echo "Keine ID angegeben";
	exit(0);
}
if ($_GET['method'] == "aktiv")
{
	$sql = "UPDATE user SET aktiv='1' WHERE id='".$_GET['uid']."'";
}
elseif ($_GET['method'] == "delet")
{
	$sql = "DELETE FROM user WHERE id='".$_GET['uid']."'"; 
}
elseif ($_GET['method'] == "giveadmin")
{
	$sql = "UPDATE user SET admin='1' WHERE id='".$_GET['uid']."'";
}
elseif ($_GET['method'] == "removeadmin")
{
	$sql = "UPDATE user SET admin='0' WHERE id='".$_GET['uid']."'";
}
else
{
	echo "Ung&uuml;ltiee Methode";
	exit(0);
}
mysqli_query($db_link, $sql) or die("Lesen der Rechte Fehlgeschlagen: " . mysql_error());
?>