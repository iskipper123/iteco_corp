<?php require_once '../dashbord/dashbord.php';?> 
<h1 style="    float: left;    margin-bottom: 25px;">Черный список</h1><br><br><br><br>
<button class="btn btn-primary"> Добавить в черный список</button>
<style>
.popup {
    display: none;
    position: fixed;
    width: auto;
    height: auto;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #fff;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    padding: 30px 30px 30px 30px;
    border-radius: 5px;
    z-index: 1000;
}

.popup-buttons {
    display: flex;
    justify-content: space-between;
    margin-top: 10px;
}

.popup button2 {
    flex: 1;
    padding: 10px;
    background-color: #4DA598;
    color: #fff;
    border: none;
    border-radius: 3px;
    cursor: pointer;
    margin-right: 30px;
}

.popup button2.close {
    background-color: black;
    color: #fff;
    border: none;
    border-radius: 3px;
    cursor: pointer;
    padding: 2px 6px;
    position: absolute;
    top: 10px;
    right: 10px;
}

.popup button2:hover {
    background-color: #2A5A54;
}
</style>

<script>
$(document).ready(function() {
    $(".btn-primary").click(function() {
        var popup = $("<div>").addClass("popup");
        var popupButtons = $("<div>").addClass("popup-buttons");
        
        var btnClose = $("<button2>").addClass("close").text("X");
        btnClose.click(function() {
            popup.fadeOut(300, function() {
                $(this).remove();
            });
        });
        
        var btnZakazchiki = $("<button2>").text("Заказчики");
        btnZakazchiki.click(function() {
            window.location.href = "addToBlacklistZacazciki.php";
        });
        
        var btnPerevozchiki = $("<button2>").text("Перевозчики");
        btnPerevozchiki.click(function() {
            window.location.href = "addToBlacklistPerevozciki.php";
        });
        
        popupButtons.append(btnZakazchiki, btnPerevozchiki);
        popup.append(btnClose, popupButtons);
        
        $("body").append(popup);
        popup.fadeIn(300);
    });
});
</script>



<?php
require_once '../dashbord/dashbord.php';

$db = DB::getObject();
$result_set = $db->getAllOrder("blacklist", "date DESC");

$addConfirmButton = false;
$error_delete = '';

if(isset($_POST["editMarkedItem"])) {
    $idItem = $_POST["idItem"];

    if(!isset($idItem))
        $error_delete = "Не была выбрана ни одна заявка";
    else {
        header("Location: editBlacklist.php?edit=$idItem");
        exit;
    }
}

if(isset($_POST["deleteMarkedItems"])) {
    $idItem = $_POST["idItem"];
    if(!isset($idItem))
        $error_delete = "Не была выбрана ни одна заявка";
    else
        $addConfirmButton = true;
}

if(isset($_POST["comfirmDelete"])) {
    $idItem = $_POST["idItem"];

    $db->delete("blacklist", $idItem);


    // Redirecționează către "blacklist.php"
    header("Location: blacklist.php?success");
    exit;
}
?>

<?php if(isset($_GET["success"])) { ?>
    <!-- Afisează mesajul de succes -->
    <div class="col-md-4">
        <div class="alert alert-success" role="alert">
            Данные успешно сохранены!
        </div>
    </div>

    <!-- Adaugă un script pentru redirecționare după 3 secunde -->
    <script>
        setTimeout(function() {
            window.location.href = "blacklist.php";
        }, 1000); // 3000 milisecunde = 3 secunde
    </script>
<?php } ?>
    <div class="col-md-12">
        <? if($addConfirmButton) {?>
            <form name="" action="" method="post">
            <div class="card col-md-5">
                <div class="card-body">
                    <h5 class="card-title">Вы действительно хотите удалить данную компанию из черного списка?</h5>
                    <h6 class="card-subtitle mb-2 text-muted">
                    <?
                    $result_set4 = $db->getRowWhere("blacklist", "id", $idItem);
                    $row4 = $result_set4->fetch_assoc(); ?>
                    <input type="hidden" name="idItem" value="<?=$idItem?>">
                    <? echo $row4[name];
                ?> </h6>

                </div>
            </div>
            </form>
            <hr>
        <?}?>
         

        <form name="" action="" method="post">

            <?if(isset($error_delete)&&$error_delete!=''){?>
                <div class="col-md-4 pt-2">
                    <div class="alert alert-danger" role="alert">
                    <?=$error_delete ?>
                    </div>
                </div>
            <?}?>   
            <table class="table table-striped mt-2 interactiveTable">
                <tr>
                    <th></th>
                    <th>Компания</th>
                    <th>Контактное лицо</th>
                    <th>Статус</th>
                    <th>Дата</th>
                    <th></th>
                </tr>
                <? while (($row = $result_set->fetch_assoc()) != false) { ?>
                    <tr>
                        <td><input type="radio" name="idItem" value="<?=$row[id] ?>"></td>
                        <td><?=$row[name]?></td>
                        <td><?=$row[contactName]?></td>
                        <td><?=$row[status]?></td>
                        <td><?=date("d.m.Y", $row["date"])?></td>
                        <td> <ul class="right_menu">
              <li class="add_menu">
              <img src="/images/items.png" alt="">
                <ul>
                  <li><input class="btn btn-danger btn-sm" type="submit" name="comfirmDelete" value="Удалить"></li>
                   <? if($_SESSION["role"] == 1) {?>
                  <li><input class="btn btn-secondary btn-sm" type="submit" name="cancelDelete" value="Отменить"></li>
              <?php } ?>
                </ul>
              </li>
            </ul></td>

                    

                    </tr>
                <?}?>
            </table>
        </form>
    </div>
    <style>
        ul.right_menu li.add_menu ul{    
            right: 22px;
            top: -20px;}
    </style>
<script>
      $('.right_menu li.add_menu').click(function () {
            $('.right_menu li.add_menu').not(this).children('ul').removeClass('active');
            $(this).children('ul').toggleClass('active');
        });
</script>
<?  require_once '../partsOfPages/footer.php';?>

</div>
<?  require_once '../dashbord/dashbord_footer.php';?> 

