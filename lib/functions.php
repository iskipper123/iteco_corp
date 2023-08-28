<?php
require_once "db.php";


function getNumberOfMonth($month) {
	switch($month) {
		case "Январь": {$answer = 1; break;}
		case "Февраль": {$answer = 2; break;}
		case "Март": {$answer = 3; break;}
		case "Апрель": {$answer = 4; break;}
		case "Май": {$answer = 5; break;}
		case "Июнь": {$answer = 6; break;}
		case "Июль": {$answer = 7; break;}
		case "Август": {$answer = 8; break;}
		case "Сентябрь": {$answer = 9; break;}
		case "Октябрь": {$answer = 10; break;}
		case "Ноябрь": {$answer = 11; break;}
		case "Декабрь": {$answer = 12; break;}
	}
	return $answer;
}

function getNumberOfMonth_precedent($month) {
	switch($month) {
		case "Январь": {$answer = 12; break;}
		case "Февраль": {$answer = 1; break;}
		case "Март": {$answer = 2; break;}
		case "Апрель": {$answer = 3; break;}
		case "Май": {$answer = 4; break;}
		case "Июнь": {$answer = 5; break;}
		case "Июль": {$answer = 6; break;}
		case "Август": {$answer = 7; break;}
		case "Сентябрь": {$answer = 8; break;}
		case "Октябрь": {$answer = 9; break;}
		case "Ноябрь": {$answer = 10; break;}
		case "Декабрь": {$answer = 11; break;}
	}
	return $answer;
}



function getNameOfMonth($startDay) {
	$month = strftime("%m", $startDay);
	switch($month) {
		case "1": {$nameOfMonth = "Январь"; break;}
		case "2": {$nameOfMonth = "Февраль"; break;}
		case "3": {$nameOfMonth = "Март"; break;}
		case "4": {$nameOfMonth = "Апрель"; break;}
		case "5": {$nameOfMonth = "Май"; break;}
		case "6": {$nameOfMonth = "Июнь"; break;}
		case "7": {$nameOfMonth = "Июль"; break;}
		case "8": {$nameOfMonth = "Август"; break;}
		case "9": {$nameOfMonth = "Сентябрь"; break;}
		case "10": {$nameOfMonth = "Октябрь"; break;}
		case "11": {$nameOfMonth = "Ноябрь"; break;}
		case "12": {$nameOfMonth = "Декабрь"; break;}
	}
	return $nameOfMonth;
}
function getAmountOfDaysInMonth($startDay) {
	// echo "startDay: ".$startDay."<br>";
	$month = strftime("%m", $startDay);
	// echo "month: ".$month."<br>";
	$year = strftime("%Y", $startDay);
	// echo "year: ".$year."<br>";
	
	switch($month) {
		case "1": {$amountOfDaysInMonth = 31; break;}
		case "2": {
					if($year%4 == 0) {$amountOfDaysInMonth = 29; break;}
					else {$amountOfDaysInMonth = 28; break;}
				  }
		case "3": {$amountOfDaysInMonth = 31; break;}
		case "4": {$amountOfDaysInMonth = 30; break;}
		case "5": {$amountOfDaysInMonth = 31; break;}
		case "6": {$amountOfDaysInMonth = 30; break;}
		case "7": {$amountOfDaysInMonth = 31; break;}
		case "8": {$amountOfDaysInMonth = 31; break;}
		case "9": {$amountOfDaysInMonth = 30; break;}
		case "10": {$amountOfDaysInMonth = 31; break;}
		case "11": {$amountOfDaysInMonth = 30; break;}
		case "12": {$amountOfDaysInMonth = 31; break;}
	}
	return $amountOfDaysInMonth;
}
function restOfTheDays($date) {
	// echo "date: ".$date."<br>";
	$answer = ceil(($date - time())/86400);
	if($answer == -0) $answer = 0;
	return $answer;
}
function getStartDay($startDay) {
	$day = strftime("%d", strtotime($startDay));
	$day--;
	$startDay = strtotime("-$day day", strtotime($startDay));
	return $startDay;
}
function getStatusForGetRequest($status) {
	if($status == 0) 		return "Запрос";
	else if($status == 1) 	return "В работе";
}
function printEmptyRow($country) { ?>
	<tr>
		<td class="emptyRow" colspan='9'><b><?=$country?></b></td>
	</tr>
<?}
function getCommission($startDate, $amountOfDaysInMonth, $idUser) {

/*	// Currency exchange rates START.
	$data= date("d.m.Y");
		$data_currency= date("d.m.y",time());
		$currency = simplexml_load_file("https://www.bnm.md/ro/official_exchange_rates?get_xml=1&date=".$data);
		$numb=0;
		$EUR=0;
		$USD=0;
		$RUB=0;
		foreach ($currency->Valute as $curr) {
			$numb+=1;
			if($numb == 1){
				$EUR = $curr->Value;
			}elseif($numb == 2){
				$USD = $curr->Value;
			}elseif($numb == 3){
				$RUB = $curr->Value;
			}
		   if($numb == 3){break;}
		}

$dif_currency =  bcdiv($EUR, 1, 4)/bcdiv($USD, 1, 4);


	$db = DB::getObject();
	$totalSum = 0;
	
	$result_set = $db->getRequestsByMonthByUser($startDate, $amountOfDaysInMonth, $idUser);

	while (($row = $result_set->fetch_assoc()) != false) {

				if ($row[currency] == 'EURO') {
						$customerPrice = $row[customerPrice]*$dif_currency;
						$carrierPrice = $row[carrierPrice]*$dif_currency;
					} elseif ($row[currency] == 'USD') {
						$customerPrice = $row[customerPrice];
						$carrierPrice = $row[carrierPrice];
					}
					
		$totalSum += $customerPrice - $carrierPrice - 5;
		($row[isCurrencyPayment] == 1) ? $isCurrencyPayment = "+" : $isCurrencyPayment = "-";
						if($isCurrencyPayment == "+") $totalSum = $totalSum - $row[comision_static];
	}
	return $totalSum;*/
}


