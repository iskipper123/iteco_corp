<?php
$idItem = $_GET["idItem"];
$hash = md5(microtime());

$file = "get_url.txt";

$fd = fopen($file,"a+");
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
echo "<p>Ссылка для перевозчика</p>";
echo "<a href='http://".$_SERVER['HTTP_HOST']."/form_carrier.php?form_id=".$idItem."&hash=".$hash."' target='_blank'>http://".$_SERVER['HTTP_HOST']."/form_carrier.php?form_id=".$idItem."&hash=".$hash."</a>";

?>