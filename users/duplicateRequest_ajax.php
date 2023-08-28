<?php
	require_once "../lib/db.php";
    require_once "../lib/vars.php";

	$db = DB::getObject(); 
	$status = 'err';
	if(!empty($_POST['date'])) {
		$date = $_POST["date"];
		$customer = $_POST["customer"];
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
		$isCurrencyPayment = $_POST["isCurrencyPayment"];
		$comision_static = $_POST["comision_static"];
		$currency = $_POST["currency"];
				
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
		$broker = $_POST["broker"];
		$pogran = $_POST["pogran"];
		$driverPhones = $_POST["driverPhones"];
		$sumForCustomer = $_POST["sumForCustomer"];
		$sumForCarrier = $_POST["sumForCarrier"];
		
		$weight = $_POST["weight"];
		$weight_var = $_POST["weight_var"];
		$v = $_POST["v"];
		$v_var = $_POST["v_var"];
		
		$error_customer = "";
		$error_carrier = "";
		$error_number = "";
		$error_date = "";
        $error_from = "";
        $error_to = "";
		$error_cargo = "";
		$error_carNumber = "";
		$error_fio = "";
		$error_customerPrice = "";
		$error_carrierPrice = "";
		$error_idUser = "";
		$error_currency = "";
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
		if(strlen($idUser) == 0) {
			$error_idUser = "Не заполнено поле";
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
		if(strlen($currency) == 0) {
			$error_currency = "Не заполнено поле";
			$error = true;
		}

		//найти id заказчика
		$result_set7 = $db->getRowWhereWhereOrder("contractors", "name", $customer, "contractorsType", 'Заказчик', "name");
		if($result_set7->num_rows == 0) {
			$error_customer = "Заказчик не найден";
			$error = true;
		}
		else $row7 = $result_set7->fetch_assoc();
		
		//найти id перевозчика 
		$result_set8 = $db->getRowWhereWhereOrder("contractors", "name", $carrier, "contractorsType", 'Перевозчик', "name");
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


		if(!$error) {
			$number = $db->getLastNumber();
			$number++;
			
			$db->addRequest($row7[id], $row8[id], $number, strtotime($date), $cargo, $carNumber, $fio, $customerPrice, $carrierPrice, 
								$idUser, $isCurrencyPayment, $comision_static, $currency, $from, $to,
								strtotime($dateShipping), $time, $term, $address1, $address2, $transportType, $contactName1, $contactName2, $route, $temperature, 
								$customs1, $customs2, $broker, $pogran, $driverPhones, $sumForCustomer, $sumForCarrier, $weight, $weight_var, $v, $v_var, $pay_variant, $languages, $partener, $customs);
			
			$id = $db->getLastID("requests");				 
			$db->addLog($row7[id], time(), "Заявки. Контрагент добавлен в качестве заказчика. Заявка №$number", 2, $id, $_SESSION["id"]);
			$db->addLog($row8[id], time(), "Заявки. Контрагент добавлен в качестве перевозчика. Заявка №$number", 2, $id, $_SESSION["id"]);
				
			//все добавленные заявки дублировать в растаможку
			$idNewFile = $db->getLastID("customs_clearance");
			$idNewFile++;
			$db->addCustomsClearance($idNewFile, $row7[id], $row8[id], $number, "", $carrierPrice, "", "");
			$db->addLog($row7[id], time(), "Растаможка. Контрагент добавлен в качестве заказчика. Заявка №$number", 1, $idNewFile, $_SESSION["id"]);
			$db->addLog($row8[id], time(), "Растаможка. Контрагент добавлен в качестве перевозчика. Заявка №$number", 1, $idNewFile, $_SESSION["id"]);
			
			$db->addPaymentFromReguest('Заказчик', $row7[id], $number, '', '');
			$db->addPaymentFromReguest('Перевозчик', $row8[id], $number, '', '');
			$status = 'ok';
		
			$status = 'ok';
		} else {
			$status = 'err';
		} 
		
		
	}
	 echo $status;die;
