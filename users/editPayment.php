<?php

	require_once "../lib/db.php";
	require_once "../lib/vars.php";
	
	$db = DB::getObject(); 
	$contractorsType = $_SESSION["contractorType"];
    $title = 'Редактирование оплаты. '.$contractorsType;
	if(isset($_GET["edit"])) {
		$id = $_GET["edit"];
				
		$result_set = $db->getRowWhere("payments", "id", $id);
		$row = $result_set->fetch_assoc();
		
		$result_set11 = $db->getRowWhere("contractors", "id", $row[customer]);
		$row11 = $result_set11->fetch_assoc();
		$customer = $row11[name];
		
		if(strlen($row["date"]) != 0) $date = date("d.m.Y", $row["date"]);
		// if($date == 0) $date = "";
		
		$dateReg = date("d.m.Y", $row["dateReg"]);
		
		$paymentWasDidAlreary = $row[paymentWasDidAlreary];
		$status = $row[status];
	}
	
	if(isset($_POST["edit"])) {
		$customer = $_POST["customer"];
		$number = $_POST["number"];
		$date = $_POST["date"];
		$days = $_POST["days"];
		$paymentWasDidAlreary = $_POST["paymentWasDidAlreary"];
		$status = $_POST["status"];

		$error_customer = "";
		$error_number = "";
		$error_days = "";
		$error = false;

		if(strlen($customer) == 0) {
			$error_customer = "Не заполнено поле";
			$error = true;
		}
		if(strlen($number) == 0) {
			$error_number = "Не заполнено поле";
			$error = true;
		}
		if(!is_numeric($days)) {
			$error_days = "Количество дней должно быть числом";
			$error = true;
		}
		if(strlen($days) == 0) {
			$error_days = "Не заполнено поле";
			$error = true;
		}

		//найти id заказчика
		$result_set7 = $db->getRowWhereWhereOrder("contractors", "name", $customer, "isDeleted", 0, "name");
		if($result_set7->num_rows == 0) {
			$error_customer = "Контрагент не найден";
			$error = true;
		}
		else $row7 = $result_set7->fetch_assoc();

		if(!$error) {
			if(strlen($date) == 0) $date = "";
			else $date = strtotime($date);
			
			$db->editPayment($id, $row7[id], $number, $date, $days, $paymentWasDidAlreary/*, $status*/);
			
			if(strlen($date) != 0) {
				// echo "date: ".$date."<br>";
				$endDate = $date + $days*86400;
				$db->editEndDate($id, $endDate);
			}
			else {
				$endDate = 0;
				$db->editEndDate($id, $endDate);				
			}
			
			if($contractorsType == $arrayOfContractorsTypes[0]) {
				header("Location: paymentsCustomers.php");
				exit;
			}
			else {
				header("Location: paymentsCarriers.php");
				exit;
			}
		}
	}
	else if(isset($_POST["cancel"])) {
		if($contractorsType == $arrayOfContractorsTypes[0]) {
			header("Location: paymentsCustomers.php?success");
			exit;
		}
		else {
			header("Location: paymentsCarriers.php?success");
			exit;
		}
	}
    require_once '../partsOfPages/head.php';
    require_once "../partsOfPages/menuForUser.php"; ?>
    <div class="col-md-12 pt-3">
        <form name="" action="" method="post" enctype="multipart/form-data">
            <input class="btn btn-danger btn-sm" type="submit" name="cancel" value="Вернуться назад"> <br /> <br />
            <div class="row">
                <div class="form-group col-md-4 mr-4">
                    <label for="tag" class="required">Выберите контрагента</label>
                    <? if($_SESSION["contractorType"] == $arrayOfContractorsTypes[0]) {?>
                        <input class="form-control<?=isset($error_customer)&&$error_customer!=''?' is-invalid':''?>" type="text" name="customer" id="tag" autocomplete="off" value="<?=isset($_POST["customer"])? $_POST["customer"]:$customer?>">
                    <?} else {?>
                        <input class="form-control<?=isset($error_customer)&&$error_customer!=''?' is-invalid':''?>" type="text" name="customer" id="tag1" autocomplete="off" value="<?=isset($_POST["customer"])? $_POST["customer"]:$customer?>">
                    <?}?>
                    <div class="invalid-feedback" <?=isset($error_customer)&&$error_customer!=''?'style="display:block;"':''?>><?=$error_customer ?></div>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="numberInput" class="required">Номер заявки</label>
                    <input class="form-control<?=isset($error_number)&&$error_number!=''?' is-invalid':''?>" type="text" name="number" id="numberInput" autocomplete="off" value="<?=isset($_POST["number"])? $_POST["number"]:$row[number]?>">
                    <div class="invalid-feedback" <?=isset($error_number)&&$error_number!=''?'style="display:block;"':''?>><?=$error_number ?></div>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="dateInput" class="required">Дата получения документов</label>
                    <input class="form-control<?=isset($error_date)&&$error_date!=''?' is-invalid':''?> datepicker-here" data-auto-close="true" type="text" name="date" id="dateInput" placeholder="дд.мм.гггг" autocomplete="off" value="<?=isset($_POST["date"])? $_POST["date"]:$date?>">
                    <div class="invalid-feedback" <?=isset($error_date)&&$error_date!=''?'style="display:block;"':''?>><?=$error_date ?></div>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="daysInput" class="required">Количество дней на расчет</label>
                    <input class="form-control<?=isset($error_days)&&$error_days!=''?' is-invalid':''?>" type="text" name="days" id="daysInput" autocomplete="off" value="<?=isset($_POST["days"])? $_POST["days"]:$row[days]?>">
                    <div class="invalid-feedback" <?=isset($error_days)&&$error_days!=''?'style="display:block;"':''?>><?=$error_days ?></div>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <? if($paymentWasDidAlreary == 1) {?>
                        <input type="checkbox" checked name="paymentWasDidAlreary" value="1"> <b>Оплата уже совершена</b> <br />
                    <?} else {?>
                        <input type="checkbox" name="paymentWasDidAlreary" value="1"> <b>Оплата уже совершена</b> <br />
                    <?}?>
                </div>
