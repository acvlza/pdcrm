<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Scheduled Meetings
Description: Allow clients to schedule zoom meetings.
Version: 1.0
Author: Godfrey Philander
Author URI: https://host4marketing.co.za
Requires at least: 3.0.4
*/

/*BREAK*/
$CI = &get_instance();
define('SCHEDULED_MEETINGS_MODULE_NAME', 'scheduled_meetings');
if (has_permission('scheduled_meetings', '', 'view') || is_admin()){
hooks()->add_action('admin_init', 'scheduled_meetings_init_menu_items');
hooks()->add_action('admin_init', SCHEDULED_MEETINGS_MODULE_NAME .'_permissions');
hooks()->add_action('admin_init', SCHEDULED_MEETINGS_MODULE_NAME .'_add_settings_tab');
//hooks()->add_action('pre_activate_module', SCHEDULED_MEETINGS_MODULE_NAME .'_sidecheck');
//hooks()->add_action('pre_deactivate_module', SCHEDULED_MEETINGS_MODULE_NAME .'_deregister');
}

hooks()->add_action('clients_init', 'scheduled_meetings_init_client_menu');

hooks()->add_action('app_customers_head', 'scheduled_meetings_customer_project_tabs');
function scheduled_meetings_customer_project_tabs()
{
    $CI = &get_instance();
    if ($CI->uri->segment(2) == 'project') {
        $project_id = $CI->uri->segment(3); ?>
        <script type="text/javascript">
            $(document).ready(function() {
                var node = '<li role="presentation" class="project_tab_scheduled_meetings"><a data-group="project-scheduled-meetings" href="<?php echo site_url('clients/project/'.$project_id.'?group=project_meetings'); ?>" role="tab"><i class="fa-regular fa-file-lines menu-icon" aria-hidden="true"></i> <?php echo _l('scheduled_meetings_meetings'); ?> </a></li>';
                $('.nav-tabs').append(node);
            });
        </script>
<?php }}


$CI->db->where('module_name', SCHEDULED_MEETINGS_MODULE_NAME);
$_result = $CI->db->get(db_prefix() . 'modules')->row();
//if($_result && $_result->active == 1){
//if(get_option('scheduled_meetings_enabled') == 1){
hooks()->add_action('app_admin_footer','scheduled_meetings_load_admin_js');
hooks()->add_action('app_admin_head', 'scheduled_meetings_admin_head');
//}
//}

