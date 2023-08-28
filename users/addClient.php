<?php
    session_start();
	require_once "../lib/db.php";
     require_once "../lib/vars.php";  
 
    $title = 'Добавить клиента';   
    
	$db = DB::getObject(); 
$countryTags = array();

    if(isset($_SESSION["id"])) {
        $idUser = $_SESSION["id"];
        $name_user = $db->getUserByID($idUser);   
        $name_user_name = $name_user->fetch_assoc(); 
    }
	
	$result_set2 = $db->getRowWhereOrder("users", "isDeleted", 0, "name");
    require_once '../partsOfPages/head.php';
    require_once "../partsOfPages/menuForAdmin.php"; ?>
    <div class="col-md-12 pt-3">
        <form id="addclient" name="" action="" method="post">
            <input class="btn btn-danger btn-sm" type="submit" name="cancel" value="Вернуться назад"> <br /> <br />
            <div class="row">
                <div class="form-group col-md-4 mr-4">
                    <label for="nameInput" class="required">Название компании</label>
                    <input class="form-control<?=isset($error_name)&&$error_name!=''?' is-invalid':''?>" type="text" name="name" id="nameInput" value="<?=$_POST["name"]?>">
                    <input class="form-control<?=isset($error_name)&&$error_name!=''?' is-invalid':''?>" type="text" name="company_form" id="company_form" value="<?=$_POST["company_form"]?>">
                    <div class="invalid-feedback" <?=isset($error_name)&&$error_name!=''?'style="display:block;"':''?>><?=$error_name ?></div>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="contactNameInput" class="required">Контактное лицо</label>
                    <input class="form-control<?=isset($error_contactName)&&$error_contactName!=''?' is-invalid':''?>" type="text" name="contactName" id="contactNameInput" value="<?=$_POST["contactName"]?>">
                    <div class="invalid-feedback" <?=isset($error_contactName)&&$error_contactName!=''?'style="display:block;"':''?>><?=$error_contactName ?></div>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="dateInput" class="required">Дата следующего контакта</label>
                    <input class="form-control<?=isset($error_date)&&$error_date!=''?' is-invalid':''?> datepicker-here" data-auto-close="true" type="text" name="date" id="dateInput" placeholder="дд.мм.гггг" autocomplete="off" value="<?=$date?>">
                    <div class="invalid-feedback" <?=isset($error_date)&&$error_date!=''?'style="display:block;"':''?>><?=$error_date ?></div>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="commentsInput">Комментарий по грузу/Компании</label>
                    <textarea class="form-control" name="comments" id="commentsInput" ><?=$comments?></textarea>
                </div>
                <div class="form-group col-md-4 mr-4" style="display: none;">
                    <label for="idUserInput" class="required">Выберите сотрудника</label>
                    <select class="form-control<?=isset($error_idUser)&&$error_idUser!=''?' is-invalid':''?>" name="idUser" id="idUserInput">
                                <option selected="selected" value="<?=$idUser?>"><?=$name_user_name[name]?></option>
                    </select>
                    <div class="invalid-feedback" <?=isset($error_idUser)&&$error_idUser!=''?'style="display:block;"':''?>><?=$error_idUser ?></div>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="phoneInput">Телефон</label>
                    <textarea class="form-control" name="phone" id="phoneInput"><?=$_POST["phone"]?></textarea>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <label for="directionsInput">Направления</label>
                    <input class="form-control<?=isset($error_directions)&&$error_directions!=''?' is-invalid':''?>" type="text" name="directions" id="directionsInput" value="<?=isset($_POST["directions"])? $_POST["directions"]:implode(";", $countryTags)?>">
                    <div class="invalid-feedback" <?=isset($error_directions)&&$error_directions!=''?'style="display:block;"':''?>><?=$error_directions?></div>
                </div>
                <div class="form-group col-md-4 mr-4">
                    <? if($isMarked == 1) {?>
                        <input type="checkbox" checked name="isMarked" value="1"> <b>Метка</b> <br />
                    <?} else {?>
                        <input type="checkbox" name="isMarked" value="1"> <b>Метка</b> <br />
                    <?}?>
                </div>
            </div>
            <input class="btn btn-secondary btn-sm" onclick="addclient()"  name="add" value="Добавить">
            <input class="btn btn-danger btn-sm" type="submit" name="cancel" value="Отменить">
        </form>
    </div>
    <script>
function addclient(){
     $.ajax({
        type: "POST",
        url: "addClient_ajax.php",
        data: $('#addclient').serialize(),
         beforeSend: function () {
                $('#add').attr("disabled","disabled");
                $('.modal-body').css('opacity', '.5');
            },
            success: function(data) {
                if (data != true) {
                   $('.statusMsg').html('<span style="color:red; text-align:center;">'+data+'</p>');

                } else {
                     $('.statusMsg').html('<span style="color:green; text-align:center;">Клиент успешно добавлен!</p>');

  $('#addclient')[0].reset();

                    setTimeout(
                  function() 
                  {
                     location.reload();
                  }, 100);  
                    
                }
                $('#add').removeAttr("disabled");
                $('.modal-body').css('opacity', '');
            }
    }); 
    }
</script>
    <script defer>
        function autocompleteTag3(){
            var ajax = new XMLHttpRequest();
            ajax.open("GET", "../lib/autocomplete2.php", true);
            ajax.onload = function () {
                var list = JSON.parse(ajax.responseText);
                new Awesomplete(document.querySelector("#nameInput"), {minChars: 1, list: list}); 
            };
            ajax.send();
        }
        document.getElementById('nameInput').addEventListener("awesomplete-select", function(event) {
            let extra = event.text.extraData;
            $('#contactNameInput').val(extra.contactName);
            $('#phoneInput').val(extra.phone);
            $('#countryInput').val(extra.country);
            $('#company_form').val(extra.company_form); 


        });
		$(function(){
            autocompleteTag3();
            var postDate = $('#dateInput').val();
            var datepicker = $('#dateInput').datepicker().data('datepicker');
            if(postDate!=''){
                postDate = postDate.split(".");
                datepicker.selectDate(new Date(postDate[2],postDate[1]- 1,postDate[0]));
            }
            $('#directionsInput').tagsInput({'delimiter': [';'],'defaultText':'теги'});
        });
	</script>
    <div class="statusMsg"></div>
        </div>
<?  require_once '../partsOfPages/footer.php';?>