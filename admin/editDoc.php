<?php
	session_start();
	require_once "../lib/checkWasUserLoginedAndIsAdmin.php";
	require_once "../lib/db.php";
    require_once "../lib/vars.php";
    
    $title = 'Редактирование документа';

	$_SESSION["userType"] = 1;
	require_once "../lib/editDoc.php";
?>