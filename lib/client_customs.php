<?php
  
  //require_once "../lib/checkWasUserLoginedAndIsUser.php";
  require_once "../lib/db.php";
  require_once "../lib/vars.php";
    require_once "../lib/functions.php";
    

    if(isset($_GET["id"])) {
     $id = $_GET["id"];
    }
  
  $db = DB::getObject();
  $showTable = true;

  $result_set10 = $db->getRowWhere("contractors", "id", $id);
        $row10 = $result_set10->fetch_assoc();


    $today = date("Y-m-d");
    $startDayForCurrentMonth = getStartDay($today);
    $todayUnix = strtotime($today);
    $amountOfDaysInMonth = getAmountOfDaysInMonth($todayUnix); 
    if ($row10[contractorsType] == 'Заказчик') {
      //$result_set = $db->getCustomsClearanceByCustoms($startDayForCurrentMonth, $amountOfDaysInMonth, $id);
      $result_set = $db->getCustomsClearanceByCustoms_new($id);

    } elseif ($row10[contractorsType] == 'Перевозчик') {
     // $result_set = $db->getCustomsClearanceByCarriers($startDayForCurrentMonth, $amountOfDaysInMonth, $id);
      $result_set = $db->getCustomsClearanceByCarriers_new($id);
    }

  ?>

   
      <form name="" action="" method="post">
  <? if($_SESSION["role"] == 1) {?>
   <!--  <input class="btn btn-danger btn-sm" type="submit" name="deleteMarkedItems" value="Удалить">  -->
    <?}?>
    <? if(isset($error_delete)&&$error_delete!='') {?>
        <div class="col-md-4 mt-2 mb-2"> 
            <div class="alert alert-danger" role="alert">
            <?=$error_delete ?>
            </div>
        </div>
    <?}?>

    <div class="head col-md-12 mr-12">
                   <?php if ($row10[contractorsType] == 'Заказчик') { ?>
                  <div class="col-md-3">Заказчик</div>
                  <div class="col-md-3">Перевозчик</div>
                <?php } elseif ($row10[contractorsType] == 'Перевозчик') { ?>
                  <div class="col-md-3">Перевозчик</div>
                  <div class="col-md-3">Заказчик</div>
                  <?php } ?>
                <div class="col-md-1">Заявка</div>
                <div class="col-md-2">Дата</div>
                <div class="col-md-2">Стоимость</div>
                <!-- <div class="col-md-1">Файл</div> -->
                <div class="col-md-1">Отписали</div>
            </div>


    <? while (($row = $result_set->fetch_assoc()) != false) { ?>
      <?
      if ($row10[contractorsType] == 'Заказчик') {
        $result_set1 = $db->getRowWhere("contractors", "id", $row[customer]);
        $row1 = $result_set1->fetch_assoc();
        
        $result_set2 = $db->getRowWhere("contractors", "id", $row[carrier]);
        $row2 = $result_set2->fetch_assoc();

      }elseif ($row10[contractorsType] == 'Перевозчик') {
        $result_set1 = $db->getRowWhere("contractors", "id", $row[carrier]);
        $row1 = $result_set1->fetch_assoc();
        
        $result_set2 = $db->getRowWhere("contractors", "id", $row[customer]);
        $row2 = $result_set2->fetch_assoc();
      }
        
        if($row[deliveryNote] == 0) {
          $deliveryNote = "-";
          $rowColor = "";
        }
        else {
          $deliveryNote = "+";
          $rowColor = "rowGreen";
        }
        
        if($row["date"] == 0)   $date = "";
        else          $date = date("d.m.Y", $row["date"]);
      ?>

   <div class="history col-md-12 mr-12">
        <input type="hidden" name="idItem" value="<?=$row[id] ?>">
        <div class="col-md-3"><?=$row1[name].' '.$row1[company_form]?></div>
        <div class="col-md-3"><?=$row2[name].' '.$row2[company_form]?></div>
        <div class="col-md-1"><?=$row[number]?></div>
        <div class="col-md-2"><?=$date?></div>
        <div class="col-md-2"><?=$row[price]?></div>
<!--         <div class="col-md-1">
  <? if(strlen($row[path]) != "") {?><a href="<?=$row[path]?>" target="_blank">Открыть</a><?}?>
</div> -->
        <div class="col-md-1"><?=$deliveryNote?></div>
  </div>
    <?}?>
</form>
   