<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php require_once(__DIR__ .'/css.php');?>
<h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 section-heading section-heading-meetings">
    <?php echo _l('scheduled_meetings_meeting_details'); ?>
</h4>


<div class="panel_s col-md-6 box-left" style="min-height:650px;">
<div class="panel-body">
<div class="col-md-12" id="meetingModal" tabindex="-1" aria-labelledby="meetingModalTitle" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered" role="document" style="max-width:500px;">
<div class="content">
<div class="row">
<div class="col-md-12">
<div class="form-group">
<label for="simpleinput"><?php echo _l('scheduled_meetings_topic');?></label>
<input type="text" id="topic" name="topic" class="form-control" value="<?php echo $meeting->topic;?>" placeholder="Topic Name Here">
</div>
<div class="form-group">
<label for="simpleinput"><?php echo _l('scheduled_meetings_agenda');?></label>
<textarea class="form-control" id="agenda" name="agenda" rows="3"><?php echo $meeting->agenda;?></textarea>
</div>
<div class="form-group">
<label for="simpleinput"><?php echo _l('scheduled_meetings_meeting_category');?></label>
<select class="form-control" data-toggle="select" id="category" name="category">
<option value="New Project"<?php if($meeting->category=='New Project'){echo ' selected';}?>>New Project</option>
<option value="Existing Project"<?php if($meeting->category=='Existing Project'){echo ' selected';}?>>Existing Project</option>
<option value="General Inquiry"<?php if($meeting->category=='General Inquiry'){echo ' selected';}?>>General Inquiry</option>
</select>
</div>


<?php if($meeting->project_id != ''){
$this->db->where('id', $meeting->project_id);
$result = $this->db->get(db_prefix(). 'projects')->row();
$project_name = $result->name;
?>
<div class="form-group">
<label>Related To Project</label><br />
<label class="col-md-12" style="border:1px solid #E1E1E1;padding:6px 10px;border-radius:5px;"><a target="_blank" href="<?php echo site_url('clients/project/'.$meeting->project_id);?>"><?php echo $project_name;?></a></label>
<!--<input type="text" id="project" name="project" class="form-control" value="" placeholder="">-->
</div>
<?php }?>










<div class="form-group">
<label for="simpleinput"><?php echo _l('scheduled_meetings_time_and_date');?></label>
<div class="row">
<div class="col-sm-12"><input type="text" id="date" name="date" class="form-control datetimepicker meeting-date" value="<?php echo $meeting->meeting_date;?>" readonly="readonly" autocomplete="off">
</div>
</div>
</div>
</div>
</div>
<div class="footer">
<label>Zoom Link</label>
<div class="clearfix"></div>
<div class="col-sm-6"><a target="_blank" href="<?php echo $meeting->web_url;?>" type="button" id="request_meeting" class="btn btn-primary waves-effect waves-light btn-block" style="background:#CBF5FB;border-radius:5px;color:#11C5DD;border:1px solid #11C5DD;"><?php echo _l('scheduled_meetings_join_web_url');?></a></div><div class="col-sm-6"><a target="_blank" href="<?php echo $meeting->app_url;?>" type="button" id="request_meeting" class="btn btn-primary waves-effect waves-light btn-block" style="background:#CBF5FB;border-radius:5px;color:#11C5DD;border:1px solid #11C5DD;"><?php echo _l('scheduled_meetings_join_app_url');?></a></div>
</div>
</div>
</div>
</div>
</div>
</div>






<div class="panel_s col-md-6 box-right" style="min-height:650px;">
<div class="panel-body">
<div class="col-md-12" id="meetingModal" tabindex="-1" aria-labelledby="meetingModalTitle" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered" role="document" style="max-width:500px;">
<div class="content">

<div class="row">
<div class="col-md-6">
<label>Category</label>
<span class="btn radius badge-light-blue btn-block"><?php echo $meeting->category;?></span> 
</div>

