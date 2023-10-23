<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Scheduled_meetings_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }
	
public function get_meetings($id='', $where = [])
{

//if($id==''){
return $this->db->get(db_prefix() . 'scheduled_meetings')->result_array();
//}else{
//return $this->db->get(db_prefix() . 'scheduled_meetings')->row();	
//}

}

public function get_meeting($id='')
{
$this->db->where('id',$id);	
return $this->db->get(db_prefix() . 'scheduled_meetings')->row();	
}

public function save($data){
$this->db->insert('scheduled_meetings',$data);
$insert_id = $this->db->insert_id();
if($insert_id ){
return 'success';	
}else{
return 'error';	
}
}	

    public function addParticipantsToMeetingTable($participants, $meeting_id)
    {
        foreach ($participants as $type => $participants_array) {
            foreach ($participants_array as $participant) {
                if ($type == 'staff') {
                    $this->insertParticipant($meeting_id, $participant, 'staff');
                }
                if ($type == 'leads') {
                    $this->insertParticipant($meeting_id, $participant, 'lead');
                }
                if ($type == 'contacts') {
                    $this->insertParticipant($meeting_id, $participant, 'contact');
                }
            }
        }
    }

    private function insertParticipant($meeting_id, $participant, $type)
    {
        $this->db->insert(
            ZMM_TABLE_PARTICIPANTS,
            [
                'meeting_id'    => $meeting_id,
                'user_type'     => $type,
                'user_email'    => $participant->email,
                'user_fullname' => $participant->firstname . ' ' . $participant->lastname
            ]
        );
    }

}
