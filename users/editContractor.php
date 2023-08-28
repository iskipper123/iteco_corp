<?php
	session_start();
	require_once "../lib/checkWasUserLoginedAndIsAdmin.php";
	require_once "../lib/db.php";
	require_once "../lib/vars.php";
	
	$db = DB::getObject();
	$contractorsType = $_SESSION["contractorType"];
    $title = 'Редактирование данных'.$contractorsType.'а';

    $result_set_users = $db->getAllUsersForAdmin();
    $row_users = $result_set_users->fetch_assoc();

        $result_set3 = $db->getUserByID($row[idManager]); // get manager name
        $row5 = $result_set3->fetch_assoc();

	if(isset($_GET["edit"])) {
		$id = $_GET["edit"];
				
		$result_set = $db->getRowWhere("contractors", "id", $id);
		$row = $result_set->fetch_assoc();
	} 
	
	if(isset($_POST["edit"])) {
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
		$cod_fiscal = $_POST["cod_fiscal"]; 

		$contractorsType = $_POST["contractorsType"]; 
		$company_import_export = $_POST["company_import_export"]; 
		$company_season = $_POST["company_season"]; 

		
		$idUser = $_POST["idUser"];
		
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
  		if(strlen($cod_fiscal) == 0) {
            $error_cod_fiscal = "Не заполнено поле";
            $error = true;
        }
        
		if(strlen($company_import_export) == 0) {
			$error_company_import_export = "Не заполнено поле";
			$error = true;
		}
		if(strlen($company_season) == 0) {
			$error_company_season = "Не заполнено поле";
			$error = true;
		}


		if(!$error) {
			$db->editContractor($id, $name, $company_form, $contactName, $phone, $email, $country, $city, $bankDetails, $headName, $carsAmount, $directions, $comments, $idUser, $date, $cod_fiscal, $company_import_export, $company_season);
			
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
?>	

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Редактирование данных контрагента</title>
	<link rel="stylesheet" type="text/css" href="/css/style.css" />
</head>
<body>
	<?php require_once "../partsOfPages/menuForAdmin.php"; ?>

	<h1>Редактирование данных контрагента</h1>
	
	<form name="" action="" method="post">
		<b>Название фирмы*</b>
		<div>
			<input class="standartInput" type="text" name="name" placeholder="" value="<?=isset($_POST["name"])? $_POST["name"]:$row[name]?>">
			<span class="error"><?=$error_name?></span>
		</div> <br />
		<b>Форма собственности</b>
		<div>
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
			<span class="error"><?=$error_name?></span>
		</div> <br />
		<b>Контактное лицо в фирме*</b>
		<div>
			<input class="standartInput" type="text" name="contactName" placeholder="" value="<?=isset($_POST["contactName"])? $_POST["contactName"]:$row[contactName]?>">
			<span class="error"><?=$error_contactName?></span>
		</div> <br />
		
		<b>Контактные телефоны*</b>
		<div>
			<textarea class="standartTextArea" name="phone" placeholder=""><?=isset($_POST["phone"])? $_POST["phone"]:$row[phone]?></textarea> <br />
			<span class="error"><?=$error_phone?></span>
		</div> <br />
		
		<b>E-mail</b>
		<div>
			<input class="standartInput" type="text" name="email" placeholder="" value="<?=isset($_POST["email"])? $_POST["email"]:$row[email]?>">
			<!-- <span class="error"><?=$error_contactName?></span> -->
		</div> <br />
		
		<b>Страна*</b>
		<div>
			<input class="standartInput" type="text" name="country" placeholder="" value="<?=isset($_POST["country"])? $_POST["country"]:$row[country]?>">
			<span class="error"><?=$error_country?></span>
		</div> <br />
		
		<b>Город*</b>
		<div>
			<input class="standartInput" type="text" name="city" placeholder="" value="<?=isset($_POST["city"])? $_POST["city"]:$row[city]?>">
			<span class="error"><?=$error_city?></span>
		</div> <br />
		
		<b>Банковские реквизиты</b>
		<div>
			<textarea class="standartTextArea" name="bankDetails" placeholder=""><?=isset($_POST["bankDetails"])? $_POST["bankDetails"]:$row[bankDetails]?></textarea> <br />
			<!-- <span class="error"><?=$error_bankDetails?></span> -->
		</div> <br />

		<b>ФИО директора</b>
		<div>
			<input class="standartInput" type="text" name="headName" placeholder="" value="<?=isset($_POST["headName"])? $_POST["headName"]:$row[headName]?>">
			<span class="error"><?=$error_headName?></span>
		</div> <br />
				
		<b>Количество автомобилей на фирме (если это перевозчик)</b>
		<div>
			<input class="standartInput" type="text" name="carsAmount" placeholder="" value="<?=isset($_POST["carsAmount"])? $_POST["carsAmount"]:$row[carsAmount]?>">
			<span class="error"><?=$error_carsAmount?></span>
		</div> <br />
		
		<b>Направления по которым работают/Направления по которым ввозят</b>
		<div>
			<textarea class="standartTextArea" name="directions" placeholder=""><?=isset($_POST["directions"])? $_POST["directions"]:$row[directions]?></textarea> <br />
			<!-- <span class="error"><?=$error_directions?></span> -->
		</div> <br />		
		
		<b>Комментарии</b>
		<div>
			<textarea class="standartTextArea" name="comments" placeholder=""><?=isset($_POST["comments"])? $_POST["comments"]:$row[comments]?></textarea> <br />
			<!-- <span class="error"><?=$error_comments?></span> -->
		</div> 

                     
                          
          <? if($_SESSION["role"] == 1) {?>
                     <div class="form-group col-md-12 mr-12">
                 <label for="directionsInput">Выберите менеджера</label>
                      <select class="form-control" name="idUser" id="idUserInput">
                         <option selected="selected" value="<?=$row[idManager]?>"><?=$row5[name]?></option>
                    <? foreach($result_set_users as $row_users1) {?>
                        <?php if ($row_users1[id] != $row[idManager]) { ?>
                                    <option value="<?=$row_users1[id]?>"><?=$row_users1[name]?></option>
                                 <?}?>
                            <?}?> 
                    </select>
                   </div> <? } else { ?>
                     <div class="form-group col-md-12 mr-12">
                    <label for="directionsInput">Выберите менеджера</label>
                      <select class="form-control" name="idUser" id="idUserInput">
                         <option selected="selected" value="<?=$row[idManager]?>"><?=$row5[name]?></option>
                    </select>
                   </div>
           <?php } ?>

		<br />		

		<input class="standartButton" type="submit" name="edit" value="Изменить">
		<input class="standartButton" type="submit" name="cancel" value="Отменить">
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
</body>
</html>