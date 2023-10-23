<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php require_once(__DIR__ .'/css.php');?>
<h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 section-heading section-heading-meetings">
    <?php echo _l('scheduled_meetings_dashboard'); ?>
</h4>
<div class="panel_s">
    <div class="panel-body">

        <table class="table dt-table table-meetings" data-order-col="1" data-order-type="desc">
            <thead>
                <tr>
                    <th class="th-meeting-number">MeetingID</th>
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
<?php foreach ($meetings as $meeting) {
$contact_ids = explode(',',$meeting['contact_ids']);	
if(in_array(get_contact_user_id(),$contact_ids)){
				?>
				
<!--
<span class="badge badge-success">2</span>
<span class="badge badge-warning">4</span>
<span class="badge badge-error">6</span>
<span class="badge badge-info">8</span>
<span class="badge badge-inverse">10</span>	-->			
				
<?php if($meeting['status'] == 0){
$status = '<span class="label label-warning">CANCELLED</span>';//cancelled
}else if($meeting['status'] == 1){
$status = '<span class="label label-primary">NEW</span>';//new
}else if($meeting['status'] == 2){
$status = '<span class="label label-success">UPCOMING</span>';//rescheduled
}else{
$status = '<span class="label label-warning">DONE</span>';//done
}?>
                <tr>
                    <td data-order="<?php echo $meeting['id']; ?>"><?php echo $meeting['meetingid']; ?></td>
                    <td data-order="<?php echo $meeting['meeting_date']; ?>"><?php echo $meeting['meeting_date']; ?></td>
					<td data-order="<?php echo $meeting['topic']; ?>"><?php echo $meeting['topic']; ?></td>
					<td data-order="<?php echo $meeting['category']; ?>"><?php echo $meeting['category']; ?></td>
					<td data-order="<?php echo $meeting['status']; ?>"><?php echo $status; ?></td>
					<td data-order="view_meeting"><a href="<?php echo admin_url('scheduled_meetings/clients/meeting_details/'.$meeting['id']);?>"><span class="label label-default" >View Meeting</span>
</a></td>
                    <?php foreach ($custom_fields as $field) { ?>
                    <td><?php echo get_custom_field_value($meeting['id'], $field['id'], 'meeting'); ?></td>
                    <?php } ?>
                </tr>
                <?php } }?>
            </tbody>
        </table>
    </div>
</div>