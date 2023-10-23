<?php
@ini_set('max_execution_time', 240);

defined('BASEPATH') or exit('No direct script access allowed');

class Scheduled_meetings extends AdminController
{
    private $ci;

    public function __construct()
    {
        parent::__construct();
        $this->ci = &get_instance();
		$this->load->library('Zoom_Meeting_Manager');
		//require_once(FCPATH . 'modules/zoom_meeting_manager/libraries/Zoom_Meeting_Manager.php');
		require_once(FCPATH . 'modules/zoom_meeting_manager/models/ZoomMeetingManager.php');
		require_once(FCPATH . 'modules/zoom_meeting_manager/models/ZoomParticipantsModel.php');
		$this->zoom = new Zoom_Meeting_Manager();

		if (!$this->zoom->isAuth()) {
			$this->zoom->revalidateToken();
		}
	     
        $this->load->model('scheduled_meetings_model');
		$this->load->model('projects_model');
        }

public function index()
{
if (!has_permission('scheduled_meetings', '', 'view') || !has_permission('scheduled_meetings', '', 'view_own')) {access_denied('scheduled_meetings');}

$this->db->where('status',0);
$result = $this->db->get('scheduled_meetings')->num_rows();
$data['cancelled_meetings'] = $result;

$this->db->where('status',1);
$result = $this->db->get('scheduled_meetings')->num_rows();
$data['new_meetings'] = $result;

$this->db->where('status',2);
$result = $this->db->get('scheduled_meetings')->num_rows();
$data['rescheduled_meetings'] = $result;

$this->db->where('status',1);
$this->db->or_where('status',2);
$result = $this->db->get('scheduled_meetings')->num_rows();
$data['upcoming_meetings'] = $result;

$this->db->where(['status'=>4,]);
$result = $this->db->get('scheduled_meetings')->num_rows();
$data['completed_meetings'] = $result;

$data['title'] = 'Scheduled Meetings';
$this->load->view('meetings_list',$data);
}


public function create_new_meeting()
{
if (!has_permission('scheduled_meetings', '', 'create')) {access_denied('scheduled_meetings');}

$where=[];
		$data = [
			'staff_members' => $this->staff_model->get('', ['active' => 1]),
			'projects' => $this->projects_model->get('', $where),
			'rel_type' => 'lead',
			'rel_contact_type' => 'contact',
			'rel_contact_id' => '',
			'rel_id' => '',
			'user' => $this->zoom->me()
		];
$data['title'] = 'Create New Meeting';
$this->load->view('create_new_meeting',$data);
}


	public function ZoomcreateMeeting()
	{
		if (!staff_can('create', 'zoom_meeting_manager')) {
			access_denied('Zoom Meeting Manager');
		}

		$data = [
			'staff_members' => $this->staff_model->get('', ['active' => 1]),
			'rel_type' => 'lead',
			'rel_contact_type' => 'contact',
			'rel_contact_id' => '',
			'rel_id' => '',
			'user' => $this->zoom->me()
		];

		$this->load->view('zoom_create', $data);
	}