hooks()->add_action('app_customers_footer','scheduled_meetings_load_clientjs');
function scheduled_meetings_load_clientjs()
{
$CI = &get_instance();
echo '<script>var client_request_uri="'. admin_url('scheduled_meetings/clients/client_request').'"</script>';
echo '<script src="'.module_dir_url('scheduled_meetings', 'assets/js/clientjs.js').'?v='.$CI->app_scripts->core_version().'"></script>';
}
if (is_client_logged_in()){
hooks()->add_action('customers_content_container_start','scheduled_meetings_add_meeting_modal');
}
function scheduled_meetings_add_meeting_modal(){
$CI = &get_instance();	
$CI->load->model('projects_model');
 $CI->db->where('id', get_contact_user_id());
 $result = $CI->db->get(db_prefix() . 'contacts')->row();
 $userid = $result->userid;

$where = ['clientid'=>$userid,];
$projects = $CI->projects_model->get('', $where);	

	
echo '<div class="col-md-12 pull-right"><a href="#" class="btn btn-primary tw-mb-2 sm:tw-mb-4 pull-right" style="background:#DEF163;border:1px solid #333300;color:#333300;" data-toggle="modal" data-target="#meetingModal"><i class="fa-regular fa-plus tw-mr-1"></i>'. _l('scheduled_meetings_schedule_meeting').'</a></div>';	
	
echo '<!-- Start Modal -->
<div class="modal fade" id="meetingModal" tabindex="-1" role="dialog" aria-labelledby="meetingModalTitle" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered" role="document" style="max-width:500px;">
<div class="modal-content">
<div class="modal-body">

<div class="col-md-6">
<span style="font-size:18px;">'. _l('scheduled_meetings_schedule_a_meeting').'</span>
</div>
<div class="col-md-6">
<span style="font-size:18px;float:right;" class="btn btn-default" data-dismiss="modal" aria-label="Close">X</span>
</div>

<div class="clearfix"></div>
<hr />

<div class="row">
<div class="col-md-12">
<div class="form-group">
<label for="simpleinput">'. _l('scheduled_meetings_topic').'</label>
<input type="text" id="topic" name="topic" class="form-control" placeholder="Topic Name Here">
</div>
<div class="form-group">
<label for="simpleinput">'. _l('scheduled_meetings_agenda').'</label>
<textarea class="form-control" id="agenda" name="agenda" rows="3"></textarea>
</div>
<div class="form-group">
<label for="simpleinput">'. _l('scheduled_meetings_meeting_category').'</label>
<select class="form-control" data-toggle="select" id="category" name="category">
<option value="New Project">New Project</option>
<option value="Existing Project">Existing Project</option>
<option value="General Inquiry">General Inquiry</option>
</select>
</div>
';

?>

<div class="form-group" id="attach_project" style="display:none;">
<label for="simpleinput">Attach Project</label>
<select class="form-control" data-toggle="select" id="project" name="project">
<?php foreach($projects as $project){?>
<?php echo '<option value="'.$project['id'].'">'.$project['name'].'</option>';
}
?>
</select>
</div>

									<hr>
                                    <h5 class="mfont-bold-medium-size"><?= _l('zmm_meeting_duration'); ?></h5>
									<div class="form-group">
                                        <div class="pull-left">
                                            <span><?= _l('zmm_hour'); ?></span>
                                            <select class="selectpicker" name="hours" id="hour">
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


<?php
echo '
<div class="form-group">
<label for="simpleinput">'. _l('scheduled_meetings_time_and_date').'</label>
<div class="row">
<div class="col-sm-12"><input type="text" id="date" name="date" class="form-control datetimepicker meeting-date" readonly="readonly" autocomplete="off">
</div>
</div>
</div>
</div>
</div>

</div>
<div class="modal-footer">
<button type="button" id="request_meeting" class="btn btn-primary waves-effect waves-light btn-block" style="background:#DEF163;border-radius:2px;color:#333300;">'. _l('scheduled_meetings_schedule_meeting').'</button>
</div>
</div>
</div>
</div>
<!-- End Modal -->';	
	
	
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
<h5 id="confirmation" class="modal-title text-primary" >'. _l('scheduled_meetings_scheduled').'</h5>
<hr>
<p>'. _l('scheduled_meetings_scheduled_on').' <b><span id="dateandtime">date and time</span></b></p>
<p><b>'. _l('scheduled_meetings_attendees').':</b> <span id="allattendees">Staff 1, Staff 2, Client 1.</span></p>
</div>
<div class="modal-footer">
<button type="button" id="closeConfirm" class="btn btn-primary waves-effect waves-light btn-block" data-dismiss="modal" aria-label="Close">Back</button>
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
	
}


function scheduled_meetings_init_client_menu()
{   
$CI  =&get_instance();
$uri = $_SERVER['REQUEST_URI'];
$curr = $_SERVER['SERVER_NAME'];
$httpstr = explode("://", APP_BASE_URL)[0];
$site_url = $httpstr."://".$_SERVER['SERVER_NAME'].$uri;

    // Show menu item only if client is logged in
   // if (get_option('scheduled_meetings_enabled') == 1 && is_client_logged_in() ) {
    if (is_client_logged_in()){
  
        add_theme_menu_item('scheduled_meetings', [
            'name'     => 'Meetings',
			//'href'     => '#" data-toggle="modal" data-target="#meetingModal',
			'href'     => admin_url('scheduled_meetings/clients/meeting_list'),
            'position' => 1,
        ]);
                
    }
    
}

