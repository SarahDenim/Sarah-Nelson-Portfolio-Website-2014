function ajax_lend_filter() {
	var xmlhttp;

	var updateStr = "?";

	updateStr = updateStr + "filter_minsum=" + document.getElementById("filter_minsum").value + "&";
	updateStr = updateStr + "filter_maxsum=" + document.getElementById("filter_maxsum").value + "&";
	updateStr = updateStr + "filter_minrate=" + document.getElementById("filter_minsum2").value + "&";
	updateStr = updateStr + "filter_maxrate=" + document.getElementById("filter_maxsum2").value + "&";
	updateStr = updateStr + "sortBy=" + document.getElementById("sortBy").value + "&";
	updateStr = updateStr + "sortDir=" + document.getElementById("sortDir").value;
	
	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	} else { // code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			document.getElementById("tableOfResults").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","include/lendLoanTable.php"+updateStr,true);
	xmlhttp.send();
}

function ajax_borrow_filter() {
	var xmlhttp;

	var updateStr = "?";

	updateStr = updateStr + "filter_minsum=" + document.getElementById("filter_minsum").value + "&";
	updateStr = updateStr + "filter_maxsum=" + document.getElementById("filter_maxsum").value + "&";
	updateStr = updateStr + "filter_minrate=" + document.getElementById("filter_minsum2").value + "&";
	updateStr = updateStr + "filter_maxrate=" + document.getElementById("filter_maxsum2").value + "&";
	updateStr = updateStr + "sortBy=" + document.getElementById("sortBy").value + "&";
	updateStr = updateStr + "sortDir=" + document.getElementById("sortDir").value;
	
	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	} else { // code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			document.getElementById("tableOfResults").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","include/borrowLoanTable.php"+updateStr,true);
	xmlhttp.send();
}