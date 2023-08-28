<?php

    require_once "../lib/db.php";
    require_once "../lib/vars.php"; 
     
    $title = 'Редактирование клиента';  
     
    $db = DB::getObject();
 
    if(isset($_GET["edit"])) {
        $id = $_GET["edit"]; 
                
        $result_set = $db->getRowWhere("clients", "id", $id);
        $row = $result_set->fetch_assoc();
        
        $date = date("d.m.Y", $row["date"]);

        $result_set_tags = $db->getRowWhereOrder("tags_clients", "idClient", $row[id], "tag");
        while(($row_tag = $result_set_tags->fetch_assoc()) != false) {
            $countryTags[] = $row_tag[tag];
        }
        if(count($countryTags)>0){
            $implodedCtags = implode(";", $countryTags);
        }else{
            $implodedCtags = '';
        }
        
        $isMarked = $row[isMarked];
    }
    
  
    if(isset($_POST["edit"])) {
        $contactName = $_POST["contactName"];
        $company_form = $_POST["company_form"];
        $comments = $_POST["comments"];
        $date = $_POST["date"];
        $name = $_POST["name"];
        $idUser = $_POST["idUser"];
        $countryTags = explode(';',$_POST["directions"]); //массив
        $phone = $_POST["phone"];
        $isMarked = $_POST["isMarked"];
        
        $error_name = "";
        $error_contactName = "";
        $error_date = "";
        $error = false;
        
        if(strlen($name) == 0) {
            $error_name = "Не заполнено поле";
            $error = true;
        }       
        if(strlen($contactName) == 0) {
            $error_contactName = "Не заполнено поле";
            $error = true;
        }
        if(strlen($date) == 0) {
            $error_date = "Не заполнено поле";
            $error = true;
        }

        if(!$error) {
            $name = htmlspecialchars($name, ENT_QUOTES);
            $db->editClient($id, $name, $company_form, $contactName, $comments, strtotime($date), $idUser, $phone, $country, $isMarked);

            //создано для тегов, 3.04.2019
            $db->deleteWhere("tags_clients", "idClient", $id);
            
            for($i = 0; $i < count($countryTags); $i++) {
                $db->addTagToClient($id, $countryTags[$i]);
            }
            
            header("Location: clients.php?success");
            exit;   
        }
    }
    else if(isset($_POST["cancel"])) {
        header("Location: clients.php");
        exit;       
    }
    
    $result_set2 = $db->getRowWhereOrder("users", "isDeleted", 0, "name");
    require_once '../partsOfPages/head.php';
    require_once "../partsOfPages/menuForAdmin.php"; ?>
    <div class="col-md-12 pt-3">
        <form name="" action="" method="post">
            <input class="btn btn-danger btn-sm" type="submit" name="cancel" value="Вернуться назад"> <br /> <br />
            <div class="row">
                <div class="form-group col-md-4 mr-4">
                    <label for="nameInput" class="required">Название компании</label>
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
                    <label for="contactNameInput" class="required">Контактное лицо</label>
                    <input class="form-control<?=isset($error_contactName)&&$error_contactName!=''?' is-invalid':''?>" type="text" name="contactName" id="contactNameInput" value="<?=isset($_POST["contactName"])? $_POST["contactName"]:$row[contactName]?>">
                    <div class="invalid-feedback" <?=isset($error_contactName)&&$error_contactName!=''?'style="display:block;"':''?>><?=$error_contactName ?></div>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="dateInput" class="required">Дата следующего контакта</label>
                    <input class="form-control<?=isset($error_date)&&$error_date!=''?' is-invalid':''?> datepicker-here" data-auto-close="true" type="text" name="date" id="dateInput" placeholder="дд.мм.гггг" autocomplete="off" value="<?=isset($_POST["date"])? $_POST["date"]:$date?>">
                    <div class="invalid-feedback" <?=isset($error_date)&&$error_date!=''?'style="display:block;"':''?>><?=$error_date ?></div>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="commentsInput">Комментарий по грузу/Компании</label>
                    <textarea class="form-control" name="comments" id="commentsInput" ><?=isset($_POST["comments"])? $_POST["comments"]:$row[comments]?></textarea>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="idUserInput" class="required">Выберите сотрудника</label>
                    <select class="form-control<?=isset($error_idUser)&&$error_idUser!=''?' is-invalid':''?>" name="idUser" id="idUserInput">
                        <? while (($row2 = $result_set2->fetch_assoc()) != false) { ?>
                            <?  if($row2[id] == $row[idManager]) {?>
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
                    <label for="phoneInput">Телефон</label>
                    <input class="form-control" type="text" name="phone" id="phoneInput" value="<?=isset($_POST["phone"])? $_POST["phone"]:$row[phone]?>">
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="directionsInput" class="required">Направления</label>
                    <input class="form-control<?=isset($error_directions)&&$error_directions!=''?' is-invalid':''?>" type="text" name="directions" id="directionsInput" value="<?=isset($_POST["directions"])? $_POST["directions"]:$implodedCtags?>">
                    <div class="invalid-feedback" <?=isset($error_directions)&&$error_directions!=''?'style="display:block;"':''?>><?=$error_directions?></div>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <? if($isMarked == 1) {?>
                        <input type="checkbox" checked name="isMarked" value="1"> <b>Метка</b> <br />
                    <?} else {?>
                        <input type="checkbox" name="isMarked" value="1"> <b>Метка</b> <br />
                    <?}?>
                </div>
            </div>
            <input class="btn btn-secondary btn-sm" type="submit" name="edit" value="Изменить">
            <input class="btn btn-danger btn-sm" type="submit" name="cancel" value="Отменить">
        </form>
    </div>
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
<?  require_once '../partsOfPages/footer.php';?>