function getCustom_clearenceByMonthByUser($startDate, $amountOfDaysInMonth, $idUser) {

	// Currency exchange rates START.
		$data= date("d.m.Y");
		$data_currency= date("d.m.y",time());
		$currency2 = simplexml_load_file("https://www.bnr.ro/nbrfxrates.xml");
		

		foreach ($currency2->Body->Cube->Rate as $curr) {
			if($curr["currency"]=='EUR')
				{
					$EUR_RO = $curr;
				}
				if($curr["currency"]=='MDL')
				{
					$MDL_RO = $curr;
				}
				if($curr["currency"]=='USD')
				{
					$USD_RO = $curr;
				}
		}

$EUR = bcdiv((float)$EUR_RO/(float)$MDL_RO, 1, 4);
$USD = bcdiv((float)$USD_RO/(float)$MDL_RO, 1, 4);


$dif_currency =  bcdiv($EUR_RO, 1, 4)/bcdiv($USD_RO, 1, 4);
	$db = DB::getObject();
	$totalSum = 0;
	
	$result_set = $db->getCustom_clearenceByMonthByUser($startDate, $amountOfDaysInMonth, $idUser);

	while (($row = $result_set->fetch_assoc()) != false) {

				if ($row['currency'] == 'EURO') {
						$customerPrice = round($row['customerPrice']*$dif_currency);
						$carrierPrice = round($row['carrierPrice']*$dif_currency);
					} elseif ($row['currency'] == 'USD') {
						$customerPrice = $row['customerPrice'];
						$carrierPrice = $row['carrierPrice'];
					}
				


		($row['isCurrencyPayment'] == 1) ? $isCurrencyPayment = "+" : $isCurrencyPayment = "-";
						if($isCurrencyPayment == "+") $totalSum = $totalSum - $row['comision_static'];

		if ($row['idUser'] != '110') {
			$totalSum += $customerPrice - $carrierPrice - 5;
		} else {
			$totalSum += $customerPrice - $carrierPrice - 5;
		}
		

	}
	 
	return $totalSum;
}


function getsalary_fromCustomclearence($startDate, $amountOfDaysInMonth, $idUser) {

	// Currency exchange rates START.
		$data= date("d.m.Y");
		$data_currency= date("d.m.y",time());
		$currency2 = simplexml_load_file("https://www.bnr.ro/nbrfxrates.xml");
		

		foreach ($currency2->Body->Cube->Rate as $curr) {
			if($curr["currency"]=='EUR')
				{
					$EUR_RO = $curr;
				}
				if($curr["currency"]=='MDL')
				{
					$MDL_RO = $curr;
				}
				if($curr["currency"]=='USD')
				{
					$USD_RO = $curr;
				}
		}

$EUR = bcdiv((float)$EUR_RO/(float)$MDL_RO, 1, 4);
$USD = bcdiv((float)$USD_RO/(float)$MDL_RO, 1, 4);


$dif_currency =  bcdiv($EUR, 1, 4)/bcdiv($USD, 1, 4);
	$db = DB::getObject();
	$totalSum = 0;
	
	$result_set = $db->getsalary_fromCustomclearence($startDate, $amountOfDaysInMonth, $idUser);

	
	return $result_set;
}


