<?php
	session_start();
	require_once "../lib/checkWasUserLoginedAndIsAdmin.php";
    require_once "../lib/db.php";
    
    $title = 'Клиенты';
	 
	$db = DB::getObject();
	$result_set = $db->getRowWhereWhereOrder("users", "rights", 2, "isDeleted", 0, "id DESC");

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
        <a class="btn btn-success btn-sm" href="addClient.php"><i class="fas fa-plus"></i> Добавить клиента</a> 
        <p>Выберите сотрудника, чтобы отобразить всех его клиентов</p>
        <ul class="nav nav-pills mb-3">
            <? while (($row = $result_set->fetch_assoc()) != false) { ?>
                <li class="nav-item">
                    <a class="nav-link btn btn-primary m-1" href="clientsByManager.php?idManager=<?=$row[id]?>"><?=$row[name]?></a>
                </li>
            <?}?>
        </ul>
    </div>
<?  require_once '../partsOfPages/footer.php';?>