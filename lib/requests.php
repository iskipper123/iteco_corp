<?php session_start();
  ob_start(); 

	require_once "../lib/db.php";
    require_once "../lib/vars.php"; 

    $db = DB::getObject();
  	$result_set3 = $db->getAllUsersForAdmin(); 


  	$result_set4 = $db->getUserByID($row[partener]); // get manager name
    $row5 = $result_set4->fetch_assoc();


  $activePage = basename($_SERVER['PHP_SELF'], ".php"); ?>
<form id="contactForm" name="" action="" method="post" enctype="multipart/form-data">
	<!-- <input class="btn btn-danger btn-sm" type="submit" name="cancel" value="Вернуться назад"> <br /> <br /> -->
	<div class="row">
		<div class="form-group col-md-4 mr-4">
			<label for="dateInput" class="required">Дата заключения заявки</label>
		<?php if ($activePage == 'editRequest') { ?>
			<input class="form-control" type="hidden" name="id" id="id" autocomplete="off" value="<?=isset($_POST["id"])? $_POST["id"]:$row["id"]?>">
		<?php } ?>
			<input class="form-control<?=isset($error_date)&&$error_date!=''?' is-invalid':''?> datepicker-here" data-auto-close="true" type="text" name="date" id="dateInput" placeholder="дд.мм.гггг" autocomplete="off" value="<?=isset($_POST["date"])? $_POST["date"]:$date?>">
			<div class="invalid-feedback" <?=isset($error_date)&&$error_date!=''?'style="display:block;"':''?>><?=$error_date ?></div>
		</div>
		<div class="form-group col-md-4 mr-4">
			<label for="dateInput" class="required">Дата погрузки</label>
			<input class="form-control<?=isset($error_dateShipping)&&$error_dateShipping!=''?' is-invalid':''?> datepicker-here" data-auto-close="true" type="text" name="dateShipping" id="dateInput" placeholder="дд.мм.гггг" autocomplete="off" value="<?=isset($_POST["dateShipping"])? $_POST["dateShipping"]:$dateShipping?>">
			<div class="invalid-feedback" <?=isset($error_dateShipping)&&$error_dateShipping!=''?'style="display:block;"':''?>><?=$error_dateShipping?></div>
		</div>
		<div class="form-group col-md-4 mr-4">
			<label for="timeInput" class="required">Время погрузки</label>
			<input class="form-control<?=isset($error_time)&&$error_time!=''?' is-invalid':''?>" type="text" name="time" id="timeInput" autocomplete="off" value="<?=isset($_POST["time"])? $_POST["time"]:$row["time"]?>">
			<div class="invalid-feedback" <?=isset($error_time)&&$error_time!=''?'style="display:block;"':''?>><?=$error_time ?></div>
		</div>
		<div class="form-group col-md-4 mr-4">
			<label for="termInput" class="required">Срок доставки</label>
			<input class="form-control<?=isset($error_term)&&$error_term!=''?' is-invalid':''?>" type="text" name="term" id="termInput" autocomplete="off" value="<?=isset($_POST["term"])? $_POST["term"]:$row["term"]?>">
			<div class="invalid-feedback" <?=isset($error_term)&&$error_term!=''?'style="display:block;"':''?>><?=$error_term ?></div>
		</div>	
		<div class="form-group col-md-4 mr-4">
			<label for="tag" class="required">Выберите заказчика</label>
			<input class="form-control<?=isset($error_customer)&&$error_customer!=''?' is-invalid':''?>" type="text" name="customer" id="tag" autocomplete="off" value="<?=isset($_POST["customer"])? $_POST["customer"]:$customer?>">
			<div class="invalid-feedback" <?=isset($error_customer)&&$error_customer!=''?'style="display:block;"':''?>><?=$error_customer ?></div>
		</div>
		<div class="form-group col-md-4 mr-4">
			<label for="tag1" class="required">Выберите перевозчика</label>
			<input class="form-control<?=isset($error_carrier)&&$error_carrier!=''?' is-invalid':''?>" type="text" name="carrier" id="tag1" autocomplete="off" value="<?=isset($_POST["carrier"])? $_POST["carrier"]:$carrier?>">
			<div class="invalid-feedback" <?=isset($error_carrier)&&$error_carrier!=''?'style="display:block;"':''?>><?=$error_carrier ?></div>
		</div>
		<div class="form-group col-md-4 mr-4">
			<label for="carNumberInput" class="required">Номер автомобиля</label>
			<input class="form-control<?=isset($error_carNumber)&&$error_carNumber!=''?' is-invalid':''?>" type="text" name="carNumber" id="carNumberInput" autocomplete="off" value="<?=isset($_POST["carNumber"])? $_POST["carNumber"]:$row[carNumber]?>">
			<div class="invalid-feedback" <?=isset($error_carNumber)&&$error_carNumber!=''?'style="display:block;"':''?>><?=$error_carNumber ?></div>
		</div>
		<div class="form-group col-md-4 mr-4">
			<label for="fioInput" class="required">Водитель ФИО</label>
			<input class="form-control<?=isset($error_fio)&&$error_fio!=''?' is-invalid':''?>" type="text" name="fio" id="fioInput" autocomplete="off" value="<?=isset($_POST["fio"])? $_POST["fio"]:$row[fio]?>">
			<div class="invalid-feedback" <?=isset($error_fio)&&$error_fio!=''?'style="display:block;"':''?>><?=$error_fio ?></div>
		</div>
		<div class="form-group col-md-4 mr-4">
			<label for="driverPhonesInput" class="required">Контактные телефоны водителя</label>
			<textarea class="form-control<?=isset($error_driverPhones)&&$error_driverPhones!=''?' is-invalid':''?>" name="driverPhones" id="driverPhonesInput" ><?=isset($_POST["driverPhones"])? $_POST["driverPhones"]:$row[driverPhones]?></textarea>
			<div class="invalid-feedback" <?=isset($error_driverPhones)&&$error_driverPhones!=''?'style="display:block;"':''?>><?=$error_driverPhones ?></div>
		</div>
		<div class="form-group col-md-4 mr-4">
			<label for="weightInput" class="required">Вес</label>
			<input class="form-control<?=isset($error_weight)&&$error_weight!=''?' is-invalid':''?>" type="text" name="weight" id="weightInput" autocomplete="off" value="<?=isset($_POST["weight"])? $_POST["weight"]:$row[weight]?>">
 			<select class="form-control<?=isset($error_weight)&&$error_weight!=''?' is-invalid':''?>" name="weight_var" id="weight_var">
                        <? if($row[weight_var] == "") { ?>
                            <option selected="selected"></option>
                        <?}
                        else { ?>
                            <option selected="selected"> <?=$row[weight_var] ?> </option>
                        <?}?>
                        <? for($i = 0; $i < count($arrayOfweight); $i++) {
                            if($row[weight_var] != $arrayOfweight[$i]) { ?>
                                <option><?=$arrayOfweight[$i] ?></option>
                            <?}?>
                        <?}?>
                    </select>
			<div class="invalid-feedback" <?=isset($error_weight)&&$error_weight!=''?'style="display:block;"':''?>><?=$error_weight ?></div>
		</div>
		<div class="form-group col-md-4 mr-4">
			<label for="vInput" class="required">Объем</label>
			<input class="form-control<?=isset($error_v)&&$error_v!=''?' is-invalid':''?>" type="text" name="v" id="vInput" autocomplete="off" value="<?=isset($_POST["v"])? $_POST["v"]:$row[v]?>">
                   <select class="form-control<?=isset($error_v)&&$error_v!=''?' is-invalid':''?>" name="v_var" id="v_var">
                        <? if($row[v_var] == "") { ?>
                            <option selected="selected"></option>
                        <?}
                        else { ?>
                            <option selected="selected"> <?=$row[v_var] ?> </option>
                        <?}?>
                        <? for($i = 0; $i < count($arrayOfv); $i++) {
                            if($row[v_var] != $arrayOfv[$i]) { ?>
                                <option><?=$arrayOfv[$i] ?></option>
                            <?}?>
                        <?}?>
                    </select>
			<div class="invalid-feedback" <?=isset($error_v)&&$error_v!=''?'style="display:block;"':''?>><?=$error_v ?></div>
		</div>

		<div class="form-group col-md-4 mr-4">
			<label for="categoryInput" class="required">Тип транспорта</label>
			<select class="form-control<?=isset($error_transportType)&&$error_transportType!=''?' is-invalid':''?>" name="transportType" id="categoryInput">
				<option selected="selected"></option>
				<? for($i = 0; $i < count($arrayOfTransportType); $i++) {
					if($transportType == $arrayOfTransportType[$i]) { ?>
						<option selected="selected"><?=$arrayOfTransportType[$i] ?></option>
					<?}
					else {?>
						<option><?=$arrayOfTransportType[$i] ?></option>
					<?}?>
				<?}?>
			</select>
			<div class="invalid-feedback" <?=isset($error_transportType)&&$error_transportType!=''?'style="display:block;"':''?>><?=$error_transportType ?></div>
		</div>
		<div class="form-group col-md-4 mr-4">
			<label for="fromInput" class="required">Откуда</label>
			<input class="form-control<?=isset($error_from)&&$error_from!=''?' is-invalid':''?>" type="text" name="from" id="fromInput" autocomplete="off" value="<?=isset($_POST["from"])? $_POST["from"]:$row['from']?>">
			<div class="invalid-feedback" <?=isset($error_from)&&$error_from!=''?'style="display:block;"':''?>><?=$error_from ?></div>
		</div>
		<div class="form-group col-md-4 mr-4">
			<label for="toInput" class="required">Куда</label>
			<input class="form-control<?=isset($error_to)&&$error_to!=''?' is-invalid':''?>" type="text" name="to" id="toInput" autocomplete="off" value="<?=isset($_POST["to"])? $_POST["to"]:$row['to']?>">
			<div class="invalid-feedback" <?=isset($error_to)&&$error_to!=''?'style="display:block;"':''?>><?=$error_to ?></div>
		</div>
		<div class="form-group col-md-4 mr-4">
			<label for="routeInput" class="required">Маршрут следования</label>
			<textarea class="form-control<?=isset($error_route)&&$error_route!=''?' is-invalid':''?>" name="route" id="routeInput" ><?=isset($_POST["route"])? $_POST["route"]:$row[route]?></textarea>
			<div class="invalid-feedback" <?=isset($error_route)&&$error_route!=''?'style="display:block;"':''?>><?=$error_route ?></div>
		</div>
		<div class="form-group col-md-4 mr-4">
			<label for="pogranInput" class="required">Погран - Переходы</label>
			<textarea class="form-control<?=isset($error_pogran)&&$error_pogran!=''?' is-invalid':''?>" name="pogran" id="pogranInput" ><?=isset($_POST["pogran"])? $_POST["pogran"]:$row[pogran]?></textarea>
			<div class="invalid-feedback" <?=isset($error_pogran)&&$error_pogran!=''?'style="display:block;"':''?>><?=$error_pogran ?></div>
		</div>
		<div class="form-group col-md-4 mr-4">
			<label for="brokerInput" class="required">Декларант (Broker)</label>
			<textarea class="form-control<?=isset($error_broker)&&$error_broker!=''?' is-invalid':''?>" name="broker" id="brokerInput" ><?=isset($_POST["broker"])? $_POST["broker"]:$row[broker]?></textarea>
			<div class="invalid-feedback" <?=isset($error_broker)&&$error_broker!=''?'style="display:block;"':''?>><?=$error_broker ?></div>
		</div>		
		<div class="form-group col-md-4 mr-4">
			<label for="address1Input" class="required">Адрес загрузки</label>
			<textarea class="form-control<?=isset($error_address1)&&$error_address1!=''?' is-invalid':''?>" name="address1" id="address1Input" ><?=isset($_POST["address1"])? $_POST["address1"]:$row[address1]?></textarea>
			<div class="invalid-feedback" <?=isset($error_address1)&&$error_address1!=''?'style="display:block;"':''?>><?=$error_address1 ?></div>
		</div>
		<div class="form-group col-md-4 mr-4">
			<label for="address2Input" class="required">Адрес разгрузки</label>
			<textarea class="form-control<?=isset($error_address2)&&$error_address2!=''?' is-invalid':''?>" name="address2" id="address2Input" ><?=isset($_POST["address2"])? $_POST["address2"]:$row[address2]?></textarea>
			<div class="invalid-feedback" <?=isset($error_address2)&&$error_address2!=''?'style="display:block;"':''?>><?=$error_address2 ?></div>
		</div>
		<div class="form-group col-md-4 mr-4">
			<label for="contactName1Input">Контактное лицо на загрузке</label>
			<textarea class="form-control<?=isset($error_contactName1)&&$error_contactName1!=''?' is-invalid':''?>" name="contactName1" id="contactName1Input" ><?=isset($_POST["contactName1"])? $_POST["contactName1"]:$row[contactName1]?></textarea>
			<div class="invalid-feedback" <?=isset($error_contactName1)&&$error_contactName1!=''?'style="display:block;"':''?>><?=$error_contactName1 ?></div>
		</div>
		<div class="form-group col-md-4 mr-4">
			<label for="contactName2Input" class="required">Контактное лицо в РМ</label>
			<textarea class="form-control<?=isset($error_contactName2)&&$error_contactName2!=''?' is-invalid':''?>" name="contactName2" id="contactName2Input" ><?=isset($_POST["contactName2"])? $_POST["contactName2"]:$row[contactName2]?></textarea>
			<div class="invalid-feedback" <?=isset($error_contactName2)&&$error_contactName2!=''?'style="display:block;"':''?>><?=$error_contactName2 ?></div>
		</div>		

		<div class="form-group col-md-4 mr-4">
			<label for="customs1Input" class="required">Таможня импорта</label>
			<textarea class="form-control<?=isset($error_customs1)&&$error_customs1!=''?' is-invalid':''?>" name="customs1" id="customs1Input" ><?=isset($_POST["customs1"])? $_POST["customs1"]:$row[customs1]?></textarea>
			<div class="invalid-feedback" <?=isset($error_customs1)&&$error_customs1!=''?'style="display:block;"':''?>><?=$error_customs1 ?></div>
		</div>
		<div class="form-group col-md-4 mr-4">
			<label for="customs2Input" class="required">Таможня экспорта</label>
			<textarea class="form-control<?=isset($error_customs2)&&$error_customs2!=''?' is-invalid':''?>" name="customs2" id="customs2Input" ><?=isset($_POST["customs2"])? $_POST["customs2"]:$row[customs2]?></textarea>
			<div class="invalid-feedback" <?=isset($error_customs2)&&$error_customs2!=''?'style="display:block;"':''?>><?=$error_customs2 ?></div>
		</div>
		<div class="form-group col-md-4 mr-4">
			<label for="temperatureInput" class="required">Температурный режим</label>
				<input class="form-control<?=isset($error_temperature)&&$error_temperature!=''?' is-invalid':''?>" type="text" name="temperature" id="temperatureInput" autocomplete="off" value="<?=isset($_POST["temperature"])? $_POST["temperature"]:$row[temperature]?>">
			<div class="invalid-feedback" <?=isset($error_temperature)&&$error_temperature!=''?'style="display:block;"':''?>><?=$error_temperature ?></div>
		</div>		
		<div class="form-group col-md-4 mr-4">
			<label for="customerPriceInput" class="required">Заказчик сумма</label>
			<input class="form-control<?=isset($error_customerPrice)&&$error_customerPrice!=''?' is-invalid':''?>" type="text" name="customerPrice" id="customerPriceInput" autocomplete="off" value="<?=isset($_POST["customerPrice"])? $_POST["customerPrice"]:$row[customerPrice]?>" >
			<div class="invalid-feedback" <?=isset($error_customerPrice)&&$error_customerPrice!=''?'style="display:block;"':''?>><?=$error_customerPrice ?></div>
		</div>
		<div class="form-group col-md-4 mr-4">
			<label for="carrierPriceInput" class="required">Перевозчик сумма</label>
			<input class="form-control<?=isset($error_carrierPrice)&&$error_carrierPrice!=''?' is-invalid':''?>" type="text" name="carrierPrice" id="carrierPriceInput" autocomplete="off" value="<?=isset($_POST["carrierPrice"])? $_POST["carrierPrice"]:$row[carrierPrice]?>" >
			<div class="invalid-feedback" <?=isset($error_carrierPrice)&&$error_carrierPrice!=''?'style="display:block;"':''?>><?=$error_carrierPrice ?></div>
		</div>
		<div class="form-group col-md-4 mr-4" style="display: none;">
			<label for="sumForCustomerInput" class="required">Заказчик сумма(прописью)</label>
			<input class="form-control<?=isset($error_sumForCustomer)&&$error_sumForCustomer!=''?' is-invalid':''?>" type="text" name="sumForCustomer" id="sumForCustomerInput" autocomplete="off" value="<?=isset($_POST["sumForCustomer"])? $_POST["sumForCustomer"]:$row[sumForCustomer]?>">
			<div class="invalid-feedback" <?=isset($error_sumForCustomer)&&$error_sumForCustomer!=''?'style="display:block;"':''?>><?=$error_sumForCustomer ?></div>
		</div>
		<div class="form-group col-md-4 mr-4" style="display: none;">
			<label for="sumForCarrierInput" class="required">Перевозчик сумма(прописью)</label>
			<input class="form-control<?=isset($error_sumForCarrier)&&$error_sumForCarrier!=''?' is-invalid':''?>" type="text" name="sumForCarrier" id="sumForCarrierInput" autocomplete="off" value="<?=isset($_POST["sumForCarrier"])? $_POST["sumForCarrier"]:$row[sumForCarrier]?>">
			<div class="invalid-feedback" <?=isset($error_sumForCarrier)&&$error_sumForCarrier!=''?'style="display:block;"':''?>><?=$error_sumForCarrier ?></div>
		</div>
				<div class="form-group col-md-4 mr-4">
			<label for="pay_variant" class="required">Вариант оплаты</label>
			<select class="form-control" name="pay_variant" id="pay_variant">
                        <? if($row["pay_variant"] == "") { ?>
                            <option selected="selected"></option>
                        <?}
                        else { ?>
                            <option selected="selected"> <?=$row[pay_variant] ?> </option>
                        <?}?>
                        <? for($i = 0; $i < count($arrayOfpay_variant); $i++) {
                            if($row["pay_variant"] != $arrayOfpay_variant[$i]) { ?>
                                <option><?=$arrayOfpay_variant[$i] ?></option>
                            <?}?>
                        <?}?>
                    </select>
		</div>

		<div class="form-group col-md-4 mr-4">
			<label for="languages">Язык документов</label>
		
			<select class="form-control" name="languages" id="languages">
                        <? if($row["languages"] == "") { ?>
                            <option selected="selected"></option>
                        <?}
                        else { ?>
                            <option selected="selected"> <?=$row[languages] ?> </option>
                        <?}?>
                        <? for($i = 0; $i < count($arrayOflanguages); $i++) {
                            if($row["avans_summ"] != $arrayOflanguages[$i]) { ?>
                                <option><?=$arrayOflanguages[$i] ?></option>
                            <?}?>
                        <?}?>
                    </select>
		</div>		
			<div class="form-group col-md-4 mr-4" <? if($_SESSION["role"] != 1) {?>style="display: none;"<?php } ?>>
				<label for="idUserInput" class="required">Выберите сотрудника</label>
				<select class="form-control<?=isset($error_idUser)&&$error_idUser!=''?' is-invalid':''?>" name="idUser" id="idUserInput">
					 <? if($_SESSION["role"] != 1) {?><option value="<?php echo $_SESSION["id"]; ?>" selected="selected"><?php echo $_SESSION["login"]; ?></option><?php } else { ?><option selected="selected"></option><?php } ?>
					<? while (($row2 = $result_set2->fetch_assoc()) != false) { ?>
						<?  if($row2[id] == $idUser) {?>
								<option selected="selected" value="<?=$row2[id]?>"><?=$row2[name]?></option>
						<?}
						else {?>
							<option value="<?=$row2[id]?>"><?=$row2[name]?></option>
						<?}?>
					<?}?>
				</select>
				<div class="invalid-feedback" <?=isset($error_idUser)&&$error_idUser!=''?'style="display:block;"':''?>><?=$error_idUser ?></div>
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
		<div class="form-group col-md-4 mr-4">
			<label for="cargoInput" class="required">Груз</label>
			<input class="form-control<?=isset($error_cargo)&&$error_cargo!=''?' is-invalid':''?>" type="text" name="cargo" id="cargoInput" autocomplete="off" value="<?=isset($_POST["cargo"])? $_POST["cargo"]:$row[cargo]?>">
			<div class="invalid-feedback" <?=isset($error_cargo)&&$error_cargo!=''?'style="display:block;"':''?>><?=$error_cargo ?></div>
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

			<div class="form-group col-md-4 mr-4">
				<label for="idUserInput" class="required">Ответственный за груз</label>
					<select class="form-control<?=isset($error_idUser)&&$error_idUser!=''?' is-invalid':''?>" name="partener" id="partener">
					<?php if ($row[partener] != '') {?> <option value="<?php echo $row[partener]; ?>" selected="selected"><?php echo $row5[name]; ?></option>
						<?php } else { ?>
							<option value="<?php echo $_SESSION["id"]; ?>" selected="selected"></option>
					<?php } ?>

					<? while (($row3 = $result_set3->fetch_assoc()) != false) { ?>
						<?php if ($row3[manager_variant] == 'transport' || $row3[manager_variant] == 'all' ) { ?>
							<option value="<?=$row3[id]?>"><?=$row3[name]?></option> 
						<?php } ?>
					<?}?>
				</select>
				<div class="invalid-feedback" <?=isset($error_partener)&&$error_partener!=''?'style="display:block;"':''?>><?=$error_partener ?></div>
			</div>
 
 				<div class="form-group col-md-4 mr-4">
				<label for="CustomsInput" class="required">Таможня</label>
					<select class="form-control" name="customs" id="customs">
					<?php if ($row[customs] != '') {?> <option value="<?php echo $row[customs]; ?>" selected="selected">

						<?php if($row[customs]=='import'){echo 'Импорта';} elseif($row[customs]=='export') {echo 'Экспорта';} ?></option>
						<?php } else { ?>
							<option value="" selected="selected"></option>
					<?php } ?>

							<option value="import">Импорта</option> 
							<option value="export">Экспорта</option> 
				</select>
				<div class="invalid-feedback" <?=isset($error_customs)&&$error_customs!=''?'style="display:block;"':''?>><?=$error_customs ?></div>
			</div>
		
	</div>  

	<?php if ($activePage == 'addRequest') { ?>
<input class="btn btn-secondary btn-sm" id="add" type="submit" name="add" onclick="addRequest()" value="Сохранить"> 
<?php } else if ($activePage == 'editRequest') { ?>
<input class="btn btn-secondary btn-sm" id="add" type="submit" name="add" onclick="editRequest()" value="Сохранить"> 
<?php }  else if ($activePage == 'duplicateRequest') { ?>
<input class="btn btn-secondary btn-sm" id="add" type="submit" name="add" onclick="addRequest()" value="Сохранить"> 
<?php } ?>

