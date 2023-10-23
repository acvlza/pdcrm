<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<style>
* { box-sizing: border-box; }

/* force scrollbar */
html { }

body { font-family: sans-serif; }

/* ---- grid ---- */

.grid {
  max-width: 1300px;
  background: rgba(93, 91, 133, 0.56);
  overflow-x: hidden; 
  overflow-y: scroll;
  height: 400px;
  max-height: 400px;
  padding:5px;
}

/* clear fix */
.grid:after {
  content: '';
  display: block;
  clear: both;
}


/* ---- .grid-item ---- */

.grid-sizer,
.grid-item {
  width: 14%;
}

.grid .grid-item {
  padding-bottom: 14%; /* hack for proportional sizing */
  float: left;
  background-position: center center;
  background-size: cover;
  
}


/* ---- grid2 ---- */

.grid2 {

 min-width:100% !important;
 max-width:100% !important;
  background: rgba(93, 91, 133, 0.56);
overflow-x: scroll; 
  /*overflow-y: hidden;*/ 
  height: 200px;
  padding:5px;
}



/* clear fix */



/* ---- .grid-item ---- */

.grid-sizer,
.grid-item {
  width: 14%;
}

.grid2 .grid-item {
  padding-bottom: 14%; /* hack for proportional sizing */
 
  background-position: center center;
  background-size: cover;

}

.packery-drop-placeholder {
  border: 3px dotted #333;
  background: hsla(0, 0%, 0%, 0.3);
}

.grid-item.is-dragging,
.grid-item.is-positioning-post-drag {
  z-index: 2;
}
/* Bottom left text */
.bottom-left {
  position: absolute;
  bottom: 7px;
  left: 5px;
  border-radius:15%;width:30px;height:30px;padding:5px;text-align:center;
}

/* Top left text */
.top-left {
  position: absolute;
  top: 4px;
  left: 15px;
  color:#CD2323;
}

/* Top right text */
.top-right {
    position: absolute;
    top: 1px;
    background: rgb(0, 0, 0);
    background-color: rgba(93, 91, 133, 0.40);
    color: #f1f1f1;
    width: 100%;
    padding: 5px 7px;
    text-align: right;
    border-top-right-radius: 6px;
    border-top-left-radius: 6px;
  
  
}


/* Bottom right text */
.bottom-right {
  position: absolute;
  bottom: 8px;
  right: 8px;
}

/* Centered text */
.centered {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

.red-label{


}

.no-display{
  visibility:hidden;
  width:0px;
  height:100px;
  
}

#project-files-upload{
margin-top:30px !important; 
  
}

.fa-trash{
	color:white;
}
</style>


<?php $custom_categories = isset($_COOKIE["custom_categories"]) ? $_COOKIE["custom_categories"] : '';?>
<h5 style="font-weight:600; color: #4F5786!important; padding-bottom: 5px; margin-left:20px;"> Category Filter</h5> 
<?php $viewcat = isset($_COOKIE["file_category"]) ? $_COOKIE["file_category"] : '';?>
<div class="form-group col-md-4" app-field-wrapper="file_category">
<select name="file_category" id="file_category" class="form-control" onchange="selectCategory(this)">
<option value="">None</option>
<?php
$cats= explode(',',$custom_categories);
foreach($cats as $cat){?>
<option value="<?php echo trim($cat);?>"<?php if($viewcat==trim($cat)){echo ' selected';}?>><?php echo trim($cat);?></option>
<?php }?>
</select></div>

<div class="form-group col-md-6" app-field-wrapper="custom_categories">
    <input 
        type="text" 
        name="custom_categories" 
        id="custom_categories" 
        class="form-control" 
        value="<?php echo $custom_categories; ?>" 
        placeholder="Enter custom categories here and separate it by comma. Ex: Intro, Outro">
</div>


  <div class="form-group col-md-2">
 
    <button class="btn btn-default btn-block" onclick="saveCategories()">
     Save
    </button>
  </div>

<div class="clearfix"></div>
<hr style="height: 2px; background-color: #323761; border: none; opacity: 0.5;">


