<?php
  require_once '../dashbord/dashbord.php';?> 
<h1>Заявки</h1>
<?php
	require_once "../lib/db.php";
	require_once "../lib/vars.php";
    require_once "../lib/functions.php";
    
    $title = 'Растаможка';
	
	$db = DB::getObject();
	$showTable = true;

	$user_id = (int)$_SESSION[id];

	
	if(isset($_POST["editMarkedItem"])) {
		
		$idItem = $_POST["idItem"];


		if(!isset($idItem))
			$error_delete = "Не была выбрана ни одна заявка";
		else {
			header("Location: editCustomsClearance.php?edit=$idItem");
			exit;
		}
	}
	if(isset($_POST["deleteMarkedItems"])) {
		$idItem = $_POST["idItem"];
		if(!isset($idItem))
			$error_delete = "Не была выбрана ни одна заявка";
		else
			$addConfirmButton = true;
	}
	if(isset($_POST["writeDeliveryNote"])) {
		$idItem = $_POST["idItem"];
		if(!isset($idItem))
			$error_delete = "Не была выбрана ни одна заявка";
		else {
			$db->writeDeliveryNote($idItem);
			
			$result_set = $db->getRowWhere("customs_clearance", "id", $idItem);
			$row = $result_set->fetch_assoc();			
			
			$db->addLog($row[customer], time(), "Растаможка. Отписана накладная, в которой контрагент выступает в качестве заказчика. Заявка №$row[number]", 1, $idItem, $_SESSION["id"]);
			$db->addLog($row[carrier], time(), "Растаможка. Отписана накладная, в которой контрагент выступает в качестве перевозчика. Заявка №$row[number]", 1, $idItem, $_SESSION["id"]);
			
			header("Location: customsClearance.php?success");
			exit;
		}
	}
	
	if(isset($_POST["comfirmDelete"])) {
		$idItem = $_POST["idItem"];
		
		$result_set4 = $db->getRowWhere("customs_clearance", "id", $idItem);
		$row4 = $result_set4->fetch_assoc();
				
		if(strlen($row4[path]) != 0) unlink($row4[path]);
		$db->delete("customs_clearance", $idItem);

		header("Location: customsClearance.php?success");
		exit;
	}
	
	if(isset($_POST["filter"])) {
		$month = $_POST["month"];
		$year = $_POST["year"];
		
		if(strlen($month) == 0 || strlen($year) == 0) {
			$error_filter = "Необходимо обязательно заполнить оба поля";
			$showTable = false;
			// $result_set = $db->getAllOrder("customs_clearance", "date DESC");
		}
		else {
			$monthUNIX = getNumberOfMonth($month);
			$startDate = "01.".$monthUNIX.".".$year;
			// echo "startDate: ".$startDate."<br>";
			$startDate = strtotime($startDate);
			// echo "startDate: ".$startDate."<br>";
			$amountOfDaysInMonth = getAmountOfDaysInMonth($startDate);
			// echo "amountOfDaysInMonth: ".$amountOfDaysInMonth."<br>";
			
			$result_set = $db->getCustomsClearanceByMonth_for_user($startDate, $amountOfDaysInMonth, $user_id);
		}
	}
	else {
		// $result_set = $db->getAllOrder("customs_clearance", "date DESC");
		//определение стартового дня
		$today = date("Y-m-d");
		$startDayForCurrentMonth = getStartDay($today);
		$todayUnix = strtotime($today);
		$amountOfDaysInMonth = getAmountOfDaysInMonth($todayUnix);
		// echo "startDayForCurrentMonth: ".$startDayForCurrentMonth."<br>";
		// echo "amountOfDaysInMonth: ".$amountOfDaysInMonth."<br>";
		
		$result_set = $db->getCustomsClearanceByMonth_for_user($startDayForCurrentMonth, $amountOfDaysInMonth, $user_id);
	} ?>
    <? if(isset($_GET["success"])) {?>
        <div class="col-md-4">
            <div class="alert alert-success" role="alert">
                Данные успешно сохранены!
            </div>
        </div>
    <?}?>
    <div class="col-md-12">
        <? if($addConfirmButton) {?>
            <form name="" action="" method="post">
            <div class="card col-md-5">
                <div class="card-body">
                    <h5 class="card-title">Вы действительно хотите удалить данную заявку на растаможку?</h5>
                    <h6 class="card-subtitle mb-2 text-muted">
                    <?
                    $result_set4 = $db->getRowWhere("customs_clearance", "id", $idItem);
                    $row4 = $result_set4->fetch_assoc(); ?>
                    <input type="hidden" name="idItem" value="<?=$idItem?>">
                    <? echo $row4[name];
                ?> </h6>
                    <input class="btn btn-danger btn-sm" type="submit" name="comfirmDelete" value="Удалить">
                    <input class="btn btn-secondary btn-sm" type="submit" name="cancelDelete" value="Отменить">
                </div>
            </div>
            </form>
            <hr>
        <?}?>
           <a class="add_client" href="addCustomsClearance.php"></a>
     
        <form name="" action="" method="post">
            <div class="row">
                <div class="form-group col-md-2">
                    <select class="form-control form-control-sm" name="month">
                        <option selected value="0">Выберите месяц</option>
                        <? for($i = 0; $i < count($arrayOfMonthesForFilter); $i++) {
                            if($month == $arrayOfMonthesForFilter[$i]) { ?>
                                <option selected="selected"><?=$arrayOfMonthesForFilter[$i] ?></option>
                            <?}
                            else {?>
                                <option><?=$arrayOfMonthesForFilter[$i] ?></option>
                            <?}?>
                        <?}?>
                    </select>
                </div>
                <div class="form-group col-md-2">  
                    <select class="form-control form-control-sm" name="year">
                        <option selected value="0">Выберите год</option>
                        <? for($i = 0; $i < count($arrayOfYearsForFilter); $i++) {
                            if($year == $arrayOfYearsForFilter[$i]) { ?>
                                <option selected="selected"><?=$arrayOfYearsForFilter[$i] ?></option>
                            <?}
                            else {?>
                                <option><?=$arrayOfYearsForFilter[$i] ?></option>
                            <?}?>
                        <?}?>
                    </select>
                </div>  
                <div class="form-group col-md-8"> 
                    <input class="btn btn-secondary btn-sm" type="submit" name="filter" value="Применить">
                    <input class="btn btn-warning btn-sm" type="submit" name="clear" value="Сбросить фильтр">
                         <a class="add_client" href="addCustomsClearance.php" style="float: right;" id="add_client"></a>
                </div>
                <? if(isset($error_filter)&&$error_filter!='') {?>
                    <div class="col-md-6">
                        <div class="alert alert-danger" role="alert">
                            <?=$error_filter?>
                        </div>
                    </div>
                <?}?>
            </div>
        </form>
        <hr>
        <? if($showTable) require_once "../lib/allCustomsClearance.php"; ?>
    </div>
<?  require_once '../partsOfPages/footer.php';?>

</div>
<?  require_once '../dashbord/dashbord_footer.php';?> 

