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
                                            <li class="breadcrumb-item active">New Meeting</li>
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
                                        <a href="<?php echo admin_url('zoom_meeting_manager/index/createMeeting');?>"><button type="button" class="btn btn-primary waves-effect pull-right" style="background:#AA55FF;color:#fff;">
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
                                                <input type="text" id="topic" name="topic" class="form-control" placeholder="Topic Name Here">
                                            </div>
                                            <div class="form-group">
                                                <label for="simpleinput">Agenda</label>
                                                <textarea class="form-control" id="agenda" name="agenda" rows="3"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="category">Meeting Category</label>
                                                <select class="form-control" data-toggle="select" id="category" name="category">
                                                    <option value="New Project">New Project</option>
                                                    <option value="Existing Project">Existing Project</option>
                                                    <option value="General Inquiry">General Inquiry</option>
                                                </select>
                                            </div>
											
											
																					
<?php $selected ='';?>											
<div class="form-group" app-field-wrapper="project" style="display:none;" id="attach_project">
<?php echo render_select('project', $projects, ['id', 'name'], 'Attach Project', $selected, [], [], '', 'ays-ignore'); ?> 
</div> 
											
										
                                            <div class="form-group">
                                                <label for="simpleinput">Time and Date</label>
                                                <div class="row">
                                                    <div class="col-sm-12"><input type="text" id="date" name="date" class="form-control datetimepicker meeting-date" readonly="readonly" autocomplete="off">
                                                    </div>
                                                <!--    <div class="col-sm-4"> <input type="text" class="form-control" data-toggle="input-mask" data-mask-format="00:00:00">
                                                    <span class="font-13 text-muted">e.g "HH:MM:SS"</span>
                                                    </div>-->
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <button id="meeting_link" type="button" class="btn badge-soft-info btn-outline-info btn-lg btn-block" style="background:#B4EDF4;border:1px solid #199BE7;"> <span class="float-left"> Meeting Link Here </span>
                                                    <i class="fa fa-angle-double-right pull-right"></i> </button> 

<!--<p> Delete this after : If no meeting links show "Schedule Meeting Link", If there is meeting link attached, Show "Attend Meeting Link" </p>-->
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
													<?php echo render_select('staff[]', $staff_members, ['staffid', ['firstname', 'lastname']], 'zmm_staff', [], ['multiple' => true], [], '', 'staff', false); ?>
													
                                                     <!--   <select id="attendees[]" name="attendees[]" class="selectpicker" multiple="1" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98">
                                                            <optgroup label="Assigned Staff">
                                                                <option value="S1">Staff 1</option>
                                                                <option value="S1">Staff 2</option>
                                                            </optgroup>
                                                            <optgroup label="Client Contacts">
                                                                <option value="C1">Client 1</option>
                                                                <option value="C1">Client 2</option>
                                                            </optgroup>
                                                        </select>-->
                                                    </p>
                                                </div>
												
												
												
																								
										 <div style="padding-top: 10px;" class="form-group col-sm-6" app-field-wrapper="reminder"><label for="reminder" class="control-label">
                  <?php echo _l('Set Reminder');?></label>
                  <select name="reminder" id="reminder" class="selectpicker reminder_select" data-none-selected-text="Nothing selected" data-width="100%" data-live-search="true" tabindex="-98"><option value="2">2 Hours Before Meeting</option>
                                 <option value="6">6 Hours Before Meeting</option>
                                 <option value="12">12 Hours Before Meeting</option>
								</select>
								</div>		
												
											
												
                                            </div>
                                            <hr>
                                            <label>Notes</label>
                                            <textarea class="form-control" id="notes" name="notes" rows="7"></textarea>
                                            <hr>
                                            <div style="padding-top: 11px;">
                                            <button type="button" id= "schedule_button" class="btn badge-primary btn-outline-primary btn-lg  waves-effect waves-light pull-right"  data-toggle="modal" data-target="" style="background:#AA55FF;color:#fff;"> <span class="float-left"> Schedule Meeting </span> </button>
                                            </div>

                                            <!-- Start Modal -->
                                                <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">

                                                            <div class="modal-body text-center">
                                                                <div style="padding-top: 30px; padding-bottom: 30px; align-items: center;">
                                                                    <div class="d-flex align-items-center justify-content-center mb-3">
                                                                        <img class="rounded-circle" src="assets/images/ico-cal.png" alt="Generic placeholder image" height="100">
                                                                    </div>
                                                                </div>
                                                                <h5 class="modal-title text-primary" >Meeting Scheduled</h5>
                                                                <hr>
                                                                <p>Your meeting is scheduled on <b>date and time</b></p>
                                                                <p><b>Attendees:</b> Staff 1, Staff 2, Client 1.</p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-primary waves-effect waves-light btn-block">Back</button>
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
$("#schedule_button").click(function(){
document.location.href="<?php echo admin_url('scheduled_meetings/ZoomcreateMeeting');?>";	
});	
//$('#project').data("data-none-selected-text","Type Project Name").attr("data-none-selected-text","Type Project Name"); 
$('.filter-option-inner-inner').eq(0).text("Type Project Name");


