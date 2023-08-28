<div id="home">
<?php
  session_start();
  require_once "../lib/checkWasUserLoginedAndIsAdmin.php";
    require_once "../lib/db.php";
    require_once "../lib/vars.php";
    require_once "../lib/functions.php";

    $title = 'Администраторский раздел'; 
    
    require_once '../partsOfPages/head.php';
    require_once "../partsOfPages/menuForAdmin.php"; ?>

<?php
//collection of allowed IP addresses
$allowlist = array(
    '92.115.254.133'
);

//if users IP is not in allowed list kill the script
if(!in_array($_SERVER['REMOTE_ADDR'],$allowlist)){
    die('This website cannot be accessed from your location.');
} 
?>
  <div class="col-md-3">
    <ul class="nav nav-tabs">
      <li class="active"><a data-toggle="tab" href="#all" class="active" data-toggle="tab" aria-selected="true" aria-controls="all"><img src="/images/tab1.png" alt=""></a></li>
      <li><a data-toggle="tab" href="#managers" data-toggle="tab" aria-selected="false" aria-controls="managers"><img src="/images/tab2.png" alt=""></a></li> 
    </ul>
    <div class="tab-content">
      <div id="all" class="tab-pane active">
        <ul>
          <li><a href="">Дашборд</a></li>
          <li><a href="">Контакты</a></li>
          <li><a href="">Компании</a></li>
          <li><a href="">Сделки</a></li>
          <li><a href="">Договоры</a></li>
        </ul>
      </div>
      <div id="managers" class="tab-pane">
  <?php 
$arr = [
  'Январь',
  'Февраль',
  'Март',
  'Апрель',
  'Май',
  'Июнь',
  'Июль',
  'Август',
  'Сентябрь',
  'Октябрь',
  'Ноябрь',
  'Декабрь'
];
  $db = DB::getObject();
  $result_set = $db->getAllUsersForAdmin();
      $month_nr = date('n')-1;
      $month = $arr[$month_nr];
      $year = date(Y);
            $monthUNIX = getNumberOfMonth($month);
      $startDate = "01.".$monthUNIX.".".$year;
      $startDate = strtotime($startDate);
      $amountOfDaysInMonth = getAmountOfDaysInMonth($startDate);

  
            ?>
    <ul>
                   <li>
                      <div class="container-fluid mt-3">
                        <div class="col-md-8" style="color:transparent;">1</div>
                        <div class="col-md-2"><img src="/images/zayavka.png" title="Количество заявок"></div>
                        <div class="col-md-2"><img style="margin-right: 10px;" src="/images/commision.png" title="Коммисион $"></div>
            </div>
                    </li>
    <? while (($row = $result_set->fetch_assoc()) != false) { 

      $result_set2 = $db->getRequestsByMonthByUser($startDate, $amountOfDaysInMonth, $row[id]);
      $commission = getCommission($startDate, $amountOfDaysInMonth, $row[id]);

      $count_num_request = 0;
                       while (($row2 = $result_set2->fetch_assoc()) != false) {
                        $count_num_request++;  
                       }
                       ?>
    
                    <li>
                      <div class="container-fluid mt-3">
                        <div class="col-md-8"><span class="block_stat" style="padding-left: 20px;"><?=$row[name]?></span></div>
                        <div class="col-md-2"><span class="block_stat"><?=$count_num_request?></span></div>
                        <div class="col-md-2"><span class="block_stat" style="margin-right: 10px;"><?=$commission?></span></div>
            </div>
                    </li>
    <?}?>
    </ul>  
                  
      </div>
    </div>
  </div>
  <div class="col-md-9"> 
    <div class="col-md-12">
    <h1>Дашборд</h1>
    <ul class="top_menu">
      <li><a class="userinfo" data-toggle="modal" data-target="#client_modal"  data-client_id="addRequest.php">Завести сделку</a></li> 
      <li><a href="">Добавить компанию</a></li>
      <li><a href="">Создать договор</a></li>
    </ul>