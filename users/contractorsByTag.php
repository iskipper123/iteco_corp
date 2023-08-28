<?php

	require_once "../lib/db.php";
    require_once "../lib/vars.php";
    
    $title = 'Список заказчиков';
	
	$db = DB::getObject();
	$noButtons = true;
	
	if(isset($_GET["type"])) {
		$type = $_GET["type"];
		$tag = $_GET["tag"];
		
		$result_set = $db->getAllContractorsByTypeAndTag($type, $tag);
	}
	
	$_SESSION["contractorType"] = $arrayOfContractorsTypes[1];
	
    require_once '../partsOfPages/head.php';
    require_once "../partsOfPages/menuForUser.php"; ?>
    <div class="col-md-12">
        <?php require_once "../partsOfPages/menuForContractors.php"; ?>
        <h2>Таг - <?=$tag?></h2>
		<a class="btn btn-success btn-sm" href="carriers.php"> Вернуться назад</a>
        <? require_once "../lib/allContractors.php"; ?>
    </div>
<?  require_once '../partsOfPages/footer.php';?>