<?php
    session_start();
	require_once "../lib/checkWasUserLoginedAndIsAdmin.php";
	require_once "../lib/db.php";
    require_once "../lib/vars.php";  

    $db = DB::getObject();

	if(isset($_POST["name"])) {
		$name = $_POST["name"];
		$company_form = $_POST["company_form"];
		$contactName = $_POST["contactName"];
		$phone = $_POST["phone"];
		$email = $_POST["email"];
		$country = $_POST["country"];
		$city = $_POST["city"];
		$bankDetails = $_POST["bankDetails"];
		$headName = $_POST["headName"];
		$carsAmount = $_POST["carsAmount"];
		$directions = '';
		$comments = $_POST["comments"];
		$countryTags = explode(';',$_POST["directions"]); //массив
		$isMarked = $_POST["isMarked"];
		$contractorsType = 'Перевозчик';
		$cod_fiscal = $_POST["cod_fiscal"]; 
		$company_import_export = $_POST["company_import_export"]; 
		$company_season = $_POST["company_season"]; 

        $error_name = "";
		$error_contactName = "";
		$error_phone = "";
		$error_country = "";
		$error_city = "";
        $error_cod_fiscal = "";
        $error_company_import_export = "";
        $error_company_season = "";
		$error = false;

		if(strlen($name) == 0) {
			$error_name = "Не заполнено поле";
			$error = true;
		}
		if(strlen($company_form) == 0) {
			$error_name = "Не заполнено поле";
			$error = true;
		}
		if(strlen($contactName) == 0) {
			$error_contactName = "Не заполнено поле";
			$error = true;
		}
		if(strlen($phone) == 0) {
			$error_phone = "Не заполнено поле";
			$error = true;
		}
		if(strlen($country) == 0) {
			$error_country = "Не заполнено поле";
			$error = true;
		}
		if(strlen($city) == 0) {
			$error_city = "Не заполнено поле";
			$error = true;
		}


		if(strlen($company_import_export) == 0) {
			$error_company_import_export = "Не заполнено поле";
			$error = true;
		}
		if(strlen($company_season) == 0) {
			$error_company_season = "Не заполнено поле";
			$error = true;
		}
		if($_SESSION["role"] == 1) {
        $idUser = $_POST["idUser"];
        } else {
        $idUser = $_SESSION["id"];  
        }
		

		$name = htmlspecialchars($name, ENT_QUOTES);
		$result_set = $db->getRowWhereWhereWhereOrder("contractors", "name", $name, "contractorsType", $arrayOfContractorsTypes[1], "isDeleted", 0, "name");
		if($result_set->num_rows != 0) {
			$error_name = "Контрагент с таким названием уже существует. Повторно завести нельзя!";
			$error = true;
		}
		
		if(!$error) { 
			$bankDetails = htmlspecialchars($bankDetails, ENT_QUOTES);
			
			$db->addContractor($contractorsType, $name,$company_form, $contactName, $phone, $email, $country, $city, $bankDetails, $headName, $carsAmount, 
								$directions, $comments, $isMarked, $idUser, $date, $cod_fiscal, $company_import_export, $company_season);
			
			$lastID = $db->getLastID("contractors");

			for($i = 0; $i < count($countryTags); $i++) {
				$db->addTagToContractor($lastID, $countryTags[$i]);
			}

			$db->addLog($lastID, time(), "Контрагент создан", 0, 0, $_SESSION["id"]);

				$status = 'ok';
		} else {
			$status = 'err';
		} 
	}
	 echo $error;die;
  ?>