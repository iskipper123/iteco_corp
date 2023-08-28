<?php 
if ($row11[headName] != '') {
    $customer_name = $row11[headName];
} else {
    $customer_name = $row11[contactName];
}
$data = date("d.m.Y");
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
            table.colored td.green,table.colored th.green{
                background-color: #797cba;width:25%;color:#fff;padding: 2px 10px;
            }
            table.colored td.gray, table.colored th.gray{
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
                    <td style="width:70%">Акт №<?php echo $row['number'];?> выполненных работ к договору<?php if ($row11['id'] == '1794') { echo ' No1 от 09.04.2022';} ?></td>
                    <td style="width:15%"><p>ОТ <?php echo $data?></p><p>Г.Кишинев</p></td>
                </tr>
            </table>
           <br>
            <p>Мы, нижеподписавшиеся, “FAHRWEST LOGISTIC” SRL в лице директора Репида Д.А., именуемый в дальнейшем “Исполнитель”, действующий на основании Устава и <?php echo $customer.' '.$row11['company_form']?> в лице директора <?php echo $customer_name; ?>., именуемый в дальнейшем “Заказчик”, действующий на основании Устава, составили данный акт выполненных работ на перевозку груза и оказание экспедиторских услуг:</p>
            <table class="colored" cellspacing="2">
                <tr>
                    <th class="green" style="width: 30px;"><strong>№</strong></th>
                    <th class="green"><strong>Маршрут</strong></th>
                    <th class="green"><strong>Регистрационный номер автомобиля</strong></th>
                    <th class="green"><strong>Наименование груза</strong></th>
                    <th class="green"><strong>Цена</strong></th>
                </tr>
                <tr>
                    <td class="gray" style="width: 30px;">1</td>
                    <td class="gray"><?php echo $row["from"]?> - <?php echo $row["to"]?></td>
                    <td class="gray"><?php echo $row['carNumber'];?></td>
                    <td class="gray"><?php echo $row['cargo'];?></td>
                    <td class="gray"><?php echo $row['customerPrice'].' '.$row['currency']; ?></td>
                </tr>
            </table>
            <p>Оплата производится по курсу НБМ в леях РМ на дату платежа.</p>
            <p></p>
            <p>Претензий стороны не имеют.</p>

               <div class="leftSide" style="width:49%;float: left;">
            <p style="font-size:12px;font-weight:bold;text-align: left;">ЗАКАЗЧИК:</p>
            <?php echo str_replace(array("\r\n", "\r", "\n"), "<br />", $row11['bankDetails'])?>
                <p><strong>Директор_________<?php echo $customer_name ?></strong></p>
        </div>
        <div class="rightSide" style="width:49%;float: left;">
            <p style="font-size:12px;font-weight:bold;text-align: right;">ЗАКАЗЧИК/ЭКСПЕДИТОР:</p>
            <p style="font-size:12px;text-align: right;margin:0px;margin-right:3px;">FAHRWEST LOGISTIC SRL</p>
            <p style="font-size:12px;text-align: right;margin:0px;margin-right:3px;">Bd.Renasterii Nationale 6, ap.90</p>
            <p style="font-size:12px;text-align: right;margin:0px;margin-right:3px;">Cod Fiscal - 1022600012316</p>
            <p style="font-size:12px;text-align: right;margin:0px;margin-right:3px;">TVA - 0612266</p>
            <p style="font-size:12px;text-align: right;margin:0px;margin-right:3px;">IBAN MD66AG000000022514703441</p>
            <p style="font-size:12px;text-align: right;margin:0px;margin-right:3px;">AGRNMD2X441</p>
            <p style="font-size:12px;text-align: right;margin:0px;margin-right:3px;">BC “Moldova-Agroindbank” S.A. Sucursala nr.18, Chisinau, SWIFT - AGRNMD2X441</p>
            <p style="font-size:12px;text-align: right;margin:0px;margin-right:3px;margin-top:4px;"><strong>Директор____________Repida D.A.</strong></p>
        </div>
    <div class="clear"></div>
            <br>
         
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
