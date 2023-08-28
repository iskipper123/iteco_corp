<?php	
	if(isset($_GET["id"])) {
		$id = $_GET["id"];
		
		$result_set = $db->getRowWhereOrder("logs", "idContractor", $id, "date DESC");
		
		$result_set1 = $db->getRowWhere("contractors", "id", $id);
		$row1 = $result_set1->fetch_assoc();
	}
	
	if(isset($_POST["cancel"])) {
		header("Location: $_SESSION[backHistory]");
		exit;
	}
		
    require_once '../partsOfPages/head.php';
    if($_SESSION["userType"] == 1) 		require_once "../partsOfPages/menuForAdmin.php"; 
    else if($_SESSION["userType"] == 2) require_once "../partsOfPages/menuForUser.php"; ?>
    <div class="col-md-12">
        <form name="" action="" method="post">
            <input class="btn btn-danger btn-sm" type="submit" name="cancel" value="Вернуться назад"> <br /> <br />
        </form>
        
        <table class="table table-striped mt-2 interactiveTable">
            <tr>
                <th>Дата</th>
                <th>Операция</th>
                <th>Пользователь</th>
                <th>Ссылка</th>
            </tr>
            <? while (($row = $result_set->fetch_assoc()) != false) { ?>
                <?
                    $result_set2 = $db->getRowWhere("users", "id", $row[idUser]);
                    $row2 = $result_set2->fetch_assoc();
                ?>
                <tr>
                    <td><?=date("d.m.Y H:i", $row["date"])?></td>
                    <td><?=$row[description]?></td>
                    <td><?=$row2[name]?></td>
                    <td>
                        <? if($row[linkType] == 1) {?>
                            <a href="editCustomsClearance.php?edit=<?=$row[idItem]?>" target='_blank'>Ссылка</a>
                        <?}
                        else if($row[linkType] == 2) {?>
                            <a href="editRequest.php?edit=<?=$row[idItem]?>" target='_blank'>Ссылка</a>
                        <?}
                        else if($row[linkType] == 3) {?>
                            <a href="editGetRequest.php?edit=<?=$row[idItem]?>" target='_blank'>Ссылка</a>
                        <?}
                        else if($row[linkType] == 4) {?>
                            <a href="editPayment.php?edit=<?=$row[idItem]?>" target='_blank'>Ссылка</a>
                        <?}?>
                    </td>
                </tr>
            <?}?>
        </table>
    </div>
<?  require_once '../partsOfPages/footer.php';?>