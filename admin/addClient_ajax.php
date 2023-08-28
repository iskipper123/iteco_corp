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
		$comments = $_POST["comments"];
		$date = $_POST["date"];
		$idUser = $_POST["idUser"];
		$phone = $_POST["phone"];
        $countryTags = explode(';',$_POST["directions"]); //массив
        $isMarked = $_POST["isMarked"];
		
		$error_name = "";
		$error_contactName = "";
		$error_date = "";
		$error_idUser = "";
		$error_country = "";
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
		if(strlen($date) == 0) {
			$error_date = "Не заполнено поле";
			$error = true;
		}
		if(strlen($idUser) == 0) { 
			$error_idUser = "Не заполнено поле";
			$error = true;
		}
		$name = htmlspecialchars($name, ENT_QUOTES);
		$result_set = $db->getRowWhereWhereOrder("clients", "name", $name, "isDeleted", 0, "name");
        $row4 = $result_set->fetch_assoc();


        $result_set2 = $db->getUserByID($row4[idManager]); // get manager name
        $row5 = $result_set2->fetch_assoc();
        
 
        if($row4[idManager] == $_SESSION["id"] && $row4[idManager] != '') {
            $error_name = "Этот клиент закреплен за ".$row5[name];
            $error = true;

        } elseif($row4[idManager] != $_SESSION["id"] && $row4[idManager] != ''){
        	$error_name = "Этот клиент закреплен за ".$row5[name];
            $error = true;
        }
		if(!$error) {
			$db->addClient($name, $company_form, $contactName, strtotime($date), $comments, $idUser, $phone, $country, $isMarked);
			
			//создано для тегов, 3.04.2019			
			$lastID = $db->getLastID("clients");

            for($i = 0; $i < count($countryTags); $i++) {
                $db->addTagToClient($lastID, $countryTags[$i]);
            }

			$status = 'ok';
		} else {
		$status = 'err';
		} 
	}
 echo $error_name;
 die;
  ?>