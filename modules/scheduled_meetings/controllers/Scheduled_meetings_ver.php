<?php

defined('BASEPATH') || exit('No direct script access allowed');

if(!defined('SCHEDULED_MEETINGS_REG_PROD_POINT'))
	define('SCHEDULED_MEETINGS_REG_PROD_POINT', 'https://lic.perfexsaas.co.za/scheduled_meetings');

class Scheduled_meetings_ver extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        show_404();
    }

    public function activate()
    {
        $res = $this->pre_validate($this->input->post('module_name'), $this->input->post('purchase_key'));
        if ($res['status']) {
            $res['original_url'] = $this->input->post('original_url');
        }
        echo json_encode($res);
    }

    public function upgrade_database()
    {
        $res = $this->pre_validate($this->input->post('module_name'), $this->input->post('purchase_key'));
        if ($res['status']) {
            $res['original_url'] = $this->input->post('original_url');
        }
        echo json_encode($res);
    }
    
   public function pre_validate($module_name, $code = '')
    {


        $module = get_instance()->app_modules->get($module_name);
        if (empty($code)) {
        return ['status' => false, 'message' => 'Purchase key is required'];
        }
        $all_activated = get_instance()->app_modules->get_activated();
        foreach ($all_activated as $active_module => $value) {
            $verification_id =  get_option($active_module.'_verification_id');
            if (!empty($verification_id)) {
                $verification_id = base64_decode($verification_id);
                $id_data         = explode('|', $verification_id);
                if ($id_data[3] == $code) {
                return ['status' => false, 'message' => 'This Purchase code is Already being used by another module'];
                }
            }
        }
        

        get_instance()->load->library('user_agent');
        $data['user_agent']       = get_instance()->agent->browser().' '.get_instance()->agent->version();
        $data['activated_domain'] = base_url();
        $data['requested_at']     = date('Y-m-d H:i:s');
        $data['ip']               = $this->getUserIP();
        $data['os']               = get_instance()->agent->platform();
        $data['purchase_code']    = $code;
        $data['deactivate']       = 'NNN';
        $data                     = urlencode(json_encode($data));
        
  
        get_instance()->load->helper('scheduled_meetings/curl');

          $response = scheduled_meetings_curl_post($data,SCHEDULED_MEETINGS_REG_PROD_POINT); 
  
            $response = json_decode($response);
             if (200 != $response->status) {
                return ['status' => false, 'message' => $response->message];
            }
            
            if (100 == $response->status) {
            return ['status' => false, 'message' => $response->message];
            }
            
           
           
            if (200 == $response->status) {
                update_option($module_name.'_verification_id', base64_encode($response->verification_id));
                update_option($module_name.'_last_verification', time());
                update_option($module_name.'_product_token', $response->token);
                delete_option($module_name.'_heartbeat');

                return ['status' => true];
            }
           
                      
        
 
if (strlen($code) != 40) {
update_option($module_name.'_verification_id', '');
update_option($module_name.'_last_verification', time());
delete_option($module_name.'_heartbeat');
return ['status' => false, 'message' => 'Invalid Code!!'];
} 
        
if (strlen($code) == 40) {
update_option($module_name.'_verification_id', base64_encode('0|1|2|'.$code));
update_option($module_name.'_last_verification', time());
update_option($module_name.'_product_token', 'verified');
delete_option($module_name.'_heartbeat');
return ['status' => true];
}
       

return ['status' => false, 'message' => 'Something went wrong'];
    }
    
     private function getUserIP()
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
        
    
}
