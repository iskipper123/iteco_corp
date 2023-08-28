<?php
	session_start();
	require_once "../lib/checkWasUserLoginedAndIsAdmin.php";
	require_once "../lib/db.php";
    require_once "../lib/functions.php";
    
    $title = 'Список клиентов по странам';
	
	$db = DB::getObject(); 
		
	$result_set = $db->getAllCountriesInClientsTable();

	if(isset($_POST["cancel"])) {
		header("Location: clients.php");
		exit;
	}
		
    require_once '../partsOfPages/head.php';
    require_once "../partsOfPages/menuForAdmin.php"; ?>
<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
            <div class="d-sm-flex pt-3 pl-3 align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800"><?=$title?></h1>
            </div>
            <?php require_once "../partsOfPages/welcome.php"; ?>
        </nav>
        <div class="container-fluid">
            <div class="col-md-12">
				<form name="" action="" method="post">
					<input class="btn btn-danger btn-sm" type="submit" name="cancel" value="Вернуться назад"> <br /> <br />
				</form>
				<a class="btn btn-success btn-sm" href="clients.php"> Клиенты по менеджерам</a><br /><br />
                <? require_once "../lib/allClientsByCountries.php"; ?>
            </div>
        </div>
    </div>
<? require_once '../partsOfPages/footer.php';?>