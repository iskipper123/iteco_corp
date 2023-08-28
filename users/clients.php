<?php
	require_once "../lib/db.php";
    require_once "../lib/functions.php";
    
    $title = 'Клиенты';
	
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
    if(isset($_POST['curid2'])){ 
        $rowColor = ''; 
        $curid = $_POST['curid2'];
        $curval = $_POST['curval2'];
        $db->editMark($curid,$curval);
        die();
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
	
	if(isset($_POST["search"])) {
		$name = $_POST["name"];
		
		if(!isset($name))
			$error_name = "Не заполнено поле";
		else {
			$result_set = $db->searchClients($_SESSION["id"], $name);
		}
	}
	else $result_set = $db->getRowWhereWhereOrder("clients", "idManager", $_SESSION["id"], "isDeleted", 0, "date ASC");
?>
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
        
        <?}?>	

        <div class="col-md-12 topbar">
            <div class="col-md-4">
        <a class="btn btn-success btn-sm" href="addClient.php"><i class="fas fa-plus"></i> Добавить клиента</a>
      </div>
       <div class="col-md-8">
<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#all" class="active">Весь список</a></li>
  <li><a data-toggle="tab" href="#injob">В работе</a></li> 
  <li><a data-toggle="tab" href="#current_job">Профит</a></li>  
  <li><a data-toggle="tab" href="#arhive">Архив</a></li>
</ul>
</div>

    </div>
		<hr>
        <? require_once "../lib/allClients.php"; ?>
    </div>
<?  require_once '../partsOfPages/footer.php';?>