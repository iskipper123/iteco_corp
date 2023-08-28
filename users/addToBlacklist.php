<?php

	require_once "../lib/db.php"; 
	$title = 'Добавить в черный список';
	$_SESSION["userType"] = 2;
	require_once "../lib/addToBlacklist.php";
?>