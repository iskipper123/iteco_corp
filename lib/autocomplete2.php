<?php
$searchq = '';
if(isset($_GET["q"])) {
	$searchq=$_GET['q'];
}
	require_once "../lib/db.php";

	require_once "../lib/vars.php";

	$db = DB::getObject();

	$result_set = $db->AJAXSearch2($searchq);

    $result = [];

	while (($row = $result_set->fetch_assoc()) != false) {
        $result[]=['label'=>$row['name'],'value'=>$row['name'],'extraData'=>['contactName'=>$row['contactName'],'phone'=>$row['phone'],'country'=>$row['country'],'company_form'=>$row['company_form']]]; 
    }

    echo json_encode($result); 
   // }

?>  