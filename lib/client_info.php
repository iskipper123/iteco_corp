
<?php	
	if(isset($_GET["id"])) {
		$id = $_GET["id"];

		$result_set = $db->getRowWhereOrder("logs", "idContractor", $id, "date DESC");
		
		$result_set1 = $db->getRowWhere("contractors", "id", $id);
		$row1 = $result_set1->fetch_assoc();
	}
?>

    <div class="col-md-6 mr-6">

        <div class="col-md-12 mr-12 his"> 
            <div class="head col-md-12 mr-12">
                <div class="col-md-2">Дата</div>
                <div class="col-md-5">Операция</div>
                <div class="col-md-3">Пользователь</div>
                <div class="col-md-2">Ссылка</div>
            </div>
            <? while (($row = $result_set->fetch_assoc()) != false) { ?>
                <?
                    $result_set2 = $db->getRowWhere("users", "id", $row[idUser]);
                    $row2 = $result_set2->fetch_assoc();

                ?>
                <div class="history col-md-12 mr-12">
                    <div class="col-md-2"><?=date("d.m.Y H:i", $row["date"])?></div>
                    <div class="col-md-5"><?=$row[description]?></div>
                    <div class="col-md-3"><?=$row2[name]?></div>
                    <div class="col-md-2"> 
                      <?php if ($_SESSION["role"] == 1) { ?>
                    <? if($row[linkType] == 2) { //link type заказчик/перевозчик ?>
                    <?php if ($row1[contractorsType] == 'Заказчик') { ?>
                     <a href="/admin/pdfCustomer.php?edit=<?=$row[idItem]?>" name="pdf1" target='_blank'>Ссылка</a>
                    <?php } elseif ($row1[contractorsType] == 'Перевозчик') {?>
                    <a href="/admin/pdfCarrier.php?edit=<?=$row[idItem]?>" name="pdf2" target='_blank'>Ссылка</a>
                    <?php } ?>
                    <?php } ?>
                      <?php } else { ?>
                           <? if($row[linkType] == 2) { //link type заказчик/перевозчик ?>
                            <?php if ($row1[contractorsType] == 'Заказчик') { ?>
                             <a href="/users/pdfCustomer.php?edit=<?=$row[idItem]?>" name="pdf1" target='_blank'>Ссылка</a>
                            <?php } elseif ($row1[contractorsType] == 'Перевозчик') {?>
                            <a href="/users/pdfCarrier.php?edit=<?=$row[idItem]?>" name="pdf2" target='_blank'>Ссылка</a>
                            <?php } ?>
                            <?php } ?> 
                      <?php } ?>


           <? if($row[linkType] == 1) { //растаможка ?>
          <?php 
          $result_set4 = $db->getRowWhere("customs_clearance", "id", $row[idItem]);
          $row4 = $result_set4->fetch_assoc(); 

          $custom_date = date("d.m.Y", $row4["date"]);

          if ($row4["date"] == '0') {
               
          } else {

            echo $custom_date;

          }

            ?>


                      <?php } ?>

                      <? if($row[linkType] == 4) { //оплата ?>
                          
                      <?php } ?>
                    </div>

                </div>
            <?}?>
        </div>
    </div>