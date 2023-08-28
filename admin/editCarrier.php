<?php
	session_start();
	require_once "../lib/checkWasUserLoginedAndIsAdmin.php";
	require_once "../lib/db.php";
    require_once "../lib/vars.php";
    require_once "../lib/functions.php"; 

	$db = DB::getObject();
	$countryTags = array();
	$contractorsType = $_SESSION["contractorType"];

	$result_set_users = $db->getAllUsersForAdmin();
    $row_users = $result_set_users->fetch_assoc();

        $result_set3 = $db->getUserByID($row[idManager]); // get manager name
        $row5 = $result_set3->fetch_assoc();

    $title = 'Редактирование данных '.$contractorsType.'а';
    
	if(isset($_GET["edit"])) {
		$id = $_GET["edit"];
				
		$result_set = $db->getRowWhere("contractors", "id", $id);
		$row = $result_set->fetch_assoc();
		
		$result_set_tags = $db->getRowWhereOrder("tags", "idContractor", $row[id], "tag");
		while(($row_tag = $result_set_tags->fetch_assoc()) != false) {
			$countryTags[] = $row_tag[tag];
		}
		
		$isMarked = $row[isMarked];
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
		$directions = '';
		$comments = $_POST["comments"];
		$countryTags = explode(';',$_POST["directions"]); //массив
        $cod_fiscal = $_POST["cod_fiscal"]; 

        $contractorsType = $_POST["contractorsType"]; 
        $company_import_export = $_POST["company_import_export"]; 
        $company_season = $_POST["company_season"]; 


        if($_SESSION["role"] == 1) {
        $idUser = $_POST["idUser"];
        } else {
        $idUser = $_SESSION["id"];  
        }


		$error_reg_nr = "";
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

                if(strlen($reg_nr) == 0) {
            $error_reg_nr = "Не заполнено поле";
            $error = true;
        }

		if(!$error) {
			$name = htmlspecialchars($name, ENT_QUOTES);
			$bankDetails = htmlspecialchars($bankDetails, ENT_QUOTES);
			
			writeLogsEditCustomer($id, $name, $company_form, $contactName, $phone, $email, $country, $city, $bankDetails, $headName, $carsAmount, $directions, $comments, $idUser);
			
			$db->editContractor($id, $name, $company_form, $contactName, $phone, $email, $country, $city, $bankDetails, $headName, $carsAmount, $directions, $comments, $isMarked, $idUser, $reg_nr);
			
			$db->deleteWhere("tags", "idContractor", $id);
			
			for($i = 0; $i < count($countryTags); $i++) {
				$db->addTagToContractor($id, $countryTags[$i]);
			}
			
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
    require_once "../partsOfPages/menuForAdmin.php"; ?>
    <div class="col-md-12 pt-3">
        <form name="" action="" method="post">
            <input class="btn btn-danger btn-sm" type="submit" name="cancel" value="Вернуться назад"> <br /> <br />
            <div class="row">
                <div class="form-group col-md-4 mr-4">
                    <label for="nameInput" class="required">Название фирмы</label>
                    <input class="form-control<?=isset($error_name)&&$error_name!=''?' is-invalid':''?>" type="text" name="name" id="nameInput" value="<?=isset($_POST["name"])? $_POST["name"]:$row[name]?>">

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
                    <input class="form-control<?=isset($error_contactName)&&$error_contactName!=''?' is-invalid':''?>" type="text" name="contactName" id="contactNameInput" value="<?=isset($_POST["contactName"])? $_POST["contactName"]:$row[contactName]?>">
                    <div class="invalid-feedback" <?=isset($error_contactName)&&$error_contactName!=''?'style="display:block;"':''?>><?=$error_contactName ?></div>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="contactphoneInput" class="required">Контактные телефоны</label>
                    <textarea class="form-control<?=isset($error_phone)&&$error_phone!=''?' is-invalid':''?>" name="phone" id="contactphoneInput" ><?=isset($_POST["phone"])? $_POST["phone"]:$row[phone]?></textarea>
                    <div class="invalid-feedback" <?=isset($error_phone)&&$error_phone!=''?'style="display:block;"':''?>><?=$error_phone ?></div>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="emailInput">E-mail</label>
                    <input class="form-control" type="text" name="email" id="emailInput" value="<?=isset($_POST["email"])? $_POST["email"]:$row[email]?>">
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="countryInput" class="required">Страна</label>
                    <input class="form-control<?=isset($error_country)&&$error_country!=''?' is-invalid':''?>" type="text" name="country" id="countryInput" value="<?=isset($_POST["country"])? $_POST["country"]:$row[country]?>">
                    <div class="invalid-feedback" <?=isset($error_country)&&$error_country!=''?'style="display:block;"':''?>><?=$error_country ?></div>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="cityInput" class="required">Город</label>
                    <input class="form-control<?=isset($error_city)&&$error_city!=''?' is-invalid':''?>" type="text" name="city" id="cityInput" value="<?=isset($_POST["city"])? $_POST["city"]:$row[city]?>">
                    <div class="invalid-feedback" <?=isset($error_city)&&$error_city!=''?'style="display:block;"':''?>><?=$error_city ?></div>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="bankDetailsInput">Банковские реквизиты</label>
                    <textarea class="form-control" name="bankDetails" id="bankDetailsInput" ><?=isset($_POST["bankDetails"])? $_POST["bankDetails"]:$row[bankDetails]?></textarea>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="headNameInput">ФИО директора</label>
                    <input class="form-control" type="text" name="headName" id="headNameInput" value="<?=isset($_POST["headName"])? $_POST["headName"]:$row[headName]?>">
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="carsAmountInput">Количество автомобилей на фирме </label>
                    <input class="form-control" type="text" name="carsAmount" id="carsAmountInput" value="<?=isset($_POST["carsAmount"])? $_POST["carsAmount"]:$row[carsAmount]?>">
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="directionsInput">Направления по которым ввозят</label>
                    <textarea class="form-control" name="directions" id="directionsInput" ><?=isset($_POST["directions"])? $_POST["directions"]:implode(";", $countryTags)?></textarea>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="commentsInput">Комментарии</label>
                    <textarea class="form-control" name="comments" id="commentsInput" ><?=isset($_POST["comments"])? $_POST["comments"]:$row[comments]?></textarea>
                </div>

                <div class="form-group col-md-12 mr-12">
                    <label for="headNameInput">Регистрационный номер</label>
                    <input class="form-control" type="text" name="reg_nr" id="reg_nrInput" value="<?=$_POST["reg_nr"]?>">
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
            </div>
            <input class="btn btn-secondary btn-sm" type="submit" name="edit" value="Изменить">
            <input class="btn btn-danger btn-sm" type="submit" name="cancel" value="Отменить">
        </form>
    </div>
    <script>
        $(function(){
            $('#directionsInput').tagsInput({'delimiter': [';'],'defaultText':'теги'});
        });
    </script>
<?  require_once '../partsOfPages/footer.php';?>