<?php

	require_once "../lib/db.php";
	$title = 'Документы';
	$_SESSION["userType"] = 2;
	require_once "../lib/docsByGroup.php";
	
	$db = DB::getObject();
?>