<?php

	require_once "../lib/db.php";
    require_once "../lib/vars.php";
    
    $title = 'Мои заказчики';
	
	$db = DB::getObject();
	$result_set = $db->getRowWhereWhereWhereOrder("contractors", "idManager", $_SESSION["id"], "contractorsType", $arrayOfContractorsTypes[0], "isDeleted", 0, "name");
	
	if(isset($_POST["editMarkedItem"])) {
		$idItem = $_POST["idItem"];

		if(!isset($idItem))
			$error_delete = "Не был выбран ни один заказчик";
		else {
			$_SESSION["contractorType"] = $arrayOfContractorsTypes[0];
			header("Location: editCustomer.php?edit=$idItem");
			exit;
		}
	}
	else if(isset($_POST["deleteMarkedItems"])) {
		$idItem = $_POST["idItem"];
		if(!isset($idItem))
			$error_delete = "Не был выбран ни один заказчик";
		else
			$addConfirmButton = true;
	}
	
	if(isset($_POST["comfirmDelete"])) {
		$idItem = $_POST["idItem"];

		$db->softDelete("contractors", $idItem);

		header("Location: contractors.php?success");
		exit;
	}
	
	if(isset($_POST["cancel"])) {
		header("Location: customers.php");
		exit;
	}
	
	if(isset($_POST["add"])) {
		$_SESSION["contractorType"] = $arrayOfContractorsTypes[0];
		header("Location: addCustomer.php");
		exit;		
	}
	else if(isset($_POST["showAllClients"])) {
		header("Location: customers.php");
		exit;		
	}
	
	$_SESSION["contractorType"] = $arrayOfContractorsTypes[0];
	$_SESSION["backHistory"] = "customers.php"; 
	
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
        <? if($addConfirmButton) {?>
            <form name="" action="" method="post">
                <div class="card col-md-5">
                    <div class="card-body">
                        <h5 class="card-title">Вы действительно хотите удалить заказчика?</h5>
                        <h6 class="card-subtitle mb-2 text-muted">
                        <?
                        $result_set4 = $db->getRowWhere("contractors", "id", $idItem);
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
        <?php require_once "../partsOfPages/menuForContractors.php"; ?>
        <form name="" action="" class="mb-3" method="post">
            <input class="btn btn-danger btn-sm" type="submit" name="cancel" value="Вернуться назад"> 
            <button class="btn btn-success btn-sm" type="submit" name="add"><i class="fas fa-plus"></i> Добавить заказчика</button>
            <button class="btn btn-success btn-sm" type="submit" name="showAllClients"></i> Отобразить всех заказчиков</button>
        </form>
        
        <? require_once "../lib/allContractors.php"; ?>
    </div>
<?  require_once '../partsOfPages/footer.php';?>