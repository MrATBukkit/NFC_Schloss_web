<?php
require_once('./php/config.php');
session_start();
$db_link = mysqli_connect(MYSQL_HOST, MYSQL_BENUTZER, MYSQL_KENNWORT, MYSQL_DATENBANK);
mysqli_set_charset($db_link, 'utf8');
$sql = "SELECT * FROM userkey WHERE userid='".$_SESSION['uid']."';";
$db_erg = mysqli_query( $db_link, $sql );
while ($zeile = mysqli_fetch_array($db_erg))
{
	echo "<tr class='keyrow' id='".$zeile['id']."'>";
	echo "	<td><input class='selectabelkey form-check-input' id='".$zeile['id']."' type='checkbox'>";
	echo "	<td id='icon".$zeile['id']."' icon='".$zeile['device']."'><span class='glyphicon ".$zeile['device']."'></span></td>";
	echo "	<td id='bezeichnung".$zeile['id']."'>".$zeile['bezeichnung']."</td>";
	echo "	<td class='hidden-xs' id='serialnumber".$zeile['id']."'>".$zeile['keyid']."</td>";
	echo "</tr>";
}
?>
<script>
$('#selectallkey').click(function() {
	if($('#selectallkey').is(':checked'))
	{
		$('.selectabelkey').prop('checked', true);
	}
	else
	{
		$('.selectabelkey').prop('checked', false);
	}
});
var dontobenmodal = true;
$('.selectabelkey').click(function() {
	dontobenmodal = false;
	var isAllChecked = 0;
	$(".selectabelkey").each(function(){
		if(!this.checked)
		   isAllChecked = 1;
	});
	if(isAllChecked == 0)
	{
		$("#selectallkey").prop("checked", true);
	}
	else
	{
		$('#selectallkey').prop('checked', false);
	}
	setTimeout(function(){
		dontobenmodal = true;
	}, 50);
});
$('.keyrow').click(function () {
	if (dontobenmodal)
	{
		hidealert();
		var id = $(this).attr('id');
		$('#keyedit').modal('show');
		$('#deletedevice').show();
		$('#bezeichnung').val($('#bezeichnung'+id).text());
		$('#Seriennummer').val($('#serialnumber'+id).text());
		var icon = $('#icon'+id).attr('icon');
		$('#selecticon').iconpicker()
				.iconpicker('setFooter', false)
				.iconpicker('setHeader', false)
				.iconpicker('setIcon', icon)
				.iconpicker('setSearch', true)
		$('#keyid').val(id);
	}
});
$('#addkey').unbind().click(function() {
	$('#keyedit').modal('show');
	$('#deletedevice').hide();
	$('#bezeichnung').val('');
	$('#Seriennummer').val('');
	$('#keyid').val("");
	hidealert();
});
var lasticon = 'glyphicon-tag';
$('#selecticon').on('change', function(e) { 
    lasticon = e.icon;
});
var istoreload = false;
$('#saveBtn').unbind().click(function() {
	var serialnumber = $('#Seriennummer').val();
	var bezeichnung = $('#bezeichnung').val();
	var stop = false;
	if (serialnumber == "")
	{
		$('#seriennummeralert').show();
		stop = true;
	}
	if (bezeichnung == "")
	{
		$('#bezeichnungalert').show();
		stop = true;
	}
	if (stop)
	{
		return;
	}
	var id = $('#keyid').val();
	if (id == "")
	{
		$.ajax({
			url: "php/dbkey.php?methode=erstellen&icon="+lasticon+"&beschreibung="+bezeichnung+"&seriennummer="+serialnumber,
			success: function(data){
				if (data == "")
				{
					$('#keyedit').modal('hide');
					istoreload = true;
				}
				else
				{
					$('#keystatus').html(data);
				}
			}
		});
	}
	else
	{
		$.ajax({
			url: "php/dbkey.php?methode=update&icon="+lasticon+"&beschreibung="+bezeichnung+"&seriennummer="+serialnumber+"&id="+id,
			success: function(data){
				if (data == "")
				{
					$('#keyedit').modal('hide');
					istoreload = true;
				}
				else
				{
					$('#keystatus').html(data);
				}
			}
		});
	}
});
$('#keyedit').on('hidden.bs.modal', function () {
	if (istoreload)
	{
		reloadkey();
	}
})
/*
$('#addkey').click(function (){
	
});
$('#deletkey').click(function (){
	console.log("click");
	$('.selectabelkey').each(function () {
	   var id = (this.checked ? $(this).val() : "");
	   console.lgo(id);
	   console.log("each");
	});
});*/
</script>