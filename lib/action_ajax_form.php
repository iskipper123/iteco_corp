<?php
    require_once "db.php";
    require_once "vars.php"; 
    require_once "../lib/functions.php";
    $db = DB::getObject();
    
    if(isset($_POST["id"])) {
        $id = $_POST["id"];
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
        $directions = $_POST["directions"];
        $comments = $_POST["comments"]; 
        $countryTags = $_POST["countryTags"]; //массив
        if (isset($_POST["date"])) {
           $date = strtotime($_POST["date"]); //массив
        } else {
           $date = strtotime(date('d.m.Y', time()) . ' 00:00:00');
        }
      

        $idManager = $_POST["idUser"];
        $cod_fiscal = $_POST["cod_fiscal"]; //массив
       
        $contractorsType = $_POST["contractorsType"]; 
        $company_import_export = $_POST["company_import_export"]; 
        $company_season = $_POST["company_season"]; 

        
        $idUser = $_POST["idUser"];
        
        if(empty($_POST["isMarked"])) 
          {
           $isMarked = 0;
          } else {
        $isMarked = $_POST["isMarked"];
    }

        $error_reg_nr = "";
        $error_contractorsType = "";
        $error_name = "";
        $error_contactName = "";
        $error_phone = "";
        $error_country = "";
        $error_city = "";
        $error = false;

        if(strlen($name) == 0) {
            $error_name = "Не заполнено поле";
            $error = true;
        }
        if(strlen($contractorsType) == 0) {
            $error_contractorsType = "Не заполнено поле";
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

        if(!$error) {
            $name = htmlspecialchars($name, ENT_QUOTES);
            $bankDetails = htmlspecialchars($bankDetails, ENT_QUOTES);

            

            
            writeLogsEditCustomer($id, $name, $company_form, $contactName, $phone, $email, $country, $city, $bankDetails, $headName, $carsAmount, $directions, $comments, $idManager,$cod_fiscal, $company_import_export, $company_season);
            
            $db->editContractor($id, $contractorsType, $name, $company_form, $contactName, $phone, $email, $country, $city, $bankDetails, $headName, $carsAmount, 
                                $directions, $comments, $isMarked, $idUser, $date, $cod_fiscal, $company_import_export, $company_season); 
            
          
            // $db->deleteWhere("tags", "idContractor", $id); 
            
            // for($i = 0; $i < count($countryTags); $i++) {
                // $db->addTagToContractor($id, $countryTags[$i]);
            // }
   
        }
    }
    
?>