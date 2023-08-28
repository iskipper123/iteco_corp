<?php

    require_once "db.php";
    require_once "vars.php"; 
    require_once "../lib/functions.php";
    $db = DB::getObject();
   
    if(isset($_POST["idItem"])) {
        $id = $_POST["idItem"];
        $name = $_POST["name"];
        $contactName = $_POST["contactName"];
        $comments = $_POST["comments"]; 
        $phone = $_POST["phone"];
        $idUser = $_POST["idUser"];
        $role = $_SESSION["role"];
        $countryTags = explode(';',$_POST["directions"]); //массив
      
        
        $error_name = "";
        $error_contactName = "";
        $error_phone = "";
        $error = false;
    
        if(strlen($name) == 0) {
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

       
        if(!$error) {
            $name = htmlspecialchars($name, ENT_QUOTES);
           
           // writeLogsEditCustomer($id, $name, $company_form, $contactName, $phone, $email, $country, $city, $bankDetails, $headName, $carsAmount, $directions, $comments);
      
                $db->editClient_custom($id, $name, $contactName, $comments, $phone, $idUser);
           

                 //создано для тегов, 11.12.2019
                    $db->deleteWhere("tags_clients", "idClient", $id);
                    
                    for($i = 0; $i < count($countryTags); $i++) {
                        $db->addTagToClient($id, $countryTags[$i]);
                    }

            }
        }
   
    
?>