	public function create()
	{
		$post_data = $this->input->post();
		//$raw = explode('&',$post_data['data']);
		parse_str($post_data['data'], $data);
		/*
		$f=fopen(__DIR__ .'/test.txt','w');
		fwrite($f,print_r($data,true));
		fclose($f);exit;*/
		
$attendees='';
foreach($data['staff'] as $sid){
$this->db->where('staffid', $sid);
$result = $this->db->get(db_prefix() . 'staff')->row();
$attendees .= $result->firstname.' '.$result->lastname.', ';
}
foreach($data['contacts'] as $cid){
$this->db->where('id', $cid);
$result = $this->db->get(db_prefix() . 'contacts')->row();
$attendees .= $result->firstname.' '.$result->lastname.', ';
}
$attendees = rtrim(trim($attendees),',');


$message = array(
    'msg'=> 'success',
	'dateandtime' => $data['date'],
    'allattendees'=>$attendees);

$json = json_encode($message);
	
	
		$project_id = $data['project'];
		$notes = $data['notes'];
		$category = $data['category'];
		$reminder = $data['reminder'];
		unset($data['project']);
		unset($data['notes']);
		unset($data['category']);
		unset($data['reminder']);
		
		
		//print_r($data);exit;
		if ($data) {
			$meeting_data = $this->zoom->createMeeting($data);
			//zmm_redirect_after_event('success', _l('zmm_meeting_created'));
			//$meeting_id=$this->db->select('meeting_id')->order_by('id',"desc")->limit(1)->get(db_prefix().'zmm_participants')->row();
			
			//echo ($meeting_data['id']);exit;
			
			if($meeting_data->id != ''){
			
			$web_url = str_replace('j/', 'wc/join/', $meeting_data->join_url);
			
			$meId = (null !== $this->session->userdata("staff_user_id")) ? $this->session->userdata("staff_user_id") : 1;
			$sInfo = $this->db->query("select `firstname`, `lastname` from `".db_prefix()."staff` where staffid='$meId'")->row();
			$created_by = $sInfo->firstname.' '.$sInfo->lastname;
			
			$scheduled_meeting_data = array(
			'meeting_date' => $data['date'],
			'meetingid' => $meeting_data->id,
			'topic' => $meeting_data->topic,
			'agenda' => $meeting_data->agenda,
			'notes' => $notes,
			'web_url' => $web_url,
			'app_url' => $meeting_data->join_url,
			'category' => $category,
			'status' => 1,
			'created_by' => $created_by,
			'project_id' => $project_id,
			'contact_ids' => implode(',',$data['contacts']),
			'reminder' => $reminder
			);
									
			$return = $this->save_meeting($scheduled_meeting_data);
			if($return == 'success'){exit($json);}else{exit('error');}
			
			}else{
			return 'error';	
			}
			
		}
	}
	
	
public function save_meeting($meeting_data){
	/*
$f=fopen(__DIR__ . '/t.txt','w');
fwrite($f,print_r($meeting_data,true));
fclose($f);	*/
//$this->scheduled_meetings_model->save($meeting_data);
$this->db->insert(db_prefix().'scheduled_meetings',$meeting_data);
$insert_id = $this->db->insert_id();
if($insert_id ){
return 'success';	
}else{
return 'error';	
}

}
//===========================================================	
public function delete($id){
  $this->db->where('id', $id);
  $result = $this->db->get(db_prefix() . 'scheduled_meetings')->row();
  $MeetingID = $result->meetingid;
  $this->db->where('id', $id);
  $this->db->delete(db_prefix() . 'scheduled_meetings');
  
  $this->zoom->deleteMeeting($MeetingID);
  
  log_activity('Delete Meeting: (MeetingID: '.$MeetingID.') '.date('Y-m-d H:i:s'),$this->session->userdata("staff_user_id"));

        set_alert('success', _l('deleted', "Meeting"));
        redirect(admin_url('scheduled_meetings/index'));  
}
//===========================================================
private function delete_zoom_meeting($id){
  $this->db->where('id', $id);
  $result = $this->db->get(db_prefix() . 'scheduled_meetings')->row();
  $MeetingID = $result->meetingid;
  //$this->db->where('id', $id);
  //$this->db->delete(db_prefix() . 'scheduled_meetings');
  
  $this->zoom->deleteMeeting($MeetingID);
  
 // log_activity('Delete Meeting: (MeetingID: '.$MeetingID.') '.date('Y-m-d H:i:s'),get_contact_user_id());

       // set_alert('success', _l('deleted', "Meeting"));
       // redirect(admin_url('scheduled_meetings/index'));  
}
//===========================================================
public function edit($id=''){
if (!has_permission('scheduled_meetings', '', 'edit') && !is_admin()) {access_denied('scheduled_meetings');}

$where=[];
		$data = [
			'staff_members' => $this->staff_model->get('', ['active' => 1]),
			'projects' => $this->projects_model->get('', $where),
			'rel_type' => 'lead',
			'rel_contact_type' => 'contact',
			'rel_contact_id' => '',
			'rel_id' => '',
			'user' => $this->zoom->me()
		];
$data['title'] = 'Edit Meeting';	
	
$this->db->where('id', $id);
$data['meeting'] = $this->db->get(db_prefix() . 'scheduled_meetings')->row();
$this->load->view('edit_meeting', $data);  
}
	
//===========================================================
public function admin_update($id=''){


		$data = $this->input->post();
		$test=[];
		foreach($data['mystaff'] as $staff){
		foreach($staff as $key=>$staffie){
		if($key =='value'){	
		$test[] = $staffie;	
		}
		}
		}
		$data['staff']=$test;
	  	$data['description'] =  $data['agenda'];
		$data['password'] =''; 
	    
		/*
		$f=fopen(__DIR__ .'/test.txt','w');
		fwrite($f,print_r($data,true));
		fclose($f);exit;
		*/
$attendees='';
foreach($data['staff'] as $sid){
$this->db->where('staffid', $sid);
$result = $this->db->get(db_prefix() . 'staff')->row();
$attendees .= $result->firstname.' '.$result->lastname.', ';
}
foreach($data['contacts'] as $cid){
$this->db->where('id', $cid);
$result = $this->db->get(db_prefix() . 'contacts')->row();
$attendees .= $result->firstname.' '.$result->lastname.', ';
}
$attendees = rtrim(trim($attendees),',');

/*
$type = $data['type'];
unset($data['type']);
if($type=='edit'){
$confirmation = 'Meeting Successfully Edited!';	
}elseif($type=='reschedule'){
$confirmation = 'Meeting Successfully Re-Scheduled!';		
}*/

$message = array(
    'msg'=> 'success',
	'dateandtime' => $data['date'],
    'allattendees'=>$attendees,
	//'allattendees'=>$attendees,
	//'confirmation'=>$confirmation,
	);

$json = json_encode($message);

$error = array(
    'msg'=> 'error',
	'dateandtime' => $data['date'],
    'allattendees'=>$attendees,
	//'allattendees'=>$attendees,
	//'confirmation'=>$confirmation,
	);
$jsonerror = json_encode($error);	
	
//exit($json);

	
		$project_id = $data['project'];
		$notes = $data['notes'];
		$category = $data['category'];
		$reminder = $data['reminder'];
		unset($data['project']);
		unset($data['notes']);
		unset($data['category']);
		unset($data['reminder']);
		unset($data['agenda']);
		unset($data['mystaff']);
		//print_r($data);exit;
		if ($data) {
			$this->delete_zoom_meeting($id);//delete existing zoom meeting
			$meeting_data = $this->zoom->createMeeting($data);
		/*	
		$f=fopen(__DIR__ .'/test.txt','w');
		fwrite($f,print_r($meeting_data,true));
		fclose($f);
			*/
			//zmm_redirect_after_event('success', _l('zmm_meeting_created'));
			//$meeting_id=$this->db->select('meeting_id')->order_by('id',"desc")->limit(1)->get(db_prefix().'zmm_participants')->row();
			
			//echo ($meeting_data['id']);exit;
			//exit($jsonerror);
			if($meeting_data->id != ''){
			
			$web_url = str_replace('j/', 'wc/join/', $meeting_data->join_url);
			
			$meId = (null !== $this->session->userdata("staff_user_id")) ? $this->session->userdata("staff_user_id") : 1;
            $sInfo = $this->db->query("select `firstname`, `lastname` from `".db_prefix()."staff` where staffid='$meId'")->row();
			$created_by = $sInfo->firstname.' '.$sInfo->lastname;
			//update existing meeting
			$scheduled_meeting_data = array(
			'meeting_date' => $data['date'],
			'meetingid' => $meeting_data->id,
			'topic' => $meeting_data->topic,
			'agenda' => $meeting_data->agenda,
			'notes' => $notes,
			'web_url' => $web_url,
			'app_url' => $meeting_data->join_url,
			'category' => $category,
			'status' => 2,//re-scheduled
			'created_by' => $created_by,
			'project_id' => $project_id,
			'contact_ids' => implode(',',$data['contacts']),
			'reminder' => $reminder
			);
									
			$return = $this->update_admin_meeting($id, $scheduled_meeting_data);
			if($return == 'success'){exit($json);}else{exit($jsonerror);}
			
			}else{
			exit($jsonerror);	
			}
			
		}

}
//================================================
public function update_admin_meeting($id, $meeting_data){
	/*
$f=fopen(__DIR__ . '/t.txt','w');
fwrite($f,print_r($meeting_data,true));
fclose($f);	*/
//$this->scheduled_meetings_model->save($meeting_data);
$this->db->where('id',$id);
$updated_status = $this->db->update(db_prefix().'scheduled_meetings',$meeting_data);
if($updated_status ){
return 'success';	
}else{
return 'error';	
}

}
//===========================================	
	
	
public function meetings_table()
{
       
if ($this->input->is_ajax_request()) {
$this->app->get_table_data(module_views_path('scheduled_meetings', 'tables/meetings_list_table'));
}
}
public function cancelled_meetings_table()
{
       
if ($this->input->is_ajax_request()) {
$this->app->get_table_data(module_views_path('scheduled_meetings', 'tables/cancelled_meetings_list_table'));
}
}

public function upcoming_meetings_table()
{
       
if ($this->input->is_ajax_request()) {
$this->app->get_table_data(module_views_path('scheduled_meetings', 'tables/upcoming_meetings_list_table'));
}
}

public function rescheduled_meetings_table()
{
       
if ($this->input->is_ajax_request()) {
$this->app->get_table_data(module_views_path('scheduled_meetings', 'tables/rescheduled_meetings_list_table'));
}
}

public function new_meetings_table()
{
       
if ($this->input->is_ajax_request()) {
$this->app->get_table_data(module_views_path('scheduled_meetings', 'tables/new_meetings_list_table'));
}
}

public function add()
{
if (!has_permission('scheduled_meetings', '', 'view') && !is_admin()) {access_denied('scheduled_meetings');}

if ($this->input->post()) {
$scheduled_meetings_enabled = $this->input->post('scheduled_meetings_enabled');
$scheduled_meetings_content = html_purify($this->input->post('scheduled_meetings_content', false));
$scheduled_meetings_background_color = $this->input->post('scheduled_meetings_background_color');

//echo $scheduled_meetings_content;exit;
update_option('scheduled_meetings_enabled',$scheduled_meetings_enabled);
update_option('scheduled_meetings_content',$scheduled_meetings_content);
update_option('scheduled_meetings_background_color',$scheduled_meetings_background_color);
set_alert('success', _l('updated_successfully', 'NewsTicker'));
redirect(admin_url('scheduled_meetings/index'));
}

$data['title'] = 'Scheduled Meetings';
$this->load->view('news',$data);
}

public function cancelled()
{
if (!has_permission('scheduled_meetings', '', 'view') || !has_permission('scheduled_meetings', '', 'view_own') && !is_admin()) {access_denied('scheduled_meetings');}

$data['title'] = 'Cancelled Meetings';
$this->load->view('cancelled_meetings_list',$data);
}

public function new_meetings()
{
if (!has_permission('scheduled_meetings', '', 'view') || !has_permission('scheduled_meetings', '', 'view_own') && !is_admin()) {access_denied('scheduled_meetings');}

$data['title'] = 'New Meetings';
$this->load->view('new_meetings_list',$data);
}

public function upcoming()
{
if (!has_permission('scheduled_meetings', '', 'view') && !is_admin()) {access_denied('scheduled_meetings');}

if (!has_permission('scheduled_meetings', '', 'view') || !has_permission('scheduled_meetings', '', 'view_own') && !is_admin()) {access_denied('scheduled_meetings');}

$data['title'] = 'Upcoming Meetings';
$this->load->view('upcoming_meetings_list',$data);
}

public function rescheduled()
{
if (!has_permission('scheduled_meetings', '', 'view') && !is_admin()) {access_denied('scheduled_meetings');}

if (!has_permission('scheduled_meetings', '', 'view') || !has_permission('scheduled_meetings', '', 'view_own') && !is_admin()) {access_denied('scheduled_meetings');}

$data['title'] = 'Rescheduled Meetings';
$this->load->view('rescheduled_meetings_list',$data);
}

public function link_list()
{
if (!has_permission('scheduled_meetings', '', 'view') && !is_admin()) {access_denied('scheduled_meetings');}

if (!has_permission('scheduled_meetings', '', 'view') || !has_permission('scheduled_meetings', '', 'view_own') && !is_admin()) {access_denied('scheduled_meetings');}

$data['title'] = 'Meetings Link List';
$this->load->view('meetings_link_list',$data);
}
public function meetings_link_list_table()
{
       
if ($this->input->is_ajax_request()) {
$this->app->get_table_data(module_views_path('scheduled_meetings', 'tables/meetings_link_list_table'));
}
}
    
    
}
