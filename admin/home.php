<?php $ip_server = $_SERVER['SERVER_ADDR'];

if ($ip_server == '88.212.247.76') { ?>
 	<? 
     require_once '../dashbord/dashbord.php';?> 
<style>
    .get_alpha.all_requests li a{width: auto; height: auto;}
    .get_beta.all_requests li a{width: auto; height: auto;}
</style>

<div class="req">
	<table>
		<td>

<h1 style="padding-left: 20px;">Откуда</h4>
<input class="form-control<?=isset($error_name)&&$error_name!=''?' is-invalid':''?>" type="text" name="FromName" id="FromName" value="">

 
    <script defer>
        function autocompletename(){
            var ajax = new XMLHttpRequest();
            ajax.open("GET", "../lib/autocompletename.php", true);
            ajax.onload = function () {
                var list = JSON.parse(ajax.responseText);
                new Awesomplete(document.querySelector("#FromName"), {minChars: 1, list: list}); 
            };
            ajax.send();
        }
        document.getElementById('FromName').addEventListener("awesomplete-select", function(event) {
            let extra = event.text.extraData;
        });
    $(function(){
            autocompletename();
        });
  </script>



<ul class="get_alpha all_requests" >
	<li><a id="alpha" class="active">Все</a></li>
	<li><a id="alpha" type="Молдова">Молдова</a></li>
	<li><a id="alpha" type="Россия">Россия</a></li>
	<li><a id="alpha" type="Нидерланды">Нидерланды</a></li>
	<li><a id="alpha" type="Турция">Турция</a></li>
	<li><a id="alpha" type="Польша">Польша</a></li>
	<li><a id="alpha" type="Украина">Украина</a></li>
	<li><a id="alpha" type="Испания">Испания</a></li>
	<li><a id="alpha" type="Литва">Литва</a></li>
	<li><a id="alpha" type="Финляндия">Финляндия</a></li>
	<li><a id="alpha" type="Беларусь">Беларусь</a></li>
</ul></td>
<td>
	<h1 style="padding-left: 20px;">Куда</h4>
<ul class="get_beta all_requests">
	<li><a id="beta" class="active">Все</a></li>
	<li><a id="beta" type="Молдова">Молдова</a></li>
	<li><a id="beta" type="Россия">Россия</a></li>
	<li><a id="beta" type="Нидерланды">Нидерланды</a></li> 
	<li><a id="beta" type="Турция">Турция</a></li> 
	<li><a id="beta" type="Польша">Польша</a></li> 
	<li><a id="beta" type="Украина">Украина</a></li> 
	<li><a id="beta" type="Испания">Испания</a></li> 
	<li><a id="beta" type="Литва">Литва</a></li> 
	<li><a id="beta" type="Финляндия">Финляндия</a></li> 
	<li><a id="beta" type="Беларусь">Беларусь</a></li> 
</ul>
</td>
</table>
</div>

<script>
    $('.get_alpha li a').click(function() {
        $(".get_alpha li a").removeClass('active');
        $(this).addClass('active');
   $("#work_space").load('./ajax_all_request_home.php',  {
           alpha: $('.get_alpha li a.active').attr('type'),
           beta: $('.get_beta li a.active').attr('type')
       }); 
	});

	$('.get_beta li a').click(function() {
        $(".get_beta li a").removeClass('active');
           $(this).addClass('active');
   			
   		$("#work_space").load('./ajax_all_request_home.php',  {
           alpha: $('.get_alpha li a.active').attr('type'),
           beta: $('.get_beta li a.active').attr('type')
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
    require_once "../partsOfPages/menuForAdmin.php"; ?>
    <? if(isset($_GET["success"])) {?>
        <div class="col-md-4">
            <div class="alert alert-success" role="alert">
                Данные успешно сохранены!
            </div>
        </div>
    <?}?>
    <div class="col-md-12">
        <div id="work_space">
            <? if($showTable) require_once "../lib/allRequests.php"; ?>
        </div>
    </div>
<?  require_once '../partsOfPages/footer.php';?>

</div>
<?  require_once '../dashbord/dashbord_footer.php';?> 


<?php } else { ?>
<?
	require_once "../lib/db.php";
    require_once '../dashbord/dashbord.php';?> 
<h1>Дашборд</h1>
<div id="work">
	<?php

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
<?php } ?>