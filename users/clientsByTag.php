<?php

	require_once "../lib/db.php";
    require_once "../lib/functions.php";
    
    $title = 'Список клиентов по тегам'; 
	
    $db = DB::getObject();
	
    if(isset($_POST['curid'])){
        $rowColor = ''; 
        $curid = $_POST['curid'];
        $curval = strtotime($_POST['val']);
         $updated_date = date("d.m.Y");
        $db->editNextCall($curid,$curval,$updated_date);
        $restOfTheDays = restOfTheDays($curval);
        if($restOfTheDays > 0) $rowColor = "rowGreen";
        else if($restOfTheDays == 0) $rowColor = "rowRed";
        else if($restOfTheDays < 0) $rowColor = "rowYellow";
        echo json_encode(['color'=>$rowColor,'days'=>$restOfTheDays]);
        die();
    }
	
	if(isset($_GET["tag"])) {
		$tag = $_GET["tag"];
				
		$result_set = $db->getAllClientsByTagAndManager($tag, $_SESSION["id"]);
	}
	
	if(isset($_POST["editMarkedItem"])) {
		$idItem = $_POST["idItem"];

		if(!isset($idItem))
			$error_delete = "Не был выбран ни один клиент";
		else {
			header("Location: editClient.php?edit=$idItem");
			exit;
		}
	}
	
    require_once '../partsOfPages/head.php';
    require_once "../partsOfPages/menuForUser.php"; ?>
    <? if(isset($_GET["success"])) {?>
        <div class="col-md-4">
            <div class="alert alert-success" role="alert">
                Данные успешно сохранены!
            </div> 
        </div>
    <?}?>
    <div class="col-md-12">
  <a class="btn btn-success btn-sm" href="clients.php"><img src="/images/arr1.png" alt=""></a>
        <a class="btn btn-success btn-sm" href="addClient.php"><i class="fas fa-plus"></i> Добавить клиента</a><br /><br />
        <? require_once "../lib/allClients.php"; ?>
    </div>
<?  require_once '../partsOfPages/footer.php';?>