<?php
	require_once "../lib/db.php";
	require_once "../lib/vars.php";
	$title = 'Добавить документ';
	$_SESSION["userType"] = 2;
	require_once "../lib/addDoc.php";
?>