</form>


  <script>



function addRequest(){
	 $.ajax({
		type: "POST", 
		url: "addRequest_ajax.php",
		data: $('#contactForm').serialize(),
		 beforeSend: function () {
                $('#add').attr("disabled","disabled");
                $('.modal-body').css('opacity', '.5');
            },
            success:function(msg){

                if(msg == 'err'){
                	$('.statusMsg').html('<span style="color:red; text-align:center;">Вы не заполнили все поля!</span>');
                } else {
                   $('.statusMsg').html('<span style="color:green; text-align:center;">Ваша заявка принята! Спасибо что Вы с нами)</p>');
                   $('#contactForm')[0].reset();
                }

                $('#add').removeAttr("disabled");
                $('.modal-body').css('opacity', '');
            }
	}); 
	}

	function editRequest(){
	 $.ajax({
		type: "POST",
		url: "editRequest_ajax.php", 
		data: $('#contactForm').serialize(),
		 beforeSend: function () {
                $('#add').attr("disabled","disabled");
                $('.modal-body').css('opacity', '.5');
            },
            success:function(msg){
                if(msg == 'err'){
                	$('.statusMsg').html('<span style="color:red; text-align:center;">Ошибка</span>');
                } else {
                   $('.statusMsg').html('<span style="color:green; text-align:center;">Данные заявки сохранены!</p>');
                }
                $('#add').removeAttr("disabled");
                $('.modal-body').css('opacity', '');
            }
	}); 
	}
	
