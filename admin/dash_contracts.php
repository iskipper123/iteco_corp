
<?  
	require_once '../dashbord/dashbord.php';?> 
<h1>Дашборд</h1>
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
        <? if($showTable) require_once "../lib/allRequests_dash.php"; ?>
    </div>
<?  require_once '../partsOfPages/footer.php';?>

</div>
<?  require_once '../dashbord/dashbord_footer.php';?> 
