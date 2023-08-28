<?php
	session_start();
	require_once "../lib/checkWasUserLoginedAndIsAdmin.php";
    require_once "../lib/db.php";
    
    

	$db = DB::getObject();
		
	if(isset($_GET["edit"])) {
		$id = $_GET["edit"];

		$result_set = $db->getRowWhere("users", "id", $id);
		$row = $result_set->fetch_assoc();
	}
	$title = 'Смена пароля пользователя '.$row["name"];
	if(isset($_POST["editPassword"])) {
		$id = $_POST["id"];
		$password = $_POST["password"];
		
		$error_password = "";
		$error = false;
		
		if(strlen($password) == 0) {
			$error_password = "Не заполнено поле";
			$error = true;
		}
		
		if(!$error) {
			$db->editPassword($id, $password);

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
            <div class="form-group">
                <label for="passwordInput" class="required">Введите новый пароль</label>
                <input class="form-control<?=isset($error_password)&&$error_password!=''?' is-invalid':''?>" type="text" name="password" id="passwordInput" value="<?=$_POST["password"]?>">
                <div class="invalid-feedback" <?=isset($error_password)&&$error_password!=''?'style="display:block;"':''?>><?=$error_login ?></div>
            </div>
            <input type="hidden" name="id" value="<?=$id ?>">
            <input class="btn btn-secondary btn-sm" type="submit" name="editPassword" value="Изменить">
            <input class="btn btn-danger btn-sm" type="submit" name="cancel" value="Отменить">
        </form>
    </div>
<?  require_once '../partsOfPages/footer.php';?>