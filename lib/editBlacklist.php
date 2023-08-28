<?php
	$db = DB::getObject();

	if(isset($_GET["edit"])) {
		$id = $_GET["edit"];
				
		$result_set = $db->getRowWhere("blacklist", "id", $id);
		$row = $result_set->fetch_assoc();
	}
	
	if(isset($_POST["edit"])) {
		$name = $_POST["name"];
		$contactName = $_POST["contactName"];
		$reason = $_POST["reason"];
		
		$error_name = "";
		$error_contactName = "";
		$error_reason = "";
		$error = false;
		
		if(strlen($name) == 0) {
			$error_name = "Не заполнено поле";
			$error = true;
		}		
		if(strlen($contactName) == 0) {
			$error_contactName = "Не заполнено поле";
			$error = true;
		}
		if(strlen($reason) == 0) {
			$error_reason = "Не заполнено поле";
			$error = true;
		}

		if(!$error) {
			$name = htmlspecialchars($name, ENT_QUOTES);
			
			$db->editBlacklist($id, $name, $contactName, $reason);
							
			header("Location: blacklist.php?success");
			exit;
		}
	}
	else if(isset($_POST["cancel"])) {
		header("Location: blacklist.php");
		exit;		
	}
    require_once '../partsOfPages/head.php';
    if($_SESSION["userType"] == 1) 		require_once "../partsOfPages/menuForAdmin.php"; 
    else if($_SESSION["userType"] == 2) require_once "../partsOfPages/menuForUser.php"; ?>
    <div class="col-md-12 pt-3">
        <form name="" action="" method="post">
            <input class="btn btn-danger btn-sm" type="submit" name="cancel" value="Вернуться назад"> <br /> <br />
            <div class="row">
                <div class="form-group col-md-4 mr-4">
                    <label for="nameInput" class="required">Название компании</label>
                    <input class="form-control<?=isset($error_name)&&$error_name!=''?' is-invalid':''?>" type="text" name="name" id="nameInput" value="<?=isset($_POST["name"])? $_POST["name"]:$row[name]?>">
                    <div class="invalid-feedback" <?=isset($error_name)&&$error_name!=''?'style="display:block;"':''?>><?=$error_name ?></div>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="contactNameInput" class="required">Контактное лицо</label>
                    <input class="form-control<?=isset($error_contactName)&&$error_contactName!=''?' is-invalid':''?>" type="text" name="contactName" id="contactNameInput" value="<?=isset($_POST["contactName"])? $_POST["contactName"]:$row[contactName]?>">
                    <div class="invalid-feedback" <?=isset($error_contactName)&&$error_contactName!=''?'style="display:block;"':''?>><?=$error_contactName ?></div>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="reasonInput" class="required">Причина, по которой добавили</label>
                    <textarea class="form-control<?=isset($error_reason)&&$error_reason!=''?' is-invalid':''?>" name="reason" id="reasonInput" ><?=isset($_POST["reason"])? $_POST["reason"]:$row[reason]?></textarea>
                    <div class="invalid-feedback" <?=isset($error_reason)&&$error_reason!=''?'style="display:block;"':''?>><?=$error_reason ?></div>
                </div>
            </div>
            <input class="btn btn-secondary btn-sm" type="submit" name="edit" value="Изменить">
            <input class="btn btn-danger btn-sm" type="submit" name="cancel" value="Отменить">
        </form>
    </div>
<?  require_once '../partsOfPages/footer.php';?> 	