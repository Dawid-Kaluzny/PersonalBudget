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
		
		<script src="https://www.gstatic.com/charts/loader.js"></script>	
		
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
		
			<article class="container container-main balance text-left px-5 py-5">
			
				<div class="row p-2">
			
					<div class="col-lg-10 px-5 mx-auto">
										
						<h1 class="header-title"><i class="icon-balance-scale"></i> Przeglądaj bilans</h1>

						<!-- Button to Open the Modal -->
						<button type="button" class="btn btn-modal float-md-right" data-toggle="modal" data-target="#myModal">
							Wybierz okres
						</button>

						<!-- The Modal -->
						<div class="modal fade" id="myModal">
							<div class="modal-dialog">
								<div class="modal-content">
							  
									<!-- Modal Header -->
									<div class="modal-header">
										<h4 class="modal-title">Wybierz okres</h4>
								  
										<button type="button" class="close" data-dismiss="modal">&times;</button>
								  
									</div>
								
									<!-- Modal body -->
									<div class="modal-body">
										<div id="date-range"></div>	
										<div><a href="#" id="current-month">Bieżący miesiąc</a></div>
										<div><a href="#" id="previous-month">Poprzedni miesiąc</a></div>
										<div><a href="#" id="current-year">Bieżący rok</a></div>
										<div><a href="#" id="custom-date">Niestandardowy</a></div>
									</div>
								
									<!-- Modal footer -->
									<div class="modal-footer">
										<button type="button" class="btn btn-danger" data-dismiss="modal">Zamknij</button>
									</div>
								
								</div>
							</div>
						</div>						
						
						<div id="date-range-main">
						</div>	
						
					</div>
					
				</div>
				
				<div id="balance"></div>
				
				<div id="piechart"></div>
			
			</article>
		
		</main>
		
		<footer>
			
			<h3 class="h5 p-2 mb-0">Copyright &copy; 2020 Dawid Kałużny (dawid.kaluzny.programista@gmail.com)</h3>
			
		</footer>
		
		<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
		<script src="date.js"></script>

	</body>
	
</html>