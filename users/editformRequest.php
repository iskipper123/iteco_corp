<?php

    require_once "../lib/db.php";
    require_once "../lib/vars.php";
    
    $title = 'Редактирование заявки';
	
	$_SESSION["userType"] = 1;
	require_once "../lib/editformRequest.php";
?>