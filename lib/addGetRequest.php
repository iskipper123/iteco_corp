<?php

    

    $db = DB::getObject();
    $ses_role = $_COOKIE[role];
    $ses_id = $_COOKIE[formid];

   

    $result_transport_dep = $db->getTransoprtDep($ses_id);

        while (($row_dep = $result_transport_dep->fetch_assoc()) != false) {


            $result_transport_dep_users = $db->getTransoprtUsers($row_dep[department]);


            while (($row_dep_users = $result_transport_dep_users->fetch_assoc()) != false) { 


                $result_transport_dep_users_requests = $db->getTransoprtUsers_requests($row_dep_users[id]);
                
                if ($result_transport_dep_users_requests[idUser] == '') {
                        $managers[] = $row_dep_users;
                        print_r($row_dep_users);
                } else {
                     $managers[] = $result_transport_dep_users_requests;
                }
                

            }

        } 

        print_r($managers);

        if ($managers[0][idUser] != '') {
           $idUserTransport = $managers[0][idUser];
        } else {
            $idUserTransport = $managers[0][id];
        }


 
        


    if(isset($_POST["add"])) {
        $dateCargoReady = $_POST["dateCargoReady"];
        $weight = $_POST["weight"];
        $weight_var = $_POST["weight_var"];
        $capacity = $_POST["capacity"];
        $capacity_var = $_POST["capacity_var"];
        $transportType = $_POST["transportType"];
        $info = $_POST["info"];
        $point1 = '';
        $point2 = '';      
        $point3 = $_POST["point3"];
        $point4 = $_POST["point4"];
        $price = $_POST["price"];
        $customer = $_POST["customer"];
        $cargo_status = $_POST["cargo_status"];


        
        if($ses_role == 1) $idUser = $_POST["idUser"];
        else $idUser = $ses_id;

  



        $error_dateCargoReady = "";
        $error_route = "";
        $error_weight = "";
        $error_capacity = "";
        $error_transportType = "";
        $error_info = "";
        $error_point3 = "";
        $error_point4 = "";
        $error_customer = "";
        $error_price = "";
        $error_idUser = "";
        $error = false;
    
        if(strlen($dateCargoReady) == 0) {
            $error_dateCargoReady = "Не заполнено поле";
            $error = true;
        }           
        if(strlen($weight) == 0) {
            $error_weight = "Не заполнено поле";
            $error = true;
        }       
        if(strlen($capacity) == 0) {
            $error_capacity = "Не заполнено поле";
            $error = true;
        }
        if(strlen($transportType) == 0) {
            $error_transportType = "Не заполнено поле";
            $error = true;
        }
        if(strlen($info) == 0) {
            $error_info = "Не заполнено поле";
            $error = true;
        }

        if(strlen($point3) == 0) {
            $error_point3 = "Не заполнено поле";
            $error = true;
        }
        if(strlen($point4) == 0) {
            $error_point4 = "Не заполнено поле";
            $error = true;
        }
        if(!is_numeric($price)) {
            $error_price = "Стоимость должна быть числом";
            $error = true;
        }
        if(strlen($price) == 0) {
            $error_price = "Не заполнено поле";
            $error = true;
        }
        if(strlen($idUser) == 0) {
            $error_idUser = "Не заполнено поле";
            $error = true;
        }
        if(strlen($customer) == 0) {
            $error_customer = "Не заполнено поле";
            $error = true;
        }
        //найти id заказчика
        $result_set7 = $db->getRowWhereWhereOrder("contractors", "name", $customer, "isDeleted", 0, "name");
        if($result_set7->num_rows == 0) {
            $error_customer = "Заказчик не найден";
            $error = true;
        } 
        else $row7 = $result_set7->fetch_assoc();


        if(!$error) { 
            $db->addGetRequest(strtotime($dateCargoReady), $row7[id], $weight, $weight_var, $capacity, $capacity_var, $transportType, $info, $point1, $point2, $point3, $point4, $price, $idUser, $idUserTransport, $cargo_status);
            
            $id = $db->getLastID("get_requests");   
            $db->addLog($row7[id], time(), "Приём заявок. Контрагент добавлен в качестве заказчика.", 3, $id, $_SESSION["id"]);         
            
            header("Location: getRequests.php?success");
            exit;
        }
    }
    else if(isset($_POST["cancel"])) {
        header("Location: getRequests.php");
        exit;       
    }
    
    $result_set2 = $db->getRowWhereOrder("users", "isDeleted", 0, "name");
    require_once '../partsOfPages/head.php';
    if($ses_role == 1)      require_once "../partsOfPages/menuForAdmin.php"; 
    else if($ses_role == 2) require_once "../partsOfPages/menuForUser.php"; ?>
    <div class="col-md-12 pt-3">
        <form name="" action="" method="post" enctype="multipart/form-data">
            <input class="btn btn-danger btn-sm" type="submit" name="cancel" value="Вернуться назад"> <br /> <br />
            <div class="row">
                <div class="form-group col-md-4 mr-4">
                    <label for="dateInput" class="required">Дата, когда готов груз</label>
                    <input class="form-control<?=isset($error_dateCargoReady)&&$error_dateCargoReady!=''?' is-invalid':''?> datepicker-here" data-auto-close="true" type="text" name="dateCargoReady" id="dateInput" placeholder="дд.мм.гггг" autocomplete="off" value="<?=$dateCargoReady?>">
                    <div class="invalid-feedback" <?=isset($error_dateCargoReady)&&$error_dateCargoReady!=''?'style="display:block;"':''?>><?=$error_dateCargoReady?></div>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="point3Input" class="required">Откуда</label>
                    <input class="form-control<?=isset($error_point3)&&$error_point3!=''?' is-invalid':''?>" type="text" name="point3" id="point3Input" autocomplete="off" value="<?=$_POST["point3"]?>">
                    <div class="invalid-feedback" <?=isset($error_point3)&&$error_point3!=''?'style="display:block;"':''?>><?=$error_point3 ?></div>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="point4Input" class="required">Куда</label>
                    <input class="form-control<?=isset($error_point4)&&$error_point4!=''?' is-invalid':''?>" type="text" name="point4" id="point4Input" autocomplete="off" value="<?=$_POST["point4"]?>">
                    <div class="invalid-feedback" <?=isset($error_point4)&&$error_point4!=''?'style="display:block;"':''?>><?=$error_point4 ?></div>
                </div>
                
                <div class="form-group col-md-4 mr-4">
                    <label for="weightInput" class="required">Вес</label>
                    <input class="form-control<?=isset($error_weight)&&$error_weight!=''?' is-invalid':''?>" type="text" name="weight" id="weightInput" autocomplete="off" value="<?=$_POST["weight"]?>">
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
                    <label for="capacityInput" class="required">Объем</label>
                    <input class="form-control<?=isset($error_capacity)&&$error_capacity!=''?' is-invalid':''?>" type="text" name="capacity" id="capacityInput" autocomplete="off" value="<?=$_POST["capacity"]?>">
                       <select class="form-control" name="capacity_var" id="capacity_var">
                        <? for($i = 0; $i < count($arrayOfv); $i++) {
                            if($row[capacity_var] == $arrayOfv[$i]) { ?>
                                <option selected="selected"><?=$arrayOfv[$i] ?></option> 
                            <?}
                            else {?> 
                                <option><?=$arrayOfv[$i] ?></option> 
                            <?}?>
                        <?}?>
                    </select>

                    <div class="invalid-feedback" <?=isset($error_capacity)&&$error_capacity!=''?'style="display:block;"':''?>><?=$error_capacity ?></div>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="currencyInput" class="required">Тип транспорта</label>
                    <select class="form-control<?=isset($error_transportType)&&$error_transportType!=''?' is-invalid':''?>" name="transportType" id="currencyInput">
                        <? if($transportType == "") { ?>
                            <option selected="selected"></option>
                        <?}
                        else { ?>
                            <option selected="selected"> <?=$transportType ?> </option>
                        <?}?>
                        <? for($i = 0; $i < count($arrayOfTransportType); $i++) {
                            if($transportType != $arrayOfTransportType[$i]) { ?>
                                <option><?=$arrayOfTransportType[$i] ?></option>
                            <?}?>
                        <?}?>
                    </select>
                    <div class="invalid-feedback" <?=isset($error_transportType)&&$error_transportType!=''?'style="display:block;"':''?>><?=$error_transportType ?></div>
                </div>



                <div class="form-group col-md-4 mr-4">
                    <label for="priceInput" class="required">Стоимость перевозки</label>
                    <input class="form-control<?=isset($error_price)&&$error_price!=''?' is-invalid':''?>" type="text" name="price" id="priceInput" autocomplete="off" value="<?=$_POST["price"]?>">
                    <div class="invalid-feedback" <?=isset($error_price)&&$error_price!=''?'style="display:block;"':''?>><?=$error_price ?></div>
                </div>

                 <div class="form-group col-md-4 mr-4">
                    <label for="tag" class="required">Выберите заказчика</label>
                    
                    <div class="invalid-feedback" <?=isset($error_customer)&&$error_customer!=''?'style="display:block;"':''?>><?=$error_customer ?></div>
    
            <input class="form-control<?=isset($error_customer)&&$error_customer!=''?' is-invalid':''?>" type="text" name="customer" id="tag" autocomplete="off" value="<?=$_POST["customer"]?>">
            
            <a class="btn btn-secondary btn-sm" id="checkBlacklistButton" type="submit" name="blacklist" value="valueToCheck">Проверка на черный список
            </a>
            <div id="messageContainer" style="display: none;" >
            <p id="messageText"></p>
