<?php
	require_once "../lib/db.php";
    require_once "../lib/vars.php";
    
    $title = 'Добавить растаможку'; 

	$db = DB::getObject(); 
	
	if(isset($_POST["add"])) { 
		$customer = $_POST["customer"];
		$carrier = $_POST["carrier"];
		$number = $_POST["number"];
		$date = $_POST["date"];
		$price = $_POST["price"];
		$name = $_FILES["file"]["name"];
		$name2 = $_FILES["file2"]["name"];
		
		$error_customer = "";
		$error_carrier = "";
		$error_number = "";
		$error_date = "";
		$error_price = "";
		$error_file = "";
		$error_file2 = "";
		$error = false;
		
		if(strlen($customer) == 0) {
			$error_customer = "Не заполнено поле";
			$error = true;
		}		
		if(strlen($carrier) == 0) {
			$error_carrier = "Не заполнено поле";
			$error = true;
		}
		if(strlen($number) == 0) {
			$error_number = "Не заполнено поле";
			$error = true;
		}
		if(strlen($date) == 0) {
			$error_date = "Не заполнено поле";
			$error = true;
		}
		if(!is_numeric($price)) {
			$error_price = "Стоимость перевозки должна быть числом";
			$error = true;
		}
		if(strlen($price) == 0) {
			$error_price = "Не заполнено поле";
			$error = true;
		}
		if(strlen($name) == 0) {
			$error_file = "Не загружен файл";
			$error = true;
		}
				if(strlen($name2) == 0) {
			$error_file2 = "Не загружен файл";
			$error = true;
		}
		
		if($_FILES["file"]["size"] > 16777214) {
			$error_file = "Превышен максимальный размер файла (16МБ)";
			$error = true;
		}		
		if($_FILES["file2"]["size"] > 16777214) {
			$error_file2 = "Превышен максимальный размер файла (16МБ)";
			$error = true;
		}
		if(!empty($_FILES["file"]["tmp_name"])) {
			$arrayTypeOfFile = explode("/", $_FILES["file"]["type"]);
			$typeOfFile = $arrayTypeOfFile[1];
			$typeOfFile1 = $arrayTypeOfFile[0];

			if($typeOfFile1 != "image" & $typeOfFile != "vnd.openxmlformats-officedocument.wordprocessingml.document" & $typeOfFile != "msword" &
					$typeOfFile != "vnd.openxmlformats-officedocument.spreadsheetml.sheet" & $typeOfFile != "vnd.ms-excel" & $typeOfFile != "pdf") {
				$error_file = "Загружаемый файл должен быть в формате doc, docx, xlsx, xls, PDF либо быть изображением";
				$error = true;
			}
		}
		if(!empty($_FILES["file2"]["tmp_name"])) {
			$arrayTypeOfFile2 = explode("/", $_FILES["file2"]["type"]);
			$typeOfFile2 = $arrayTypeOfFile2[1];
			$typeOfFile12 = $arrayTypeOfFile2[0];

			if($typeOfFile12 != "image" & $typeOfFile2 != "vnd.openxmlformats-officedocument.wordprocessingml.document" & $typeOfFile2 != "msword" &
					$typeOfFile2 != "vnd.openxmlformats-officedocument.spreadsheetml.sheet" & $typeOfFile2 != "vnd.ms-excel" & $typeOfFile2 != "pdf") {
				$error_file2 = "Загружаемый файл должен быть в формате doc, docx, xlsx, xls, PDF либо быть изображением";
				$error = true;
			}
		}

		//найти id заказчика
		$result_set7 = $db->getRowWhereWhereOrder("contractors", "name", $customer, "isDeleted", 0, "name");
		if($result_set7->num_rows == 0) {
			$error_customer = "Заказчик не найден";
			$error = true;
		}
		else $row7 = $result_set7->fetch_assoc();
		
		//найти id перевозчика
		$result_set8 = $db->getRowWhereWhereOrder("contractors", "name", $carrier, "isDeleted", 0, "name");
		if($result_set8->num_rows == 0) {
			$error_carrier = "Перевозчик не найден";
			$error = true;
		}
		else $row8 = $result_set8->fetch_assoc();
			
		if(!$error) {
			$idNewFile = $db->getLastID("customs_clearance");
			$idNewFile++;
				
			if(!empty($_FILES["file"]["tmp_name"])) {				
				//взять расширение файла
				$temp = explode(".", $name);
				$lastPartOfName = $temp[count($temp) - 1];
				
				$scan = '../files/customs_clearance/'.$idNewFile.".".$lastPartOfName;

				$upload = $_FILES["file"]["tmp_name"];
				move_uploaded_file($upload, "$scan");
			}
			if(!empty($_FILES["file2"]["tmp_name"])) {				
				//взять расширение файла
				$temp2 = explode(".", $name2);
				$lastPartOfName2 = $temp2[count($temp2) - 1];
				
				$scan2 = '../files/customs_clearance/'.$idNewFile."-1.".$lastPartOfName2;

				$upload2 = $_FILES["file2"]["tmp_name"];
				move_uploaded_file($upload2, "$scan2");
			}
			$db->addCustomsClearance($idNewFile, $row7[id], $row8[id], $number, strtotime($date), $price, $scan, $scan2);
			
			$db->addLog($row7[id], time(), "Растаможка. Контрагент добавлен в качестве заказчика. Заявка №$number", 1, $idNewFile, $_SESSION["id"]);
			$db->addLog($row8[id], time(), "Растаможка. Контрагент добавлен в качестве перевозчика. Заявка №$number", 1, $idNewFile, $_SESSION["id"]);
							
			header("Location: customsClearance.php?success");
			exit;
		}
	}
	else if(isset($_POST["cancel"])) {
		header("Location: customsClearance.php");
		exit;		
	}
    require_once '../partsOfPages/head.php';
    require_once "../partsOfPages/menuForAdmin.php"; ?>
    <div class="col-md-12 pt-3">
        <form name="" action="" method="post" enctype="multipart/form-data">
            <input class="btn btn-danger btn-sm" type="submit" name="cancel" value="Вернуться назад"> <br /> <br />
            <div class="row">
                <div class="form-group col-md-4 mr-4">
                    <label for="tag" class="required">Выберите заказчика</label>
                    <input class="form-control<?=isset($error_customer)&&$error_customer!=''?' is-invalid':''?>" type="text" name="customer" id="tag" autocomplete="off" value="<?=$_POST["customer"]?>">
                    <div class="invalid-feedback" <?=isset($error_customer)&&$error_customer!=''?'style="display:block;"':''?>><?=$error_customer ?></div>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="tag1" class="required">Выберите перевозчика</label>
                    <input class="form-control<?=isset($error_carrier)&&$error_carrier!=''?' is-invalid':''?>" type="text" name="carrier" id="tag1" autocomplete="off" value="<?=$_POST["carrier"]?>">
                    <div class="invalid-feedback" <?=isset($error_carrier)&&$error_carrier!=''?'style="display:block;"':''?>><?=$error_carrier ?></div>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="numberInput" class="required">Номер заявки</label>
                    <input class="form-control<?=isset($error_number)&&$error_number!=''?' is-invalid':''?>" type="text" name="number" id="numberInput" autocomplete="off" value="<?=$_POST["number"]?>">
                    <div class="invalid-feedback" <?=isset($error_number)&&$error_number!=''?'style="display:block;"':''?>><?=$error_number ?></div>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="dateInput" class="required">Дата растаможки</label>
                    <input class="form-control<?=isset($error_date)&&$error_date!=''?' is-invalid':''?> datepicker-here" data-auto-close="true" type="text" name="date" id="dateInput" placeholder="дд.мм.гггг" autocomplete="off" value="<?=$date?>">
                    <div class="invalid-feedback" <?=isset($error_date)&&$error_date!=''?'style="display:block;"':''?>><?=$error_date ?></div>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="priceInput" class="required">Стоимость перевозки</label>
                    <input class="form-control<?=isset($error_price)&&$error_price!=''?' is-invalid':''?>" type="text" name="price" id="priceInput" autocomplete="off" value="<?=$_POST["price"]?>">
                    <div class="invalid-feedback" <?=isset($error_price)&&$error_price!=''?'style="display:block;"':''?>><?=$error_number ?></div>
                </div>
                      <div class="form-group col-md-4 mr-4">
                 </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="fileInput" class="required">Файл/заявка между нами и заказчиком</label>
                    <input class="form-control-file<?=isset($error_file)&&$error_file!=''?' is-invalid':''?>" type="file" name="file" id="fileInput">
                    <small class="form-text text-muted">(допустимые форматы - doc, docx, xlsx, xls, PDF, изображения; размер до 16 МБ)</small>
                    <div class="invalid-feedback" <?=isset($error_file)&&$error_file!=''?'style="display:block;"':''?>><?=$error_file ?></div>
                </div>

                <div class="form-group col-md-4 mr-4">
                    <label for="fileInput" class="required">Файл/заявка между нами и заказчиком</label>
                    <input class="form-control-file<?=isset($error_file2)&&$error_file2!=''?' is-invalid':''?>" type="file" name="file2" id="fileInput">
                    <small class="form-text text-muted">(допустимые форматы - doc, docx, xlsx, xls, PDF, изображения; размер до 16 МБ)</small>
                    <div class="invalid-feedback" <?=isset($error_file2)&&$error_file2!=''?'style="display:block;"':''?>><?=$error_file2 ?></div>
                </div>
            </div>
            <input class="btn btn-secondary btn-sm" type="submit" name="add" value="Добавить">
            <input class="btn btn-danger btn-sm" type="submit" name="cancel" value="Отменить">
        </form> 
    </div>
    <script defer>
        function autocompleteTag(){
            var ajax = new XMLHttpRequest(); 
            ajax.open("GET", "../lib/autocomplete.php", true);
            ajax.onload = function () {
                var list = JSON.parse(ajax.responseText);
                new Awesomplete(document.querySelector("#tag"), {minChars: 1, list: list});
            };
            ajax.send();
        }
        function autocompleteTag1(){
            var ajax = new XMLHttpRequest();
            ajax.open("GET", "../lib/autocomplete1.php", true);
            ajax.onload = function () {
                var list = JSON.parse(ajax.responseText);
                new Awesomplete(document.querySelector("#tag1"), {minChars: 1, list: list});
            };
            ajax.send();
        }
		$(function(){
            autocompleteTag();
            autocompleteTag1();
            var postDate = $('#dateInput').val();
            var datepicker = $('#dateInput').datepicker().data('datepicker');
            if(postDate!=''){
                postDate = postDate.split(".");
                datepicker.selectDate(new Date(postDate[2],postDate[1]- 1,postDate[0]));
            }
        });
	</script>
<?  require_once '../partsOfPages/footer.php';?>