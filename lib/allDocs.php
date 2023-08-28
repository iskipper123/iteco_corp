<form name="" action="" method="post">
	<input class="btn btn-secondary btn-sm" type="submit" name="editMarkedItem" value="Редактировать">
	<? if($_SESSION["role"] == 1) {?>
		<input class="btn btn-danger btn-sm" type="submit" name="deleteMarkedItems" value="Удалить"> 
	<?}?>
    <? if(isset($error_delete)&&$error_delete!='') {?>
        <div class="col-md-4">
            <div class="alert alert-danger" role="alert">
            <?=$error_delete ?>
            </div>
        </div>
    <?}?>
	<br />
	<table class="table table-striped mt-2 interactiveTable">
		<tr>
			<th></th> 
			<th>Название</th>
			<th>Категория</th>
			<th>Файл</th>
			<th>Дата загрузки</th>
		</tr>
		<? while (($row = $result_set->fetch_assoc()) != false) { ?>
			<tr>
				<td><input type="radio" name="idItem" value="<?=$row[id] ?>"></td>
				<td><?=$row[name]?></td>
				<td><?=$row[category]?></td>
				<td><a href="<?=$row[path]?>" target="_blank">Открыть</a></td>
				<td><?=date("d.m.Y", $row["date"])?></td>
			</tr>
		<?}?>
	</table>
</form>