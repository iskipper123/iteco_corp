<?php

	require_once "../lib/db.php";
    require_once "../lib/vars.php";  

    $db = DB::getObject();
    $countryTags = array();
    
    $title = 'Добавить Перевозчика';

    require_once '../partsOfPages/head.php';
    require_once "../partsOfPages/menuForAdmin.php"; ?>
    <div class="col-md-12 pt-3">
        <form id="addcarrier" name="" action="" method="post">
            <input class="btn btn-danger btn-sm" type="submit" name="cancel" value="Вернуться назад"> <br /> <br />
            <div class="row">
                <div class="form-group col-md-4 mr-4">
                    <label for="nameInput" class="required">Название фирмы</label>
                    <input class="form-control<?=isset($error_name)&&$error_name!=''?' is-invalid':''?>" type="text" name="name" id="nameInput" value="<?=$_POST["name"]?>">

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
                    <textarea class="form-control<?=isset($error_phone)&&$error_phone!=''?' is-invalid':''?>" name="phone" id="contactphoneInput" ><?=$_POST["phone"]?></textarea>
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
                    <textarea class="form-control" name="bankDetails" id="bankDetailsInput" ><?=$_POST["bankDetails"]?></textarea>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="headNameInput">ФИО директора</label>
                    <input class="form-control" type="text" name="headName" id="headNameInput" value="<?=$_POST["headName"]?>">
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="carsAmountInput">Количество автомобилей на фирме </label>
                    <input class="form-control" type="text" name="carsAmount" id="carsAmountInput" value="<?=$_POST["carsAmount"]?>">
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="directionsInput">Направления по которым ввозят</label>
                    <textarea class="form-control" name="directions" id="directionsInput" ><?=isset($_POST["directions"])? $_POST["directions"]:implode(";", $countryTags)?></textarea>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="commentsInput">Комментарии</label>
                    <textarea class="form-control" name="comments" id="commentsInput" ><?=$comments?></textarea>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <? if($isMarked == 1) {?>
                        <input type="checkbox" checked name="isMarked" value="1"> <b>Метка</b> <br />
                    <?} else {?>
                        <input type="checkbox" name="isMarked" value="1"> <b>Метка</b> <br />
                    <?}?>
                </div>
            </div>
            <input class="btn btn-secondary btn-sm" type="add" id="add" onclick="addCarrier()"  name="add" value="Добавить">
            <input class="btn btn-danger btn-sm" type="submit" name="cancel" value="Отменить">
        </form>
<script>
function addCarrier(){
	 $.ajax({
		type: "POST",
		url: "addCarrier_ajax.php",
		data: $('#addcarrier').serialize(),
		 beforeSend: function () {
                $('#add').attr("disabled","disabled");
                $('.modal-body').css('opacity', '.5');
            },
            success: function(data) {
                if (data == true) {
                	$('.statusMsg').html('<span style="color:red; text-align:center;">Вы не заполнили все поля!</span>');
                } else {
                   $('.statusMsg').html('<span style="color:green; text-align:center;">Перевозчик создан успешно!</p>');
                   $('#addcarrier')[0].reset();

                    setTimeout(
                  function() 
                  {
                     location.reload();
                  }, 100);  

                }
                $('#add').removeAttr("disabled");
                $('.modal-body').css('opacity', '');
            }
	}); 
	}
</script>
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
<div class="statusMsg"></div>
    </div>
    <script>
        $(function(){
            $('#directionsInput').tagsInput({'delimiter': [';'],'defaultText':'теги'});
        });
    </script>
<?  require_once '../partsOfPages/footer.php';?>