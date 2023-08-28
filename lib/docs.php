<?  require_once '../partsOfPages/head.php';
    if($_SESSION["userType"] == 1) 		require_once "../partsOfPages/menuForAdmin.php"; 
    else if($_SESSION["userType"] == 2) require_once "../partsOfPages/menuForUser.php";  ?>
    <? if(isset($_GET["success"])) {?>
        <div class="col-md-4">
            <div class="alert alert-success" role="alert">
                Данные успешно сохранены!
            </div>
        </div>
    <?}?>
    <div class="col-md-12">
        <ul class="nav nav-pills mb-3">
            <? for($i = 0; $i < count($arrayOfDocsSection); $i++) {?>
                <li class="nav-item">
                    <a class="nav-link btn btn-primary m-1" href="docsByGroup.php?group=<?=$arrayOfDocsSection[$i]?>"><?=$arrayOfDocsSection[$i]?></a>
                </li>
            <?}?>
        </ul>
    </div>
<?  require_once '../partsOfPages/footer.php';?>