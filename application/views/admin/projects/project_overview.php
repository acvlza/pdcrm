<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="row"> 
    <div class="col-md-12 project-overview-left">
        <div class="panel_s">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <p class="project-info tw-mb-0 tw-font-medium tw-text-base tw-tracking-tight">
                            <?php echo _l('project_progress_text'); ?> <span
                                class="tw-text-neutral-500"><?php echo $percent; ?>%</span>
                        </p>
                        <div class="progress progress-bar-mini">
                            <div  style="width: <?php echo $percent; ?>%;" class="progress-bar progress-bar-success no-percent-text not-dynamic"
                                role="progressbar" aria-valuenow="<?php echo $percent; ?>" aria-valuemin="0"
                                aria-valuemax="100" style="width: 0%" data-percent="<?php echo $percent; ?>">
                            </div>
                        </div>
                        <?php hooks()->do_action('admin_area_after_project_progress') ?>
                        <hr class="hr-panel-separator" />
                    </div>

                    <?php if (count($project->shared_vault_entries) > 0) { ?>
                    <?php $this->load->view('admin/clients/vault_confirm_password'); ?>
                    <div class="col-md-12">
                        <p class="tw-font-medium">
                            <a href="#" onclick="slideToggle('#project_vault_entries'); return false;"
                                class="tw-inline-flex tw-items-center tw-space-x-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="tw-w-5 tw-h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                                </svg>
                                <span>
                                    <?php echo _l('project_shared_vault_entry_login_details'); ?>
                                </span>
                            </a>
                        </p>
                        <div id="project_vault_entries"
                            class="hide tw-mb-4 tw-bg-neutral-50 tw-px-4 tw-py-2 tw-rounded-md">
                            <?php foreach ($project->shared_vault_entries as $vault_entry) { ?>
                            <div class="tw-my-3">
                                <div class="row" id="<?php echo 'vaultEntry-' . $vault_entry['id']; ?>">
                                    <div class="col-md-6">
                                        <p class="mtop5">
                                            <b><?php echo _l('server_address'); ?>:
                                            </b><?php echo $vault_entry['server_address']; ?>
                                        </p>
                                        <p class="tw-mb-0">
                                            <b><?php echo _l('port'); ?>:
                                            </b><?php echo !empty($vault_entry['port']) ? $vault_entry['port'] : _l('no_port_provided'); ?>
                                        </p>
                                        <p class="tw-mb-0">
                                            <b><?php echo _l('vault_username'); ?>:
                                            </b><?php echo $vault_entry['username']; ?>
                                        </p>
                                        <p class="no-margin">
                                            <b><?php echo _l('vault_password'); ?>: </b><span
                                                class="vault-password-fake">
                                                <?php echo str_repeat('&bull;', 10); ?> </span><span
                                                class="vault-password-encrypted"></span> <a href="#"
                                                class="vault-view-password mleft10" data-toggle="tooltip"
                                                data-title="<?php echo _l('view_password'); ?>"
                                                onclick="vault_re_enter_password(<?php echo $vault_entry['id']; ?>,this); return false;"><i
                                                    class="fa fa-lock" aria-hidden="true"></i></a>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <?php if (!empty($vault_entry['description'])) { ?>
                                        <p class="tw-mb-0">
                                            <b><?php echo _l('vault_description'); ?>:
                                            </b><br /><?php echo $vault_entry['description']; ?>
                                        </p>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } ?>
					
				

                    <div class="col-md-6 project-overview-left">
                        <h4 class="tw-font-semibold tw-text-base tw-mb-4">
                            <?php echo _l('project_overview'); ?>
                        </h4>
                        <dl class="tw-grid tw-grid-cols-1 tw-gap-x-4 tw-gap-y-5 sm:tw-grid-cols-2">
                                    <div class="tw-flex tw-space-x-4">
            <p class="bold"><?php echo _l('project'); ?></p>
            <p><?php echo _l('the_number_sign'); ?><?php echo $project->id; ?></p>
        </div>


							
			<div class="tw-flex tw-space-x-4">
            <p class="bold"> <?php echo _l('project_customer'); ?></p>
            <p><a
                                        href="<?php echo admin_url(); ?>clients/client/<?php echo $project->clientid; ?>">
                                        <?php echo $project->client_data->company; ?>
                                    </a></p>
        </div>
							
							
							

                            <?php if (has_permission('projects', '', 'edit')) { ?>
                           
        <?php if (($project->billing_type == 1 || $project->billing_type == 2) && $project->settings->view_finance_overview == 1) {
            echo '<div class="project-cost tw-flex tw-space-x-4">';
            if ($project->billing_type == 1) {
                echo '<p class="bold">' . _l('project_total_rate') . '</p>';
                echo '<p>' . app_format_money($project->project_cost, $currency) . '</p>';
            } else {
                echo '<p class="bold">' . _l('project_rate_per_hour') . '</p>';
                echo '<p>' . app_format_money($project->project_rate_per_hour, $currency) . '</p>';
            }
            echo '</div>';
        }
        ?>
                            <?php  } ?>

        <div class="tw-flex tw-space-x-4">
            <p class="bold"><?php echo _l('project_status'); ?></p>
            <p><?php
            if ($project_status['name'] == 'In Progress') {
                $labelStyles = 'label label-primary';
            } elseif ($project_status['name'] == 'For Review') {
                $labelStyles = 'label label-warning';
            } elseif ($project_status['name'] == 'Done') {
                $labelStyles = 'label label-success';
            } else {
// Handle the case if none of the specified values match
$labelStyles = ''; // Set a default value or handle it accordingly
}
?>

<span class="<?php echo $labelStyles; ?>"><?php echo $project_status['name']; ?></span></p>
</div>

   <div class="tw-flex tw-space-x-4">
    <p class="bold"><?php echo _l('project_start_date'); ?></p>
    <p><?php echo _d($project->start_date); ?></p>
</div>
<?php if ($project->deadline) { ?>
    <div class="tw-flex tw-space-x-4">
        <p class="bold"><?php echo _l('project_deadline'); ?></p>
        <p><?php echo _d($project->deadline); ?></p>
    </div>
<?php } ?>
<?php if ($project->date_finished) { ?>
    <div class="text-success tw-flex tw-space-x-4">
        <p class="bold"><?php echo _l('project_completed_date'); ?></p>
        <p><?php echo _d($project->date_finished); ?></p>
    </div>
<?php } ?>

<?php $custom_fields = get_custom_fields('projects', ['show_on_client_portal' => 1]);
if (count($custom_fields) > 0) { ?>
    <?php foreach ($custom_fields as $field) { ?>
        <?php $value = get_custom_field_value($project->id, $field['id'], 'projects');
        if ($value == '') {
            continue;
        } ?>
        <div class="tw-flex tw-space-x-4">
            <p class="bold"><?php echo ucfirst($field['name']); ?></p>
            <p><?php echo $value; ?></p>
        </div>
    <?php } ?>
<?php } ?>



                           


                            <?php $custom_fields = get_custom_fields('projects');
         if (count($custom_fields) > 0) { ?>
                            <?php foreach ($custom_fields as $field) { ?>
                            <?php $value = get_custom_field_value($project->id, $field['id'], 'projects');
         if ($value == '') {
             continue;
         } ?>
                            <div class="sm:tw-col-span-1">
                                <dt class="tw-text-sm tw-font-medium tw-text-neutral-500">
                                    <?php echo ucfirst($field['name']); ?>
                                </dt>
                                <dd class="tw-mt-1 tw-text-sm tw-text-neutral-900">
                                    <?php echo $value; ?>
                                </dd>
                            </div>
                            <?php } ?>
                            <?php } ?>

                            <?php $tags = get_tags_in($project->id, 'project'); ?>
                            <?php if (count($tags) > 0) { ?>
                            <div class="sm:tw-col-span-1 project-overview-tags">
                                <dt class="tw-text-sm tw-font-medium tw-text-neutral-500">
                                    <?php echo _l('tags'); ?>
                                </dt>
                                <dd class="tags-read-only-custom tw-mt-1 tw-text-sm tw-text-neutral-900">
                                    <input type="text" class="tagsinput read-only" id="tags" name="tags"
                                        value="<?php echo prep_tags_input($tags); ?>" data-role="tagsinput">
                                </dd>
                            </div>
                            <?php } ?>
                            <div class="clearfix"></div>
						 </dl>
                    </div>
					
					
	<div class="col-md-6">				
    <div class="project-progress-bars tw-mb-3">
        <div class="tw-rounded-md tw-border tw-border-solid tw-border-neutral-100 tw-bg-neutral-50 tw-py-2 tw-px-3">
            <div class="row">
                <div class="col-md-9">
                    <p class="bold text-dark font-medium tw-mb-0">
                                        <span dir="ltr"><?php echo $tasks_not_completed; ?> /
                                            <?php echo $total_tasks; ?></span>
                                        <?php echo _l('project_open_tasks'); ?>
                                    </p>
                                    <p class="text-muted bold tw-mb-0"><?php echo $tasks_not_completed_progress; ?>%</p>
                                </div>
                                <div class="col-md-3 text-right">
                                    <i class="fa-regular fa-check-circle<?php echo $tasks_not_completed_progress >= 100 ? ' text-success' : ' text-muted'; ?>"
                                        aria-hidden="true"></i>
                                </div>
                                <div class="col-md-12 mtop5">
                                    <div class="progress no-margin progress-bar-mini">
                                        <div style="width: <?php echo $tasks_not_completed_progress; ?>%;" class="progress-bar progress-bar-success no-percent-text not-dynamic"
                                            role="progressbar"
                                            aria-valuenow="<?php echo $tasks_not_completed_progress; ?>"
                                            aria-valuemin="0" aria-valuemax="100" style="width: 0%"
                                            data-percent="<?php echo $tasks_not_completed_progress; ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                   <!-- </div>-->
                </div>
            
			
			
            <?php if ($project->deadline) { ?>
			
    <div class="project-progress-bars tw-mb-3">
        <div class="tw-rounded-md tw-border tw-border-solid tw-border-neutral-100 tw-bg-neutral-50 tw-py-2 tw-px-3">
            <div class="row">
                <div class="col-md-9">
                    <p class="bold text-dark font-medium tw-mb-0">
                                    <span dir="ltr"><?php echo $project_days_left; ?> /
                                        <?php echo $project_total_days; ?></span>
                                    <?php echo _l('project_days_left'); ?>
                                </p>
                                <p class="text-muted bold tw-mb-0"><?php echo $project_time_left_percent; ?>%</p>
                            </div>
                            <div class="col-md-3 text-right">
                                <i class="fa-regular fa-calendar-check<?php echo $project_time_left_percent >= 100 ? ' text-success' : ' text-muted'; ?>"
                                    aria-hidden="true"></i>
                            </div>
                            <div class="col-md-12 mtop5">
                                <div class="progress no-margin progress-bar-mini">
                                    <div style="width: <?php echo $project_time_left_percent; ?>%;" class="progress-bar<?php if ($project_time_left_percent == 0) {
             echo ' progress-bar-warning ';
         } else {
             echo ' progress-bar-success ';
         } ?>		 no-percent-text not-dynamic" role="progressbar" aria-valuenow="<?php echo $project_time_left_percent; ?>" aria-valuemin="0" aria-valuemax="100" style="width: 0%" data-percent="<?php echo $project_time_left_percent; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            <!--    </div>-->
            </div>
          
			<?php } ?>

        <?php if (has_permission('projects', '', 'create')) { ?>
        
        <?php } ?>
        
    </div>



	
	</div>				
					
					
					
					
					
					
					
					
					
					
					
					
					
					
							<div class="col-md-12">
                            <div class="sm:tw-col-span-2 project-overview-description tc-content">
                                <dt class="tw-text-sm tw-font-medium tw-text-neutral-500">
                                    <?php echo _l('project_description'); ?>
                                </dt>
                                <dd class="tw-mt-1 tw-space-y-5 tw-text-sm tw-text-neutral-900">
                                    <?php if (empty($project->description)) { ?>
                                    <p class="text-muted tw-mb-0">
                                        <?php echo _l('no_description_project'); ?>
                                    </p>
                                    <?php } ?>
                                    <?php echo check_for_links($project->description); ?>
                                </dd>
                            </div>
							</div>					
					
					
<div class="clearfix"></div>
<hr>
<div class="col-md-12">
<h4 class="tw-font-semibold tw-text-base tw-mt-0 tw-mb-4"><?php echo _l('project_milestone_current'); ?></h4>
                   
<table class="table dt-table table-milestones" data-order-col="0" data-order-type="asc">
    <thead>
        <tr>
        <?php foreach($milestones as $key=>$milestone){ 
        if($key == 0){$start_date = _d($milestone['start_date']);}
        ?>
             <th><?php echo $milestone['name']; ?></th>
             <?php } ?>
        </tr>
    </thead>
    <tbody>

<tr>
<td colspan="<?php echo count($milestones);?>">

<div class="progress"><span class="progress-bar-success" style="float:left;color:white;height:24px;padding-left:5px;"><?php echo $start_date;?></span><span class="" style="float:right;color:green;height:24px;padding-right:5px;"><?php echo $start_date;?></span>
    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $tasks_not_completed_progress; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $tasks_not_completed_progress; ?>%;">
        <span><?php echo $tasks_not_completed_progress; ?>%</span>
    </div>
</div>
</td>
</tr>
</tbody>
</table>
                
<div class="clearfix"></div>
<hr>
<h4 class="tw-font-semibold tw-text-base tw-mt-0 tw-mb-4"><?php echo _l('project_gant'); ?></h4>
<?php if ($project->settings->view_gantt == 1 && $project->settings->available_features['project_gantt'] == 1) {               
include __DIR__ .'/project_gantt.php';}
?>						
</div>					
					
					
					
					
					
                </div>
            </div>
			
			

		
			
</div>
</div>
	
	

<?php if (isset($project_overview_chart)) { ?>
<script>
var project_overview_chart = <?php echo json_encode($project_overview_chart); ?>;
</script>
<?php } ?>