<!--                 <div class="form-group col-md-4 mr-4">
					<label for="status_payment" class="required">Статус</label>
					<select class="form-control" name="status" id="status">
		                        <? if($row[status] == "") { ?>
		                            <option selected="selected"></option>
		                        <?}
		                        else { ?>
		                            <option selected="selected"> <?=$row[status] ?> </option>
		                        <?}?>
		                        <? for($i = 0; $i < count($status_payment_variant); $i++) {
		                            if($row[status] != $status_payment_variant[$i]) { ?>
		                                <option><?=$status_payment_variant[$i] ?></option>
		                            <?}?>
		                        <?}?>
		                    </select>
				</div> -->
            </div>
            <input class="btn btn-secondary btn-sm" type="submit" name="edit" value="Изменить">
            <input class="btn btn-danger btn-sm" type="submit" name="cancel" value="Отменить">
        </form>
    </div>
    <script defer>
        function autocompleteTag(){
            var ajax = new XMLHttpRequest();
            ajax.open("GET", "../lib/autocomplete.php", true);
            ajax.onload = function () {
                var list = JSON.parse(ajax.responseText);
                new Awesomplete(document.querySelector("#tag"), {minChars: 1, list: list});
            };
            ajax.send();
        }
        function autocompleteTag1(){
            var ajax = new XMLHttpRequest();
            ajax.open("GET", "../lib/autocomplete1.php", true);
            ajax.onload = function () {
                var list = JSON.parse(ajax.responseText);
                new Awesomplete(document.querySelector("#tag1"), {minChars: 1, list: list});
            };
            ajax.send();
        }
		$(function(){
            autocompleteTag();
            autocompleteTag1();
            var postDate = $('#dateInput').val();
            var datepicker = $('#dateInput').datepicker().data('datepicker');
            if(postDate!=''){
                postDate = postDate.split(".");
                datepicker.selectDate(new Date(postDate[2],postDate[1]- 1,postDate[0]));
            }
        });
	</script>
<?  require_once '../partsOfPages/footer.php';?>	