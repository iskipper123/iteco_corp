<?php
	session_start();
	require_once "../lib/checkWasUserLoginedAndIsAdmin.php";
    require_once "../lib/db.php";
    
    $title = 'Смена статуса заявки';
	
	$_SESSION["userType"] = 1;
	require_once "../lib/getRequestToRequest.php"; 
?>