<?php
if($meeting->status == 0){
$status = '<span class="btn radius badge-light-pink btn-block">Cancelled</span>';//cancelled
}else if($meeting->status == 1){
$status = '<span class="btn radius badge-light-green btn-block">New</span>';//new
}else if($meeting->status == 2){
$status = '<span class="btn radius badge-light-blue btn-block">Re-Scheduled</span>';//rescheduled
}else if($meeting->status == 3){
$status = '<span class="btn radius badge-light-orange btn-block">Upcoming</span>';//upcoming
}else if($meeting->status == 4){
$status = '<span class="btn radius badge-light-green btn-block">Done</span>';//done
}
?>

<div class="col-md-6">
<label>Status</label>
<?php echo $status;?>
</div>
</div>

<div class="clearfix"></div>
<hr />


<div class="row">
<div class="col-md-6">
<label>Attendees</label>
<p style="margin:5px 0 0 0;">
<?php
$colors = array('badge-light-orange','badge-light-purple','badge-light-pink','badge-light-green','badge-light-blue');
$this->db->where(['meeting_id'=> $meeting->meetingid,'user_type'=>'staff']);
$results = $this->db->get(db_prefix(). 'zmm_participants')->result_array();
if($results){
shuffle($colors); 	
foreach($results as $key=>$result){
$chars = explode(' ',$result['user_fullname']);	
$N = substr(str_replace('  ',' ',$chars[0]), 0, 1);
$S = substr(str_replace('  ',' ',$chars[1]), 0, 1);
echo '<span class="square-badge '.$colors[$key].' btn-block">'.$N.$S.'</span>';
}	
}
$this->db->where(['meeting_id'=> $meeting->meetingid,'user_type'=>'contact']);
$results = $this->db->get(db_prefix(). 'zmm_participants')->result_array();
if($results){
shuffle($colors); 	
foreach($results as $key=>$result){
$chars = explode(' ',$result['user_fullname']);	
$N = substr(str_replace('  ',' ',$chars[0]), 0, 1);
$S = substr(str_replace('  ',' ',$chars[1]), 0, 1);
echo '<span class="square-badge '.$colors[$key].' btn-block">'.$N.$S.'</span>';
}	
}
?>
</p>
 
</div>

<div class="col-md-6">
<label>Set Reminder</label>
<p>
<select class="form-control" data-toggle="select2">
<option value="2"<?php if($meeting->reminder==2){echo ' selected';}?>>2 Hours Before Meeting</option>
<option value="6"<?php if($meeting->reminder==6){echo ' selected';}?>>6 Hours Before Meeting</option>
<option value="12"<?php if($meeting->reminder==12){echo ' selected';}?>>12 Hours Before Meeting</option>
</select>
</p>
</div>
</div>

<div class="clearfix"></div>
<hr />

<div class="row">
<div class="col-md-12">
<label><h4><i class="fa fa-sticky-note"></i> Notes:</h4></label>
<p>
<?php echo nl2br($meeting->notes);?>
</p>
</div>
</div>



<div class="clearfix"></div>
<hr />

<div class="footer">
<div class="col-sm-4"><button type="button" id="edit_meeting" data-toggle="modal" data-target="#editMeetingModal" data-type="Edit" class="modify-meeting btn btn-primary waves-effect waves-light btn-block" style="background:#FBFDDF;border-radius:5px;color:#C0D220;border:1px solid #C0D220;font-size:11px;"><?php echo _l('scheduled_meetings_edit_meeting');?></button></div>
<div class="col-sm-4"><button type="button" id="reschedule_meeting" data-toggle="modal" data-target="#editMeetingModal" data-type="Reschedule" class="modify-meeting btn btn-primary waves-effect waves-light btn-block" style="background:#FCEDD6;border-radius:5px;color:#F4B85E;border:1px solid #F4B85E;font-size:11px;"><?php echo _l('scheduled_meetings_reschedule_meeting');?></button></div>
<div class="col-sm-4"><button type="button" id="cancel_meeting" class="btn btn-primary waves-effect waves-light btn-block" style="background:#FEE2E2;border-radius:5px;color:#F73D3D;border:1px solid #F73D3D;font-size:11px;"><?php echo _l('scheduled_meetings_cancel_meeting');?></button></div>
</div>
</div>
</div>
</div>
</div>
</div>


