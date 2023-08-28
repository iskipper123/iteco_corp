<?php
	$db = DB::getObject();

	if(isset($_GET["edit"])) {
		$id = $_GET["edit"];
				
		$result_set = $db->getRowWhere("docs", "id", $id);
		$row = $result_set->fetch_assoc();
		
		$category = $row[category];
	}
	
	if(isset($_POST["edit"])) {
		$name = $_POST["name"];
		$category = $_POST["category"];
		$nameFile = $_FILES["file"]["name"];
		
		$error_name = "";
		$error_category = "";
		$error_file = "";
		$error = false;
		
		if(strlen($name) == 0) {
			$error_name = "Не заполнено поле";
			$error = true;
		}		
		if(strlen($category) == 0) {
			$error_category = "Не заполнено поле";
			$error = true;
		}
		if($_FILES["file"]["size"] > 16777214) {
			$error_file = "Превышен максимальный размер файла (16МБ)";
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
		
		if(!$error) {
			$name = htmlspecialchars($name, ENT_QUOTES);
			
			if(empty($_FILES["file"]["tmp_name"])) {
				$db->editDoc($id, $name, $category, $row[path]);
			}
			else {
				//удалить предыдущий файл
				unlink($row[path]);
				
				//взять расширение файла
				$temp = explode(".", $nameFile);
				$lastPartOfName = $temp[count($temp) - 1];
				
				$scan = '../files/docs/'.$id.".".$lastPartOfName;

				$upload = $_FILES["file"]["tmp_name"];
				move_uploaded_file($upload, "$scan");
				
				$db->editDoc($id, $name, $category, $scan);
			}
			
			header("Location: docs.php?success");
			exit;	
		}
	}
	else if(isset($_POST["cancel"])) {
		header("Location: docsByGroup.php?group=$_SESSION[backDocs]");
		exit;		
	}
    require_once '../partsOfPages/head.php';
    if($_SESSION["userType"] == 1) 		require_once "../partsOfPages/menuForAdmin.php"; 
    else if($_SESSION["userType"] == 2) require_once "../partsOfPages/menuForUser.php"; ?>
    <div class="col-md-12 pt-3">
        <form name="" action="" method="post" enctype="multipart/form-data">
            <input class="btn btn-danger btn-sm" type="submit" name="cancel" value="Вернуться назад"> <br /> <br />
            <div class="row">
                <div class="form-group col-md-4 mr-4">
                    <label for="routeInnameInputput" class="required">Название документа</label>
                    <input class="form-control<?=isset($error_name)&&$error_name!=''?' is-invalid':''?>" type="text" name="name" id="nameInput" autocomplete="off" value="<?=isset($_POST["name"])? $_POST["name"]:$row[name]?>">
                    <div class="invalid-feedback" <?=isset($error_name)&&$error_name!=''?'style="display:block;"':''?>><?=$error_name ?></div>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="categoryInput" class="required">Категория</label>
                    <select class="form-control<?=isset($error_category)&&$error_category!=''?' is-invalid':''?>" name="category" id="categoryInput">
                        <? for($i = 0; $i < count($arrayOfDocsSection); $i++) {
                            if($category == $arrayOfDocsSection[$i]) { ?>
                                <option selected="selected"><?=$arrayOfDocsSection[$i] ?></option>
                            <?}
                            else {?>
                                <option><?=$arrayOfDocsSection[$i] ?></option>
                            <?}?>
                        <?}?>
                    </select>
                    <div class="invalid-feedback" <?=isset($error_category)&&$error_category!=''?'style="display:block;"':''?>><?=$error_category ?></div>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="fileInput">Файл</label>
                    <input class="form-control-file<?=isset($error_file)&&$error_file!=''?' is-invalid':''?>" type="file" name="file" id="fileInput">
                    <small class="form-text text-muted">(допустимые форматы - doc, docx, xlsx, xls, PDF, изображения; размер до 16 МБ)</small>
                    <div class="invalid-feedback" <?=isset($error_file)&&$error_file!=''?'style="display:block;"':''?>><?=$error_file ?></div>
                </div>
                
            </div>
            <input class="btn btn-secondary btn-sm" type="submit" name="edit" value="Изменить">
            <input class="btn btn-danger btn-sm" type="submit" name="cancel" value="Отменить">
        </form>
    </div>
<?  require_once '../partsOfPages/footer.php';?>	