<?php
defined('BASEPATH') || exit('No direct script access allowed');
$CI  =&get_instance();	
$aColumns = [
   // 'id',
    'meeting_date',
    'meetingid',
    'topic',
    'web_url',
	'app_url',
    'category',
    'status',
    'created_by',
	//'user_type',
    //'user_email',
    //'user_fullname',
      ];
    


$where = ['AND status=0', ];
$join = [];//['LEFT JOIN ' . db_prefix() . 'zmm_participants ON ' . db_prefix() . 'zmm_participants.meeting_id = ' . db_prefix() . 'scheduled_meetings.meetingid',];	

    $sIndexColumn = 'id';
    $sTable       = db_prefix().'scheduled_meetings';
    $result       = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [db_prefix().'scheduled_meetings.id']);
    $output       = $result['output'];
    $rResult      = $result['rResult'];
/*
$f = fopen(__DIR__ .'/test.txt','w');
fwrite($f,print_r($rResult,true));
fclose($f);*/
$c=1;
foreach ($rResult as $aRow) {
    
    
if($aRow['status'] == 0){
$status = '<span class="btn radius badge-light-pink">Cancelled</span>';//cancelled
}else if($aRow['status'] == 1){
$status = '<span class="btn radius badge-light-green">New</span>';//new
}else if($aRow['status'] == 2){
$status = '<span class="btn radius badge-light-blue">Re-Scheduled</span>';//rescheduled
}else if($aRow['status'] == 3){
$status = '<span class="btn radius badge-light-orange">Upcoming</span>';//upcoming
}else if($aRow['status'] == 4){
$status = '<span class="btn radius badge-light-green">Done</span>';//done
}

if($aRow['category'] == 'New Project'){
$category = '<span class="btn radius badge-light-green">New Project</span>';//new
}else if($aRow['category'] == 'Existing Project'){
$category = '<span class="btn radius badge-light-orange">Existing Project</span>';//existing
}else{
$category = '<span class="btn radius badge-light-purple">General Inquiry</span>';//inquiry
}


    
//$attendees = $aRow['attendees']; 
$web_url = $aRow['web_url']; 
$app_url = $aRow['app_url'];
$created_by = $aRow['created_by']; 
//$user_email = $aRow['user_email']; 
//$user_fullname = $aRow['user_fullname'];    
 
    $row = [];
    $row[] = $c;//$aRow['id'];
    $row[] = $aRow['meeting_date'];
    $row[] = $aRow['topic'];
    $row[] = $category;
    $row[] = $status;
    $row[] = '<a href="edit/'.$aRow['id'].'" onclick="return confirm(\'Do you want to Edit?\');"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;
    <a href="delete/'.$aRow['id'].'" onclick="return confirm(\'Do you want to Delete?\');"><i class="fa fa-trash" style="color:red;"></i></a>&nbsp;&nbsp;
    <a href="userprofile/view/'.$aRow['id'].'"><i class="fa fa-eye" style="color:green;"></i></a>&nbsp;&nbsp;';

$output['aaData'][] = $row;
$c++;
}