<h3 style="font-weight:600; color: #323761!important; padding-bottom: 20px;"> Main Bin</h3> 


<?php echo form_open_multipart(admin_url('projects/upload_file/' . $project->id), ['class' => 'dropzone', 'id' => 'project-files-upload']); ?>
<input type="file" name="file" multiple />
<?php echo form_close(); ?>
<span class="tw-mt-4 tw-inline-block tw-text-sm"><?php echo _l('project_file_visible_to_customer'); ?></span><br />
<div class="onoffswitch">
    <input type="checkbox" name="visible_to_customer" id="pf_visible_to_customer" class="onoffswitch-checkbox">
    <label class="onoffswitch-label" for="pf_visible_to_customer"></label>
</div>
<div class="tw-flex tw-justify-end tw-items-center tw-space-x-2">
    <button class="gpicker" data-on-pick="projectFileGoogleDriveSave">
        <i class="fa-brands fa-google" aria-hidden="true"></i>
        <?php echo _l('choose_from_google_drive'); ?>
    </button>
    <div id="dropbox-chooser"></div>
</div>
<div class="clearfix"></div>
<div class="mtop20"></div>
<div class="modal fade bulk_actions" id="project_files_bulk_actions" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo _l('bulk_actions'); ?></h4>
            </div>
            <div class="modal-body">
                <?php if (is_admin()) { ?>
                <div class="checkbox checkbox-danger">
                    <input type="checkbox" name="mass_delete" id="mass_delete">
                    <label for="mass_delete"><?php echo _l('mass_delete'); ?></label>
                </div>
                <hr style="Border : 1px solid #323761;" />
                <?php } ?>
                <div id="bulk_change">
                    <div class="form-group">
                        <label class="mtop5"><?php echo _l('project_file_visible_to_customer'); ?></label>
                        <div class="onoffswitch">
                            <input type="checkbox" name="bulk_visible_to_customer" id="bulk_pf_visible_to_customer"
                                class="onoffswitch-checkbox">
                            <label class="onoffswitch-label" for="bulk_pf_visible_to_customer"></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <a href="#" class="btn btn-default"
                    onclick="project_files_bulk_action(this); return false;"><?php echo _l('confirm'); ?></a>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<a href="#" data-toggle="modal" data-target="#project_files_bulk_actions" class="bulk-actions-btn table-btn hide"
    data-table=".table-project-files">
    <?php echo _l('bulk_actions'); ?>
</a>
<a href="#"
    onclick="window.location.href = '<?php echo admin_url('projects/download_all_files/' . $project->id); ?>'; return false;"
    class="table-btn hide" data-table=".table-project-files"><?php echo _l('download_all'); ?></a>
<div class="clearfix"></div>


<div class="panel_s panel-table-full">
    <div class="panel-body">
	
	
	
<div style="width:100%;margin:auto;">
<div class="grid which" id="grid">
<div class="grid-sizer"></div>

