<?php

	require_once "../lib/db.php";
	$title = 'Редактирование компании в черном списке';
	$_SESSION["userType"] = 2; 
	require_once "../lib/editBlacklist.php";
?>