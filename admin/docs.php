<?php
	session_start();
	require_once "../lib/checkWasUserLoginedAndIsAdmin.php";
	require_once "../lib/db.php";
    require_once "../lib/vars.php";
    
    $title = 'Документы';
	
	// $_SESSION["userType"] = 1;
	// require_once "../lib/docsByGroup.php";
	
	$db = DB::getObject(); 
    require_once '../partsOfPages/head.php';
    require_once "../partsOfPages/menuForAdmin.php"; ?>
    <? if(isset($_GET["success"])) {?>
        <div class="col-md-4">
            <div class="alert alert-success" role="alert">
                Данные успешно сохранены!
            </div>
        </div>
    <?}?>
    <div class="col-md-12">
        <ul class="nav nav-pills mb-3">
            <? for($i = 0; $i < count($arrayOfDocsSection); $i++) {?>
                <li class="nav-item">
                    <a class="nav-link btn btn-primary m-1" href="docsByGroup.php?group=<?=$arrayOfDocsSection[$i]?>"><?=$arrayOfDocsSection[$i]?></a>
                </li>
            <?}?>
        </ul>
    </div>
<?  require_once '../partsOfPages/footer.php';?>