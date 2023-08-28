<?php 
session_start();
    require_once '../dashbord/dashbord.php';?> 
<h1>Заказчики</h1>

<ul class="get_alpha">
<li><a id="alpha" type="A">A</a></li>
<li><a id="alpha" type="B">B</a></li>
<li><a id="alpha" type="C">C</a></li>
<li><a id="alpha" type="D">D</a></li>
<li><a id="alpha" type="E">E</a></li>
<li><a id="alpha" type="F">F</a></li>
<li><a id="alpha" type="G">G</a></li> 
<li><a id="alpha" type="H">H</a></li>
<li><a id="alpha" type="I">I</a></li>
<li><a id="alpha" type="J">J</a></li> 
<li><a id="alpha" type="K">K</a></li>
<li><a id="alpha" type="L">L</a></li>
<li><a id="alpha" type="M">M</a></li>
<li><a id="alpha" type="N">N</a></li>
<li><a id="alpha" type="O">O</a></li>
<li><a id="alpha" type="P">P</a></li>
<li><a id="alpha" type="Q">Q</a></li>
<li><a id="alpha" type="R">R</a></li>
<li><a id="alpha" type="S">S</a></li>
<li><a id="alpha" type="T">T</a></li>
<li><a id="alpha" type="U">U</a></li>
<li><a id="alpha" type="V">V</a></li>
<li><a id="alpha" type="W">W</a></li>
<li><a id="alpha" type="X">X</a></li>
<li><a id="alpha" type="Y">Y</a></li>
<li><a id="alpha" type="Z">Z</a></li>
<li><a class="active" id="alpha" type="" style="width: 45px;">RUS</a></li>
<li style="margin-left:10px;"><a style="width: auto;" id="alpha" type="without_manager">Свободные фирмы</a></li>
</ul>
      
<script>


    $('.get_alpha li a').click(function() {
        $(".get_alpha li a").removeClass('active');
        $(this).addClass('active');

   $("#work_space").load('./ajax_all_contractors.php',  {
           alpha: $(this).attr('type'),
           contr_or_carr: 'Заказчик'
       });
});


 </script>
<?php

    
    $title = 'Список заказчиков';
	
	
    $db = DB::getObject(); 


    date_default_timezone_set('Etc/GMT+0');
    $date = new DateTime();
    $date1 = $date->setTime(00, 00, 00);
 $current_date = $date1->getTimestamp();

 $current_date_up = date("d.m.Y"); 


	 
    $result_set = $db->get_contractors_rus("contractors", "contractorsType", $arrayOfContractorsTypes[0], "isDeleted", 0, "name"); 
	if(isset($_POST["editMarkedItem"])) {
		$idItem = $_POST["idItem"];


		if(!isset($idItem))
			$error_delete = "Не был выбран ни один заказчик";
		else {

			$_SESSION["contractorType"] = $arrayOfContractorsTypes[0];
			header("Location:editCustomer.php?edit=$idItem");
			exit;
		}
	}
	else if(isset($_POST["deleteMarkedItems"])) {
		$idItem = $_POST["deleteMarkedItems"];
		if(!isset($idItem))
			$error_delete = "Не был выбран ни один заказчик";
		else
			$addConfirmButton = true;
	}
	
	if(isset($_POST["comfirmDelete"])) {
		$idItem = $_POST["idItem"];

		$db->softDelete("contractors", $idItem);

		header("Location: customers.php?success");
		exit;
	}
	
	if(isset($_POST["cancel"])) {
		header("Location: customers.php");
		exit;
	}
	
	if(isset($_POST["add"])) {
		$_SESSION["contractorType"] = $arrayOfContractorsTypes[0];
		header("Location: addCustomer.php");
		exit;		
	}
	
	$_SESSION["contractorType"] = $arrayOfContractorsTypes[0];
	$_SESSION["backHistory"] = "customers.php"; 
	
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
                        <h5 class="card-title">Вы действительно хотите удалить заказчика?</h5>
                        <h6 class="card-subtitle mb-2 text-muted">
                        <?
                        $result_set4 = $db->getRowWhere("contractors", "id", $idItem);
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
        <?php require_once "../partsOfPages/menuForContractors.php"; ?>

 <div id="work_space">
        <? require_once "../lib/allContractors.php"; ?>
 </div>
    </div>
<?  require_once '../partsOfPages/footer.php';?>

</div>
<?  require_once '../dashbord/dashbord_footer.php';?> 
