<?php
    require_once "../lib/db.php";
    require_once "../lib/vars.php";  

    $db = DB::getObject();

	
	if(isset($_POST["nameCheck"])) {
		$name = $_POST["nameCheck"];
		$error_name = "";
		$error = false;
		
		if(strlen($name) == 0) {
			$error_name = "Не заполнено поле";
			$error = true;
		}	 
    
		$name = htmlspecialchars($name, ENT_QUOTES);
		$result_set = $db->getRowWhereWhereOrder("contractors", "name", $name, "isDeleted", 0, "name");
        $row4 = $result_set->fetch_assoc();

        $result_set2 = $db->getUserByID($row4[idManager]); // get manager name
        $row5 = $result_set2->fetch_assoc();
 
        if($row5[name] != '') {
            $error_name = "Этот клиент закреплен за ".$row5[name];
        }elseif($row4[name] != '') {
        	 $error_name = "Этот клиент закреплен за ".$row4[name];
        } else {
             $error_name = "Крутяк, свободный клиент";
        }
	}
 echo $error_name;
 die;
  ?>
