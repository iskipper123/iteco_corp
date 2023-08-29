<?php
	session_start();
	require_once "lib/db.php";
    
    $title = 'Авторизация';

	$user = DB::getObject();
	$auth = $user->isAuth();
    $userIsBlocked = false;
	if(isset($_POST["auth"])) {
		$login = $_POST["login"];
		$password = $_POST["password"];
        $auth_success = $user->login($login, $password);
        $userIsBlocked = false;
		
		$result_set = $user->getUserByLogin($login);
		$row = $result_set->fetch_assoc();
        
		if($row['isDeleted'] == 1 || $row['isDeleted'] == 2) {
			$auth_success = false;
			$userIsBlocked = true;
        }
		if($auth_success){
			$_SESSION["isLogin"] = "true";
			$role = $user->getRole($login);
			$_SESSION["role"] = $role;
  
			$result_set = $user->getUserByLogin($login); 
			$row = $result_set->fetch_assoc();
			$_SESSION["id"] = $row["id"];

            $_SESSION["role"] = $role;
		
    		setcookie("formLogin", $_POST["login"], time() + (86400 * 30));
			setcookie("formPassword", $_POST["password"], time() + (86400 * 30));
            setcookie("formid", $_SESSION["id"], time() + (86400 * 30));
            setcookie("role", $_SESSION["role"], time() + (86400 * 30));
           
			
			switch($role) {
				case "1": {header("Location: admin/home.php"); break;}
				case "2": {header("Location: users/home.php"); break;}
			}
			exit;
		}
	}
?>
<? require_once 'partsOfPages/head.php';?>
<div class="sidenav">
    <div class="login-main-text">
    <p><img src="/images/iteco-logo.png" alt="iteco logo" style="width:90%"/></p>
    </div>
</div>
<div class="main">
   
        <div class="login-form">
            <form name="auth" action="" method="post">
                <div class="form-group">
                    <label>Логин</label>
                    <input type="text" class="form-control" name="login">
                </div>
                <div class="form-group">
                    <label>Пароль</label>
                    <input type="password" class="form-control" name="password">
                </div>
                <? if(isset($_POST["auth"])) {
                    if(!$auth_success && !$userIsBlocked) {?>
                    <div class="form-group">
                        <div class="alert alert-danger" role="alert">
                            Неверные имя пользователя и/или пароль
                        </div>
                    </div>
                    <?}?>
                <?}?>
                <!-- <? if($userIsBlocked) {?>
                    <div class="form-group">
                        <div class="alert alert-danger" role="alert">
                        Личный кабинет заблокирован!
                        </div>
                    </div>
                <?}?> -->
                <button type="submit" name="auth" class="btn btn-black">Войти</button>
            </form>
        </div>
  
</div>
</body>
</html>

