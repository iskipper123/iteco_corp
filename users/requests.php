<?  require_once '../dashbord/dashbord.php';?> 
<h1>Заявки</h1>
<? if($_SESSION["role"] == 1 || $_SESSION["login"] == 'vlada') {?> 
<?php

    $title = 'Заявки';
	
	$db = DB::getObject(); 
	$showTable = true;
	
	if(isset($_POST["pdf1"])) {
		$idItem = $_POST["idItem"];

		if(!isset($idItem))
			$error_delete = "Не была выбрана ни одна заявка";
		else {
			header("Location: pdfCustomer.php?edit=$idItem");
			exit;
		}
	}	 
	else if(isset($_POST["pdf2"])) {
		$idItem = $_POST["idItem"];

		if(!isset($idItem))
			$error_delete = "Не была выбрана ни одна заявка";
		else {
			header("Location: pdfCarrier.php?edit=$idItem");
			exit;
		}
	}
	else if(isset($_POST["editMarkedItem"])) {
		$idItem = $_POST["idItem"];

		if(!isset($idItem))
			$error_delete = "Не была выбрана ни одна заявка";
		else {
			header("Location: editRequest.php?edit=$idItem");
			exit;
		}
	}
	if(isset($_POST["deleteMarkedItems"])) {
		$idItem = $_POST["idItem"];
		if(!isset($idItem))
			$error_delete = "Не была выбрана ни одна заявка";
		else
			$addConfirmButton = true;

        echo $number_request;
	}
	
	if(isset($_POST["comfirmDelete"])) {

		$idItem = $_POST["idItem"];

        $result_set10 = $db->getRowWhere("requests", "id", $idItem);
        $row10 = $result_set10->fetch_assoc();
        $number_request = $row10[number];

		$db->delete("requests", $idItem);
        $db->deleteWhere("customs_clearance", "number", $number_request);
        $db->deleteWhere("payments", "number", $number_request);

		header("Location: requests.php?success");
		exit;
	}
	
	if(isset($_POST["filter"])) {
		$month = $_POST["month"];
		$year = $_POST["year"];
		$idUser = $_POST["idUser"];
		// echo "month: ".$month."<br>";
		// echo "year: ".$year."<br>";
		// echo "idUser: ".$idUser."<br>";
		
		if(strlen($month) == 0 || strlen($year) == 0) {
			$error_filter = "Необходимо обязательно заполнить оба поля с датой";
			$showTable = false;
		}
		else {
			$monthUNIX = getNumberOfMonth($month);
			$startDate = "01.".$monthUNIX.".".$year;
			$startDate = strtotime($startDate);
			$amountOfDaysInMonth = getAmountOfDaysInMonth($startDate);
			
			if($idUser == 0) 	$result_set = $db->getRequestsByMonth($startDate, $amountOfDaysInMonth);
			else 				$result_set = $db->getRequestsByMonthByUser($startDate, $amountOfDaysInMonth, $idUser);
		}
	}
	else {
		$today = date("Y-m-d");
		$startDayForCurrentMonth = getStartDay($today);
		$todayUnix = strtotime($today);
		$amountOfDaysInMonth = getAmountOfDaysInMonth($todayUnix);
		
		$result_set = $db->getRequestsByMonth($startDayForCurrentMonth, $amountOfDaysInMonth);
	}
	
	$result_set31 = $db->getRowWhereWhereOrder("users", "rights", 2, "isDeleted", 0, "name"); //для фильтра по магазинам
	
    require_once '../partsOfPages/head.php';
    require_once "../partsOfPages/menuForRequests.php"; ?>
    <? if(isset($_GET["success"])) {?>
        <div class="col-md-4">
            <div class="alert alert-success" role="alert">
                Данные успешно сохранены!
            </div>
        </div>
    <?}?>
    <div class="col-md-12">

        <hr>
        <? if($showTable) require_once "../lib/allRequests.php"; ?>
    </div>
            <? } ?>
<?  require_once '../partsOfPages/footer.php';?>

</div>
<?  require_once '../dashbord/dashbord_footer.php';?> 

