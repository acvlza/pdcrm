<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<style type="text/css">
.scrollable-container::-webkit-scrollbar


   

</style>


<div class="modal fade _project_file" tabindex="-1" role="dialog" data-toggle="modal">
   <div class="modal-dialog full-screen-modal" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" onclick="close_modal_manually('._project_file'); return false;"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><?php echo $file->subject; ?></h4>
         </div>
         
         <div class="modal-body">
            <div class="row">
               <div class="col-md-8 project_file_area" style="height: 100px !important">
               
               <div class="form-group col-md-6">

               <?php
                    if ($file->contact_id == get_contact_user_id()) { ?>
               <?php echo render_input('file_subject', 'Filename', $file->subject, 'text', ['onblur' => 'update_file_data(' . $file->id . ',' . $file->project_id . ')']); ?>
                </div>  
               <div class="form-group col-md-3" app-field-wrapper="file_priority">
                  <label for="file_importance" class="control-label">Priority</label>
                  <?php if (isset($_COOKIE["file_priority_".$file->id])) {
                  $pvalue = $_COOKIE["file_priority_".$file->id];
                  }else{
                  $pvalue='';
                  }
                  ?>
                  <select name="file_priority_<?php echo $file->id;?>" id="file_priority_<?php echo $file->id;?>" class="form-control" onchange="setPriority(this)">
                  <option value="">NONE</option>
                  <option value="Low"<?php if($pvalue=="Low"){echo ' selected';}?>>Low</option>
                  <option value="Medium"<?php if($pvalue=="Medium"){echo ' selected';}?>>Medium</option>
                  <option value="High"<?php if($pvalue=="High"){echo ' selected';}?>>High</option>
                  </select>
               </div>

               <div class="form-group col-md-3" app-field-wrapper="file_category"><label for="file_category" class="control-label">Category</label>
                  <?php if (isset($_COOKIE["file_category_".$file->id])) {
                  $cvalue = $_COOKIE["file_category_".$file->id];
                  }else{
                  $cvalue='';
                  }
                  ?>
                  <?php
                  $custom_categories = isset($_COOKIE["custom_categories"]) ? $_COOKIE["custom_categories"] : '';
                  $cats= explode(',',$custom_categories);
                  ?>

                  <select name="file_category_<?php echo $file->id;?>" id="file_category_<?php echo $file->id;?>" class="form-control" onchange="setCategory(this)">
                  <option value="">NONE</option>
                  <?php
                  foreach($cats as $cat){?>
                  <option value="<?php echo trim($cat);?>"<?php if($cvalue==trim($cat)){echo ' selected';}?>><?php echo trim($cat);?></option>
                  <?php }?>

                  </select>
               </div>
 						               
<div class="clearfix"></div>                  
<hr style="border: 1px solid #323761;" />                  
                  
                  <?php echo render_textarea('file_description', 'project_discussion_description', $file->description, ['onblur' => 'update_file_data(' . $file->id . ',' . $file->project_id . ')']); ?>
                  <hr style="border: 1px solid #323761;" />
                  <?php } else { ?>
                  <?php if (!empty($file->description)) { ?>
                  <p class="bold"><?php echo _l('project_discussion_description'); ?></p>
                  <p class="text-muted"><?php echo $file->description; ?></p>
                  <hr style="border:1px solid #323761;" />
                  <?php } ?>
                  <?php } ?>
                  <?php if (!empty($file->external) && $file->external == 'dropbox') { ?>
                  <a href="<?php echo $file->external_link; ?>" target="_blank" class="btn btn-primary mbot20"><i class="fa fa-dropbox" aria-hidden="true"></i> <?php echo _l('open_in_dropbox'); ?></a><br /><br />
                  <?php } elseif (!empty($file->external) && $file->external == 'gdrive') { ?>
                     <a href="<?php echo $file->external_link; ?>" target="_blank" class="btn btn-primary mbot20">
                           <i class="fa-brands fa-google" aria-hidden="true"></i>
                           <?php echo _l('open_in_google'); ?>
                     </a>
                     <br />
                  <?php } ?>
                  <?php
                     $path = PROJECT_ATTACHMENTS_FOLDER . $file->project_id . '/' . $file->file_name;
                     if (is_image($path)) { ?>
                  <img src="<?php echo base_url('uploads/projects/' . $file->project_id . '/' . $file->file_name); ?>" class="img img-responsive">
                  <?php } elseif (!empty($file->external) && !empty($file->thumbnail_link)) { ?>
                  <img src="<?php echo optimize_dropbox_thumbnail($file->thumbnail_link); ?>" class="img img-responsive">
                  <?php } elseif (strpos($file->filetype, 'pdf') !== false && empty($file->external)) { ?>
                  <iframe src="<?php echo base_url('uploads/projects/' . $file->project_id . '/' . $file->file_name); ?>" height="100%" width="100%" frameborder="0"></iframe>
                  <?php } elseif (is_html5_video($path)) { ?>
                  <video width="100%" height="100%" src="<?php echo site_url('download/preview_video?path=' . protected_file_url_by_path($path) . '&type=' . $file->filetype); ?>" controls>
                     Your browser does not support the video tag.
                  </video>
                  <?php } elseif (is_markdown_file($path) && $previewMarkdown = markdown_parse_preview($path)) {
                         echo $previewMarkdown;
                     } else {
                         if (empty($file->external)) {
                             echo '<a href="' . site_url('uploads/projects/' . $file->project_id . '/' . $file->file_name) . '" download>' . $file->file_name . '</a>';
                         } else {
                             echo '<a href="' . $file->external_link . '" target="_blank">' . $file->file_name . '</a>';
                         }

                         echo '<p class="text-muted">' . _l('no_preview_available_for_file') . '</p>';
                     } ?>
               </div>
               <div class="col-md-4 project_file_discusssions_area">
                  <div id="project-file-discussion" class="tc-content"></div>
               </div>
            </div>
         </div>
         <div class="clearfix"></div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" onclick="close_modal_manually('._project_file'); return false;"><?php echo _l('close'); ?></button>
         </div>
      </div>
      <!-- /.modal-content -->
   </div>
   <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<script>
function setPriority(obj){
var id = obj.id;
var value = obj.value;
document.cookie = id + "=" + value; 

console.log(getCookie(id));

}

function setCategory(obj){
var id = obj.id;
var value = obj.value;
document.cookie = id + "=" + value; 

console.log(getCookie(id));

}

function getCookie(cname) {
  let name = cname + "=";
  let decodedCookie = decodeURIComponent(document.cookie);
  let ca = decodedCookie.split(';');
  for(let i = 0; i <ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

var discussion_id = '<?php echo $file->id; ?>';
var discussion_user_profile_image_url = '<?php echo $discussion_user_profile_image_url; ?>';
var current_user_is_admin = '<?php echo $current_user_is_admin; ?>';

function adjustModalContentHeight() {
    var default_height = '550px';
    
    if ($('iframe').length > 0) {
        $('iframe').css('height', default_height);
    }

    $('.project_file_area, .project_file_discusssions_area').css('height', default_height);
}

// Adjust content when the modal is shown
$('body').on('shown.bs.modal', '._project_file', function() {
    adjustModalContentHeight();
});

// Adjust content on window resize
$(window).on('resize', function() {
    if ($('body').find('._project_file').hasClass('in')) { // Check if modal is currently visible
        adjustModalContentHeight();
    }
});

// Show the modal
$('body').find('._project_file').modal({ show: true, backdrop: 'static', keyboard: false });
</script>
