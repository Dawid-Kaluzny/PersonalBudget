function setCurrentDate()
{
	var today = new Date();
	
	var day = today.getDate();
	if (day < 10) day = "0" + day;
	
	var month = today.getMonth()+1;
	if (month < 10) month = "0" + month;
	
	var year = today.getFullYear();
	
	var time = year + '-' + month + '-' + day;
	
	document.getElementById("time").setAttribute("value", time);
}

window.addEventListener("load", function() { setCurrentDate(); });