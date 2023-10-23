<?php defined('BASEPATH') or exit('No direct script access allowed');

class Client_video_requests extends ClientsController
{
public function __construct()
{
parent::__construct();


}

public function index()
{


}

//=========================================
public function add($id = '')
{
$data=[];

$data['project_id'] = $id;
$data['request_date'] = date('Y-m-d H:i:s');

if ($id != '') {
$this->db->insert(db_prefix() . 'video_requests', $data);
$insert_id = $this->db->insert_id();
if ($insert_id) {
log_activity('New Video Request Added');	
set_alert('success', _l('added_successfully', 'Video Request'));
redirect(site_url('video_library/client/project/'.$id.'?group=video_library'));
}
}
}

	
public function approve_milestone(){
$id= $this->input->post('id');	
$data['approval']='Approved';
//print_r($data);exit;
$this->db->where('id',$id);
$res = $this->db->update(db_prefix() . 'milestones', $data);
if($res ){
exit('success');	
}else{
exit('error');		
}
}

public function decline_milestone(){
$id= $this->input->post('id');		
$data['approval']='Declined';
//print_r($data);exit;
$this->db->where('id',$id);
$res = $this->db->update(db_prefix() . 'milestones', $data);
if($res ){
exit('success');	
}else{
exit('error');		
}

}



}
