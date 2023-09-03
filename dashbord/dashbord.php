<div id="home">

<?php
session_start();
  ob_start(); 


 if ($_SESSION["role"] != 1) {
   require_once "../lib/checkWasUserLoginedAndIsUser.php";
   $idManager = $_SESSION["id"];
 } else {
  require_once "../lib/checkWasUserLoginedAndIsAdmin.php";
  $idManager = $_SESSION["id"];
 }



    require_once "../lib/db.php";
    require_once "../lib/vars.php";
    require_once "../lib/functions.php";

    $title = 'Администраторский раздел'; 
    
    require_once '../partsOfPages/head.php';
    require_once "../partsOfPages/menuForAdmin.php";


            $user = DB::getObject();
            $manager_variant = $user->getvariant_manager($idManager);
            $_SESSION["manager_variant"] = $manager_variant;
            setcookie("manager_variant", $_POST["manager_variant"], time() + (86400 * 30)); 

   ?>
    <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.min.js"></script>
<script src="https://unpkg.com/tippy.js@6/dist/tippy-bundle.umd.js"></script>
<?php
//collection of allowed IP addresses
/*$allowlist = array(
    '92.115.254.133'
);*/
$activePage = basename($_SERVER['PHP_SELF'], ".php");
//if users IP is not in allowed list kill the script
/*if(!in_array($_SERVER['REMOTE_ADDR'],$allowlist)){
    die('This website cannot be accessed from your location.');
} */
?> 
  <div class="col-md-2 dashbord_space_left closed" id="left_dash">
               <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php if ($_SESSION["role"] != 1) {echo '/users/home.php';} else {echo '/admin/home.php';} ?>" >
                <div class="sidebar-brand-text pt-1"><img src="/images/logo_m.png" alt="iteco logo" id="logoImg" style="    max-width: 90%;  margin: 0px auto 30px auto; display: block;"/></div>
              </a>
    <ul class="nav nav-tabs">
      <li class="active"><a data-toggle="tab" href="#all" class="active" data-toggle="tab" aria-selected="true" aria-controls="all"><img src="/images/tab1.png" alt=""></a></li>
      <li><a data-toggle="tab" href="#managers" data-toggle="tab" aria-selected="false" aria-controls="managers"><img src="/images/tab2.png" alt=""></a></li> 
    </ul>
    <div class="tab-content">
      <div id="all" class="tab-pane active">
        <ul>
          <li><a class="<?= ($activePage == 'home') ? 'active':''; ?>" href="home.php"  data-tippy-content="Дашборд"><span>Дашборд</a></li>
          <?php if ($manager_variant == 'all' || $manager_variant == 'cargo') { ?>
          <li><a class="<?= ($activePage == 'dash_contacts' || $activePage == 'clientsByManager') ? 'active':''; ?>" href="dash_contacts.php" data-tippy-content="Контакты"><span>Контакты</span></a></li>
          <?php } ?>
          <?php if ($manager_variant == 'all') { ?>
          <li><a class="<?= ($activePage == 'customers' || $activePage == 'carriers') ? 'active':''; ?>" href="customers.php" ><span>Компании</span></a>
            <ul style="display: none;">
              <li style="margin-bottom:9px;"><a href="customers.php">Заказчики</a></li>
              <li><a href="carriers.php">Перевозчики</a></li>
            </ul></li> 
          <?php } elseif($manager_variant == 'cargo'){?>
          <li><a class="<?= ($activePage == 'customers' || $activePage == 'carriers') ? 'active':''; ?>" href="customers.php" ><span>Компании</span></a></li> 
          <?php } elseif($manager_variant == 'transport'){?>
          <li><a class="<?= ($activePage == 'customers' || $activePage == 'carriers') ? 'active':''; ?>" href="carriers.php" ><span>Компании</span></a></li> 
          <?php } ?>


 
             <? if($_SESSION["role"] == 1) {?> 
          <li><a class="<?= ($activePage == 'requests') ? 'active':''; ?>" href="requests.php" data-tippy-content="Сделки"><span>Сделки</span></a></li>
           <? } else { ?> 
             <li><a class="<?= ($activePage == 'myRequests') ? 'active':''; ?>" href="myRequests.php" data-tippy-content="Сделки"><span>Сделки</span></a></li>
             <? } ?> 
          <li><a class="<?= ($activePage == 'paymentsCustomers' || $activePage == 'paymentsCarriers') ? 'active':''; ?>" href="paymentsCustomers.php" ><span>Договоры</span></a>
            <ul style="display: none;">
              <li style="margin-bottom:9px;"><a href="paymentsCustomers.php">Заказчики</a></li>
              <li><a href="paymentsCarriers.php">Перевозчики</a></li>
            </ul></li> 

          <li><a class="<?= ($activePage == 'blacklist') ? 'active':''; ?>" href="blacklist.php" data-tippy-content="Черный список"><span>Черный список</span></a></li>
          <li><a class="<?= ($activePage == 'customsClearance') ? 'active':''; ?>" href="customsClearance.php" data-tippy-content="Расстаможка"><span>Расстаможка</span></a></li>


         <li><a class="<?= ($activePage == 'getRequests') ? 'active':''; ?>" href="getRequests.php" data-tippy-content="Прием заявок"><span>Прием заявок</span></a></li>
                   <? if($_SESSION["role"] == 1) {?> 
		       <li class="nav-item">
		                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo" >
		                    <span>Пользователи</span>
		                </a>
		            <ul style="display: none;">
                  <li style="margin-bottom:9px;"> <a  href="usersGroup.php">Отделы</a></li>
		              <li style="margin-bottom:9px;"> <a  href="admins.php">Админы</a></li>
		              <li><a href="users.php" >Менеджеры</a></li>
		            </ul></li> 
       
        <? } ?>


         <li><a class="<?= ($activePage == 'customsClearance') ? 'active':''; ?>" href="formRequests.php" data-tippy-content="Список запросов"><span>Список запросов</span></a></li>
        <li><a class="<?= ($activePage == 'salary' || $activePage == 'salaryByManager') ? 'active':''; ?>" href="salary.php" data-tippy-content="Зарплата"><span>Зарплата</span></a></li>
        <? if($_SESSION["role"] != 1) {?> 
         <li><a class="<?= ($activePage == 'usersGroup') ? 'active':''; ?>" href="usersGroup.php" data-tippy-content="Отделы"><span>Отделы</span></a></li>
       <?php } ?>
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
      $year = date("Y");
            $monthUNIX = getNumberOfMonth($month);
      $startDate = "01.".$monthUNIX.".".$year;
      $startDate = strtotime($startDate);
      $amountOfDaysInMonth = getAmountOfDaysInMonth($startDate);

            ?>
    <ul>
                   <li>
                      <div class="container-fluid mt-3">
                        <div class="col-md-8" style="color:transparent;">1</div>
                        <div class="col-md-4"><img src="/images/zayavka.png" title="Количество заявок"></div>
            </div>
                    </li>
    <? while (($row = $result_set->fetch_assoc()) != false) { 

      $result_set2 = $db->getRequestsByMonthByUser($startDate, $amountOfDaysInMonth, $row['id']);
      $commission = getCommission($startDate, $amountOfDaysInMonth, $row['id']);

      $count_num_request = 0;
                       while (($row2 = $result_set2->fetch_assoc()) != false) {
                        $count_num_request++;  
                       }
                       ?>
     <?php if ($row['name'] != 'Александр' && $row['name'] != 'Юлия' && $row['name'] != 'Арчил' && $row['manager_variant'] != 'transport') { ?>
                    <li>
                      <div class="container-fluid mt-3">
                       
                        <div class="col-md-8"><span class="block_stat" style="padding-left: 20px;"><?=$row['name']?></span></div>
                        <div class="col-md-4"><span class="block_stat"><?=$count_num_request?></span></div>

            </div>
                    </li>
                     <?php } ?>
    <?}?>
    </ul>  
                  
      </div>
    </div>
  </div>
  <div class="col-md-10 dashbord_space closed" id="right_dash">
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-0 static-top shadow">
                     <div id="menu_icon" onclick="close_menu();">
            <div class="line"></div>
            <div class="line"></div> 
            <div class="line"></div>
        </div>
        <div id="right_bar" style="margin-left:auto;">
            <ul class="top_menu">
