<?php  require_once '../dashbord/dashbord.php';?> 
<h1>Заявки</h1>
<style>
    .get_alpha.all_requests li a{width: auto; height: auto;}
</style>
<ul class="get_alpha all_requests">
<li><a id="alpha" class="active">Все</a></li>
<li><a id="alpha" type="activ">Активные</a></li>
<li><a id="alpha" type="end">Завершенные</a></li>
<li><a id="alpha" type="arhive">Архив</a></li>
</ul>
<script>
    $('.get_alpha li a').click(function() {
        $(".get_alpha li a").removeClass('active');
        $(this).addClass('active');
        var month = $('select[name=month] option:selected').text();
        var year = $('select[name=year] option:selected').text();
        var idUser = $('select[name=idUser] option:selected').val();
       

   $("#work_space").load('./ajax_all_request.php',  {
           alpha: $(this).attr('type'),
           month: month,
           year: year,
           idUser: idUser
       }); 
});
 </script>


<?php

    $title = 'Заявки';
	
	$db = DB::getObject(); 
	$showTable = true;
	
	if(isset($_POST["pdf1"])) { 
		$idItem = $_POST["idItem"];

		if(!isset($idItem))
			$error_delete = "Не была выбрана ни одна заявка";
		else {
			header("Location: editor/pdfCustomer2.php?edit=$idItem");
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

        if ($_SESSION["login"] == 'vlada') {
            $result_set = $db->getRequestsByMonth($startDayForCurrentMonth, $amountOfDaysInMonth);
        }else {
		  $result_set = $db->getRequestsByMonthByUser($startDate, $amountOfDaysInMonth, $_SESSION["id"]);
         }
	}
	
	$result_set31 = $db->getRowWhereWhereOrder("users", "rights", 2, "isDeleted", 0, "name"); //для фильтра по магазинам
	
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
        <!-- <a class="btn btn-success btn-sm" href="addRequest.php"><i class="fas fa-plus"></i> Добавить заявку</a> <br /><br /> -->
       
        <form name="" action="" method="post">
            <div class="row" style="margin:0; width:100%;">
                <div class="form-group col-md-2">
                    <select class="form-control form-control-sm" name="month">
                        <option selected value="">Выберите месяц</option>
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
                        <option selected value="">Выберите год</option>
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

                <?php if ($_SESSION["login"] == 'vlada') { ?>
                                  <div class="form-group col-md-4">
                    <select class="form-control form-control-sm" name="idUser">
                        <? if ($idUser == "" || $idUser == "Выберите сотрудника") { ?>
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
                <?php }else { ?>
                <div class="form-group col-md-4">
                    <select class="form-control form-control-sm" name="idUser">
                        <? 
                            $result_set30 = $db->getRowWhere("users", "id", $_SESSION[id]);
                            $row30 = $result_set30->fetch_assoc(); ?>
                            <option selected="selected" value="<?=$_SESSION[id]?>"><?=$row30["name"]?></option>
               
                    </select>
                </div>
            <?php } ?>
                <div class="form-group col-md-4"> 
                    <input class="btn btn-secondary btn-sm" type="submit" name="filter" value="Применить" style="line-height: 38px;vertical-align: top;">
                    <input class="btn btn-warning btn-sm" type="submit" name="clear" value="Сбросить фильтр" style="line-height: 38px;vertical-align: top;">
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
        <div id="work_space">
            <? if($showTable) require_once "../lib/allRequests.php"; ?>
        </div>
    </div>
<?  require_once '../partsOfPages/footer.php';?>

</div>
<?  require_once '../dashbord/dashbord_footer.php';?> 

