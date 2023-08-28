<?php  
require_once './ApiController.php';
$date = date("Ymd");
$today = NULL;
$request_url = 'https://rci.md/api/loadData/blankListInfo.php';
$output_file = 'requests.csv'; 

getProducts($request_url,$today,$output_file);

?>   
  