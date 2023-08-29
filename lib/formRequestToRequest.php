<?php
	require_once "../lib/db.php";
	require_once "../lib/vars.php";
	$db = DB::getObject();

	if(isset($_GET["edit"])) {
		$id = $_GET["edit"];
				
		$result_set = $db->getRowWhere("form_requests_customer", "id", $id);
		$row = $result_set->fetch_assoc();
	}
	
	if(isset($_POST["add"])) {
		$carrier = $_POST["carrier"];
		$carrierPrice = $_POST["carrierPrice"];
		$customerPrice = $_POST["customerPrice"];
		$time = $_POST["time"];
		$term = $_POST["term"];
		$sumForCustomer = $_POST["sumForCustomer"];
		$sumForCarrier = $_POST["sumForCarrier"];
		$cargo = $_POST["cargo"];
		$isCurrencyPayment = $_POST["isCurrencyPayment"];
		$comision_static = $_POST["comision_static"];
		$currency = $_POST["currency"];
		$temperature = $_POST["temperature"];
		$from = $_POST["from"];
        $to = $_POST["to"];

        $error_from = "";
        $error_to = "";
		$error_cargo = "";
		$error_sumForCustomer = "";
		$error_sumForCarrier = "";
		$error_carrier = "";
		$error_carrierPrice = "";
		$error_customerPrice = "";
		$error_time = "";
		$error_term = "";
		$error = false;


		if(strlen($from) == 0) {
			$error_from = "Не заполнено поле";
			$error = true;
        }
        if(strlen($to) == 0) {
			$error_to = "Не заполнено поле";
			$error = true;
		}

		if(strlen($temperature) == 0) {
			$error_temperature = "Не заполнено поле";
			$error = true;
		}
		if(strlen($currency) == 0) {
			$error_currency = "Не заполнено поле";
			$error = true;
		}
		if(strlen($cargo) == 0) {
			$error_cargo = "Не заполнено поле";
			$error = true;
		}	
		if(strlen($sumForCustomer) == 0) {
			$error_sumForCustomer = "Не заполнено поле";
			$error = true;
		}		
		if(strlen($sumForCarrier) == 0) {
			$error_sumForCarrier = "Не заполнено поле";
			$error = true;
		}
				if(strlen($carrier) == 0) {
			$error_carrier = "Не заполнено поле";
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
				if(strlen($time) == 0) {
			$error_time = "Не заполнено поле";
			$error = true;
		}
				if(strlen($term) == 0) {
			$error_term = "Не заполнено поле";
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
			$result_set = $db->getRowWhere("form_requests_customer", "id", $id);
			$row = $result_set->fetch_assoc();

		$result_set12 = $db->getRowWhere("contractors", "name", $row[customer]);
		$row12 = $result_set12->fetch_assoc();


			$number = $db->getLastNumber();
			$number++;


			//перебросить заявку из "Прием заявок" в "Заявки"
			$db->addRequest($row12[id], $row8[id], $number, strtotime($row[date]), $cargo, $row[carrier_auto_nr], $row[carrier_fio], $customerPrice, $carrierPrice, $row[idUser], $isCurrencyPayment, $comision_static, $currency,
								$from, $to, strtotime($row[dateShipping]), $time, $term, $row[adress_load], $row[adress_unload], $row[carrier_transport_type], $row[customer_name], $row[contact_factory], $row[carrier_adress], $temperature, $row[custom_in], $row[custom_out], $row[broker], $row[carrier_pogran], 
								$row[carrier_phone], $sumForCustomer, $sumForCarrier, $row[weight], $row[weight_var], $row[v], $row[v_var]);





			$result_set3 = $db->getRowWhere("requests", "number", $number);
			if($result_set3->num_rows == 0) {

			} else {
				$db->updateFormAdded($id, '+');
			}



			//сменить статус "Прием заявок" 
			$db->addLog($row12[id], time(), "Заявки. Контрагент добавлен в качестве заказчика. Заявка №$number", 2, $id, $_SESSION["id"]);
			$db->addLog($row8[id], time(), "Заявки. Контрагент добавлен в качестве перевозчика. Заявка №$number", 2, $id, $_SESSION["id"]);
			
			//все добавленные заявки дублировать в растаможку
			$idNewFile = $db->getLastID("customs_clearance");
			$idNewFile++;
			$db->addCustomsClearance($idNewFile, $row12[id], $row8[id], $number, time(), $carrierPrice, "", "");
			$db->addLog($row12[id], time(), "Растаможка. Контрагент добавлен в качестве заказчика. Заявка №$number", 1, $idNewFile, $_SESSION["id"]);
			$db->addLog($row8[id], time(), "Растаможка. Контрагент добавлен в качестве перевозчика. Заявка №$number", 1, $idNewFile, $_SESSION["id"]);
					
			header("Location: requests.php?success");
			exit;
		} else {
			echo '<div style="text-align:center; font-weight:bold;">Нужно добавить фирму в базу данных для начала!</div>';
		}
	}
	else if(isset($_POST["cancel"])) {
		header("Location: formRequests.php");
		exit;		
	}
	
	
	$result_set2 = $db->getRowWhereOrder("users", "isDeleted", 0, "name");
    require_once '../partsOfPages/head.php';
    if($_SESSION["userType"] == 1) 		require_once "../partsOfPages/menuForAdmin.php"; 
    else if($_SESSION["userType"] == 2) require_once "../partsOfPages/menuForUser.php"; ?>
    <div class="col-md-8 pt-3">
        <form name="" action="" method="post" enctype="multipart/form-data">
            <input class="btn btn-danger btn-sm" type="submit" name="cancel" value="Вернуться назад"> <br /> <br />
            <div class="row">
            	<div class="form-group col-md-4 mr-4" style="display: none;">
                    <label for="tag1" class="required">Выберите перевозчика</label>
                    <input class="form-control<?=isset($error_carrier)&&$error_carrier!=''?' is-invalid':''?>" type="text" name="carrier" id="tag1" autocomplete="off" value="<?=$row[carrier]?>">
                    <div class="invalid-feedback" <?=isset($error_carrier)&&$error_carrier!=''?'style="display:block;"':''?>><?=$error_carrier ?></div>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="carrierPriceInput" class="required">Перевозчик сумма</label>
                    <input class="form-control<?=isset($error_carrierPrice)&&$error_carrierPrice!=''?' is-invalid':''?>" type="text" name="carrierPrice" id="carrierPriceInput" autocomplete="off" value="<?=$carrierPrice?>">
                    <div class="invalid-feedback" <?=isset($error_carrierPrice)&&$error_carrierPrice!=''?'style="display:block;"':''?>><?=$error_carrierPrice ?></div>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="customerPriceInput" class="required">Заказчик сумма</label>
                    <input class="form-control<?=isset($error_customerPrice)&&$error_customerPrice!=''?' is-invalid':''?>" type="text" name="customerPrice" id="customerPriceInput" autocomplete="off" value="<?=$customerPrice?>">
                    <div class="invalid-feedback" <?=isset($error_customerPrice)&&$error_customerPrice!=''?'style="display:block;"':''?>><?=$error_customerPrice ?></div>
                </div>
            </div>
            <div class="row">

		<div class="form-group col-md-4 mr-4">
			<label for="sumForCarrierInput" class="required">Перевозчик сумма(прописью)</label>
			<input class="form-control<?=isset($error_sumForCarrier)&&$error_sumForCarrier!=''?' is-invalid':''?>" type="text" name="sumForCarrier" id="sumForCarrierInput" autocomplete="off" value="<?=$sumForCarrier?>">
			<div class="invalid-feedback" <?=isset($error_sumForCarrier)&&$error_sumForCarrier!=''?'style="display:block;"':''?>><?=$error_sumForCarrier ?></div>
		</div>
		<div class="form-group col-md-4 mr-4">
			<label for="sumForCustomerInput" class="required">Заказчик сумма(прописью)</label>
			<input class="form-control<?=isset($error_sumForCustomer)&&$error_sumForCustomer!=''?' is-invalid':''?>" type="text" name="sumForCustomer" id="sumForCustomerInput" autocomplete="off" value="<?=$sumForCustomer?>">
			<div class="invalid-feedback" <?=isset($error_sumForCustomer)&&$error_sumForCustomer!=''?'style="display:block;"':''?>><?=$error_sumForCustomer ?></div>
		</div>
            </div>
			<div class="row">
				<div class="form-group col-md-4 mr-4">
						<label for="timeInput" class="required">Время погрузки</label>
						<input class="form-control<?=isset($error_time)&&$error_time!=''?' is-invalid':''?>" type="text" name="time" id="timeInput" autocomplete="off" value="<?=$time?>">
						<div class="invalid-feedback" <?=isset($error_time)&&$error_time!=''?'style="display:block;"':''?>><?=$error_time ?></div>
					</div>
					<div class="form-group col-md-4 mr-4">
						<label for="termInput" class="required">Срок доставки</label>
						<input class="form-control<?=isset($error_term)&&$error_term!=''?' is-invalid':''?>" type="text" name="term" id="termInput" autocomplete="off" value="<?=$term?>">
						<div class="invalid-feedback" <?=isset($error_term)&&$error_term!=''?'style="display:block;"':''?>><?=$error_term ?></div>
					</div>	
			</div>
			<div class="row">
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

		<div class="form-group col-md-4 mr-4">
			<? if($isCurrencyPayment == 1) {?>
				<input type="checkbox" checked name="isCurrencyPayment" value="1" style="    margin-bottom: 15px;"> <b>Валютный платёж</b> <br />
			<?} else {?>
				<input type="checkbox" name="isCurrencyPayment" value="1" style="    margin-bottom: 15px;"> <b>Валютный платёж</b> <br />
			<?}?>
			<select class="form-control" name="comision_static" id="comision_static">
                        <? if($row[comision_static] == "") { ?>
                            <option selected="selected"></option>
                        <?}
                        else { ?>
                            <option selected="selected"> <?=$row[comision_static] ?> </option>
                        <?}?>
                        <? for($i = 0; $i < count($arrayOfcomision_static); $i++) {
                            if($row[comision_static] != $arrayOfcomision_static[$i]) { ?>
                                <option><?=$arrayOfcomision_static[$i] ?></option>
                            <?}?>
                        <?}?>
                    </select>
		</div>
			</div>
<div class="row">
					<div class="form-group col-md-4 mr-4">
			<label for="cargoInput" class="required">Груз</label>
			<input class="form-control<?=isset($error_cargo)&&$error_cargo!=''?' is-invalid':''?>" type="text" name="cargo" id="cargoInput" autocomplete="off" value="<?=$cargo?>">
			<div class="invalid-feedback" <?=isset($error_cargo)&&$error_cargo!=''?'style="display:block;"':''?>><?=$error_cargo ?></div>
		</div>		
				<div class="form-group col-md-4 mr-4">
			<label for="temperatureInput" class="required">Температурный режим</label>
			<input class="form-control<?=isset($error_temperature)&&$error_temperature!=''?' is-invalid':''?>" type="text" name="temperature" id="temperatureInput" autocomplete="off" value="<?=$temperature?>">
			<div class="invalid-feedback" <?=isset($error_temperature)&&$error_temperature!=''?'style="display:block;"':''?>><?=$error_temperature ?></div>
		</div>	
</div>
<div class="row">
		<div class="form-group col-md-4 mr-4">
			<label for="fromInput" class="required">Откуда</label>
			<input class="form-control<?=isset($error_from)&&$error_from!=''?' is-invalid':''?>" type="text" name="from" id="fromInput" autocomplete="off" value="<?=$from?>">
			<div class="invalid-feedback" <?=isset($error_from)&&$error_from!=''?'style="display:block;"':''?>><?=$error_from ?></div>
		</div>
		<div class="form-group col-md-4 mr-4">
			<label for="toInput" class="required">Куда</label>
			<input class="form-control<?=isset($error_to)&&$error_to!=''?' is-invalid':''?>" type="text" name="to" id="toInput" autocomplete="off" value="<?=$to?>">
			<div class="invalid-feedback" <?=isset($error_to)&&$error_to!=''?'style="display:block;"':''?>><?=$error_to ?></div>
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
        function autocompletefromInput(){
            var awesomplete = new Awesomplete(document.querySelector("#fromInput"), {minChars: 3,autoFirst: true});
            $("#fromInput").on("keyup", function(){
                let thisval = $(this).val();
                if(thisval.length>=3){
                    $.ajax({
                        url: 'https://geocode-maps.yandex.ru/1.x/?format=json&apikey=42f4289f-31ea-4a01-9939-e6785142a533&geocode=' + thisval,
                        type: 'GET',
                        dataType: 'json',
                        success: function (data) { 
                            var list = [];
                            for(var i = 0; i < data.response.GeoObjectCollection.featureMember.length; i++) {
                                var kind = data.response.GeoObjectCollection.featureMember[i].GeoObject.metaDataProperty.GeocoderMetaData.kind;
                                if(kind!='locality'&&kind!='province'&&kind!='country'){
                                    continue;
                                }else{
                                    //записываем в массив результаты, которые возвращает нам геокодер
                                    list.push(data.response.GeoObjectCollection.featureMember[i].GeoObject.name+', '+data.response.GeoObjectCollection.featureMember[i].GeoObject.metaDataProperty.GeocoderMetaData.AddressDetails.Country.CountryName);
                                }
                            }
                            awesomplete.list = list;
                        }
                    });
                }
            });
        }
        function autocompleteToInput(){
            var awesomplete = new Awesomplete(document.querySelector("#toInput"), {minChars: 3,autoFirst: true});
            $("#toInput").on("keyup", function(){
                let thisval = $(this).val();
                if(thisval.length>=3){
                    $.ajax({
                        url: 'https://geocode-maps.yandex.ru/1.x/?format=json&apikey=42f4289f-31ea-4a01-9939-e6785142a533&geocode=' + thisval,
                        type: 'GET',
                        dataType: 'json',
                        success: function (data) { 
                            var list = [];
                            for(var i = 0; i < data.response.GeoObjectCollection.featureMember.length; i++) {
                                var kind = data.response.GeoObjectCollection.featureMember[i].GeoObject.metaDataProperty.GeocoderMetaData.kind;
                                if(kind!='locality'&&kind!='province'&&kind!='country'){
                                    continue;
                                }else{
                                    //записываем в массив результаты, которые возвращает нам геокодер
                                    list.push(data.response.GeoObjectCollection.featureMember[i].GeoObject.name+', '+data.response.GeoObjectCollection.featureMember[i].GeoObject.metaDataProperty.GeocoderMetaData.AddressDetails.Country.CountryName);
                                }
                            }
                            awesomplete.list = list;
                        }
                    });
                }
            });
        }
        
		$(function(){
            autocompleteTag();
            autocompleteTag1();
            autocompletefromInput();
            autocompleteToInput();
            var postDate = $('#dateInput').val();
            var datepicker = $('#dateInput').datepicker().data('datepicker');
            if(postDate!=''){
                postDate = postDate.split(".");
                datepicker.selectDate(new Date(postDate[2],postDate[1]- 1,postDate[0]));
            }
        });
	</script>
<?  require_once '../partsOfPages/footer.php';?>