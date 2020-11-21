<?php
session_start();

if (isset($_POST['email'])) {
	//control flag
	$correct_data = true;
	
	$first_name = $_POST['first_name'];
	
	if (strlen($first_name) > 30) {
		$correct_data = false;
		$_SESSION['error_name'] = "Imię może posiadać maksymalnie 30 znaków!";
	}
	
	//sprawdzenie poprawności maila
	$email = $_POST['email'];
	$email_save = filter_var($email, FILTER_SANITIZE_EMAIL);
	
	if (!(filter_var($email_save, FILTER_VALIDATE_EMAIL)) || ($email_save != $email)) {
		$correct_data = false;
		$_SESSION['error_email'] = "Podaj poprawny adres e-mail!";
	}
	
	// Sprawdź poprawność hasła
	$password = $_POST['password'];
	$repeated_password = $_POST['repeated_password'];
	
	if ((strlen($password) < 8 ) || (strlen($password) > 30)) {
		$correct_data = false;
		$_SESSION['error_password'] = "Hasło musi posiadać od 8 do 30 znaków!";
	}
	
	if ($password != $repeated_password) {
		$correct_data = false;
		$_SESSION['error_password'] = "Podane hasła nie są identyczne!";
	}
	
	$password_hash = password_hash($password, PASSWORD_DEFAULT);
	
	//recaptcha
	$secret_key = ""; //wpisz tajny klucz recaptcha
	
	$check_correctness = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret_key.'&response='.$_POST['g-recaptcha-response']);
	
	$answer_received = json_decode($check_correctness);
	
	if (!($answer_received->success)){
		$correct_data = false;
		$_SESSION['error_bot'] = "Potwierdź, że nie jesteś botem!";
	}
	
	require_once 'database.php';
	
	$emailQuery = $db->prepare('SELECT id FROM users WHERE email = :email');
	$emailQuery->bindValue(':email', $email, PDO::PARAM_STR);
	$emailQuery->execute();
	
	if ($emailQuery->rowCount()) {
		$correct_data = false;
		$_SESSION['error_email'] = "Istnieje już konto przypisane do tego adresu email!";
	}
	
	if ($correct_data) {
		if (isset($_SESSION['given_name'])) unset($_SESSION['given_name']);
		if (isset($_SESSION['given_email'])) unset($_SESSION['given_email']);
		
		$queryUser = $db->prepare('INSERT INTO users VALUES (NULL, :first_name, :password, :email)');
		$queryUser->bindValue(':first_name', $first_name, PDO::PARAM_STR);
		$queryUser->bindValue(':password', $password_hash, PDO::PARAM_STR);
		$queryUser->bindValue(':email', $email, PDO::PARAM_STR);
		$queryUser->execute();
		
		$userIdQuery = $db->prepare('SELECT id, username FROM users WHERE email = :email');
		$userIdQuery->bindValue(':email', $email, PDO::PARAM_STR);
		$userIdQuery->execute();
		
		$usersId = $userIdQuery->fetch();
		$userId = $usersId['id'];
		
		$paymentQuery = $db->prepare('INSERT INTO payment_methods_assigned_to_users SELECT  NULL, :userId, name FROM payment_methods_default');
		$paymentQuery->bindValue(':userId', $userId, PDO::PARAM_INT);
		$paymentQuery->execute();
		
		$incomeQuery = $db->prepare('INSERT INTO incomes_category_assigned_to_users SELECT  NULL, :userId, name FROM incomes_category_default');
		$incomeQuery->bindValue(':userId', $userId, PDO::PARAM_INT);
		$incomeQuery->execute();
		
		$expenseQuery = $db->prepare('INSERT INTO expenses_category_assigned_to_users SELECT  NULL, :userId, name FROM expenses_category_default');
		$expenseQuery->bindValue(':userId', $userId, PDO::PARAM_INT);
		$expenseQuery->execute();
		
		$_SESSION['account_created'] = true;
		$_SESSION['logged_user'] = true;
		
		$_SESSION['id_logged_user'] = $userId;
		$_SESSION['first_name'] = $usersId['username'];
		
		header('Location: budzet-menu-glowne');
		exit();
	} else {
		$_SESSION['given_name'] = $first_name;
		$_SESSION['given_email'] = $email;
	}
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
		
		<script src="https://www.google.com/recaptcha/api.js" async defer></script>
    
	</head>

	<body>

		<div class="container mt-5">
		
			<div class="row p-2">
		
				<article class="mx-auto col-md-10 col-lg-7 col-xl-6 p-5 container-register border-main text-center text-steel">
				
					<form method="post">
					
						<h1 class="h1 mb-4 text-center"><i class="icon-money"></i> Rejestracja</h1>
						
						<label>Imię <input type="text" name="first_name" <?= isset($_SESSION['given_name']) ? 'value="'.$_SESSION['given_name'].'"' : '' ?> required></label>
						
						<?php
							if (isset($_SESSION['error_name'])) {
								echo '<div class="error">'.$_SESSION['error_name'].'</div>';
								unset($_SESSION['error_name']);
							}
							if (isset($_SESSION['given_name'])) unset($_SESSION['given_name']);
						?>
							
						<label>Adres e-mail <input type="email" name="email" <?= isset($_SESSION['given_email']) ? 'value="'.$_SESSION['given_email'].'"' : '' ?> required></label>
						
						<?php
							if (isset($_SESSION['error_email'])) {
								echo '<div class="error">'.$_SESSION['error_email'].'</div>';
								unset($_SESSION['error_email']);
							}
							if (isset($_SESSION['given_email'])) unset($_SESSION['given_email']);
						?>
							
						<label>Hasło <input type="password" name="password" required></label>
						
						<?php
							if (isset($_SESSION['error_password'])) {
								echo '<div class="error">'.$_SESSION['error_password'].'</div>';
								unset($_SESSION['error_password']);
							}
						?>
						
						<label>Powtórz hasło <input type="password" name="repeated_password" required></label>
						
						<div class="text-center">
							<div class="d-inline-block g-recaptcha mt-3" data-sitekey="6LdMaNQZAAAAACKeP4qGxXtF5yxCNcYDpkyn0dzZ"></div>
						</div>
						
						<?php
							if (isset($_SESSION['error_bot'])) {
								echo '<div class="error">'.$_SESSION['error_bot'].'</div>';
								unset($_SESSION['error_bot']);
							}
						?>
						
						<input type="submit" value="Zarejestruj">
					
					</form>
				
				</article>
			
			</div>
		
		</div>
		
		<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

	</body>
	
</html>