<?php
	$db = DB::getObject();
    $title = 'Документы';
	if(isset($_GET["group"])) {
		$group = $_GET["group"]; 
	}
	
	if(isset($_POST["editMarkedItem"])) {
		$idItem = $_POST["idItem"];

		if(!isset($idItem))
			$error_delete = "Не была выбрана ни одна заявка";
		else {
			$_SESSION["backDocs"] = $group;
			header("Location: editDoc.php?edit=$idItem");
			exit;
		}
	}
	if(isset($_POST["deleteMarkedItems"])) {
		$idItem = $_POST["idItem"];
		if(!isset($idItem))
			$error_delete = "Не была выбрана ни одна заявка";
		else
			$addConfirmButton = true;
	}
	if(isset($_POST["comfirmDelete"])) {
		$idItem = $_POST["idItem"];
		
		$result_set4 = $db->getRowWhere("docs", "id", $idItem);
		$row4 = $result_set4->fetch_assoc();
				
		if(strlen($row4[path]) != 0) unlink($row4[path]);
		$db->delete("docs", $idItem);

		header("Location: docs.php?success");
		exit;
	}
	
	if(isset($_POST["cancel"])) {
		header("Location: docs.php");
		exit;
	}

	$result_set = $db->getRowWhereOrder("docs", "category", $group, "date DESC");
    require_once '../partsOfPages/head.php';
    if($_SESSION["userType"] == 1) 		require_once "../partsOfPages/menuForAdmin.php"; 
    else if($_SESSION["userType"] == 2) require_once "../partsOfPages/menuForUser.php";  ?>
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
                        <?
                        $result_set4 = $db->getRowWhere("docs", "id", $idItem);
                        $row4 = $result_set4->fetch_assoc(); ?>
                        <h5 class="card-title">Вы действительно хотите удалить документ <?=$row4[name]?>?</h5>
                        <input class="btn btn-danger btn-sm" type="submit" name="comfirmDelete" value="Удалить">
                        <input class="btn btn-secondary btn-sm" type="submit" name="cancelDelete" value="Отменить">
                    </div>
                </div>
            </form>
            <hr>
        <?}?>
        <form name="" action="" method="post">
            <input class="btn btn-danger btn-sm" type="submit" name="cancel" value="Вернуться назад"> <br /> <br />
        </form>
        <a class="btn btn-success btn-sm" href="addDoc.php?group=<?=$group?>"><i class="fas fa-plus"></i> Добавить документ</a> <br /><br />
        <? require_once "../lib/allDocs.php"; ?>
    </div>
<?  require_once '../partsOfPages/footer.php';?>