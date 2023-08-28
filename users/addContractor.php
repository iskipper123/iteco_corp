<?php

	require_once "../lib/db.php";
	require_once "../lib/vars.php";

	$db = DB::getObject();
	$contractorsType = $_SESSION["contractorType"];
	
	$title = 'Добавить '.$contractorsType.'а';
	
	if(isset($_POST["add"])) {
		$name = $_POST["name"];
		$company_form = $_POST["company_form"];
		$contactName = $_POST["contactName"];
		$phone = $_POST["phone"];
		$email = $_POST["email"];
		$country = $_POST["country"];
		$city = $_POST["city"];
		$bankDetails = $_POST["bankDetails"];
		$headName = $_POST["headName"];
		$carsAmount = $_POST["carsAmount"];
		$directions = $_POST["directions"];
		$comments = $_POST["comments"];
		$countryTags = $_POST["countryTags"]; //массив

		$error_contractorsType = "";
		$error_name = "";
		$error_contactName = "";
		$error_phone = "";
		$error_country = "";
		$error_city = "";
		$error = false;

		if(strlen($name) == 0) {
			$error_name = "Не заполнено поле";
			$error = true;
		}
		if(strlen($company_form) == 0) {
			$error_name = "Не заполнено поле";
			$error = true;
		}
		if(strlen($contactName) == 0) {
			$error_contactName = "Не заполнено поле";
			$error = true;
		}
		if(strlen($phone) == 0) {
			$error_phone = "Не заполнено поле";
			$error = true;
		}
		if(strlen($country) == 0) {
			$error_country = "Не заполнено поле";
			$error = true;
		}
		if(strlen($city) == 0) {
			$error_city = "Не заполнено поле";
			$error = true;
		}
		
		if(!$error) {
			$name = htmlspecialchars($name, ENT_QUOTES);
			$bankDetails = htmlspecialchars($bankDetails, ENT_QUOTES);
			
			$db->addContractor($contractorsType, $name, $company_form, $contactName, $phone, $email, $country, $city, $bankDetails, $headName, $carsAmount, $directions, $comments);
			
			$lastID = $db->getLastID("contractors");

			for($i = 0; $i < count($countryTags); $i++) {
				$db->addTagToContractor($lastID, $countryTags[$i]);
			}
			
			$db->addLog($lastID, time(), "Контрагент создан", 0, 0, $_SESSION["id"]);
			
			if($contractorsType == $arrayOfContractorsTypes[0]) {
				header("Location: customers.php?success");
				exit;
			}
			else {
				header("Location: carriers.php?success");
				exit;
			}
		}
	}
	else if(isset($_POST["cancel"])) {
		if($contractorsType == $arrayOfContractorsTypes[0]) {
			header("Location: customers.php");
			exit;
		}
		else {
			header("Location: carriers.php");
			exit;
		}	
	}
    require_once '../partsOfPages/head.php';
    require_once "../partsOfPages/menuForUser.php"; ?>
    <div class="col-md-12 pt-3">
        <form name="" action="" method="post">
            <input class="btn btn-danger btn-sm" type="submit" name="cancel" value="Вернуться назад"> <br /> <br />
            <div class="row">
                <div class="form-group col-md-4 mr-4">
                    <label for="nameInput" class="required">Название фирмы</label>
                    <input class="form-control<?=isset($error_name)&&$error_name!=''?' is-invalid':''?>" type="text" name="name" id="nameInput" value="<?=$_POST["name"]?>">
                    <div class="invalid-feedback" <?=isset($error_name)&&$error_name!=''?'style="display:block;"':''?>><?=$error_name ?></div>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="nameInput" class="required">Форма собственности</label>
                     <select class="form-control" name="company_form" id="company_form">
                        <? if($row[company_form] == "") { ?>
                            <option selected="selected"></option>
                        <?}
                        else { ?>
                            <option selected="selected"> <?=$row[company_form] ?> </option>
                        <?}?>
                        <? for($i = 0; $i < count($arrayOfcompany_form); $i++) {
                            if($row[company_form] != $arrayOfcompany_form[$i]) { ?>
                                <option><?=$arrayOfcompany_form[$i] ?></option>
                            <?}?>
                        <?}?>
                    </select>
                    <div class="invalid-feedback" <?=isset($error_name)&&$error_name!=''?'style="display:block;"':''?>><?=$error_name ?></div>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="contactNameInput" class="required">Контактное лицо в фирме</label>
                    <input class="form-control<?=isset($error_contactName)&&$error_contactName!=''?' is-invalid':''?>" type="text" name="contactName" id="contactNameInput" value="<?=$_POST["contactName"]?>">
                    <div class="invalid-feedback" <?=isset($error_contactName)&&$error_contactName!=''?'style="display:block;"':''?>><?=$error_contactName ?></div>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="contactphoneInput" class="required">Контактные телефоны</label>
                    <textarea class="form-control<?=isset($error_phone)&&$error_phone!=''?' is-invalid':''?>" name="phone" id="contactphoneInput" ><?=$phone?></textarea>
                    <div class="invalid-feedback" <?=isset($error_phone)&&$error_phone!=''?'style="display:block;"':''?>><?=$error_phone ?></div>
                </div>
                <div class="form-group col-md-4 mr-4"> 
                    <label for="emailInput">E-mail</label>
                    <input class="form-control" type="text" name="email" id="emailInput" value="<?=$_POST["email"]?>">
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="countryInput" class="required">Страна</label>
                    <input class="form-control<?=isset($error_country)&&$error_country!=''?' is-invalid':''?>" type="text" name="country" id="countryInput" value="<?=$_POST["country"]?>">
                    <div class="invalid-feedback" <?=isset($error_country)&&$error_country!=''?'style="display:block;"':''?>><?=$error_country ?></div>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="cityInput" class="required">Город</label>
                    <input class="form-control<?=isset($error_city)&&$error_city!=''?' is-invalid':''?>" type="text" name="city" id="cityInput" value="<?=$_POST["city"]?>">
                    <div class="invalid-feedback" <?=isset($error_city)&&$error_city!=''?'style="display:block;"':''?>><?=$error_city ?></div>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="bankDetailsInput">Банковские реквизиты</label>
                    <textarea class="form-control" name="bankDetails" id="bankDetailsInput" ><?=$bankDetails?></textarea>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="headNameInput">ФИО директора</label>
                    <input class="form-control" type="text" name="headName" id="headNameInput" value="<?=$_POST["headName"]?>">
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="carsAmountInput">Количество автомобилей на фирме (если это перевозчик)</label>
                    <input class="form-control" type="text" name="carsAmount" id="carsAmountInput" value="<?=$_POST["carsAmount"]?>">
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="directionsInput">Направления по которым работают/Направления по которым ввозят</label>
                    <textarea class="form-control" name="directions" id="directionsInput" ><?=$directions?></textarea>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="commentsInput">Комментарии</label>
                    <textarea class="form-control" name="comments" id="commentsInput" ><?=$comments?></textarea>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <? for($i = 0; $i < count($arrayOfTagsForCountry); $i++) {?>
                        <? if(isset($_POST["countryTags"]) && in_array($arrayOfTagsForCountry[$i], $_POST["countryTags"])) {?>
                            <input type="checkbox" checked name="countryTags[]" value="<?=$arrayOfTagsForCountry[$i]?>"> <b><?=$arrayOfTagsForCountry[$i]?></b> <br />
                        <?} else {?>
                            <input type="checkbox" name="countryTags[]" value="<?=$arrayOfTagsForCountry[$i]?>"> <b><?=$arrayOfTagsForCountry[$i]?></b> <br />
                        <?}?>
                    <?}?>
                </div>
            </div>
            <input class="btn btn-secondary btn-sm" type="submit" name="add" value="Добавить">
            <input class="btn btn-danger btn-sm" type="submit" name="cancel" value="Отменить">
        </form>
          <script defer>
        function autocompleteTag3(){
            var ajax = new XMLHttpRequest();
            ajax.open("GET", "../lib/autocomplete2.php", true);
            ajax.onload = function () {
                var list = JSON.parse(ajax.responseText);
                new Awesomplete(document.querySelector("#nameInput"), {minChars: 1, list: list}); 
            };
            ajax.send();
        }
        document.getElementById('nameInput').addEventListener("awesomplete-select", function(event) {
            let extra = event.text.extraData;
            $('#contactNameInput').val(extra.contactName);
            $('#phoneInput').val(extra.phone);
            $('#countryInput').val(extra.country);
            $('#company_form').val(extra.company_form); 


        });
        $(function(){
            autocompleteTag3();

            $('#directionsInput').tagsInput({'delimiter': [';'],'defaultText':'теги'});
        });
    </script>
    </div>
<?  require_once '../partsOfPages/footer.php';?>