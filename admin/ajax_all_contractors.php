<?php       
    session_start();
    require_once "../lib/checkWasUserLoginedAndIsUser.php";
    require_once "../lib/db.php";
    require_once "../lib/vars.php";
    require_once "../lib/functions.php";


    $db = DB::getObject(); 

        if( $_REQUEST["alpha"] )
      {
        $alpha = $_REQUEST['alpha'];
      } else {
        $alpha = '';
      }
    if( $_REQUEST["contr_or_carr"] )
      {
 $contr_or_carr = $_REQUEST['contr_or_carr'];
} 
    
      $user_id = '';



      if ($alpha == '') {
      $result_set = $db->get_contractors_rus_users("contractors", "contractorsType", $contr_or_carr, "isDeleted", 0, "name"); 
      }elseif($alpha == 'without_manager'){
      $result_set = $db->get_contractors_without_manager("contractors", "contractorsType", $contr_or_carr, "isDeleted", 0, "name"); 
      } else {
      $result_set = $db->get_contractors("contractors", "contractorsType", $contr_or_carr, "isDeleted", 0, $alpha, "name"); 
      }

?>

        <? require_once "../lib/allContractors.php"; ?>
  

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