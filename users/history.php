<?php

	require_once "../lib/db.php";
    
    $db = DB::getObject();
	
	$title = 'История действий над контрагентом '.$row1[name];
	
	$_SESSION["userType"] = 2;
	require_once "../lib/history.php";
?>