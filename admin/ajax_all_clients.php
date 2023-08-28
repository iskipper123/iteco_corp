<?php
    require_once "../lib/db.php";
    require_once "../lib/vars.php";
    require_once "../lib/functions.php";

    $db = DB::getObject(); 
    if(isset($_GET["idManager"])) {
        $idManager = $_GET["idManager"];
    }

    $date = new DateTime();
    $date1 = $date->setTime(00, 00, 00);
 $current_date = $date1->getTimestamp();


 $current_date_up = date("d.m.Y");
    if(isset($_POST['curid'])){ 
        $rowColor = ''; 
        $curid = $_POST['curid'];
        $curval = strtotime($_POST['val']);
        $updated_date = date("d.m.Y");
        $db->editNextCall($curid,$curval,$updated_date);
        $restOfTheDays = restOfTheDays($curval);
        if($restOfTheDays > 0) $rowColor = "rowGreen";
        else if($restOfTheDays == 0) $rowColor = "rowRed";
        else if($restOfTheDays < 0) $rowColor = "rowYellow";
        echo json_encode(['color'=>$rowColor,'days'=>$restOfTheDays]);
        die();
    }
    if(isset($_POST['curid2'])){ 
        $rowColor = ''; 
        $curid = $_POST['curid2'];
        $curval = $_POST['curval2'];
        $db->editMark($curid,$curval);
        die();
    }

    if(isset($_GET["idManager"])) {
        $idManager = $_GET["idManager"];
    }
        
    if(isset($_POST["editMarkedItem"])) {
        $idItem = $_POST["idItem"];

        if(!isset($idItem))
            $error_delete = "Не был выбран ни один клиент";
        else {
            header("Location: editClient.php?edit=$idItem");
            exit;
        }
    }
    if(isset($_POST["deleteMarkedItems"])) {
        $idItem = $_POST["deleteMarkedItems"];
        if(!isset($idItem))
            $error_delete = "Не был выбран ни один клиент";
        else
            $addConfirmButton = true;
    }
    if(isset($_POST["comfirmDelete"])) {
        $idItem = $_POST["idItem"];
        $db->softDelete("clients", $idItem);
        header("Location: clients.php?success");
        exit;
    }
    
    if(isset($_POST["search"])) {
        $name = $_POST["name"];
        
        if(!isset($name))
            $error_name = "Не заполнено поле";
        else {
            $result_set = $db->searchClients($idManager, $name);
        }
    }
    if( $_REQUEST["alpha"] )
      {
        $alpha = $_REQUEST['alpha'];
      } else {
        $alpha = '';
      }
    if( $_REQUEST["filter"] )
          {
     $filter = $_REQUEST['filter'];
    } else {
     $filter = 'all';
    }

if ($filter == 'all') {
   if ($alpha == '') {
    $result_set = $db->get_contractors_rus_users("contractors", "idManager", $idManager, "isDeleted", 0, "date DESC");
   } else {
   $result_set = $db->get_clients("contractors", "idManager", $idManager, "isDeleted", 0, $alpha, "date DESC");
    }
 }
 if ($filter == 'injob') {
   $result_set = $db->get_clients_injob("contractors", "idManager", $idManager, "isDeleted", 0, "date DESC");
  } 
  if ($filter == 'profit') {
   $result_set = $db->get_clients_profit("contractors", "idManager", $idManager, "isDeleted", 0, $current_date_up, "date DESC");
  } 
  if ($filter == 'arhive') {
   $result_set = $db->get_clients_arhive("contractors", "idManager", $idManager, "isDeleted", 0, $current_date, "date DESC");
  } 

  if ($filter == 'Зима' || $filter == 'Весна' || $filter == 'Лето' || $filter == 'Осень' || $filter == 'Все') {
   $result_set = $db->get_clients_season("contractors", "idManager", $idManager, "isDeleted", 0, $filter, "date DESC");
  } 
?>
 