<li>
                <? //if($_SESSION["role"] == 1) {?> 
                <form id="check_client" name="" action="" method="post">
                 <input class="form-control<?=isset($error_name)&&$error_name!=''?' is-invalid':''?>" type="text" name="nameCheck" id="nameCheck" value="">
                  <input class="btn btn-secondary btn-sm" onclick="check_client()"  name="add" value="проверить" style="cursor:pointer;">
                  <span class="status" style="position: absolute;left: 42px;top: 49px;"></span>                
                </form>
                    <script>
function check_client(){
     $.ajax({
        type: "POST",
        url: "check.php",
        data: $('#check_client').serialize(),
            success: function(data) {
                if (data != true) {
                   $('.status').html('<span style="color:red; text-align:center;">'+data+'</p>');
                } else {
                     $('.status').html('<span style="color:green; text-align:center;">Клиент успешно добавлен!</p>');

  $('#check_client')[0].reset();

                    setTimeout(
                  function() 
                  {
                     $('.status').hide();
                  }, 500);  
                    
                }
            }
    }); 
    }
</script>

              <? //} ?>
</li>
<? //if($_SESSION["role"] == 1) {?> 
  <button class="add-request-btn1"><a style="color: #fff;" href="/get_urle9804678248fda5be215f404bc.php?manager_id=<?php echo $_SESSION["id"] ?>" target="_blank">Запрос данных<img src="/images/plus.png" style="padding-left: 10px;" alt=""></a></button>  
    <?php //} ?>
   
    <button class="add-request-btn"> Сделка<img src="/images/plus.png" style="padding-left: 10px;" alt=""></button>
    <div id="popup" class="popup">
        <div class="popup-content">
            <span class="close-popup">&times;</span>
            <iframe id="form-iframe" src="addRequest.php" style="width: 800px; height: 700px;"></iframe>
        </div>
    </div>    
    <button class="add-request-btn1"><a class="addrequest" style="color: #fff;" data-toggle="modal" data-target="#client_modal"  data-backdrop="static" data-keyboard="false" data-client_id="addCustomer.php">Компания<img src="/images/plus.png" style="padding-left: 10px;" alt=""></a>