var mapNumbers = {
    0 : [2, 1, "ноль"],
    1 : [0, 2, "один", "одна"],
    2 : [1, 2, "два", "две"],
    3 : [1, 1, "три"],
    4 : [1, 1, "четыре"],
    5 : [2, 1, "пять"],
    6 : [2, 1, "шесть"],
    7 : [2, 1, "семь"],
    8 : [2, 1, "восемь"],
    9 : [2, 1, "девять"],
    10 : [2, 1, "десять"],
    11 : [2, 1, "одиннадцать"],
    12 : [2, 1, "двенадцать"],
    13 : [2, 1, "тринадцать"],
    14 : [2, 1, "четырнадцать"],
    15 : [2, 1, "пятнадцать"],
    16 : [2, 1, "шестнадцать"],
    17 : [2, 1, "семнадцать"],
    18 : [2, 1, "восемнадцать"],
    19 : [2, 1, "девятнадцать"],
    20 : [2, 1, "двадцать"],
    30 : [2, 1, "тридцать"],
    40 : [2, 1, "сорок"],
    50 : [2, 1, "пятьдесят"],
    60 : [2, 1, "шестьдесят"],
    70 : [2, 1, "семьдесят"],
    80 : [2, 1, "восемьдесят"],
    90 : [2, 1, "девяносто"],
    100 : [2, 1, "сто"],
    200 : [2, 1, "двести"],
    300 : [2, 1, "триста"],
    400 : [2, 1, "четыреста"],
    500 : [2, 1, "пятьсот"],
    600 : [2, 1, "шестьсот"],
    700 : [2, 1, "семьсот"],
    800 : [2, 1, "восемьсот"],
    900 : [2, 1, "девятьсот"]
};

