<?php
	$searchq=$_GET['q'];
	// print_r($_GET);
	
	require_once "../lib/db.php";
	require_once "../lib/vars.php";
	
	$db = DB::getObject();
	
	$result_set = $db->AJAXSearchBlacklist($searchq, $arrayOfContractorsTypes[1]);
    $result = [];
	while (($row = $result_set->fetch_assoc()) != false) {
		$result[]=$row['name'];
    }
    echo json_encode($result);  
?>
