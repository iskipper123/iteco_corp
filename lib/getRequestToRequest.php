<?php
	$db = DB::getObject();

	if(isset($_GET["edit"])) {
		$id = $_GET["edit"];
				
		$result_set = $db->getRowWhere("get_requests", "id", $id);
		$row = $result_set->fetch_assoc();
	}
	$partener = $row[IdUserTransport];

	 	$from = $_POST["from"];
        $to = $_POST["to"];
		$cargo = $_POST["cargo"];



	if(isset($_POST["add"])) {
		$carrier = $_POST["carrier"];
		$carrierPrice = $_POST["carrierPrice"];
		$customerPrice = $_POST["customerPrice"];
		$currency = $_POST["currency"];

		$error_carrier = "";
		$error_carrierPrice = "";
		$error_customerPrice = "";
		$error_currency = "";
		$error = false;
	
		if(strlen($carrier) == 0) {
			$error_carrier = "Не заполнено поле";
			$error = true;
		}	
		if(strlen($currency) == 0) {
			$error_currency = "Не заполнено поле";
			$error = true;
		}
		if(!is_numeric($carrierPrice)) {
			$error_carrierPrice = "Сумма должна быть числом";
			$error = true;
		}
		if(strlen($carrierPrice) == 0) {
			$error_carrierPrice = "Не заполнено поле";
			$error = true;
		}
		if(!is_numeric($customerPrice)) {
			$error_customerPrice = "Сумма должна быть числом";
			$error = true;
		}
		if(strlen($customerPrice) == 0) {
			$error_customerPrice = "Не заполнено поле";
			$error = true;
		}
		if($customerPrice < $carrierPrice) {
			$error_customerPrice = "Оплата заказчика меньше, чем оплата перевозчику";
			$error = true;			
		}

		//найти id перевозчика
		$result_set8 = $db->getRowWhereWhereOrder("contractors", "name", $carrier, "isDeleted", 0, "name");
		if($result_set8->num_rows == 0) {
			$error_carrier = "Перевозчик не найден";
			$error = true;
		}
		else $row8 = $result_set8->fetch_assoc();

		if(!$error) {
			$result_set = $db->getRowWhere("get_requests", "id", $id);
			$row = $result_set->fetch_assoc();

			$number = $db->getLastNumber();
			$number++;			
			
			//перебросить заявку из "Прием заявок" в "Заявки"


			$db->addRequest($row[customer],  $row8[id], $number, $row["date"], $row[info], "", "", $customerPrice, $carrierPrice, 
					$row[idUser], 0, "", $currency, $row[point3], $row[point4],
					strtotime($row[dateCargoReady]), "", "", "", "", "", "", $row[transportType], "", "", 
					"", "", "", "", "", "", "", "", $row[weight], $row[weight_var], $row[capacity], $row[capacity_var], "", "", $partener, "");


			
			//сменить статус "Прием заявок"
			$db->changeGetRequestStatus($row[id]);

			$id = $db->getLastID("requests");				
			$db->addLog($row[customer], time(), "Заявки. Контрагент добавлен в качестве заказчика. Заявка №$number", 2, $id, $_SESSION["id"]);
			$db->addLog($row8[id], time(), "Заявки. Контрагент добавлен в качестве перевозчика. Заявка №$number", 2, $id, $_SESSION["id"]);
			
			//все добавленные заявки дублировать в растаможку
			$idNewFile = $db->getLastID("customs_clearance");
			$idNewFile++;
			$db->addCustomsClearance($idNewFile, $row[customer], $row8[id], $number, time(), $carrierPrice, "");
			$db->addLog($row[customer], time(), "Растаможка. Контрагент добавлен в качестве заказчика. Заявка №$number", 1, $idNewFile, $_SESSION["id"]);
			$db->addLog($row8[id], time(), "Растаможка. Контрагент добавлен в качестве перевозчика. Заявка №$number", 1, $idNewFile, $_SESSION["id"]);
						
			header("Location: requests.php?success");
			exit;
		}
	}
	else if(isset($_POST["cancel"])) {
		header("Location: getRequests.php");
		exit;		
	}
	
	$result_set2 = $db->getRowWhereOrder("users", "isDeleted", 0, "name");
    require_once '../partsOfPages/head.php';
    if($_SESSION["userType"] == 1) 		require_once "../partsOfPages/menuForAdmin.php"; 
    else if($_SESSION["userType"] == 2) require_once "../partsOfPages/menuForUser.php"; ?>
    <div class="col-md-12 pt-3">
        <form name="" action="" method="post" enctype="multipart/form-data">
            <input class="btn btn-danger btn-sm" type="submit" name="cancel" value="Вернуться назад"> <br /> <br />
            <div class="row">
                <div class="form-group col-md-4 mr-4">
                    <label for="tag1" class="required">Выберите перевозчика</label>
                    <input class="form-control<?=isset($error_carrier)&&$error_carrier!=''?' is-invalid':''?>" type="text" name="carrier" id="tag1" autocomplete="off" value="<?=$_POST["carrier"]?>">
                    <div class="invalid-feedback" <?=isset($error_carrier)&&$error_carrier!=''?'style="display:block;"':''?>><?=$error_carrier ?></div>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="carrierPriceInput" class="required">Перевозчик сумма</label>
                    <input class="form-control<?=isset($error_carrierPrice)&&$error_carrierPrice!=''?' is-invalid':''?>" type="text" name="carrierPrice" id="carrierPriceInput" autocomplete="off" value="<?=$_POST["carrierPrice"]?>">
                    <div class="invalid-feedback" <?=isset($error_carrierPrice)&&$error_carrierPrice!=''?'style="display:block;"':''?>><?=$error_carrierPrice ?></div>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="customerPriceInput" class="required">Заказчик сумма</label>
                    <input class="form-control<?=isset($error_customerPrice)&&$error_customerPrice!=''?' is-invalid':''?>" type="text" name="customerPrice" id="customerPriceInput" autocomplete="off" value="<?=$_POST["customerPrice"]?>">
                    <div class="invalid-feedback" <?=isset($error_customerPrice)&&$error_customerPrice!=''?'style="display:block;"':''?>><?=$error_customerPrice ?></div>
                </div>
                		<div class="form-group col-md-4 mr-4">
			<label for="currencyInput" class="required">Валюта</label>
			<select class="form-control<?=isset($error_currency)&&$error_currency!=''?' is-invalid':''?>" name="currency" id="currencyInput">
				<option selected="selected"></option>
				<? for($i = 0; $i < count($arrayOfCurrency); $i++) {
					if($currency == $arrayOfCurrency[$i]) { ?>
						<option selected="selected"><?=$arrayOfCurrency[$i] ?></option>
					<?}
					else {?>
						<option><?=$arrayOfCurrency[$i] ?></option>
					<?}?>
				<?}?>
			</select>
			<div class="invalid-feedback" <?=isset($error_currency)&&$error_currency!=''?'style="display:block;"':''?>><?=$error_currency ?></div>
		</div>

            </div>
            <input class="btn btn-secondary btn-sm" type="submit" name="add" value="Добавить">
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
		function autocompleteTag2(){
			var ajax = new XMLHttpRequest();
			ajax.open("GET", "../lib/autocompleteBlackList.php", true);
			ajax.onload = function () {
				var list = JSON.parse(ajax.responseText);
				var inputElement = document.querySelector("#tag1");
				var awesompleteInstance = new Awesomplete(inputElement, {minChars: 1, list: list});

				// Adaugă clasa CSS specifică pentru stilul cu cifra 2
				awesompleteInstance.container.classList.add("awesomplete2");
			};
			ajax.send();
		}
		$(function(){
            autocompleteTag();
            autocompleteTag1();
            autocompleteTag2();
            var postDate = $('#dateInput').val();
            var datepicker = $('#dateInput').datepicker().data('datepicker');
            if(postDate!=''){
                postDate = postDate.split(".");
                datepicker.selectDate(new Date(postDate[2],postDate[1]- 1,postDate[0]));
            }
        });
	</script>
<?  require_once '../partsOfPages/footer.php';?>