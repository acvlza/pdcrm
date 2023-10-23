 <?php defined('BASEPATH') or exit('No direct script access allowed');?>
<?php init_head(); ?>
<div id="wrapper"> 
    <div class="content">
        <div class="row main_row">
            <div class="col-md-12">
                            <!-- start page title -->
                    <div class="row">
					       <div class="col-md-6">
                            <div class="page-title-box d-flex align-items-center justify-content-between">
                                <h4 class="mb-0" style="font-size:18px;font-weight:bold;">Meetings Dashboard
                                    <span style="font-size:14px;font-weight:normal;">
                                        <ol class="breadcrumb m-0 text-small">
                                            <li class="breadcrumb-item"><a href="<?php echo admin_url('scheduled_meetings/index');?>">Dashboard</a></li>
                                            <li class="breadcrumb-item active">Edit Meeting</li>
                                        </ol>
                                    </span>
                                </h4>
								</div>
								</div>
								<div class="col-md-6">
                                <div class="">
                                    <div class="button-examples float-right" style="padding-top: 21px;margin-left:5px;">
									<a href="<?php echo admin_url('scheduled_meetings/link_list');?>" class="btn btn-secondary btn-outline-primary pull-right mleft10">
                                    <i class="mdi mdi-arrow-left"></i> Back</a>
                                        <a href="<?php echo admin_url('scheduled_meetings/create_new_meeting');?>"><button type="button" class="btn btn-primary waves-effect pull-right" style="background:#AA55FF;color:#fff;">
                                                <i class="mdi mdi mdi-file-document-box-plus-outline"></i> Create New Meeting
                                            </button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                    </div>    
                    </div>
					
					<div class="col-md-12">
                     <div class="panel_s">
                	<div class="panel-body">
					
                    <!-- end page title -->
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="simpleinput">Topic</label>
                                                <input type="text" id="topic" name="topic" class="form-control" placeholder="Topic Name Here" value="<?= $meeting->topic;?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="simpleinput">Agenda</label>
                                                <textarea class="form-control" id="agenda" name="agenda" rows="3"><?php echo $meeting->agenda;?></textarea>
                                            </div>
                                            <div class="form-group">
											<?php $selected = $meeting->category;?>
                                                <label for="category">Meeting Category</label>
                                                <select class="form-control" data-toggle="select" id="category" name="category">
                                                    <option value="New Project"<?php if($selected == "New Project"){echo ' selected';}?>>New Project</option>
                                                    <option value="Existing Project"<?php if($selected == "Existing Project"){echo ' selected';}?>>Existing Project</option>
                                                    <option value="General Inquiry"<?php if($selected == "General Inquiry"){echo ' selected';}?>>General Inquiry</option>
                                                </select>
                                            </div>
											
											
																					
<?php $selected = $meeting->project_id;?>
<?php if($meeting->category == "Existing Project"){$display='block';}else{$display='none';}?>											
<div class="form-group" app-field-wrapper="project" style="display:<?php echo $display;?>;" id="attach_project">
<?php echo render_select('project', $projects, ['id', 'name'], 'Attach Project', $selected, [], [], '', 'ays-ignore'); ?> 
</div> 
											
										
                                            <div class="form-group">
                                                <label for="simpleinput">Time and Date</label>
                                                <div class="row">
												<div class="col-sm-12"><input type="text" id="date" name="date" class="form-control datetimepicker meeting-date" readonly="readonly" autocomplete="off" value="<?php echo $meeting->meeting_date;?>">
                                                    </div>
                                                <!--    <div class="col-sm-4"> <input type="text" class="form-control" data-toggle="input-mask" data-mask-format="00:00:00">
                                                    <span class="font-13 text-muted">e.g "HH:MM:SS"</span>
                                                    </div>-->
                                                </div>
                                            </div>
                                            <div class="form-group">
                                              <a target="_blank" href="<?php echo $meeting->web_url;?>"><button id="attend_meeting_link" type="button" class="btn badge-soft-info btn-outline-info btn-lg btn-block" style="background:#B4EDF4;border:1px solid #199BE7;"> <span class="float-left"> Attend Meeting </span>
                                                    <i class="fa fa-angle-double-right pull-right"></i> </button></a> 
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
											
											
											
											
										<div class="col-md-6">
                                        <div class="form-group" id="select_contacts">
                                            <input type="text" hidden id="rel_contact_type" value="contacts">
                                            <label for="rel_contact_id"><?= _l('zmm_contacts'); ?></label>
                                            <div id="rel_contact_id_select">
                                                <select name="contacts[]" id="rel_contact_id" multiple="true" class="ajax-search contacts" data-width="100%" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
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
                                                <select name="leads[]" id="rel_id" multiple="true" class="ajax-search leads" data-width="100%" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
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
											
											
											
											
											
											
											
											
                              <div class="form-group col-md-6">
								<!--	<label for="simpleinput">Attendees</label>-->
                               <p>
								<?php echo render_select('staff[]', $staff_members, ['staffid', ['firstname', 'lastname']], 'zmm_staff', [], ['multiple' => true], [], '', 'staffies', false); ?>
								 </p>
                                 </div>
												
												
												
																								
					<div style="padding-top: 10px;" class="form-group col-sm-6" app-field-wrapper="reminder"><label for="reminder" class="control-label">
                  <?php echo _l('Set Reminder');?></label>
				  <?php $selected =$meeting->reminder;?>
                  <select name="reminder" id="reminder" class="selectpicker reminder_select" data-none-selected-text="Nothing selected" data-width="100%" data-live-search="true" tabindex="-98"><option value="2"<?php if($selected == "2"){echo ' selected';}?>>2 Hours Before Meeting</option>
                                 <option value="6"<?php if($selected == "6"){echo ' selected';}?>>6 Hours Before Meeting</option>
                                 <option value="12"<?php if($selected == "12"){echo ' selected';}?>>12 Hours Before Meeting</option>
								</select>
								</div>		
												
											
												
                                            </div>
                                            <hr>
                                            <label>Notes</label>
                                            <textarea class="form-control" id="notes" name="notes" rows="7"><?php echo $meeting->notes;?></textarea>
                                            <hr>
                                            <div style="padding-top: 11px;">
                                            <button id="meeting_update" type="button" class="btn badge-primary btn-outline-primary btn-lg  waves-effect waves-light pull-right"  data-toggle="" data-target="" style="background:#AA55FF;color:#fff;"> <span class="float-left"> Update Meeting </span> </button>
                                            </div>

