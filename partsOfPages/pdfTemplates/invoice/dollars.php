<?php 
if ($date_of_clearence == '01.01.1970') {
   $date_of_clearence = date("d.m.Y");
}
        $currency = simplexml_load_file("https://www.bnm.md/ro/official_exchange_rates?get_xml=1&date=".$date_of_clearence);
        $numb=0;
        $EUR=0;
        $USD=0;
        $RUB=0; 
        foreach ($currency->Valute as $curr) {
            $numb+=1; 
            if($numb == 1){
                $EUR = (float)$curr->Value;
            }elseif($numb == 2){
                $USD = (float)$curr->Value;
            }elseif($numb == 3){
                $RUB = (float)$curr->Value;
            }
           if($numb == 3){break;} 
        }


        /**
 * Возвращает сумму прописью
 * @author runcore
 * @uses morph(...)
 */
function num2str($num)
{
    $nul = 'zero';
    $ten = array(
        array('', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eigth', 'nine'),
        array('', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eigth', 'nine')
    );
    $a20 = array('ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen');
    $tens = array(2 => 'twenty', 'thirty', 'fourty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety');
    $hundred = array('', 'one hundred', 'two hundred', 'three hundred', 'four hundred', 'five hundred', 'six hundred', 'seven hundred', 'eigth hundred', 'nine hundred');
    $unit = array(
        array('' , '',   '',     1),
        array('dollar USA',    'dollar USA',     'dollar USA',     0),
        array('thousand',   'thousand',    'thousand',      1),
        array('million',  'million',  'million',  0),
        array('billion', 'billion', 'billion', 0),
    );
 
    list($rub, $kop) = explode('.', sprintf("%015.2f", floatval($num)));
    $out = array();
    if (intval($rub) > 0) {
        foreach (str_split($rub, 3) as $uk => $v) {
            if (!intval($v)) continue;
            $uk = sizeof($unit) - $uk - 1;
            $gender = $unit[$uk][3];
            list($i1, $i2, $i3) = array_map('intval', str_split($v, 1));
            // mega-logic
            $out[] = $hundred[$i1]; // 1xx-9xx
            if ($i2 > 1) $out[] = $tens[$i2] . ' ' . $ten[$gender][$i3]; // 20-99
            else $out[] = $i2 > 0 ? $a20[$i3] : $ten[$gender][$i3]; // 10-19 | 1-9
            // units without rub & kop
            if ($uk > 1) $out[] = morph($v, $unit[$uk][0], $unit[$uk][1], $unit[$uk][2]);
        }
    } else {
        $out[] = $nul;
    }
    $out[] = morph(intval($rub), $unit[1][0], $unit[1][1], $unit[1][2]); // rub
    //$out[] = $kop . ' ' . morph($kop, $unit[0][0], $unit[0][1], $unit[0][2]); // kop
    return trim(preg_replace('/ {2,}/', ' ', join(' ', $out)));
}

function num2str_rus($num)
{
    $nul = 'ноль';
    $ten = array(
        array('', 'один', 'два', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять'),
        array('', 'одна', 'две', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять')
    );
    $a20 = array('десять', 'одиннадцать', 'двенадцать', 'тринадцать', 'четырнадцать', 'пятнадцать', 'шестнадцать', 'семнадцать', 'восемнадцать', 'девятнадцать');
    $tens = array(2 => 'двадцать', 'тридцать', 'сорок', 'пятьдесят', 'шестьдесят', 'семьдесят', 'восемьдесят', 'девяносто');
    $hundred = array('', 'сто', 'двести', 'триста', 'четыреста', 'пятьсот', 'шестьсот', 'семьсот', 'восемьсот', 'девятьсот');
    $unit = array(
        array('' , '',   '',     1),
        array('долларов США',    'долларов США',     'долларов США',     0),
        array('тысяча',   'тысячи',    'тысяч',      1),
        array('миллион',  'миллиона',  'миллионов',  0),
        array('миллиард', 'миллиарда', 'миллиардов', 0),
    );
 
    list($rub, $kop) = explode('.', sprintf("%015.2f", floatval($num)));
    $out = array();
    if (intval($rub) > 0) {
        foreach (str_split($rub, 3) as $uk => $v) {
            if (!intval($v)) continue;
            $uk = sizeof($unit) - $uk - 1;
            $gender = $unit[$uk][3];
            list($i1, $i2, $i3) = array_map('intval', str_split($v, 1));
            // mega-logic
            $out[] = $hundred[$i1]; // 1xx-9xx
            if ($i2 > 1) $out[] = $tens[$i2] . ' ' . $ten[$gender][$i3]; // 20-99
            else $out[] = $i2 > 0 ? $a20[$i3] : $ten[$gender][$i3]; // 10-19 | 1-9
            // units without rub & kop
            if ($uk > 1) $out[] = morph($v, $unit[$uk][0], $unit[$uk][1], $unit[$uk][2]);
        }
    } else {
        $out[] = $nul;
    }
    $out[] = morph(intval($rub), $unit[1][0], $unit[1][1], $unit[1][2]); // rub
    //$out[] = $kop . ' ' . morph($kop, $unit[0][0], $unit[0][1], $unit[0][2]); // kop
    return trim(preg_replace('/ {2,}/', ' ', join(' ', $out)));
}

 
/**
 * Склоняем словоформу
 * @author runcore
 */
function morph($n, $f1, $f2, $f5) 
{
    $n = abs(intval($n)) % 100;
    if ($n > 10 && $n < 20) return $f5;
    $n = $n % 10;
    if ($n > 1 && $n < 5) return $f2;
    if ($n == 1) return $f1;
    return $f5;
}
 

        ?>
<?php
if ($row['currency'] == "EURO") {
    $currency = $EUR;
    $dif_currency =  bcdiv($EUR, 1, 4)/bcdiv($USD, 1, 4);

} elseif($row['currency'] == "USD"){
    $currency = $USD;
    $dif_currency = 1;
} else {
    $currency = 1;
    $dif_currency = 1/$USD;
}
 $euro_sign = " MDL";
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
                    <td style="width:15%">No. <?php echo $row['number']?></td>
                    <td style="width:70%">Invoice No.<?php echo $row['number']?> dd <?php if ($row11['id'] == '1794') { echo ' К Договору No1 от 09.04.2022';} ?></td>
                    <td style="width:15%"><p>dd <?php echo date('d.m.Y'); ?></p></td>
                </tr>
            </table>
           <br>

            <table class="colored">
                <tr>
                    <td class="gray"><p>Beneficiary customer:</p><p>Получатель:</p></td>
                    <td class="green"><p>SRL "FAHRWEST LOGISTIC"</p><p>Str.Alecu Russo 15, et 3, of.21</p><p>Mun. Chisinau, MD-2068, Republica Moldova</p></td>
                </tr>
                <tr>
                    <td class="gray"><p>Account with institution:</p><p>Банковские реквизиты:</p></td>
                    <td class="green">
                        <p>BENEFICIARY’S BANK:</p>
                        <p>BC “Moldova-Agroindbank” S.A. Sucursala nr.18, Chisinau</p>
                        <p>Bd.Renasterii Nationale 6, ap.90, Chisinau, Moldova</p>
                        <p>SWIFT CODE:AGRNMD2X441</p>
                        <p>TVA:0612266</p>
                        <p>IBAN:MD82AG000000022514703444</p>
                        <p>BENEFICIARY’S NAME:SRL "FAHRWEST LOGISTIC"</p> 
                    </td>
                </tr>                
                <tr>
                    <td class="gray">
                        <p>Correspondent bank:</p>
                        <p>Банк – корреспондент:</p>
                    </td>
                    <td class="green">
                        <p>THE BANK OF NEW YORK MELLON.,</p>
                        <p>NEW YORK, USA</p>
                        <p>SWIFT CODE: IRVTUS3N</p>
                        <p>CORR.ACC. 8900720743</p>
                </td>
                </tr>
                 <tr>
                    <td class="gray">
                        <p>Payer:</p>
                        <p>Плательщик:</p>
                    </td>
                    <td class="green">
                        <p><?php echo str_replace(array("\r\n", "\r", "\n"), "<br />", $row11['bankDetails'])?></p>
                    </td>
                </tr>
                <tr>
                    <td class="gray">
                        <p>Ref No</p>
                    </td>
                    <td class="green">
                        <p>No.<?php echo $row['number']?> to the contract from <?php echo date('d.m.Y'); ?></p>
                    </td>
                </tr>

            </table>

            <table cellspacing="2">
                <tr>
                 <th style="text-align: left;">
                    <p>Date of loading</p>
                 </th>
                 <th style="text-align: center;">
                    <p>Route</p>
                </th>
                 <th style="text-align: center;">
                    <p>Trock/trailer</p>
                </th>
                 <th style="text-align: center;">
                    <p>SUM</p>
                 </th>
                </tr>

                <tr>
                    <td style="text-align: left;"><?php echo $dateShipping?></td>
                    <td style="text-align: center;"><?php echo $row["from"]?> - <?php echo $row["to"]?> (<?php echo $row['route'];?>)</td>
                    <td style="text-align: center;"><?php echo $row['carNumber']?></td>
                    <td style="text-align: center;"><?php echo round($row['customerPrice']*$dif_currency) ?></td>
                </tr>
            </table>
             <p style="font-size:12pt;font-weight:bold;text-align: right;">Amount: <?php echo round($row['customerPrice']*$dif_currency) ?></p>
             <p style="font-size:12pt;font-weight:bold;text-align: left;">For pay: <?php echo round($row['customerPrice']*$dif_currency) ?> (<?php echo num2str(round($row['customerPrice']*$dif_currency)) ?>)</p>
        <div class="clear"></div>
          <p></p>
            <p style="font-size:11pt;text-align: left;margin:0px;margin-left:3px;margin-top:4px;">Director_________________________________________________Repida D.A.</p>
        </div>

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
