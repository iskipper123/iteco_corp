<?php

    session_start();
    require_once "../lib/checkWasUserLoginedAndIsAdmin.php";
    require_once "../lib/db.php";
    require_once "../lib/vars.php";

     
    $title = 'Добавить Заявку';

    $db = DB::getObject(); 

    $result_set2 = $db->getRowWhereOrder("users", "isDeleted", 0, "name");
        if(isset($_GET["duplicate"])) {
        $id = $_GET["duplicate"]; 
                
        $result_set = $db->getRowWhere("requests", "id", $id);
        $row = $result_set->fetch_assoc();
        
        $result_set11 = $db->getRowWhere("contractors", "id", $row[customer]);
        $row11 = $result_set11->fetch_assoc();
        $customer = $row11[name];
        
        $result_set12 = $db->getRowWhere("contractors", "id", $row[carrier]);
        $row12 = $result_set12->fetch_assoc();
        $carrier = $row12[name];
        
        $date = date("d.m.Y", $row["date"]);
        
        $isCurrencyPayment = $row[isCurrencyPayment];
        $comision_static = $row[comision_static];
        $currency = $row[currency];
        
        $idUser = $row[idUser];
        $currency = $row[currency];
        $transportType = $row[transportType];
        $dateShipping = date("d.m.Y", $row[dateShipping]);
    }
    require_once '../partsOfPages/head.php';
    require_once "../partsOfPages/menuForAdmin.php"; ?>
    <div class="col-md-12 pt-3">
        <h1>Дублирование заявки</h1>
        <? require_once "../lib/requests.php"; ?>
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
        function autocompletefromInput(){
            var awesomplete = new Awesomplete(document.querySelector("#fromInput"), {minChars: 3,autoFirst: true});
            $("#fromInput").on("keyup", function(){
                let thisval = $(this).val();
                if(thisval.length>=3){
                    $.ajax({
                        url: 'https://geocode-maps.yandex.ru/1.x/?format=json&apikey=42f4289f-31ea-4a01-9939-e6785142a533&geocode=' + thisval,
                        type: 'GET',
                        dataType: 'json',
                        success: function (data) { 
                            var list = [];
                            for(var i = 0; i < data.response.GeoObjectCollection.featureMember.length; i++) {
                                var kind = data.response.GeoObjectCollection.featureMember[i].GeoObject.metaDataProperty.GeocoderMetaData.kind;
                                if(kind!='locality'&&kind!='province'&&kind!='country'){
                                    continue;
                                }else{
                                    //записываем в массив результаты, которые возвращает нам геокодер
                                    list.push(data.response.GeoObjectCollection.featureMember[i].GeoObject.name+', '+data.response.GeoObjectCollection.featureMember[i].GeoObject.metaDataProperty.GeocoderMetaData.AddressDetails.Country.CountryName);
                                }
                            }
                            awesomplete.list = list;
                        }
                    });
                }
            });
        }
        function autocompleteToInput(){
            var awesomplete = new Awesomplete(document.querySelector("#toInput"), {minChars: 3,autoFirst: true});
            $("#toInput").on("keyup", function(){
                let thisval = $(this).val();
                if(thisval.length>=3){
                    $.ajax({
                        url: 'https://geocode-maps.yandex.ru/1.x/?format=json&apikey=42f4289f-31ea-4a01-9939-e6785142a533&geocode=' + thisval,
                        type: 'GET',
                        dataType: 'json',
                        success: function (data) { 
                            var list = [];
                            for(var i = 0; i < data.response.GeoObjectCollection.featureMember.length; i++) {
                                var kind = data.response.GeoObjectCollection.featureMember[i].GeoObject.metaDataProperty.GeocoderMetaData.kind;
                                if(kind!='locality'&&kind!='province'&&kind!='country'){
                                    continue;
                                }else{
                                    //записываем в массив результаты, которые возвращает нам геокодер
                                    list.push(data.response.GeoObjectCollection.featureMember[i].GeoObject.name+', '+data.response.GeoObjectCollection.featureMember[i].GeoObject.metaDataProperty.GeocoderMetaData.AddressDetails.Country.CountryName);
                                }
                            }
                            awesomplete.list = list;
                        }
                    });
                }
            });
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