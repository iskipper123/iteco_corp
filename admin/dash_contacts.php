
<?   
    require_once '../dashbord/dashbord.php';?> 
<h1>Контакты</h1>
<div id="work">
	<?php
	session_start();

    $title = 'Заявки';
	
	$db = DB::getObject(); 
	$showTable = true; 

		$today = date("Y-m-d");
		$startDayForCurrentMonth = getStartDay($today);
		$todayUnix = strtotime($today);
		$amountOfDaysInMonth = getAmountOfDaysInMonth($todayUnix);
		
		$result_set = $db->getRequestsByMonth($startDayForCurrentMonth, $amountOfDaysInMonth);

	$result_set31 = $db->getRowWhereWhereOrder("users", "rights", 2, "isDeleted", 0, "name"); //для фильтра по магазинам
	?>
    
    <div class="col-md-12">
      <?php

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
        <ul class="nav nav-pills mb-3">
            <? while (($row = $result_set->fetch_assoc()) != false) { ?>
                <li class="nav-item">
                    <a class="nav-link btn btn-primary m-1" href="clientsByManager.php?idManager=<?=$row[id]?>"><?=$row[name]?></a>
                </li>
            <?}?>
        </ul>
    </div>
    </div>
<?  require_once '../partsOfPages/footer.php';?>

</div>
<?  require_once '../partsOfPages/footer.php';?>

</div>
<?  require_once '../dashbord/dashbord_footer.php';?> 

