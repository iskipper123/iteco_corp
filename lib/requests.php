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
		</div>
		<div class="form-group col-md-4 mr-4">
			<label for="dateInput2" class="required">Дата погрузки</label>
			<input class="form-control<?=isset($error_dateShipping)&&$error_dateShipping!=''?' is-invalid':''?> datepicker-here" data-auto-close="true" type="text" name="dateShipping" id="dateInput2" placeholder="дд.мм.гггг" autocomplete="off" value="<?=isset($_POST["dateShipping"])? $_POST["dateShipping"]:$dateShipping?>">
    	</div>
		<div class="form-group col-md-4 mr-4">
			<label for="timeInput" class="required">Время погрузки</label>
			<input class="form-control<?=isset($error_time)&&$error_time!=''?' is-invalid':''?>" type="text" name="time" id="timeInput" autocomplete="off" value="<?=isset($_POST["time"])? $_POST["time"]:$row["time"]?>">
    	</div>
		<div class="form-group col-md-4 mr-4">
			<label for="termInput" class="required">Срок доставки</label>
			<input class="form-control<?=isset($error_term)&&$error_term!=''?' is-invalid':''?>" type="text" name="term" id="termInput" autocomplete="off" value="<?=isset($_POST["term"])? $_POST["term"]:$row["term"]?>">
		</div>	
		<div class="form-group col-md-4 mr-4">
			<label for="tag" class="required">Выберите заказчика</label>
			<input class="form-control<?=isset($error_customer)&&$error_customer!=''?' is-invalid':''?>" type="text" name="customer" id="tag" autocomplete="off" value="<?=isset($_POST["customer"])? $_POST["customer"]:$customer?>">
            <div class="invalid-feedback2"></div>
        </div>
		<div class="form-group col-md-4 mr-4">
            <label for="tag1" class="required">Выберите перевозчика</label>
            <input class="form-control<?= isset($error_carrier) && $error_carrier != '' ? ' is-invalid' : '' ?>" type="text" name="carrier" id="tag1" autocomplete="off" value="<?= isset($_POST["carrier"]) ? $_POST["carrier"] : $carrier ?>">
            <div class="invalid-feedback3"></div>
        </div>
		<div class="form-group col-md-4 mr-4">
			<label for="carNumberInput" class="required">Номер автомобиля</label>
			<input class="form-control<?=isset($error_carNumber)&&$error_carNumber!=''?' is-invalid':''?>" type="text" name="carNumber" id="carNumberInput" autocomplete="off" value="<?=isset($_POST["carNumber"])? $_POST["carNumber"]:$row[carNumber]?>">
		</div>
		<div class="form-group col-md-4 mr-4">
			<label for="fioInput" class="required">Водитель ФИО</label>
			<input class="form-control<?=isset($error_fio)&&$error_fio!=''?' is-invalid':''?>" type="text" name="fio" id="fioInput" autocomplete="off" value="<?=isset($_POST["fio"])? $_POST["fio"]:$row[fio]?>">
		</div>
		<div class="form-group col-md-4 mr-4">
			<label for="driverPhonesInput" class="required">Контактные телефоны водителя</label>
			<textarea class="form-control" name="driverPhones" id="driverPhonesInput" ></textarea>
		</div>
		<div class="form-group col-md-4 mr-4">
			<label for="weightInput" class="required">Вес</label>
			<input class="form-control" type="text" name="weightInput" id="weightInput" autocomplete="off" value="">
            <select class="form-control" name="weight_var" id="weight_var">
                <option selected="selected" value="">Selectează măsura</option>
                <? for($i = 0; $i < count($arrayOfweight); $i++) {
                    ?>
						<option><?=$arrayOfweight[$i] ?></option>
					<?}?>
            </select>
		</div>
        
		<div class="form-group col-md-4 mr-4">
			<label for="vInput" class="required">Объем</label>
			<input class="form-control" type="text" name="vInput" id="vInput" autocomplete="off" value="">
            <select class="form-control" name="vInput_var" id="vInput_var">
                <option selected="selected" value="">Selectează măsura</option>
                <? for($i = 0; $i < count($arrayOfv); $i++) {
                    ?>
						<option><?=$arrayOfv[$i] ?></option>
					<?}?>
            </select>
		</div>

		<div class="form-group col-md-4 mr-4">
			<label for="categoryInput" class="required">Тип транспорта</label>
			<select class="form-control" name="categoryInput" id="categoryInput">
				<option selected="selected" value="">Selecteaza transportul</option>
				<? for($i = 0; $i < count($arrayOfTransportType); $i++) {
                    ?>
						<option><?=$arrayOfTransportType[$i] ?></option>
					<?}?>
				
			</select>
		</div>
		<div class="form-group col-md-4 mr-4">
			<label for="fromInput" class="required">Откуда</label>
			<input class="form-control" type="text" name="from" id="fromInput" autocomplete="off" value="">
		</div>
		<div class="form-group col-md-4 mr-4">
			<label for="toInput" class="required">Куда</label>
			<input class="form-control" type="text" name="to" id="toInput" autocomplete="off" value="">
		</div>
		<div class="form-group col-md-4 mr-4">
			<label for="routeInput" class="required">Маршрут следования</label>
			<textarea class="form-control" name="route" id="routeInput"></textarea>
		</div>
		<div class="form-group col-md-4 mr-4">
			<label for="pogranInput" class="required">Погран - Переходы</label>
            <textarea class="form-control" name="pogran" id="pogranInput"></textarea>
        </div>
		<div class="form-group col-md-4 mr-4">
			<label for="brokerInput" class="required">Декларант (Broker)</label>
			<textarea class="form-control" name="broker" id="brokerInput" ></textarea>
		</div>		
		<div class="form-group col-md-4 mr-4">
			<label for="address1Input" class="required">Адрес загрузки</label>
			<textarea class="form-control" name="address1" id="address1Input" ></textarea>
		</div>
		<div class="form-group col-md-4 mr-4">
			<label for="address2Input" class="required">Адрес разгрузки</label>
			<textarea class="form-control" name="address2" id="address2Input" ></textarea>
		</div>
		<div class="form-group col-md-4 mr-4">
			<label for="contactName1Input">Контактное лицо на загрузке</label>
			<textarea class="form-control" name="contactName1" id="contactName1Input" ></textarea>
		</div>
		<div class="form-group col-md-4 mr-4">
			<label for="contactName2Input" class="required">Контактное лицо в РМ</label>
			<textarea class="form-control" name="contactName2" id="contactName2Input" ></textarea>
		</div>		

		<div class="form-group col-md-4 mr-4">
			<label for="customs1Input" class="required">Таможня импорта</label>
			<textarea class="form-control" name="customs1" id="customs1Input" ></textarea>
		</div>
		<div class="form-group col-md-4 mr-4">
			<label for="customs2Input" class="required">Таможня экспорта</label>
			<textarea class="form-control" name="customs2" id="customs2Input" ></textarea>
		</div>
		<div class="form-group col-md-4 mr-4">
			<label for="temperatureInput" class="required">Температурный режим</label>
				<input class="form-control" type="text" name="temperature" id="temperatureInput" autocomplete="off" value="">
		</div>		
		<div class="form-group col-md-4 mr-4">
			<label for="customerPriceInput" class="required">Заказчик сумма</label>
			<input class="form-control" type="text" name="customerPrice" id="customerPriceInput" autocomplete="off" value="" >
		</div>
		<div class="form-group col-md-4 mr-4">
			<label for="carrierPriceInput" class="required">Перевозчик сумма</label>
			<input class="form-control" type="text" name="carrierPrice" id="carrierPriceInput" autocomplete="off" value="" >
		</div>
		<!-- <div class="form-group col-md-4 mr-4" style="display: none;">
			<label for="sumForCustomerInput" class="required">Заказчик сумма(прописью)</label>
			<input class="form-control" type="text" name="sumForCustomer" id="sumForCustomerInput" autocomplete="off" value="">
		</div>
		<div class="form-group col-md-4 mr-4" style="display: none;">
			<label for="sumForCarrierInput" class="required">Перевозчик сумма(прописью)</label>
			<input class="form-control" type="text" name="sumForCarrier" id="sumForCarrierInput" autocomplete="off" value="">
		</div> -->
				<div class="form-group col-md-4 mr-4">
			<label for="pay_variant" class="required">Вариант оплаты</label>
			<select class="form-control" name="payMetod" id="pay_variant">
           <option selected="selected" name="payMetod" id="pay_variant" value=""> Selectați varianta de plată </option>
            <? for($i = 0; $i < count($arrayOfpay_variant); $i++) {
                            ?>
                                <option><?=$arrayOfpay_variant[$i] ?></option>
                            <?}?>
                         
                            </select>
		</div>

		<div class="form-group col-md-4 mr-4">
			<label for="languages" class="required">Язык документов</label>
                <select class="form-control" name="languages" id="languages" >
                    <option selected="selected" name="languages" id="languages" value=""> Selectează limba </option>
                        <? for($i = 0; $i < count($arrayOflanguages); $i++) {
                        if($row["avans_summ"] != $arrayOflanguages[$i]) { ?>
                        <option><?=$arrayOflanguages[$i] ?></option>
                        <?}?>
                    <?}?>
                </select>
		</div>		
			<div class="form-group col-md-4 mr-4" <? if($_SESSION["role"] != 1) {?>style="display: none;"<?php } ?>>
				<label for="idUserInput" class="required">Выберите сотрудника</label>
				<select class="form-control" name="idUser" id="idUserInput">
					 <? if($_SESSION["role"] != 1) {?><option name="idUserInput" id="idUserInput" value="<?php echo $_SESSION["id"]; ?>" selected="selected"><?php echo $_SESSION["login"]; ?></option><?php } else { ?><option selected="selected"></option><?php } ?>
					<? while (($row2 = $result_set2->fetch_assoc()) != false) { ?>
						<?  if($row2[id] == $idUser) {?>
								<option selected="selected" value="<?=$row2[id]?>"><?=$row2[name]?></option>
						<?}
						else {?>
							<option value="<?=$row2[id]?>"><?=$row2[name]?></option>
						<?}?>
					<?}?>
				</select>
			</div>
		<div class="form-group col-md-4 mr-4">
			<label for="currencyInput" class="required">Валюта</label>
			<select class="form-control" name="currency" id="currencyInput">
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
		</div>
		<div class="form-group col-md-4 mr-4">
			<label for="cargoInput" class="required">Груз</label>
			<input class="form-control" type="text" name="cargo" id="cargoInput" autocomplete="off" value="<?=isset($_POST["cargo"])? $_POST["cargo"]:$row[cargo]?>">
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
        
        <div class="form-group col-md-4 mr-4" <? if($_SESSION["role"] != 1) {?>style="display: none;"<?php } ?>>
				<label for="idUserInput2" class="required">Выберите сотрудника</label>
				<select class="form-control" name="idUser2" id="idUserInput2">
                <?php if ($row[partener] != '') {?> <option value="<?php echo $row[partener]; ?>" selected="selected"><?php echo $row5[name]; ?></option>
						<?php } else { ?>
							<option selected="selected" value="">Выберите сотрудника</option>
					<?php } ?>

					<? while (($row3 = $result_set3->fetch_assoc()) != false) { ?>
						<?php if ($row3[manager_variant] == 'transport' || $row3[manager_variant] == 'all' ) { ?>
							<option value="<?=$row3[id]?>"><?=$row3[name]?></option> 
						<?php } ?>
					<?}?>
				</select>
			</div>

 				<div class="form-group col-md-4 mr-4">
				<label for="CustomsInput" class="required">Таможня</label>
					<select class="form-control" name="customs" id="CustomsInput">
					<?php if ($row[customs] != '') {?> <option value="<?php echo $row[customs]; ?>" selected="selected">

						<?php if($row[customs]=='import'){echo 'Импорта';} elseif($row[customs]=='export') {echo 'Экспорта';} ?></option>
						<?php } else { ?>
							<option value="" selected="selected"></option>
					<?php } ?>

							<option value="import">Импорта</option> 
							<option value="export">Экспорта</option> 
				</select>
			</div>
		
	</div>  
	<!-- Butonul de validare -->
