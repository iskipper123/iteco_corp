<form name="" action="" method="post">
	 <div id="buttons_request">
	
</div>
    <? if(isset($error_delete)&&$error_delete!='') {?> 
        <div class="col-md-4 mt-2 mb-2"> 
            <div class="alert alert-danger" role="alert">
            <?=$error_delete ?>
            </div>
        </div>
    <?}?>
	<br />
	
	<div class="tableWrapper payment_carriers">
	<table class="table table-striped mt-2 interactiveTable"> 
		<tr>
			<th></th>
			<th><?=$_SESSION["contractorType"]?></th>
			<th>Номер заявки</th> 
			<th>Количество дней на расчет</th>
			<th>Дата получения документов</th>
			<th>Дата расчета</th>
			<th>Сделана ли уже оплата</th>
			<th>Статус</th>
			<th>Способ оплаты</th>
			<th></th>
		</tr>
		<? while (($row = $result_set->fetch_assoc()) != false) { ?>
			<?
				$result_set1 = $db->getRowWhere("contractors", "id", $row[customer]);
				$row1 = $result_set1->fetch_assoc();

				$result_set2 = $db->getRowWhere("requests", "number", $row[number]);
				$row2 = $result_set2->fetch_assoc();
				
				$row["date"] == 0 ? $date = "" : $date = date("d.m.Y", $row["date"]);
				$row["dateEnd"] == 0 ? $dateEnd = "" : $dateEnd = date("d.m.Y", $row["dateEnd"]);
				
                $row["paymentWasDidAlreary"] == 0 ? $paymentWasDidAlreary = "-" : $paymentWasDidAlreary = "+";
                $rowColor = "";
                $restOfTheDays = restOfTheDays($row["date"]);
				if( $paymentWasDidAlreary == "+") {$rowColor = "rowGreen";$row2[pay_variant] = 'Оплачено';}
				else if($row[status] == 'Проблема с перевозчиком') $rowColor = "rowRed";

				if ($row2[pay_variant] == 'Авансовый платёж') {
					$color_pay = '#f6c23e';
				} elseif ($row2[pay_variant] == 'Оплата на загрузке') {
					$color_pay = '#f084fc';
				} elseif ($row2[pay_variant] == 'Оплата на выгрузке') {
					$color_pay = '#797eff';
				} elseif ($row2[pay_variant] == 'Стандартный') {
					$color_pay = '#858796';
				} else {
					$color_pay = '';
				}

			?>
			<tr class="<?=$rowColor?>" style="background:<?php echo $color_pay;?>;">
				<td><input type="radio" name="idItem" value="<?=$row[id]?>"></td>
				<td><?=$row1[name]?></td>
				<td><?=$row[number]?></td>
				<td><?=$row[days]?></td>
				<td><?=$date?></td>
				<td><?=$dateEnd?></td>
				<td><?=$paymentWasDidAlreary?></td>
				<td><?=$row[status]?></td>
				<td><?=$row2[pay_variant]?></td>
				<td>
		            <ul class="right_menu">
		              <li class="add_menu">
		              <img src="/images/items.png" alt="">
		                <ul>
		                  <li><input class="btn btn-secondary btn-sm" type="submit" name="editMarkedItem" value="Редактировать"></li>
		                  <li><input class="btn btn-secondary btn-sm" type="submit" name="addMarkAboutPayment" value="Оплачено"></li>
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
    </div>
</form>
<style>
	
/* раскрашивание строк таблицы */
.rowGreen {
	background: rgb(133, 248, 133)!important;
}
.rowRed {
}
.rowYellow {
	background: rgb(255, 255, 125)!important;
}

ul.right_menu li.add_menu ul{    top: -34px;}
</style>

<script>
	  $('.right_menu li.add_menu').click(function () {
            $('.right_menu li.add_menu').not(this).children('ul').removeClass('active');
            $(this).children('ul').toggleClass('active');
        });
</script>