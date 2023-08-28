<?php 
if(isset($_POST["deleteMarkedItems"])) {
    $idItem = $_POST["deleteMarkedItems"];
    if(!isset($idItem))
      $error_delete = "Не был выбран ни один заказчик";
    else
      $addConfirmButton = true;
  }
  
  if(isset($_POST["comfirmDelete"])) {
    $idItem = $_POST["idItem"];

    $get_contractor_name = $db->getRowWhere("contractors", "id", $idItem);
     $row_con = $get_contractor_name->fetch_assoc();

    $db->delete("contractors", $idItem);

    exit;
  }
   ?>


<div class="tab-content" id="salary">
  <div id="all" class="tab-pane active">
    <div class="col-md-12" id="tab_one">
    <?php 
    $result_set_users = $db->getAllUsersForAdmin();
    $row_users = $result_set_users->fetch_assoc();
    ?>

<form name="" action="" method="post" id="ajax_form_clients" class="inactive">
         <div class="alert alert-success" role="alert" id="result_form" style="display: none;"></div>
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
      <th>Комментарий</th>
      <th>Телефон</th> 
      <? if($_SESSION["role"] == 1) {?> <th>Менеджер</th> <?}?>
      <th>Направления</th>
      <th></th>
    </tr>
    <? while (($row = $result_set->fetch_assoc()) != false) { 
  
        $result_set1 = $db->getRowWhere("users", "id", $row[idManager]);
        $row1 = $result_set1->fetch_assoc();
        
        $restOfTheDays = restOfTheDays($row["date"]);
        if($restOfTheDays > 0) $rowColor = "rowGreen";
        else if($restOfTheDays == 0) $rowColor = "rowRed";
        else if($restOfTheDays < 0) $rowColor = "rowYellow";
        
        $result_set_tags = $db->getRowWhereOrder("tags_clients", "idClient", $row[id], "tag");
        ?> 
        <tr class="<?=$row[isMarked] == 1?'markedItem':''?> <?=$rowColor?>">
      
        <td><input type="radio" name="idItem" readonly value="<?=$row[id]?>"></td>
        <td><a class="userinfo" data-toggle="modal" data-target="#client_modal" data-client_id="<?= $row[id] ?>"><?=$row[name]?> <?=$row[company_form]?></a><input type="hidden" name="name" readonly value="<?=$row[name]?>"></td> 
        <td><input type="text" name="contactName" readonly value="<?=$row[contactName]?>"></td>
        <td><input readonly class="btn btn-link next-call datepicker-here dp-holder" data-auto-close="true" type="text" name="date" placeholder="дд.мм.гггг" autocomplete="off" value="<?=date("d.m.Y", $row["date"])?>" data-curval="<?=date("d.m.Y", $row["date"])?>" data-curid="<?=$row[id]?>">
                </td>
        <td><textarea type="text" name="comments" readonly ><?=$row[comments]?></textarea></td>
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

        <td>
            <ul class="right_menu">
              <li class="add_menu">
              <img src="/images/items.png" alt="">
                <ul>
                  <li><span class="edit">Редактировать</span><input type="reset" value="Сохранить" id="edit_client" style="display: none;"></li>
                  <?php if($_COOKIE[role] == 1) {?> 
                  <li>  
                    <input class="btn btn-danger btn-sm del_client" type="submit" name="deleteMarkedItems" value="<?=$row[id]?>">
                </li> 
                  <?php } ?>  
                </ul> 
              </li>
            </ul>
        </td>

      </tr>
   <?php }  ?>
  
  </table> 
</form>
  </div> 
  </div>

</div>
  <script>
    $('.interactiveTable tr').click(function() {
      $('.interactiveTable tr').removeClass('active');
      $(this).addClass('active');
      });

       $('.right_menu li.add_menu').click(function () {
            $('.right_menu li.add_menu').not(this).children('ul').removeClass('active');
            $(this).children('ul').toggleClass('active');
        });


  </script>
  <script>
    $(document).ready(function(){
   $('.userinfo').click(function(){
   var client_id = $(this).data('client_id');
   $.ajax({
    type: 'GET',
    url: 'client_info.php?id='+client_id,
    success: function(response){ 
      $('.modal-body').html(response); 
    }
  });
 }); 
});
  </script> 