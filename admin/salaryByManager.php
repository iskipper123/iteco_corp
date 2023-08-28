<? 
 require_once '../dashbord/dashbord.php';?> 

<?php
	
	require_once "../lib/checkWasUserLoginedAndIsAdmin.php";
	require_once "../lib/db.php";
	require_once "../lib/functions.php";
    require_once "../lib/vars.php";  
    
    $title = 'Зарплата';
	
	$db = DB::getObject();
	$showTable = false;
	
	if(isset($_GET["idManager"])) {
		$idUser = $_GET["idManager"];
		$idManager = $_GET["idManager"];
		$name_user = $db->getUserByID($idUser); 
		$name_user_name = $name_user->fetch_assoc();
	}
	



	if(isset($_POST["editMarkedItem"])) {
		$idItem = $_POST["idItem"];

		if(!isset($idItem))
			$error_delete = "Не была выбрана ни одна строка";
		else {
			$_SESSION["backSalary"] = $idUser;
			header("Location: editSalary.php?edit=$idItem");
			exit;
		}
	}
	if(isset($_POST["deleteMarkedItems"])) {
		$idItem = $_POST["idItem"];
		if(!isset($idItem))
			$error_delete = "Не была выбрана ни одна строка";
		else
			$addConfirmButton = true;
	}
	
	if(isset($_POST["comfirmDelete"])) {
		$idItem = $_POST["idItem"];

		$db->delete("salary", $idItem);

		header("Location: salary.php?success");
		exit;
	}
	
	if(isset($_POST["clear"])) {
		unset($_SESSION["month"]);
		unset($_SESSION["year"]);
		
		header("Location: salary.php");
		exit;
	}
	
	if(isset($_POST["filter"])) {
		$month = $_POST["month"];
		$year = $_POST["year"];



		
		if(strlen($month) == 0 || strlen($year) == 0) {
			$error_filter = "Необходимо обязательно заполнить все поля";
		}
		else {
			$monthUNIX = getNumberOfMonth($month);
			$startDate = "01.".$monthUNIX.".".$year;
			$startDate = strtotime($startDate);
			$amountOfDaysInMonth = getAmountOfDaysInMonth($startDate);


			$monthUNIX_precedent = getNumberOfMonth_precedent($month); 
			$startDate_precedent = "01.".$monthUNIX_precedent.".".$year;


		if ($year == '2023' && $month == 'Январь') {
			$startDate_precedent = "01.".$monthUNIX_precedent.".2022"; //Исправление переходных данных по расстаможкам с декабря прошлого года
		}



			$startDate_precedent = strtotime($startDate_precedent);
			$amountOfDaysInMonth_precedent = getAmountOfDaysInMonth($startDate_precedent);
			
			$result_set = $db->getMonthSalary($startDate, $amountOfDaysInMonth, $idUser);
			
			//рассчитать з/п сотрудника за заявки (коммиссион)
			$commission = getCommission($startDate, $amountOfDaysInMonth, $idUser);
			$commission_cusom_clearence = getCustom_clearenceByMonthByUser($startDate, $amountOfDaysInMonth, $idUser);
			$getsalary_fromCustomclearence = getsalary_fromCustomclearence($startDate, $amountOfDaysInMonth, $idUser);
			$difference_custom = difference_custom($startDate, $amountOfDaysInMonth, $idUser);

			$difference_request = difference_request($startDate, $amountOfDaysInMonth, $startDate_precedent, $amountOfDaysInMonth_precedent, $idUser);
			$difference_request_no_custom = difference_request_no_custom($startDate, $amountOfDaysInMonth, $startDate_precedent, $amountOfDaysInMonth_precedent, $idUser);
			
			$_SESSION["month"] = $month;
			$_SESSION["year"] = $year;

			setcookie("precedent_month", $startDate_precedent);
			setcookie("year", $year);
			setcookie("month", $month);
			
			$showTable = true;
		}
	}
	else {
		$today = date("Y-m-d");
		$prev_date = date('Y-m-d', strtotime('first day of last month'));
		$startDate = getStartDay($today);
		$startDate_precedent = getStartDay($prev_date);
		
		// $startDate = strtotime($startDate);
		$amountOfDaysInMonth = getAmountOfDaysInMonth($startDate);
		$amountOfDaysInMonth_precedent = getAmountOfDaysInMonth($startDate_precedent);
		
		$result_set = $db->getMonthSalary($startDate, $amountOfDaysInMonth, $idUser);
		
		//рассчитать з/п сотрудника за заявки (коммиссион)
		$commission = getCommission($startDate, $amountOfDaysInMonth, $idUser);
		$commission_cusom_clearence = getCustom_clearenceByMonthByUser($startDate, $amountOfDaysInMonth, $idUser);
		$getsalary_fromCustomclearence = getsalary_fromCustomclearence($startDate, $amountOfDaysInMonth, $idUser);
		$difference_custom = difference_custom($startDate, $amountOfDaysInMonth, $idUser);
		$difference_request = difference_request($startDate, $amountOfDaysInMonth, $startDate_precedent, $amountOfDaysInMonth_precedent, $idUser);
		$difference_request_no_custom = difference_request_no_custom($startDate, $amountOfDaysInMonth, $startDate_precedent, $amountOfDaysInMonth_precedent, $idUser);
		
		$month = getNameOfMonth($startDate);
		$year = date("Y", $startDate);
		
		$showTable = true;
	}
	
	if(isset($_POST["cancel"])) {
		header("Location: salary.php");
		exit;	
	}
		
    require_once '../partsOfPages/head.php';
    require_once "../partsOfPages/menuForAdmin.php"; ?>
    <h1>Зарплата <? echo $name_user_name[name] ?></h1>
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
                        <h5 class="card-title">Вы действительно хотите удалить запись?</h5>
                        <h6 class="card-subtitle mb-2 text-muted">
                            <? $result_set4 = $db->getRowWhere("salary", "id", $idItem);
                            $row4 = $result_set4->fetch_assoc(); ?>
                            <input type="hidden" name="idItem" value="<?=$idItem?>">
                            <? //echo $row4[name];?> 
                        </h6>
                        <input class="btn btn-danger btn-sm" type="submit" name="comfirmDelete" value="Удалить">
                        <input class="btn btn-secondary btn-sm" type="submit" name="cancelDelete" value="Отменить">
                    </div>
                </div>
            </form>
            <hr>
        <?}?>
        <? if($showTable) {?>
            <a class="btn btn-success btn-sm" href="addSalary.php?idUser=<?=$idUser?>"><i class="fas fa-plus"></i> Добавить запись</a> <br /><br />
        <?}?>
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
                <div class="form-group col-md-4"> 
                    <input class="btn btn-secondary btn-sm" type="submit" name="filter" value="Применить">
                    <input class="btn btn-warning btn-sm" type="submit" name="clear" value="Назад">
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
        <? if($showTable) require_once "../lib/salary.php"; ?>
    </div>
<?  require_once '../partsOfPages/footer.php';?>

<?  require_once '../dashbord/dashbord_footer.php';?> 