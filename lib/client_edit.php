<?php
  
    require_once "db.php";
    require_once "vars.php"; 
    require_once "../lib/functions.php";
    
    $db = DB::getObject();
    $countryTags = array();
    $contractorsType = $_SESSION["contractorType"];
    
    $title = 'Редактирование данных '.$contractorsType.'а';

    if(isset($_GET["id"])) {
        $id = $_GET["id"];
                
        $result_set = $db->getRowWhere("contractors", "id", $id);
        $row = $result_set->fetch_assoc();

        $result_set_tags = $db->getRowWhereOrder("tags", "idContractor", $row[id], "tag");
        while(($row_tag = $result_set_tags->fetch_assoc()) != false) {
            $countryTags[] = $row_tag[tag];
        }
        
        $isMarked = $row[isMarked];
    }
    $result_set2 = $db->getRowWhereOrder("users", "isDeleted", 0, "name");
        $result_set_users = $db->getAllUsersForAdmin();
    $row_users = $result_set_users->fetch_assoc();


        $result_set3 = $db->getUserByID($row[idManager]); // get manager name
        $row5 = $result_set3->fetch_assoc();

    ?>



    <div class="col-md-6 mr-6">    
        <div class="col-md-12 pt-3">
        <div class="alert alert-success" role="alert" id="result_form" style="display: none;"></div>
        <span class="edit">Редактировать</span>
        <form name="" action="" method="post" id="ajax_form" class="inactive">
            <div class="row">
                                       
          <? //if($_SESSION["role"] == 1) {?>
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
                   </div> <? //} else { ?>
                 <!--     <div class="form-group col-md-12 mr-12">
                    <label for="directionsInput">Выберите менеджера</label>
                      <select class="form-control" name="idUser" id="idUserInput">
                         <option selected="selected" value="<?=$row[idManager]?>"><?=$row5[name]?></option>
                    </select>
                   </div> -->
           <?php //} ?>


                 <div class="form-group col-md-6 mr-6">
                     <input class="form-control" type="hidden" name="id" id="id" value="<?=$row[id]?>">
                    <label for="nameInput" class="required">Название фирмы</label> 
                    <input class="form-control<?=isset($error_name)&&$error_name!=''?' is-invalid':''?>" type="text" name="name" id="nameInput" value="<?=isset($_POST["name"])? $_POST["name"]:$row[name]?>">
                </div>
                 <div class="form-group col-md-6 mr-6">
                    <label for="company_form" >Форма</label> 
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
                <div class="form-group col-md-6 mr-6">
                    <label for="contactNameInput" class="required">Контактное лицо в фирме</label>
                    <input class="form-control<?=isset($error_contactName)&&$error_contactName!=''?' is-invalid':''?>" type="text" name="contactName" id="contactNameInput" value="<?=isset($_POST["contactName"])? $_POST["contactName"]:$row[contactName]?>">
                    <div class="invalid-feedback" <?=isset($error_contactName)&&$error_contactName!=''?'style="display:block;"':''?>><?=$error_contactName ?></div>
                </div>
                <div class="form-group col-md-6 mr-6">
                    <label for="contactphoneInput" class="required">Контактные телефоны</label>
                    <textarea class="form-control<?=isset($error_phone)&&$error_phone!=''?' is-invalid':''?>" name="phone" id="contactphoneInput" ><?=isset($_POST["phone"])? $_POST["phone"]:$row[phone]?></textarea>
                    <div class="invalid-feedback" <?=isset($error_phone)&&$error_phone!=''?'style="display:block;"':''?>><?=$error_phone ?></div>
                </div>

                <div class="form-group col-md-6 mr-6">
                    <label for="countryInput" class="required">Страна</label>
                    <input class="form-control<?=isset($error_country)&&$error_country!=''?' is-invalid':''?>" type="text" name="country" id="countryInput" value="<?=isset($_POST["country"])? $_POST["country"]:$row[country]?>">
                    <div class="invalid-feedback" <?=isset($error_country)&&$error_country!=''?'style="display:block;"':''?>><?=$error_country ?></div>
                </div>
                <div class="form-group col-md-6 mr-6">
                    <label for="cityInput" class="required">Город</label>
                    <input class="form-control<?=isset($error_city)&&$error_city!=''?' is-invalid':''?>" type="text" name="city" id="cityInput" value="<?=isset($_POST["city"])? $_POST["city"]:$row[city]?>">
                    <div class="invalid-feedback" <?=isset($error_city)&&$error_city!=''?'style="display:block;"':''?>><?=$error_city ?></div>
                </div>
                <div class="form-group col-md-6 mr-6">
                    <label for="headNameInput">ФИО директора</label>
                    <input class="form-control" type="text" name="headName" id="headNameInput" value="<?=isset($_POST["headName"])? $_POST["headName"]:$row[headName]?>">
                </div>
                <div class="form-group col-md-6 mr-6">
                    <label for="emailInput">E-mail</label>
                    <input class="form-control" type="text" name="email" id="emailInput" value="<?=isset($_POST["email"])? $_POST["email"]:$row[email]?>">
                </div>
                <div class="form-group col-md-12 mr-12">
                    <label for="bankDetailsInput">Банковские реквизиты</label>
                    <textarea class="form-control" name="bankDetails" id="bankDetailsInput" ><?=isset($_POST["bankDetails"])? $_POST["bankDetails"]:$row[bankDetails]?></textarea>
                </div>
                <div class="form-group col-md-6 mr-6">
                    <label for="directionsInput">Направления по которым работают</label>
                    <textarea class="form-control" name="directions" id="directionsInput" ><?=isset($_POST["directions"])? $_POST["directions"]:$row[directions]?></textarea>
                </div>

                  <div class="form-group col-md-6 mr-6">
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

                 <div class="form-group col-md-6 mr-6">
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

                <div class="form-group col-md-6 mr-6">
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

                <div class="form-group col-md-12 mr-12">
                    <label for="commentsInput">Комментарии</label>
                    <textarea class="form-control" name="comments" id="commentsInput" ><?=isset($_POST["comments"])? $_POST["comments"]:$row[comments]?></textarea>
                </div>
                <!--<div class="form-group col-md-12 mr-12">
                    <? //for($i = 0; $i < count($arrayOfTagsForCountry); $i++) {?>
                        <? //if(isset($countryTags) && in_array($arrayOfTagsForCountry[$i], $countryTags)) {?>
                            <input type="checkbox" checked name="countryTags[]" value="<?=$arrayOfTagsForCountry[$i]?>"> <b><?=$arrayOfTagsForCountry[$i]?></b> <br />
                        <?//} else {?>
                            <input type="checkbox" name="countryTags[]" value="<?=$arrayOfTagsForCountry[$i]?>"> <b><?=$arrayOfTagsForCountry[$i]?></b> <br />
                        <?//}?>
                    <?//}?>
                </div>-->

                <div class="form-group col-md-12 mr-12">
                    <label for="headNameInput">Регистрационный номер</label>
                    <input class="form-control" type="text" name="cod_fiscal" id="reg_nrInput" value="<?=isset($_POST["cod_fiscal"])? $_POST["cod_fiscal"]:$row[cod_fiscal]?>">
                </div>

                <div class="form-group col-md-6 mr-6" style="display: none;">
                    <label for="emailInput">Date</label>
                    <input readonly class="btn btn-link next-call datepicker-here dp-holder" data-auto-close="true" type="text" id="dateInput" name="date" placeholder="дд.мм.гггг" autocomplete="off" value="<?=date("d.m.Y", $row["date"])?>" data-curval="<?=date("d.m.Y", $row["date"])?>" data-curid="<?=$row[id]?>">
                </div>

            </div>
            <input class="btn btn-secondary btn-sm" type="submit" name="edit" value="Сохранить">
        </form>
    </div>
     <script defer>

        document.getElementById('nameInput').addEventListener("awesomplete-select", function(event) {
            let extra = event.text.extraData;
            $('#contactNameInput').val(extra.contactName);
            $('#phoneInput').val(extra.phone);
            $('#countryInput').val(extra.country);
        });
        $(function(){
            autocompleteTag3();
            var postDate = $('#dateInput').val();
            var datepicker = $('#dateInput').datepicker().data('datepicker');
            if(postDate!=''){
                postDate = postDate.split(".");
                datepicker.selectDate(new Date(postDate[2],postDate[1]- 1,postDate[0]));
            }
            $('#directionsInput').tagsInput({'delimiter': [';'],'defaultText':'теги'});
        });

 


    </script>

    <script>

