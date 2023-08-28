<form name="" action="" method="post" id="form_req">

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
			<th>Заказчик</th>
			<th>Перевозчик</th>
			<th>Техпаспорт</th>
			<th>Паспорт Вод</th>
			<th>Уставные документы</th>
			<th>Дата</th>
			<th></th>
		</tr> 
		<? while (($row = $result_set->fetch_assoc()) != false) { ?>
			<?
				$result_set1 = $db->getRowWhere("contractors", "id", $row[customer]);
				$row1 = $result_set1->fetch_assoc();

				$result_set3 = $db->getRowWhere("users", "id", $row[idUser]);
				$row3 = $result_set3->fetch_assoc();
				if ($row[added] == '+') {
					$added = 'is_added';
				} else {
					$added = 'no_add';
				}
			?>
			<tr class="<?php echo $added ?>">
				<td><input type="radio" name="idItem" value="<?=$row[id] ?>"></td>
				<td><?=$row[customer]?></td>
				<td><?=$row[carrier]?></td>
				<td><a href="/<?=$row[carrier_teh_pasport]?>" target="_blank">Посмотреть</a></td>
				<td><a href="/<?=$row[carrier_prava]?>" target="_blank">Посмотреть</a></td>
				<td><a href="/<?=$row[carrier_docs]?>" target="_blank">Посмотреть</a></td>
				<td><?=$row[date]?></a></td>
				<td><?=$row[added]?></a></td>
				<td>						<ul class="right_menu" style="text-align: center;">
							<li class="add_menu">
							<img src="/images/items.png" alt="">
								<ul style="right: 40px; top: -50px;">
									<li><a class="check_item" href="editformRequest.php" target="_blank">Проверить данные</a></li>
									<li><a class="addrequest btn btn-secondary btn-sm" href="/get_urle9804678248fda5be215f404bc22222.php" target="_blank">Создать ссылку для перевозчика</a></li>
									<li><input class="btn btn-secondary btn-sm" type="submit" name="addToRequest" value="Добавить в заявки"></li>
										<? if($_SESSION["role"] == 1) {?>
									<li><input class="btn btn-danger btn-sm" type="submit" name="deleteMarkedItems" value="Удалить"></li>
									 <?}?>
								</ul>
							</li>
						</ul></td>
			</tr>
		<?}?>
    </table>
    </div>
</form>

<script>
    $("#form_req tr").click(function() {
    idItem = $(this).find('input[name="idItem"]').val();
    $(".addrequest").attr("href", "/get_urle9804678248fda5be215f404bc22222.php?idItem="+idItem);
    $(".check_item").attr("href", "editformRequest.php?edit="+idItem);
        });
        $('.right_menu li.add_menu').click(function () {
            $('.right_menu li.add_menu').not(this).children('ul').removeClass('active');
            $(this).children('ul').toggleClass('active');
        });
</script>