function difference_custom($startDate, $amountOfDaysInMonth, $idUser) {
 
	// Currency exchange rates START.
		$data= date("d.m.Y");

		$data_last_month = date('d.m.Y', strtotime('first day of last month'));

		$data_currency= date("d.m.y",time());
		$currency = simplexml_load_file("https://www.bnm.md/ro/official_exchange_rates?get_xml=1&date=".$data);
		$numb=0;
		$EUR=0;
		$USD=0;
		$RUB=0;
		foreach ($currency->Valute as $curr) {
			$numb+=1;
			if($numb == 1){
				$EUR = $curr->Value;
			}elseif($numb == 2){
				$USD = $curr->Value;
			}elseif($numb == 3){
				$RUB = $curr->Value;
			}
		   if($numb == 3){break;}
		}

$dif_currency =  bcdiv($EUR, 1, 4)/bcdiv($USD, 1, 4);
	$db = DB::getObject();
	$totalSum = 0;
	
	$result_set3 = $db->difference_custom($startDate, $amountOfDaysInMonth, $idUser);
	return $result_set3;
}

function difference_request($startDate, $amountOfDaysInMonth, $startDate_precedent, $amountOfDaysInMonth_precedent, $idUser) {

	// Currency exchange rates START.
		$data= date("d.m.Y");

		$data_last_month = date('d.m.Y', strtotime('first day of last month'));

		$data_currency= date("d.m.y",time());
		$currency = simplexml_load_file("https://www.bnm.md/ro/official_exchange_rates?get_xml=1&date=".$data);
		$numb=0;
		$EUR=0;
		$USD=0;
		$RUB=0;
		foreach ($currency->Valute as $curr) {
			$numb+=1;
			if($numb == 1){
				$EUR = $curr->Value;
			}elseif($numb == 2){
				$USD = $curr->Value;
			}elseif($numb == 3){
				$RUB = $curr->Value;
			}
		   if($numb == 3){break;}
		}

$dif_currency =  bcdiv($EUR, 1, 4)/bcdiv($USD, 1, 4);

	$db = DB::getObject();
	$totalSum = 0;
	
	$result_set4 = $db->difference_request($startDate, $amountOfDaysInMonth, $startDate_precedent, $amountOfDaysInMonth_precedent, $idUser);
	return $result_set4;
}


function difference_request_no_custom($startDate, $amountOfDaysInMonth, $startDate_precedent, $amountOfDaysInMonth_precedent, $idUser) {

	// Currency exchange rates START.
		$data= date("d.m.Y");

		$data_last_month = date('d.m.Y', strtotime('first day of last month'));

	$data_currency= date("d.m.y",time());
		$currency2 = simplexml_load_file("https://www.bnr.ro/nbrfxrates.xml");
		

		foreach ($currency2->Body->Cube->Rate as $curr) {
			if($curr["currency"]=='EUR')
				{
					$EUR_RO = $curr;
				}
				if($curr["currency"]=='MDL')
				{
					$MDL_RO = $curr;
				}
				if($curr["currency"]=='USD')
				{
					$USD_RO = $curr;
				}
		}

$EUR = bcdiv((float)$EUR_RO/(float)$MDL_RO, 1, 4);
$USD = bcdiv((float)$USD_RO/(float)$MDL_RO, 1, 4);


$dif_currency =  bcdiv($EUR, 1, 4)/bcdiv($USD, 1, 4);
	$db = DB::getObject();
	$totalSum = 0;
	
	$result_set4 = $db->difference_request_no_custom($startDate, $amountOfDaysInMonth, $startDate_precedent, $amountOfDaysInMonth_precedent, $idUser);
	return $result_set4;
}