<button id="validateButton" type="button">Сохранить</button>
<div id="errorMessages"></div>
<style>

</style>
</form>
<script type="text/javascript">
(function($) {
    $(document).ready(function() {
        // Declara o variabilă pentru a urmări dacă 'tag' este în blacklist
        var tagInBlacklist = false;
        // Declara o variabilă pentru a urmări dacă 'tag1' este în blacklist
        var tag1InBlacklist = false;

        $('#tag').change(function() {
            var name = $(this).val();

            // Resetăm mesajul de eroare pentru #tag
            $('#tag').removeClass('is-invalid');
            $('.invalid-feedback2').html('');

            // Trimite cererea AJAX pentru verificare în blacklist
            $.ajax({
                type: 'POST',
                url: 'check_blacklist.php',
                data: { name: name },
                success: function(response) {
                    if (response === 'in_blacklist') {
                        tagInBlacklist = true;
                        $('#tag').addClass('is-invalid');
                        $('.invalid-feedback2').html('<span style="color: red;">Имя в черном списке. Внесение в базу данных не допускается..</span>');
                    } else {
                        tagInBlacklist = false;
                    }

                    // Verificăm ambele condiții pentru a dezactiva butonul
                    if (tagInBlacklist || tag1InBlacklist) {
                        $('#validateButton').prop('disabled', true);
                    } else {
                        $('#validateButton').prop('disabled', false);
                    }
                },
                error: function() {
                    $('#tag').addClass('is-invalid');
                    $('.invalid-feedback2').html('A apărut o eroare în timpul verificării.');
                }
            });
        });

        $('#tag1').change(function() {
            var name = $(this).val();

            // Resetăm mesajul de eroare pentru #tag1
            $('#tag1').removeClass('is-invalid');
            $('.invalid-feedback3').html('');

            // Trimite cererea AJAX pentru verificare în blacklist pentru 'tag1'
            $.ajax({
                type: 'POST',
                url: 'check_blacklist1.php',
                data: { name: name },
                success: function(response) {
                    if (response === 'in_blacklist') {
                        tag1InBlacklist = true;
                        $('#tag1').addClass('is-invalid');
                        $('.invalid-feedback3').html('<span style="color: red;">Имя в черном списке. Внесение в базу данных не допускается..</span>');
                    } else {
                        tag1InBlacklist = false;
                    }

                    // Verificăm ambele condiții pentru a dezactiva butonul
                    if (tagInBlacklist || tag1InBlacklist) {
                        $('#validateButton').prop('disabled', true);
                    } else {
                        $('#validateButton').prop('disabled', false);
                    }
                },
                error: function() {
                    $('#tag1').addClass('is-invalid');
                    $('.invalid-feedback3').html('A apărut o eroare în timpul verificării pentru "tag1".');
                }
            });
        });
    });
})(jQuery);
</script>



