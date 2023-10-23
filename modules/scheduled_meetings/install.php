<?php
defined('BASEPATH') or exit('No direct script access allowed');
$CI  =&get_instance();

//$CI->db->query("DROP TABLE IF EXISTS `".db_prefix()."scheduled_meetings`");
if (!$CI->db->table_exists(db_prefix() . 'scheduled_meetings')) {
    $query = 'CREATE TABLE `' . db_prefix() . "scheduled_meetings` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `meeting_date` varchar(200) NOT NULL,
        `meeting_id` varchar(200) NOT NULL,
        `topic` varchar(200) NOT NULL,
		`agenda` text,
		`notes` text,
        `web_url` varchar(200) NOT NULL,
	   	`app_url` varchar(200) NOT NULL,
        `attendees` text,
        `category` varchar(200) NOT NULL,
        `status` INT NOT NULL,
        `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `date_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `created_by` INT NOT NULL,
        `client_id` INT DEFAULT NULL,
        `project_id` INT DEFAULT NULL,		
PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';';
$CI->db->query($query);
}

//=============================================  Clients
$projects_path    = __DIR__ .'/../../application/controllers/Clients.php';
@chmod($projects_path, FILE_WRITE_MODE);

$projects_file = file_get_contents($projects_path);
$pos = strpos($projects_file, "project_meetings");
if ($pos === false){
if (@copy($projects_path, $projects_path.'.backup') == true) {
unlink($projects_path);
@copy(__DIR__ .'/helpers/perfex_clients.php', $projects_path); 
}
}
@chmod($projects_path, FILE_READ_MODE);

//=============================================  Clients
$projects_path    = __DIR__ .'/../../application/views/themes/perfex/template_parts/projects/project_meetings.php';
@chmod($projects_path, FILE_WRITE_MODE);


if (!file_exists($projects_path)){
unlink($projects_path);
@copy(__DIR__ .'/helpers/project_meetings.php', $projects_path); 

}
@chmod($projects_path, FILE_READ_MODE);
//=============================================  Clients
$projects_path    = __DIR__ .'/../../application/views/themes/perfex/template_parts/projects/project_tabs.php';
@chmod($projects_path, FILE_WRITE_MODE);

$projects_file = file_get_contents($projects_path);
$pos = strpos($projects_file, "project_meetings");
if ($pos === false){
if (@copy($projects_path, $projects_path.'.backup') == true) {
unlink($projects_path);
@copy(__DIR__ .'/helpers/perfex_project_tabs.php', $projects_path); 
}
}
@chmod($projects_path, FILE_READ_MODE);
//=============================================  Clients