<!-- Start Modal -->
<div class="modal fade" id="editMeetingModal" tabindex="-1" role="dialog" aria-labelledby="meetingModalTitle" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered" role="document" style="max-width:500px;">
<div class="modal-content">
<div class="modal-body">

<div class="col-md-6">
<span id="edit_reschedule" style="font-size:18px;"><?php echo _l('scheduled_meetings_edit_meeting');?> / <?php echo _l('scheduled_meetings_reschedule_meeting');?></span>
</div>
<div class="col-md-6">
<span style="font-size:18px;float:right;" class="btn btn-default" data-dismiss="modal" aria-label="Close">X</span>
</div>

<div class="clearfix"></div>
<hr />

<div class="row">
<div class="col-md-12">
<div class="form-group">
<label for="simpleinput"><?php echo _l('scheduled_meetings_topic');?></label>
<input type="text" id="edit_topic" name="edit_topic" class="form-control" placeholder="Topic Name Here" value="<?php echo $meeting->topic;?>">
</div>
<div class="form-group">
<label for="simpleinput"><?php echo _l('scheduled_meetings_agenda');?></label>
<textarea class="form-control" id="edit_agenda" name="edit_agenda" rows="3"><?php echo $meeting->agenda;?></textarea>
</div>
<div class="form-group">
<label for="simpleinput"><?php echo _l('scheduled_meetings_meeting_category');?></label>
<select class="form-control" data-toggle="select" id="edit_category" name="edit_category">
<option value="New Project"<?php if($meeting->category =='New Project'){echo ' selected';}?>>New Project</option>
<option value="Existing Project"<?php if($meeting->category =='Existing Project'){echo ' selected';}?>>Existing Project</option>
<option value="General Inquiry"<?php if($meeting->category =='General Inquiry'){echo ' selected';}?>>General Inquiry</option>
</select>
</div>

<?php
$this->load->model('projects_model');
$this->db->where('id', get_contact_user_id());
$result = $this->db->get(db_prefix() . 'contacts')->row();
$userid = $result->userid;
$where = ['clientid'=>$userid,];
$projects = $this->projects_model->get('', $where);
?>

