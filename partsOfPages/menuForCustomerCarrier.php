<ul class="nav nav-pills mb-3">
    <li class="nav-item">
        <a class="nav-link btn btn-primary m-1 <?=strpos(strtolower(basename($_SERVER["SCRIPT_FILENAME"], '.php')),'paymentscustomer')!==false?'active':''?>" href="paymentsCustomers.php">Заказчики</a>
    </li>
    <li class="nav-item">
        <a class="nav-link btn btn-primary m-1 <?=strpos(strtolower(basename($_SERVER["SCRIPT_FILENAME"], '.php')),'paymentscarriers')!==false?'active':''?>" href="paymentsCarriers.php">Перевозчики</a>
    </li>
</ul>