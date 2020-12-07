<?php
	session_start();
	
	if (!isset($_SESSION['logged_user'])) {
		header('Location: budzet-domowy');
		exit();
	} else {
		if (isset($_SESSION['earliestDate'])) {
			require_once 'database.php';
			
			$earliestDate = $_SESSION['earliestDate'];
			$lastestDate = $_SESSION['lastestDate'];
			
			$expensesQuery = $db->prepare('SELECT ec.name, SUM(e.amount) AS ea FROM users AS u, expenses AS e, expenses_category_assigned_to_users
				AS ec WHERE e.user_id = :user_id AND e.date_of_expense >= :earliestDate AND e.date_of_expense <= :lastestDate AND u.id = ec.user_id AND u.id = e.user_id AND ec.id = e.expense_category_assigned_to_user_id GROUP BY ec.name ORDER BY ea DESC');
			
			$expensesQuery->bindValue(':user_id', $_SESSION['id_logged_user'], PDO::PARAM_INT);
			$expensesQuery->bindValue(':earliestDate', $earliestDate, PDO::PARAM_STR);
			$expensesQuery->bindValue(':lastestDate', $lastestDate, PDO::PARAM_STR);
			$expensesQuery->execute();
		
			$expenses = $expensesQuery->fetchAll();
			
			$data = Array();
			$data[] = Array ("Name", "Value");

			foreach ($expenses as $expense) {
				$data[] = Array($expense['name'], (float)$expense['ea']);
			}
			echo json_encode($data);
		}
	}




       