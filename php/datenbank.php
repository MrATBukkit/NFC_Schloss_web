<?php
//Lädt die Konvig Datei
require_once('config.php');
//Speichert den Mysqli_connect Befehl in eien Variable
$db_link = mysqli_connect(MYSQL_HOST, MYSQL_BENUTZER, MYSQL_KENNWORT);
//Zeichensatz wird auf UTF8 gesetzt
mysqli_set_charset($db_link, 'utf8');
//Datenbank wird erstelllt
$sql = 'CREATE DATABASE IF NOT EXISTS '.MYSQL_DATENBANK;
//Verbindung mit der SQL Server und der obere SQL befehl wird ausgeführt
mysqli_query($db_link, $sql) or die("Schreiben der Datenbank fehlgeschlagen: " . mysql_error());
//Speichert den Mysqli_connect Befehl in eien Variable
$db_link = mysqli_connect(MYSQL_HOST, MYSQL_BENUTZER, MYSQL_KENNWORT, MYSQL_DATENBANK);
//Tabelle user wird erstellt
$sql = "CREATE TABLE IF NOT EXISTS user (
		id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
		email varchar(40),
		Vorname varchar(20),
		Nachname varchar(20),
		securitykey varchar(50),
		pword varchar(200),
		aktiv boolean not Null default 0,
		admin boolean not NULL default 0);";
//Verbindung mit der SQL Server und der obere SQL befehl wird ausgeführt
mysqli_query($db_link, $sql) or die("Schreiben der Tabelle user fehlgeschlagen: " . mysql_error());
$sql = "CREATE TABLE IF NOT EXISTS blacklist (
		id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
		code int(6) NOT NULL,
		userid int NOT NULL,
		usetime TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP)";	
mysqli_query($db_link, $sql) or die("Schreiben der Tabelle blacklist fehlgeschlagen: " . mysql_error());
$sql = "CREATE TABLE IF NOT EXISTS userkey (
		id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
		keyid varchar(20) NOT NULL,
		bezeichnung varchar(80) NULL,
		userid int NOT NULL,
		device varchar(20));";
mysqli_query($db_link, $sql) or die("Schreiben der Tabelle key fehlgeschlagen: " . mysql_error());
$sql = "SELECT count(email) as anzahl FROM user WHERE `email`='root@root.at'";
//Sql Befehl wird ausgeführt und das Ergebniss wird in db_erg gespeichert
$db_erg = mysqli_query( $db_link, $sql );
//Prüft ob Die SQL-Abfrage überhaupt ein Ergebnis liefert wenn nicht wird wieder ein Fehler ausgegeben
while ($zeile = mysqli_fetch_array($db_erg))
{
	if ($zeile['anzahl'] == 0) {
		$sql = "INSERT INTO user (`Vorname`, `Nachname`, `email`, `pword`, `securitykey`, `aktiv`, `admin`) 
				VALUES ('Master', 'User', 'root@root.at', '".root_Default_password_hash."', 'OQB6ZZGYHCPSX4AK', '1', '1')";
		mysqli_query($db_link, $sql) or die("Schreiben der Tabelle fehlgeschlagen: " . mysql_error());
	}
}
$sql = "SELECT * FROM blacklist WHERE usetime < NOW() - INTERVAL 10 MINUTE";
//Sql Befehl wird ausgeführt und das Ergebniss wird in db_erg gespeichert
$db_erg = mysqli_query( $db_link, $sql );
//Prüft ob Die SQL-Abfrage überhaupt ein Ergebnis liefert wenn nicht wird wieder ein Fehler ausgegeben
while ($zeile = mysqli_fetch_array($db_erg))
{
	$sql = "DELETE FROM blacklist
			WHERE id='".$zeile['id']."'";
	mysqli_query($db_link, $sql) or die("Fehler beim löschen der Codes: " . mysql_error());
}
//mysqli_query($db_link, $sql) or die("Schreiben des Datensatzes Fehlgeschlagen" . mysql_error());
?>