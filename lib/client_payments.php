<?php
    
    //require_once "../lib/checkWasUserLoginedAndIsUser.php";
    require_once "../lib/db.php";
    require_once "../lib/vars.php";
    require_once "../lib/functions.php"; 
   
       if(isset($_GET["id"])) {
         $id = $_GET["id"];
        }

        $db = DB::getObject();
        $showTable = true; 
    
        $today = date("Y-m-d");
        $startDayForCurrentMonth = getStartDay($today);
        $todayUnix = strtotime($today);
        $amountOfDaysInMonth = getAmountOfDaysInMonth($todayUnix);
       // $result_set = $db->getPaymentsByMonth($startDayForCurrentMonth, $amountOfDaysInMonth, $arrayOfContractorsTypes[0]);
        $result_set = $db->getPaymentsByMonth_new($arrayOfContractorsTypes[0]);
    ?>


<form name="" action="" method="post">
    <div class="tableWrapper">

           <div class="head col-md-12 mr-12">
            <div class="col-md-2"><?=$_SESSION["contractorType"]?></div>
            <div class="col-md-2">Номер заявки</div>
            <div class="col-md-2">Количество дней на расчет</div>
            <div class="col-md-2">Дата получения документов</div>
            <div class="col-md-2">Дата расчета</div>
            <div class="col-md-2">Сделана ли уже оплата</div>
           </div>

            <?
                $result_set1 = $db->getRowWhere("contractors", "id", $id);
                $row1 = $result_set1->fetch_assoc();

                            $result_set = $db->getRowWhere("payments", "customer", $id);
                         
            ?>
             <? while (($row = $result_set->fetch_assoc()) != false) { 

                $row["date"] == 0 ? $date = "" : $date = date("d.m.Y", $row["date"]);
                $row["dateEnd"] == 0 ? $dateEnd = "" : $dateEnd = date("d.m.Y", $row["dateEnd"]);
                
                $row["paymentWasDidAlreary"] == 0 ? $paymentWasDidAlreary = "-" : $paymentWasDidAlreary = "+";
                $rowColor = "";
                $restOfTheDays = restOfTheDays($row["date"]);
                if( $paymentWasDidAlreary == "+") $rowColor = "rowGreen";
                else if($restOfTheDays == 0||$restOfTheDays < 0) $rowColor = "rowRed";
                ?>
             <div class="history col-md-12 mr-12">
                <input type="hidden" name="idItem" value="<?=$row[id]?>">
                <div class="col-md-2"><?php if ($row[id] != '') { ?><?=$row1[name].' '.$row1[company_form]?><?php } ?></div>
                <div class="col-md-2"><?=$row[number]?></div>
                <div class="col-md-2"><?=$row[days]?></div>
                <div class="col-md-2"><?=$date?></div>
                <div class="col-md-2"><?=$dateEnd?></div>
                <div class="col-md-2"><?=$paymentWasDidAlreary?></div>
             </div>
             <?}?> 

    </div>
</form>