<?php 
$c =1;$b=1;
$key=0;
foreach ($files as $key=>$file) {
$pos = ($file['id'] & 1) ? 'even' : 'odd';  
$bin_item = isset($_COOKIE["bin_item_".$file['id']]) ? $_COOKIE["bin_item_".$file['id']] :'false';    
$key=$key+1;
if (isset($_COOKIE["file_priority_".$file['id']])) {
$pvalue = $_COOKIE["file_priority_".$file['id']];
if($pvalue == 'Low'){
$color='grey';
}else if($pvalue == 'Medium'){
$color='orange';
}else if($pvalue == 'High'){
$color='red';
}else{
$color='';
$pvalue ='';
}
$priority = '<span style="background-color:'.$color.';color:white;padding:3px 5px;border-radius:5px;font-size:10px;font-weight:bold;">'.strtoupper($pvalue).'</span>';
}else{
$priority ='';
}


if (isset($_COOKIE["file_category_".$file['id']])) {
$cvalue = $_COOKIE["file_category_".$file['id']];
$viewcat = isset($_COOKIE["file_category"]) ? $_COOKIE["file_category"] : '';
}else{
$cvalue='';
$viewcat ='';
}

if($cvalue != $viewcat && $viewcat !=''){continue;}

$path = get_upload_path_by_type('project') . $project->id . '/' . $file['file_name']; ?>
<?php if (!is_image(PROJECT_ATTACHMENTS_FOLDER . $project->id . '/' . $file['file_name'])) {
if ( $bin_item != 'true' ){
$httpstr = explode("://", APP_BASE_URL)[0];
$placeholder = $httpstr."://".$_SERVER['SERVER_NAME']."/assets/images/placeholder.jpg";?>  <div class="gutter-sizer"></div>

<div id="grid-item-<?php echo $file['id']?>" class="grid-item" data-item-id="<?php echo $c;?>" style="background-image: url(<?php echo $placeholder;?>);border-radius: 10px;"><div class="top-right"><a href="#" onclick="view_project_file(<?php echo $file['id']; ?>,<?php echo $file['project_id']; ?>); return false;"><i style="margin-right:5px;color:white;" class="fa fa-eye" aria-hidden="true"></i></a>
<?php $file_name = $file['original_file_name'] != '' ? $file['original_file_name'] : $file['file_name']; ?>
                            <a href="#" data-toggle="modal" data-original-file-name="<?php echo $file_name; ?>"
                                data-filetype="<?php echo $file['filetype']; ?>"
                                data-file-name="<?php echo $file['original_file_name']; ?>"
                                data-path="<?php echo PROJECT_ATTACHMENTS_FOLDER . $project->id . '/' . $file['file_name']; ?>"
                                data-target="#send_file"
                                class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 tw-mt-1"><i style="margin-right:5px;color:white;" class="fa-regular fa-envelope fa-lg" aria-hidden="true"></i></a>

<?php if ($file['staffid'] == get_staff_user_id() || has_permission('projects', '', 'delete') || is_admin()) { ?>
<a href="<?php echo admin_url('projects/remove_file/' . $project->id . '/' . $file['id']); ?>"><i class="fa-solid fa-trash"></i></a>
<?php } ?>
</div>

<div class="top-left"><?php echo $priority;?></div>

<div class="centered"><?php //echo $file['subject']; ?></div>
<div id="grid-number-<?php echo $file['id']?>" class="bottom-right" style="font-size:12px;font-weight:normal;color:white;background-color: rgba(93, 91, 133, 0.60);;;border-radius:30%;width:30px;height:30px;padding:5px;text-align:center;"><?php echo $key; ?></div>
<span onclick="toBin(<?php echo $file['id']?>);" class="bottom-left">
<!--<span class="glyphicon glyphicon-arrow-down"></span>-->
<img style="width:30px;" src="<?php echo site_url('assets/images/down.png'); ?>">
</span>
</div><?php
}
}else{
if ( $bin_item != 'true' ) {
?>
<div id="grid-item-<?php echo $file['id']?>" class="grid-item" data-item-id="<?php echo $c;?>" style="background-image: url(<?php echo project_file_url($file, false);?>);border-radius: 10px;"><div class="top-right"><a href="#" onclick="view_project_file(<?php echo $file['id']; ?>,<?php echo $file['project_id']; ?>); return false;"><i style="margin-right:5px;color:white;" class="fa fa-eye" aria-hidden="true"></i></a>

<?php $file_name = $file['original_file_name'] != '' ? $file['original_file_name'] : $file['file_name']; ?>
                            <a href="#" data-toggle="modal" data-original-file-name="<?php echo $file_name; ?>"
                                data-filetype="<?php echo $file['filetype']; ?>"
                                data-file-name="<?php echo $file['original_file_name']; ?>"
                                data-path="<?php echo PROJECT_ATTACHMENTS_FOLDER . $project->id . '/' . $file['file_name']; ?>"
                                data-target="#send_file"
                                class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 tw-mt-1"><i style="margin-right:5px;color:white;" class="fa-regular fa-envelope fa-lg" aria-hidden="true"></i></a>
								
<?php if ($file['staffid'] == get_staff_user_id() || has_permission('projects', '', 'delete') || is_admin()) { ?>
<a href="<?php echo admin_url('projects/remove_file/' . $project->id . '/' . $file['id']); ?>"><i class="fa-solid fa-trash"></i></a>
<?php } ?>
</div>

<div class="top-left"><?php echo $priority;?></div>

<div class="centered"><?php //echo $file['subject']; ?></div>
<div id="grid-number-<?php echo $file['id']?>" class="bottom-right" style="font-size:16px;font-weight:bold;color:white;background: rgba(255, 255, 255, 0.56);;border-radius:15%;width:30px;height:30px;padding:5px;text-align:center;"><?php echo $key; ?></div>
<span onclick="toBin(<?php echo $file['id']?>);" class="bottom-left">
<!--<span class="glyphicon glyphicon-arrow-down"></span>-->
<img style="width:30px;" src="<?php echo site_url('assets/images/down.png'); ?>">
</span>
</div>
<?php


}
}
$c++;

}

