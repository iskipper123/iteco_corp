<?php
	session_start();
	require_once "../lib/checkWasUserLoginedAndIsAdmin.php";
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
				
		$result_set = $db->getAllClientsByTag($tag);
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
	if(isset($_POST["deleteMarkedItems"])) {
		$idItem = $_POST["idItem"];
		if(!isset($idItem))
			$error_delete = "Не был выбран ни один клиент";
		else
			$addConfirmButton = true;
	}
	
	if(isset($_POST["comfirmDelete"])) {
		$idItem = $_POST["idItem"];

		$db->softDelete("clients", $idItem);

		header("Location: clients.php?success");
		exit;
	}
	
    require_once '../partsOfPages/head.php';
    require_once "../partsOfPages/menuForAdmin.php"; ?>
    <? if(isset($_GET["success"])) {?>
        <div class="col-md-4">
            <div class="alert alert-success" role="alert">
                Данные успешно сохранены!
            </div> 
        </div>
    <?}?>
    <div class="col-md-12">
         <a class="btn btn-success btn-sm" href="clients.php"><img src="/images/arr1.png" alt=""></a>
        <a class="btn btn-success btn-sm" href="addClient.php"><i class="fas fa-plus"></i> Добавить клиента</a>
        <? if($addConfirmButton) {?>
            <form name="" action="" method="post">
            <div class="card col-md-5">
                <div class="card-body">
                    <h5 class="card-title">Вы действительно хотите удалить данного клиента?</h5>
                    <h6 class="card-subtitle mb-2 text-muted">
                    <?
                    $result_set4 = $db->getRowWhere("clients", "id", $idItem);
                    $row4 = $result_set4->fetch_assoc(); ?>
                    <input type="hidden" name="idItem" value="<?=$idItem?>">
                    <? echo $row4[name];
                ?> </h6>
                    <input class="btn btn-danger btn-sm" type="submit" name="comfirmDelete" value="Удалить">
                    <input class="btn btn-secondary btn-sm" type="submit" name="cancelDelete" value="Отменить">
                </div>
            </div>
            </form>
            <hr>
        <?}?>
        <? require_once "../lib/allClients.php"; ?>
    </div>
<?  require_once '../partsOfPages/footer.php';?>