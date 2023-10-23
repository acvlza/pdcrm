<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <form id="meeting-form" name="meeting-form" action="<?= admin_url('scheduled_meetings/create'); ?>" method="POST">
			<input id="project" name="project" class="project" type="hidden">
			<input id="category" name="category" class="category" type="hidden">
			<input id="notes" name="notes" class="notes" type="hidden">
			<input id="reminder" name="reminder" class="reminder" type="hidden">
                <div class="col-md-12">
                    <div class="panel_s">
                        <div class="panel-body">
                            <h4> <?= ($user->type == 1)
                                    ? '<i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" title="' . _l('zmm_participants_account_info') . '"></i>'
                                    : ''; ?> <?= _l('zmm_select_participants'); ?>
                                <small><?= _l('zmm_optional'); ?> </small>
                            </h4>
                            <hr class="hr-panel-heading">


                                <div class="col-md-12 no-padding">
                                <h4><?= _l('zmm_create_meeting'); ?></h4>
                                <span><?= _l('zmm_create_note'); ?></span>
                                <hr>
                                <input type="hidden" name="<?php echo get_instance()->security->get_csrf_token_name(); ?>" value="<?php echo get_instance()->security->get_csrf_hash(); ?>">
                                <div class="col-md-6">
                                    <h4 class="mfont-bold-medium-size mtop1"><?= _l('zmm_general'); ?></h4>
                                    <hr>
                                    <div class="form-group">
                                        <label for="topic"><small class="req text-danger">* </small><?= _l('zmm_topic_label'); ?>
                                        </label>
                                        <input type="text" name="topic" class="form-control" id="topic" placeholder="<?= _l('zmm_topic_label'); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="description"><?= _l('zmm_description_label'); ?></label>
                                        <textarea name="description" class="form-control" id="description" placeholder="<?= _l('zmm_description_label'); ?>"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-group" app-field-wrapper="date">
                                            <label for="date" class="control-label">
                                                <small class="req text-danger">* </small><?= _l('zmm_when_date'); ?>
                                            </label>
                                            <div class="input-group date">
                                                <input type="text" id="date" name="date" class="form-control datetimepicker meeting-date" readonly="readonly" autocomplete="off">
                                                <div class="input-group-addon">
                                                    <i class="fa-light fa-calendars calendar-icon"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <h4 class="mfont-bold-medium-size"><?= _l('zmm_meeting_duration'); ?></h4>
                                    <hr>
                                    <div class="form-group">
                                        <div class="pull-left">
                                            <span><?= _l('zmm_hour'); ?></span>
                                            <select class="selectpicker" name="hour" id="metting_hours">
                                                <?php foreach (zmmGetHours() as $hour) { ?>
                                                    <option value="<?php echo $hour['value']; ?>">
                                                        <?php echo $hour['name']; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                            <label for="minutes"><?= _l('zmm_minutes'); ?></label>
                                            <select class="selectpicker" name="minutes" id="minutes">
                                                <option value="0">0</option>
                                                <option value="15">15</option>
                                                <option value="30" selected>30</option>
                                                <option value="45">45</option>
                                            </select>
                                        </div>
                                        <div class="timezone_parent pull-right">
										<label for="timezones" id="timezones_label" class="control-label"><?php echo _l('zmm_timezone'); ?></label>
                                            <select name="timezone" id="timezones" class="form-control selectpicker" data-live-search="true">
                                                <?php foreach (get_timezones_list() as $key => $timezones) { ?>
                                                    <optgroup label="<?php echo $key; ?>">
                                                        <?php foreach ($timezones as $timezone) { ?>
                                                            <option value="<?php echo $timezone; ?>"><?php echo $timezone; ?></option>
                                                        <?php } ?>
                                                    </optgroup>
                                                <?php } ?>
                                            </select>
                                            
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <hr>


                                    <input type="hidden" class="form-control pwd" name="password" maxlength="9"> 
                                    <!--<div class="input-group">
                                        <input type="password" class="form-control pwd" name="password" maxlength="9" placeholder="Password (optional) - Max 9 characters">
                                        <span class="input-group-btn">
                                                       <button class="btn btn-default reveal" type="button"><i class="glyphicon glyphicon-eye-open"></i></button>
                                                  </span>
                                    </div>-->
                                </div>
                                <div class="col-md-6">
                                    <h4 class="mfont-bold-medium-size mtop1"><?= _l('zmm_additional_settings'); ?></h4>
                                    <hr>
                                    <div class="form-group">
                                        <div class="checkbox checkbox-primary">
                                            <input type="checkbox" name="join_before_host" id="join_before_host">
                                            <label for="join_before_host"><?= _l('zmm_join_before_host'); ?></label>
                                        </div>
                                        <div class="checkbox checkbox-primary">
                                            <input type="checkbox" name="host_video" id="host_video">
                                            <label for="host_video"><?= _l('zmm_host_video'); ?></label>
                                        </div>
                                        <div class="checkbox checkbox-primary">
                                            <input type="checkbox" name="participant_video" id="participant_video">
                                            <label for="participant_video"><?= _l('zmm_participant_video'); ?></label>
                                        </div>
                                        <div class="checkbox checkbox-primary">
                                            <input type="checkbox" name="mute_upon_entry" id="mute_upon_entry">
                                            <label for="mute_upon_entry"><?= _l('zmm_mute_upon_entry'); ?></label>
                                        </div>
                                        <div class="checkbox checkbox-primary">
                                            <input type="checkbox" name="waiting_room" id="waiting_room">
                                            <label for="waiting_room"><?= _l('zmm_waiting_room'); ?></label>
                                        </div>
                                        <div class="ptop10">

                                        </div>
                                    </div>
                                </div>
								
								
								
                                    <div class="col-md-6">
                                        <div class="form-group" id="select_contacts">
                                            <input type="text" hidden id="rel_contact_type" value="contacts">
                                            <label for="rel_contact_id"><?= _l('zmm_contacts'); ?></label>
                                            <div id="rel_contact_id_select">
                                                <select name="contacts[]" id="rel_contact_id" multiple="true" class="ajax-search" data-width="100%" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                                    <?php
                                                    if ($rel_contact_id != '' && $rel_contact_type != '') {
                                                        $rel_cdata = get_relation_data($rel_contact_type, $rel_contact_id);
                                                        $rel_c_val = get_relation_values($rel_cdata, $rel_contact_type);
                                                        echo '<option value="' . $rel_val['id'] . '" selected>' . $rel_c_val['name'] . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group select-placeholder" id="rel_id_wrapper">
                                            <input type="text" hidden id="rel_lead_type" value="leads">
                                            <label for="rel_id"><?= _l('zmm_leads'); ?></label>
                                            <div id="rel_id_select">
                                                <select name="leads[]" id="rel_id" multiple="true" class="ajax-search" data-width="100%" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                                    <?php
                                                    if ($rel_id != '' && $rel_type != '') {
                                                        $rel_data = get_relation_data($rel_type, $rel_id);
                                                        $rel_val = get_relation_values($rel_data, $rel_type);
                                                        echo '<option value="' . $rel_val['id'] . '" selected>' . $rel_val['name'] . '</option>';
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
									
									
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <?php echo render_select('staff[]', $staff_members, ['staffid', ['firstname', 'lastname']], 'zmm_staff', [], ['multiple' => true], [], '', '', false); ?>
                                        </div>
                                    </div>
									
								
								
                                <div class="clearfix"></div>
                                <hr class="hr-panel-heading">
                                <a href="<?= admin_url('zoom_meeting_manager/index'); ?>" class="btn btn-default"><?= _l('zmm_back_to_meetings'); ?></a>
                                <button type="button" id="btnScheduleMeeting" class="btn btn-primary pull-right" style="background:#AA55FF;"><?= _l('zmm_shedule_label'); ?></button>
                                <?php
                                /**
                                 * User Types
                                 * 1 - Free
                                 * 2 - Licenced
                                 * 3 - On-perm
                                 */
                                if ($user->type == 1) : ?>
                                    <hr class="hr-panel-heading">
                                    <span><span class="label label-info"><?= _l('zmm_user_type') ?></span> - <?= _l('zmm_user_basic_info'); ?> </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
	
<?php
echo '<!-- Start Modal -->
<div class="modal fade" id="meetingModalConfirm" tabindex="-1" role="dialog" aria-labelledby="meetingModalConfirmTitle" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered" role="document">
<div class="modal-content">
<div class="modal-body text-center">
<div style="padding-top: 30px; padding-bottom: 30px; align-items: center;">
<div class="d-flex align-items-center justify-content-center mb-3">
<img class="rounded-circle" src="'.site_url('modules/scheduled_meetings/assets/ico-cal.png').'" alt="Generic placeholder image" height="100">
</div>
</div>
<h5 class="modal-title text-primary" >'. _l('scheduled_meetings_scheduled').'</h5>
<hr style ="border: 1px solid #323761;">
<p>'. _l('scheduled_meetings_scheduled_on').' <b><span id="dateandtime">date and time</span></b></p>
<p><b>'. _l('scheduled_meetings_attendees').':</b><span id="allattendees">Staff 1, Staff 2, Client 1.</span></p>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-primary waves-effect waves-light btn-block" data-dismiss="modal" aria-label="Close">Back</button>
</div>
</div>
</div>
</div>
<!-- End Modal -->';

echo '<!-- Start Modal -->
<div class="modal fade" id="meetingModalError" tabindex="-1" role="dialog" aria-labelledby="meetingModalErrorTitle" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered" role="document">
<div class="modal-content">
<div class="modal-body text-center">
<div style="padding-top: 30px; padding-bottom: 30px; align-items: center;">
<div class="d-flex align-items-center justify-content-center mb-3">
<img class="rounded-circle" src="'.site_url('modules/scheduled_meetings/assets/error.png').'" alt="Generic placeholder image" height="100">
</div>
</div>
<h5 class="modal-title text-primary" >'. _l('scheduled_meetings_could_not_be_scheduled').'</h5>
<hr>
<p>'. _l('scheduled_meetings_notify_admin').'</p>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-danger waves-effect waves-light btn-block" data-dismiss="modal" aria-label="Close">Close</button>
</div>
</div>
</div>
</div>
<!-- End Modal -->';		
	
?>
	
	
	
</div>
<?php init_tail(); ?>
<script>
$(document).ready(function() {
$('.dropdown.bootstrap-select.bs3').eq(0).width(70);
$('.dropdown.bootstrap-select.bs3').eq(1).width(70);
$('.dropdown.bootstrap-select.bs3').eq(2).width(122);
});

$("#btnScheduleMeeting").click(function(){
var datastring = $("#meeting-form").serialize();	
$.post("<?php echo admin_url('scheduled_meetings/create'); ?>", {
//topic: $('#db_user').val(),
data: datastring,
dataType: "json",
}, function(result){
const obj = JSON.parse(result);	
if(obj.msg == 'success'){
$("#dateandtime").text(obj.dateandtime);
$("#allattendees").text(obj.allattendees);
$('#meetingModalConfirm').modal('show');
}else{
$('#meetingModalError').modal('show');
}
});
});


</script>
<!-- Include create js functionality file -->
<?php require(FCPATH .'modules/zoom_meeting_manager/assets/js/create_js.php'); ?>

</body>

</html>
