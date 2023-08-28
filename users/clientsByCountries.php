<?php

	require_once "../lib/db.php";
    require_once "../lib/functions.php";
    
    $title = 'Список клиентов по странам';
	
	$db = DB::getObject(); 
		
	$result_set = $db->getAllCountriesInClientsTableByManager($_SESSION["id"]);
	
	if(isset($_POST["cancel"])) {
		header("Location: clients.php");
		exit;	
	}

    require_once '../partsOfPages/head.php';
    require_once "../partsOfPages/menuForUser.php"; ?>
    <div class="col-md-12">
        <form name="" action="" method="post">
            <input class="btn btn-danger btn-sm" type="submit" name="cancel" value="Вернуться назад">
        </form> <br />
        <table class="table table-striped mt-2 interactiveTable">
            <tr>
                <!-- <th></th> -->
                <th>Название компании</th>
                <th>Контактное лицо</th>
                <th>Дата следующего контакта</th>
                <th>Сколько дней осталось до звонка</th>
                <th>Комментарий по грузу</th>
                <th>Страна</th>
                <th>Телефон</th>
                <th>Менеджер</th>
            </tr>
            <? while (($row = $result_set->fetch_assoc()) != false) { ?>
                <? printEmptyRow($row[country]); ?>
                <? $result_set5 = $db->getRowWhereWhereWhereOrder("clients", "country", $row[country], "idManager", $_SESSION["id"], "isDeleted", 0, "date DESC"); ?>
                <? while (($row5 = $result_set5->fetch_assoc()) != false) {
                    $result_set1 = $db->getRowWhere("users", "id", $row5[idManager]);
                    $row1 = $result_set1->fetch_assoc();
                    ?>
                    <tr>
                        <!--<td><input type="radio" name="idItem" value="<?=$row[id]?>"></td> -->
                        <td><?=$row5[name]?></td>
                        <td><?=$row5[contactName]?></td>
                        <td><?=date("d.m.Y", $row5["date"])?></td>
                        <td><?=$restOfTheDays?></td>
                        <td><?=$row5[comments]?></td>
                        <td><?=$row5[country]?></td>
                        <td><?=$row5[phone]?></td>
                        <td><?=$row1[name]?></td>
                    </tr>
                <?}?>
            <?}?>
        </table>
    </div>
<?  require_once '../partsOfPages/footer.php';?>