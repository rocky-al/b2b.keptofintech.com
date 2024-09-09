<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">View Image</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
      </div>
      <div class="modal-body">
      <div class="modal-body" id="replaceModal"></div>
      </div>
      <div class="modal-footer">
     
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">Close</button>
       
      </div>
    </div>
  </div>
</div>

<script> 
function imageZoom(folderPath, image) { 
$('#replaceModal').html('<img src="<?php echo url('/'); ?>/' + folderPath + '/' + image + '" alt="Profile Image" style="width: 100%;">');

jQuery('#exampleModal').modal('show')
}

$(document).on("click", ".imageZoomKp", function() {
    var image_path = $(this).attr('src');
    console.log(image_path);
    $('#replaceModal').html('<img src="'+ image_path + '" alt="Profile Image" style="width: 100%;">');
    jQuery('#exampleModal').modal('show')
});

</script>


<footer class="footer">
    <div class="loader" style="display:none">
        <div class="indeterminate"></div>
    </div>
    <div class="w-100 clearfix">
        <span class="text-center text-sm-left d-md-inline-block">
            {{ __('COPYRIGHT © '.date("Y").'')}} KEPTO FINTECH PVT. LTD. - ALL RIGHTS RESERVED.

        </span>
    </div>
</footer>