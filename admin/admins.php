<? session_start();
    require_once "../lib/checkWasUserLoginedAndIsAdmin.php";
 require_once '../dashbord/dashbord.php';?> 
<h1>Список администраторов</h1>
<?php
    $title = 'Список администраторов';

	$db = DB::getObject();
	$result_set = $db->getRowWhere("users", "rights", 1);
	$_SESSION["userType"] = "1";

	if(isset($_POST["editMarkedItem"])) {
		$idItem = $_POST["idItem"];

		if(!isset($idItem))
			$error_delete = "Не был выбран ни один пользователь";
		else {
			header("Location: editAdmin.php?edit=$idItem");
			exit;
		}
	}
	else if(isset($_POST["changePassword"])) {
		$idItem = $_POST["idItem"];

		if(!isset($idItem))
			$error_delete = "Не был выбран ни один пользователь";
		else {
			header("Location: changePasswordAdmin.php?edit=$idItem");
			exit;
		}
	}
	else if(isset($_POST["deleteMarkedItems"])) {
		$idItem = $_POST["idItem"];
		if(!isset($idItem))
			$error_delete = "Не был выбран ни один пользователь";
		else
			$addConfirmButton = true;
	}
	
	if(isset($_POST["comfirmDelete"])) {
		$idItem = $_POST["idItem"];

		$db->delete("users", $idItem);

		header("Location: admins.php?success");
		exit;
	}
    require_once '../partsOfPages/head.php';
    require_once "../partsOfPages/menuForAdmin.php"; ?>
    <? if(isset($_GET["success"])) {?>
        <div class="col-md-4">
            <div class="alert alert-success" role="alert">
                Данные успешно сохранены!
            </div>
        </div>
    <?}?>
    <div class="col-md-12">
        <? if($addConfirmButton) {?>
            <form name="" action="" method="post">
            <div class="card col-md-5">
                <div class="card-body">
                    <h5 class="card-title">Вы действительно хотите удалить администратора?</h5>
                    <h6 class="card-subtitle mb-2 text-muted">
                    <?
                    $result_set4 = $db->getRowWhere("users", "id", $idItem);
                    $row4 = $result_set4->fetch_assoc(); ?>
                    <input type="hidden" name="idItem" value="<?=$idItem?>">
                    <? echo $row4[name];
                ?> </h6>
                    <input class="btn btn-danger btn-sm" type="submit" name="comfirmDelete" value="Удалить">
                    <input class="btn btn-secondary btn-sm" type="submit" name="cancelDelete" value="Отменить">
                </div>
            </div>
            </form>
            <hr>
        <?}?>
        <a class="btn btn-success btn-sm" href="addAdmin.php"><i class="fas fa-plus"></i> Добавить администратора</a> <br /><br />
        <form name="" action="" method="post">
            <input class="btn btn-secondary btn-sm" type="submit" name="editMarkedItem" value="Редактировать">
            <input class="btn btn-secondary btn-sm" type="submit" name="changePassword" value="Поменять пароль">
            <input class="btn btn-danger btn-sm" type="submit" name="deleteMarkedItems" value="Удалить"> <br />
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
                    <th>ФИО</th>
                    <th>Логин</th>
                </tr>
                <? while (($row = $result_set->fetch_assoc()) != false) { ?>
                    <tr>
                        <td><input type="radio" name="idItem" value="<?=$row[id]?>"></td>
                        <td><?=$row[name]?></td>
                        <td><?=$row[login] ?></td>
                    </tr>
                <?}?>
            </table>
        </form>
    </div>
<?  require_once '../partsOfPages/footer.php';?>

</div>
<?  require_once '../dashbord/dashbord_footer.php';?> 