function scheduled_meetings_admin_head(){
echo '<style>
</style>';
}

if(!defined('SCHEDULED_MEETINGS_REG_PROD_POINT'))
	define('SCHEDULED_MEETINGS_REG_PROD_POINT', 'https://lic.perfexsaas.co.za/scheduled_meetings');

define('SCHEDULED_MEETINGS_VERSION', '1.0.0');

//scheduled_meetings_verify('scheduled_meetings'); 

function scheduled_meetings_add_settings_tab()
{
    $CI = &get_instance();
    $CI->app_tabs->add_settings_tab('scheduled_meetings-settings', [
       'name'     => '<img style="width:32px;padding:0 10px 0 0;position:absolute;margin:0 35px 0 -35px;" src="'.site_url('modules/scheduled_meetings/assets/scheduled_meetings.png').'"/>Meetings Settings',
       'icon'     => 'fa',
       'view'     => 'scheduled_meetings/scheduled_meetings_settings',
       'position' => 30,
   ]);
}

  
function scheduled_meetings_load_admin_js()
{
echo '<script>
$(document).ready(function() {
$(".selectpicker").click(function(){
  // alert( $(".selectpicker").index(this) );
});	
	
var segment = $(location).attr("href").split("/").pop();	

//if(segment == "createMeeting"){
if(segment == "ZoomcreateMeeting"){	
$("#topic").val(localStorage.getItem("topic"));	
$("#description").val(localStorage.getItem("agenda"));	
$("#date").val(localStorage.getItem("date"));
$("#reminder").val(localStorage.getItem("reminder"));

$("#project").val(localStorage.getItem("project"));
$("#category").val(localStorage.getItem("category"));
$("#notes").val(localStorage.getItem("notes"));

var values=localStorage.getItem("staff");
$.each(values.split(","), function(i,e){
$(".selectpicker option[value=\'" + e + "\']").eq(0).prop("selected", true);
var staffnames = localStorage.getItem("staffnames");
$(".filter-option-inner-inner").eq(3).text(staffnames);
});

contacts_optgroup=localStorage.getItem("contacts_optgroup");
$("#rel_contact_id").append(contacts_optgroup);

var contactnames = localStorage.getItem("contactnames");
console.log(contactnames);
$(".filter-option-inner-inner").eq(5).text(contactnames);


}
});
</script>';
}

function scheduled_meetings_permissions() {
    $capabilities = [];
    $capabilities['capabilities'] = [
        'view_own' => _l('permission_view_own'),
        'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
        'create' => _l('permission_create'),
        'edit'   => _l('permission_edit'),
        'delete' => _l('permission_delete'),
    ];
    if (function_exists('register_staff_capabilities')) {
        register_staff_capabilities('scheduled_meetings', $capabilities,'Meetings');
    }
}

