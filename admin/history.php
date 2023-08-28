<?php
	session_start();
	require_once "../lib/checkWasUserLoginedAndIsAdmin.php";
	require_once "../lib/db.php";
    
    $db = DB::getObject();
	
	$title = 'История действий над контрагентом '.$row1[name];
	
	$_SESSION["userType"] = 1;
	require_once "../lib/history.php";
?>