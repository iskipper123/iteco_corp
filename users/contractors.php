<?php

    $title = 'Контрагенты';
 ?>
    
<?  require_once '../dashbord/dashbord.php';?> 
<h1>Дашборд</h1>
<div id="work">
    <? if(isset($_GET["success"])) {?>
        <div class="col-md-4">
            <div class="alert alert-success" role="alert">
                Данные успешно сохранены!
            </div>
        </div>
    <?}?>
    <div class="col-md-12">
        <?php require_once "../partsOfPages/menuForContractors.php"; ?>
    </div>
<?  require_once '../partsOfPages/footer.php';?>

</div>
<?  require_once '../dashbord/dashbord_footer.php';?> 
