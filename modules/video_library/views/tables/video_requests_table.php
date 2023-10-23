<?php
defined('BASEPATH') || exit('No direct script access allowed');
$CI  =&get_instance();

//fieldnames below	
$aColumns = [
'id',
'project_id',
'request_date',

];

$sIndexColumn = 'id';
$sTable       = db_prefix().'video_requests';
$result       = data_tables_init($aColumns, $sIndexColumn, $sTable, [], [], [db_prefix().'video_requests.id']);
$output       = $result['output'];
$rResult      = $result['rResult'];


foreach ($rResult as $aRow) {
	
$CI->db->where('id', $aRow['project_id']);
$result = $CI->db->get(db_prefix() . 'projects')->row();
if($result){
$project_name= '<a target="_blank" href="'.admin_url('projects/view/'.$aRow['project_id']).'">'.$result->name.'</a>';	
}else{
$project_name ='';
}	
$row = [];

$row[] = $aRow['id'];
$row[] = $project_name;
$row[] = $aRow['request_date'];

$row[] = '<a href="'.admin_url('video_library/video_request_delete/'.$aRow['id']).'" onclick="return confirm(\'Do you want to Delete?\');"><i class="fa fa-trash" style="color:red;"></i></a>&nbsp;&nbsp;';

$output['aaData'][] = $row;
}
