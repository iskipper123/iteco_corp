<?php

    require_once "../lib/db.php";
    
    $title = 'Смена статуса заявки';
	
	$_SESSION["userType"] = 1;
	require_once "../lib/formRequestToRequest.php"; 
?>