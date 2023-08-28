<?php
$hash = md5(microtime());

$manager_id = $_GET["manager_id"];

$file = "get_url.txt";

$fd = fopen($file,"a");
if(!$fd) {
 exit("Не возможно открыть файл");
}
if(!flock($fd,LOCK_EX)) {
 exit("Блокировка файла не удалась");
}
fwrite($fd,$hash."\n");

if(!flock($fd,LOCK_UN)) {
 exit("Не возможно разблокировать файл");
}
fclose($fd);




$path = substr($_SERVER['PHP_SELF'],0,strrpos($_SERVER['PHP_SELF'],"/"));
echo "<p>Ссылка на запрос данных заказчика</p>";
echo "<a href='http://".$_SERVER['HTTP_HOST'].$path."/form.php?hash=".$hash."&manager_id=".$manager_id."' target='_blank'>http://".$_SERVER['HTTP_HOST'].$path."/form.php?hash=".$hash."&manager_id=".$manager_id."</a>";
?>