var mapOrders = [
    { _Gender : true, _arrStates : ["", "", ""] },
    { _Gender : false, _arrStates : ["тысяча", "тысячи", "тысяч"] },
    { _Gender : true, _arrStates : ["миллион", "миллиона", "миллионов"] },
    { _Gender : true, _arrStates : ["миллиард", "миллиарда", "миллиардов"] },
    { _Gender : true, _arrStates : ["триллион", "триллиона", "триллионов"] }
];

var objKop = { _Gender : false, _arrStates : ["копейка", "копейки", "копеек"] };

function Value(dVal, bGender) {
    var xVal = mapNumbers[dVal];
    if (xVal[1] == 1) {
        return xVal[2];
    } else {
        return xVal[2 + (bGender ? 0 : 1)];
    }
}

function From0To999(fValue, oObjDesc, fnAddNum, fnAddDesc)
{
    var nCurrState = 2;
    if (Math.floor(fValue/100) > 0) {
        var fCurr = Math.floor(fValue/100)*100;
        fnAddNum(Value(fCurr, oObjDesc._Gender));
        nCurrState = mapNumbers[fCurr][0];
        fValue -= fCurr;
    }

    if (fValue < 20) {
        if (Math.floor(fValue) > 0) {
            fnAddNum(Value(fValue, oObjDesc._Gender));
            nCurrState = mapNumbers[fValue][0];
        }
    } else {
        var fCurr = Math.floor(fValue/10)*10;
        fnAddNum(Value(fCurr, oObjDesc._Gender));
        nCurrState = mapNumbers[fCurr][0];
        fValue -= fCurr;

        if (Math.floor(fValue) > 0) {
            fnAddNum(Value(fValue, oObjDesc._Gender));
            nCurrState = mapNumbers[fValue][0];
        }
    }

    fnAddDesc(oObjDesc._arrStates[nCurrState]);
}

