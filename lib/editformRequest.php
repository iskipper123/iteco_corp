   <link rel="stylesheet" type="text/css" href="/css/form.css" />
<?php

    require_once '../partsOfPages/head.php';
    require_once "../lib/db.php";
    require_once "../lib/vars.php";
    
    $db = DB::getObject();

    if(isset($_GET["edit"])) {
        $id = $_GET["edit"];
                
        $result_set = $db->getRowWhere("form_requests_customer", "id", $id);
        $row = $result_set->fetch_assoc();
        
        $result_set11 = $db->getrow_form("contractors", "name", $row[customer], "isDeleted", 0, "Заказчик", "name");
        $row11 = $result_set11->fetch_assoc();
        $customer = $row11[name];
        if ($row11[name] != '') {
            $result_set12 = $db->getRowWhere("contractors", "id", $row11[name]);
            $row12 = $result_set11->fetch_assoc();
            $customer = $row12[name];
            $error_customer_exist = "Контрагент есть в базе";
        } else {
            $error_customer = "Контрагента нету в базе <a class='addrequest' data-toggle='modal' data-target='#client_modal'  data-client_id='../admin/addCustomer.php'>Создать</a>";
        }




  $result_set13 = $db->getrow_form("contractors", "name", $row[carrier], "isDeleted", 0, "Перевозчик", "name");
        $row13 = $result_set13->fetch_assoc();
        $carrier = $row13[name];
        if ($row13[name] != '') {
            $result_set14 = $db->getRowWhere("contractors", "id", $row13[name]);
            $row14 = $result_set14->fetch_assoc();
            $carrier = $row14[name];
            $error_carrier_exist = "Контрагент есть в базе";
        } else {
            $error_carrier = "Перевозчика нету в базе <a class='addrequest_carrier' data-toggle='modal' data-target='#client_modal'  data-client_id='../admin/addCarrier.php'>Создать</a>";
        }





    }
    
    if(isset($_POST["edit"])) {
        $idUser = $_GET['idUser'];
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
        $time = $_POST["time"];
        $term = $_POST["term"];
        $transportType = $_POST["transportType"];
        $broker = $_POST["broker"];
        $pogran = $_POST["pogran"];
        $weight = $_POST["weight"];
        $weight_var = $_POST["weight_var"];
        $v = $_POST["v"];
        $v_var = $_POST["v_var"];


                $carrier = $_POST["carrier"];
        $carrier_name = $_POST["carrier_name"];
        $carrier_details = $_POST["carrier_details"];
        $carrier_phone = $_POST["carrier_phone"];
        $carrier_auto_nr = $_POST["carrier_auto_nr"];
        $carrier_fio = $_POST["carrier_fio"];
        $carrier_transport_type = $_POST["carrier_transport_type"];
        $carrier_adress = $_POST["carrier_adress"];
        $carrier_pogran = $_POST["carrier_pogran"];

        $carrier_teh_pasport = $_FILES["carrier_teh_pasport"]["name"];
        $carrier_prava = $_FILES["carrier_prava"]["name"];
        $carrier_docs = $_FILES["carrier_docs"]["name"];

        $phone_other_companies_carrier = $_POST["phone_other_companies_carrier"];

        $error_carrier = "";
        $error_carrier_name = "";
        $error_carrier_details = "";
        $error_carrier_phone = "";
        $error_carrier_auto_nr = "";
        $error_carrier_fio = "";
        $error_carrier_transport_type = "";
        $error_carrier_adress = "";
        $error_carrier_pogran = "";
        $error_carrier_teh_pasport = "";
        $error_carrier_prava = "";
        $error_carrier_docs = "";
        $error_phone_other_companies_carrier = "";

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

       
        if(strlen($carrier) == 0) { 
            $error_carrier = "Не заполнено поле";
            $error = true;
        }     
        if(strlen($carrier_name) == 0) {
            $error_carrier_name = "Не заполнено поле";
            $error = true;
        }   
        if(strlen($carrier_details) == 0) {
            $error_carrier_details = "Не заполнено поле";
            $error = true;
        }          
        if(strlen($carrier_phone) == 0) {
            $error_carrier_phone = "Не заполнено поле";
            $error = true;
        }   
        if(strlen($carrier_auto_nr) == 0) {
            $error_carrier_auto_nr = "Не заполнено поле";
            $error = true;
        } 
        if(strlen($carrier_fio) == 0) {
            $error_carrier_fio = "Не заполнено поле";
            $error = true;
        } 
        if(strlen($carrier_transport_type) == 0) {
            $error_carrier_transport_type = "Не заполнено поле";
            $error = true;
        } 
        if(strlen($carrier_adress) == 0) {
            $error_carrier_adress = "Не заполнено поле";
            $error = true;
        } 
        if(strlen($carrier_pogran) == 0) {
            $error_carrier_pogran = "Не заполнено поле";
            $error = true;
        } 


        if(strlen($phone_other_companies_carrier) == 0) {
            $error_phone_other_companies_carrier = "Не заполнено поле";
            $error = true;
        }

        //найти id заказчика
        $result_set7 = $db->getrow_form("contractors", "name", $customer, "isDeleted", 0, "Заказчик", "name");
        if($result_set7->num_rows == 0) {
            $customer = $customer;
        } 
        else $row7 = $result_set7->fetch_assoc();

        if(!$error) {
            $db->editFormRequest($id, $dateShipping, $time, $term, $weight, $weight_var, $v, $v_var, $transportType, $from, $to, $route, $pogran, $broker, $customer, $adress_load, $adress_unload, $custom_in, $custom_out, $contact_factory, $contact_broker, $contact_recipient, $pay_period, $company_details, $phone_other_companies, $customer_name, $customer_phone, $status, $date, $idUser, $carrier, $carrier_name, $carrier_details, $carrier_phone, $carrier_auto_nr, $carrier_fio, $carrier_transport_type, $carrier_adress, $carrier_pogran, $phone_other_companies_carrier); 
                            
            header("Location: formRequests.php?success");
            exit;
        } else {
            echo $error;
        }
    }
    else if(isset($_POST["cancel"])) {
        header("Location: formRequests.php");
        exit;       
    }
    
    $result_set2 = $db->getRowWhereOrder("users", "isDeleted", 0, "name");
    require_once '../partsOfPages/head.php';
    if($_SESSION["userType"] == 1)      require_once "../partsOfPages/menuForAdmin.php"; 
    else if($_SESSION["userType"] == 2) require_once "../partsOfPages/menuForUser.php"; ?>
    <div class="col-md-12 pt-3">
          <img src="/images/form_logo.png" alt="iteco logo" id="logoImg" style="  margin: 0px auto 30px auto; display: block;">
  <form name="" action="" method="post" enctype="multipart/form-data" class="form-group col-md-6 mr-6" style="margin:0px auto;" id="out_form">
            <div class="row">
                 <div class="form-group col-md-12 mr-12">
                  
                    <h1 style="text-align: center;">Заявка Заказчик</h1>
                </div>
                  <div class="form-group col-md-6 mr-6">
                    <label for="tag" class="required">Название компании</label>
                    <input class="form-control<?=isset($error_customer)&&$error_customer!=''?' is-invalid':''?>" data-auto-close="true" type="text" name="customer" id="tag" autocomplete="off" value="<?=isset($_POST["customer"])? $_POST["customer"]:$row[customer]?>">
                    <div class="invalid-feedback" <?=isset($error_customer)&&$error_customer!=''?'style="display:block;"':''?>><?=$error_customer?></div>

                    <div class="valid-feedback" style="color:green;" <?=isset($error_customer_exist)&&$error_customer_exist!=''?'style="display:block;"':''?>><?=$error_customer_exist?></div>
                </div>
                 <div class="form-group col-md-6 mr-6">
                    <label for="customer" class="required">Реквизиты</label>
                    <textarea class="form-control<?=isset($error_company_details)&&$error_company_details!=''?' is-invalid':''?>" data-auto-close="true" type="text" name="company_details" id="company_details" autocomplete="off"><?=isset($_POST["company_details"])? $_POST["company_details"]:$row[company_details]?></textarea>
                    <div class="invalid-feedback" <?=isset($error_company_details)&&$error_company_details!=''?'style="display:block;"':''?>><?=$error_company_details?></div>
                </div>
                <div class="form-group col-md-6 mr-6"> 
                    <label for="customer" class="required">Имя</label>
                    <input class="form-control<?=isset($error_customer_name)&&$error_customer_name!=''?' is-invalid':''?>" data-auto-close="true" type="text" name="customer_name" id="customer_name" autocomplete="off" value="<?=isset($_POST["customer_name"])? $_POST["customer_name"]:$row[customer_name]?>">
                    <div class="invalid-feedback" <?=isset($error_customer_name)&&$error_customer_name!=''?'style="display:block;"':''?>><?=$error_customer_name?></div>
                </div>
                <div class="form-group col-md-6 mr-6">
                    <label for="customer" class="required">Телефон</label>
                    <input class="form-control<?=isset($error_customer_phone)&&$error_customer_phone!=''?' is-invalid':''?>" data-auto-close="true" type="text" name="customer_phone" id="customer_phone" autocomplete="off" value="<?=isset($_POST["customer_phone"])? $_POST["customer_phone"]:$row[customer_phone]?>">
                    <div class="invalid-feedback" <?=isset($error_customer_phone)&&$error_customer_phone!=''?'style="display:block;"':''?>><?=$error_customer_phone?></div>
                </div>
                <div class="form-group col-md-6 mr-6">
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
        <div class="form-group col-md-6 mr-6">
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



        <div class="form-group col-md-6 mr-6">
            <label for="dateInput" class="required">Дата погрузки</label>
            <input class="form-control<?=isset($error_dateShipping)&&$error_dateShipping!=''?' is-invalid':''?> datepicker-here" data-auto-close="true" type="text" name="dateShipping" id="dateInput" placeholder="дд.мм.гггг" autocomplete="off" value="<?=isset($_POST["dateShipping"])? $_POST["dateShipping"]:$row[dateShipping]?>">
            <div class="invalid-feedback" <?=isset($error_dateShipping)&&$error_dateShipping!=''?'style="display:block;"':''?>><?=$error_dateShipping?></div>
        </div>

  <div class="form-group col-md-6 mr-6">
            <label for="brokerInput" class="required">Декларант (Broker)</label>
             <input class="form-control<?=isset($error_broker)&&$error_broker!=''?' is-invalid':''?>" data-auto-close="true" type="text" name="broker" id="brokerInput" autocomplete="off" value="<?=isset($_POST["broker"])? $_POST["broker"]:$row[broker]?>">
          
            <div class="invalid-feedback" <?=isset($error_broker)&&$error_broker!=''?'style="display:block;"':''?>><?=$error_broker ?></div>
        </div>      

            <div class="form-group col-md-6 mr-6">
                    <label for="customer" class="required">Адрес загрузки</label>
                    <input class="form-control<?=isset($error_adress_load)&&$error_adress_load!=''?' is-invalid':''?>" data-auto-close="true" type="text" name="adress_load" id="adress_load" autocomplete="off" value="<?=isset($_POST["adress_load"])? $_POST["adress_load"]:$row[adress_load]?>">
                    <div class="invalid-feedback" <?=isset($error_adress_load)&&$error_adress_load!=''?'style="display:block;"':''?>><?=$error_adress_load?></div>
                </div>
                <div class="form-group col-md-6 mr-6">
                    <label for="customer" class="required">Адрес разгрузки</label>
                    <input class="form-control<?=isset($error_adress_unload)&&$error_adress_unload!=''?' is-invalid':''?>" data-auto-close="true" type="text" name="adress_unload" id="adress_unload" autocomplete="off" value="<?=isset($_POST["adress_unload"])? $_POST["adress_unload"]:$row[adress_unload]?>">
                    <div class="invalid-feedback" <?=isset($error_adress_unload)&&$error_adress_unload!=''?'style="display:block;"':''?>><?=$error_adress_unload?></div>
                </div>

            
                <div class="form-group col-md-6 mr-6">
                    <label for="customer" class="required">Адрес Затаможки</label>
                    <input class="form-control<?=isset($error_customer)&&$error_custom_in!=''?' is-invalid':''?>" data-auto-close="true" type="text" name="custom_in" id="custom_in" autocomplete="off" value="<?=isset($_POST["custom_in"])? $_POST["custom_in"]:$row[custom_in]?>">
                    <div class="invalid-feedback" <?=isset($error_custom_in)&&$error_custom_in!=''?'style="display:block;"':''?>><?=$error_custom_in?></div>
                </div>
                <div class="form-group col-md-6 mr-6">
                    <label for="customer" class="required">Адрес Растаможки</label>
                    <input class="form-control<?=isset($error_custom_out)&&$error_custom_out!=''?' is-invalid':''?>" data-auto-close="true" type="text" name="custom_out" id="custom_out" autocomplete="off" value="<?=isset($_POST["custom_out"])? $_POST["custom_out"]:$row[custom_out]?>">
                    <div class="invalid-feedback" <?=isset($error_custom_out)&&$error_custom_out!=''?'style="display:block;"':''?>><?=$error_custom_out?></div>
                </div>
                    <div class="form-group col-md-12 mr-12">
                    <label for="customer" class="required">Контактные телефоны на заводе</label>
                    <input class="form-control<?=isset($error_contact_factory)&&$error_contact_factory!=''?' is-invalid':''?>" data-auto-close="true" type="text" name="contact_factory" id="contact_factory" autocomplete="off" value="<?=isset($_POST["contact_factory"])? $_POST["contact_factory"]:$row[contact_factory]?>">
                    <div class="invalid-feedback" <?=isset($error_contact_factory)&&$error_contact_factory!=''?'style="display:block;"':''?>><?=$error_contact_factory?></div>
                </div>
                <div class="form-group col-md-12 mr-12">
                    <label for="customer" class="required">Контактные телефоны 1-2 компаний с кем работали за последний месяц( с целью проверки платежеспособности)</label>
                    <textarea class="form-control<?=isset($error_phone_other_companies)&&$error_phone_other_companies!=''?' is-invalid':''?>" data-auto-close="true" type="text" name="phone_other_companies" id="phone_other_companies" autocomplete="off"><?=isset($_POST["phone_other_companies"])? $_POST["phone_other_companies"]:$row[phone_other_companies]?></textarea>
                    <div class="invalid-feedback" <?=isset($error_phone_other_companies)&&$error_phone_other_companies!=''?'style="display:block;"':''?>><?=$error_phone_other_companies?></div>
                </div>
            <div class="row">
                 <div class="form-group col-md-12 mr-12">
                    <h1 style="text-align: center;">Данные Перевозчика</h1>
                </div>
        <div class="form-group col-md-12 mr-12">
                    <label for="tag1" class="required">Название компании</label>
                    <input class="form-control<?=isset($error_carrier)&&$error_carrier!=''?' is-invalid':''?>" data-auto-close="true" type="text" name="carrier" id="tag1" autocomplete="off" value="<?=isset($_POST["carrier"])? $_POST["carrier"]:$row[carrier]?>">
                    <div class="invalid-feedback" <?=isset($error_carrier)&&$error_carrier!=''?'style="display:block;"':''?>><?=$error_carrier?></div>

                    <div class="valid-feedback" style="color:green;" <?=isset($error_carrier_exist)&&$error_carrier_exist!=''?'style="display:block;"':''?>><?=$error_carrier_exist?></div>
                </div>
                 <div class="form-group col-md-12 mr-12">
                    <label for="carrier" class="required">Реквизиты</label>
                    <textarea class="form-control<?=isset($error_carrier_details)&&$error_carrier_details!=''?' is-invalid':''?>" data-auto-close="true" type="text" name="carrier_details" id="carrier_details" autocomplete="off"><?=isset($_POST["carrier_details"])? $_POST["carrier_details"]:$row[carrier_details]?></textarea>
                    <div class="invalid-feedback" <?=isset($error_carrier_details)&&$error_carrier_details!=''?'style="display:block;"':''?>><?=$error_carrier_details?></div>
                </div>
                <div class="form-group col-md-12 mr-12"> 
                    <label for="carrier" class="required">Имя</label>
                    <input class="form-control<?=isset($error_carrier_name)&&$error_carrier_name!=''?' is-invalid':''?>" data-auto-close="true" type="text" name="carrier_name" id="carrier_name" autocomplete="off" value="<?=isset($_POST["carrier_name"])? $_POST["carrier_name"]:$row[carrier_name]?>">
                    <div class="invalid-feedback" <?=isset($error_carrier_name)&&$error_carrier_name!=''?'style="display:block;"':''?>><?=$error_carrier_name?></div>
                </div>
                <div class="form-group col-md-12 mr-12">
                    <label for="carrier" class="required">Телефон</label>
                    <input class="form-control<?=isset($error_carrier_phone)&&$error_carrier_phone!=''?' is-invalid':''?>" data-auto-close="true" type="text" name="carrier_phone" id="carrier_phone" autocomplete="off" value="<?=isset($_POST["carrier_phone"])? $_POST["carrier_phone"]:$row[carrier_phone]?>">
                    <div class="invalid-feedback" <?=isset($error_carrier_phone)&&$error_carrier_phone!=''?'style="display:block;"':''?>><?=$error_carrier_phone?></div>
                </div>
                  <div class="form-group col-md-12 mr-12">
                <label for="carrier" class="required">Номер автомобиля</label>
                    <input class="form-control<?=isset($error_carrier_auto_nr)&&$error_carrier_auto_nr!=''?' is-invalid':''?>" data-auto-close="true" type="text" name="carrier_auto_nr" id="carrier_auto_nr" autocomplete="off" value="<?=isset($_POST["carrier_auto_nr"])? $_POST["carrier_auto_nr"]:$row[carrier_auto_nr]?>">
                    <div class="invalid-feedback" <?=isset($error_carrier_auto_nr)&&$error_carrier_auto_nr!=''?'style="display:block;"':''?>><?=$error_carrier_auto_nr?></div>
                 </div>

                <div class="form-group col-md-12 mr-12">
                <label for="carrier" class="required">Водитель Ф.И.О</label>
                    <input class="form-control<?=isset($error_carrier_fio)&&$error_carrier_fio!=''?' is-invalid':''?>" data-auto-close="true" type="text" name="carrier_fio" id="carrier_fio" autocomplete="off" value="<?=isset($_POST["carrier_fio"])? $_POST["carrier_fio"]:$row[carrier_fio]?>">
                    <div class="invalid-feedback" <?=isset($error_carrier_fio)&&$error_carrier_fio!=''?'style="display:block;"':''?>><?=$error_carrier_fio?></div>
                 </div>

                <div class="form-group col-md-12 mr-12">
                <label for="carrier" class="required">Тип транспорта</label>
                                       <select class="form-control<?=isset($error_carrier_transport_type)&&$error_carrier_transport_type!=''?' is-invalid':''?>" name="carrier_transport_type" id="categoryInput">
                <? if($row[carrier_transport_type] == "") { ?>
                               <option selected="selected"></option>
                           <?}
                           else { ?>
                               <option selected="selected"> <?=$row[carrier_transport_type] ?> </option>
                           <?}?>
                <? for($i = 0; $i < count($arrayOfTransportType); $i++) {
                    if($carrier_transport_type == $arrayOfTransportType[$i]) { ?>
                        <option selected="selected"><?=$arrayOfTransportType[$i] ?></option>
                    <?}
                    else {?>
                        <option><?=$arrayOfTransportType[$i] ?></option>
                    <?}?>
                <?}?>
            </select>


                    <div class="invalid-feedback" <?=isset($error_carrier_transport_type)&&$error_carrier_transport_type!=''?'style="display:block;"':''?>><?=$error_carrier_transport_type?></div>
                 </div>

                <div class="form-group col-md-12 mr-12">
                <label for="carrier" class="required">Маршрут Следования</label>
                    <input class="form-control<?=isset($error_carrier_adress)&&$error_carrier_adress!=''?' is-invalid':''?>" data-auto-close="true" type="text" name="carrier_adress" id="carrier_adress" autocomplete="off" value="<?=isset($_POST["carrier_adress"])? $_POST["carrier_adress"]:$row[carrier_adress]?>">
                    <div class="invalid-feedback" <?=isset($error_carrier_adress)&&$error_carrier_adress!=''?'style="display:block;"':''?>><?=$error_carrier_adress?></div>
                 </div>

                <div class="form-group col-md-12 mr-12">
                <label for="carrier" class="required">Погран-Переходы</label>
                    <input class="form-control<?=isset($error_carrier_pogran)&&$error_carrier_pogran!=''?' is-invalid':''?>" data-auto-close="true" type="text" name="carrier_pogran" id="carrier_pogran" autocomplete="off" value="<?=isset($_POST["carrier_pogran"])? $_POST["carrier_pogran"]:$row[carrier_pogran]?>">
                    <div class="invalid-feedback" <?=isset($error_carrier_pogran)&&$error_carrier_pogran!=''?'style="display:block;"':''?>><?=$error_carrier_pogran?></div>
                 </div>

                <div class="form-group col-md-4 mr-4">
                <label for="carrier" class="required">Техпаспорт</label>
                <a target="_blank" href="/<?=isset($_POST["carrier_teh_pasport"])? $_POST["carrier_teh_pasport"]:$row[carrier_teh_pasport]?>">Посмотреть</a>
                 </div>

                <div class="form-group col-md-4 mr-4">
                <label for="carrier" class="required">Водительский паспорт</label>
                  <a target="_blank" href="/<?=isset($_POST["carrier_prava"])? $_POST["carrier_prava"]:$row[carrier_prava]?>">Посмотреть</a>
                 </div>

                <div class="form-group col-md-4 mr-4">
                <label for="carrier" class="required">Документы</label>
                    <a target="_blank" href="/<?=isset($_POST["carrier_docs"])? $_POST["carrier_docs"]:$row[carrier_docs]?>">Посмотреть</a>
                 </div>

                <div class="form-group col-md-12 mr-12">
                    <label for="carrier" class="required">Контактные телефоны 1-2 компаний с кем работали за последний месяц</label>
                    <textarea class="form-control<?=isset($error_phone_other_companies_carrier)&&$error_phone_other_companies_carrier!=''?' is-invalid':''?>" data-auto-close="true" type="text" name="phone_other_companies_carrier" id="phone_other_companies_carrier" autocomplete="off"><?=isset($_POST["phone_other_companies_carrier"])? $_POST["phone_other_companies_carrier"]:$row[phone_other_companies_carrier]?></textarea>
                    <div class="invalid-feedback" <?=isset($error_phone_other_companies_carrier)&&$error_phone_other_companies_carrier!=''?'style="display:block;"':''?>><?=$error_phone_other_companies_carrier?></div>
                </div>

            <input class="btn btn-secondary btn-sm" type="submit" name="edit" value="Сохранить" style="margin:0px auto; display: block; padding: 0 60px;">
        </form>
    </div>
    <script defer>
        function autocompleteTag(){
            var ajax = new XMLHttpRequest();
            ajax.open("GET", "/lib/autocomplete.php", true);
            ajax.onload = function () {
                var list = JSON.parse(ajax.responseText);
                new Awesomplete(document.querySelector("#tag"), {minChars: 1, list: list});
            };
            ajax.send();
        }
        function autocompleteTag1(){
            var ajax = new XMLHttpRequest();
            ajax.open("GET", "/lib/autocomplete1.php", true);
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


    $(document).ready(function(){
   $('.addrequest').click(function(){
  var client_id = $(this).data('client_id');
   $.ajax({
    type: 'POST',
    url: client_id,
    data: { 
        name: $("input[name*='customer']").val(), 
        contactName: $("input[name*='customer_name']").val(), 
        phone: $("input[name*='customer_phone']").val(), 
        bankDetails: $("textarea[name*='company_details']").val()
    },
    success: function(response){ 
      $('.modal-body').html(response); 
    }
  });
 });   

   $('.addrequest_carrier').click(function(){  
  var client_id = $(this).data('client_id');
   $.ajax({
    type: 'POST',
    url: client_id,
    data: { 
        name: $("input[name*='carrier']").val(), 
        contactName: $("input[name*='carrier_name']").val(), 
        phone: $("input[name*='carrier_phone']").val(), 
        bankDetails: $("textarea[name*='carrier_details']").val()
    },
    success: function(response){ 
      $('.modal-body').html(response); 
    }
  });
 });
});

    </script>

<!-- Modal -->
<div class="modal fade dashbord_modal" id="client_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div id="mask" class="close" style="width: 240px;height:inherit;float:left;"></div>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      </div>
    </div>
  </div>
</div>