<script type="text/javascript">
(function($) {
    $(document).ready(function() {
        $('#validateButton').click(function() {
            var isValid = true;
            var errorMessages = [];

        // Validarea fiecărui câmp individual
        if ($('#dateInput').val() === '') {
            isValid = false;
            errorMessages.push('Vă rugăm să completați data.');
            $('#dateInput').addClass('is-invalid');
        } else {
            $('#dateInput').removeClass('is-invalid');
        }

        if ($('#dateInput2').val() === '') {
            isValid = false;
            errorMessages.push('Vă rugăm să completați data de până.');
            $('#dateInput2').addClass('is-invalid');
        } else {
            $('#dateInput2').removeClass('is-invalid');
        }
		if ($('#timeInput').val() === '') {
            isValid = false;
            errorMessages.push('Vă rugăm să completați ora.');
            $('#timeInput').addClass('is-invalid');
        } else {
            $('#timeInput').removeClass('is-invalid');
        }
        
        if ($('#termInput').val() === '') {
            isValid = false;
            errorMessages.push('Vă rugăm să completați termenul de livrare.');
            $('#termInput').addClass('is-invalid');
        } else {
            $('#termInput').removeClass('is-invalid');
        }

        if ($('#tag').val() === '') {
            isValid = false;
            errorMessages.push('Vă rugăm să selectați un client.');
            $('#tag').addClass('is-invalid');
        } else {
            $('#tag').removeClass('is-invalid');
        }

        if ($('#tag1').val() === '') {
            isValid = false;
            errorMessages.push('Vă rugăm să selectați un transportator.');
            $('#tag1').addClass('is-invalid');
        } else {
            $('#tag1').removeClass('is-invalid');
        }

        if ($('#carNumberInput').val() === '') {
            isValid = false;
            errorMessages.push('Vă rugăm să completați numărul mașinii.');
            $('#carNumberInput').addClass('is-invalid');
        } else {
            $('#carNumberInput').removeClass('is-invalid');
        }

		if ($('#fioInput').val() === '') {
            isValid = false;
            errorMessages.push('Vă rugăm să completați numele șoferului.');
            $('#fioInput').addClass('is-invalid');
        } else {
            $('#fioInput').removeClass('is-invalid');
        }

        if ($('#driverPhonesInput').val() === '') {
            isValid = false;
            errorMessages.push('Vă rugăm să completați datele de contact a șoferului.');
            $('#driverPhonesInput').addClass('is-invalid');
        } else {
            $('#driverPhonesInput').removeClass('is-invalid');
        }
		
        if ($('#weightInput').val() === '') {
            isValid = false;
            errorMessages.push('Vă rugăm să completați cantitatea.');
            $('#weightInput').addClass('is-invalid');
        } else {
            $('#weightInput').removeClass('is-invalid');
        }

        
		if ($('#weight_var').val() === '') {
            isValid = false;
            errorMessages.push('Vă rugăm să selectați cantitatea.');
            $('#weight_var').addClass('is-invalid');
        } else {
            $('#weight_var').removeClass('is-invalid');
        }

        	if ($('#vInput').val() === '') {
            isValid = false;
            errorMessages.push('Vă rugăm să completați volumul.');
            $('#vInput').addClass('is-invalid');
        } else {
            $('#vInput').removeClass('is-invalid');
        }

        if ($('#vInput_var').val() === '') {
            isValid = false;
            errorMessages.push('Vă rugăm să completați tipul volumului.');
            $('#vInput_var').addClass('is-invalid');
        } else {
            $('#vInput_var').removeClass('is-invalid');
        }

		if ($('#categoryInput').val() === '') {
            isValid = false;
            errorMessages.push('Vă rugăm să selectați tipul transportului.');
            $('#categoryInput').addClass('is-invalid');
        } else {
            $('#categoryInput').removeClass('is-invalid');
        }

		if ($('#fromInput').val() === '') {
            isValid = false;
            errorMessages.push('Vă rugăm să completați de unde.');
            $('#fromInput').addClass('is-invalid');
        } else {
            $('#fromInput').removeClass('is-invalid');
        }
		
		if ($('#toInput').val() === '') {
            isValid = false;
            errorMessages.push('Vă rugăm să completați unde.');
            $('#toInput').addClass('is-invalid');
        } else {
            $('#toInput').removeClass('is-invalid');
        }
		
		if ($('#routeInput').val() === '') {
            isValid = false;
            errorMessages.push('Vă rugăm să completați itinerariul.');
            $('#routeInput').addClass('is-invalid');
        } else {
            $('#routeInput').removeClass('is-invalid');
        }

		if ($('#pogranInput').val() === '') {
            isValid = false;
            errorMessages.push('Vă rugăm să completați Frontieră - Treceri.');
            $('#pogranInput').addClass('is-invalid');
        } else {
            $('#pogranInput').removeClass('is-invalid');
        }
	
		if ($('#brokerInput').val() === '') {
            isValid = false;
            errorMessages.push('Vă rugăm să completați declarant (Broker).');
            $('#brokerInput').addClass('is-invalid');
        } else {
            $('#brokerInput').removeClass('is-invalid');
        }
		
		if ($('#address1Input').val() === '') {
            isValid = false;
            errorMessages.push('Vă rugăm să completați adresa de încărcare.');
            $('#address1Input').addClass('is-invalid');
        } else {
            $('#address1Input').removeClass('is-invalid');
        }

		if ($('#address2Input').val() === '') {
            isValid = false;
            errorMessages.push('Vă rugăm să completați adresa de descărcare.');
            $('#address2Input').addClass('is-invalid');
        } else {
            $('#address2Input').removeClass('is-invalid');
        }

		if ($('#contactName1Input').val() === '') {
            isValid = false;
            errorMessages.push('Vă rugăm să completați persoana de contact pentru descărcare.');
            $('#contactName1Input').addClass('is-invalid');
        } else {
            $('#contactName1Input').removeClass('is-invalid');
        }

		if ($('#contactName2Input').val() === '') {
            isValid = false;
            errorMessages.push('Vă rugăm să completați persoana de contact în Republica Moldova.');
            $('#contactName2Input').addClass('is-invalid');
        } else {
            $('#contactName2Input').removeClass('is-invalid');
        }

		if ($('#customs1Input').val() === '') {
            isValid = false;
            errorMessages.push('Vă rugăm să completați vama de import.');
            $('#customs1Input').addClass('is-invalid');
        } else {
            $('#customs1Input').removeClass('is-invalid');
        }

		if ($('#customs2Input').val() === '') {
            isValid = false;
            errorMessages.push('Vă rugăm să completați vama de export.');
            $('#customs2Input').addClass('is-invalid');
        } else {
            $('#customs2Input').removeClass('is-invalid');
        }
		
		if ($('#temperatureInput').val() === '') {
            isValid = false;
            errorMessages.push('Vă rugăm să completați regimul de temperatură.');
            $('#temperatureInput').addClass('is-invalid');
        } else {
            $('#temperatureInput').removeClass('is-invalid');
        }

		if ($('#customerPriceInput').val() === '') {
            isValid = false;
            errorMessages.push('Vă rugăm să completați suma clientului.');
            $('#customerPriceInput').addClass('is-invalid');
        } else {
            $('#customerPriceInput').removeClass('is-invalid');
        }
		

		if ($('#carrierPriceInput').val() === '') {
            isValid = false;
            errorMessages.push('Vă rugăm să completați suma transportatorului.');
            $('#carrierPriceInput').addClass('is-invalid');
        } else {
            $('#carrierPriceInput').removeClass('is-invalid');
        }
		
		
		if ($('#pay_variant').val() === '') {
            isValid = false;
            errorMessages.push('Vă rugăm să selectați tipul de plata.');
            $('#pay_variant').addClass('is-invalid');
        } else {
            $('#pay_variant').removeClass('is-invalid');
        }

		if ($('#languages').val() === '') {
            isValid = false;
            errorMessages.push('Vă rugăm să selectați limbajul documentului.');
            $('#languages').addClass('is-invalid');
        } else {
            $('#languages').removeClass('is-invalid');
        }
		
		if ($('#idUserInput').val() === '') {
            isValid = false;
            errorMessages.push('Vă rugăm să selectați alegeți un angajat.');
            $('#idUserInput').addClass('is-invalid');
        } else {
            $('#idUserInput').removeClass('is-invalid');
        }

		if ($('#currencyInput').val() === '') {
            isValid = false;
            errorMessages.push('Vă rugăm să selectați moneda.');
            $('#currencyInput').addClass('is-invalid');
        } else {
            $('#currencyInput').removeClass('is-invalid');
        }

		if ($('#cargoInput').val() === '') {
            isValid = false;
            errorMessages.push('Vă rugăm să completați ce marfă.');
            $('#cargoInput').addClass('is-invalid');
        } else {
            $('#cargoInput').removeClass('is-invalid');
        }

		if ($('#comision_static').val() === '') {
            isValid = false;
            errorMessages.push('Vă rugăm să selectați comision.');
            $('#comision_static').addClass('is-invalid');
        } else {
            $('#comision_static').removeClass('is-invalid');
        }
		
        if ($('#idUserInput2').val() === '') {
            isValid = false;
            errorMessages.push('Vă rugăm să selectați persoana responsabilă.');
            $('#idUserInput2').addClass('is-invalid');
        } else {
            $('#idUserInput2').removeClass('is-invalid');
        }

        if ($('#CustomsInput').val() === '') {
            isValid = false;
            errorMessages.push('Vă rugăm să selectați tipul transportării.');
            $('#CustomsInput').addClass('is-invalid');
        } else {
            $('#CustomsInput').removeClass('is-invalid');
        }
        

        if (!isValid) {
            var errorMessageHTML = '<ul>';
            for (var i = 0; i < errorMessages.length; i++) {
                errorMessageHTML += '<li>' + errorMessages[i] + '</li>';
            }
            errorMessageHTML += '</ul>';
            $('#errorMessages').html(errorMessageHTML);
            $('#errorMessages').show();
        } else {
            $('#errorMessages').hide();

            // Trimite formularul prin Ajax
            $.ajax({
                type: 'POST',
                url: 'addRequest_ajax.php',
                data: {
                    // Aici puteți adăuga câmpurile și valorile corespunzătoare din formular
                    dateInput: $('#dateInput').val(),
                    dateInput2: $('#dateInput2').val(),
                    timeInput: $('#timeInput').val(),
                    termInput: $('#termInput').val(),
                    tag: $('#tag').val(),
                    tag1: $('#tag1').val(),
                    carNumberInput: $('#carNumberInput').val(),
                    fioInput: $('#fioInput').val(),
                    driverPhonesInput: $('#driverPhonesInput').val(),
                    weightInput: $('#weightInput').val(),
                    weight_var: $('#weight_var').val(),
                    vInput: $('#vInput').val(),
                    vInput_var : $('#vInput_var').val(),
                    categoryInput: $('#categoryInput').val(),
                    fromInput: $('#fromInput').val(),
					toInput: $('#toInput').val(),
					routeInput: $('#routeInput').val(),
					pogranInput: $('#pogranInput').val(),
					brokerInput: $('#brokerInput').val(),
					address1Input : $('#address1Input').val(),
					address2Input : $('#address2Input').val(),
					contactName1Input : $('#contactName1Input').val(),
					contactName2Input : $('#contactName2Input').val(),
					customs1Input : $('#customs1Input').val(),
					customs2Input : $('#customs2Input').val(),
					temperatureInput : $('#temperatureInput').val(),
					customerPriceInput : $('#customerPriceInput').val(),
                    carrierPriceInput : $('#carrierPriceInput').val(),
					pay_variant : $('#pay_variant').val(),
                    languages : $('#languages').val(),
                    idUserInput : $('#idUserInput').val(),
					currencyInput : $('#currencyInput').val(),
					cargoInput : $('#cargoInput').val(),
					comision_static : $('#comision_static').val(),
                    idUserInput2 : $('#idUserInput2').val(),
                    CustomsInput : $('#CustomsInput').val()
					
                },
                success: function(response) {
                    // Aici puteți gestiona răspunsul primit de la server după trimitere
                    alert(response);
                },
                error: function() {
                    // Adăugăm clasa 'is-invalid' pentru a evidenția mesajul de eroare în roșu
                    $('#errorMessages').addClass('is-invalid');
                    alert('A apărut o eroare în timpul trimiterii.');
                }
            });
        }
     });
    });

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
})(jQuery);
</script>


