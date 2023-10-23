<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="row mtop15"style="color: #9ba1c4!important;">
    <div class="col-md-6">
        <h4 class="tw-font-semibold tw-text-base tw-mt-0 tw-mb-4">
            <?php echo _l('project_overview'); ?>
        </h4>
        <div class="tw-flex tw-space-x-4" >
            <p class="bold"><?php echo _l('project'); ?></p>
            <p><?php echo _l('the_number_sign'); ?><?php echo $project->id; ?></p>
        </div>

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

</div>
<div class="col-md-6">
  
<?php if ($project->settings->view_tasks == 1) { ?>
    <div class="project-progress-bars tw-mb-3">
        <div class="tw-rounded-md tw-border tw-border-solid  tw-py-2 tw-px-3" style="background-color: rgba(93, 91, 133, 0.56); border-color: #323761;">
            <div class="row">
                <div class="col-md-9" >
                    <p class="bold  font-medium tw-mb-0">
                        <span dir="ltr"><?php echo $tasks_not_completed; ?> / <?php echo $total_tasks; ?></span>
                        <?php echo _l('project_open_tasks'); ?>
                    </p>
                    <p class="tw-text-neutral-600 tw-font-medium"><?php echo $tasks_not_completed_progress; ?>%</p>
                </div>
                <div class="col-md-3 text-right">
                    <i class="fa-regular fa-check-circle<?php echo $tasks_not_completed_progress >= 100 ? ' text-success' : ' text-muted'; ?>"
                        aria-hidden="true"></i>
                    </div>
                    <div class="col-md-12">
                        <div class="progress tw-my-0 progress-bar-mini">
                            <div class="progress-bar progress-bar-success no-percent-text not-dynamic"
                            role="progressbar" aria-valuenow="<?php echo $tasks_not_completed_progress; ?>"
                            aria-valuemin="0" aria-valuemax="100" style="width: 0%"
                            data-percent="<?php echo $tasks_not_completed_progress; ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($project->deadline) { ?>
    <div class="project-progress-bars">
        <div class="tw-rounded-md tw-border tw-border-solid tw-py-2 tw-px-3" style="background-color: rgba(93, 91, 133, 0.56); border-color: #323761;">
            <div class="row">
                <div class="col-md-9">
                    <p class="bold text-dark font-medium tw-mb-0">
                        <span dir="ltr"><?php echo $project_days_left; ?> /
                            <?php echo $project_total_days; ?></span>
                            <?php echo _l('project_days_left'); ?>
                        </p>
                        <p class="tw-text-neutral-600 tw-font-medium"><?php echo $project_time_left_percent; ?>%</p>
                    </div>
                    <div class="col-md-3 text-right">
                        <i class="fa-regular fa-calendar-check<?php echo $project_time_left_percent >= 100 ? ' text-success' : ' text-muted'; ?>"
                            aria-hidden="true"></i>
                        </div>
                        <div class="col-md-12">
                            <div class="progress tw-my-0 progress-bar-mini">
                                <div class="progress-bar<?php echo $project_time_left_percent == 0 ? ' progress-bar-warning ' : ' progress-bar-success '; ?>no-percent-text not-dynamic"
                                    role="progressbar" aria-valuenow="<?php echo $project_time_left_percent; ?>"
                                    aria-valuemin="0" aria-valuemax="100" style="width: 0%"
                                    data-percent="<?php echo $project_time_left_percent; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

    </div>

    <div class="clearfix"></div>

    <?php if ($project->settings->view_finance_overview == 1) { ?>
        <div class="col-md-12 project-overview-column">
            <div class="row">
                <div class="col-md-12">
                   <hr style="background-color: #323761; border-color: #323761;">

                    <?php
                    if ($project->billing_type == 3 || $project->billing_type == 2) { ?>
                        <div class="row">
                            <div class="col-md-3">
                                <?php
                                $data = $this->projects_model->total_logged_time_by_billing_type($project->id);
                                ?>
                                <p class="tw-mb-0 text-muted">
                                    <?php echo _l('project_overview_logged_hours'); ?>
                                    <span class="bold"><?php echo $data['logged_time']; ?></span>
                                </p>
                                <p class="tw-font-medium tw-text-neutral-600 tw-mb-0">
                                    <?php echo app_format_money($data['total_money'], $currency); ?>
                                </p>
                            </div>
                            <div class="col-md-3">
                                <?php
                                $data = $this->projects_model->data_billable_time($project->id);
                                ?>
                                <p class="text-info tw-mb-0">
                                    <?php echo _l('project_overview_billable_hours'); ?>
                                    <span class="bold"><?php echo $data['logged_time'] ?></span>
                                </p>
                                <p class="tw-font-medium tw-text-neutral-600 tw-mb-0">
                                    <?php echo app_format_money($data['total_money'], $currency); ?>
                                </p>
                            </div>
                            <div class="col-md-3">
                                <?php
                                $data = $this->projects_model->data_billed_time($project->id);
                                ?>
                                <p class="text-success tw-mb-0">
                                    <?php echo _l('project_overview_billed_hours'); ?>
                                    <span class="bold"><?php echo $data['logged_time']; ?></span>
                                </p>
                                <p class="tw-font-medium tw-text-neutral-600 tw-mb-0">
                                    <?php echo app_format_money($data['total_money'], $currency); ?>
                                </p>
                            </div>
                            <div class="col-md-3">
                                <?php
                                $data = $this->projects_model->data_unbilled_time($project->id);
                                ?>
                                <p class="text-danger tw-mb-0">
                                    <?php echo _l('project_overview_unbilled_hours'); ?>
                                    <span class="bold"><?php echo $data['logged_time']; ?></span>
                                </p>
                                <p class="tw-font-medium tw-text-neutral-600 tw-mb-0">
                                    <?php echo app_format_money($data['total_money'], $currency); ?>
                                </p>
                            </div>
                        </div>
                        <hr style="background-color: #323761; border-color: #323761;">

                    <?php } ?>
                </div>
            </div>
            <?php } ?>

                <div class="clearfix"></div>
                <div class="col-md-12">
                    <h4 class="tw-font-semibold tw-text-base tw-mt-0 tw-mb-4" ><?php echo _l('project_description'); ?></h4>
                    <div class="tc-content project-description">
                        <?php if (empty($project->description)) { ?>
                            <p class="text-center tw-mb-0">
                                <?php echo _l('no_description_project'); ?>
                            </p>
                        <?php }
                        echo check_for_links($project->description);
                        ?>
                    </div>
                </div>
                <div class="clearfix"></div>
                <hr style="background-color: #323761; border-color: #323761;">

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

<?php if ($project->settings->view_tasks == 1) {

 ?>
<div class="progress tw-h-6 align-middle" style="margin-bottom:0px;"><span class="progress-bar-success" style="float:left;color:white;height:24px;padding-left:5px;"><?php echo $start_date;?></span><span class="" style="float:right;color:green;height:24px;padding-right:5px;"><?php echo $start_date;?></span>
<div class="progress-bar progress-bar-success not-dynamic" role="progressbar" aria-valuenow="<?php echo $tasks_not_completed_progress; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $tasks_not_completed_progress; ?>%; position: relative;" data-percent="<?php echo $tasks_not_completed_progress; ?>">hello
</div>
</div>
<?php } ?>

</td>
</tr>

</tbody>
</table>
                   
                    
                    
                    <div class="clearfix"></div>
                    <hr style="background-color: #323761; border-color: #323761;">

                    <h4 class="tw-font-semibold tw-text-base tw-mt-0 tw-mb-4"><?php echo _l('project_gant'); ?></h4>
<?php if ($project->settings->view_gantt == 1 && $project->settings->available_features['project_gantt'] == 1) {               
include __DIR__ .'/project_gantt.php';}
?>
                </div>


            </div>