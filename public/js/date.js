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
	viewBalance(earliestDateCurrentMonth, lastestDateCurrentMonth);
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
	viewBalance(earliestDatePrevioustMonth, lastestDatePreviousMonth);
}

function viewDateCurrentYear()
{
	var today = new Date();
	
	var year = today.getFullYear();
	
	var earliestDateCurrentYear = year + '-01-01';
	var lastestDateCurrentYear = year + '-12-31';
	var description = 'Bieżący rok';
	
	setDescription(description, earliestDateCurrentYear, lastestDateCurrentYear);
	viewBalance(earliestDateCurrentYear, lastestDateCurrentYear);
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
	rangeDate.addEventListener("click", function() { viewBalance(earliestDate, lastestDate); });
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

function setDescription(description, earliestDate, lastestDate)
{
	document.getElementById("date-range").innerHTML = '<p><span class="range">' + description + '</span><p> <p><b>od ' + earliestDate + ' do ' + lastestDate + '</b></p>';
	document.getElementById("date-range-main").innerHTML = '<p><span class="range">' + description + '</span><p> <p><b>od ' + earliestDate + ' do ' + lastestDate + '</b></p>';		
}

function viewBalance(earliestDate, lastestDate)
{
	var expensesSum = 0.0;
	var incomesSum = 0.0;
	
	var incomeAjax = $.post("/budget/view-incomes-table", {earliestDate: earliestDate, lastestDate: lastestDate})
		.done(function(data) {
			var jsonData = JSON.parse(data);

			var incomesTable = '<caption>Przychody</caption>';
			
			$.each(jsonData, function(i, jsonData){
				incomesSum += parseFloat(jsonData.ia);
				incomesTable += '<tr><td>' + jsonData.name + '</td><td>' + jsonData.ia + '</td></tr>';
			});
			
			incomesTable += '<tr><th>Razem</th><th>' + incomesSum + '</th></tr>';
			document.getElementById("incomes").innerHTML = incomesTable;
		});
		
	var expenseAjax = $.post("/budget/view-expenses-table", {earliestDate: earliestDate, lastestDate: lastestDate})
		.done(function(data) {
			var jsonData = JSON.parse(data);

			var expensesTable = '<caption>Wydatki</caption>';
			
			$.each(jsonData, function(i, jsonData){
				expensesSum += parseFloat(jsonData.ea);
				expensesTable += '<tr><td>' + jsonData.name + '</td><td>' + jsonData.ea + '</td></tr>';
			});
			
			expensesTable += '<tr><th>Razem</th><th>' + expensesSum + '</th></tr>';
			document.getElementById("expenses").innerHTML = expensesTable;
			drawChart(jsonData);			
		});
	
	$.when(incomeAjax, expenseAjax).then(function() {
		setBalanceResult(incomesSum, expensesSum);			
	});
}

function setBalanceResult(incomesSum, expensesSum)
{
	var balanceResult = parseFloat(incomesSum) - parseFloat(expensesSum);
	var description = '';
	if (parseFloat(balanceResult) >= parseFloat(0)) {
		description = '<p>Bilans: <span class="color-result">' + balanceResult.toFixed(2) + '</span></p>Gratulacje. Świetnie zarządzasz finansami!';
	} else {
		description = '<p>Bilans: <span class="error">' + balanceResult.toFixed(2) + '</span></p>Uważaj, wpadasz w długi!';
	}
	document.getElementById("balance-result").innerHTML = description;	
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
function drawChart(jsonData) {
	var data = new google.visualization.DataTable();
	
	data.addColumn('string', 'name');
	data.addColumn('number', 'ea');
	
	$.each(jsonData, function(i, jsonData) {
		var name = jsonData.name;
		var amount = parseFloat(jsonData.ea);
		data.addRows([[name, amount]]);
	});
	
	// Optional; add a title and set the width and height of the chart
	var options = {
		'title':'Twoje Wydatki',
		is3D: true,
	};
	
	// Display the chart inside the <div> element with id="piechart"
	var chart = new google.visualization.PieChart(document.getElementById('piechart'));
	chart.draw(data, options);
}