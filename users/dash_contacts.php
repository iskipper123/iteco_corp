<?php

require_once '../dashbord/dashbord.php';
    $title = 'Список клиентов'; 
	
    $db = DB::getObject(); 
    if ($alpha == '') {
       $alpha = '';
    } else {
        $alpha = $_GET["alpha"];
    }
  
        $idManager = $_SESSION["id"];
  
    date_default_timezone_set('Etc/GMT+0');
    $date = new DateTime();
    $date1 = $date->setTime(00, 00, 00);
 $current_date = $date1->getTimestamp();

 $current_date_up = date("d.m.Y");


    if(isset($_POST['curid'])){ 
        $rowColor = ''; 
        $curid = $_POST['curid'];
        $curval = strtotime($_POST['val']);
        $updated_date = date("d.m.Y");
        $db->editNextCall($curid,$curval,$updated_date);
        $restOfTheDays = restOfTheDays($curval);
        if($restOfTheDays > 0) $rowColor = "rowGreen";
        else if($restOfTheDays == 0) $rowColor = "rowRed";
        else if($restOfTheDays < 0) $rowColor = "rowYellow";
        echo json_encode(['color'=>$rowColor,'days'=>$restOfTheDays]);
        die();
    }
    if(isset($_POST['curid2'])){ 
        $rowColor = ''; 
        $curid = $_POST['curid2'];
        $curval = $_POST['curval2'];
        $db->editMark($curid,$curval);
        die();
    }

	if(isset($_POST["editMarkedItem"])) {
		$idItem = $_POST["idItem"];

		if(!isset($idItem))
			$error_delete = "Не был выбран ни один клиент";
		else {
			header("Location: editClient.php?edit=$idItem");
			exit;
		}
	}
	if(isset($_POST["deleteMarkedItems"])) {
		$idItem = $_POST["idItem"];
		if(!isset($idItem))
			$error_delete = "Не был выбран ни один клиент";
		else
			$addConfirmButton = true;
	}
	
	if(isset($_POST["comfirmDelete"])) {
		$idItem = $_POST["idItem"];

		$db->softDelete("clients", $idItem);

		header("Location: clients.php?success");
		exit;
	}
	
	if(isset($_POST["search"])) {
		$name = $_POST["name"];
		
		if(!isset($name))
			$error_name = "Не заполнено поле";
		else {
			$result_set = $db->searchClients($idManager, $name);
		}
	}

	else $result_set = $db->get_contractors_rus("contractors", "idManager", $idManager, "isDeleted", 0, "date DESC");
?>
<h1>Контакты</h1>
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

</ul>
 <a class="addrequest" id="add_client" data-toggle="modal" data-target="#client_modal" data-client_id="addClient.php"></a>

<script>
    $('.get_alpha li a').click(function() {
        $(".get_alpha li a").removeClass('active');
        $(this).addClass('active');

        if ($(".topbar ul.menu_tab li:nth-child(1)").hasClass("active")){
            filter = 'all';
        
        }
        if ($(".topbar ul.menu_tab li:nth-child(2)").hasClass("active")){
            filter = 'injob';
           
        }
        if ($(".topbar ul.menu_tab li:nth-child(3)").hasClass("active")){
            filter = 'profit';
          
        }
        if ($(".topbar ul.menu_tab li:nth-child(4)").hasClass("active")){
            filter = 'arhive';
          
        }

   $("#work_space").load('./ajax_all_clients.php?idManager='+'<?php echo $_SESSION["id"]; ?>',  {
           alpha: $(this).attr('type'),
           filter: filter
       });
});


    $('.bottom_bar ul.sezon li a').click(function() {
        if ($(".bottom_bar ul.sezon li:nth-child(1)").hasClass("active")){
            filter = 'Зима';
        
        }
        if ($(".bottom_bar ul.sezon li:nth-child(2)").hasClass("active")){
            filter = 'Весна';
           
        }
        if ($(".bottom_bar ul.sezon li:nth-child(3)").hasClass("active")){
            filter = 'Лето';
          
        }
        if ($(".bottom_bar ul.sezon li:nth-child(4)").hasClass("active")){
            filter = 'Осень';
          
        }
        if ($(".bottom_bar ul.sezon li:nth-child(5)").hasClass("active")){
            filter = 'Все';
          
        }

   $("#work_space").load('./ajax_all_clients.php?idManager='+'<?php echo $_SESSION["id"]; ?>',  {
           filter: filter
       });
});


 </script> 
<div class="col-md-12 topbar"> 
    <div class="col-md-4">
       
      </div>
<div class="col-md-8">
<ul class="nav menu_tab">
  <li class="active"><a class="active">Весь список</a></li>
  <li><a>В работе</a></li> 
  <li><a>Профит</a></li>
  <li><a>Архив</a></li>
</ul> 

</div>
    </div>

    <div class="col-md-12 bottom_bar">
    <div class="col-md-4">
       
      </div>
<div class="col-md-8" style="display: block; margin: 0px auto; padding: 0;">

<ul class="nav sezon menu_tab">
  <li><a>Зима</a></li>
  <li><a>Весна</a></li> 
  <li><a>Лето</a></li>  
  <li><a>Осень</a></li>
</ul> 

</div>

    </div>

 <div id="work_space">
<div id="work">
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
                    <h5 class="card-title">Вы действительно хотите удалить данного клиента?</h5>
                    <h6 class="card-subtitle mb-2 text-muted">
                    <?
                    $result_set4 = $db->getRowWhere("clients", "id", $idItem);
                    $row4 = $result_set4->fetch_assoc(); ?>
                    <input type="hidden" name="idItem" value="<?=$idItem?>">
                    <? echo $row4[name];
                ?> </h6>
                    <input class="btn btn-danger btn-sm" type="submit" name="comfirmDelete" value="Удалить">
                    <input class="btn btn-secondary btn-sm" type="submit" name="cancelDelete" value="Отменить">
                </div>
            </div>
            </form>
         
        <?}?>	

		<hr>
        <div id="clients">
        <? require_once "../lib/allClients.php"; ?>
        </div>
         </div>
<?  require_once '../partsOfPages/footer.php';?>

</div>
</div>
<?  require_once '../dashbord/dashbord_footer.php';?> 

