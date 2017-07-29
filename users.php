<html>
<head>
<style>
.activeuser, .removeuser, .giveadmin, .removeadmin {
	width: 175px;
}
</style>
</head>
<body>
<div class="container" id="usercontent">
<?php
require_once('./php/config.php');
$db_link = mysqli_connect(MYSQL_HOST, MYSQL_BENUTZER, MYSQL_KENNWORT, MYSQL_DATENBANK);
mysqli_set_charset($db_link, 'utf8');
?>
<div class="row">
<div class="col-sm-2 col-md-push-10 col-sm-push-10">
<select id="filter" class="form-control">
	<option <?php if (!isset($_GET['methode'])) {print "selected";} ?> >Alle</option>
	<option <?php if (isset($_GET['methode'])) {if($_GET['methode'] == "Admins") {print "selected";}} ?> >Admins</option>
	<option <?php if (isset($_GET['methode'])) {if($_GET['methode'] == "Normalle User") {print "selected";}} ?> >Normalle User</option>
	<option <?php if (isset($_GET['methode'])) {if($_GET['methode'] == "Zu Aktivieren") {print "selected";}} ?> >Zu Aktivieren</option>
</select>
</div>
  <div class="col-sm-10 col-md-pull-2 col-sm-pull-2">
	<div class="table-responsive">
		<table class="table table-hover">
			<thead>
				<th>Vorname</th>
				<th>Nachname</th>
				<th class="hidden-xs">E-Mail</th>
				<th></th>
			</thead>
			<tbody>
				<?php
					$sql = "SELECT * FROM user;";
					if (isset($_GET['methode']))
					{
						if ($_GET['methode'] == "Admins")
						{
							$sql = "SELECT * FROM user WHERE admin=1;";
						}
						elseif ($_GET['methode'] == "Normalle User")
						{
							$sql = "SELECT * FROM user WHERE admin=0 and aktiv=1;";
						}
						elseif ($_GET['methode'] == "Zu Aktivieren")
						{
							$sql = "SELECT * FROM user WHERE aktiv=0;";
						}
					}
					$db_erg = mysqli_query( $db_link, $sql );
					session_start();
					while ($zeile = mysqli_fetch_array($db_erg))
					{
						echo "<tr id='keyrow".$zeile['id']."' class=''>";
						echo "	<td>".$zeile['Vorname']."</td>";
						echo "	<td>".$zeile['Nachname']."</td>";
						echo "	<td class='hidden-xs'>".$zeile['email']."</td>";
						if ($zeile['aktiv'] == 0)
						{
							echo "	<td>
										<button class='btn btn-success activeuser' id='ok".$zeile['id']."'>
											Aktivieren
										</button>
										<button class='btn btn-danger removeuser' id='remove".$zeile['id']."'>
											L&ouml;schen
										</button>
									</td>";
							echo "</tr>";
						}
						else
						{
							if ($zeile['id'] == $_SESSION['uid'])
							{
								echo "<td>Sie k&ouml;nnen sich selbst nicht bearbeiten</td>";
								continue;
							}
							if ($zeile['admin'] == 0)
							{
								echo "	<td>
											<button class='btn btn-warning giveadmin' id='giveadmin".$zeile['id']."'>
												Admin rechte geben
											</button>
											<button class='btn btn-danger removeuser' id='remove".$zeile['id']."'>
												L&ouml;schen
											</button>
										</td>";
								echo "</tr>";
							}
							else
							{
								echo "	<td>
											<button class='btn btn-warning removeadmin' id='deletadmin".$zeile['id']."'>
												Admin rechte entfernen
											</button>
										</td>";
								echo "</tr>";
							}
						}
					}
				?>
			</tbody>
		</table>
	</div>
  </div>
</div>
<script>
$(".activeuser").click(function(){
	var id = $(this).attr('id');
	var id = id.substr(2);
	$.ajax({
		type: "GET",
		url: "./edituser.php?uid="+ id +"&method=aktiv",
		beforeSend: function(){ $("#loginBtn").val('Connecting...');},
		success: function(data){
			$('#keyrow'+id).addClass('success');
			$('#ok'+id).hide();
			$('#remove'+id).hide();
		}
	});
});
$('.removeuser').click(function() {
	var id = $(this).attr('id');
	var id = id.substr(6);
	$.ajax({
		type: "GET",
		url: "./edituser.php?uid="+ id +"&method=delet",
		beforeSend: function(){ $("#loginBtn").val('Connecting...');},
		success: function(data){
			$('#keyrow'+id).addClass('danger');
			$('#ok'+id).hide();
			$('#remove'+id).hide();
			$('#giveadmin'+id).hide();
		}
	});
});
$('.removeadmin').click(function() {
	var id = $(this).attr('id');
	var id = id.substr(10);
	console.log(id);
	$.ajax({
		type: "GET",
		url: "./edituser.php?uid="+ id +"&method=removeadmin",
		beforeSend: function(){ $("#loginBtn").val('Connecting...');},
		success: function(data){
			$('#keyrow'+id).addClass('warning');
			$('#deletadmin'+id).hide();
		}
	});
});
$('.giveadmin').click(function() {
	var id = $(this).attr('id');
	var id = id.substr(9);
	console.log(id);
	$.ajax({
		type: "GET",
		url: "./edituser.php?uid="+ id +"&method=giveadmin",
		beforeSend: function(){ $("#loginBtn").val('Connecting...');},
		success: function(data){
			console.log(data);
			$('#keyrow'+id).addClass('warning');
			$('#giveadmin'+id).hide();
			$('#remove'+id).hide();
		}
	});
});

$('#filter').change(function()
{
	/* setting currently changed option value to option variable */
	var option = $(this).find('option:selected').val();
	/* setting input box value to selected option value */
	$.ajax({
		url: "users.php?methode="+option,
		success: function(data){
			$('#content').html(data);
		}
	});
});
</script>
</div>
</body>
</html>