function writeLogsEditCustomer($id, $name, $company_form, $contactName,  $phone, $email, $country, $city, $bankDetails, $headName, $carsAmount, $directions, $comments, $idManager) {
	$db = DB::getObject();
	
	$result_set = $db->getRowWhere("contractors", "id", $id);
	$row = $result_set->fetch_assoc();
	
	if($row[name] != $name) {
		$description = "Изменено поле Название фирмы. Старое значение: $row[name]. Новое значение: $name";
		$db->addLog($id, time(), $description, 0, 0, $_SESSION["id"]);
	}
	if($row[company_form] != $company_form) {
		$description = "Изменено поле Название фирмы. Старое значение: $row[company_form]. Новое значение: $company_form";
		$db->addLog($id, time(), $description, 0, 0, $_SESSION["id"]);
	}
	if($row[contactName] != $contactName) {
		$description = "Изменено поле Контактное лицо в фирме. Старое значение: $row[contactName]. Новое значение: $contactName";
		$db->addLog($id, time(), $description, 0, 0, $_SESSION["id"]);
	}
	
	if($row[phone] != $phone) {
		$description = "Изменено поле Контактные телефоны. Старое значение: $row[phone]. Новое значение: $phone";
		$db->addLog($id, time(), $description, 0, 0, $_SESSION["id"]);
	}
	
	if($row[email] != $email) {
		$description = "Изменено поле E-mail. Старое значение: $row[email]. Новое значение: $email";
		$db->addLog($id, time(), $description, 0, 0, $_SESSION["id"]);
	}
	
	if($row[country] != $country) {
		$description = "Изменено поле Страна. Старое значение: $row[country]. Новое значение: $country";
		$db->addLog($id, time(), $description, 0, 0, $_SESSION["id"]);
	}
	
	if($row[city] != $city) {
		$description = "Изменено поле Город. Старое значение: $row[city]. Новое значение: $city";
		$db->addLog($id, time(), $description, 0, 0, $_SESSION["id"]);
	}
	
	if($row[bankDetails] != $bankDetails) {
		$description = "Изменено поле Банковские реквизиты. Старое значение: $row[bankDetails]. Новое значение: $bankDetails";
		$db->addLog($id, time(), $description, 0, 0, $_SESSION["id"]);
	}
	
	if($row[headName] != $headName) {
		$description = "Изменено поле ФИО директора. Старое значение: $row[headName]. Новое значение: $headName";
		$db->addLog($id, time(), $description, 0, 0, $_SESSION["id"]);
	}
	
	if($row[carsAmount] != $carsAmount) {
		$description = "Изменено поле Количество автомобилей на фирме. Старое значение: $row[carsAmount]. Новое значение: $carsAmount";
		$db->addLog($id, time(), $description, 0, 0, $_SESSION["id"]);
	}
	
	if($row[directions] != $directions) {
		$description = "Изменено поле Направления по которым ввозят. Старое значение: $row[directions]. Новое значение: $directions";
		$db->addLog($id, time(), $description, 0, 0, $_SESSION["id"]);
	}
	
	if($row[comments] != $comments) {
		$description = "Изменено поле Комментарии. Старое значение: $row[comments]. Новое значение: $comments";
		$db->addLog($id, time(), $description, 0, 0, $_SESSION["id"]);
	}


/*new*/
	if($row[cod_fiscal] != $cod_fiscal) {
		$description = "Изменено поле Фискальный код. Старое значение: $row[cod_fiscal]. Новое значение: $cod_fiscal";
		$db->addLog($id, time(), $description, 0, 0, $_SESSION["id"]);
	}

		if($row[company_import_export] != $company_import_export) {
		$description = "Изменено поле Роль контрагента. Старое значение: $row[company_import_export]. Новое значение: $company_import_export";
		$db->addLog($id, time(), $description, 0, 0, $_SESSION["id"]);
	}
		if($row[company_season] != $company_season) {
		$description = "Изменено поле Сезонность контрагента. Старое значение: $row[company_season]. Новое значение: $company_season";
		$db->addLog($id, time(), $description, 0, 0, $_SESSION["id"]);
	}
/*new*/

 

	if($row[idManager] != $idManager) {
		        $result_set3 = $db->getUserByID($idManager); // get manager name
        		$row5 = $result_set3->fetch_assoc();

        		$result_set4 = $db->getUserByID($row[idManager]); // get manager name
        		$row6 = $result_set4->fetch_assoc();


		$description = "Изменено поле Менеджер. Старое значение: $row6[name]. Новое значение: $row5[name]";
		$db->addLog($id, time(), $description, 0, 0, $_SESSION["id"]);
	}
}
?>

