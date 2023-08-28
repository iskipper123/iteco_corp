<?php

	require_once "../lib/db.php";
	require_once "../lib/vars.php"; 
    
    $db = DB::getObject();
	
	$_SESSION["userType"] = 2;
 
?>
        <link rel="stylesheet" href="./editor/app.css" />
        <link rel="stylesheet" href="./editor/build/jodit.min.css" />
        <script src="./editor/build/jodit.js"></script>
<ul class="nav nav-tabs">
      <li class="active"><a data-toggle="tab" href="#info" class="active">Общие</a></li>
      <li><a data-toggle="tab" href="#sdelki">Сделки</a></li> 
      <li><a data-toggle="tab" href="#rastamojka">Растаможка</a></li>
      <li><a data-toggle="tab" href="#oplata">Оплата</a></li>
      <li><a data-toggle="tab" href="#zvonki">Звонки</a></li>
    </ul>
<div class="tab-content">
  <div id="info" class="tab-pane active">
    <div class="col-md-12">
     <?php include "../lib/client_edit.php"; ?>
      <?php include "../lib/client_info.php"; ?>
  </div> 
  </div>
  <div id="sdelki" class="tab-pane">
    <div class="col-md-12">
  <?php include "../lib/client_requests.php"; ?>
  </div> 
  </div>
    <div id="rastamojka" class="tab-pane">
      <div class="col-md-12">
  <?php include "../lib/client_customs.php"; ?>
  </div> 
  </div>

    <div id="oplata" class="tab-pane">
         <div class="col-md-12">
  <?php include "../lib/client_payments.php"; ?>
  </div> 
  </div>
     <div id="zvonki" class="tab-pane">
       <div class="col-md-12">
      <?php include "../lib/client_call.php"; ?>
      </div> 
  </div>
</div>
    <script>
            new Jodit.make('#bankDetailsInput' ,{
                uploader: {
                    url: 'https://xdsoft.net/jodit/connector/index.php?action=fileUpload'
                },
                toolbarButtonSize: "large",
                
                filebrowser: {
                    ajax: {
                        url: 'https://xdsoft.net/jodit/connector/index.php'
                    }
                }
            });

        </script>