function scheduled_meetings_init_menu_items(){
    $CI  =&get_instance();
    
$CI->app_menu->add_sidebar_menu_item('scheduled_meetings', [
            'name'     => '<img style="width:32px;padding:0 10px 0 0;" src="'.site_url('modules/scheduled_meetings/assets/scheduled_meetings.png').'"/>Scheduled Meetings',
            'slug'     => 'scheduled_meetings_menu_items',
            'position' => 1, 
            'collapse' => true,
            'icon'     => '', 
        ]);  
$CI->app_menu->add_sidebar_children_item('scheduled_meetings', [
            'name'     => 'Overview',
            'slug'     => 'scheduled_meetings_view_all',
            'position' => 1, 
            'href'     => admin_url('scheduled_meetings/index'),
            'icon'     => 'fa fa-eye', 
        ]); 

   
 $CI->app_menu->add_sidebar_children_item('scheduled_meetings', [
            'name'     => 'New',
            'slug'     => 'scheduled_meetings_new',
            'position' => 2, 
            'href'     => admin_url('scheduled_meetings/new_meetings'),
            'icon'     => 'fa fa-clock', 
        ]);		
        
 $CI->app_menu->add_sidebar_children_item('scheduled_meetings', [
            'name'     => 'Cancelled',
            'slug'     => 'scheduled_meetings_cancelled',
            'position' => 3, 
            'href'     => admin_url('scheduled_meetings/cancelled'),
            'icon'     => 'fa fa-close', 
        ]);
                         
$CI->app_menu->add_sidebar_children_item('scheduled_meetings', [
            'name'     => 'Upcoming',
            'slug'     => 'scheduled_meetings_upcoming',
            'position' => 4, 
            'href'     => admin_url('scheduled_meetings/upcoming'),
            'icon'     => 'fa fa-arrow-right', 
        ]);
        
$CI->app_menu->add_sidebar_children_item('scheduled_meetings', [
            'name'     => 'Reschedule',
            'slug'     => 'scheduled_meetings_rescheduled',
            'position' => 5, 
            'href'     => admin_url('scheduled_meetings/rescheduled'),
            'icon'     => 'fa fa-calendar', 
        ]);
$CI->app_menu->add_sidebar_children_item('scheduled_meetings', [
            'name'     => 'Create New Meeting',
            'slug'     => 'scheduled_meetings_add',
            'position' => 6, 
            'href'     => admin_url('scheduled_meetings/create_new_meeting'),
            'icon'     => 'fa fa-plus', 
        ]);		
		
$CI->app_menu->add_sidebar_menu_item('scheduled_meetings_links', [
            'name'     => '<img style="width:32px;padding:0 10px 0 0;" src="'.site_url('modules/scheduled_meetings/assets/scheduled_meetings.png').'"/>Manage Link',
            'slug'     => 'scheduled_meetings_menu_links',
            'position' => 2, 
            'collapse' => true,
            'icon'     => '', 
        ]);	
$CI->app_menu->add_sidebar_children_item('scheduled_meetings_links', [
            'name'     => 'Overview',
            'slug'     => 'scheduled_meetings_link_list',
            'position' => 1, 
            'href'     => admin_url('scheduled_meetings/link_list'),
            'icon'     => '', 
        ]); 
$CI->app_menu->add_sidebar_children_item('scheduled_meetings_links', [
            'name'     => 'Create Link Schedule',
            'slug'     => 'scheduled_meetings_create_link_schedule',
            'position' => 2,
			//'href'     => admin_url('zoom_meeting_manager/index/createMeeting'),
            'href'     => admin_url('scheduled_meetings/ZoomcreateMeeting'),
            'icon'     => '', 
        ]);
$CI->app_menu->add_sidebar_children_item('scheduled_meetings_links', [
            'name'     => 'Link Settings',
            'slug'     => 'scheduled_meetings_link_settings',
            'position' => 2, 
            'href'     => admin_url('settings?group=zoom-meeting-manager-settings'),
            'icon'     => '', 
        ]);  		
                  
}                 
  
/**
* Register activation module hook
*/
register_activation_hook('scheduled_meetings', 'scheduled_meetings_module_activation_hook');

function scheduled_meetings_module_activation_hook()
{
require_once(__DIR__ . '/install.php');
}
/**
* Register deactivation hook
*/

register_deactivation_hook('scheduled_meetings', 'scheduled_meetings_module_deactivation_hook');
function scheduled_meetings_module_deactivation_hook()
{
$CI = &get_instance();

//require_once(__DIR__ . '/deactivate.php');
}


/*
 * Register language files
 */
register_language_files('scheduled_meetings', ['scheduled_meetings']);


