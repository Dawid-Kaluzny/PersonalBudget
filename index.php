<?php
session_start();

if (isset($_SESSION['logged_user'])) {
	header('Location: budzet-menu-glowne');
	exit();
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
    
	</head>

	<body>

		<div class="container mt-5 text-steel">
		
			<div class="row">
		
				<header class="col-md-6 mb-4 px-4 order-2">
					
					<div class="logo-main-left border-main">
					
						<h2 class="h1 text-center mb-4"><i class="icon-money"></i><br />Budżet domowy</h2>
						
						<p class="text-center h5">Aplikacja wspierająca efektywne zarządzanie finansami w gospodarstwie domowym. Zapanuj nad wydatkami, oszczędzaj pieniądze, inwestuj mądrze i&nbsp;spełniaj swoje marzenia!</p>
						
					</div>
					
				</header>
					
				<div class="col-md-6 text-center px-4 order-1 order-md-12">
				
					<article>
					
						<div class="login-main border-main mb-4 p-4">
						
							<h1 class="h2 mb-3"><i class="icon-money"></i>Logowanie</h1>
							
							<form method="post" action="login.php">
					
								<label><input type="email" name="login" placeholder="adres e-mail" <?= isset($_SESSION['given_login']) ? 'value="'.$_SESSION['given_login'].'"' : '' ?> required></label>
								
								<label><input type="password" name="password" placeholder="hasło" required></label>
								
								<input type="submit" value="Zaloguj się">
								
								<?php
									if (isset($_SESSION['bad_attempt'])) {
										echo '<p class="error">Niepoprawny login lub hasło!</p>';
										unset($_SESSION['bad_attempt']);
										unset($_SESSION['given_login']);
									}
								?>
					
							</form>
				
						</div>
						
					</article>
				
					<article>
					
						<div class="register-main border-main p-4 mb-4">
						
							<h3 class="text-dark h5">Nie masz konta?</h3>
							
							<a href="zaloz-konto" class="mx-auto h5 mt-2">Zarejestruj się</a>
					
						</div>
					
					</article>
				
				</div>
			
			</div>
		
		</div>
		
		<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

	</body>
	
</html>