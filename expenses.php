<?php
	session_start();
	
	if (!isset($_SESSION['logged_user'])) {
		header('Location: budzet-domowy');
		exit();
	} else {
		require_once 'database.php';
		
		if (isset($_POST['amount'])) {
			$amount = $_POST['amount'];
			$date_of_expense = $_POST['date_of_expense'];
			$payment_category = $_POST['payment_category'];
			$expense_category = $_POST['expense_category'];
			$comment = $_POST['comment'];
			
			$paymentsCategoryQuery = $db->prepare('SELECT id FROM payment_methods_assigned_to_users WHERE user_id = :user_id AND name = :payment_category');
			$paymentsCategoryQuery->bindValue(':user_id', $_SESSION['id_logged_user'], PDO::PARAM_INT);;
			$paymentsCategoryQuery->bindValue(':payment_category', $payment_category, PDO::PARAM_STR);;
			$paymentsCategoryQuery->execute();
		
			$paymentsCategory = $paymentsCategoryQuery->fetch();
			$paymentCategoryId = $paymentsCategory['id'];
			
			$expensesCategoryQuery = $db->prepare('SELECT id FROM expenses_category_assigned_to_users WHERE user_id = :user_id AND name = :expense_category');
			$expensesCategoryQuery->bindValue(':user_id', $_SESSION['id_logged_user'], PDO::PARAM_INT);;
			$expensesCategoryQuery->bindValue(':expense_category', $expense_category, PDO::PARAM_STR);;
			$expensesCategoryQuery->execute();
		
			$expensesCategory = $expensesCategoryQuery->fetch();
			$expenseCategoryId = $expensesCategory['id'];
			
			$addExpensesQuery = $db->prepare('INSERT INTO expenses VALUES (NULL, :user_id, :expense_category_id, :payment_method, :amount, :date_of_expense, :comment)');
			$addExpensesQuery->bindValue(':user_id', $_SESSION['id_logged_user'], PDO::PARAM_INT);
			$addExpensesQuery->bindValue(':expense_category_id', $expenseCategoryId, PDO::PARAM_INT);
			$addExpensesQuery->bindValue(':payment_method', $paymentCategoryId, PDO::PARAM_INT);
			$addExpensesQuery->bindValue(':amount', $amount, PDO::PARAM_STR);
			$addExpensesQuery->bindValue(':date_of_expense', $date_of_expense, PDO::PARAM_STR);
			$addExpensesQuery->bindValue(':comment', $comment, PDO::PARAM_STR);	
			$addExpensesQuery->execute();
			
			$_SESSION['expense_added'] = true;
		}
		
		$paymentsQuery = $db->prepare('SELECT name FROM payment_methods_assigned_to_users WHERE user_id = :user_id');
		$paymentsQuery->bindValue(':user_id', $_SESSION['id_logged_user'], PDO::PARAM_INT);
		$paymentsQuery->execute();
		
		$payments = $paymentsQuery->fetchAll();
		
		$expensesQuery = $db->prepare('SELECT name FROM expenses_category_assigned_to_users WHERE user_id = :user_id');
		$expensesQuery->bindValue(':user_id', $_SESSION['id_logged_user'], PDO::PARAM_INT);
		$expensesQuery->execute();
		
		$expenses = $expensesQuery->fetchAll();
	}
?>
<!DOCTYPE html>
<html lang="pl">

	<head>
	
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Budżet domowy</title>
		<meta name="description" content="">
		<meta name="keywords" content="budżet, domowy, finanse, wydatki, oszczędzanie, zarobki, bogactwo">
		<meta name="author" content="Dawid Kałużny">
		
		<meta http-equiv="X-Ua-Compatible" content="IE=edge">
		
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
		<link rel="stylesheet" href="main.css" />
		<link rel="stylesheet" href="css/fontello.css" />
		
		<link href="https://fonts.googleapis.com/css2?family=MuseoModerno:wght@400;600&display=swap" rel="stylesheet">

		<script src="time.js"></script>
    
	</head>

	<body>

		<header class="sticky">
		
			<nav class="navbar navbar-expand-xl pl-5">
			
				<a class="navbar-brand" href="budzet-menu-glowne"><i class="icon-money"></i> Budżet domowy</a>
				
				<button class="navbar-toggler navbar-dark" type="button" data-toggle="collapse" data-target="#main-menu" aria-controls="main-menu" aria-label="Przełącznik nawigacji">
					<span class="navbar-toggler-icon"></span>
				</button>
				
				<div class="collapse navbar-collapse" id="main-menu">
				
					<ol class="navbar-nav">
						<li class="nav-item">
							<a class="nav-link" href="budzet-wprowadz-przychod"><i class="icon-money-1"></i> Dodaj przychód</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="budzet-wprowadz-wydatek"><i class="icon-shopping-basket"></i> Dodaj wydatek</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="budzet-przegladaj-bilans"><i class="icon-balance-scale"></i> Przeglądaj bilans</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#"><i class="icon-cog"></i> Ustawienia</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="logout.php"><i class="icon-logout"></i> Wyloguj się</a>
						</li>
					</ol>
					
				</div>
				
			</nav>
			
		</header>
		
		<main>
		
			<div class="container">
			
				<article>
			
					<div class="finance row p-4 container-main">
					
						<div class="col-lg-6 px-3 mx-auto">
					
							<h1 class="header-title"><i class="icon-shopping-basket"></i> Dodaj wydatek</h1>
							
							<?php
								if (isset($_SESSION['expense_added'])) {
									echo '<p class="error">Wydatek został dodany!</p>';
									unset($_SESSION['expense_added']);
								}
							?>
							
							<form method="post">
							
								<label class="input-right mr-auto">Kwota <input type="number" name="amount" step="0.01" required></label>
								
								<label class="input-right mb-5 mr-auto">Data <input type="date" name="date_of_expense" id="time" required></label>
								
								<p>Sposób płatności:</p>
								<?php
									foreach ($payments as $payment) {
										echo '<div><label><input type="radio" name="payment_category" value="'.$payment['name'].'" required> '.$payment['name'].'</label></div>';
									}
								?>
								
								<p>Kategoria:</p>
								<?php
									foreach ($expenses as $expense) {
										echo '<div><label><input type="radio" name="expense_category" value="'.$expense['name'].'" required> '.$expense['name'].'</label></div>';
									}
								?>
								
								<div><p><label for="comment">Komentarz:</label></p></div>
								<textarea name="comment" id="comment" rows="6" cols="30" maxlength="150"></textarea>
								
								<input type="submit" value="Dodaj">
								<input type="reset" value="Anuluj">
							
							</form>
										
						</div>
					
					</div>
					
				</article>
				
			</div>
		
		</main>
		
		<footer>
			
			<h3 class="h5 p-2 mb-0">Copyright &copy; 2020 Dawid Kałużny (dawid.kaluzny.programista@gmail.com)</h3>
			
		</footer>
		
		<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
		<script src="date.js"></script>

	</body>
	
</html>