<?php
	$db = DB::getObject();

	if(isset($_GET["edit"])) {
		$id = $_GET["edit"];
				
		$result_set = $db->getRowWhere("get_requests", "id", $id);
		$row = $result_set->fetch_assoc();
		
		$result_set11 = $db->getRowWhere("contractors", "id", $row[customer]);
		$row11 = $result_set11->fetch_assoc();
		$customer = $row11[name];
		$transportType = $row[transportType];
		
		$dateCargoReady = date("d.m.Y", $row["dateCargoReady"]);
	}
	
	if(isset($_POST["edit"])) {
		$dateCargoReady = $_POST["dateCargoReady"];
		$weight = $_POST["weight"];
        $weight_var = $_POST["weight_var"];
		$capacity = $_POST["capacity"];
        $capacity_var = $_POST["capacity_var"];
		$transportType = $_POST["transportType"];
		$info = $_POST["info"];
		$point1 = $_POST["point1"];
		$point2 = $_POST["point2"];
		$point3 = $_POST["point3"];
		$point4 = $_POST["point4"];
		$price = $_POST["price"];
		$customer = $_POST["customer"];
		if($_SESSION[role] == 1) $idUser = $_POST["idUser"];
		else $idUser = $_SESSION["id"];

		$error_route = "";
		$error_weight = "";
		$error_capacity = "";
		$error_transportType = "";
		$error_info = "";
		$error_point1 = "";
		$error_point2 = "";
		$error_point3 = "";
		$error_point4 = "";
		$error_price = "";
		$error_customer = "";
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
/*		if(strlen($point1) == 0) {
			$error_point1 = "Не заполнено поле";
			$error = true;
		}
		if(strlen($point2) == 0) {
			$error_point2 = "Не заполнено поле";
			$error = true;
		}*/
		if(!is_numeric($price)) {
			$error_price = "Стоимость должна быть числом";
			$error = true;
		}
		if(strlen($price) == 0) {
			$error_price = "Не заполнено поле";
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
			$db->editGetRequest($id, $row7[id], $weight, $weight_var, $capacity, $capacity_var, $transportType, $info, '', '', $point3, $point4, $price, $idUser, strtotime($dateCargoReady)); 
							
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
    if($_SESSION["userType"] == 1) 		require_once "../partsOfPages/menuForAdmin.php"; 
    else if($_SESSION["userType"] == 2) require_once "../partsOfPages/menuForUser.php"; ?>
    <div class="col-md-12 pt-3">
        <form name="" action="" method="post" enctype="multipart/form-data">
            <input class="btn btn-danger btn-sm" type="submit" name="cancel" value="Вернуться назад"> <br /> <br />
            <div class="row">
                <div class="form-group col-md-4 mr-4">
                    <label for="dateInput" class="required">Дата, когда готов груз</label>
                    <input class="form-control<?=isset($error_dateCargoReady)&&$error_dateCargoReady!=''?' is-invalid':''?> datepicker-here" data-auto-close="true" type="text" name="dateCargoReady" id="dateInput" placeholder="дд.мм.гггг" autocomplete="off" value="<?=isset($_POST["dateCargoReady"])? $_POST["dateCargoReady"]:$dateCargoReady?>">
                    <div class="invalid-feedback" <?=isset($error_dateCargoReady)&&$error_dateCargoReady!=''?'style="display:block;"':''?>><?=$error_dateCargoReady ?></div>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="point3Input" class="required">Откуда</label>
                    <input class="form-control<?=isset($error_point3)&&$error_point3!=''?' is-invalid':''?>" type="text" name="point3" id="point3Input" autocomplete="off" value="<?=isset($_POST["point3"])? $_POST["point3"]:$row[point3]?>">
                    <div class="invalid-feedback" <?=isset($error_point3)&&$error_point3!=''?'style="display:block;"':''?>><?=$error_point3 ?></div>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="point4Input" class="required">Куда</label>
                    <input class="form-control<?=isset($error_point4)&&$error_point4!=''?' is-invalid':''?>" type="text" name="point4" id="point4Input" autocomplete="off" value="<?=isset($_POST["point4"])? $_POST["point4"]:$row[point4]?>">
                    <div class="invalid-feedback" <?=isset($error_point4)&&$error_point4!=''?'style="display:block;"':''?>><?=$error_point4 ?></div>
                </div>
                
                <div class="form-group col-md-4 mr-4">
                    <label for="weightInput" class="required">Вес</label>
                    <input class="form-control<?=isset($error_weight)&&$error_weight!=''?' is-invalid':''?>" type="text" name="weight" id="weightInput" autocomplete="off" value="<?=isset($_POST["weight"])? $_POST["weight"]:$row[weight]?>">
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
                    <input class="form-control<?=isset($error_capacity)&&$error_capacity!=''?' is-invalid':''?>" type="text" name="capacity" id="capacityInput" autocomplete="off" value="<?=isset($_POST["capacity"])? $_POST["capacity"]:$row[capacity]?>">
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
                    <label for="categoryInput" class="required">Тип транспорта</label>
                    <select class="form-control<?=isset($error_transportType)&&$error_transportType!=''?' is-invalid':''?>" name="transportType" id="categoryInput">
                        <? for($i = 0; $i < count($arrayOfTransportType); $i++) {
                            if($transportType == $arrayOfTransportType[$i]) { ?>
                                <option selected="selected"><?=$arrayOfTransportType[$i] ?></option>
                            <?}
                            else {?>
                                <option><?=$arrayOfTransportType[$i] ?></option>
                            <?}?>
                        <?}?>
                    </select>
                    <div class="invalid-feedback" <?=isset($error_transportType)&&$error_transportType!=''?'style="display:block;"':''?>><?=$error_transportType ?></div>
                </div>
<!--                 
                <div class="form-group col-md-4 mr-4">
                    <label for="point1Input" class="required">Где происходит затаможка груза</label>
                    <input class="form-control<?=isset($error_point1)&&$error_point1!=''?' is-invalid':''?>" type="text" name="point1" id="point1Input" autocomplete="off" value="<?=isset($_POST["point1"])? $_POST["point1"]:$row[point1]?>">
                    <div class="invalid-feedback" <?=isset($error_point1)&&$error_point1!=''?'style="display:block;"':''?>><?=$error_point1 ?></div>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="point2Input" class="required">Где происходит растаможка груза</label>
                    <input class="form-control<?=isset($error_point2)&&$error_point2!=''?' is-invalid':''?>" type="text" name="point2" id="point2Input" autocomplete="off" value="<?=isset($_POST["point2"])? $_POST["point2"]:$row[point2]?>">
                    <div class="invalid-feedback" <?=isset($error_point2)&&$error_point2!=''?'style="display:block;"':''?>><?=$error_point2 ?></div>
                </div> -->
                <div class="form-group col-md-4 mr-4">
                    <label for="priceInput" class="required">Стоимость перевозки</label>
                    <input class="form-control<?=isset($error_price)&&$error_price!=''?' is-invalid':''?>" type="text" name="price" id="priceInput" autocomplete="off" value="<?=isset($_POST["price"])? $_POST["price"]:$row[price]?>">
                    <div class="invalid-feedback" <?=isset($error_price)&&$error_price!=''?'style="display:block;"':''?>><?=$error_price ?></div>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="tag" class="required">Выберите заказчика</label>
                    <input class="form-control<?=isset($error_customer)&&$error_customer!=''?' is-invalid':''?>" type="text" name="customer" id="tag" autocomplete="off" value="<?=isset($_POST["customer"])? $_POST["customer"]:$customer?>">
                    <div class="invalid-feedback" <?=isset($error_customer)&&$error_customer!=''?'style="display:block;"':''?>><?=$error_customer ?></div>
                </div>
                <? if($_SESSION[role] == 1) {?>
                <div class="form-group col-md-4 mr-4">
                    <label for="idUserInput" class="required">Менеджер</label>
                    <select class="form-control<?=isset($error_idUser)&&$error_idUser!=''?' is-invalid':''?>" name="idUser" id="idUserInput">
                        <? while (($row2 = $result_set2->fetch_assoc()) != false) { ?>
                            <?  if($row2[id] == $row[idUser]) {?>
                                    <option selected="selected" value="<?=$row2[id]?>"><?=$row2[name]?></option>
                            <?}
                            else {?>
                                <option value="<?=$row2[id]?>"><?=$row2[name]?></option>
                            <?}?>
                        <?}?>
                    </select>
                    <div class="invalid-feedback" <?=isset($error_idUser)&&$error_idUser!=''?'style="display:block;"':''?>><?=$error_idUser ?></div>
                </div>
                <?}?>
                <div class="form-group col-md-4 mr-4">
                    <label for="infoInput" class="required">Информация по грузу</label>
                    <textarea class="form-control<?=isset($error_info)&&$error_info!=''?' is-invalid':''?>" name="info" id="infoInput" ><?=isset($_POST["info"])? $_POST["info"]:$row[info]?></textarea>
                    <div class="invalid-feedback" <?=isset($error_info)&&$error_info!=''?'style="display:block;"':''?>><?=$error_info ?></div>
                </div>
            </div>
            <input class="btn btn-secondary btn-sm" type="submit" name="edit" value="Изменить">
            <input class="btn btn-danger btn-sm" type="submit" name="cancel" value="Отменить">
        </form>
    </div>
    <script defer>
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