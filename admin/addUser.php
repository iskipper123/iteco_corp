<?php
	session_start();
	require_once "../lib/checkWasUserLoginedAndIsAdmin.php";
	require_once "../lib/db.php";
    require_once "../lib/vars.php";
    
    $title = 'Добавить пользователя';

	$db = DB::getObject();

		$result_set = $db->getAllUsersDepartments();




	if(isset($_POST["addUser"])) {

		$name = $_POST["name"];
		$login = $_POST["login"];
		$password = $_POST["password"];
		$variant_pay = $_POST["variant_pay"];
		$manager_variant = $_POST["manager_variant"];


		$department = $_POST["department"];

		$error_name = "";
		$error_login = ""; 
		$error_password = "";
		$error_variant_pay = "";
		$error_manager_variant = "";
		$error_department = "";
		$error = false;
		
		if(strlen($name) == 0) {
			$error_name = "Не заполнено поле";
			$error = true;
		}
		if(strlen($variant_pay) == 0) {
			$error_variant_pay = "Не заполнено поле";
			$error = true;
		}
		if(strlen($manager_variant) == 0) {
			$error_manager_variant = "Не заполнено поле";
			$error = true;
		}		
		if(strlen($department) == 0) {
			$error_department = "Не заполнено поле";
			$error = true;
		}
		if(strlen($login) == 0) {
			$error_login = "Не заполнено поле";
			$error = true;
		}
		$result_set10 = $db->checkLogin($_POST["login"]);
		if($result_set10->num_rows != 0) {
			$error_login = "Данный логин занят, выберите, пожалуйста, другой";
			$error = true;
		}
		if(strlen($password) == 0) {
			$error_password = "Не заполнено поле";
			$error = true;
		}
		
		if(!$error) {
			$db->addUser($login, $password, $name, $variant_pay, $manager_variant, $department);
							
			header("Location: users.php?success");
			exit;
		}
	}
	else if(isset($_POST["cancel"])) {
		header("Location: users.php");
		exit;		
	}
    require_once '../partsOfPages/head.php';
    require_once "../partsOfPages/menuForAdmin.php"; ?>
    <div class="col-md-4 pt-3">
        <form name="" action="" method="post">
            <div class="form-group" style="clear: both;">
                <label for="nameInput" class="required">Фамилия Имя Отчество</label>
                <input class="form-control<?=isset($error_name)&&$error_name!=''?' is-invalid':''?>" type="text" name="name" id="nameInput" value="<?=$_POST["name"]?>">
                <div class="invalid-feedback" <?=isset($error_name)&&$error_name!=''?'style="display:block;"':''?>><?=$error_name ?></div>
            </div>
            <div class="form-group" style="clear: both;">
                <label for="loginInput" class="required">Логин</label>
                <input class="form-control<?=isset($error_login)&&$error_login!=''?' is-invalid':''?>" type="text" name="login" id="loginInput" value="<?=$_POST["login"]?>">
                <div class="invalid-feedback" <?=isset($error_login)&&$error_login!=''?'style="display:block;"':''?>><?=$error_login ?></div>
            </div>
            <div class="form-group" style="clear: both;">
                <label for="passwordInput" class="required">Пароль</label>
                <input class="form-control<?=isset($error_password)&&$error_password!=''?' is-invalid':''?>" type="text" name="password" id="passwordInput" value="<?=$_POST["password"]?>">
                <div class="invalid-feedback" <?=isset($error_password)&&$error_password!=''?'style="display:block;"':''?>><?=$error_password ?></div>
            </div>
            <div class="form-group" style="clear: both;">
              	 <label for="loginInput" class="required">Вариант оплаты</label>
            
				<select class="form-control" title="variant_pay" id="variant_pay" name="variant_pay">
				<?php foreach ($plan as $key => $value) { ?>
				  <option value="<?php echo $key;?>" <?php echo ($key ==  $variant_pay) ? ' selected="selected"' : '';?>><?php echo $value;?></option>
				<?php } ?>
				</select>

            </div>
             <div class="form-group" style="clear: both;">
              	 <label for="loginInput" class="required">Роль менеджера</label>
            
			 <select class="form-control" title="manager_variant" id="manager_variant" name="manager_variant">

                        <? for($i = 0; $i < count($arrayOfmanager_variants); $i++) {
                            if($manager_variant != $arrayOfmanager_variants[$i]) { ?>
                                <option><?=$arrayOfmanager_variants[$i] ?></option>
                            <?}?>
                        <?}?>
             </select>

            </div>

            <div class="form-group" style="clear: both;">
              	 <label for="department" class="required">Отдел</label>
            
				<select class="form-control" title="department" id="department" name="department">
					 <option value="">Без отдела</option>
					<?php while (($row3 = $result_set->fetch_assoc()) != false) { ?>
							<option value="<?=$row3[id]?>"><?=$row3[usergroup_name]?></option> 
					<? } ?>
				</select>

            </div>


            <input class="btn btn-secondary btn-sm" type="submit" name="addUser" value="Добавить">
            <input class="btn btn-danger btn-sm" type="submit" name="cancel" value="Отменить">
        </form>
    </div>
<?  require_once '../partsOfPages/footer.php';?> 