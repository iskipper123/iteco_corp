<?php
	session_start();
	require_once "../lib/checkWasUserLoginedAndIsAdmin.php";
    require_once "../lib/vars.php";
    
    $title = 'Редактирование данных пользователя';
	
	$db = DB::getObject();
 
	if(isset($_GET["edit"])) {
		$id = $_GET["edit"];
				
		$result_set = $db->getRowWhere("user_group", "id", $id);
		$row = $result_set->fetch_assoc();
	} 
	
	if(isset($_POST["editEmployee"])) {

		$usergroup_name = $_POST["usergroup_name"];

		$error = false;
				
		if(strlen($usergroup_name) == 0) {
			$error_usergroup_name = "Не заполнено поле";
			$error = true;
		}


		if(!$error) {
			$db->editUserGroup($id, $usergroup_name);

			if($_SESSION["userType"] == "1") {
				header("Location: usersGroup.php?success");
				exit;	
			}
		}
	}
	else if(isset($_POST["cancel"])) {
		if($_SESSION["userType"] == "1") {
			header("Location: usersGroup.php");
			exit;	
		}
	}
    require_once '../partsOfPages/head.php';
    require_once "../partsOfPages/menuForAdmin.php"; ?>
    <div class="col-md-4 pt-3">
        <form name="" action="" method="post">
            <input class="btn btn-danger btn-sm" type="submit" name="cancel" value="Вернуться назад"> <br /> <br />
            <div class="form-group" style="clear: both;">
                <label for="nameInput" class="required">Название отдела</label>
                <input class="form-control<?=isset($error_usergroup_name)&&$error_usergroup_name!=''?' is-invalid':''?>" type="text" name="usergroup_name" id="usergroup_name" value="<?=isset($_POST["usergroup_name"])? $_POST["usergroup_name"]:$row[usergroup_name]?>">
                <div class="invalid-feedback" <?=isset($error_usergroup_name)&&$error_usergroup_name!=''?'style="display:block;"':''?>><?=$error_usergroup_name ?></div>
            </div>
            
            <input class="btn btn-secondary btn-sm" type="submit" name="editEmployee" value="Изменить">
            <input class="btn btn-danger btn-sm" type="submit" name="cancel" value="Отменить">
        </form>
    </div>
<?  require_once '../partsOfPages/footer.php';?>