</button>  
      
    </ul>
            <?php require_once "../partsOfPages/welcome.php"; ?>
            </div>
        </nav>
        <style>
.add-request-btn {
  background-color: #4DA598;
  color: #fff;
  border: none;
  padding: 7px 15px;
  cursor: pointer;
  border-radius: 10%;
}

.add-request-btn1 {
  background-color: #4DA598;
  color: #fff;
  border: none;
  padding: 7px 15px;
  cursor: pointer;
  border-radius: 10%;
}
.add-request-btn:hover {
  background-color: #4DA598;
  color: #fff;
  border: none;
  padding: 7px 15px;
  cursor: pointer;
  border-radius: 10%;
}

.popup {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
}

.popup-content {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background-color: #fff;
  padding: 20px;
  border-radius: 5px;
  box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.2);
}

.close-popup {
  position: absolute;
  top: 10px;
  right: 10px;
  font-size: 20px;
  cursor: pointer;
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const addRequestBtn = document.querySelector(".add-request-btn");
    const popup = document.getElementById("popup");
    const closePopup = document.querySelector(".close-popup");

    addRequestBtn.addEventListener("click", function () {
        popup.style.display = "block";
    });

    closePopup.addEventListener("click", function () {
        popup.style.display = "none";
    });
});

$(document).ready(function(){
    if (!$.cookie('closed')) {
       $.cookie('closed', 'yes', {expires: 7 });
    }
    if ($.cookie('closed') == 'no') {
      $("#logoImg").attr("src","/images/iteco-logo.png"); 
      $("#right_dash").removeClass("closed");
      $("#left_dash").removeClass("closed");
         } 
      if ($.cookie('closed') == 'yes') {
       $("#logoImg").attr("src","/images/logo_m.png"); 
      $("#right_dash").addClass("closed");
      $("#left_dash").addClass("closed");
     }

   }); 


  function close_menu() {
   $("#right_dash").toggleClass("closed");
   $("#left_dash").toggleClass("closed");

   if ($("#right_dash").hasClass('closed')) {
     $("#logoImg").attr("src","/images/logo_m.png"); 
       $.cookie('closed', 'yes', {expires: 7 });
   } else {
     $("#logoImg").attr("src","/images/iteco-logo.png"); 
      $.cookie('closed', 'no', {expires: 7 });
   }
}


tippy('[data-tippy-content]', {
  placement: 'right',
});

</script>


    <script defer>
        function autocompleteTag3(){
            var ajax = new XMLHttpRequest();
            ajax.open("GET", "../lib/autocomplete2.php", true);
            ajax.onload = function () {
                var list = JSON.parse(ajax.responseText);
                new Awesomplete(document.querySelector("#nameCheck"), {minChars: 1, list: list}); 
            };
            ajax.send();
        }
        document.getElementById('nameCheck').addEventListener("awesomplete-select", function(event) {
            let extra = event.text.extraData;
        });
    $(function(){
            autocompleteTag3();
        });
  </script>