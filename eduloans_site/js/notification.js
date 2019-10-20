function notification() {
	var xmlhttp;

	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	} else { // code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}

	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			document.getElementById("listNotifications").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","include/notification.php",true);
	xmlhttp.send();
}

function viewNotification(loanid, notifID) {
	document.location='loan.php?loanId=' + loanid;
	//submit php
	$.get("include/seenNotification.php?notification=" + notifID);
}

function toggleVis() {
	if(document.getElementById("listNotifications").style.visibility=="visible") {
		document.getElementById("listNotifications").style.visibility="hidden";
	} else {
		document.getElementById("listNotifications").style.visibility="visible";
		document.getElementById("notificationICON").src = "images/notifU.gif";
		document.getElementById("messageCount").innerHTML = "";
	}
}

function notificationHoverIn(item) {
	item.style.background = '#8D5C99';
}

function notificationHoverOut(item) {
	item.style.background = '#EB99FF';
}

window.onload = notification;
