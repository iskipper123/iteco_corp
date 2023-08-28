<?php
	session_start();
	unset($_SESSION["login"]);
	unset($_SESSION["isLogin"]);
	unset($_SESSION["password"]);
	unset($_SESSION["role"]);
	unset($_SESSION["id"]);
	unset($_COOKIE['formLogin']);
	setcookie('formLogin', null, -1, '/');
	unset($_COOKIE['formPassword']);
	setcookie('formPassword', null, -1, '/');
	header("Location: ../index.php");
	exit;
?>