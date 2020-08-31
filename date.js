function setCurrentDate()
{
	var today = new Date();
	
	var day = today.getDate();
	if (day < 10) day = "0" + day;
	
	var month = today.getMonth() + 1;
	if (month < 10) month = "0" + month;
	
	var year = today.getFullYear();
	
	var time = year + '-' + month + '-' + day;
	
	document.getElementById("time").setAttribute("value", time);
}

function viewDateCurrentMonth()
{
	var today = new Date();
	
	var month = today.getMonth() + 1;
	var year = today.getFullYear();
	
	var lastDayInMonth = calculateLastDayInMonth(year, month);
	
	if (month < 10) month = "0" + month;
	var earliestDateCurrentMonth = year + '-' + month + '-' + '01';
	var lastestDateCurrentMonth = year + '-' + month + '-' + lastDayInMonth;
	var description = 'Bieżący miesiąc';
	
	setDescription(description, earliestDateCurrentMonth, lastestDateCurrentMonth);
}

function viewDatePreviousMonth()
{
	var today = new Date();
	
	var month = today.getMonth() + 1;
	if (month == 1) month = 12;
	else month--;
	var year = today.getFullYear();
	
	var lastDayInMonth = calculateLastDayInMonth(year, month);
	
	if (month < 10) month = "0" + month;
	var earliestDatePrevioustMonth = year + '-' + month + '-' + '01';
	var lastestDatePreviousMonth = year + '-' + month + '-' + lastDayInMonth;
	var description = 'Poprzedni miesiąc';
	
	setDescription(description, earliestDatePrevioustMonth, lastestDatePreviousMonth);
}

function viewDateCurrentYear()
{
	var today = new Date();
	
	var year = today.getFullYear();
	
	var earliestDateCurrentYear = year + '-01-01';
	var lastestDateCurrentYear = year + '-12-31';
	var description = 'Bieżący rok';
	
	setDescription(description, earliestDateCurrentYear, lastestDateCurrentYear);
}

function viewCustomDate()
{
	document.getElementById("date-range").innerHTML = '<div class="text-right"> <label>Data początkowa <input type="date" id="start-date"></label> <label class="ml-3">Data końcowa <input type="date" id="end-date"></label> <input type="submit" class="d-block ml-auto" id="confirm" value="Pokaż"> </div>';
	
	var today = new Date();
	var year = today.getFullYear();
	var earliestDate = year + '-01-01';
	var lastestDate = year + '-12-31';
	document.getElementById("start-date").setAttribute("value", earliestDate);
	document.getElementById("end-date").setAttribute("value", lastestDate);

	var rangeDate = document.getElementById('confirm');	
	var description = 'Niestandardowy zakres dat';
	
	rangeDate.addEventListener("click", function() { earliestDate = document.getElementById("start-date").value; });
	rangeDate.addEventListener("click", function() { lastestDate = document.getElementById("end-date").value; });
	rangeDate.addEventListener("click", function() { setDescription(description, earliestDate, lastestDate); });
}

function calculateLastDayInMonth(year, month) 
{
    var lastDayInMonth = 0;

    switch(month) {
    case 1:
    case 3:
    case 5:
    case 7:
    case 8:
    case 10:
    case 12:
        lastDayInMonth = 31;
        break;
    case 4:
    case 6:
    case 9:
    case 11:
        lastDayInMonth = 30;
        break;
    case 2: {
        if (((year % 4 == 0) && (year % 100 != 0)) || (year % 400 == 0))
            lastDayInMonth = 29;
        else
            lastDayInMonth = 28;
    }
    break;
    }
    return lastDayInMonth;
}

function setDescription(description, earliestDate, latestDate)
{
	document.getElementById("date-range").innerHTML = '<p><span class="range">' + description + '</span><p> <p><b>od ' + earliestDate + ' do ' + latestDate + '</b></p>';
	document.getElementById("date-range-main").innerHTML = '<p><span class="range">' + description + '</span><p> <p><b>od ' + earliestDate + ' do ' + latestDate + '</b></p>';
}

window.addEventListener("load", function() { viewDateCurrentMonth(); });

window.onload=function()
{	
	var currentMonth = document.getElementById('current-month');
	var previousMonth = document.getElementById('previous-month');	
	var currentYear = document.getElementById('current-year');	
	var customDate = document.getElementById('custom-date');	
	
	currentMonth.addEventListener("click", function() { viewDateCurrentMonth(); });
	previousMonth.addEventListener("click", function() { viewDatePreviousMonth(); });
	currentYear.addEventListener("click", function() { viewDateCurrentYear(); });
	customDate.addEventListener("click", function() { viewCustomDate(); });
}

// Load google charts
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);

// Draw the chart and set the chart values
function drawChart() {
	var data = google.visualization.arrayToDataTable([
		['Kategoria', 'Kwota'],
		['Mieszkanie', 1500.00],
		['Jedzenie', 432.56],
		['Transport', 200.00],
		['Telekomunikacja', 50.00],
		['Rozrywka', 122.67],
	]);

	// Optional; add a title and set the width and height of the chart
	var options = {'title':'Twoje Wydatki'};

	// Display the chart inside the <div> element with id="piechart"
	var chart = new google.visualization.PieChart(document.getElementById('piechart'));
	chart.draw(data, options);
}

$(window).resize(function(){
  drawChart();
});