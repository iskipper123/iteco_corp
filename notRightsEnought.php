<?php
	if(isset($_GET["goToLoginPage"])) {
		session_start();
		session_destroy();
		header("Location: index.php");
		exit;
    }
    $title = 'Недостаточно прав!';
?> 
<? require_once 'partsOfPages/head.php';?>
<div class="sidenav">
    <div class="login-main-text">
    <p><img src="/images/iteco-logo.png" alt="iteco logo" style="width:90%"/></p>
    </div>
</div>
<div class="main">
   
        <div class="login-form pt-4">
            <p><b>У вас недостаточно прав для просмотра этой страницы. </b></p>
			<p>Вы можете вернуться на страницу входа и войти под другими учетными данными.</p>
			<div class="">
				<a class="download" href="\notRightsEnought.php?goToLoginPage=1">Страница входа</a>
			</div>
        </div>
  
</div>
</body>
</html>