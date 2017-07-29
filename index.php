<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="./css/bootstrap.min.css">
	<link rel="stylesheet" href="./css/custom.css">
	<script src="./js/jquery.min.js"></script>
	<script src="./js/bootstrap.min.js"></script>
	<title>NFC-Schloss</title>
	<script>
		function reloadkey()
		{
			$.ajax({
				url: "getuserkeys.html",
				success: function(data){
					$('#content').html(data)
				}
			});
		}
		$( document ).ready(function() {
			$('#usernav').click( function() {
				$('#keynav').removeClass('active');
				$('#usernav').addClass('active');
				$.ajax({
					url: "users.php",
					success: function(data){
						$('#content').html(data);
					}
				});
			});
			$('#keynav').click( function() {
				console.log('click');
				$('#usernav').removeClass('active');
				$('#keynav').addClass('active');
				$.ajax({
					url: "getuserkeys.html",
					success: function(data){
						$('#content').html(data)
					}
				});
			});
			$.ajax({
				url: "./php/datenbank.php",
			});
			$('#usernav').hide();
			function loadpage()
			{
				$.ajax({
					url: "./php/rechte.php",
					dataType: 'json',
					success: function(data){
						var obj = jQuery.parseJSON(JSON.stringify(data));
						if(obj.admin == true)
						{
							$('#usernav').show();
						}
					}
				});
				$.ajax({
					url: "getuserkeys.html",
					success: function(data){
						$('#content').html(data)
					}
				});
			}
			<?php
				session_start();
				if ($_SESSION['eingeloggt'] == false):
			?>
			$('#loginmodal').modal({
				backdrop: 'static',
				keyboard: false, 
				show: true
			});
			<?php else: ?>
			loadpage();
			<?php endif;?>
			$('#loginBtn').click(function() {
				$('#loginstatus').html("");
				var email=$("#email").val();
				if (email.indexOf("@") === -1)
				{
					$('#loginstatus').html("<div class='alert alert-warning'>\n<strong>Bitte eine g&uuml;ltige E-Mail eingeben!</strong></div>");
				}
				else
				{
					var password=$("#pwd").val();
					var dataString = 'email='+email+'&password='+password;
					if($.trim(email).length>0 && $.trim(password).length>0) {
						$.ajax({
							type: "POST",
							url: "./login/login.php",
							data: dataString,
							success: function(data){
								if (data == "success")
								{
									$('#loginmodal').modal('hide');
									$('#authcode').modal({
										backdrop: 'static',
										keyboard: false, 
										show: true
									});
								}
								else 
								{
									$('#loginstatus').html(data);
								}
							}
						});
					}
					else
					{
						$('#loginstatus').html("<div class='alert alert-warning'>\n<strong>Bitte etwas eingeben!</strong></div>");
					}
				}
				return true;
			});
			var lastautcode = 0;
			$('#authBtn').click(function() {
				var code=$("#authcodeinput").val();
				if (code != lastautcode){
					$('#authstatus').html("");
					var dataString = 'authcode='+code
					if($.trim(code).length>0) {
						$.ajax({
							type: "POST",
							url: "./login/authentcode.php",
							data: dataString,
							beforeSend: function(){ $("#authBtn").val('checking...');},
							success: function(data){
								if (data == "success")
								{
									$('#authcode').modal('hide');
									loadpage();
								}
								else
								{
									lastautcode = $("#authcodeinput").val();
									$('#authstatus').html(data);
								}
							}
						});
					}
					else
					{
						$('#auth').html("<div class='alert alert-warning'>\n<strong>Bitte etwas eingeben!</strong></div>");
					}
					return false;
				}
				return false;
			});
			$('#logoutbtn').click(function() {
				$.ajax({
					url: "./login/logout.php",
				});
				$('#loginmodal').modal({
					backdrop: 'static',
					keyboard: false, 
					show: true
				});
			});
		});
	</script>
</head>
<body>
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">NFC-Schloss</a>
    </div>
    <ul class="nav navbar-nav">
      <li id="keynav" class="active"><a href="#">Schl&uuml;sselverwaltung</a></li>
	  <li id="usernav" class=""><a href="#">Benutzer</a></li>
    </ul>
	<ul class="nav navbar-nav navbar-right">
		<li><a href="#" id="logoutbtn">Logout</a></li>
	</ul>
  </div>
</nav>
<!--Login Modal-->
<div id="loginmodal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!--Inhalt-->
	<div class="modal-content">
	  <div class="modal-header">
		<h4 class="modal-title">Login</h4>
	  </div>
	  <div class="modal-body">
		<div class="form-group">
			<label for="email">Email address:</label>
			<input type="email" class="form-control" id="email">
		</div>
		<div class="form-group">
			<label for="pwd">Passwort:</label>
			<input type="password" class="form-control" id="pwd">
		</div>
		<div class="row">
			<div id="loginstatus"></div>
		</div>
	  </div>
	  <div class="modal-footer">
		<a href="creatuser.html" class="pull-left btn btn-default" id="register">Registrieren</a>
		<button class="btn btn-default" id="loginBtn">Login</button>
	  </div>
	</div>
  </div>
</div>
<!--Modal Authentifikations Code-->
<div id="authcode" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!--Inhalt-->
	<div class="modal-content">
	  <div class="modal-header">
		<h4 class="modal-title">Authentication</h4>
	  </div>
	  <div class="modal-body">
		<div class="row">
			<div class="col-xs-2 col-xs-offset-5 col-sm-2 col-sm-offset-5">
				<img class="img-responsive middle" src="./images/auth.png">
			</div>
		</div>
		<div class="form-group">
			<label for="authcodeinput">Authentication Code:</label>
			<input type="number" class="form-control" id="authcodeinput">
		</div>
		<div class="row">
			<div id="authstatus">
			</div>
		</div>
	  </div>
	  <div class="modal-footer">
		<button class="btn btn-default" id="authBtn">Authentifizieren</button>
	  </div>
	</div>
  </div>
</div>

<div class="container" id="content"></div>

</body>
</html>