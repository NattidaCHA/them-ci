<?php
$config['site_name'] = 'Notification system';
$config['site_thumb'] = '';
$config['site_author'] = '';

$config['google_verify'] = '';
$config['fb_app_id'] = '';
$config['fb_app_secrete'] = '';
$config['yt_data_key'] = 'AIzaSyBdaSImGcpAD_SarVBFaj-ZbOMYaql4x8s';


$day = [
    ['id' => 'Sunday', 'name' => 'วันอาทิตย์', 'sort' => 1],
    ['id' => 'Monday', 'name' => 'วันจันทร์', 'sort' => 2],
    ['id' => 'Tuesday', 'name' => 'วันอังคาร', 'sort' => 3],
    ['id' => 'Wednesday', 'name' => 'วันพุธ', 'sort' => 4],
    ['id' => 'Thursday', 'name' => 'วันพฤหัสบดี', 'sort' => 5],
    ['id' => 'Friday', 'name' => 'วันศุกร์', 'sort' => 6],
    ['id' => 'Saturday', 'name' => 'วันเสาร์', 'sort' => 7],
];
$config['day'] = json_decode(json_encode($day));
