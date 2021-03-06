<?php
	session_start();
	
	if (!isset($_SESSION['logged_user'])) {
		header('Location: budzet-domowy');
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
		
			<div class="container container-main">
			
				<?php
					if (isset($_SESSION['account_created'])) {
						echo '<h3 class="text-success text-center pt-4">Konto założone pomyślnie! Życzymy miłego użytkowania!</h3>';
						unset($_SESSION['account_created']);
					}
				?>
			
				<div class="row p-4">
			
					<nav class="col-md-6 px-4 py-2">
				
						<div class="menu-main text-center">
					
							<h1 class="header-title">Witaj, <?= $_SESSION['first_name'] ?>!</h1>
							
							<div class="options">
								<a href="budzet-wprowadz-przychod"><i class="icon-money-1"></i> Dodaj przychód</a>
							</div>
							
							<div class="options">
								<a href="budzet-wprowadz-wydatek"><i class="icon-shopping-basket"></i> Dodaj wydatek</a>
							</div>
							
							<div class="options">
								<a href="budzet-przegladaj-bilans"><i class="icon-balance-scale"></i> Przeglądaj bilans</a>
							</div>
							
							<div class="options">
								<a href="#"><i class="icon-cog"></i> Ustawienia</a>
							</div>
							
							<div class="options">
								<a href="logout.php"><i class="icon-logout"></i> Wyloguj się</a>
							</div>
						
						</div>
					
					</nav>
					
					<div class="col-md-6 px-4 mt-5 text-justify">
					
						<p>Prowadzenie budżetu domowego pozwala lepiej kontrolować wydatki i skuteczniej zarządzać finansami. Dzięki temu można zaoszczędzić w skali roku naprawdę imponujące kwoty. Sprawdź, jakie to łatwe!</p>
						<p>Budowanie budżetu należy zacząć od określenia wszystkich swoich źródeł dochodu. Dodaj wszystkie kwoty jakie w danym miesiącu wpłyną na twoje konto.</p>
						<p>Podobnie podejdź do wydatków. Dodaj wszystkie kwoty jakie w danym miesiącu opuszczają Twoje konto. Pamiętaj o ciągłym uzupełnianiu tej listy, wraz z pojawiającymi się wydatkami.</p>
						<p>Najważniejszym elementem planowania domowego budżetu jest analiza swojej sytuacji finansowej. Analiza pomaga stwierdzić, które z naszych wydatków są zupełnie zbędne. Dzięki temu możemy zaoszczędzić pieniądze poprzez całkowite lub częściowe wyeliminowanie niepotrzebnych wydatków.</p>
					
					</div>
					
				</div>
			
			</div>
		
		</main>
		
		<footer>
			
			<h3 class="h5 p-2 mb-0">Copyright &copy; 2020 Dawid Kałużny (dawid.kaluzny.programista@gmail.com)</h3>
			
		</footer>
		
		<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

	</body>
	
</html>