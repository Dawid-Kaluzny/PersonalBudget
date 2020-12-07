<?php
	session_start();
	
	if (!isset($_SESSION['logged_user'])) {
		header('Location: budzet-domowy');
		exit();
	} else {	
		if (isset($_POST['earliestDate'])) {
			require_once 'database.php';
			
			$earliestDate = $_POST['earliestDate'];
			$lastestDate = $_POST['lastestDate'];
			$_SESSION['earliestDate'] = $earliestDate;
			$_SESSION['lastestDate'] = $lastestDate;
						
			$incomesQuery = $db->prepare('SELECT ic.name, SUM(i.amount) AS ia FROM users AS u, incomes AS i, incomes_category_assigned_to_users AS ic
				WHERE i.user_id = :user_id AND i.date_of_income >= :earliestDate AND i.date_of_income <= :lastestDate AND u.id = ic.user_id AND u.id = i.user_id AND ic.id = i.income_category_assigned_to_user_id GROUP BY ic.name ORDER BY ia DESC');
			$incomesQuery->bindValue(':user_id', $_SESSION['id_logged_user'], PDO::PARAM_INT);
			$incomesQuery->bindValue(':earliestDate', $earliestDate, PDO::PARAM_STR);
			$incomesQuery->bindValue(':lastestDate', $lastestDate, PDO::PARAM_STR);
			$incomesQuery->execute();
		
			$incomes = $incomesQuery->fetchAll();
			
			$expensesQuery = $db->prepare('SELECT ec.name, SUM(e.amount) AS ea FROM users AS u, expenses AS e, expenses_category_assigned_to_users
				AS ec WHERE e.user_id = :user_id AND e.date_of_expense >= :earliestDate AND e.date_of_expense <= :lastestDate AND u.id = ec.user_id AND u.id = e.user_id AND ec.id = e.expense_category_assigned_to_user_id GROUP BY ec.name ORDER BY ea DESC');
			$expensesQuery->bindValue(':user_id', $_SESSION['id_logged_user'], PDO::PARAM_INT);
			$expensesQuery->bindValue(':earliestDate', $earliestDate, PDO::PARAM_STR);
			$expensesQuery->bindValue(':lastestDate', $lastestDate, PDO::PARAM_STR);
			$expensesQuery->execute();
		
			$expenses = $expensesQuery->fetchAll();
			
			$incomesSum = 0;
			$expensesSum = 0;
			
			echo	'<div class="row py-5">
								
						<div class="col-md-6 text-center table-balances">
							
							<h2>Przychody</h2>
							<table>
								<caption>Przychody</caption>';
			
								foreach ($incomes as $income) {
									$incomesSum += $income['ia'];
									echo '<tr><td>'.$income['name'].'</td><td>'.$income['ia'].'</td></tr>';
								}
								echo '<tr><th>Razem</th><th>'.$incomesSum.'</th></tr>';				
			echo			'</table>							
						</div>	
						
						<div class="col-md-6 text-center table-balances">
							
							<h2>Wydatki</h2>
							<table>
								<caption>Wydatki</caption>';
								
								foreach ($expenses as $expense) {
									$expensesSum += $expense['ea'];
									echo '<tr><td>'.$expense['name'].'</td><td>'.$expense['ea'].'</td></tr>';
								}
								echo '<tr><th>Razem</th><th>'.$expensesSum.'</th></tr>';									
			echo			'</table>
						
						</div>
							
					</div>
					
					<div class="row pl-5">
					
						<div class="col-md-12">
						
							<div class="balance-result">';
								$balanceResult = $incomesSum - $expensesSum;
								if ($balanceResult >= 0) echo '<p>Bilans: <span class="color-result">'.$balanceResult.'</span></p>Gratulacje. Świetnie zarządzasz finansami!';
								else echo '<p>Bilans: <span class="error">'.$balanceResult.'</span></p>Uważaj, wpadasz w długi!';
			echo			'</div>

						</div>
					
					</div>';
		} 
	}