<!-- Start Modal -->
<div class="modal fade" id="meetingModalConfirm" tabindex="-1" role="dialog" aria-labelledby="meetingModalConfirmTitle" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered" role="document">
<div class="modal-content">
<div class="modal-body text-center">
<div style="padding-top: 30px; padding-bottom: 30px; align-items: center;">
<div class="d-flex align-items-center justify-content-center mb-3">
<img class="rounded-circle" src="<?php echo site_url('modules/scheduled_meetings/assets/ico-cal.png');?>" alt="Generic placeholder image" height="100">
</div>
</div>
<h5 id="confirmation" class="modal-title text-primary" ><?php echo _l('scheduled_meetings_updated');?></h5>
<hr>
<p><?php echo _l('scheduled_meetings_scheduled_on');?> <b><span id="dateandtime">date and time</span></b></p>
<p><b><?php echo _l('scheduled_meetings_attendees');?>:</b> <span id="allattendees">Staff 1, Staff 2, Client 1.</span></p>
</div>
<div class="modal-footer">
<button type="button" id="closeConfirm" class="btn btn-primary waves-effect waves-light btn-block" data-dismiss="modal" aria-label="Close">Back</button>
</div>
</div>
</div>
</div>
<!-- End Modal -->

<!-- Start Modal -->
<div class="modal fade" id="meetingModalError" tabindex="-1" role="dialog" aria-labelledby="meetingModalErrorTitle" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered" role="document">
<div class="modal-content">
<div class="modal-body text-center">
<div style="padding-top: 30px; padding-bottom: 30px; align-items: center;">
<div class="d-flex align-items-center justify-content-center mb-3">
<img class="rounded-circle" src="<?php echo site_url('modules/scheduled_meetings/assets/error.png');?>" alt="Generic placeholder image" height="100">
</div>
</div>
<h5 class="modal-title text-primary" ><?php echo _l('scheduled_meetings_could_not_be_scheduled');?></h5>
<hr>
<p><?php echo _l('scheduled_meetings_notify_admin');?></p>
</div>
<div class="modal-footer">
<button id="" type="button" class="btn btn-danger waves-effect waves-light btn-block" data-dismiss="modal" aria-label="Close">Close</button>
</div>
</div>
</div>
</div>
<!-- End Modal -->


                                        </div>
                                    </div>
                                </div>
                           
                        </div> <!-- end card-->
                    </div> <!-- end col -->
                </div>
            </div> <!-- container-fluid -->
        </div>
		</div>
		</div>
		</div>
<?php init_tail(); ?>
<?php require_once(FCPATH .'modules/zoom_meeting_manager/assets/js/create_js.php'); ?>
<script>
$('#category').on('change', function () {
  //ways to retrieve selected option and text outside handler
  console.log('Changed option value ' + this.value);
  console.log('Changed option text ' + $(this).find('option').filter(':selected').text());
  if(this.value == 'Existing Project'){
  $('#attach_project').show();
  }else{
  $('#attach_project').hide();	  
  }
});


$(document).ready(function() {

//$('#project').data("data-none-selected-text","Type Project Name").attr("data-none-selected-text","Type Project Name"); 
$('.filter-option-inner-inner').eq(0).text("Type Project Name");


$("#meeting_update").click(function(){
var mystaff=[];
var $el=$(".staffies");
$el.find('option:selected').each(function(){
mystaff.push({value:$(this).val(),text:$(this).text()});
});
	
//alert(data);return;	
var admin_update_uri="<?php echo admin_url('scheduled_meetings/admin_update/'.$meeting->id);?>";	
//alert($(this).attr("data-type"));return;	
//console.log(admin_update_uri);	
$.post(admin_update_uri, {
mystaff:mystaff,
contacts:$("#rel_contact_id").val(),
leads:$("#rel_id").val(),	
topic: $("#topic").val(),
agenda: $("#agenda").val(),
category: $("#category").val(),
project: $("#project").val(),
notes: $("#notes").val(),
date: $('#date').val(),
hour: $("#hour").val(),
minutes: $('#minutes').val(),
timezone: $('#timezones').val(),
reminder: $('#reminder').val(),
type: $(this).attr("data-type"),
}, function(result){
const obj = JSON.parse(result);	
if(obj.msg == 'success'){
$("#dateandtime").text(obj.dateandtime);
$("#allattendees").text(obj.allattendees);
$('#meetingModalConfirm').modal('show');
}else{
//$('#editMeetingModal').modal('hide');	
$('#meetingModalError').modal('show');
}
});
});
$("#closeConfirm").click(function(){ 
var domain = location.protocol + '//' + location.host;
window.location.assign(domain + "/admin/scheduled_meetings/index")	;
});

});



</script>
</body>
</html>		