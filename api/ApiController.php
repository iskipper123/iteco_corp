<?php

function getProducts($request_url,$today,$output_file)
{
 

$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL,$request_url);
$results=curl_exec($ch);
curl_close($ch); 
$fp = fopen($output_file, 'w');

fputs($fp, $bom = chr(0xEF) . chr(0xBB) . chr(0xBF) );

fwrite($fp,'date_create;idBlank;blankN;nameFirm;idFirm;nameTrans;idTrans;adressFrom;adressTo;carNumber;priceFirm;priceTransport;currency;valutaryPay;idRci;rciFirm;lang;current_currency;vama;daysFirm;daysTransport;current_vama;akt;pdf;' . "\r\n");

$data = json_decode($results);

$header = true;

foreach ($data as $value) {

   
print_r($value);


             $values = array( 
              "date_create" => $value->dateCreating,
              "datePogruz" => $value->datePogruz, 
              "idBlank" => $value->idBlank, 
              "blankN" => $value->blankN, 
              "nameFirm" => $value->nameFirm, 
              "idFirm" => $value->idFirm, 
              "nameTrans" => $value->nameTrans, 
              "idTrans" => $value->idTrans, 
              "adressFrom" => $value->adressFrom, 
              "adressTo" => $value->adressTo, 
              "carNumber" => $value->carNumber, 
              "priceFirm" => $value->priceFirm, 
              "priceTransport" => $value->priceTransport, 
              "currency" => $value->currency, 
              "valutaryPay" => $value->valutaryPay, 
              "idRci" => $value->idRci, 
              "rciFirm" => $value->rciFirm, 
              "lang" => $value->lang, 
              "current_currency" => $value->current_currency, 
              "vama" => $value->vama, 
              "daysFirm" => implode(',',$value->daysFirm), 
              "daysTransport" => implode(',',$value->daysTransport), 
              "current_vama" => $value->current_vama, 
              "akt" => $value->akt, 
              "pdf" => $value->pdf
            ); 

                fputs($fp, implode(";", $values).";\r\n");      
        }


fclose($fp);


}







