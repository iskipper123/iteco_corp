<ul class="nav nav-pills mb-3">
    <li class="nav-item">
        <a class="nav-link btn btn-primary m-1 <?=strpos(basename($_SERVER["SCRIPT_FILENAME"], '.php'),'requests')!==false?'active':''?>" href="requests.php">Все заявки</a>
    </li>
    <li class="nav-item">
        <a class="nav-link btn btn-primary m-1 <?=strpos(basename($_SERVER["SCRIPT_FILENAME"], '.php'),'myRequests')!==false?'active':''?>" href="myRequests.php">Мои заявки</a>
    </li>
</ul>