<?php
	session_start();
	require_once "../lib/checkWasUserLoginedAndIsAdmin.php";
	require_once "../lib/db.php";
    require_once "../lib/vars.php";
    
    $title = 'Редактирование растаможки';
	
	$db = DB::getObject(); 

	if(isset($_GET["edit"])) {
		$id = $_GET["edit"];
				
		$result_set = $db->getRowWhere("customs_clearance", "id", $id);
		$row = $result_set->fetch_assoc();
		
		$result_set11 = $db->getRowWhere("contractors", "id", $row[customer]);
		$row11 = $result_set11->fetch_assoc();
		$customer = $row11[name];
		
		$result_set12 = $db->getRowWhere("contractors", "id", $row[carrier]);
		$row12 = $result_set12->fetch_assoc();
		$carrier = $row12[name];
		
		if($row["date"] == 0) 	$date = "";
		else 					$date = date("d.m.Y", $row["date"]);
	} 
	
	if(isset($_POST["edit"])) {
		$customer = $_POST["customer"];
		// echo "customer: ".$customer."<br />";
		$carrier = $_POST["carrier"];
		// echo "carrier: ".$carrier."<br />";
		$number = $_POST["number"];
		$date = $_POST["date"];
		$price = $_POST["price"];
		$name = $_FILES["file"]["name"];
		$name2 = $_FILES["file2"]["name"];

		$error_number = "";
		$error_date = "";
		$error_price = "";
		$error_file = "";
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
			if(empty($_FILES["file"]["tmp_name"]) && empty($_FILES["file2"]["tmp_name"])) {
				$db->editCustomsClearance($id, $row7[id], $row8[id], $number, strtotime($date), $price);
			}
			else {
				
			
			if(!empty($_FILES["file"]["tmp_name"])) {	

			unlink($row[path]);		
				//взять расширение файла
				$temp = explode(".", $name);
				$lastPartOfName = $temp[count($temp) - 1];
				
				$scan = '../files/customs_clearance/'.$id.".".$lastPartOfName;

				$upload = $_FILES["file"]["tmp_name"];
				move_uploaded_file($upload, "$scan");
			}
			if(!empty($_FILES["file2"]["tmp_name"])) {
			unlink($row[path2]);							
				//взять расширение файла
				$temp2 = explode(".", $name2);
				$lastPartOfName2 = $temp2[count($temp2) - 1];
				
				$scan2 = '../files/customs_clearance/'.$id."-1.".$lastPartOfName2;

				$upload2 = $_FILES["file2"]["tmp_name"];
				move_uploaded_file($upload2, "$scan2");
			}
					$db->editCustomsClearanceWithNewFile($id, $row7[id], $row8[id], $number, strtotime($date), $price, $scan, $scan2);
				
			} 
			
			header("Location: customsClearance.php?success");
			exit;	
		}
	}
	else if(isset($_POST["cancel"])) {
		header("Location: customsClearance.php");
		exit;		
	}
	
	$result_set2 = $db->getRowWhereWhereOrder("contractors", "contractorsType", $arrayOfContractorsTypes[0], "isDeleted", 0, "name");
	$result_set3 = $db->getRowWhereWhereOrder("contractors", "contractorsType", $arrayOfContractorsTypes[1], "isDeleted", 0, "name");
    require_once '../partsOfPages/head.php';
    require_once "../partsOfPages/menuForAdmin.php"; ?>
    <div class="col-md-12 pt-3">
        <form name="" action="" method="post" enctype="multipart/form-data">
            <input class="btn btn-danger btn-sm" type="submit" name="cancel" value="Вернуться назад"> <br /> <br />
            <div class="row">
                <div class="form-group col-md-4 mr-4">
                    <label for="tag" class="required">Выберите заказчика</label>
                    <input class="form-control<?=isset($error_customer)&&$error_customer!=''?' is-invalid':''?>" type="text" name="customer" id="tag" autocomplete="off" value="<?=isset($_POST["customer"])? $_POST["customer"]:$customer?>">
                    <div class="invalid-feedback" <?=isset($error_customer)&&$error_customer!=''?'style="display:block;"':''?>><?=$error_customer ?></div>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="tag1" class="required">Выберите перевозчика</label>
                    <input class="form-control<?=isset($error_carrier)&&$error_carrier!=''?' is-invalid':''?>" type="text" name="carrier" id="tag1" autocomplete="off" value="<?=isset($_POST["carrier"])? $_POST["carrier"]:$carrier?>">
                    <div class="invalid-feedback" <?=isset($error_carrier)&&$error_carrier!=''?'style="display:block;"':''?>><?=$error_carrier ?></div>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="numberInput" class="required">Номер заявки</label>
                    <input class="form-control<?=isset($error_number)&&$error_number!=''?' is-invalid':''?>" type="text" name="number" id="numberInput" autocomplete="off" value="<?=isset($_POST["number"])? $_POST["number"]:$row[number]?>">
                    <div class="invalid-feedback" <?=isset($error_number)&&$error_number!=''?'style="display:block;"':''?>><?=$error_number ?></div>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="dateInput" class="required">Дата растаможки</label>
                    <input class="form-control<?=isset($error_date)&&$error_date!=''?' is-invalid':''?> datepicker-here" data-auto-close="true" type="text" name="date" id="dateInput" placeholder="дд.мм.гггг" autocomplete="off" value="<?=isset($_POST["date"])? $_POST["date"]:$date?>">
                    <div class="invalid-feedback" <?=isset($error_date)&&$error_date!=''?'style="display:block;"':''?>><?=$error_date ?></div>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="priceInput" class="required">Стоимость перевозки</label>
                    <input class="form-control<?=isset($error_price)&&$error_price!=''?' is-invalid':''?>" type="text" name="price" id="priceInput" autocomplete="off" value="<?=isset($_POST["price"])? $_POST["price"]:$row[price]?>">
                    <div class="invalid-feedback" <?=isset($error_price)&&$error_price!=''?'style="display:block;"':''?>><?=$error_price ?></div>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="fileInput">Файл/заявка между нами и заказчиком</label>
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
            <input class="btn btn-secondary btn-sm" type="submit" name="edit" value="Изменить">
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
		function autocompleteTag2(){
			var ajax = new XMLHttpRequest();
			ajax.open("GET", "../lib/autocompleteBlackList.php", true);
			ajax.onload = function () {
				var list = JSON.parse(ajax.responseText);
				var inputElement = document.querySelector("#tag1");
				var awesompleteInstance = new Awesomplete(inputElement, {minChars: 1, list: list});

				// Adaugă clasa CSS specifică pentru stilul cu cifra 2
				awesompleteInstance.container.classList.add("awesomplete2");
			};
			ajax.send();
		}
		$(function(){
            autocompleteTag();
            autocompleteTag1();
            autocompleteTag2();
            var postDate = $('#dateInput').val();
            var datepicker = $('#dateInput').datepicker().data('datepicker');
            if(postDate!=''){
                postDate = postDate.split(".");
                datepicker.selectDate(new Date(postDate[2],postDate[1]- 1,postDate[0]));
            }
        });
	</script>
<?  require_once '../partsOfPages/footer.php';?>	