function scheduled_meetings_sidecheck($module_name)
{
    if (SCHEDULED_MEETINGS_MODULE_NAME == $module_name['system_name']) {
        scheduled_meetings_activate($module_name);
    }
}
//########################################################################################
function scheduled_meetings_deregister($module_name)
{
    if (SCHEDULED_MEETINGS_MODULE_NAME == $module_name['system_name']) {
        scheduled_meetings_deactivate(SCHEDULED_MEETINGS_MODULE_NAME);
        delete_option(SCHEDULED_MEETINGS_MODULE_NAME.'_verification_id');
        delete_option(SCHEDULED_MEETINGS_MODULE_NAME.'_last_verification');
        delete_option(SCHEDULED_MEETINGS_MODULE_NAME.'_product_token');
        delete_option(SCHEDULED_MEETINGS_MODULE_NAME.'_heartbeat');
        
         
    }
    
    
}

//########################################################################################
    function scheduled_meetings_activate($module)
    {		error_reporting(-1);
		ini_set('display_errors', 1);
    
        if (!option_exists($module['system_name'].'_verification_id') || empty(get_option($module['system_name'].'_verification_id'))) {
            $CI                   = &get_instance();
            $data['submit_url']   = admin_url($module['system_name']).'/scheduled_meetings_ver/activate';
            $data['original_url'] = admin_url('modules/activate/'.$module['system_name']);
            $data['module_name']  = $module['system_name'];
            $data['title']        = 'Perfex News Ticker Module Activation';
            echo $CI->load->view($module['system_name'].'/activate', $data, true);
            exit;
        }
    }
//########################################################################################
   
    function scheduled_meetings_getUserIP()
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        } elseif (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        } else {
            $ipaddress = 'UNKNOWN';
        }
        return $ipaddress;
    }

    
//########################################################################################
    
    function scheduled_meetings_deactivate($module_name)
    {
        get_instance()->load->helper('scheduled_meetings/curl');
        get_instance()->load->library('user_agent');
        $data['user_agent']       = get_instance()->agent->browser().' '.get_instance()->agent->version();
        $data['activated_domain'] = base_url();
        $data['requested_at']     = date('Y-m-d H:i:s');
        $data['ip']               = scheduled_meetings_getUserIP();
        $data['os']               = get_instance()->agent->platform();
        $verification_id          = base64_decode(get_option($module_name.'_verification_id'));
        $id_data                  = explode('|', $verification_id);
        $data['purchase_code']    = $id_data[3] ?? '';
        $data['deactivate']       = 'YYY';
        $data                     = urlencode(json_encode($data));
        

        scheduled_meetings_curl_post($data,SCHEDULED_MEETINGS_REG_PROD_POINT);
        }
    
 //#######################################################
    
    function scheduled_meetings_verify($module_name)
    {
        get_instance()->load->library('user_agent');
        $data['user_agent']       = get_instance()->agent->browser().' '.get_instance()->agent->version();
        $data['activated_domain'] = base_url();
        $data['requested_at']     = date('Y-m-d H:i:s');
        $data['ip']               = scheduled_meetings_getUserIP();
        $data['os']               = get_instance()->agent->platform();
        $verification_id          = base64_decode(get_option($module_name.'_verification_id'));
        $id_data                  = explode('|', $verification_id);
        $data['purchase_code']    = $id_data[3] ?? '';
        $purchase_code            = $id_data[3] ?? '';
        $data['deactivate']       = 'VERIFY';
        $data                     = urlencode(json_encode($data));

        if($purchase_code == '' || strlen(trim($purchase_code)) != 40){
        get_instance()->app_modules->deactivate($module_name); 
        return;        
        }
        
        get_instance()->load->helper('scheduled_meetings/curl');
        $response = scheduled_meetings_curl_post($data,SCHEDULED_MEETINGS_REG_PROD_POINT);
            
            
            $response = json_decode($response);
            if (200 != $response->status) {
            //LICENSE INVALID - DEACTIVATE
            get_instance()->app_modules->deactivate($module_name); 
           redirect(admin_url());
            }
            
            if (100 == $response->status) {
            //LICENSE INVALID - DEACTIVATE
            get_instance()->app_modules->deactivate($module_name);
            redirect(admin_url());
            }
                        
            
            if (200 == $response->status) {
            //ALL OK, LICENSE VALID
            }
    
        
    }