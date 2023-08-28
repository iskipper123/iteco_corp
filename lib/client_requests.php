<?php
	//require_once "../lib/checkWasUserLoginedAndIsUser.php";
    require_once "../lib/db.php";
    require_once "../lib/functions.php";
    require_once "../lib/vars.php";
?>
<form name="" action="" method="post">
    <? if(isset($error_delete)&&$error_delete!='') {?>
        <div class="col-md-4 mt-2 mb-2"> 
            <div class="alert alert-danger" role="alert">
            <?=$error_delete ?>
            </div>
        </div>
    <?}?> 
    <br />
    <div class="tableWrapper">

            <div class="head col-md-12 mr-12">
                 <div class="col-md-1">Дата</div>
                 <div class="col-md-1">Заказчик</div>
                 <div class="col-md-1">Перевозчик</div>
                 <div class="col-md-1">№</div>
                 <div class="col-md-1">Откуда</div>
                 <div class="col-md-1">Куда</div>
                 <!-- <div class="col-md-1">Груз</div> -->
                 <div class="col-md-1">А/М</div>
                 <!-- <div class="col-md-1">Вод. ФИО</div> -->
                 <div class="col-md-1">Заказ $</div>
                 <div class="col-md-1">Перевоз $</div>
                <!-- <th>Валюта</th> -->
                <? if($_SESSION["showZP"] != 1 & $_SESSION["role"] != 1) {?>  <div class="col-md-1">Менеджер</div> <?}?>
                 <div class="col-md-1">Валютный платёж</div>
                <? if($_SESSION["role"] == 1) {?>
                     <div class="col-md-1">Комиссион</div>
                <?}?>
                 <div class="col-md-1">Дубл</div>
        </div>

<?php 

   if(isset($_GET["id"])) {
             $id = $_GET["id"];
            }
                $result_set1 = $db->getRowWhere("contractors", "id", $id);
                $row1 = $result_set1->fetch_assoc();
          

            if ($row1[contractorsType] == "Заказчик") {
                 $result_set = $db->getRowWhere("requests", "customer", $id);
            } elseif ($row1[contractorsType] == "Перевозчик") {
                 $result_set = $db->getRowWhere("requests", "carrier", $id);
            }

            
            ?>
            <? while (($row = $result_set->fetch_assoc()) != false) {  
                        ($row[isCurrencyPayment] == 1) ? $isCurrencyPayment = "+" : $isCurrencyPayment = "-";
                    
                    // if($_SESSION["role"] == 1) {
                        $totalSum = $row[customerPrice] - $row[carrierPrice] - 5;
                        if($isCurrencyPayment == "+") $totalSum = $totalSum - $row[comision_static];
                    // }
                    if ($row1[contractorsType] == "Заказчик") {
                    $result_set2 = $db->getRowWhere("contractors", "id", $row[carrier]);
                    $row2 = $result_set2->fetch_assoc();
                    } elseif ($row1[contractorsType] == "Перевозчик") {
                    $result_set2 = $db->getRowWhere("contractors", "id", $row[customer]);
                    $row2 = $result_set2->fetch_assoc();
                    }
                
                 $result_set3 = $db->getRowWhereOrder("users", "id", $row["idUser"], "name");
                 $row3 = $result_set3->fetch_assoc(); 


                if($row["dateShipping"] != 0) $dateCargoReady = date("d.m.Y", $row["dateShipping"]);
                else $dateCargoReady = "";
                 ?>
            <div class="history col-md-12 mr-12">
              <input type="hidden" name="idItem" value="<?=$row[id] ?>">
              <table>
                    <div class="col-md-1"><?php if ($row[id] != '') { ?><?=date("d.m.Y", $row["date"])?><?php } ?></div>
                    <?php if ($row1[contractorsType] == "Заказчик") { ?>
                    <div class="col-md-1"><?php if ($row[id] != '') { ?><?=$row1[name].' '.$row1[company_form]?><?php } ?></div>
                    <div class="col-md-1"><?php if ($row[id] != '') { ?><?=$row2[name].' '.$row2[company_form]?><?php } ?></div>
                    <?php } elseif ($row1[contractorsType] == "Перевозчик") {?>

                    <div class="col-md-1"><?php if ($row[id] != '') { ?><?=$row2[name].' '.$row2[company_form]?><?php } ?></div>
                    <div class="col-md-1"><?php if ($row[id] != '') { ?><?=$row1[name].' '.$row1[company_form]?><?php } ?></div>
                    <?php } ?>
                    <div class="col-md-1"><?=$row[number]?></div>
                    <div class="col-md-1"><?=$row[from]?></div>
                    <div class="col-md-1"><?=$row[to]?></div>
                    <!-- <div class="col-md-1"><?=$row[cargo]?></div> -->
                    <div class="col-md-1"><?=$row[carNumber]?></div>
                    <!-- <div class="col-md-1"><?=$row[fio]?></div> -->
                    <div class="col-md-1"><?=$row[customerPrice]?></div>
                    <div class="col-md-1"><?=$row[carrierPrice]?></div>
                    <!-- <td><?=$row[currency]?></td> -->
                    <? if($_SESSION["showZP"] != 1 & $_SESSION["role"] != 1) {?> <div class="col-md-1"><?=$row3[name]?></div> <?}?>
                    <div class="col-md-1"><?=$isCurrencyPayment?></div>

                    <? if($_SESSION["role"] == 1) {?> <div class="col-md-1"><?=$totalSum?></div> <?}?>
                   <!--  <? if($_SESSION["role"] == 1) {?> <div class="col-md-1"><?=($totalSum*0.35)?></div> <?}?> -->
                    <? if($_SESSION["role"] == 1) $salary += ($totalSum*0.35); ?>
                    <? if($_SESSION["role"] == 1) $totalSumForAdmin += $totalSum; ?>
                     <div class="col-md-1">
                <a class="addrequest" href="duplicateRequest.php?duplicate=<?=$row[id] ?>" style="border:0;"  onclick="window.open('duplicateRequest.php?duplicate=<?=$row[id] ?>', 
                         'newwindow', 
                         'width=800,height=700'); 
              return false;"><img src="/images/plus.png" alt=""></a>
            </div> 
                </table>
                </div>
                <?}?>
    </div>
</form>