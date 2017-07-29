<?php
require_once('datenbank.php');
$sql = "SELECT * FROM userkey";
$db_erg = mysqli_query( $db_link, $sql );
while ($zeile = mysqli_fetch_array($db_erg))
{
	echo '<tr data-toggle="collapse" data-target="#key'.$zeile[''].'" data-parent="#account-table" class="">';
	
}
?>