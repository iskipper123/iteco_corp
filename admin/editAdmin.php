<?php
	session_start();
	require_once "../lib/checkWasUserLoginedAndIsAdmin.php";
    require_once "../lib/db.php";
    
    $title = 'Редактирование данных администратора';
	
	$db = DB::getObject();

	if(isset($_GET["edit"])) {
		$id = $_GET["edit"];
				
		$result_set = $db->getRowWhere("users", "id", $id);
		$row = $result_set->fetch_assoc();
	}
	
	if(isset($_POST["editEmployee"])) {
		$name = $_POST["name"];
		$login = $_POST["login"];
		$oldLogin = $_POST["oldLogin"];

		$error_name = "";
		$error_login = "";
		$error = false;
				
		if(strlen($name) == 0) {
			$error_name = "Не заполнено поле";
			$error = true;
		}			
		if(strlen($login) == 0) {
			$error_login = "Не заполнено поле";
			$error = true;
		}
		if($login != $oldLogin) {
			$result_set = $db->checkLogin($login);
			$row = $result_set->num_rows;
			if($row != 0) {
				$error_login = "Логин \"$login\" уже занят";
				$error = true;
			}
		}

		if(!$error) {
			$db->editUser($id, $login, $name);

			if($_SESSION["userType"] == "1") {
				header("Location: admins.php?success");
				exit;	
			}
			else {
				header("Location: users.php?success");
				exit;	
			}
		}
	}
	else if(isset($_POST["cancel"])) {
		if($_SESSION["userType"] == "1") {
			header("Location: admins.php");
			exit;	
		}
		else {
			header("Location: users.php");
			exit;	
		}	
	}
    require_once '../partsOfPages/head.php';
    require_once "../partsOfPages/menuForAdmin.php"; ?>
    <div class="col-md-4 pt-3">
        <form name="" action="" method="post">
            <input class="btn btn-danger btn-sm" type="submit" name="cancel" value="Вернуться назад"> <br /> <br />
            <div class="form-group">
                <label for="nameInput" class="required">Фамилия Имя Отчество</label>
                <input class="form-control<?=isset($error_name)&&$error_name!=''?' is-invalid':''?>" type="text" name="name" id="nameInput" value="<?=isset($_POST["name"])? $_POST["name"]:$row[name]?>">
                <div class="invalid-feedback" <?=isset($error_name)&&$error_name!=''?'style="display:block;"':''?>><?=$error_name ?></div>
            </div>
            <div class="form-group">
                <label for="loginInput" class="required">Логин</label>
                <input class="form-control<?=isset($error_login)&&$error_login!=''?' is-invalid':''?>" type="text" name="login" id="loginInput" value="<?=isset($_POST["login"])? $_POST["login"]:$row[login]?>">
                <input type="hidden" name="oldLogin" value="<?=$row["login"]?>">
                <div class="invalid-feedback" <?=isset($error_login)&&$error_login!=''?'style="display:block;"':''?>><?=$error_login ?></div>
            </div>
            
            <input class="btn btn-secondary btn-sm" type="submit" name="editEmployee" value="Изменить">
            <input class="btn btn-danger btn-sm" type="submit" name="cancel" value="Отменить">
        </form>
    </div>
<?  require_once '../partsOfPages/footer.php';?>