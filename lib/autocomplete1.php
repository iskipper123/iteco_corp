<?php
	$searchq=$_GET['q'];
	
	require_once "../lib/db.php";
	require_once "../lib/vars.php";
	
	$db = DB::getObject();
	
	$result_set = $db->AJAXSearch($searchq, $arrayOfContractorsTypes[1]);
    $result = [];
	while (($row = $result_set->fetch_assoc()) != false) {
        $result[]=$row['name'];
    }
    echo json_encode($result); 
?>