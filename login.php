<?php
session_start();

if (!isset($_SESSION['logged_user'])) {
	if (isset($_POST['login'])) {
		
		$login = filter_input(INPUT_POST, 'login');
		$password = filter_input(INPUT_POST, 'password');
		
		require_once 'database.php';
		
		$loginQuery = $db->prepare('SELECT id, username, password FROM users WHERE email = :email');
		$loginQuery->bindValue(':email', $login, PDO::PARAM_STR);
		$loginQuery->execute();
		
		$user = $loginQuery->fetch();
		
		if ($user && password_verify($password, $user['password'])) {
			$_SESSION['logged_user'] = true;
			$_SESSION['id_logged_user'] = $user['id'];
			$_SESSION['first_name'] = $user['username'];
			header('Location: budzet-menu-glowne');
		} else {
			$_SESSION['given_login'] = $login;
			$_SESSION['bad_attempt'] = true;
			header('Location: index.php');
			exit();
		}
		
	} else {
		header('Location: budzet-domowy');
		exit();
	}
} else {
	header('Location: budzet-domowy');
	exit();
}