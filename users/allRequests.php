<?php

	require_once "../lib/db.php";
	require_once "../lib/functions.php";
	require_once "../lib/vars.php";
	
	$title = 'Все заявки';
	
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
	}
	
	if(isset($_POST["comfirmDelete"])) {
		$idItem = $_POST["idItem"];

		$db->delete("requests", $idItem);

		header("Location: requests.php?success");
		exit;
	}
	
	if(isset($_POST["filter"])) {
		$month = $_POST["month"];
		$year = $_POST["year"];
		
		if(strlen($month) == 0 || strlen($year) == 0) {
			$error_filter = "Необходимо обязательно заполнить оба поля";
			$showTable = false;
		}
		else {
			$monthUNIX = getNumberOfMonth($month);
			$startDate = "01.".$monthUNIX.".".$year;
			$startDate = strtotime($startDate);
			$amountOfDaysInMonth = getAmountOfDaysInMonth($startDate);
			
			$result_set = $db->getRequestsByMonth($startDate, $amountOfDaysInMonth);
		}
	}
	else {
		$today = date("Y-m-d");
		$startDayForCurrentMonth = getStartDay($today);
		$todayUnix = strtotime($today);
		$amountOfDaysInMonth = getAmountOfDaysInMonth($todayUnix);
		
		$result_set = $db->getRequestsByMonth($startDayForCurrentMonth, $amountOfDaysInMonth);
	}
	
	if(isset($_POST["cancel"])) {
		header("Location: requests.php");
		exit;		
	}
	
	$_SESSION["showZP"] = "0";
	$_SESSION["backRequests"] = "allRequests.php";
	
    require_once '../partsOfPages/head.php';
    require_once "../partsOfPages/menuForUser.php"; ?>
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
                        <h5 class="card-title">Вы действительно хотите удалить данную заявку?</h5>
                        <h6 class="card-subtitle mb-2 text-muted">
                        <?
                        $result_set4 = $db->getRowWhere("requests", "id", $idItem);
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
        <?php require_once "../partsOfPages/menuForRequests.php"; ?>
        <form name="" action="" method="post">
            <input class="btn btn-danger btn-sm" type="submit" name="cancel" value="Вернуться назад">
            <a class="btn btn-success btn-sm" href="addRequest.php?edit=<?=$idItem?>"><i class="fas fa-plus"></i> Добавить заявку</a> <br /><br />
        </form>
        <hr>
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
                <div class="form-group col-md-3"> 
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
        <hr>
        <? if($showTable) require_once "../lib/allRequests.php"; ?>
    </div>
<?  require_once '../partsOfPages/footer.php';?>