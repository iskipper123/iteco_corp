<?php
	require_once "continueSession.php";
	
	if($_SESSION["isLogin"] != "true" || $_SESSION["role"] != 1 || $_SESSION["role"] != 2) {
		header("Location: /notRightsEnought.php");
		exit;
	}
?>