<?php
	require_once "continueSession.php";
	
	if($_SESSION["isLogin"] != "true") {
		if ($_SESSION["role"] != 2 && $_SESSION["role"] != 1) {
			header("Location: /notRightsEnought.php");
		exit;
		}
	
	}
?> 