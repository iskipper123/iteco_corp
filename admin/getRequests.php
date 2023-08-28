<?php

session_start();
	require_once "../lib/checkWasUserLoginedAndIsAdmin.php";
	require_once "../lib/db.php";
	require_once "../lib/functions.php";
    require_once "../lib/vars.php";
    
    $title = 'Прием заявок';
	
	$_SESSION["userType"] = 1;
  require_once '../dashbord/dashbord.php';?> 
<h1>Дашборд</h1>

<?php	require_once "../lib/getRequests.php"; ?>
<?  require_once '../partsOfPages/footer.php';?>

</div>
<?  require_once '../dashbord/dashbord_footer.php';?> 