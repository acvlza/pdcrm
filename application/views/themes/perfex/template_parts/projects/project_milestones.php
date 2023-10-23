<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<a href="#" class="btn btn-default pull-left milestone-table-toggle" id="mstones" style="margin-bottom:10px;">
    <i class="fa fa-th-list"></i>
</a>
<div class="clearfix"></div>
<hr style="Border : 1px solid #323761" />

<div class="div-milestones hide">
<table class="table dt-table table-milestones" data-order-col="0" data-order-type="asc">
    <thead>
        <tr>
            <th class="hidden"></th>
            <th width="20%"><?php echo _l('milestone_name'); ?></th>
            <th width="10%"><?php echo _l('milestone_start_date'); ?></th>
            <th width="10%"><?php echo _l('milestone_due_date'); ?></th>
            
        </tr>
    </thead>
    <tbody>
        <?php foreach($milestones as $milestone){ ?>
            <tr>
                <td class="hide" data-order="<?php echo $milestone['milestone_order']; ?>"></td>
                <td><?php echo $milestone['name']; ?></td>
                
                <td data-order="<?php echo $milestone['start_date']; ?>"><?php echo _d($milestone['start_date']); ?></td>
                <td data-order="<?php echo $milestone['due_date']; ?>"><?php echo _d($milestone['due_date']); ?></td>
                
            </tr>
        <?php } ?>
    </tbody>
</table>
</div>



<div class="milestone-phases">
    <?php
      $milestones   = [];
      $milestones[] = [
       'name'                 => _l('milestones_uncategorized'),
       'id'                   => 0,
       'total_logged_time'    => $this->projects_model->calc_milestone_logged_time($project->id, 0),
       'total_tasks'          => total_project_tasks_by_milestone(0, $project->id),
       'total_finished_tasks' => total_project_finished_tasks_by_milestone(0, $project->id),
       'color'                => null,
      ];
      $_milestones = $this->projects_model->get_milestones($project->id, ['hide_from_customer' => 0]);
      foreach ($_milestones as $m) {
          $milestones[] = $m;
      }
      ?>
    <div class="kan-ban-row">
        <?php foreach ($milestones as $milestone) {
          $tasks   = $this->projects_model->get_tasks($project->id, ['milestone' => $milestone['id']]);
          $percent = 0;
          if ($milestone['total_finished_tasks'] >= floatval($milestone['total_tasks'])) {
              $percent = 100;
          } else {
              if ($milestone['total_tasks'] !== 0) {
                  $percent = number_format(($milestone['total_finished_tasks'] * 100) / $milestone['total_tasks'], 2);
              }
          }
          $milestone_color = '';
          if (!empty($milestone['color']) && !is_null($milestone['color'])) {
              $milestone_color = ' style="background:' . $milestone['color'] . ';border:1px solid ' . $milestone['color'] . '"';
          } ?>
        <div class="kan-ban-col<?php if ($milestone['id'] == 0 && count($tasks) == 0) {
              echo ' hide';
          } ?>">
            <div  style="min-height:22px;background-color:#0A1A8F !important; border-bottom: #0A1A8F; color: #9ba1c4;" class="panel-heading">
                <?php if ($milestone['id'] != 0 && $milestone['description_visible_to_customer'] == 1) { ?>
                <i class="fa fa-file-text pointer" aria-hidden="true" data-toggle="popover"
                    data-title="<?php echo _l('milestone_description'); ?>" data-html="true"
                    data-content="<?php echo htmlspecialchars($milestone['description']); ?>"></i>&nbsp;
                <?php } ?>
                <span class="bold tw-text-sm"><?php echo $milestone['name']; ?></span>
                <span class="tw-text-xs">
                    <?php echo $milestone['id'] != 0 ? (' | ' . _d($milestone['start_date']) . ' - ' . _d($milestone['due_date'])) : ''; ?>
                </span>
                
            </div>
            <div class="panel-body" style="min-height:220px; background: #323761!important;">
                <?php
               if (count($tasks) == 0) {
                   echo _l('milestone_no_tasks_found');
               }
          foreach ($tasks as $task) { ?>
                <div class="media _task_wrapper<?php if ((!empty($task['duedate']) && $task['duedate'] < date('Y-m-d')) && $task['status'] != Tasks_model::STATUS_COMPLETE) {
              echo ' overdue-task';
          } ?>">
                    <div style="background-color: #323761 !important;">
                        <a href="<?php echo site_url('clients/project/' . $project->id . '?group=project_tasks&taskid=' . $task['id']); ?>"
                            class="task_milestone tw-mb-1 pull-left<?php if ($task['status'] == Tasks_model::STATUS_COMPLETE) {
              echo ' line-throught text-muted';
          } ?>">
                            <?php echo $task['name']; ?>

                        </a>

                        <?php if (
                     $project->settings->edit_tasks == 1 &&
                     $task['is_added_from_contact'] == 1 &&
                     $task['addedfrom'] == get_contact_user_id() &&
                     $task['billed'] == 0
                     ) { ?>
                        <a href="<?php echo site_url('clients/project/' . $project->id . '?group=edit_task&taskid=' . $task['id']); ?>"
                            class="pull-right">
                            <small><i class="fa-regular fa-pen-to-square"></i></small>
                        </a>
                        <?php } ?>
                        <div class="clearfix"></div>
                        <p class="text-xs tw-mb-0">
                            <?php echo format_task_status($task['status'], true); ?>
                        </p>
                        <p class="tw-mb-0 tw-text-xs tw-text-neutral-500"><?php echo _l('tasks_dt_datestart'); ?>:
                            <b><?php echo _d($task['startdate']); ?></b>
                        </p>
                        <?php if (is_date($task['duedate'])) { ?>
                        <p class="tw-mb-0 tw-text-xs tw-text-neutral-500">
                            <?php echo _l('task_duedate'); ?>: <b><?php echo _d($task['duedate']); ?></b>
                        </p>
                        <?php } ?>
                    </div>
                </div>
                <?php } ?>
            </div>
            <div class="panel-footer" style="background-color: #0A1A8F!important; border-top: #0A1A8F ;" >
                <div class="progress tw-my-0">
                    <div class="progress-bar progress-bar-default" role="progressbar" aria-valuenow="40"
                        aria-valuemin="0" aria-valuemax="100" style="width: 0%" data-percent="<?php echo $percent; ?>">
                    </div>
                </div>
            </div>
        </div>
        <?php
      } ?>
    </div>
</div>
















<script>
$(function() {
document.getElementById('mstones').addEventListener('click', function() {
  $(".div-milestones").toggleClass("hide");
  $(".milestone-phases").toggleClass("hide");
});
});
</script>