<div class="form-group" id="attach_edit_project" style="display:block;">
<label for="simpleinput">Attach Project</label>
<select class="form-control" data-toggle="select" id="edit_project" name="edit_project">
<option value="">Nothing Selected</option>
<?php foreach($projects as $project){
if($project['id']== $meeting->project_id){$selected = ' selected';}else{$selected = '';}	
?>
<?php echo '<option value="'.$project['id'].'"'.$selected.'>'.$project['name'].'</option>';
}
?>
</select>
</div>

									<hr>
                                    <h5 class="mfont-bold-medium-size"><?= _l('zmm_meeting_duration'); ?></h5>
									<div class="form-group">
                                        <div class="pull-left">
                                            <span><?= _l('zmm_hour'); ?></span>
                                            <select class="selectpicker" name="hours" id="edit_hour">
                                                <?php foreach (zmmGetHours() as $hour) { ?>
                                                    <option value="<?php echo $hour['value']; ?>">
                                                        <?php echo $hour['name']; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                            <label for="minutes"><?= _l('zmm_minutes'); ?></label>
                                            <select class="selectpicker" name="minutes" id="edit_minutes">
                                                <option value="0">0</option>
                                                <option value="15">15</option>
                                                <option value="30" selected>30</option>
                                                <option value="45">45</option>
                                            </select>
                                        </div>
                                        <div class="timezone_parent pull-right">
										<label for="timezones" id="timezones_label" class="control-label"><?php echo _l('zmm_timezone'); ?></label>
                                            <select name="edit_timezone" id="edit_timezones" class="form-control selectpicker" data-live-search="true">
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


<div class="form-group">
<label for="simpleinput"><?php echo _l('scheduled_meetings_time_and_date');?></label>
<div class="row">
<div class="col-sm-12"><input type="text" id="edit_date" name="edit_date" class="form-control datetimepicker meeting-date" readonly="readonly" autocomplete="off" value="<?php echo $meeting->meeting_date;?>">
</div>
</div>
</div>
</div>
</div>

</div>
<div class="modal-footer">
<button type="button" id="update_meeting" data-type="edit" class="update_meeting btn btn-primary waves-effect waves-light btn-block" style="background:#DEF163;border-radius:2px;color:#333300;"><?php echo _l('scheduled_meetings_update_meeting');?></button>
</div>
</div>
</div>
</div>
<!-- End Modal -->

<!-- Start Modal -->
<div class="modal fade" id="meetingModalCancel" tabindex="-1" role="dialog" aria-labelledby="meetingModalCancelTitle" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered" role="document">
<div class="modal-content">
<div class="modal-body text-center">
<div style="padding-top: 30px; padding-bottom: 30px; align-items: center;">
<div class="d-flex align-items-center justify-content-center mb-3">
<img class="rounded-circle" src="<?php echo site_url('modules/scheduled_meetings/assets/cancel.png');?>" alt="Generic placeholder image" height="100">
</div>
</div>
<h5 class="modal-title text-primary" ><?php echo _l('scheduled_meetings_meeting_cancelled');?></h5>
<hr>
<p><?php echo _l('scheduled_meetings_admin_was_notified');?></p>
</div>
<div class="modal-footer">
<button id="closeCancel" type="button" class="btn btn-danger waves-effect waves-light btn-block" data-dismiss="modal" aria-label="Close">Close</button>
</div>
</div>
</div>
</div>
<!-- End Modal -->








<script>
$(document).ready(function() {
$('.dropdown.bootstrap-select.bs3').eq(3).width(60);//HR
$('.dropdown.bootstrap-select.bs3').eq(4).width(60);//MIN
$('.dropdown.bootstrap-select.bs3').eq(5).width(150);//TZ

$("#cancel_meeting").click(function(){
if (!confirm("Do you want to Cancel the Meeting?")){
return false;
}	
var client_cancel_uri="<?php echo admin_url('scheduled_meetings/clients/client_cancel/'.$meeting->id);?>";		
$.post(client_cancel_uri, {
type: 'cancel',
}, function(result){
const obj = JSON.parse(result);	
if(obj.msg == 'success'){
//$("#dateandtime").text(obj.dateandtime);
//$("#allattendees").text(obj.allattendees);
//$("#confirmation").text(obj.confirmation);
//$('#meetingModal').modal('hide');
$('#meetingModalCancel').modal('show');
}else{
$('#meetingModal').modal('hide');	
$('#meetingModalError').modal('show');
}
});	
});

$("#closeCancel").click(function(){ 
var domain = location.protocol + '//' + location.host;
window.location.assign(domain + "/scheduled_meetings/clients/meeting_list")	;
});




$("#update_meeting").click(function(){
//function updateMeeting(){	
var client_update_uri="<?php echo admin_url('scheduled_meetings/clients/client_update/'.$meeting->id);?>";	
//alert($(this).attr("data-type"));return;	
console.log(client_request_uri);	
$.post(client_update_uri, {
topic: $("#edit_topic").val(),
agenda: $("#edit_agenda").val(),
category: $("#edit_category").val(),
project: $("#edit_project").val(),
date: $('#edit_date').val(),
hour: $("#edit_hour").val(),
minutes: $('#edit_minutes').val(),
timezone: $('#edit_timezones').val(),
type: $(this).attr("data-type"),
}, function(result){
const obj = JSON.parse(result);	
if(obj.msg == 'success'){
$("#dateandtime").text(obj.dateandtime);
$("#allattendees").text(obj.allattendees);
$('#editMeetingModal').modal('hide');
$('#meetingModalConfirm').modal('show');
}else{
$('#editMeetingModal').modal('hide');	
$('#meetingModalError').modal('show');
}
});
//}
});

$(".modify-meeting").click(function(){
$('#edit_reschedule').text($(this).attr("data-type") + ' Meeting');
if($(this).attr("data-type")=='Edit'){
$(".update_meeting").text('Edit Meeting');
$(".update_meeting").attr("data-type", 'edit');
}else{
$(".update_meeting").text('Reschedule Meeting');
$(".update_meeting").attr("data-type", 'reschedule');
}	
});

});
</script>