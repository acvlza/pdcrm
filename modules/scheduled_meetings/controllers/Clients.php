<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Clients extends ClientsController
{
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
       // $this->load->model('super_email_model');
    }

    /* View all settings */
    public function index()
    {
     $contactuserid = get_contact_user_id();
    if($contactuserid == ''){
    redirect(site_url());
    }
    }
	
public function client_request(){
//exit('success');

		$data = $this->input->post();
		//$raw = explode('&',$post_data['data']);
		//parse_str($post_data['data'], $data);
		$data['description'] =  $data['agenda'];
		$data['reminder'] = 2;
	    $data['notes'] = '';
        $data['password'] =''; 
        $data['contacts'] = array(0=>get_contact_user_id(),);
		$data['staff'] = array(0=>1,);

		
		/*
		$f=fopen(__DIR__ .'/client.txt','w');
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


$type = $data['type'];
unset($data['type']);
if($type=='request'){
$confirmation = 'Meeting Scheduled on';		
}else{
$confirmation ='';	
}
$message = array(
    'msg'=> 'success',
	'dateandtime' => $data['date'],
    'allattendees'=>$attendees,
	'allattendees'=>$attendees,
	'confirmation'=>$confirmation,
	);

$json = json_encode($message);
	
	
		$project_id = $data['project'];
		$notes = $data['notes'];
		$category = $data['category'];
		$reminder = $data['reminder'];
		unset($data['project']);
		unset($data['notes']);
		unset($data['category']);
		unset($data['reminder']);
		unset($data['agenda']);
		
		//print_r($data);exit;
		if ($data) {
			$meeting_data = $this->zoom->createMeeting($data);
			//zmm_redirect_after_event('success', _l('zmm_meeting_created'));
			//$meeting_id=$this->db->select('meeting_id')->order_by('id',"desc")->limit(1)->get(db_prefix().'zmm_participants')->row();
			
			//echo ($meeting_data['id']);exit;
			
			if($meeting_data->id != ''){
			
			$web_url = str_replace('j/', 'wc/join/', $meeting_data->join_url);
			
			$meId = get_contact_user_id();
			$sInfo = $this->db->query("select `firstname`, `lastname` from `".db_prefix()."contacts` where id='$meId'")->row();
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
									
			$return = $this->save_client_meeting($scheduled_meeting_data);
			if($return == 'success'){exit($json);}else{exit('error');}
			
			}else{
			return 'error';	
			}
			
		}

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
public function client_cancel($id=''){
$this->delete_zoom_meeting($id);//delete zoom meeting
//update meeting status
$this->db->where('id',$id);
$updated_status = $this->db->update(db_prefix().'scheduled_meetings',['status'=>0,]);
if($updated_status ){
$message = array('msg'=> 'success',);
}else{
$message = array('msg'=> 'error',);
}
$json = json_encode($message);
exit($json);	
}

//===========================================================
public function client_update($id=''){


		$data = $this->input->post();
		
		//$raw = explode('&',$post_data['data']);
		//parse_str($post_data['data'], $data);
		$data['description'] =  $data['agenda'];
		$data['reminder'] = 2;
	    $data['notes'] = '';
        $data['password'] =''; 
        $data['contacts'] = array(0=>get_contact_user_id(),);
		$data['staff'] = array(0=>1,);

		
		/*
		$f=fopen(__DIR__ .'/client.txt','w');
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


$type = $data['type'];
unset($data['type']);
if($type=='edit'){
$confirmation = 'Meeting Successfully Edited!';	
}elseif($type=='reschedule'){
$confirmation = 'Meeting Successfully Re-Scheduled!';		
}
$message = array(
    'msg'=> 'success',
	'dateandtime' => $data['date'],
    'allattendees'=>$attendees,
	'allattendees'=>$attendees,
	'confirmation'=>$confirmation,
	);

$json = json_encode($message);


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
		
		//print_r($data);exit;
		if ($data) {
			$this->delete_zoom_meeting($id);//delete existing zoom meeting
			$meeting_data = $this->zoom->createMeeting($data);
			//zmm_redirect_after_event('success', _l('zmm_meeting_created'));
			//$meeting_id=$this->db->select('meeting_id')->order_by('id',"desc")->limit(1)->get(db_prefix().'zmm_participants')->row();
			
			//echo ($meeting_data['id']);exit;
			
			if($meeting_data->id != ''){
			
			$web_url = str_replace('j/', 'wc/join/', $meeting_data->join_url);
			
			$meId = get_contact_user_id();
			$sInfo = $this->db->query("select `firstname`, `lastname` from `".db_prefix()."contacts` where id='$meId'")->row();
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
									
			$return = $this->update_client_meeting($id, $scheduled_meeting_data);
			if($return == 'success'){exit($json);}else{exit('error');}
			
			}else{
			return 'error';	
			}
			
		}

}
//================================================
public function update_client_meeting($id, $meeting_data){
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
public function save_client_meeting($meeting_data){
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
//===========================================	
public function meeting_list(){
/*$contactuserid = get_contact_user_id();
$this->db->where('client_id',$contactuserid);
$result = $this->db->get('scheduled_meetings')->num_rows();
$data['meetings'] = $result;*/

 $this->db->where('id', get_contact_user_id());
 $result = $this->db->get(db_prefix() . 'contacts')->row();
 $userid = $result->userid;
 
 //$this->db->where('user_email', $email);
 //$result = $this->db->get(db_prefix() . 'zmm_participants')->row();
 //$email = $result->email;
 
 //$contact_id = get_contact_user_id();
//$this->db->where_in('contact_ids', [$contact_id]);
// $where = ['contact_id' => get_contact_user_id(),];

       // if (is_numeric($status)) {
       //     $where['status'] = $status;
       // }

        $data['meetings'] = $this->db->get(db_prefix() . 'scheduled_meetings')->result_array();
        
        $this->data($data);
        $this->view('client_meetings_list');
        $this->layout();

} 

public function meeting_details($id=''){
/*$contactuserid = get_contact_user_id();
$this->db->where('client_id',$contactuserid);
$result = $this->db->get('scheduled_meetings')->num_rows();
$data['meetings'] = $result;*/

      //  $where = ['client_id' => get_contact_user_id(), 'id' => $id ];

       // if (is_numeric($status)) {
       //     $where['status'] = $status;
       // }
	   
	 
        $data['meeting'] = $this->scheduled_meetings_model->get_meeting($id);
		        
        $this->data($data);
        $this->view('client_meeting_details');
        $this->layout();

}


public function client_meetings_table()
{
       
if ($this->input->is_ajax_request()) {
$this->app->get_table_data(module_views_path('scheduled_meetings', 'tables/client_meetings_list_table'));
}
}   
//======================================================================================
public function date_difference($date){
$date1 = date_create($date);
$date2 = date_create(date("Y-m-d"));
$diff=date_diff($date1,$date2);
$months = $diff->format("%m months");
$years = $diff->format("%y years");
//$days = $diff->format("%d days");
$days = $diff->format("%d");
return $days;
}
//======================================================================================
public function days_difference($date){
$date1 = date_create($date);
$date2 = date_create(date("Y-m-d"));
$diff=date_diff($date1,$date2);
$months = $diff->format("%m months");
$years = $diff->format("%y years");
//$days = $diff->format("%d days");
$days = $diff->format("%d");
return $days;
}
      
}