$("#meeting_link").click(function(){
//var topic = document.getElementById('topic').id;
var topic = $('#topic').val();
document.cookie = "topic=" + topic; 
localStorage.setItem('topic',topic);
var agenda = $('#agenda').val();
document.cookie = "agenda=" + agenda;
localStorage.setItem('agenda',agenda);
var category = $('#category').find(":selected").val();
document.cookie = "category=" + category; 
localStorage.setItem('category',category);
var project = $('#project').find(":selected").val();
document.cookie = "project=" + project; 
localStorage.setItem('project',project);
var date = $('#date').val();
document.cookie = "date=" + date;
localStorage.setItem('date',date);

var allstaff = [];
$(".staff > option").each(function(){
 if($(this).is(':selected')){	
allstaff.push(this.value);
 }
});
var staff = allstaff.join(',');
document.cookie = "staff=" + staff; 
localStorage.setItem('staff',staff);

var allstaffnames = [];
var staff_options = "";
$(".staff > option").each(function(){
if($(this).is(':selected')){	
allstaffnames.push(this.text);
staff_options += "<option value=\'" + this.value + "\' selected>" + this.text + "</option>";
}else{
staff_options += "<option value=\'" + this.value + "\'>" + this.text + "</option>";	
}
});
localStorage.setItem('staff_options',staff_options);

var staffnames = allstaffnames.join(',');
document.cookie = "staffnames=" + staffnames; 
localStorage.setItem('staffnames',staffnames);

var allcontacts = [];
var allcontactnames = [];
var contacts_optgroup = "<optgroup label=\'Currently Selected\'>";
$(".contacts > optgroup > option").each(function(){
allcontacts.push(this.value);
allcontactnames.push(this.text);
contacts_optgroup += "<option value=\'" + this.value + "\' selected>" + this.text + "</option>";
});
contacts_optgroup += "</optgroup>";
localStorage.setItem('contacts_optgroup',contacts_optgroup);

var contacts = allcontacts.join(',');
document.cookie = "contacts=" + contacts; 
localStorage.setItem('contacts',contacts);

var contactnames = allcontactnames.join(',');
document.cookie = "contactnames=" + contactnames; 
localStorage.setItem('contactnames',contactnames);

var allleads = [];
$(".leads > option").each(function(){
allleads.push(this.value);
});
var leads = allleads.join(',');
document.cookie = "leads=" + leads; 
localStorage.setItem('leads',leads);

var reminder = $('.reminder_select').find(":selected").val();
document.cookie = "reminder=" + reminder; 
localStorage.setItem('reminder',reminder);

var notes = $('#notes').val();
document.cookie = "notes=" + notes;
localStorage.setItem('notes',notes);

for (var i = 0; i < localStorage.length; i++)  console.log( localStorage.key(i) +" has value " + localStorage[localStorage.key(i)] );

//document.location.href="<?php echo admin_url('zoom_meeting_manager/index/createMeeting');?>";
document.location.href="<?php echo admin_url('scheduled_meetings/ZoomcreateMeeting');?>";
});


});



</script>
</body>
</html>		