</div>
                </div>

                
                <? if($ses_role == 1) {?>
                <div class="form-group col-md-4 mr-4">
                    <label for="idUserInput" class="required">Менеджер</label>
                    <select class="form-control<?=isset($error_idUser)&&$error_idUser!=''?' is-invalid':''?>" name="idUser" id="idUserInput">
                        <? if($idUser == "") { ?>
                            <option selected="selected"></option>
                        <?}
                        else {
                            $result_set1 = $db->getRowWhereOrder("users", "id", $_POST["idUser"], "name");
                            $row1 = $result_set1->fetch_assoc(); ?>
                            <option selected="selected" value="<?=$_POST["idUser"]?>"><?=$row1["name"]?></option>
                        <?}?>
                        <? while (($row2 = $result_set2->fetch_assoc()) != false) { ?>
                            <? if($row1["name"] != $row2[name]) {?>
                                <option value="<?=$row2[id]?>"><?=$row2[name]?></option>
                            <?}?>
                        <?}?>
                    </select>
                    <div class="invalid-feedback" <?=isset($error_idUser)&&$error_idUser!=''?'style="display:block;"':''?>><?=$error_idUser ?></div>
                </div>
                <?}?>

                    <div class="form-group col-md-4 mr-4">  
                      <label for="infoInput" class="required">Тип груза</label>
                    <select class="form-control form-control-sm" name="info">
                        <option selected value="0">Выберите Тип груза</option>
                        <? for($i = 0; $i < count($array_of_type_cargo); $i++) {?>
                                <option selected="selected"><?=$array_of_type_cargo[$i] ?></option>
                        <?}?>
                    </select>
                </div>  

                <div class="form-group col-md-4 mr-4">  
                      <label for="infoInput" class="required">Статус груза</label>
                    <select class="form-control form-control-sm" name="cargo_status">
                        <option selected value="0">Выберите Статус груза</option>
                        <? for($i = 0; $i < count($array_of_cargo_status); $i++) {?>
                                <option selected="selected"><?=$array_of_cargo_status[$i] ?></option>
                            <?}?>
                    </select>
                </div>  

            </div>
            <input class="btn btn-secondary btn-sm" type="submit" name="add" value="Добавить">
            <input class="btn btn-danger btn-sm" type="submit" name="cancel" value="Отменить">
        </form>
    </div>
    
    <script defer>
            $(document).ready(function() {
                $("#checkBlacklistButton").click(function() {
                    var valueToCheck = $("#tag").val();
                    
                    $.ajax({
                        url: "../lib/check_blacklist.php",
                        type: "POST",
                        data: { value: valueToCheck },
                        cache: false,
                        success: function(response) {
                            var responseArray = response.split(':');
                            var status = responseArray[0];
                            var name = responseArray[1];
                            var reason = responseArray[2];

                            var messageContainer = $("#messageContainer ");
                            var messageText = $("#messageText");

                            if (status === "found") {
                                messageText.text("Компания найдена в черном списке!" + '\n' + "Мотив: " + reason);
                            } else {
                                messageText.text("Все в порядке!");
                            }

                            messageContainer.show(); // Afișați containerul mesajului
                        }
                    });
                });
            });


        function autocompleteTag(){
            var ajax = new XMLHttpRequest();
            ajax.open("GET", "../lib/autocomplete.php", true);
            ajax.onload = function () {
                var list = JSON.parse(ajax.responseText);
                new Awesomplete(document.querySelector("#tag"), {minChars: 1, list: list});
            };
            ajax.send();
        }
        function autocompleteTag1(){
            var ajax = new XMLHttpRequest();
            ajax.open("GET", "../lib/autocomplete1.php", true);
            ajax.onload = function () {
                var list = JSON.parse(ajax.responseText);
                new Awesomplete(document.querySelector("#tag1"), {minChars: 1, list: list});
            };
            ajax.send();
        }
        function autocompletefromInput(){
            var awesomplete = new Awesomplete(document.querySelector("#point3Input"), {minChars: 3,autoFirst: true});
            $("#point3Input").on("keyup", function(){
                let thisval = $(this).val();
                if(thisval.length>=3){
                    $.ajax({
                        url: 'https://geocode-maps.yandex.ru/1.x/?format=json&apikey=42f4289f-31ea-4a01-9939-e6785142a533&geocode=' + thisval,
                        type: 'GET',
                        dataType: 'json',
                        success: function (data) { 
                            var list = [];
                            for(var i = 0; i < data.response.GeoObjectCollection.featureMember.length; i++) {
                                var kind = data.response.GeoObjectCollection.featureMember[i].GeoObject.metaDataProperty.GeocoderMetaData.kind;
                                if(kind!='locality'&&kind!='province'&&kind!='country'){
                                    continue;
                                }else{
                                    //записываем в массив результаты, которые возвращает нам геокодер
                                    list.push(data.response.GeoObjectCollection.featureMember[i].GeoObject.name+', '+data.response.GeoObjectCollection.featureMember[i].GeoObject.metaDataProperty.GeocoderMetaData.AddressDetails.Country.CountryName);
                                }
                            }
                            awesomplete.list = list;
                        }
                    });
                }
            });
        }
        function autocompleteToInput(){
            var awesomplete = new Awesomplete(document.querySelector("#point4Input"), {minChars: 3,autoFirst: true});
            $("#point4Input").on("keyup", function(){
                let thisval = $(this).val();
                if(thisval.length>=3){
                    $.ajax({
                        url: 'https://geocode-maps.yandex.ru/1.x/?format=json&apikey=42f4289f-31ea-4a01-9939-e6785142a533&geocode=' + thisval,
                        type: 'GET',
                        dataType: 'json',
                        success: function (data) { 
                            var list = [];
                            for(var i = 0; i < data.response.GeoObjectCollection.featureMember.length; i++) {
                                var kind = data.response.GeoObjectCollection.featureMember[i].GeoObject.metaDataProperty.GeocoderMetaData.kind;
                                if(kind!='locality'&&kind!='province'&&kind!='country'){
                                    continue;
                                }else{
                                    //записываем в массив результаты, которые возвращает нам геокодер
                                    list.push(data.response.GeoObjectCollection.featureMember[i].GeoObject.name+', '+data.response.GeoObjectCollection.featureMember[i].GeoObject.metaDataProperty.GeocoderMetaData.AddressDetails.Country.CountryName);
                                }
                            }
                            awesomplete.list = list;
                        }
                    });
                }
            });
        }
     
        
        $(function(){
            autocompleteTag();
            autocompleteTag1();
            autocompletefromInput();
            autocompleteToInput();
            var postDate = $('#dateInput').val();
            var datepicker = $('#dateInput').datepicker().data('datepicker');
            if(postDate!=''){
                postDate = postDate.split(".");
                datepicker.selectDate(new Date(postDate[2],postDate[1]- 1,postDate[0]));
            }
        });
    </script>
<?  require_once '../partsOfPages/footer.php';?>
