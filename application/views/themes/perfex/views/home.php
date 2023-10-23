<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<style>
.progress-bar-mini {
height: 2.32rem;
border-radius: 0.5rem;
}
.tw-tracking-tight {
padding: 0.6rem 0 0.6rem 0;
display: flex;
align-items: center;
justify-content: center;
padding-left: 280%;
}
</style>
<h3 id="greeting" class="tw-font-semibold tw-mt-0"></h3>
<h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tickets-summary-heading">
<?php echo _l('projects_summary'); ?>
</h4>
<div class="tw-mb-2">
    <?php get_template_part('projects/project_summary'); ?>
</div>
<div class="panel_s">
    <div class="panel-body">
        <table class="table dt-table table-projects" data-order-col="2" data-order-type="desc">
            <thead>
                <tr>
                    <th class="th-project-name"><?php echo _l('project_name'); ?></th>
                    <th class="th-project-start-date"><?php echo _l('project_start_date'); ?></th>
                    <th class="th-project-deadline"><?php echo _l('project_deadline'); ?></th>
                    <th><?php echo _l('project_milestone_current'); ?></th>
                    <th><?php echo _l('project_progress'); ?></th>
                    <th><?php echo _l('project_status'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($projects as $project) { ?>
                <tr>
                    <td><a href="<?php echo site_url('clients/project/' . $project['id']); ?>"><?php echo $project['name']; ?> </a></td>
                    <td data-order="<?php echo $project['start_date']; ?>"><?php echo _d($project['start_date']); ?></td>
                    <td data-order="<?php echo $project['deadline']; ?>"><?php echo _d($project['deadline']); ?></td>
                    <td>
                        <?php
                        $currentDate = date('Y-m-d'); // Get the current system date
                        $milestones = get_project_milestones($project['id']);
                        $nearestMilestone = null; // Variable to store the nearest milestone
                        foreach ($milestones as $milestone) {
                        $startDate = $milestone['start_date'];
                        $dueDate = $milestone['due_date'];
                        if ($currentDate >= $startDate && $currentDate <= $dueDate) {
                        // Condition 1: If the current date is within the range of milestone start and due date
                        echo $milestone['name'] . "<br>";
                        } elseif ($currentDate < $startDate) {
                        // Condition 2: If the current date is before the milestone start date
                        if ($nearestMilestone === null || $startDate < $nearestMilestone['start_date']) {
                        $nearestMilestone = $milestone;
                        }
                        }
                        }
                        // Add a new condition to check if currentDate is past the last $milestone['due_date']
                        $lastMilestone = end($milestones); // Get the last milestone in the array
                        if ($lastMilestone !== false && $currentDate > $lastMilestone['due_date']) {
                        $nearestMilestone = $lastMilestone;
                        }
                        if ($nearestMilestone !== null) {
                        // Echo the name of the nearest milestone
                        echo $nearestMilestone['name'] . "<br>";
                        }
                        ?>
                    </td>
                    <td>
                        <div class="progress tw-h-6 align-middle" style="margin-bottom:0px;">
                            <div class="progress-bar progress-bar-success not-dynamic" role="progressbar" aria-valuenow="<?php echo $project['progress']; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $project['progress']; ?>%; position: relative;" data-percent="<?php echo $project['progress']; ?>">
                            </div>
                        </div>
                    </td>
                    <?php foreach ($custom_fields as $field) { ?>
                    <td><?php echo get_custom_field_value($project['id'], $field['id'], 'projects'); ?></td>
                    <?php } ?>
                    <td>
                        <?php
                        $status = get_project_status_by_id($project['status']);
                        if ($status['id'] == 1) {
                        $labelStyles = 'label label-primary';
                        } elseif ($status['id'] == 2) {
                        $labelStyles = 'label label-warning';
                        } elseif ($status['id'] == 3) {
                        $labelStyles = 'label label-success';
                        }
                        echo '<span class="' . $labelStyles . '">' . $status['name'] . '</span>';
                        ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<script>
$(function() {
var greetDate = new Date();
var hrsGreet = greetDate.getHours();
var greet;
if (hrsGreet < 12)
greet = "<?php echo _l('good_morning'); ?>";
else if (hrsGreet >= 12 && hrsGreet <= 17)
greet = "<?php echo _l('good_afternoon'); ?>";
else if (hrsGreet >= 17 && hrsGreet <= 24)
greet = "<?php echo _l('good_evening'); ?>";
if (greet) {
document.getElementById('greeting').innerHTML =
'<b>' + greet + ' <?php echo $contact->firstname; ?>!</b>';
}
var initialProgress = parseFloat($('input[name="progress"]').val());
$('.project_progress_slider_horizontal').slider({
min: 0,
max: 100,
value: initialProgress,
slide: function(event, ui) {
$('input[name="progress"]').val(ui.value);
$('.label_progress').html(ui.value + '%');
}
});
$('.project_progress_slider_horizontal').on('slidestop', function(event, ui) {
$('input[name="progress"]').val(ui.value);
$('.label_progress').html(ui.value + '%');
});
});
</script>