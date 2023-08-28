<?php
	session_start();
	require_once "../lib/checkWasUserLoginedAndIsAdmin.php";
    require_once "../lib/db.php";
    
    $title = 'Редактирование компании в черном списке';
	
	$_SESSION["userType"] = 1;
	require_once "../lib/editBlacklist.php";
?>