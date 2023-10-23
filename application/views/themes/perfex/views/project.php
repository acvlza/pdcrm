<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php echo form_hidden('project_id', $project->id); ?>
<div class="tw-flex tw-items-center tw-justify-between tw-mb-3">
    <div class="tw-flex tw-items-center">
        <h4 class="tw-my-0 tw-font-semibold tw-text-lg tw-text-neutral-700 section-heading section-heading-project">
            <?php echo $project->name; ?>
        </h4>
        <?php if ($project->settings->view_team_members == 1 && count($members) > 0) { ?>
        <div class="team-members tw-items-center ltr:tw-space-x-2 tw-ml-3 tw-inline-flex">
            <div class="tw-flex -tw-space-x-1">
                <?php foreach ($members as $member) { ?>
                <span data-title="<?php echo get_staff_full_name($member['staff_id']); ?>" data-toggle="tooltip">
                    <?php
                echo staff_profile_image(
    $member['staff_id'],
    ['tw-inline-block tw-h-7 tw-w-7 tw-rounded-full tw-ring-2 tw-ring-white', '']
);
                ?>
                </span>
                <?php } ?>
            </div>
        </div>
        <?php } ?>
        <?php
            $taskStatus = $project_status['id'];  // Get the project status id

if ($taskStatus == 1) {
    $labelStyles = 'label label-primary';
} elseif ($taskStatus == 2) {
    $labelStyles = 'label label-warning';
} elseif ($taskStatus == 3) {
    $labelStyles = 'label label-success';
} else {
    $labelStyles = '';  // Default, in case the taskStatus doesn't match any of the above
}

echo '<span class="' . $labelStyles . ' tw-ml-3">' . $project_status['name'] . '</span>';

       ?>
    </div>

</div>
<div class="panel_s">
    <div class="panel-body">
        <?php get_template_part('projects/project_tabs'); ?>
        <div class="clearfix mtop15"></div>
        <?php get_template_part('projects/' . $group); ?>
    </div>
</div>
<script>
function approve_contract(contract_id) {

$.post("<?php echo site_url('video_library/client_video_requests/approve_contract'); ?>", {
id: contract_id,
}, function(result){
	//alert(result);
if(result == 'success'){
alert('Document Approved');
location.reload(); 
//$('#ModalConfirm').modal('show');
}else{
alert('An error occured');
//$('#ModalError').modal('show');
}
});
}


function revise_contract(contract_id) {
	
$.post("<?php echo site_url('video_library/client_video_requests/revise_contract'); ?>", {
id: contract_id,
}, function(result){
	//alert(result);
if(result == 'success'){
alert('Document under Revision');
location.reload(); 
//$('#ModalConfirm').modal('show');
}else{
alert('An error occured');
//$('#ModalError').modal('show');
}
});
}
</script>