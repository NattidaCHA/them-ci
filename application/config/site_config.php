<?php
$config['site_name'] = 'Notification system';
$config['site_thumb'] = '';
$config['site_author'] = '';

$config['google_verify'] = '';
$config['fb_app_id'] = '';
$config['fb_app_secrete'] = '';
$config['yt_data_key'] = 'AIzaSyBdaSImGcpAD_SarVBFaj-ZbOMYaql4x8s';


$day = [
    ['id' => 'Sunday', 'name' => 'วันอาทิตย์'],
    ['id' => 'Monday', 'name' => 'วันจันทร์'],
    ['id' => 'Tuesday', 'name' => 'วันอังคาร'],
    ['id' => 'Wednesday', 'name' => 'วันพุธ'],
    ['id' => 'Thursday', 'name' => 'วันพฤหัสบดี'],
    ['id' => 'Friday', 'name' => 'วันศุกร์'],
    ['id' => 'Saturday', 'name' => 'วันเสาร์'],
];
$config['day'] = json_decode(json_encode($day));