?>

</div>
</div>
<div class="clearfix"></div>
<hr style="height: 2px; background-color: #323761; border: none; opacity: 0.5;">

<h3 style="font-weight:600; color: #4F5786!important; padding-bottom: 20px;"> Ordered Bin</h3> 

 <div class="clearfix"></div>
<hr style="height: 2px; background-color: #323761; border: none; opacity: 0.5;">

<div style="width:100%;margin:auto;">
<div class="grid2" id="grid2">
<div class="grid-sizer"></div>


<?php 
$c =1;$b=1;
$keys=0;
foreach ($files as $key=>$file) {
$pos = ($file['id'] & 1) ? 'even' : 'odd';  
$bin_item = isset($_COOKIE["bin_item_".$file['id']]) ? $_COOKIE["bin_item_".$file['id']] :'false';  

if (isset($_COOKIE["file_priority_".$file['id']])) {
$pvalue = $_COOKIE["file_priority_".$file['id']];
if($pvalue == 'Low'){
$color='grey';
}else if($pvalue == 'Medium'){
$color='orange';
}else if($pvalue == 'High'){
$color='red';
}else{
$color='';
$pvalue ='';
}
$priority = '<span style="background-color:'.$color.';color:white;padding:3px 5px;border-radius:5px;font-size:10px;font-weight:bold;">'.strtoupper($pvalue).'</span>';
}else{
$priority ='';
}


if (isset($_COOKIE["file_category_".$file['id']])) {
$cvalue = $_COOKIE["file_category_".$file['id']];
$viewcat = isset($_COOKIE["file_category"]) ? $_COOKIE["file_category"] : '';
}else{
$cvalue='';
$viewcat ='';
}

if($cvalue != $viewcat && $viewcat !=''){continue;}

$path = get_upload_path_by_type('project') . $project->id . '/' . $file['file_name']; ?>
<?php if (!is_image(PROJECT_ATTACHMENTS_FOLDER . $project->id . '/' . $file['file_name'])) {
if ( $bin_item == 'true' ){
$keys++;  
$httpstr = explode("://", APP_BASE_URL)[0];
$placeholder = $httpstr."://".$_SERVER['SERVER_NAME']."/assets/images/placeholder.jpg";?>
<div id="grid-item-<?php echo $file['id']?>" class="grid-item" data-item-id="<?php echo $c;?>" style="background-image: url(<?php echo $placeholder;?>);border-radius: 10px;"><div class="top-right"><a href="#" onclick="view_project_file(<?php echo $file['id']; ?>,<?php echo $file['project_id']; ?>); return false;"><i style="margin-right:5px;color:white;" class="fa fa-eye" aria-hidden="true"></i></a>

<?php $file_name = $file['original_file_name'] != '' ? $file['original_file_name'] : $file['file_name']; ?>
                            <a href="#" data-toggle="modal" data-original-file-name="<?php echo $file_name; ?>"
                                data-filetype="<?php echo $file['filetype']; ?>"
                                data-file-name="<?php echo $file['original_file_name']; ?>"
                                data-path="<?php echo PROJECT_ATTACHMENTS_FOLDER . $project->id . '/' . $file['file_name']; ?>"
                                data-target="#send_file"
                                class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 tw-mt-1"><i style="margin-right:5px;color:white;" class="fa-regular fa-envelope fa-lg" aria-hidden="true"></i></a>
								
<?php if ($file['staffid'] == get_staff_user_id() || has_permission('projects', '', 'delete') || is_admin()) { ?>
<a href="<?php echo admin_url('projects/remove_file/' . $project->id . '/' . $file['id']); ?>"><i class="fa-solid fa-trash"></i></a>
<?php } ?>
</div>

<div class="top-left"><?php echo $priority;?></div>

<div class="centered"><?php echo $file['type']; ?></div>
<div id="grid-number-<?php echo $file['id']?>" class="bottom-right" style="font-size:16px;font-weight:bold;color:white;background: rgba(255, 255, 255, 0.56);;border-radius:15%;width:30px;height:30px;padding:5px;text-align:center;"><?php echo $key; ?></div>
<span onclick="fromBin(<?php echo $file['id']?>);" class="bottom-left">
<!--<span class="glyphicon glyphicon-arrow-down"></span>-->
<img style="width:30px;" src="<?php echo site_url('assets/images/up.png'); ?>">
</span>
</div><?php
}
}else{
if ( $bin_item == 'true' ){
  $keys++;
?>
<div id="grid-item-<?php echo $file['id']?>" class="grid-item" data-item-id="<?php echo $c;?>" style="background-image: url(<?php echo project_file_url($file, false);?>);border-radius: 10px;"><div class="top-right"><a href="#" onclick="view_project_file(<?php echo $file['id']; ?>,<?php echo $file['project_id']; ?>); return false;"><i style="margin-right:5px;color:white;" class="fa fa-eye" aria-hidden="true"></i></a>

<?php $file_name = $file['original_file_name'] != '' ? $file['original_file_name'] : $file['file_name']; ?>
                            <a href="#" data-toggle="modal" data-original-file-name="<?php echo $file_name; ?>"
                                data-filetype="<?php echo $file['filetype']; ?>"
                                data-file-name="<?php echo $file['original_file_name']; ?>"
                                data-path="<?php echo PROJECT_ATTACHMENTS_FOLDER . $project->id . '/' . $file['file_name']; ?>"
                                data-target="#send_file"
                                class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 tw-mt-1"><i style="margin-right:5px;color:white;" class="fa-regular fa-envelope fa-lg" aria-hidden="true"></i></a>
								
<?php if ($file['staffid'] == get_staff_user_id() || has_permission('projects', '', 'delete') || is_admin()) { ?>
<a href="<?php echo admin_url('projects/remove_file/' . $project->id . '/' . $file['id']); ?>"><i class="fa-solid fa-trash"></i></a>
<?php } ?>
</div>

<div class="top-left"><?php echo $priority;?></div>

<div class="centered"><?php echo $file['type']; ?></div>
<div id="grid-number-<?php echo $file['id']?>" class="bottom-right bottom-right-bin" style="font-size:16px;font-weight:bold;color:white;background: rgba(255, 255, 255, 0.56);;border-radius:15%;width:30px;height:30px;padding:5px;text-align:center;"><?php echo $keys; ?></div>
<span onclick="fromBin(<?php echo $file['id']?>);" class="bottom-left">
<!--<span class="glyphicon glyphicon-arrow-down"></span>-->
<img style="width:30px;" src="<?php echo site_url('assets/images/up.png'); ?>">
</span>
</div>
<?php

}
}
$c++;




}

?>

</div>
</div>

<div class="clearfix"></div>
<hr style="border: 1px solid #323761 !important;">
<div class="pull-right mbot20">
    <button class="btn btn-default" onclick="Reset();">
      Reset File Order
    </button>
  </div>
  
  
 </div>
 </div>

<div id="project_file_data"></div>
<?php include_once(APPPATH . 'views/admin/clients/modals/send_file_modal.php'); ?>

