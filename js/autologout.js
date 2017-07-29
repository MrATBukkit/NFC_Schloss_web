var timoutNow = 900000;
var timoutNow = 60;
// Warning has been shown, give the user 1 minute to interact
var logoutUrl = '../login/logout.php'; // URL to logout page.
//var warningtime = 2000;
var timeoutTimer;

// Reset timers.
function ResetTimeOutTimer() {
    clearTimeout(timeoutTimer);
    startoutologout();
}

function startoutologout() {
	timeoutTimer = setTimeout("IdleTimeout()", timoutNow);
}

// Show idle timeout warning dialog.
/*function warning() {
	console.log("warning");
    timeoutTimer = setTimeout("IdleTimeout()", timoutNow);
	$('#timeoutwarning').modal("show");
	//setTime(60, false);
    // Add code in the #timeout element to call ResetTimeOutTimer() if
    // the "Stay Logged In" button is clicked
}*/

// Logout the user.
function IdleTimeout() {
	$('#timeoutwarning').modal("hide");
    $.ajax({
		url: "./login/logout.php",
	});
	$('#loginmodal').modal({
		backdrop: 'static',
		keyboard: false, 
		show: true
	});
}

$(window).click(function() {
	ResetTimeOutTimer();
});