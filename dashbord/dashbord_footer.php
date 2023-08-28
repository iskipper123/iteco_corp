
  </div>

<? 
   require_once '../partsOfPages/footer.php';?> 
</div>



<!-- Modal -->
<div class="modal fade dashbord_modal" id="client_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div id="mask" class="close" style="width: 240px;height:inherit;float:left;"></div>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
       <ul class="nav nav-tabs" style="display: none;">
        <li class="active"><a data-toggle="tab" href="#info" class="active">Общие</a></li>
        <li><a data-toggle="tab" href="#sdelki">Сделки</a></li> 
        <li><a data-toggle="tab" href="#rastamojka">Растаможка</a></li>
        <li><a data-toggle="tab" href="#oplata">Оплата</a></li>
        <li><a data-toggle="tab" href="#zvonki">Звонки</a></li>
      </ul>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      
      </div>
    </div>
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
    if (scroll >= 300) {
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


    $('.menu_tab li a').click(function() {
       alpha_nr = '';
       filter = 'all';
        $(".menu_tab li").removeClass('active');
        $(this).parents('li').addClass('active');

        if ($(".topbar ul li:nth-child(1)").hasClass("active")){
            filter = 'all';
            alpha_nr = '';
        $(".get_alpha li a").removeClass('active');
        $(".get_alpha li:first-child a").addClass('active');
        $(".get_alpha").show();
        }
        if ($(".topbar ul li:nth-child(2)").hasClass("active")){
            filter = 'injob';
            alpha_nr = $('.get_alpha li a.active').attr('type');
        $(".get_alpha li a").removeClass('active');
        $(".get_alpha li:last-child a").addClass('active');
         $(".get_alpha").hide();
        }
        if ($(".topbar ul li:nth-child(3)").hasClass("active")){
            filter = 'profit';
            alpha_nr = $('.get_alpha li a.active').attr('type');
             $(".get_alpha").hide();
        }
        if ($(".topbar ul li:nth-child(4)").hasClass("active")){
            filter = 'arhive';
             alpha_nr = ''; 
             $(".get_alpha").hide(); 
        }



          if ($(".bottom_bar ul li:nth-child(1)").hasClass("active")){
            filter = 'Зима';
             alpha_nr = ''; 
             $(".get_alpha").hide(); 
        }
     
          if ($(".bottom_bar ul li:nth-child(2)").hasClass("active")){
            filter = 'Весна';
             alpha_nr = ''; 
             $(".get_alpha").hide(); 
        }
     
          if ($(".bottom_bar ul li:nth-child(3)").hasClass("active")){
            filter = 'Лето';
             alpha_nr = ''; 
             $(".get_alpha").hide(); 
        }

          if ($(".bottom_bar ul li:nth-child(4)").hasClass("active")){
            filter = 'Осень';
             alpha_nr = ''; 
             $(".get_alpha").hide(); 
        }    

        if ($(".bottom_bar ul li:nth-child(5)").hasClass("active")){
            filter = 'Все';
             alpha_nr = ''; 
             $(".get_alpha").hide(); 
        } 

     
     

   $("#work_space").load('./ajax_all_clients.php?idManager='+'<?php echo $idManager; ?>',  {
           alpha: alpha_nr,
           filter: filter
       }); 
});


</script>

    <script defer>
        $(function(){
            $('#ajax_form_clients #directionsInput').tagsInput({'delimiter': [';'],'defaultText':'теги'});
             $('#ajax_form_clients .tagsinput').css("display","none");
        });
    </script>

