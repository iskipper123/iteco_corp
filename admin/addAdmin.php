<?php
	session_start();
	require_once "../lib/checkWasUserLoginedAndIsAdmin.php";
	require_once "../lib/db.php"; 

    $title = 'Добавить администратора';

	$db = DB::getObject();
	
	if(isset($_POST["addUser"])) {
		$name = $_POST["name"];
		$login = $_POST["login"];
		$password = $_POST["password"]; 

		$error_name = "";
		$error_login = "";
		$error_password = "";
		$error = false;
		
		if(strlen($name) == 0) {
			$error_name = "Не заполнено поле";
			$error = true;
		}
		if(strlen($login) == 0) {
			$error_login = "Не заполнено поле";
			$error = true;
		}
		$result_set10 = $db->checkLogin($login);
		if($result_set10->num_rows != 0) {
			$error_login = "Данный логин занят, выберите, пожалуйста, другой";
			$error = true;
		}
		if(strlen($password) == 0) {
			$error_password = "Не заполнено поле";
			$error = true;
		}
		
		if(!$error) {
			$db->addAdmin($login, $password, $name);
							
			header("Location: admins.php?success");
			exit;
		}
	}
	else if(isset($_POST["cancel"])) {
		header("Location: admins.php");
		exit;		
	}
    require_once '../partsOfPages/head.php';
    require_once "../partsOfPages/menuForAdmin.php"; ?>
    <div class="col-md-4 pt-3">
        <form name="" action="" method="post">
            <div class="form-group">
                <label for="nameInput" class="required">Фамилия Имя Отчество</label>
                <input class="form-control<?=isset($error_name)&&$error_name!=''?' is-invalid':''?>" type="text" name="name" id="nameInput" value="<?=$_POST["name"]?>">
                <div class="invalid-feedback" <?=isset($error_name)&&$error_name!=''?'style="display:block;"':''?>><?=$error_name ?></div>
            </div>
            <div class="form-group">
                <label for="loginInput" class="required">Логин</label>
                <input class="form-control<?=isset($error_login)&&$error_login!=''?' is-invalid':''?>" type="text" name="login" id="loginInput" value="<?=$_POST["login"]?>">
                <div class="invalid-feedback" <?=isset($error_login)&&$error_login!=''?'style="display:block;"':''?>><?=$error_login ?></div>
            </div>
            <div class="form-group">
                <label for="passwordInput" class="required">Пароль</label>
                <input class="form-control<?=isset($error_password)&&$error_password!=''?' is-invalid':''?>" type="text" name="password" id="passwordInput" value="<?=$_POST["password"]?>">
                <div class="invalid-feedback" <?=isset($error_password)&&$error_password!=''?'style="display:block;"':''?>><?=$error_login ?></div>
            </div>
            
            <input class="btn btn-secondary btn-sm" type="submit" name="addUser" value="Добавить">
            <input class="btn btn-danger btn-sm" type="submit" name="cancel" value="Отменить">
        </form>
    </div>
<?  require_once '../partsOfPages/footer.php';?>