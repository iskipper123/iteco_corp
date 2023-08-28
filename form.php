<?php

$check = FALSE;
$hash = $_GET['hash'];


if( strpos(file_get_contents("get_url.txt"),$hash) !== false) {
  $check = TRUE;
 }


if($check) { ?>
 

   <link rel="stylesheet" type="text/css" href="/css/form.css" />
<?php
   
    require_once "lib/db.php";
    require_once "lib/vars.php";
    
    $title = 'Приём заявок';
	
	$_SESSION["userType"] = 1;
	$db = DB::getObject();
        if (isset($_GET['manager_id'])) {
            $idUser = $_GET['manager_id'];
        } else {
            $idUser = '1';
        }

	if(isset($_POST["add"])) {

        if (isset($_GET['manager_id'])) {
            $idUser = $_GET['manager_id'];
        } else {
            $idUser = '1';
        }

		$customer = $_POST["customer"];
		$dateShipping = $_POST["dateShipping"];
		$route = $_POST["route"];
		$adress_load = $_POST["adress_load"];
        $adress_unload = $_POST["adress_unload"];
		$custom_in = $_POST["custom_in"];
        $custom_out = $_POST["custom_out"];
		$contact_factory = $_POST["contact_factory"];
		$contact_broker = $_POST["contact_broker"];
		$contact_recipient = $_POST["contact_recipient"];
		$pay_period = $_POST["pay_period"];
		$company_details = $_POST["company_details"];
		$phone_other_companies = $_POST["phone_other_companies"];
		$customer_name = $_POST["customer_name"];
		$customer_phone = $_POST["customer_phone"];
        $status = 0;
        $date = date("Y-m-d");
        $from = $_POST["from"];
        $to = $_POST["to"];
		$transportType = $_POST["transportType"];
		$broker = $_POST["broker"];
		$pogran = $_POST["pogran"];
		$weight = $_POST["weight"];
		$weight_var = $_POST["weight_var"];
		$v = $_POST["v"];
		$v_var = $_POST["v_var"];

		$error_from = "";
        $error_to = "";
		$error_customer = "";
		$error_broker = "";
		$error_adress_load = "";
		$error_adress_unload = "";
		$error_custom_in = "";
		$error_custom_out = "";
		$error_contact_factory = "";
		$error_company_details = "";
		$error_phone_other_companies = "";
		$error_customer_name = "";
		$error_customer_phone = "";
		$error = false;
	
		if(strlen($customer) == 0) {
			$error_customer = "Не заполнено поле";
			$error = true;
		}	
        if(strlen($adress_load) == 0) {
            $error_adress_load = "Не заполнено поле";
            $error = true;
        }          
        if(strlen($adress_unload) == 0) {
            $error_adress_unload = "Не заполнено поле";
            $error = true;
        }   
        if(strlen($custom_in) == 0) {
            $error_custom_in = "Не заполнено поле";
            $error = true;
        } 
        if(strlen($custom_out) == 0) {
            $error_custom_out = "Не заполнено поле";
            $error = true;
        } 
        if(strlen($contact_factory) == 0) {
            $error_contact_factory = "Не заполнено поле";
            $error = true;
        } 
        if(strlen($company_details) == 0) {
            $error_company_details = "Не заполнено поле";
            $error = true;
        } 
        if(strlen($phone_other_companies) == 0) {
            $error_phone_other_companies = "Не заполнено поле";
            $error = true;
        } 
        if(strlen($customer_name) == 0) {
            $error_customer_name = "Не заполнено поле";
            $error = true;
        } 
        if(strlen($customer_phone) == 0) {
            $error_customer_phone = "Не заполнено поле";
            $error = true;
        }
        if(strlen($broker) == 0) {
			$error_broker = "Не заполнено поле";
			$error = true;
		}


		//найти id заказчика
		$result_set7 = $db->getrow_form("contractors", "name", $customer, "isDeleted", 0, "Заказчик", "name");
		if($result_set7->num_rows == 0) {
            $customer = $customer;
		} 
		else $row7 = $result_set7->fetch_assoc();

		if(!$error) {
			$db->addFormRequest($dateShipping, $weight, $weight_var, $v, $v_var, $broker, $customer, $adress_load, $adress_unload, $custom_in, $custom_out, $contact_factory, $company_details, $phone_other_companies, $customer_name, $customer_phone, $status, $date, $idUser);
			
			$id = $db->getLastID("form_requests_customer");				
			//$db->addLog($row7[id], time(), "Приём заявок. Контрагент добавлен в качестве заказчика.", 3, $id, $id);
			
			header("Location: success.php");
			exit;
		}
	}
	
	$result_set2 = $db->getRowWhereOrder("users", "isDeleted", 0, "name");
    require_once 'partsOfPages/head.php';?>

    <div class="col-md-12 pt-3" >
         <img src="/images/form_logo.png" alt="iteco logo" id="logoImg" style="  margin: 0px auto 30px auto; display: block;">
        <form name="" action="" method="post" enctype="multipart/form-data" class="form-group col-md-4 mr-4" style="margin:0px auto !important;" id="out_form">
            <div class="row">
            	 <div class="form-group col-md-12 mr-12">
            	   
            	    <h1 style="text-align: center;">Заявка контрагент</h1>
            	</div>
        <div class="form-group col-md-12 mr-12">
                    <label for="tag" class="required">Название компании</label>
                    <input class="form-control<?=isset($error_customer)&&$error_customer!=''?' is-invalid':''?>" data-auto-close="true" type="text" name="customer" id="tag" autocomplete="off" value="<?=isset($_POST["customer"])? $_POST["customer"]:$row[customer]?>">
                    <div class="invalid-feedback" <?=isset($error_customer)&&$error_customer!=''?'style="display:block;"':''?>><?=$error_customer?></div>

                    <div class="valid-feedback" style="color:green;" <?=isset($error_customer_exist)&&$error_customer_exist!=''?'style="display:block;"':''?>><?=$error_customer_exist?></div>
                </div>
                 <div class="form-group col-md-12 mr-12">
                    <label for="customer" class="required">Реквизиты</label>
                    <textarea class="form-control<?=isset($error_company_details)&&$error_company_details!=''?' is-invalid':''?>" data-auto-close="true" type="text" name="company_details" id="company_details" autocomplete="off"><?=isset($_POST["company_details"])? $_POST["company_details"]:$row[company_details]?></textarea>
                    <div class="invalid-feedback" <?=isset($error_company_details)&&$error_company_details!=''?'style="display:block;"':''?>><?=$error_company_details?></div>
                </div>
                <div class="form-group col-md-12 mr-12"> 
                    <label for="customer" class="required">Имя</label>
                    <input class="form-control<?=isset($error_customer_name)&&$error_customer_name!=''?' is-invalid':''?>" data-auto-close="true" type="text" name="customer_name" id="customer_name" autocomplete="off" value="<?=isset($_POST["customer_name"])? $_POST["customer_name"]:$row[customer_name]?>">
                    <div class="invalid-feedback" <?=isset($error_customer_name)&&$error_customer_name!=''?'style="display:block;"':''?>><?=$error_customer_name?></div>
                </div>
                <div class="form-group col-md-12 mr-12">
                    <label for="customer" class="required">Телефон</label>
                    <input class="form-control<?=isset($error_customer_phone)&&$error_customer_phone!=''?' is-invalid':''?>" data-auto-close="true" type="text" name="customer_phone" id="customer_phone" autocomplete="off" value="<?=isset($_POST["customer_phone"])? $_POST["customer_phone"]:$row[customer_phone]?>">
                    <div class="invalid-feedback" <?=isset($error_customer_phone)&&$error_customer_phone!=''?'style="display:block;"':''?>><?=$error_customer_phone?></div>
                </div>
        <div class="form-group col-md-12 mr-12">
            <label for="dateInput" class="required">Дата погрузки</label>
            <input class="form-control<?=isset($error_dateShipping)&&$error_dateShipping!=''?' is-invalid':''?> datepicker-here" data-auto-close="true" type="text" name="dateShipping" id="dateInput" placeholder="дд.мм.гггг" autocomplete="off" value="<?=isset($_POST["dateShipping"])? $_POST["dateShipping"]:$dateShipping?>">
            <div class="invalid-feedback" <?=isset($error_dateShipping)&&$error_dateShipping!=''?'style="display:block;"':''?>><?=$error_dateShipping?></div>
        </div>

                <div class="form-group col-md-12 mr-12">
            <label for="weightInput" class="required">Вес</label>
            <input class="form-control<?=isset($error_weight)&&$error_weight!=''?' is-invalid':''?>" type="text" name="weight" id="weightInput" autocomplete="off" value="<?=isset($_POST["weight"])? $_POST["weight"]:$row[weight]?>">
            <select class="form-control<?=isset($error_weight)&&$error_weight!=''?' is-invalid':''?>" name="weight_var" id="weight_var">
                        <? if($row[weight_var] == "") { ?>
                            <option selected="selected"></option>
                        <?}
                        else { ?>
                            <option selected="selected"> <?=$row[weight_var] ?> </option>
                        <?}?>
                        <? for($i = 0; $i < count($arrayOfweight); $i++) {
                            if($row[weight_var] != $arrayOfweight[$i]) { ?>
                                <option><?=$arrayOfweight[$i] ?></option>
                            <?}?>
                        <?}?>
                    </select>

            <div class="invalid-feedback" <?=isset($error_weight)&&$error_weight!=''?'style="display:block;"':''?>><?=$error_weight ?></div>
        </div>
        <div class="form-group col-md-12 mr-12">
            <label for="vInput" class="required">Объем</label>
            <input class="form-control<?=isset($error_v)&&$error_v!=''?' is-invalid':''?>" type="text" name="v" id="vInput" autocomplete="off" value="<?=isset($_POST["v"])? $_POST["v"]:$row[v]?>">
                   <select class="form-control<?=isset($error_v)&&$error_v!=''?' is-invalid':''?>" name="v_var" id="v_var">
                        <? if($row[v_var] == "") { ?>
                            <option selected="selected"></option>
                        <?}
                        else { ?>
                            <option selected="selected"> <?=$row[v_var] ?> </option>
                        <?}?>
                        <? for($i = 0; $i < count($arrayOfv); $i++) {
                            if($row[v_var] != $arrayOfv[$i]) { ?>
                                <option><?=$arrayOfv[$i] ?></option>
                            <?}?>
                        <?}?>
                    </select>
            <div class="invalid-feedback" <?=isset($error_v)&&$error_v!=''?'style="display:block;"':''?>><?=$error_v ?></div>
        </div>
      
  <div class="form-group col-md-12 mr-12">
            <label for="brokerInput" class="required">Декларант (Broker)</label>
              <input class="form-control<?=isset($error_broker)&&$error_broker!=''?' is-invalid':''?>" data-auto-close="true" type="text" name="broker" id="brokerInput" autocomplete="off" value="<?=isset($_POST["broker"])? $_POST["broker"]:$row[broker]?>">
            <div class="invalid-feedback" <?=isset($error_broker)&&$error_broker!=''?'style="display:block;"':''?>><?=$error_broker ?></div>
        </div>      

            <div class="form-group col-md-12 mr-12">
                    <label for="customer" class="required">Адрес загрузки</label>
                    <input class="form-control<?=isset($error_adress_load)&&$error_adress_load!=''?' is-invalid':''?>" data-auto-close="true" type="text" name="adress_load" id="adress_load" autocomplete="off" value="<?=isset($_POST["adress_load"])? $_POST["adress_load"]:$row[adress_load]?>">
                    <div class="invalid-feedback" <?=isset($error_adress_load)&&$error_adress_load!=''?'style="display:block;"':''?>><?=$error_adress_load?></div>
                </div>
                <div class="form-group col-md-12 mr-12">
                    <label for="customer" class="required">Адрес разгрузки</label>
                    <input class="form-control<?=isset($error_adress_unload)&&$error_adress_unload!=''?' is-invalid':''?>" data-auto-close="true" type="text" name="adress_unload" id="adress_unload" autocomplete="off" value="<?=isset($_POST["adress_unload"])? $_POST["adress_unload"]:$row[adress_unload]?>">
                    <div class="invalid-feedback" <?=isset($error_adress_unload)&&$error_adress_unload!=''?'style="display:block;"':''?>><?=$error_adress_unload?></div>
                </div>

                <div class="form-group col-md-12 mr-12">
                    <label for="customer" class="required">Контактное лицо на загрузке</label>
                    <input class="form-control<?=isset($error_contact_factory)&&$error_contact_factory!=''?' is-invalid':''?>" data-auto-close="true" type="text" name="contact_factory" id="contact_factory" autocomplete="off" value="<?=isset($_POST["contact_factory"])? $_POST["contact_factory"]:$row[contact_factory]?>">
                    <div class="invalid-feedback" <?=isset($error_contact_factory)&&$error_contact_factory!=''?'style="display:block;"':''?>><?=$error_contact_factory?></div>
                </div>
                <div class="form-group col-md-12 mr-12">
                    <label for="customer" class="required">Адрес Затаможки</label>
                    <input class="form-control<?=isset($error_customer)&&$error_custom_in!=''?' is-invalid':''?>" data-auto-close="true" type="text" name="custom_in" id="custom_in" autocomplete="off" value="<?=isset($_POST["custom_in"])? $_POST["custom_in"]:$row[custom_in]?>">
                    <div class="invalid-feedback" <?=isset($error_custom_in)&&$error_custom_in!=''?'style="display:block;"':''?>><?=$error_custom_in?></div>
                </div>
                <div class="form-group col-md-12 mr-12">
                    <label for="customer" class="required">Адрес Растаможки</label>
                    <input class="form-control<?=isset($error_custom_out)&&$error_custom_out!=''?' is-invalid':''?>" data-auto-close="true" type="text" name="custom_out" id="custom_out" autocomplete="off" value="<?=isset($_POST["custom_out"])? $_POST["custom_out"]:$row[custom_out]?>">
                    <div class="invalid-feedback" <?=isset($error_custom_out)&&$error_custom_out!=''?'style="display:block;"':''?>><?=$error_custom_out?></div>
                </div>
                 <div class="form-group col-md-12 mr-12">
                    <label for="customer" class="required">Груз</label>
                    <input class="form-control<?=isset($error_cargo)&&$error_cargo!=''?' is-invalid':''?>" data-auto-close="true" type="text" name="cargo" id="cargo" autocomplete="off" value="<?=isset($_POST["cargo"])? $_POST["cargo"]:$row[cargo]?>">
                    <div class="invalid-feedback" <?=isset($error_cargo)&&$error_cargo!=''?'style="display:block;"':''?>><?=$error_cargo?></div>
                </div>
                <div class="form-group col-md-12 mr-12">
                    <label for="customer" class="required">Контактные телефоны 1-2 компаний с кем работали за последний месяц( с целью проверки платежеспособности)</label>
                    <textarea class="form-control<?=isset($error_phone_other_companies)&&$error_phone_other_companies!=''?' is-invalid':''?>" data-auto-close="true" type="text" name="phone_other_companies" id="phone_other_companies" autocomplete="off"><?=isset($_POST["phone_other_companies"])? $_POST["phone_other_companies"]:$row[phone_other_companies]?></textarea>
                    <div class="invalid-feedback" <?=isset($error_phone_other_companies)&&$error_phone_other_companies!=''?'style="display:block;"':''?>><?=$error_phone_other_companies?></div>
                </div>
            <input class="btn btn-secondary btn-sm" type="submit" name="add" value="Добавить" style="margin:0px auto; display: block; padding: 0 60px;">
        </form>
    </div>
    <script defer>
        function autocompleteTag(){
            var ajax = new XMLHttpRequest();
            ajax.open("GET", "lib/autocomplete.php", true);
            ajax.onload = function () {
                var list = JSON.parse(ajax.responseText);
                new Awesomplete(document.querySelector("#tag"), {minChars: 1, list: list});
            };
            ajax.send();
        }
        function autocompleteTag1(){
            var ajax = new XMLHttpRequest();
            ajax.open("GET", "lib/autocomplete1.php", true);
            ajax.onload = function () {
                var list = JSON.parse(ajax.responseText);
                new Awesomplete(document.querySelector("#tag1"), {minChars: 1, list: list});
            };
            ajax.send();
        }

        
		$(function(){
            autocompleteTag();
            autocompleteTag1();
            var postDate = $('#dateShipping').val();
            var datepicker = $('#dateShipping').datepicker().data('datepicker');
            if(postDate!=''){
                postDate = postDate.split(".");
                datepicker.selectDate(new Date(postDate[2],postDate[1]- 1,postDate[0]));
            }
        });
	</script>
<?  require_once '../partsOfPages/footer.php';?>
<?php 
ob_clean();
 flush();
 
 readfile($s_file);
 exit();
}
else {
 exit("Не правильная ссылка!!!");
}
?>