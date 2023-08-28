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
                    <td style="width:15%">Для предоставления в таможенные органы</td>
                    <td style="width:70%">Справка о транспортных расходах.<?php if ($row11['id'] == '1794') { echo ' К Договору No1 от 09.04.2022';} ?></td>
                    <td style="width:15%"><p>ОТ <?php echo $data ?></p></td>
                </tr>
            </table>
           <br>
           
            <hr> 
            <p>На перевозку груза по маршруту <?php echo $row['address1'].' - '.$row['address2']?> ), автомобиль с регистрационными номером <?php echo $row['carNumber'];?>, водитель <?php echo $row['fio'] ?> для фирмы <?php echo $customer.' '.$row11['company_form']?> составляет ____ Euro.</p>
            <p>От места загрузки до границы Украины – 100 EURO</p>
            <p>От границы Украины до места выгрузки (Кишинёв) – 200 EURO</p>
            <br>
            <br>
            <br>
            <p>Пункт перехода границы: <?php echo $row['pogran']?></p>
            <br><br><br>
            <p><strong>Директор_________Repida D.A</strong></p>
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
