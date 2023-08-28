<?php
	require_once "db.php";
	
	if(isset($_COOKIE["formLogin"])) {
		$user = DB::getObject();
		$auth = $user->isAuth();
		
		$login = $_COOKIE["formLogin"];
		$password = $_COOKIE["formPassword"];
		
		$auth_success = $user->login($login, $password);
		if($auth_success){
			$_SESSION["isLogin"] = "true";
			$role = $user->getRole($login);
			$_SESSION["role"] = $role;
			
			$result_set = $user->getUserByLogin($login);
			$row = $result_set->fetch_assoc();
			$_SESSION["id"] = $row["id"];
		}
	}
?>