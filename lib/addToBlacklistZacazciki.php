<?php require_once '../lib/db.php';?>
<?php require_once '../lib/vars.php';?>

<?php
	$db = DB::getObject();
	$reasonOptions = array(
		"Часто опаздывает на загрузку" => "Часто опаздывает на загрузку",
		"Выходит напрямую на клиентов" => "Выходит напрямую на клиентов",
		"Не платят" => "Не платят",
		"Много судов подозрительные" => "Много судов подозрительные"
	);
	if(isset($_POST["add"])) {
		$name = $_POST["name"];
		$contactName = $_POST["contactName"];
		$status = $_POST["status"];
		
		$error_name = "";
		$error_contactName = "";
		$error_status = "";
		$error = false;
		
		if(strlen($name) == 0) {
			$error_name = "Не заполнено поле";
			$error = true;
		}		
		if(strlen($contactName) == 0) {
			$error_contactName = "Не заполнено поле";
			$error = true;
		}

		if(strlen($status) == 0) {
			$error_status = "Не заполнено поле";
			$error = true;
		}


		if(!$error) {
			$name = htmlspecialchars($name, ENT_QUOTES);
			
			$db->addToBlacklist($name, $contactName, $status);
							
			header("Location: blacklist.php?success");
			exit;
		}
	}
	else if(isset($_POST["cancel"])) {
		header("Location: blacklist.php");
		exit;		
	}
    require_once '../partsOfPages/head.php';
    if($_SESSION["userType"] == 1) 		require_once "../partsOfPages/menuForAdmin.php"; 
    else if($_SESSION["userType"] == 2) require_once "../partsOfPages/menuForUser.php"; ?>
    <div class="col-md-12 pt-3">
        <form name="" action="" method="post">
            <input class="btn btn-danger btn-sm" type="submit" name="cancel" value="Вернуться назад"> <br /> <br />
            <div class="row"> 
                <div class="form-group col-md-4 mr-4">
                    <label for="tag" class="required">Название компании</label>
                    <input class="form-control<?=isset($error_name)&&$error_name!=''?' is-invalid':''?>" type="text" name="name" id="tag" value="<?=$_POST["name"]?>">
                    <div class="invalid-feedback" <?=isset($error_name)&&$error_name!=''?'style="display:block;"':''?>><?=$error_name ?></div>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="contactNameInput" class="required">Контактное лицо</label>
                    <input class="form-control<?=isset($error_contactName)&&$error_contactName!=''?' is-invalid':''?>" type="text" name="contactName" id="contactNameInput" value="<?=$_POST["contactName"]?>">
                    <div class="invalid-feedback" <?=isset($error_contactName)&&$error_contactName!=''?'style="display:block;"':''?>><?=$error_contactName ?></div>
                </div>
				
				<div class="form-group col-md-4 mr-4">
				<label for="reasonSelect" class="required">Статус</label>
				<select class="form-control<?=isset($error_status)&&$error_status!=''?' is-invalid':''?>" name="status" id="reasonSelect">
					<option value="">Выбрать статус</option>
							<?php
							foreach ($reasonOptions as $value => $label) {
								echo '<option value="' . htmlspecialchars($value, ENT_QUOTES) . '"';
								if ($_POST["status"] === $value) {
									echo ' selected';
								}
								echo '>' . htmlspecialchars($label, ENT_QUOTES) . '</option>';
							}
							?>
				</select>
				<div class="invalid-feedback" <?=isset($error_status)&&$error_status!=''?'style="display:block;"':''?>><?=$error_status ?></div>
			</div>
            </div>  
            <input class="btn btn-secondary btn-sm" type="submit" name="add" value="Добавить">
            <input class="btn btn-danger btn-sm" type="submit" name="cancel" value="Отменить">
        </form>
    </div>
	<script>
		 function autocompleteTag(){
            var ajax = new XMLHttpRequest();
            ajax.open("GET", "../lib/autocomplete.php", true);
            ajax.onload = function () {
                var list = JSON.parse(ajax.responseText);
                new Awesomplete(document.querySelector("#tag"), {minChars: 1, list: list});
            };
            ajax.send();
        }
		$(function(){
            autocompleteTag();
            autocompleteTag1();
            autocompletefromInput();
            autocompleteToInput();
            var postDate = $('#dateInput').val();
            var datepicker = $('#dateInput').datepicker().data('datepicker');
            if(postDate!=''){
                postDate = postDate.split(".");
                datepicker.selectDate(new Date(postDate[2],postDate[1]- 1,postDate[0]));
            }
        });

	</script>
<?  require_once '../partsOfPages/footer.php';?>

</div>
<?  require_once '../dashbord/dashbord_footer.php';?> 

