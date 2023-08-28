<?php

$check = FALSE;
$hash = $_GET['hash'];


if( strpos(file_get_contents("get_url.txt"),$hash) !== false) {
  $check = TRUE;
 }


if($check) { ?>
 
   <link rel="stylesheet" type="text/css" href="/css/form.css" />
<?php
    require_once "lib/db.php";
    require_once "lib/vars.php";
    
    $title = 'Приём заявок';
	
	$_SESSION["userType"] = 1;
	$db = DB::getObject();


if (isset($_GET['form_id'])) {
            $id = $_GET['form_id'];
        } else {
            $error = true;
        }

	if(isset($_POST["add"])) {

		$carrier = $_POST["carrier"];
        $carrier_name = $_POST["carrier_name"];
		$carrier_details = $_POST["carrier_details"];
		$carrier_phone = $_POST["carrier_phone"];
		$carrier_auto_nr = $_POST["carrier_auto_nr"];
        $carrier_fio = $_POST["carrier_fio"];
		$carrier_transport_type = $_POST["carrier_transport_type"];
        $carrier_adress = $_POST["carrier_adress"];
		$carrier_pogran = $_POST["carrier_pogran"];

        $carrier_teh_pasport = $_FILES["carrier_teh_pasport"]["name"];
        $carrier_prava = $_FILES["carrier_prava"]["name"];
        $carrier_docs = $_FILES["carrier_docs"]["name"];

		$phone_other_companies_carrier = $_POST["phone_other_companies_carrier"];

		$error_carrier = "";
        $error_carrier_name = "";
        $error_carrier_details = "";
		$error_carrier_phone = "";
		$error_carrier_auto_nr = "";
		$error_carrier_fio = "";
		$error_carrier_transport_type = "";
		$error_carrier_adress = "";
		$error_carrier_pogran = "";
		$error_carrier_teh_pasport = "";
		$error_carrier_prava = "";
		$error_carrier_docs = "";
		$error_phone_other_companies_carrier = "";
		$error = false;
	

            if($_FILES["carrier_teh_pasport"]["size"] > 16777214) {
            $error_carrier_teh_pasport = "Превышен максимальный размер файла (16МБ)";
            $error = true;
        }       
       
        if(!empty($_FILES["carrier_teh_pasport"]["tmp_name"])) {
            $arrayTypeOfFile = explode("/", $_FILES["carrier_teh_pasport"]["type"]);
            $typeOfFile = $arrayTypeOfFile[1];
            $typeOfFile1 = $arrayTypeOfFile[0];

            if($typeOfFile1 != "image" & $typeOfFile != "vnd.openxmlformats-officedocument.wordprocessingml.document" & $typeOfFile != "msword" &
                    $typeOfFile != "vnd.openxmlformats-officedocument.spreadsheetml.sheet" & $typeOfFile != "vnd.ms-excel" & $typeOfFile != "pdf" & $typeOfFile != "jpg" & $typeOfFile != "png") {
                $error_carrier_teh_pasport = "Загружаемый файл должен быть в формате doc, docx, xlsx, xls, PDF либо быть изображением";
                $error = true;
            }
        }

            if($_FILES["carrier_prava"]["size"] > 16777214) {
            $error_carrier_prava = "Превышен максимальный размер файла (16МБ)";
            $error = true;
        }       
       
        if(!empty($_FILES["carrier_prava"]["tmp_name"])) {
            $arrayTypeOfFile = explode("/", $_FILES["carrier_prava"]["type"]);
            $typeOfFile = $arrayTypeOfFile[1];
            $typeOfFile1 = $arrayTypeOfFile[0];

            if($typeOfFile1 != "image" & $typeOfFile != "vnd.openxmlformats-officedocument.wordprocessingml.document" & $typeOfFile != "msword" &
                    $typeOfFile != "vnd.openxmlformats-officedocument.spreadsheetml.sheet" & $typeOfFile != "vnd.ms-excel" & $typeOfFile != "pdf" & $typeOfFile != "jpg" & $typeOfFile != "png") {
                $error_carrier_prava = "Загружаемый файл должен быть в формате doc, docx, xlsx, xls, PDF либо быть изображением";
                $error = true;
            }
        }

            if($_FILES["carrier_docs"]["size"] > 16777214) {
            $error_carrier_docs = "Превышен максимальный размер файла (16МБ)";
            $error = true;
        }       
       
        if(!empty($_FILES["carrier_docs"]["tmp_name"])) {
            $arrayTypeOfFile = explode("/", $_FILES["carrier_docs"]["type"]);
            $typeOfFile = $arrayTypeOfFile[1];
            $typeOfFile1 = $arrayTypeOfFile[0];

            if($typeOfFile1 != "image" & $typeOfFile != "vnd.openxmlformats-officedocument.wordprocessingml.document" & $typeOfFile != "msword" &
                    $typeOfFile != "vnd.openxmlformats-officedocument.spreadsheetml.sheet" & $typeOfFile != "vnd.ms-excel" & $typeOfFile != "pdf" & $typeOfFile != "jpg" & $typeOfFile != "png") {
                $error_carrier_docs = "Загружаемый файл должен быть в формате doc, docx, xlsx, xls, PDF либо быть изображением";
                $error = true;
            }
        }

  if(strlen($carrier_teh_pasport) == 0) { 
            $error_carrier_teh_pasport = "Не загружен файл";
            $error = true;
        }  
        if(strlen($carrier_prava) == 0) { 
            $error_carrier_prava = "Не загружен файл";
            $error = true;
        }  
        if(strlen($carrier_docs) == 0) { 
            $error_carrier_docs = "Не загружен файл";
            $error = true;
        }  
       
		if(strlen($carrier) == 0) { 
			$error_carrier = "Не заполнено поле";
			$error = true;
		}     
        if(strlen($carrier_name) == 0) {
            $error_carrier_name = "Не заполнено поле";
            $error = true;
        }	
        if(strlen($carrier_details) == 0) {
            $error_carrier_details = "Не заполнено поле";
            $error = true;
        }          
        if(strlen($carrier_phone) == 0) {
            $error_carrier_phone = "Не заполнено поле";
            $error = true;
        }   
        if(strlen($carrier_auto_nr) == 0) {
            $error_carrier_auto_nr = "Не заполнено поле";
            $error = true;
        } 
        if(strlen($carrier_fio) == 0) {
            $error_carrier_fio = "Не заполнено поле";
            $error = true;
        } 
        if(strlen($carrier_transport_type) == 0) {
            $error_carrier_transport_type = "Не заполнено поле";
            $error = true;
        } 
        if(strlen($carrier_adress) == 0) {
            $error_carrier_adress = "Не заполнено поле";
            $error = true;
        } 
        if(strlen($carrier_pogran) == 0) {
            $error_carrier_pogran = "Не заполнено поле";
            $error = true;
        } 


        if(strlen($phone_other_companies_carrier) == 0) {
            $error_phone_other_companies_carrier = "Не заполнено поле";
            $error = true;
        }


		//найти id перевозчика
		$result_set7 = $db->getrow_form("contractors", "name", $carrier, "isDeleted", 0, "Перевозчик", "name");
		if($result_set7->num_rows == 0) {
            $carrier = $carrier;
		} 
		else $row7 = $result_set7->fetch_assoc();


		if(!$error) {
            if(!empty($_FILES["carrier_teh_pasport"]["tmp_name"])) {               
                //взять расширение файла
                $temp1 = explode(".", $carrier_teh_pasport);
                $lastPartOfName1 = $temp1[count($temp1) - 1];
                
                $scan1 = 'files/forms/'.$id."tehpasport.".$lastPartOfName1;

                $upload1 = $_FILES["carrier_teh_pasport"]["tmp_name"];
                move_uploaded_file($upload1, "$scan1");
            }

            if(!empty($_FILES["carrier_prava"]["tmp_name"])) {               
                //взять расширение файла
                $temp2 = explode(".", $carrier_prava);
                $lastPartOfName2 = $temp2[count($temp2) - 1];
                
                $scan2 = 'files/forms/'.$id."prava.".$lastPartOfName2;

                $upload2 = $_FILES["carrier_prava"]["tmp_name"];
                move_uploaded_file($upload2, "$scan2");
            }

            if(!empty($_FILES["carrier_docs"]["tmp_name"])) {               
                //взять расширение файла
                $temp3 = explode(".", $carrier_docs);
                $lastPartOfName3 = $temp3[count($temp3) - 1];
                
                $scan3 = 'files/forms/'.$id."docs.".$lastPartOfName3;

                $upload3 = $_FILES["carrier_docs"]["tmp_name"];
                move_uploaded_file($upload3, "$scan3");
            }

			$db->updateFormRequest($id, $carrier, $carrier_name, $carrier_details, $carrier_phone, $carrier_auto_nr, $carrier_fio, $carrier_transport_type, $carrier_adress, $carrier_pogran, $scan1, $scan2, $scan3, $phone_other_companies_carrier);
						
			//$db->addLog($row7[id], time(), "Приём заявок. Контрагент добавлен в качестве перевозчика.", 3, $id, $id);
			
			header("Location: success.php");
			exit;
		}
	}
	
	$result_set2 = $db->getRowWhereOrder("users", "isDeleted", 0, "name");
    require_once 'partsOfPages/head.php';?>

    <div class="col-md-12 pt-3" >
          <img src="/images/form_logo.png" alt="iteco logo" id="logoImg" style="  margin: 0px auto 30px auto; display: block;">
        <form name="" action="" method="post" enctype="multipart/form-data" class="form-group col-md-6 mr-6" style="margin:0px auto !important; " id="out_form">
            <div class="row">
            	 <div class="form-group col-md-12 mr-12">
            	 
            	    <h1 style="text-align: center;">Данные перевозчик</h1>
            	</div>
        <div class="form-group col-md-12 mr-12">
                    <label for="tag1" class="required">Название компании</label>
                    <input class="form-control<?=isset($error_carrier)&&$error_carrier!=''?' is-invalid':''?>" data-auto-close="true" type="text" name="carrier" id="tag1" autocomplete="off" value="<?=isset($_POST["carrier"])? $_POST["carrier"]:$row[carrier]?>">
                    <div class="invalid-feedback" <?=isset($error_carrier)&&$error_carrier!=''?'style="display:block;"':''?>><?=$error_carrier?></div>

                    <div class="valid-feedback" style="color:green;" <?=isset($error_carrier_exist)&&$error_carrier_exist!=''?'style="display:block;"':''?>><?=$error_carrier_exist?></div>
                </div>
                 <div class="form-group col-md-12 mr-12">
                    <label for="carrier" class="required">Реквизиты</label>
                    <textarea class="form-control<?=isset($error_carrier_details)&&$error_carrier_details!=''?' is-invalid':''?>" data-auto-close="true" type="text" name="carrier_details" id="carrier_details" autocomplete="off"><?=isset($_POST["carrier_details"])? $_POST["carrier_details"]:$row[carrier_details]?></textarea>
                    <div class="invalid-feedback" <?=isset($error_carrier_details)&&$error_carrier_details!=''?'style="display:block;"':''?>><?=$error_carrier_details?></div>
                </div>
                <div class="form-group col-md-12 mr-12"> 
                    <label for="carrier" class="required">Имя</label>
                    <input class="form-control<?=isset($error_carrier_name)&&$error_carrier_name!=''?' is-invalid':''?>" data-auto-close="true" type="text" name="carrier_name" id="carrier_name" autocomplete="off" value="<?=isset($_POST["carrier_name"])? $_POST["carrier_name"]:$row[carrier_name]?>">
                    <div class="invalid-feedback" <?=isset($error_carrier_name)&&$error_carrier_name!=''?'style="display:block;"':''?>><?=$error_carrier_name?></div>
                </div>
                <div class="form-group col-md-12 mr-12">
                    <label for="carrier" class="required">Телефон</label>
                    <input class="form-control<?=isset($error_carrier_phone)&&$error_carrier_phone!=''?' is-invalid':''?>" data-auto-close="true" type="text" name="carrier_phone" id="carrier_phone" autocomplete="off" value="<?=isset($_POST["carrier_phone"])? $_POST["carrier_phone"]:$row[carrier_phone]?>">
                    <div class="invalid-feedback" <?=isset($error_carrier_phone)&&$error_carrier_phone!=''?'style="display:block;"':''?>><?=$error_carrier_phone?></div>
                </div>
                  <div class="form-group col-md-12 mr-12">
                <label for="carrier" class="required">Номер автомобиля</label>
                    <input class="form-control<?=isset($error_carrier_auto_nr)&&$error_carrier_auto_nr!=''?' is-invalid':''?>" data-auto-close="true" type="text" name="carrier_auto_nr" id="carrier_auto_nr" autocomplete="off" value="<?=isset($_POST["carrier_auto_nr"])? $_POST["carrier_auto_nr"]:$row[carrier_auto_nr]?>">
                    <div class="invalid-feedback" <?=isset($error_carrier_auto_nr)&&$error_carrier_auto_nr!=''?'style="display:block;"':''?>><?=$error_carrier_auto_nr?></div>
                 </div>

                <div class="form-group col-md-12 mr-12">
                <label for="carrier" class="required">Водитель Ф.И.О</label>
                    <input class="form-control<?=isset($error_carrier_fio)&&$error_carrier_fio!=''?' is-invalid':''?>" data-auto-close="true" type="text" name="carrier_fio" id="carrier_fio" autocomplete="off" value="<?=isset($_POST["carrier_fio"])? $_POST["carrier_fio"]:$row[carrier_fio]?>">
                    <div class="invalid-feedback" <?=isset($error_carrier_fio)&&$error_carrier_fio!=''?'style="display:block;"':''?>><?=$error_carrier_fio?></div>
                 </div>

                <div class="form-group col-md-12 mr-12">
                <label for="carrier" class="required">Тип транспорта</label>
     
                    <select class="form-control<?=isset($error_carrier_transport_type)&&$error_carrier_transport_type!=''?' is-invalid':''?>" name="carrier_transport_type" id="categoryInput">
                <option selected="selected"></option>
                <? for($i = 0; $i < count($arrayOfTransportType); $i++) {
                    if($carrier_transport_type == $arrayOfTransportType[$i]) { ?>
                        <option selected="selected"><?=$arrayOfTransportType[$i] ?></option>
                    <?}
                    else {?>
                        <option><?=$arrayOfTransportType[$i] ?></option>
                    <?}?>
                <?}?>
            </select>
                    <div class="invalid-feedback" <?=isset($error_carrier_transport_type)&&$error_carrier_transport_type!=''?'style="display:block;"':''?>><?=$error_carrier_transport_type?></div>
                 </div>

                <div class="form-group col-md-12 mr-12">
                <label for="carrier" class="required">Маршрут Следования</label>
                    <input class="form-control<?=isset($error_carrier_adress)&&$error_carrier_adress!=''?' is-invalid':''?>" data-auto-close="true" type="text" name="carrier_adress" id="carrier_adress" autocomplete="off" value="<?=isset($_POST["carrier_adress"])? $_POST["carrier_adress"]:$row[carrier_adress]?>">
                    <div class="invalid-feedback" <?=isset($error_carrier_adress)&&$error_carrier_adress!=''?'style="display:block;"':''?>><?=$error_carrier_adress?></div>
                 </div>

                <div class="form-group col-md-12 mr-12">
                <label for="carrier" class="required">Погран-Переходы</label>
                    <input class="form-control<?=isset($error_carrier_pogran)&&$error_carrier_pogran!=''?' is-invalid':''?>" data-auto-close="true" type="text" name="carrier_pogran" id="carrier_pogran" autocomplete="off" value="<?=isset($_POST["carrier_pogran"])? $_POST["carrier_pogran"]:$row[carrier_pogran]?>">
                    <div class="invalid-feedback" <?=isset($error_carrier_pogran)&&$error_carrier_pogran!=''?'style="display:block;"':''?>><?=$error_carrier_pogran?></div>
                 </div>

                <div class="form-group col-md-4 mr-4">
                <label for="carrier" class="required" style="    min-height: 36px;">Загрузка техпаспорта</label>
                    <input class="form-control-file<?=isset($error_carrier_teh_pasport)&&$error_carrier_teh_pasport!=''?' is-invalid':''?>" type="file" name="carrier_teh_pasport" id="fileInput">
                    <small class="form-text text-muted">(допустимые форматы - doc, docx, xlsx, xls, PDF, JPG, PNG изображения; размер до 16 МБ)</small>
                    <div class="invalid-feedback" <?=isset($error_carrier_teh_pasport)&&$error_carrier_teh_pasport!=''?'style="display:block;"':''?>><?=$error_carrier_teh_pasport ?></div>
                 </div>

                <div class="form-group col-md-4 mr-4">
                <label for="carrier" class="required">Загрузка водительского паспорта</label>
                    <input class="form-control-file<?=isset($error_carrier_prava)&&$error_carrier_prava!=''?' is-invalid':''?>" type="file" name="carrier_prava" id="fileInput">
                    <small class="form-text text-muted">(допустимые форматы - doc, docx, xlsx, xls, PDF, JPG, PNG изображения; размер до 16 МБ)</small>
                    <div class="invalid-feedback" <?=isset($error_carrier_prava)&&$error_carrier_prava!=''?'style="display:block;"':''?>><?=$error_carrier_prava ?></div>
                 </div>

                <div class="form-group col-md-4 mr-4">
                <label for="carrier" class="required">Загрузка водительского паспорта</label>
                    <input class="form-control-file<?=isset($error_carrier_docs)&&$error_carrier_docs!=''?' is-invalid':''?>" type="file" name="carrier_docs" id="fileInput">
                    <small class="form-text text-muted">(допустимые форматы - doc, docx, xlsx, xls, PDF, JPG, PNG изображения; размер до 16 МБ)</small>
                    <div class="invalid-feedback" <?=isset($error_carrier_docs)&&$error_carrier_docs!=''?'style="display:block;"':''?>><?=$error_carrier_docs ?></div>
                 </div>

                <div class="form-group col-md-12 mr-12">
                    <label for="carrier" class="required">Контактные телефоны 1-2 компаний с кем работали за последний месяц</label>
                    <textarea class="form-control<?=isset($error_phone_other_companies_carrier)&&$error_phone_other_companies_carrier!=''?' is-invalid':''?>" data-auto-close="true" type="text" name="phone_other_companies_carrier" id="phone_other_companies_carrier" autocomplete="off"><?=isset($_POST["phone_other_companies_carrier"])? $_POST["phone_other_companies_carrier"]:$row[phone_other_companies_carrier]?></textarea>
                    <div class="invalid-feedback" <?=isset($error_phone_other_companies_carrier)&&$error_phone_other_companies_carrier!=''?'style="display:block;"':''?>><?=$error_phone_other_companies_carrier?></div>
                </div>
            <input class="btn btn-secondary btn-sm" type="submit" name="add" value="Добавить" style="margin:0px auto; display: block; padding: 0 60px;">
        </form>
    </div>
    <script defer>

        function autocompleteTag1(){
            var ajax = new XMLHttpRequest();
            ajax.open("GET", "lib/autocomplete1.php", true);
            ajax.onload = function () {
                var list = JSON.parse(ajax.responseText);
                new Awesomplete(document.querySelector("#tag1"), {minChars: 1, list: list});
            };
            ajax.send();
        }

        
		$(function(){
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

<?php 
ob_clean();
 flush();
 
 readfile($s_file);
 exit();
}
else {
 exit("Не правильная ссылка!!!");
}
?>