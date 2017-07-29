<?php
session_start();
require_once('config.php');
$db_link = mysqli_connect(MYSQL_HOST, MYSQL_BENUTZER, MYSQL_KENNWORT, MYSQL_DATENBANK);
mysqli_set_charset($db_link, 'utf8');
if (isset($_GET['methode']))
{
	$methode = $_GET['methode'];
	if ($methode == "delet")
	{
		if (isset($_GET['id']))
		{
			$id = $_GET['id'];
		}
		else
		{
			exit('	<div class="alert alert-warning">
						<strong>Warnung!</strong> Es ist ein Interner Fehler aufgetr&auml;ten
					</div>');
		}
		$sql = "DELETE FROM userkey WHERE id=$id;";
		mysqli_query($db_link, $sql) or die("Fehler beim l&ouml;schen");
		exit();
	}
}
else
{
	exit('<div class="alert alert-warning">
			<strong>Warnung!</strong> Es ist ein Interner Fehler aufgetr&auml;ten
		  </div>');
}

if (isset($_GET['icon']))
{
	$icon = $_GET['icon'];
}
else
{
	exit('<div class="alert alert-warning">
			<strong>Warnung!</strong> Das Icon konnte nicht an den Server &uuml;bertragen werden
		  </div>');
}
if (isset($_GET['beschreibung']))
{
	$beschreibung = $_GET['beschreibung'];
}
else
{
	exit('<div class="alert alert-warning">
			<strong>Warnung!</strong> Die Bezeichnung konnte nicht an den Server &uuml;bertragen werden
		  </div>');
}
if (isset($_GET['seriennummer']))
{
	$seriennummer = $_GET['seriennummer'];
}
else
{
	exit('<div class="alert alert-warning">
			<strong>Warnung!</strong> Die Seriennummer konnte nicht an den Server &uuml;bertragen werden
		  </div>');
}
if (isset($_SESSION['uid']))
{
	$uid = $_SESSION['uid'];
}
else
{
	exit('<div class="alert alert-warning">
			<strong>Warnung!</strong> Sie sind nicht eingeloggt 
		  </div>');
}
if ($_GET['methode'] == "erstellen")
{
	$sql = "INSERT INTO userkey (keyid, bezeichnung, userid, device)
			VALUES ('$seriennummer','$beschreibung','$uid', '$icon');";

}
elseif ($_GET['methode'] == "update")
{
	if (isset($_GET['id']))
	{
		$id = $_GET['id'];
	}
	else
	{
		exit('	<div class="alert alert-warning">
					<strong>Warnung!</strong> Es ist ein Interner Fehler aufgetr&auml;ten
				</div>');
	}
	$sql = "UPDATE userkey SET keyid='$seriennummer', bezeichnung='$beschreibung', userid='$uid', device='$icon' WHERE id='$id'";
}
else
{
	exit('<div class="alert alert-warning">
			<strong>Warnung!</strong> Es ist ein Interner Fehler aufgetr&auml;ten
		  </div>');
}
mysqli_query($db_link, $sql) or die("Fehler beim schreiben in die Datenbank");
?>