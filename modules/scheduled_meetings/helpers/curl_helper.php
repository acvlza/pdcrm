<?php
defined('BASEPATH') or exit('No direct script access allowed');

function scheduled_meetings_curl_post($json,$url)
{
$CI = & get_instance();

$ch = curl_init($url); 
curl_setopt($ch, CURLOPT_URL, $url."?data=".$json);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$result = curl_exec($ch);
curl_close($ch);
return $result;
}

