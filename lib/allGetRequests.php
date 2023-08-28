<form name="" action="" method="post">
	<input class="btn btn-secondary btn-sm" type="submit" name="editMarkedItem" value="Редактировать">
	<input class="btn btn-secondary btn-sm" type="submit" name="addToRequest" value="Добавить в заявки">
	<input class="btn btn-danger btn-sm" type="submit" name="deleteMarkedItems" value="Удалить"> 

    <? if(isset($error_delete)&&$error_delete!='') {?>
        <div class="col-md-4 mt-2 mb-2"> 
            <div class="alert alert-danger" role="alert">
            <?=$error_delete ?>
            </div>
        </div>
    <?}?> 
	<br />
	<div class="tableWrapper">
	<table class="table table-striped mt-2 interactiveTable"> 
		<tr>
			<th></th>
			<th>Откуда</th>
			<th>Куда</th>
			<th>Вес</th>
			<th>Объем</th>
			<th>Тип</th>
			<th>Груз</th>
			<th>Стоимость</th>
			<th>Готовность груза</th>
			<? if($_SESSION["role"] == 1) {?> <th>Менеджер</th> <?}?>
			<th>Статус</th>
			<th>Партнер</th>
		</tr>
		<? while (($row = $result_set->fetch_assoc()) != false) { ?>
			<?
				$result_set1 = $db->getRowWhere("contractors", "id", $row[customer]);
				$row1 = $result_set1->fetch_assoc();

				$result_set3 = $db->getRowWhere("users", "id", $row[idUser]);
				$row3 = $result_set3->fetch_assoc();

				$result_set4 = $db->getRowWhere("users", "id", $row[IdUserTransport]);
				$row4 = $result_set4->fetch_assoc();
				
				if($row["dateCargoReady"] != 0) $dateCargoReady = date("d.m.Y", $row["dateCargoReady"]);
				else $dateCargoReady = "";
			?>
			<tr>
				<td><input type="radio" name="idItem" value="<?=$row[id] ?>"></td>
				<td><?=$row[point3]?></td>
				<td><?=$row[point4]?></td>
				<td><?=$row[weight]?> <?=$row[weight_var]?></td>
				<td><?=$row[capacity]?> <?=$row[capacity_var]?></td>
				<td><?=$row[transportType]?></td>
				<td><?=$row[info]?></td>
				<td><?=$row[price]?></td>
				<td><?=$dateCargoReady?></td>
				<? if($_SESSION["role"] == 1) {?> <td><?=$row3[name]?></td> <?}?>
				<td><?=$row[cargo_status]?></td>
				<td><?=$row4[name]?></td>
			</tr>
		<?}?>
    </table>
    </div>
</form>