<?  
	require_once '../dashbord/dashbord.php';?> 
<h1>Договоры Заказчики</h1>
<?php

    $title = 'Оплаты от заказчиков';
	
	$db = DB::getObject();
	$showTable = true;
	
	if(isset($_POST["editMarkedItem"])) {
		$idItem = $_POST["idItem"];

		if(!isset($idItem))
			$error_delete = "Не был выбран ни один платеж";
		else {
			$_SESSION["contractorType"] = $arrayOfContractorsTypes[0];
			header("Location: editPayment.php?edit=$idItem");
			exit;
		}
	}
	else if(isset($_POST["deleteMarkedItems"])) {
		$idItem = $_POST["idItem"];
		if(!isset($idItem))
			$error_delete = "Не был выбран ни один платеж";
		else
			$addConfirmButton = true;
	}
	
	if(isset($_POST["comfirmDelete"])) {
		$idItem = $_POST["idItem"];

		$db->delete("payments", $idItem);

		header("Location: paymentsCustomers.php?success");
		exit;
	}
	
	if(isset($_POST["addMarkAboutPayment"])) {
		$idItem = $_POST["idItem"];
		if(!isset($idItem))
			$error_delete = "Не был выбран ни один платеж";
		else {
			$result_set0 = $db->getRowWhere("payments", "id", $idItem);
			$row0 = $result_set0->fetch_assoc();
			
			if($row0[paymentWasDidAlreary] == 1) {
				$error_delete = "На данный платеж ужа ранее была поставлена отметка о том, что он выполнен.";
			}
			
			else {
				$db->changePaymentStatus($idItem);
				
				$db->addLog($row0[customer], time(), "Оплата. Платеж №$row0[number] выполнен.", 4, $idItem, $_SESSION["id"]);
				
				header("Location: paymentsCustomers.php?success");
				exit;			
			}
		}
	}
	
	if(isset($_POST["add"])) {
		$_SESSION["contractorType"] = $arrayOfContractorsTypes[0];
		header("Location: addPaymentsCustomers.php");
		exit;		
	}
	
	$_SESSION["contractorType"] = $arrayOfContractorsTypes[0];
	
	if(isset($_POST["clear"])) {
		unset($_SESSION["month"]);
		unset($_SESSION["year"]);
	}
	
	if(isset($_POST["filter"])) {
		$month = $_POST["month"];
		$year = $_POST["year"];
		
		if(strlen($month) == 0 || strlen($year) == 0) {
			$error_filter = "Необходимо обязательно заполнить оба поля";
			$showTable = false;
		}
		else {
			$_SESSION["month"] = $month;
			$_SESSION["year"] = $year;
			
			$monthUNIX = getNumberOfMonth($month);
			$startDate = "01.".$monthUNIX.".".$year;
			$startDate = strtotime($startDate);
			$amountOfDaysInMonth = getAmountOfDaysInMonth($startDate);
			
			$result_set = $db->getPaymentsByMonth($startDate, $amountOfDaysInMonth, $arrayOfContractorsTypes[0]);
		}
	}
	else if(isset($_SESSION["month"])) {
		$month = $_SESSION["month"];
		$year = $_SESSION["year"];
		
		$monthUNIX = getNumberOfMonth($month);
		$startDate = "01.".$monthUNIX.".".$year;
		$startDate = strtotime($startDate);
		$amountOfDaysInMonth = getAmountOfDaysInMonth($startDate);
		
		$result_set = $db->getPaymentsByMonth($startDate, $amountOfDaysInMonth, $arrayOfContractorsTypes[0]);
	}
	else {
		$today = date("Y-m-d");
		$startDayForCurrentMonth = getStartDay($today);
		$todayUnix = strtotime($today);
		$amountOfDaysInMonth = getAmountOfDaysInMonth($todayUnix);
		
		$result_set = $db->getPaymentsByMonth($startDayForCurrentMonth, $amountOfDaysInMonth, $arrayOfContractorsTypes[0]);
	}
	
	if(isset($_POST["cancel"])) {
		header("Location: customerCarrier.php");
		exit;
	}
	
    require_once '../partsOfPages/head.php';
    require_once "../partsOfPages/menuForAdmin.php"; ?>
    <? if(isset($_GET["success"])) {?>
        <div class="col-md-4">
            <div class="alert alert-success" role="alert">
                Данные успешно сохранены!
            </div>
        </div>
    <?}?>
    <div class="col-md-12 payment">
        <? if($addConfirmButton) {?>
            <form name="" action="" method="post">
                <div class="card col-md-5">
                    <div class="card-body">
                        <?
                            $result_set4 = $db->getRowWhere("payments", "id", $idItem);
                            $row4 = $result_set4->fetch_assoc(); 
                        ?>
                        <h5 class="card-title">Вы действительно хотите удалить плажет №<?=$row4[number]?>?</h5>
                        
                        <input type="hidden" name="idItem" value="<?=$idItem?>">
                        <input class="btn btn-danger btn-sm" type="submit" name="comfirmDelete" value="Удалить">
                        <input class="btn btn-secondary btn-sm" type="submit" name="cancelDelete" value="Отменить">
                    </div>
                </div>
            </form>
            <hr>
        <?}?>
           <form name="" action="" class="mb-3" method="post">
            <button class="add_client" type="submit" name="add"></button>
        </form>
      
        <form name="" action="" method="post">
            <div class="row">
                <div class="form-group col-md-3">
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
                <div class="form-group col-md-3">  
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
                <div class="form-group col-md-6"> 
                    <input class="btn btn-secondary btn-sm" type="submit" name="filter" value="Применить">
                    <input class="btn btn-warning btn-sm" type="submit" name="clear" value="Сбросить фильтр">
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
    
  
        <? if($showTable) require_once "../lib/allPayments.php"; ?>
    </div>
<?  require_once '../partsOfPages/footer.php';?>

</div>
<?  require_once '../dashbord/dashbord_footer.php';?> 