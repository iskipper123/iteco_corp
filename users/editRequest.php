<?php

	require_once "../lib/db.php";
    require_once "../lib/vars.php";
    
    $title = 'Редактирование заявки';
	
	$db = DB::getObject();

	if(isset($_GET["edit"])) {
		$id = $_GET["edit"]; 
				
		$result_set = $db->getRowWhere("requests", "id", $id);
		$row = $result_set->fetch_assoc();
		
		$result_set11 = $db->getRowWhere("contractors", "id", $row[customer]);
		$row11 = $result_set11->fetch_assoc();
		$customer = $row11[name];
		
		$result_set12 = $db->getRowWhere("contractors", "id", $row[carrier]);
		$row12 = $result_set12->fetch_assoc();
		$carrier = $row12[name];
		
		$date = date("d.m.Y", $row["date"]);
		
		$isCurrencyPayment = $row[isCurrencyPayment];
		$comision_static = $row[comision_static];
		$currency = $row[currency];
		
		$idUser = $row[idUser];
		$currency = $row[currency];
		$transportType = $row[transportType];
		$dateShipping = date("d.m.Y", $row[dateShipping]);
	}
	
	if(isset($_POST["add"])) {
		// echo "+++";
		$date = $_POST["date"];
		$customer = $_POST["customer"];
		// echo "customer: ".$customer."<br>";
		$carrier = $_POST["carrier"];
		// $number = $_POST["number"];
		$from = $_POST["from"];
        $to = $_POST["to"];
		$cargo = $_POST["cargo"];
		$carNumber = $_POST["carNumber"];
		$fio = $_POST["fio"];
		$customerPrice = $_POST["customerPrice"];
		$carrierPrice = $_POST["carrierPrice"];
		$idUser = $_POST["idUser"];
		$currency = $_POST["currency"];
		$isCurrencyPayment = $_POST["isCurrencyPayment"];
		$comision_static = $_POST["comision_static"];
		
		$dateShipping = $_POST["dateShipping"];
		$time = $_POST["time"];
		$term = $_POST["term"];
		$address1 = $_POST["address1"];
		$address2 = $_POST["address2"];
		$transportType = $_POST["transportType"];
		$contactName1 = $_POST["contactName1"];
		$contactName2 = $_POST["contactName2"];
		$route = $_POST["route"];
		$temperature = $_POST["temperature"];
		$customs1 = $_POST["customs1"];
		$customs2 = $_POST["customs2"];
		$contactName3 = $_POST["contactName3"];
		$broker = $_POST["broker"];
		$pogran = $_POST["pogran"];
		$driverPhones = $_POST["driverPhones"];
		$sumForCustomer = $_POST["sumForCustomer"];
		$sumForCarrier = $_POST["sumForCarrier"];
		
		$weight = $_POST["weight"];
		$weight_var = $_POST["weight_var"];
		$v = $_POST["v"];
		$v_var = $_POST["v_var"];
		$pay_variant = $_POST["pay_variant"];
		$languages = $_POST["languages"];
		$partener = $_POST["partener"];
		$customs = $_POST["customs"];
		
		$error_customer = "";
		$error_carrier = "";
		// $error_number = "";
		$error_date = "";
		$error_from = "";
        $error_to = "";
		$error_cargo = "";
		$error_carNumber = "";
		$error_fio = "";
		$error_customerPrice = "";
		$error_carrierPrice = "";		
		$error_partener = "";		

		$error = false;
		
		if(strlen($customer) == 0) {
			$error_customer = "Не заполнено поле";
			$error = true;
		}		
		if(strlen($carrier) == 0) {
			$error_carrier = "Не заполнено поле";
			$error = true;
		}
		// if(strlen($number) == 0) {
			// $error_number = "Не заполнено поле";
			// $error = true;
		// }
		if(strlen($date) == 0) {
			$error_date = "Не заполнено поле";
			$error = true;
		}
		if(strlen($from) == 0) {
			$error_from = "Не заполнено поле";
			$error = true;
        }
        if(strlen($to) == 0) {
			$error_to = "Не заполнено поле";
			$error = true;
		}
		if(strlen($cargo) == 0) {
			$error_cargo = "Не заполнено поле";
			$error = true;
		}
		if(strlen($carNumber) == 0) {
			$error_carNumber = "Не заполнено поле";
			$error = true;
		}
		if(strlen($fio) == 0) {
			$error_fio = "Не заполнено поле";
			$error = true;
		}
		if(!is_numeric($customerPrice)) {
			$error_customerPrice = "Сумма должна быть числом";
			$error = true;
		}
		if(strlen($customerPrice) == 0) {
			$error_customerPrice = "Не заполнено поле";
			$error = true;
		}
		if(!is_numeric($carrierPrice)) {
			$error_carrierPrice = "Сумма должна быть числом";
			$error = true;
		}
		if(strlen($carrierPrice) == 0) {
			$error_carrierPrice = "Не заполнено поле";
			$error = true;
		}

		//найти id заказчика
		$customer = htmlspecialchars($customer, ENT_QUOTES);
		if(isset($_POST["customer"])) $_POST["customer"] = htmlspecialchars($_POST["customer"], ENT_QUOTES);
		// echo "customer: ".$customer."<br>";
		$result_set7 = $db->getRowWhereWhereOrder("contractors", "name", $customer, "isDeleted", 0, "name");
		if($result_set7->num_rows == 0) {
			$error_customer = "Заказчик не найден";
			$error = true;
		}
		else $row7 = $result_set7->fetch_assoc();
		
		//найти id перевозчика
		$carrier = htmlspecialchars($carrier, ENT_QUOTES);
		if(isset($_POST["carrier"])) $_POST["carrier"] = htmlspecialchars($_POST["carrier"], ENT_QUOTES);
		$result_set8 = $db->getRowWhereWhereOrder("contractors", "name", $carrier, "isDeleted", 0, "name");
		if($result_set8->num_rows == 0) {
			$error_carrier = "Перевозчик не найден";
			$error = true;
		}
		else $row8 = $result_set8->fetch_assoc();
		
		if(strlen($dateShipping) == 0) {
			$error_dateShipping = "Не заполнено поле";
			$error = true;
		}
		if(strlen($time) == 0) {
			$error_time = "Не заполнено поле";
			$error = true;
		}
		if(strlen($term) == 0) {
			$error_term = "Не заполнено поле";
			$error = true;
		}
		if(strlen($address1) == 0) {
			$error_address1 = "Не заполнено поле";
			$error = true;
		}
		if(strlen($address2) == 0) {
			$error_address2 = "Не заполнено поле";
			$error = true;
		}
		if(strlen($transportType) == 0) {
			$error_transportType = "Не заполнено поле";
			$error = true;
		}
		// if(strlen($contactName1) == 0) {
			// $error_contactName1 = "Не заполнено поле";
			// $error = true;
		// }
		if(strlen($contactName2) == 0) {
			$error_contactName2 = "Не заполнено поле";
			$error = true;
		}
		if(strlen($route) == 0) {
			$error_route = "Не заполнено поле";
			$error = true;
		}
		if(strlen($temperature) == 0) {
			$error_temperature = "Не заполнено поле";
			$error = true;
		}
		if(strlen($customs1) == 0) {
			$error_customs1 = "Не заполнено поле";
			$error = true;
		}
		if(strlen($customs2) == 0) {
			$error_customs2 = "Не заполнено поле";
			$error = true;
		}
		if(strlen($contactName3) == 0) {
			$error_contactName3 = "Не заполнено поле";
			$error = true;
		}
		if(strlen($broker) == 0) {
			$error_broker = "Не заполнено поле";
			$error = true;
		}
		if(strlen($pogran) == 0) {
			$error_pogran = "Не заполнено поле";
			$error = true;
		}
		if(strlen($driverPhones) == 0) {
			$error_driverPhones = "Не заполнено поле";
			$error = true;
		}
		if(strlen($sumForCustomer) == 0) {
			$error_sumForCustomer = "Не заполнено поле";
			$error = true;
		}
		if(strlen($sumForCarrier) == 0) {
			$error_sumForCarrier = "Не заполнено поле";
			$error = true;
		}
		
		if(strlen($weight) == 0) {
			$error_weight = "Не заполнено поле";
			$error = true;
		}
		if(strlen($v) == 0) {
			$error_v = "Не заполнено поле";
			$error = true;
		}		

		if(strlen($customs) == 0) {
			$error_customs = "Не заполнено поле";
			$error = true;
		}
		 
		if(!$error) {

			
			$number = $row[number];
			$db->editRequest($id, $row7[id], $row8[id], $number, strtotime($date), $cargo, $carNumber, $fio, $customerPrice, $carrierPrice, 
								$idUser, $isCurrencyPayment, $comision_static, $currency, $from, $to,
								strtotime($dateShipping), $time, $term, $address1, $address2, $transportType, $contactName1, $contactName2, $route, $temperature, 
								$customs1, $customs2, $broker, $pogran, $driverPhones, $sumForCustomer, $sumForCarrier, $weight, $weight_var, $v, $v_var, $pay_variant, $languages, $partener, $customs);

			$db->addLog($row7[id], time(), "Заявки. Контрагент добавлен в качестве заказчика. Заявка №$number", 2, $id, $_SESSION["id"]);
			$db->addLog($row8[id], time(), "Заявки. Контрагент добавлен в качестве перевозчика. Заявка №$number", 2, $id, $_SESSION["id"]);


			$db->editCustomsClearance_number($row7[id], $row8[id], $number);

			$db->addLog($row7[id], time(), "Растаможка. Контрагент добавлен в качестве заказчика. Заявка №$number", 1, $idNewFile, $_SESSION["id"]);
			$db->addLog($row8[id], time(), "Растаможка. Контрагент добавлен в качестве перевозчика. Заявка №$number", 1, $idNewFile, $_SESSION["id"]);

			$db->editPayment_number_customer($row7[id], $number, $row7[contractorsType]);
			$db->editPayment_number_carrier($row8[id], $number, $row8[contractorsType]);

			header("Location: requests.php?success");
			exit;
		}
	}
	else if(isset($_POST["cancel"])) {
		header("Location: requests.php");
		exit;		
	}
	
	$result_set2 = $db->getRowWhereOrder("users", "isDeleted", 0, "name");
    require_once '../partsOfPages/head.php';
    require_once "../partsOfPages/menuForAdmin.php"; ?>
    <div class="col-md-12 pt-3">
    	<h1>Редактирование заявки</h1>
		<? require_once "../lib/requests.php"; ?>
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
            var awesomplete = new Awesomplete(document.querySelector("#fromInput"), {minChars: 3,autoFirst: true});
            $("#fromInput").on("keyup", function(){
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
            var awesomplete = new Awesomplete(document.querySelector("#toInput"), {minChars: 3,autoFirst: true});
            $("#toInput").on("keyup", function(){
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