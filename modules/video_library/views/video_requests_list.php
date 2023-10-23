<?php
defined('BASEPATH') || exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
<div class="content">
<div class="row">

<div class="col-md-12">
<div class="panel_s">
<div class="panel-body">
<div class="_buttons">
<div class="row">
<div class="col-md-12">
<!--
<?php if (has_permission('video_requests', '', 'create') || is_admin()) { ?>
<?php if(file_exists(__DIR__ .'/video_requests_add_modal.php')){?>
<a href="#" data-toggle="modal" data-target="#video_requestsModal" class="btn btn-primary tw-mb-2 sm:tw-mb-4 pull-right"><i class="fa-regular fa-plus tw-mr-1"></i>Add New</a>
<?php }else{?>	
<a href="<?php echo admin_url('test_module/video_requests/add'); ?>" class="btn btn-primary tw-mb-2 sm:tw-mb-4 pull-right"><i class="fa-regular fa-plus tw-mr-1"></i>Add New</a>
<?php }} ?>
-->
<h4><span class="glyphicon glyphicon-facetime-video"></span> <?php echo _l('Video Requests'); ?></h4>
</div>

</div>
</div>
<div class="clearfix"></div>
<hr class="hr-panel-heading" />
<div class="clearfix"></div>
<?php render_datatable([
'#',
'Project Name<br>',
'Request Date and Time<br>',

'Action', 
], 'video_requests_list');
?>
</div>
</div>
</div>
</div>
</div>
</div>

<?php init_tail(); ?>

<?php 
if(file_exists(__DIR__ .'/video_requests_add_modal.php')){
include module_dir_path('video_library','views/video_requests_add_modal.php');
}
?>
<script>
initDataTable('.table-video_requests_list', admin_url+"video_library/video_requests_table/", undefined, undefined,undefined,[2,'Desc']);

</script>
</body>
</html>