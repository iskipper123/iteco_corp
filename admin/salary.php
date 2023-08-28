<?php

    require_once '../dashbord/dashbord.php';
	
	require_once "../lib/db.php";
	require_once "../lib/functions.php";
    require_once "../lib/vars.php";  
    
    $title = 'Зарплата';
	
	$db = DB::getObject();
	$result_set = $db->getRowWhereWhereOrder("users", "rights", 2, "isDeleted", 0, "name");
	
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
        <p>Выберите сотрудника</p>
        <ul class="nav nav-pills mb-3">
            <? while (($row = $result_set->fetch_assoc()) != false) { ?>
                <li class="nav-item">
                    <a class="nav-link btn btn-primary m-1" href="salaryByManager.php?idManager=<?=$row[id]?>"><?=$row[name]?></a>
                </li>
            <?}?>
        </ul>
    </div>

<?  require_once '../partsOfPages/footer.php';?>

<?  require_once '../dashbord/dashbord_footer.php';?> 