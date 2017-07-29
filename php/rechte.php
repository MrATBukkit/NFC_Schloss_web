<?php
require_once('datenbank.php');
session_start();
$output = [];
$sql = "SELECT * FROM user WHERE id='".$_SESSION['uid']."'";
$db_erg = mysqli_query($db_link, $sql) or die("Lesen der Rechte Fehlgeschlagen: " . mysql_error());
$erg = mysqli_fetch_assoc($db_erg);
$output['admin'] = $erg['admin'];
echo json_encode($output);
?>