<?php

    require_once "../lib/db.php";
    $title = 'Контрагенты';
    require_once '../partsOfPages/head.php';
    require_once "../partsOfPages/menuForUser.php"; ?>
    <? if(isset($_GET["success"])) {?>
        <div class="col-md-4">
            <div class="alert alert-success" role="alert">
                Данные успешно сохранены!
            </div>
        </div>
    <?}?>
    <div class="col-md-12">
        <?php require_once "../partsOfPages/menuForCustomerCarrier.php"; ?>
    </div>
<?  require_once '../partsOfPages/footer.php';?>