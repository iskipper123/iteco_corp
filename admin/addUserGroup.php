<?php
	session_start();
	require_once "../lib/checkWasUserLoginedAndIsAdmin.php";
	require_once "../lib/db.php";
    require_once "../lib/vars.php";
    
    $title = 'Добавить группу пользователей';

	$db = DB::getObject();


	if(isset($_POST["usergroup_name"])) {

		$usergroup_name = $_POST["usergroup_name"];

		$error_userGroup = "";
		$error = false;
		
		if(strlen($usergroup_name) == 0) {
			$error_name = "Не заполнено поле";
			$error = true;
		}

		
		if(!$error) {
			$db->addUserGroup($usergroup_name);
							
			header("Location: usersGroup.php?success");
			exit;
		}
	}if(isset($_POST["cancel"])) {
		header("Location: usersGroup.php");
		exit;		
	}
    require_once '../partsOfPages/head.php';
    require_once "../partsOfPages/menuForAdmin.php"; ?>

    <div class="col-md-4 pt-3">
        <form name="" action="" method="post">
            <div class="form-group" style="clear: both;">
                <label for="loginInput" class="required">Отдел</label>
                <input class="form-control<?=isset($error_usergroup_name)&&$error_usergroup_name!=''?' is-invalid':''?>" type="text" name="usergroup_name" id="usergroup_name" value="<?=$_POST["usergroup_name"]?>">
                <div class="invalid-feedback" <?=isset($error_usergroup_name)&&$error_usergroup_name!=''?'style="display:block;"':''?>><?=$error_usergroup_name ?></div>
            </div>
            <input class="btn btn-secondary btn-sm" type="submit" name="addUser" value="Добавить">
            <input class="btn btn-danger btn-sm" type="submit" name="cancel" value="Отменить">
        </form>
    </div>

<?  require_once '../partsOfPages/footer.php';?> 