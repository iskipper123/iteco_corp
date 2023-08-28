<?php
	session_start();
	require_once "../lib/checkWasUserLoginedAndIsAdmin.php";
    require_once "../lib/db.php";
    require_once "../lib/vars.php";
    
    $title = 'Редактирование выплаты';

	$db = DB::getObject();
	
	if(isset($_GET["edit"])) {
		$id = $_GET["edit"];
		
		$result_set = $db->getRowWhere("salary", "id", $id);
		$row = $result_set->fetch_assoc();
		
		$category = $row[category];
		
		$date = date("d.m.Y", $row["date"]);
	}
	
	if(isset($_POST["addUser"])) {
		$category = $_POST["category"];
		$sum = $_POST["sum"];
		$date = $_POST["date"];
		$comment = $_POST["comment"];

		$error_category = "";
		$error_sum = ""; 
		$error_date = "";
		$error = false;
		
		// if(strlen($category) == 0) {
			// $error_category = "Не заполнено поле";
			// $error = true;
		// }
		if(strlen($sum) == 0) {
			$error_sum = "Не заполнено поле";
			$error = true;
		}
		if(strlen($date) == 0) {
			$error_date = "Не заполнено поле";
			$error = true;
		}
		
		if(!$error) {
			$db->editSalary($id, $category, $sum, strtotime($date), $comment);
							
			header("Location: salary.php?success");
			exit;
		}
	}
	else if(isset($_POST["cancel"])) {
		header("Location: salaryByManager.php?idManager=$_SESSION[backSalary]");
		exit;		
	}
    require_once '../partsOfPages/head.php';
    require_once "../partsOfPages/menuForAdmin.php"; ?>
    <div class="col-md-12 pt-3">
        <form name="" action="" method="post">
            <input class="btn btn-danger btn-sm" type="submit" name="cancel" value="Вернуться назад"> <br /> <br />
            <div class="form-group col-md-4 mr-4">
                <label for="categoryInput" class="required">Категория</label>
                <select class="form-control<?=isset($error_category)&&$error_category!=''?' is-invalid':''?>" name="category" id="categoryInput">
                    <? for($i = 0; $i < count($arrayOfTypeSalary); $i++) {
                        if($category == $arrayOfTypeSalary[$i]) { ?>
                            <option selected="selected"><?=$arrayOfTypeSalary[$i] ?></option>
                        <?}
                        else {?>
                            <option><?=$arrayOfTypeSalary[$i] ?></option>
                        <?}?>
                    <?}?>
                </select>
                <div class="invalid-feedback" <?=isset($error_category)&&$error_category!=''?'style="display:block;"':''?>><?=$error_category ?></div>
            </div>
            
            <div class="form-group col-md-4 mr-4">
                <label for="routeInnameInputput" class="required">Сумма</label>
                <input class="form-control<?=isset($error_sum)&&$error_sum!=''?' is-invalid':''?>" type="text" name="sum" id="nameInput" autocomplete="off" value="<?=isset($_POST["sum"])? $_POST["sum"]:$row[sum]?>">
                <div class="invalid-feedback" <?=isset($error_sum)&&$error_sum!=''?'style="display:block;"':''?>><?=$error_sum ?></div>
            </div>
            
            <div class="form-group col-md-4 mr-4">
                <label for="dateInput" class="required">Дата</label>
                <input class="form-control<?=isset($error_date)&&$error_date!=''?' is-invalid':''?> datepicker-here" data-auto-close="true" type="text" name="date" id="dateInput" placeholder="дд.мм.гггг" autocomplete="off" value="<?=isset($_POST["date"])? $_POST["date"]:$date?>">
                <div class="invalid-feedback" <?=isset($error_date)&&$error_date!=''?'style="display:block;"':''?>><?=$error_date ?></div>
            </div>
            
            <div class="form-group col-md-4 mr-4">
                <label for="commentInput" class="required">Комментарий</label>
                <textarea class="form-control<?=isset($error_comment)&&$error_comment!=''?' is-invalid':''?>" name="comment" id="commentInput" ><?=isset($_POST["comment"])? $_POST["comment"]:$row[comment]?></textarea>
            </div>
            
            <input class="btn btn-secondary btn-sm" type="submit" name="addUser" value="Сохранить">
            <input class="btn btn-danger btn-sm" type="submit" name="cancel" value="Отменить">
        </form>
    </div>
    <script defer>
		$(function(){
            var postDate = $('#dateInput').val();
            var datepicker = $('#dateInput').datepicker().data('datepicker');
            if(postDate!=''){
                postDate = postDate.split(".");
                datepicker.selectDate(new Date(postDate[2],postDate[1]- 1,postDate[0]));
            }
        });
	</script>
<?  require_once '../partsOfPages/footer.php';?> 