$("#ajax_form").submit(function(e) {

    e.preventDefault(); // avoid to execute the actual submit of the form.

    var form = $(this);
    var url = form.attr('action');

    $.ajax({ 
           type: "POST",
           url: '/lib/action_ajax_form.php',
           data: form.serialize(), // serializes the form's elements.
           success: function(data)
           {
               $("#result_form").html('Данные успешно сохранены!');
               $("#result_form").css("display","block");

           }
         });


});


$(".edit").click(function() {   
    $("#ajax_form").toggleClass("inactive");

    if ($("#ajax_form").hasClass("inactive")) {
     $('#ajax_form input').prop("disabled", true);
     $('#ajax_form textarea').prop("disabled", true);
     $('#ajax_form select').prop("disabled", true);
    } else { 
     $('#ajax_form input').prop("disabled", false);
     $('#ajax_form textarea').prop("disabled", false);
     $('#ajax_form select').prop("disabled", false);
     $('.edit').hide();
}
});


$("#ajax_form .btn-sm").click(function() {   
    $("#ajax_form").toggleClass("inactive");
    $('.edit').show();
});




$(function() {
         $('#ajax_form input').prop("disabled", true);
     $('#ajax_form textarea').prop("disabled", true);
     $('#ajax_form select').prop("disabled", true);
});


</script>
</div>

