<?php
	$db = DB::getObject();
	$showTable = true;
	
	if(isset($_POST["editMarkedItem"])) {
		$idItem = $_POST["idItem"];

		if(!isset($idItem))
			$error_delete = "Не была выбрана ни одна заявка";
		else {
			header("Location: editGetRequest.php?edit=$idItem");
			exit;
		}
	}
	if(isset($_POST["addToRequest"])) {
		$idItem = $_POST["idItem"];

		if(!isset($idItem))
			$error_delete = "Не была выбрана ни одна заявка";
		else {
			$result_set = $db->getRowWhere("get_requests", "id", $idItem);
			$row = $result_set->fetch_assoc();	
			
			if($row[status] == 1) {
				$error_delete = "Заявка уже была переведена на статус 'В работе'";
			}
			else {
				header("Location: getRequestToRequest.php?edit=$idItem");
				exit;
			}
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

		$db->delete("get_requests", $idItem);

		header("Location: getRequests.php?success");
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
			
			if($_SESSION["role"] == 1) 		$result_set = $db->getGetRequestsByMonth($startDate, $amountOfDaysInMonth);
			else if($_SESSION["role"] == 2) $result_set = $db->getGetRequestsByMonthByManager($startDate, $amountOfDaysInMonth, $_SESSION["id"]);
		}
	}
	else if(isset($_POST["filterByEmployee"])) {
		$idUser = $_POST["idUser"];
		
		if(strlen($idUser) == 0) {
			$error_filterByEmployee = "Не сделан выбор";
			$showTable = false;
		}
		else $result_set = $db->getGetRequestsByManager($idUser);
	}
	else {
		$today = date("Y-m-d");
		$startDayForCurrentMonth = getStartDay($today);
		$todayUnix = strtotime($today);
		$amountOfDaysInMonth = getAmountOfDaysInMonth($todayUnix);
		
		if($_SESSION["role"] == 1) 		$result_set = $db->getGetRequestsByMonth($startDayForCurrentMonth, $amountOfDaysInMonth);
		else if($_SESSION["role"] == 2)	$result_set = $db->getGetRequestsByMonthByManager($startDayForCurrentMonth, $amountOfDaysInMonth, $_SESSION["id"]);
	}
	
	if($_SESSION["role"] == 1) $result_set31 = $db->getRowWhereWhereOrder("users", "rights", 2, "isDeleted", 0, "name"); //для фильтра по сотрудникам
	
    require_once '../partsOfPages/head.php';
 
    if($_SESSION["userType"] == 1) 		require_once "../partsOfPages/menuForAdmin.php"; 
    else if($_SESSION["userType"] == 2) require_once "../partsOfPages/menuForUser.php"; ?>
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
                            $result_set4 = $db->getRowWhere("get_requests", "id", $idItem);
                            $row4 = $result_set4->fetch_assoc(); 
                        ?>
                        <input type="hidden" name="idItem" value="<?=$idItem?>">
                        </h6>
                        <input class="btn btn-danger btn-sm" type="submit" name="comfirmDelete" value="Удалить">
                        <input class="btn btn-secondary btn-sm" type="submit" name="cancelDelete" value="Отменить">
                    </div>
                </div>
            </form>
            <hr>
        <?}?>
        <?php if ($_SESSION[manager_variant] == 'cargo' || $_SESSION[manager_variant] == 'all') { ?>
        <a class="btn btn-success btn-sm" href="addGetRequest.php"><i class="fas fa-plus"></i> Добавить заявку</a> <br /><br />
         <?php } ?>
        <hr style="clear: both;">
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
        <hr>
        <? if($_SESSION["role"] == 1) { ?>
            <form name="" action="" method="post">
                <div class="row">
                    <div class="form-group col-md-2">
                        <select class="form-control form-control-sm" name="idUser">
                            <? if ($idUser == "") { ?>
                                <option selected="selected">Выберите сотрудника</option>
                            <?} else {
                                $result_set30 = $db->getRowWhere("users", "id", $idUser);
                                $row30 = $result_set30->fetch_assoc(); ?>
                                <option selected="selected" value="<?=$_POST["idUser"]?>"><?=$row30["name"]?></option>
                            <?}?>
                            <? while (($row31 = $result_set31->fetch_assoc()) != false) { ?>
                                <? if ($row30["name"] != $row31[name]) { ?>
                                    <option value="<?=$row31[id]?>"><?=$row31[name]?></option>
                                <?}?>
                            <?}?>
                        </select>
                    </div>
                    <div class="form-group col-md-3"> 
                        <input class="btn btn-secondary btn-sm" type="submit" name="filterByEmployee" value="Применить">
                        <input class="btn btn-warning btn-sm" type="submit" name="clear" value="Сбросить фильтр">
                    </div>
                    <? if(isset($error_filterByEmployee)&&$error_filterByEmployee!='') {?>
                        <div class="col-md-6">
                            <div class="alert alert-danger" role="alert">
                                <?=$error_filterByEmployee?>
                            </div>
                        </div>
                    <?}?>
                </div>
            </form>
            <hr>
        <?}?>
        
        <? if($showTable) require_once "../lib/allGetRequests.php"; ?>
    </div>
<?  require_once '../partsOfPages/footer.php';?>