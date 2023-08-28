<form name="" action="" method="post">

    <? if(isset($error_delete)&&$error_delete!='') {?>
        <div class="col-md-4 mt-2 mb-2"> 
            <div class="alert alert-danger" role="alert">
            <?=$error_delete ?>
            </div>
        </div>
    <?}?>
	<table class="table table-striped mt-2 interactiveTable">
		<tr>
			<th></th>
			<th>Заказчик</th>
			<th>Перевозчик</th>
			<th>Заявка</th>
			<th>Дата</th>
			<!-- <th>Стоимость</th> -->
			<th>Файл</th>
			<th>Отписали</th>
		</tr>
		<? while (($row = $result_set->fetch_assoc()) != false) { ?>
			<?
				$result_set1 = $db->getRowWhere("contractors", "id", $row[customer]);
				$row1 = $result_set1->fetch_assoc();
				
				$result_set2 = $db->getRowWhere("contractors", "id", $row[carrier]);
				$row2 = $result_set2->fetch_assoc();
				
				if($row[deliveryNote] == 0) {
					$deliveryNote = "-";
					$rowColor = "";
				}
				else {
					$deliveryNote = "+";
					$rowColor = "rowGreen";
				}
				
				if($row["date"] == 0) 	$date = "";
				else 					$date = date("d.m.Y", $row["date"]);
			?>
			<tr class="<?=$rowColor?>">
				<td><input type="radio" name="idItem" value="<?=$row[id] ?>"></td>
				<td><?=$row1[name]?></td>
				<td><?=$row2[name]?></td>
				<td><?=$row[number]?></td>
				<td><?=$date?></td>
				<!-- <td><?=$row[price]?></td> -->
				<td>
					<? if(strlen($row[path]) != "") {?><a href="<?=$row[path]?>" target="_blank">Файл 1</a><?}?>
					<? if(strlen($row[path2]) != "") {?><a href="<?=$row[path2]?>" target="_blank">Файл 2</a><?}?>
				</td>
				<td><?=$deliveryNote?></td>
		<td>
            <ul class="right_menu">
              <li class="add_menu">
              <img src="/images/items.png" alt="">
                <ul>
                  <li><input class="btn btn-secondary btn-sm" type="submit" name="editMarkedItem" value="Редактировать"></li>
                  <li><input class="btn btn-secondary btn-sm" type="submit" name="writeDeliveryNote" value="Отписать накладную"></li>
                  <? if($_SESSION["role"] == 1) {?>
                  <li><input class="btn btn-danger btn-sm" type="submit" name="deleteMarkedItems" value="Удалить"></li>
                  <?}?> 
                </ul>
              </li>
            </ul>
        </td>
			</tr>
		<?}?>
	</table>
</form>
<style>
	
/* раскрашивание строк таблицы */
.rowGreen {
	background: rgb(133, 248, 133)!important;
}
.rowRed {
	background: rgb(255, 129, 129)!important;
}
.rowYellow {
	background: rgb(255, 255, 125)!important;
}

ul.right_menu li.add_menu ul{
	    right: 41px;
    top: -27px;
}

</style>

<script>
	  $('.right_menu li.add_menu').click(function () {
            $('.right_menu li.add_menu').not(this).children('ul').removeClass('active');
            $(this).children('ul').toggleClass('active');
        });
</script>