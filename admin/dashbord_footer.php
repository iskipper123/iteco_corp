
  </div>
</div>
<?  require_once '../partsOfPages/footer.php';?> 
</div>



<!-- Modal -->
<div class="modal fade dashbord_modal" id="client_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div id="mask" class="close" style="width: 240px;height:inherit;float:left;" data-dismiss="modal"></div>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
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
   $('.userinfo').click(function(){
  var client_id = $(this).data('client_id');
  alert(client_id);
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

</script>