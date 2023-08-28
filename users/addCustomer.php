<?php
    
    require_once "../lib/checkWasUserLoginedAndIsUser.php";
    require_once "../lib/db.php";
    require_once "../lib/vars.php"; 

    $db = DB::getObject();


        $isMarked = 0;
    $title = 'Добавить Заказчика'; 
    
    require_once '../partsOfPages/head.php';
    require_once "../partsOfPages/menuForAdmin.php"; ?>
    <div class="col-md-12 pt-3">
        <form id="addcustomer" name="" action="" method="post" >
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

                   <div class="form-group col-md-3 mr-3">
                    <label for="nameInput" class="required">Роль контрагента</label>

                    <select class="form-control" name="contractorsType" id="contractorsType">
                        <? if($row[contractorsType] == "") { ?>
                            <option selected="selected"></option>
                        <?}
                        else { ?>
                            <option selected="selected"> <?=$row[contractorsType] ?> </option>
                        <?}?>
                        <? for($i = 0; $i < count($arrayOfContractorsTypes); $i++) {
                            if($row[contractorsType] != $arrayOfContractorsTypes[$i]) { ?>
                                <option><?=$arrayOfContractorsTypes[$i] ?></option>
                            <?}?>
                        <?}?> 
                    </select>
                    <div class="invalid-feedback" <?=isset($error_contractorsType)&&$error_contractorsType!=''?'style="display:block;"':''?>><?=$error_contractorsType ?></div>
                </div>

                 <div class="form-group col-md-3 mr-3">
                    <label for="nameInput" class="required">Занятость</label>
                 
                    <select class="form-control" name="company_import_export" id="company_import_export">
                        <? if($row[company_import_export] == "") { ?>
                            <option selected="selected"></option>
                        <?}
                        else { ?>
                            <option selected="selected"> <?=$row[company_import_export] ?> </option>
                        <?}?>
                        <? for($i = 0; $i < count($arrayOfContractorsImportExport); $i++) {
                            if($row[company_import_export] != $arrayOfContractorsImportExport[$i]) { ?>
                                <option><?=$arrayOfContractorsImportExport[$i] ?></option>
                            <?}?>
                        <?}?>
                    </select>
                    <div class="invalid-feedback" <?=isset($error_company_import_export)&&$error_company_import_export!=''?'style="display:block;"':''?>><?=$error_company_import_export ?></div>
                </div>

                <div class="form-group col-md-3 mr-3">
                    <label for="nameInput" class="required">Сезонность</label>
                  
                    <select class="form-control" name="company_season" id="company_season">
                        <? if($row[company_season] == "") { ?>
                            <option selected="selected"></option>
                        <?}
                        else { ?>
                            <option selected="selected"> <?=$row[company_season] ?> </option>
                        <?}?>
                        <? for($i = 0; $i < count($arrayOfContractorsSeason); $i++) {
                            if($row[company_season] != $arrayOfContractorsSeason[$i]) { ?>
                                <option><?=$arrayOfContractorsSeason[$i] ?></option>
                            <?}?>
                        <?}?>
                    </select>
                    <div class="invalid-feedback" <?=isset($error_company_season)&&$error_company_season!=''?'style="display:block;"':''?>><?=$error_company_season ?></div>
                </div>
 

                <div class="form-group col-md-4 mr-4">
                    <label for="contactphoneInput" class="required">Контактные телефоны</label>
                    <textarea class="form-control<?=isset($error_phone)&&$error_phone!=''?' is-invalid':''?>" name="phone" id="contactphoneInput" ><?=$_POST["phone"]?><?=$phone?></textarea>
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
                    <textarea class="form-control" name="bankDetails" id="bankDetailsInput" ><?=$_POST["bankDetails"]?><?=$bankDetails?></textarea>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="headNameInput">ФИО директора</label>
                    <input class="form-control" type="text" name="headName" id="headNameInput" value="<?=$_POST["headName"]?>">
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="directionsInput">Направления, по которым работают</label>
                    <textarea class="form-control" name="directions" id="directionsInput" ><?=$directions?></textarea>
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
                 <div class="form-group col-md-12 mr-12">
                    <label for="headNameInput">Регистрационный номер</label>
                    <input class="form-control" type="text" name="cod_fiscal" id="reg_nrInput" value="<?=$_POST["cod_fiscal"]?>">
                </div>

            </div>
            <input class="btn btn-secondary btn-sm" type="add" id="add" onclick="addCustomer()" name="add" value="Добавить">
            <input class="btn btn-danger btn-sm" type="submit" name="cancel" value="Отменить">
        </form>
            <script>
function addCustomer(){
     $.ajax({
        type: "POST",
        url: "addCustomer_ajax.php",
        data: $('#addcustomer').serialize(),
         beforeSend: function () {
                $('#add').attr("disabled","disabled");
                $('.modal-body').css('opacity', '.5');
            },
            success: function(data) {
                if (data == true) {
                     $('.statusMsg').html('<span style="color:red; text-align:center;">Имя контрагента и Фискальный код должны быть уникальны!</span>');
                } else {
                   $('.statusMsg').html('<span style="color:green; text-align:center;">Заказчик создан успешно!</p>');
                   $('.invalid-feedback').html('<span style="color:green; text-align:center;">Заказчик создан успешно!</p>');
                   $('#addcustomer')[0].reset();

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
<?  require_once '../partsOfPages/footer.php';?>
