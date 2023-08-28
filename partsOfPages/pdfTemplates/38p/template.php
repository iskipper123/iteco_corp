<?php
if ($row['currency'] == "EURO") {
    $euro_sign = "€";
} elseif($row['currency'] == "USD"){
    $euro_sign = "$";
}

?>
<?php 
if ($row11[headName] != '') {
    $customer_name = $row12[headName];
} else {
    $customer_name = $row12[contactName];
}
?>

<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8" />
        <meta
            name="viewport"
            content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"
        />
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />
        <title>Заявка Перевозчик</title>
        <link rel="stylesheet" href="./editor/app.css" />
        <link rel="stylesheet" href="./editor/build/jodit.min.css" />
        <script src="./editor/build/jodit.js"></script>
    </head>
    <body>
       
        <div id="box">
            <h1>Заявка Перевозчик</h1>
             
            <textarea id="editor_carrier">
                <style>
          
            body{
                
            }
            table.colored{
                width:100%;
                font-size:10pt;
            }

            table.colored td.gray{
                background-color: rgb(230, 230, 230);width:30%;padding: 3px 10px;
            }
            .rightSide p{
                margin: 3px;
            }
            .leftSide p{
                margin: 3px;
            }
            hr {
                border: none;
                color: rgb(230, 230, 230);
                background-color: rgb(230, 230, 230);
                height: 2px;
                margin-top:0px;
            }
            .clear {
                clear: left;
            }

            table.colored{
                width:100%;
                font-size:10pt;
            }
            table.colored td.green{
                background-color: #797cba;width:25%;color:#fff;padding: 2px 10px;
            }
            table.colored td.gray{
                background-color: rgb(230, 230, 230);width:15%;padding: 2px 10px;
            }
            hr {
                border: none;
                color: rgb(230, 230, 230);
                background-color: rgb(230, 230, 230);
                height: 2px;
                margin-top:0px;
            }
            .clear {
                clear: left;
            }

            @media (max-width: 480px) {
                #box {
                    padding: 0;
                }
            }
            .header{    background: #797cba;
    padding: 20px 0;
    display: table;
    width: 100%;}
            .block-left,.block-right{display: table-cell;color:#fff;width: 33.3%;vertical-align: middle;padding: 0 5%;}
            .block-center{display: table-cell;color:#fff;width: 33.3%;vertical-align: middle;}
            .header p{margin:0;   line-height: 18px;  font-size: 11px;}
            p{margin:0;font-size: 11px;}
            .header_line td{font-size: 12px; color: #fff; text-align: center; vertical-align: middle;border:2px solid #fff;background-color: #201600;}
            .header_line tr{border:0;}
        </style>
            <div class="header">
                <div class="block-left" style="text-align: left;">
                    <p>FAHRWEST LOGISTIC SRL</p>
                    <p>Str.Alecu Russo 15, et 3, of.21</p>
                </div>
                <div class="block-center" style="text-align: center;">
                    <img src="https://hav.iteco.md/partsOfPages/pdfTemplates/38z/images/fahrwest.png" style="width: 250px;">
                </div> 
                <div class="block-right" style="text-align: right;">
                    <p>E-mail: Dmitri@fahrwest.com</p>
                    <p>Tel: 078100033</p>
                </div>
            </div>
            <table class="header_line" style="width: 100%;border:0; ">
                <tr>
                    <td style="width:15%">№ <?php echo $row['number']?></td>
                    <td style="width:70%">ДОГОВОР-ЗАЯВКА НА ТРАНСПОРТНО-ЭКСПЕДИЦИОННЫЕ УСЛУГИ<?php if ($row11['id'] == '1794') { echo ' К Договору No1 от 09.04.2022';} ?></td>
                    <td style="width:15%"><p>ОТ <?php echo $date?></p><p>Г.Кишинев</p></td>
                </tr>
            </table>
           <br>
            <table class="colored" cellspacing="2">
                <tr>
                    <td class="green">1. Заказчик</td>
                    <td class="gray" colspan="5">FAHRWEST LOGISTIC SRL</td>
                </tr>
                <tr>
                    <td class="green">2. Дата погрузки</td>
                    <td class="gray"><?php echo $dateShipping?></td>                   

                    <td class="gray" colspan="2">3. Дата разгрузки</td>
                    <td class="gray" colspan="2"><?php echo $row['term']?></td>
                </tr>
                <tr>
                    <td class="green">4. Маршрут следования</td>
                    <td class="gray" colspan="5"><?php echo $row['route']?></td>    
                </tr>
                <tr>
                    <td class="green">5. Адрес загрузки</td>
                    <td class="gray" colspan="5"><?php echo $row['address1']?></td>    
                </tr>

                <tr>
                    <td class="green">6. Контактное лицо-грузоотправитель</td>
                    <td class="gray" colspan="5"><?php echo $row['contactName1']?></td>  
                </tr>

                <tr>
                    <td class="green">7. Сведения о грузе</td>
                    <td class="gray" colspan="5"><?php echo $row['cargo']?></td>  
                </tr>
                <tr>
                    <td class="green">8. Загрузка</td>
                    <td class="gray"><?php echo $row['transportType']?></td>  
                    <td class="gray">9. Вес, тонн</td>
                    <td class="gray"><?php echo $row['weight'].' '.$row['weight_var']?></td>                      
                    <td class="gray">10. Объем, м3</td>
                    <td class="gray"><?php echo $row['v'].' '.$row['v_var']?></td>

                </tr>
                 <tr>
                    <td class="green">12. Сопроводительные документы</td>
                    <td class="gray" colspan="5">CMR, Invoice, упаковочнный лист, сертификат качества, экспортная декларация, сертификат о происхождении товара, фитосанитарный сертификат</td>  
                </tr>
                <tr>
                    <td class="green">13. Таможня экспорта</td>
                    <td class="gray" colspan="5"><?php echo $row['customs2']?></td>  
                </tr>
                <tr>
                    <td class="green">14. Таможня импорта</td>
                    <td class="gray" colspan="5"><?php echo $row['customs1']?></td>  
                </tr>
                <tr>
                    <td class="green">15. Пограничные переходы</td>
                    <td class="gray" colspan="5"><?php echo $row['pogran']?></td>  
                </tr>
                 <tr>
                    <td class="green">16. Адрес разгрузки</td>
                    <td class="gray" colspan="5"><?php echo $row['address2']?></td>  
                </tr>
                <tr>
                    <td class="green">17. Контактное лицо-грузополучатель</td>
                    <td class="gray" colspan="5"><?php echo $row['contactName2']?></td>  
                </tr>
                <tr>
                     <td class="green">18. Данные о ТС</td>
                    <td class="gray"><?php echo $row['carNumber']?></td>  
                    <td class="gray">19. Тип</td>
                    <td class="green" style="width:15%;"><?php echo $row['transportType']?></td>
                    <td class="gray">Темп. режим</td>
                    <td class="green" style="width:15%;"><?php echo $row['temperature']?></td>
                </tr>                
                <tr>
                    <td class="green">19. Данные о водителе</td>
                    <td class="gray"><?php echo $row['fio']?></td> 
                    <td class="gray">Телефон</td>
                    <td class="gray"><?php echo $row['driverPhones']?></td> 
                    <td class="gray">Декларант (Broker)</td>
                    <td class="gray"><?php echo $row['broker']?></td> 
                </tr>

                <tr>
                    <td class="green">20. Стоимость перевозки</td>
                    <td class="gray"><?php echo $row['carrierPrice'].' ('.$row['sumForCarrier'].')'?></td> 
                    <td class="gray"><?php echo $euro_sign?>
                           <?php if ($row['isCurrencyPayment'] == '1') { ?><p>(Комиссию своего банка оплачивает каждая сторона самостоятельно)</p><?php } ?>
                    </td> 

                     <td class="gray" colspan="3">
                    <?php if ($row['customs'] == 'import') {
                       echo 'по курсу BNM на дату импортного таможенного оформления РМ';
                    }elseif ($row['customs'] == 'export') {
                        echo 'по курсу BNM на дату экспортного таможенного оформления РМ';
                    }
                        ?></td> 
                </tr>
                <tr>
                    <td class="green">21. Срок оплаты</td>
                    <td class="gray" colspan="5">  
                        <?php if ($row['pay_variant'] == 'Авансовый платёж') {
                            echo 'Перечисление 30-50 процентов от суммы фрахта после таможенного оформления Т/С по скан копиям, но оригиналы надо в дальнейшем передать почтой физически, а это  (Накладная+Акт+Заявка с мокрой печатью+СМР и сертификат о резиденстве если фирма не из Молдовы)';
                        } elseif ($row['pay_variant'] == 'Оплата на выгрузке') {
                            echo 'Перечисление на следующий день после предоставления полного пакета документов в оригинале (Накладная+Акт+Заявка с мокрой печатью+СМР и сертификат о резиденстве если фирма не из Молдовы)';
                        } elseif ($row['pay_variant'] == 'Оплата на загрузке') {
                            echo 'Перечисление после таможенного оформления Т/С по скан копиям, но оригиналы надо в дальнейшем передать почтой физически, а это  (Накладная+Акт+Заявка с мокрой печатью+СМР и сертификат о резиденстве если фирма не из Молдовы)';
                        } elseif ($row['pay_variant'] == 'Стандартный') {
                            echo 'Перечисление в течении 20 рабочих дней после предоставления полного пакета документов в оригинале (Накладная+Акт+Заявка с мокрой печатью+СМР и сертификат о резиденстве если фирма не из Молдовы)';
                        } elseif($row['pay_variant'] == 'Наличный расчёт'){
                            echo 'Оплата производится наличными';
                        } else {
                            echo 'Перечисление в течении 10-14 рабочих дней после предоставления полного пакета документов в оригинале (Накладная+Акт+Заявка с мокрой печатью+СМР и сертификат о резиденстве если фирма не из Молдовы)';
                        } ?></td> 
                </tr>

            </table>
            <p>22. Заказчику предоставляется 48 часов на погрузку и 48 часов на разгрузку автомобиля, включая таможенные процедуры.</p>
            <p>23. За задержку транспорта (простой) более чем на срок указанный в пункте 1 по вине заказчика или перевозчика виновный выплачивает штраф в размере 100 $ за каждые сутки простоя на территории РМ и СНГ и 100€ в странах ЕС, исключая выходные дни.</p>
            <p>24. Перевозка осуществляется в соответствии конвенции CMR</p>
            <p>25. За каждые сутки опоздания доставки груза, перевозчик выплачивает штраф в размере 100$ если грузополучатель на территории РМ и СНГ и 50€ если получатель груза в странах ЕС.</p>
            <p>26. За отказ от погрузки(разгрузки) по вине заказчика – перевозчика, виновный выплачивает штраф в размере 20% от суммы договора</p>
            <p>27. В случае поломки транспортного средства, Перевозчик обязуется обеспечить замену транспортного средства за свой счет, в соответствии заявленных требований и без увеличения стоимости.</p>
            <p>28. Несогласованное изменение перевозчиком погран переходов –  за каждый штраф 100 $ на территории СНГ и 100 € в странах ЕС.</p>
            <p>29. Высылать информацию о местонахождении транспортного средства каждый день до 10:00, в случае несоблюдения условий, перевозчик обязуется выплатить штраф в размере 30$ на территории СНГ, и 30€ на территории ЕС.</p>
            <p>30. За несоответствие объёма машины, ставка будет уменьшена на 200 евро.</p>
            <p>31. Перевозчик несет полную материальную ответственность за груз с момента погрузки до его принятия на склад Получателя, в размере полной стоимости товара указанных в накладных. и расходов связанных с его отправкой</p>
            <p>32. Без подтверждения печатью, данный договор – заявка признаётся недействительной.</p>
            <p>33.Изменения или дополнение текста без согласования и дополнительного письменного уведомления об изменениях либо дополнениях в данном договоре-заявке считается недействительным и к исполнению невозможным.</p>
            <br>
            <div class="leftSide" style="float: left; width: 300px;">
            <p style="font-size:12px;font-weight:bold;text-align: left;">ЗАКАЗЧИК/ЭКСПЕДИТОР:</p>
            <p style="font-size:12px;text-align: left;">FAHRWEST LOGISTIC SRL</p>
            <p style="font-size:12px;text-align: left;">Bd.Renasterii Nationale 6, ap.90</p>
            <p style="font-size:12px;text-align: left;">Cod Fiscal - 1022600012316</p>
            <p style="font-size:12px;text-align: left;">TVA - 0612266</p>
            <p style="font-size:12px;text-align: left;">IBAN MD66AG000000022514703441</p>
            <p style="font-size:12px;text-align: left;">AGRNMD2X441</p>
            <p style="font-size:12px;text-align: left;">BC “Moldova-Agroindbank” S.A. Sucursala nr.18, Chisinau, SWIFT - AGRNMD2X441</p>
            <p style="font-size:12px;text-align: left;margin-top:4px;"><strong>Директор____________Repida D.A.</strong></p>
        </div>
        <div class="rightSide" style="float: right;    width: 300px;">
            <p style="font-size:12px;font-weight:bold;text-align: left;margin:0px;">ИСПОЛНИТЕЛЬ:</p>
           <?php echo str_replace(array("\r\n", "\r", "\n"), "<br />", $row12['bankDetails'])?>
               <p><strong>Директор____________<?php echo $customer_name ?></strong></p>
        </div> 
            <div class="clear"></div>
            <br><br><br><br>
          
          
         
            </textarea>
        </div>
        <script>
            new Jodit.make('#editor_carrier' ,{
                uploader: {
                    url: 'https://xdsoft.net/jodit/connector/index.php?action=fileUpload'
                },
                toolbarButtonSize: "large",
                filebrowser: {
                    ajax: {
                        url: 'https://xdsoft.net/jodit/connector/index.php'
                    }
                }
            });
        </script>
    </body>
</html>
