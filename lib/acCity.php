<?php
	require_once "../lib/db.php";
	
    $db = DB::getObject();

    $searchq=$_GET['q'];
	
	$result_set = $db->ajaxSearchCity($searchq);
    $result = [];
	while (($row = $result_set->fetch_assoc()) != false) {
        $countryRow = $db->ajaxGetCountry($row['country_id']);
		$result[]=$row['name'].', '.$countryRow['name'];
    }
    echo json_encode($result);  
?>