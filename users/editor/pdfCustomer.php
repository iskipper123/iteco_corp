<?php
	session_start();
	require_once $_SERVER['DOCUMENT_ROOT'] . "/lib/checkWasUserLoginedAndIsUser.php";
	require_once $_SERVER['DOCUMENT_ROOT'] . "/lib/db.php";

	$db = DB::getObject();

	if(isset($_GET["edit"])) {
		$id = $_GET["edit"]; 
				
		$result_set = $db->getRowWhere("requests", "id", $id);
		$row = $result_set->fetch_assoc();
		
		$result_set11 = $db->getRowWhere("contractors", "id", $row[customer]);
		$row11 = $result_set11->fetch_assoc(); 
		$customer = $row11[name];
		
		$result_set12 = $db->getRowWhere("contractors", "id", $row[carrier]);
		$row12 = $result_set12->fetch_assoc();
		$carrier = $row12[name];
		
		$date = date("d.m.Y", $row["date"]);
		
		$isCurrencyPayment = $row[isCurrencyPayment];
		$currency = $row[currency];
		
		// $idUser = $row[idUser];
		$transportType = $row[transportType];
        $dateShipping = date("d.m.Y", $row[dateShipping]);

        
        require_once $_SERVER['DOCUMENT_ROOT'] . '/partsOfPages/pdfTemplates/38z/template.php';

	}else{
        die(); 
    }
