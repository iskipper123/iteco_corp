<?php 
session_start();

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

    header("Location: contractors.php?success");
    exit;
  }
  
  if(isset($_POST["cancel"])) {
    header("Location: contractors.php");
    exit;
  }
  
  if(isset($_POST["add"])) {
    $_SESSION["contractorType"] = $arrayOfContractorsTypes[0];
    header("Location: addCustomer.php");
    exit;   
  }
  
 
?>


<form name="" action="" method="post">

    <?
if ($alpha == '') {
       $alpha = ''; 
    } else {
        $alpha = $_GET["alpha"];
    }
       $_SESSION["contractorType"] = $arrayOfContractorsTypes[0];
       if ($_SESSION["contractorType"] = $arrayOfContractorsTypes[0]) {
     		$arr = $arrayOfContractorsTypes[0];
       } elseif ($_SESSION["contractorType"] = $arrayOfContractorsTypes[1]) {
       		$arr = $arrayOfContractorsTypes[1]; 
       } 
       if(isset($error_delete)&&$error_delete!='') {?>
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
			<th>Менеджер</th>
			<th>Телефон</th>
			<th>E-mail</th>
		<!-- 	<th>Страна</th>
		<th>Город</th> -->
			<? if($_SESSION["contractorType"] == $arrayOfContractorsTypes[1]) {?><th>Кол. Авто</th> <?}?>
			<th>
				<? if($_SESSION["contractorType"] == $arrayOfContractorsTypes[0]) {?> Направления <?}
				else {?> Направления<?}?>
			</th>
      <? //if($_SESSION["role"] == 1) {?> 
      <th>Менеджер</th>
      <?php //} ?>
		</tr>
		<?  $i=1; while (($row = $result_set->fetch_assoc()) != false) { ?>
			<?
				$result_set_tags = $db->getRowWhereOrder("tags", "idContractor", $row[id], "tag");

          if ($row[idManager] != '0') {
            $manager = $db->getUserByID($row[idManager]);
            $name_user_name = $manager->fetch_assoc();
          } else {
            $name_user_name = '';
          }

            if ($row[comments] == 'Дубль, на удаление') { $color = '#ff9191'; $text_color = '#000';} else {$color = 'transparent';$text_color = '#4da598';}

			?>

			<tr class="<?=$row[isMarked] == 1?'markedItem':''?>" style="background-color:<?= $color ?>; ">

				<td><input type="radio" name="idItem" value="<?=$row[id] ?>"></td>
				<td>
					<a class="userinfo" style="color:<?= $text_color ?>;" data-toggle="modal" data-target="#client_modal" data-client_id="<?= $row[id] ?>"><?=$row[name]?> <?=$row[company_form]?></a>
					<? if($row[isMarked] == 1) {?> <i class="fas fa-check"></i><?}?>
				</td>
				<td><?=$row[contactName]?></td>
				<td><?=$row[phone]?></td>
				<td><?=$row[email]?></td>

				<? if($_SESSION["contractorType"] == $arrayOfContractorsTypes[1]) {?> <td><?=$row[carsAmount]?></td> <?}?>
				<td>
					<? if($_SESSION["contractorType"] == $arrayOfContractorsTypes[1]) echo $row[directions]; ?>
					<? 
						if($_SESSION["contractorType"] == $arrayOfContractorsTypes[0]) {
							while(($row_tag = $result_set_tags->fetch_assoc()) != false) { ?>
								<a href="contractorsByTag.php?type=<?=$row[contractorsType]?>&tag=<?=$row_tag[tag]?>"><?=$row_tag[tag]?></a>
						<?}
						} 
					?>
				</td>
                 <? //if($_SESSION["role"] == 1) {?>  

                  <td>
                    <?php echo $name_user_name[name]; ?>
                  </td>
<!--           <td>
            <ul class="right_menu"> 
              <li class="add_menu">
              <img src="/images/items.png" alt="">
                <ul>
                  <li><input class="btn btn-danger btn-sm del_client" type="submit" name="deleteMarkedItems" value="<?=$row[id] ?>"></li>
                </ul>
              </li>
            </ul>
        </td> -->
        <td><input type="checkbox" name="checkboxstatus[<?php echo $i; ?>]" value="<?php echo $row['id']; ?>"  /></td>
        <?php $i++; ?>
        <?php //} ?>

			</tr>
		<?}?>
	</table>

 <? //if($_SESSION["role"] == 1) {?> 
  <input type="submit" value="Удалить" name="Delete" style="float: right;position: fixed;top: 163px;right: 0;"/>
 <?php //} ?>
  
</form>
  <?php 
if(isset($_POST["Delete"])) {

    if(!empty($_REQUEST['checkboxstatus'])) {
        $checked_values = $_REQUEST['checkboxstatus'];
        foreach($checked_values as $val) {
          $db->delete("contractors", $val);
        }
        header("Location: customers.php?success");
    }
} ?>
<style>
  ul.right_menu li.add_menu ul{    top: -1px;}
  ul.right_menu li.add_menu ul:after{top:40%;}
</style>
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

  $('.close, #mask').on('click touchend', function(){
   $('.modal-header ul li a').removeClass('active');
   $('.modal-header ul li:first-child a').addClass('active'); 
  });
  
        $('.right_menu li.add_menu').click(function () {
            $('.right_menu li.add_menu').not(this).children('ul').removeClass('active');
            $(this).children('ul').toggleClass('active');
        });

  </script> 
