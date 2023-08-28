  <?php 
    $result_set_users = $db->getAllUsersForAdmin();
    $row_users = $result_set_users->fetch_assoc();
    // set the pointer back to the beginning
$result_set->data_seek(0); 
$result_set_tags->data_seek(0); 

    ?>

<form name="" action="" method="post" id="ajax_form_clients" class="inactive">
         <div class="alert alert-success" role="alert" id="result_form" style="display: none;"></div>

           <? if($_SESSION["role"] == 1) {?> 
  <input class="btn btn-secondary btn-sm" type="submit" name="editMarkedItem" value="Редактировать">

    <input class="btn btn-danger btn-sm" type="submit" name="deleteMarkedItems" value="Удалить"> 
    <?}?> 
    <? if(isset($error_delete)&&$error_delete!='') {?>
        <div class="col-md-4">
            <div class="alert alert-danger" role="alert">
            <?=$error_delete ?>
            </div>
        </div>
    <?}?>
  <br />
  <table class="table table-striped mt-2 interactiveTable">
    <tr>
    <th></th>
      <th>Название</th>
      <th>Контактное лицо</th>
      <th>Дата</th>
      <!-- <th>Дней</th> -->
      <th>Комментарий</th>
      <!-- <th>Страна</th> -->
      <th>Телефон</th> 
      <? if($_SESSION["role"] == 1) {?> <th>Менеджер</th> <?}?>
      <th>Направления</th>
      <th>Ред.</th>
    </tr>
    <? 

    while (($row = $result_set->fetch_assoc()) != false) {  ?>

      <?

        $result_set1 = $db->getRowWhere("users", "id", $row[idManager]);
        $row1 = $result_set1->fetch_assoc();
        
        $restOfTheDays = restOfTheDays($row["date"]);

        if($restOfTheDays > 0) $rowColor = "rowGreen";
        else if($restOfTheDays == 0) $rowColor = "rowRed";
        else if($restOfTheDays < 0) $rowColor = "rowYellow";
        
        $result_set_tags = $db->getRowWhereOrder("tags_clients", "idClient", $row[id], "tag");

         $current_date = date("d.m.Y");
         $client_date =(new DateTime())->setTimestamp($row["date"]);

        if ($current_date == $client_date->format('d.m.Y')) {
            if ($_SESSION[role] == 2 && $_SESSION[id] == $row[idManager]){ 
      ?>  

      <tr class="<?=$row[isMarked] == 1?'markedItem':''?> <?=$rowColor?>">
        <td><input type="radio" name="idItem" readonly value="<?=$row[id]?>"></td>
        <td><input type="text" name="name" readonly value="<?=$row[name]?>"></td> 
        <td><input type="text" name="contactName" readonly value="<?=$row[contactName]?>"></td>
        <td>
                    <input readonly class="btn btn-link next-call datepicker-here dp-holder" data-auto-close="true" type="text" name="date" placeholder="дд.мм.гггг" autocomplete="off" value="<?=date("d.m.Y", $row["date"])?>" data-curval="<?=date("d.m.Y", $row["date"])?>" data-curid="<?=$row[id]?>">
                </td>
        <!--  <td class="restOfTheDays"><?=$restOfTheDays?></td>   -->
        <td><textarea type="text" name="comments" readonly ><?=$row[comments]?></textarea></td>
      <!--     <td><?=$row[country]?></td> -->
        <td><input type="text" name="phone" readonly value="<?=$row[phone]?>"></td>
               <? if($_SESSION["role"] == 1) {?> <td>
                      <select class="form-control" name="idUser" id="idUserInput">
                            <?  if($row1[id] == $row[idManager]) {?>
                                    <option selected="selected" value="<?=$row1[id]?>"><?=$row1[name]?></option>
                            <? } ?>
                    <? foreach($result_set_users as $row_users1) {?>
                        <?php if ($row_users1[id] != $row[idManager]) { ?>
                                    <option value="<?=$row_users1[id]?>"><?=$row_users1[name]?></option>
                                 <?}?>
                            <?}?> 
                  
                    </select>
                   </td> <?}?>
         <td>
            <?= $row[isMarked] == 1?' <div class="markTrigger1" style="display:inline-block;"><i class="far fa-check-square" style="color:#66676b"></i></div> ':'<div class="markTrigger2" style="display:inline-block;"><i class="far fa-square" style="color:#66676b"></i></div> '?>

     <? $datas = array() ;
                while(($row_tag = $result_set_tags->fetch_assoc()) != false) { ?>
                <? $datas[] = $row_tag[tag]?>
                 <a class="dirs" href="clientsByTag.php?tag=<?=$row_tag[tag]?>"><?=$row_tag[tag]?></a>
                <?  } $tags = implode(";", $datas) ;?>

                <input type="text" name="directions" id="directionsInput" readonly style="display: none;" value="<?=$tags?>">


</td> 
                <td><span class="edit"></span><input type="reset" value="" id="edit_client" style="display: none;"></td>
      </tr>
<?php  } else { ?>
      <tr class="<?=$row[isMarked] == 1?'markedItem':''?> <?=$rowColor?>">
        <td><input type="radio" name="idItem" readonly value="<?=$row[id]?>"></td>
        <td><input type="text" name="name" readonly value="<?=$row[name]?>"></td> 
        <td><input type="text" name="contactName" readonly value="<?=$row[contactName]?>"></td>
        <td>
                    <input readonly class="btn btn-link next-call datepicker-here dp-holder" data-auto-close="true" type="text" name="date" placeholder="дд.мм.гггг" autocomplete="off" value="<?=date("d.m.Y", $row["date"])?>" data-curval="<?=date("d.m.Y", $row["date"])?>" data-curid="<?=$row[id]?>">
                </td>
        <!--  <td class="restOfTheDays"><?=$restOfTheDays?></td>   -->
        <td><textarea type="text" name="comments" readonly ><?=$row[comments]?></textarea></td>
      <!--     <td><?=$row[country]?></td> -->
        <td><input type="text" name="phone" readonly value="<?=$row[phone]?>"></td>
               <? if($_SESSION["role"] == 1) {?> <td>
                      <select class="form-control" name="idUser" id="idUserInput">
                            <?  if($row1[id] == $row[idManager]) {?>
                                    <option selected="selected" value="<?=$row1[id]?>"><?=$row1[name]?></option>
                            <? } ?>
                    <? foreach($result_set_users as $row_users1) {?>
                        <?php if ($row_users1[id] != $row[idManager]) { ?>
                                    <option value="<?=$row_users1[id]?>"><?=$row_users1[name]?></option>
                                 <?}?>
                            <?}?> 
                  
                    </select>
                   </td> <?}?>
         <td>
            <?= $row[isMarked] == 1?' <div class="markTrigger1" style="display:inline-block;"><i class="far fa-check-square" style="color:#66676b"></i></div> ':'<div class="markTrigger2" style="display:inline-block;"><i class="far fa-square" style="color:#66676b"></i></div> '?>

     <? $datas = array() ;
                while(($row_tag = $result_set_tags->fetch_assoc()) != false) { ?>
                <? $datas[] = $row_tag[tag]?>
                 <a class="dirs" href="clientsByTag.php?tag=<?=$row_tag[tag]?>"><?=$row_tag[tag]?></a>
                <?  } $tags = implode(";", $datas) ;?>

                <input type="text" name="directions" id="directionsInput" readonly style="display: none;" value="<?=$tags?>">


</td> 
                <td><span class="edit"></span><input type="reset" value="" id="edit_client" style="display: none;"></td>
      </tr>
<?php } }?>
    <?}?>
  </table> 
</form>