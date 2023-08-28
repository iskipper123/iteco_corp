<? 

 	require_once '../dashbord/dashbord.php';

    $title = 'Зарплата';
	
	$db = DB::getObject();
	$showTable = false;

	if(isset($_SESSION["id"])) {
		$idUser = $_SESSION["id"];
		$name_user = $db->getUserByID($idUser);   
		$name_user_name = $name_user->fetch_assoc(); 
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
	
    require_once '../partsOfPages/head.php';
    //require_once "../partsOfPages/menuForUser.php"; ?>
     <h1>Зарплата <? echo $name_user_name[name] ?></h1>
    <div class="col-md-12">
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
                    <input class="btn btn-warning btn-sm" type="submit" name="clear" value="Текущий месяц">
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