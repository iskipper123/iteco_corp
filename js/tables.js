$(function(){

    $('.interactiveTable tr').on('click',function(){

        var status = $(this).data('status');

        $(this).find('input[type=radio]').prop("checked", true);
        $('#edit_request').attr("data-client_id", 'editRequest.php?edit='+$(this).find('input[type=radio]').val());
        $('.interactiveTable tr').each(function(){

            $(this).removeClass('active');

        });

        $(this).addClass('active');

    }); 



    $("#accordionSidebar").on({

        mouseenter: function () {

            if($('#collapseTwo').is(":hidden")){

                //$('#logoImg').attr('src','/images/iteco-logo.png');

                //$('#accordionSidebar').removeClass('toggled');

            }

           

        },

        mouseleave: function () {

            if($('#collapseTwo').is(":hidden")){

                //$('#logoImg').attr('src','/images/logo-small.png');

                //$('#accordionSidebar').addClass('toggled');

            }

            

        }

    });



});




$('input#nameInput').on('input', function() {
  var c = this.selectionStart,
      r = /[^a-zA-Zа-яА-Я0-9-&. ]/gi,
      v = $(this).val();
  if(r.test(v)) {
    $(this).val(v.replace(r, ''));
    c--;
  }
  this.setSelectionRange(c, c);
   var str = $('input#nameInput').val();
        var spart = str.split(" ");
        for ( var i = 0; i < spart.length; i++ )
        {
            var j = spart[i].charAt(0).toUpperCase();
            spart[i] = j + spart[i].substr(1);
        }
      $('input#nameInput').val(spart.join(" "));
});
