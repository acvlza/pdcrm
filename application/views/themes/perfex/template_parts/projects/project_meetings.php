<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<style>
.badge {
  padding: 3px 11px 4px;
  font-size: 12.025px;
  font-weight: bold;
  white-space: nowrap;
  color: #ffffff;
  background-color: #70b9c5;
  -webkit-border-radius: 9px;
  -moz-border-radius: 9px;
  border-radius: 9px;
}
.badge:hover {
  color: #ffffff;
  text-decoration: none;
  cursor: pointer;
}
.badge-red {
  background-color: #FF3333;
}
.badge-error:hover {
  background-color: #953b39;
}
.badge-warning {
  background-color: #f89406;
}
.badge-warning:hover {
  background-color: #c67605;
}
.badge-success {
  background-color: #00CC00;
}
.badge-success:hover {
  background-color: #356635;
}
.badge-info {
  background-color: #3a87ad;
}
.badge-info:hover {
  background-color: #2d6987;
}
.badge-inverse {
  background-color: #333333;
}
.badge-blue {
  background-color: #9999FF;
}
.badge-purple {
  background-color: #CC00CC;
}
.badge-green {
  background-color: #009900;
}
.badge-orange {
  background-color: #FF7F2A;
}

.badge-inverse:hover {
  background-color: #1a1a1a;
}
</style>
<div class="panel_s">
    <div class="panel-body">

        <table class="table dt-table table-meetings" data-order-col="1" data-order-type="desc">
            <thead>
                <tr>
                    <th class="th-meeting-number">#</th>
                    <th class="th-meeting-date"><?php echo _l('scheduled_meetings_date'); ?></th>
                    <th class="th-meeting-topic"><?php echo _l('scheduled_meetings_topic_name'); ?></th>
                    <th class="th-meeting-category"><?php echo _l('scheduled_meetings_category'); ?></th>
                    <th class="th-meeting-status"><?php echo _l('scheduled_meetings_status'); ?></th>
					<th class="th-meeting-action"><?php echo _l('scheduled_meetings_action'); ?></th>
                    <?php
                $custom_fields = get_custom_fields('meeting', ['show_on_client_portal' => 1]);
                foreach ($custom_fields as $field) { ?>
                    <th><?php echo $field['name']; ?></th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($meetings as $meeting) { ?>
				
<!--
<span class="badge badge-success">2</span>
<span class="badge badge-warning">4</span>
<span class="badge badge-error">6</span>
<span class="badge badge-info">8</span>
<span class="badge badge-inverse">10</span>	-->			
				
<?php if($meeting['status'] == 0){
$status = '<span class="badge badge-red">CANCELLED</span>';//cancelled
}else if($meeting['status'] == 1){
$status = '<span class="badge badge-info">NEW</span>';//new
}else if($meeting['status'] == 2){
$status = '<span class="badge badge-warning">UPCOMING</span>';//rescheduled
}else{
$status = '<span class="badge badge-success">DONE</span>';//done
}?>
                <tr>
                    <td data-order="<?php echo $meeting['id']; ?>"><?php echo $meeting['id']; ?></td>
                    <td data-order="<?php echo $meeting['meeting_date']; ?>"><?php echo $meeting['meeting_date']; ?></td>
					<td data-order="<?php echo $meeting['topic']; ?>"><?php echo $meeting['topic']; ?></td>
					<td data-order="<?php echo $meeting['category']; ?>"><?php echo $meeting['category']; ?></td>
					<td data-order="<?php echo $meeting['status']; ?>"><?php echo $status; ?></td>
					<td data-order="view_meeting"><a href="<?php echo admin_url('scheduled_meetings/clients/meeting_details/'.$meeting['id']);?>"><span class="badge" style="background-color: rgba(93, 91, 133, 0.56);">View Meeting</span></a></td>
                    <?php foreach ($custom_fields as $field) { ?>
                    <td><?php echo get_custom_field_value($meeting['id'], $field['id'], 'meeting'); ?></td>
                    <?php } ?>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>