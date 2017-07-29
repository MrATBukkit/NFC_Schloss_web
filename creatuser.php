<?php
require_once('./php/config.php');
require_once('./login/GoogleAuthenticator.php');
require_once('./login/funktions.php');
session_start();
$db_link = mysqli_connect(MYSQL_HOST, MYSQL_BENUTZER, MYSQL_KENNWORT, MYSQL_DATENBANK);
mysqli_set_charset($db_link, 'utf8');
$email = $_POST['email'];
$sql = "SELeCT count(email) AS anzahl FROM user WHERE email='".$email."'";
$db_erg = mysqli_query( $db_link, $sql );
$zeile = mysqli_fetch_assoc($db_erg);
if ($zeile['anzahl'] >= 1)
{
	echo "	<div class='alert alert-warning' id='passwordcheckalert'>
				<strong>Email!</strong> Ein Konto mit dieser Email existiert schon
			</div>";
	exit(0);
}
$ga = new PHPGangsta_GoogleAuthenticator();
$vorname = $_POST['vorname'];
$nachname = $_POST['nachname'];
$password = $_POST['password'];
$secret = $ga->createSecret();
$qrCodeUrl = $ga->getQRCodeGoogleUrl('NFC-Schloss', $secret);
$hash = password_hash($password, PASSWORD_DEFAULT);
$sql = "INSERT INTO user (Vorname, Nachname, email, securitykey, pword)
				VALUES ('".$vorname."', '".$nachname."', 
				'".$email."', '".$secret."', '".$hash."');";
mysqli_query($db_link, $sql) or die("Fehler beim erstellen des Benutzers");
?>
<img src='<?php echo $qrCodeUrl; ?>'>
<h4 class="toplittel">Secret: <?php echo $secret; ?></h4>
<p class="topsmall">Bitte Scannen Sie diesen QR-Code mit dem 
einer Authenticator-App. Diesen Code brauchen Sie um sich sp&auml;ter anmelden zu k&ouml;nnen.<br>
Alternativ k&ouml;nnen Sie auch den Secret Manuel eingeben.<br><br>
<h4>Authenticator APPs:</h4>
<div class="row">
	<div class="col-sm-12 col-lg-12 col-xs-12 col-sm-12 toplittel bottomlittel">
		<a target="_blank" href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=de">
			<img class="logosmall" src="./images/Google_play.png">
		</a>
		<a target="_blank" href="https://itunes.apple.com/at/app/google-authenticator/id388497605?mt=8">
			<img class="logosmall" src="./images/iTunes.png">
		</a>
	</div>
</div>
<div class="row" style="margin-top: 5px; margin-bottom: 20px">
	<a target="_blank" href="https://www.microsoft.com/en-us/store/p/authenticator/9nblggh08h54">
		<img class="logobig" src="./images/Windows_phone.jpg">
	</a>
</div>
<div class="row">
	<div class="checkbox" >
		<label><input id="barcodescan" type="checkbox" value="">Barcode gescannt</label>
	</div>
	<button href="index.php" class="btn btn-default disabled" id="backtostart" title="Hooray!">Zur Startseite</button>
</div>
<div class='alert alert-warning' id="barcodescanalert">
	<strong>Barcode!</strong> Bitte Scannen sie den Barcode und dann best&auml;tigen Sie das Sie den Barcode gescannt haben 
</div>
<script>
$(document).ready(function () {
	$('#barcodescanalert').hide();
	var tostart = false;
	$('#barcodescan').click(function (){
		if ($('#barcodescan').prop('checked'))
		{
			$('#backtostart').removeClass('disabled');
			tostart = true;
		}
		else
		{
			$('#backtostart').addClass('disabled');
			tostart = false;
		}
	});
	$('#backtostart').click(function() {
		if (tostart == false)
		{
			$('barcodescanalert').show();
		}
		else
		{
			window.location = "..";
		}
	});
});
</script>
</p>