function FloatToSamplesInWordsRus(fAmount)
{
    var fInt = Math.floor(fAmount + 0.005);
    var fDec = Math.floor(((fAmount - fInt) * 100) + 0.5);

    var arrRet = [];
    var iOrder = 0;
    var arrThousands = [];
    for (; fInt > 0.9999; fInt/=1000) {
        arrThousands.push(Math.floor(fInt % 1000));
    }
    if (arrThousands.length == 0) {
        arrThousands.push(0);
    }

    function PushToRes(strVal) {
        arrRet.push(strVal);
    }

    for (var iSouth = arrThousands.length-1; iSouth >= 0; --iSouth) {
        if (arrThousands[iSouth] == 0) {
            continue;
        }
        From0To999(arrThousands[iSouth], mapOrders[iSouth], PushToRes, PushToRes);
    }

    if (arrThousands[0] == 0) {
        //  Handle zero amount
        if (arrThousands.length == 1) {
            PushToRes(Value(0, mapOrders[0]._Gender));
        }

        var nCurrState = 2;
        PushToRes(mapOrders[0]._arrStates[nCurrState]);
    }

    if (arrRet.length > 0) {
        // Capitalize first letter
        arrRet[0] = arrRet[0].match(/^(.)/)[1].toLocaleUpperCase() + arrRet[0].match(/^.(.*)$/)[1];
    }

    //arrRet.push((fDec < 10) ? ("0" + fDec) : ("" + fDec));
    //From0To999(fDec, objKop, function() {}, PushToRes);

    return arrRet.join(" ");
}

$(document).ready(function(){
$('#customerPriceInput').on('change keyup input click', function(){
	if ($("#customerPriceInput").val() !='') {
		$("#sumForCustomerInput").val(FloatToSamplesInWordsRus(parseFloat($("#customerPriceInput").val())));
	}
});
$('#carrierPriceInput').on('change keyup input click', function(){
	if ($("#carrierPriceInput").val() !='') {
		$("#sumForCarrierInput").val(FloatToSamplesInWordsRus(parseFloat($("#carrierPriceInput").val())));
	}
});
 });

</script>
<div class="statusMsg"></div>