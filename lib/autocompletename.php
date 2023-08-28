<?php
$searchq = '';
if(isset($_GET["q"])) {
	$searchq=$_GET['q'];
}
	require_once "../lib/db.php";

	require_once "../lib/vars.php";

	$db = DB::getObject(); 

	$result_set = $db->AJAXSearchName1($searchq);

    $result = [];

	while (($row = $result_set->fetch_assoc()) != false) {
        $result[]=$row['from'];
    }

    echo json_encode($result); 
   // }

?>  