<div id="work">
    <script src="/js/datepicker.min.js"></script>
    <? if(isset($_GET["success"])) {?> 
        <div class="col-md-4">
            <div class="alert alert-success" role="alert">
                Данные успешно сохранены!
            </div> 
        </div> 
    <?}?>
    <div class="col-md-12">
        <? if($addConfirmButton) {?>
            <form name="" action="" method="post">
            <div class="card col-md-5">
                <div class="card-body">
                    <h5 class="card-title">Вы действительно хотите удалить данного клиента?</h5>
                    <h6 class="card-subtitle mb-2 text-muted">
                    <?
                    $result_set4 = $db->getRowWhere("clients", "id", $idItem);
                    $row4 = $result_set4->fetch_assoc(); ?>
                    <input type="hidden" name="idItem" value="<?=$idItem?>">
                    <? echo $row4[name];
                ?> </h6>
                    <input class="btn btn-danger btn-sm" type="submit" name="comfirmDelete" value="Удалить">
                    <input class="btn btn-secondary btn-sm" type="submit" name="cancelDelete" value="Отменить">
                </div>
            </div>
            </form>
        <?}?>   
        <hr>
        <div id="clients">
          
        <? require_once "../lib/allClients.php"; ?>
        </div>
         </div>
         
<script>
  $(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
    $(document).ready(function(){
   $('.addrequest').click(function(){
  var client_id = $(this).data('client_id');
   $.ajax({
    type: 'GET',
    url: client_id,
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

$(window).scroll(function() {    
    var scroll = $(window).scrollTop();
    if (scroll >= 400) {
        $("#buttons_request").addClass("static_buttons");
        $(".req_form").addClass("static");
    } else {
        $("#buttons_request").removeClass("static_buttons");
        $(".req_form").removeClass("static");
    }
});
</script>

<script> 
    $('.dp-holder').on('blur',function(){
        let element = $(this);
        let curval = $(this).data('curval');
        let val = $(this).val();
        let curid = $(this).data('curid');
        let today = new Date();
        let date = today.getFullYear()+'.'+(today.getMonth()+1)+'.'+today.getDate();
        if(curval!=val){
            $.post( "", { curid: curid, val: val, updated_date: date })
                .done(function( data ) {
                    element.data('curval',val);
                    let rowData = JSON.parse(data);
                    let rowColor = rowData.color;
                    let restOfTheDays = rowData.days;
                    element.parents('tr').removeClass();
                    element.parents('tr').addClass(rowColor);
                    element.parents('tr').find('.restOfTheDays').html(restOfTheDays);
                });
        }
    });
    $(function(){

        $(document).on('click','.markTrigger1',function(){
            let curid = $(this).parents('tr').find('.dp-holder').data('curid');
            let curval = 0;
            let today = new Date();
            let date = today.getFullYear()+'.'+(today.getMonth()+1)+'.'+today.getDate();
            $.post( "", { curid2: curid, curval2: curval, updated_date: date })
                .done(function( data ) {

                });
            $(this).parents('tr').removeClass('markedItem');
            $(this).html('<i class="far fa-square" style="color:#66676b"></i>');
            $(this).removeClass('markTrigger1');
            $(this).addClass('markTrigger2');
            $(this).parents('tr').removeClass('active');
        });


        $(document).on('click','.markTrigger2',function(){
            let curid = $(this).parents('tr').find('.dp-holder').data('curid');
            let curval = 1;
            let today = new Date();
            let date = today.getFullYear()+'.'+(today.getMonth()+1)+'.'+today.getDate();
            $.post( "", { curid2: curid, curval2: curval, updated_date: date })
                .done(function( data ) {

                });
            $(this).parents('tr').addClass('markedItem');
            $(this).html('<i class="far fa-check-square" style="color:#66676b"></i>');
            $(this).removeClass('markTrigger2');
            $(this).addClass('markTrigger1');
            $(this).parents('tr').removeClass('active');
        });
    });

    $("#ajax_form_clients #edit_client").click(function() {

     $(this).parents('tr').find('.tagsinput').css("display","none");
    $('#ajax_form_clients .dirs').css("display","inline-block");

    idItem = $(this).parents('tr').find('input[name="idItem"]').val();
    name = $(this).parents('tr').find('input[name="name"]').val();
    contactName = $(this).parents('tr').find('input[name="contactName"]').val();
    comments = $(this).parents('tr').find('textarea[name="comments"]').val();
    phone = $(this).parents('tr').find('input[name="phone"]').val();
    idUser = $(this).parents('tr').find('select[name="idUser"]').val();
    directions = $(this).parents('tr').find('input[name="directions"]').val();
      
    var form = $(this).parents('#ajax_form_clients');
    var url = form.attr('action');
    $.ajax({
           type: "POST",
           url: '/lib/action_ajax_form_client.php',
           data: ({idItem:idItem, name:name, contactName:contactName, comments:comments, phone:phone, idUser:idUser, directions:directions}),
           success: function(data)
           {
               $("#result_form").html('Данные успешно сохранены!');
               $("#result_form").css("display","block");
            setTimeout(function() {
            $("#result_form").css("display","none");
            }, 1500);

           }
         });


         if ($(this).is(':visible')) {
            $(this).attr("style", "display:none");
            $(this).parents('tr').find('.edit').attr("style", "display:block");
            $("#ajax_form_clients").toggleClass("inactive");



              $(this).parents('tr').find('input[type=text]').prop("readonly", true);
              $(this).parents('tr').find('select').prop("readonly", true);
              $(this).parents('tr').find('textarea').prop("readonly", true);
            } 


            event.preventDefault();
        });


$(".edit").click(function() {

       $(this).parents('tr').find('.tagsinput').css("display","inline-block");
       $('#ajax_form_clients .dirs').css("display","none");


    $("#ajax_form_clients").toggleClass("inactive");


 if ($(this).is(':visible')) {
    $(this).attr("style", "display:none");
    $(this).parents('tr').find('input#edit_client').attr("style", "display:block");
    $("#ajax_form_clients").removeClass("inactive");

    }

    if ($("#ajax_form_clients").hasClass("inactive")) {
          $(this).parents('tr').find('input[type=text]').prop("readonly", true);
          $(this).parents('tr').find('select').prop("readonly", true);
          $(this).parents('tr').find('textarea').prop("readonly", true);
          
          
    } else { 
         $(this).parents('tr').find('input[type=text]').prop("readonly", false);
          $(this).parents('tr').find('select').prop("readonly", false);
          $(this).parents('tr').find('textarea').prop("readonly", false);
}
});


$(".nav-tabs li a").click(function() {
  $(".nav-tabs li").removeClass('active');
$(this).parents('li').addClass('active');
});



$(document).ready(() => {
  let url = location.href.replace(/\/$/, "");
 
  if (location.hash) {
    const hash = url.split("#");
    url = location.href.replace(/\/#/, "#");
    history.replaceState(null, null, url);
    $('.nav-tabs li a').removeClass('active');
    $('.nav-tabs li').find('a[href="'+location.hash+'"]').click();
    $('.nav-tabs li').find('a[href="'+location.hash+'"]').addClass('active');

    $('.tab-content .tab-pane').removeClass('active');

    $('.tab-content '+location.hash+'').addClass('active');

  } 
  
   
  $('a[data-toggle="tab"]').on("click", function() {
    let newUrl;
    const hash = $(this).attr("href");
    if(hash == "#all") {
      newUrl = url.split("#")[0];
    } else {
      newUrl = url.split("#")[0] + hash;
    }
    newUrl += "";
    history.replaceState(null, null, newUrl);
  });

});


</script>

    <script defer>
        $(function(){
            $('#ajax_form_clients #directionsInput').tagsInput({'delimiter': [';'],'defaultText':'теги'});
             $('#ajax_form_clients .tagsinput').css("display","none");
        });
    </script>

</div>