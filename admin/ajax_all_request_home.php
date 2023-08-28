<?php       
    require_once "../lib/db.php";
    require_once "../lib/vars.php";
    require_once "../lib/functions.php";


    $db = DB::getObject(); 

if ($_REQUEST["alpha"] != '') {
  $alpha = $_REQUEST["alpha"];
} else {
  $alpha = '';
}
if ($_REQUEST["beta"] != '') {
$beta = $_REQUEST["beta"];
} else {
  $beta = '';
}

/*      if($idUser == "Выберите сотрудника")  {
            if( $_REQUEST["alpha"] == 'activ'){
                $result_set = $db->getRequestsByMonth_activ($todayUnix);
              } elseif( $_REQUEST["alpha"] == 'end'){
                $result_set = $db->getRequestsByMonth_end($todayUnix, $minusWeek);
              } elseif( $_REQUEST["alpha"] == 'arhive'){ 
                $result_set = $db->getRequestsByMonth_end($minusWeek, $minusMonth);
              } else {
                $result_set = $db->getRequestsByMonth($startDate, $amountOfDaysInMonth);
              }
      } else { 
        $result_set = $db->getRequestsByMonth($startDate, $amountOfDaysInMonth);
      }*/


      $result_set = $db->getRequestsHome($alpha, $beta);
?>
          
        <? require_once "../lib/allRequests.php"; ?>
  

<script>
    $(document).ready(function(){
   $('.userinfo').click(function(){
   var client_id = $(this).data('client_id');
   $.ajax({
    type: 'GET',
    url: 'client_info.php?id='+client_id,
    success: function(response){ 
      $('.modal-body').html(response); 
    }
  });
 });
   
});

  $('.close, #mask').on('click touchend', function(){
   $('.modal-header ul li a').removeClass('active');
   $('.modal-header ul li:first-child a').addClass('active');
  });

    $('.interactiveTable tr').click(function() {
      $('.interactiveTable tr').removeClass('active');
      $(this).addClass('active');
      });

         $('.interactiveTable tr').click(function() {
      $('.interactiveTable tr').removeClass('active');
      $(this).addClass('active');
      });


</script>