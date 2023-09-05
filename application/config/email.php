<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// $config = array(
//     "protocol" => "mail",smtp
//     "smtp_host" => "smtp.office365.com",
//     "smtp_port" => 587, //587
//     "smtp_crypto" => "tls",
//     "smtp_user" => "nattidac@scg.com",
//     "smtp_pass" => "Year@2023",
//     "mailtype" => "text",
//     "priority" => 3,
//     "newline" => "\r\n",
//     "crlf" => "\r\n",
//     "charset" => "utf-8",
//     "smtp_timeout" => 7,
//     "wordwrap" => FALSE
// );

$config['protocol'] = "smtp";
$config['smtp_host'] = 'smtp.office365.com';
// $config['smtp_host'] = "soms.scg.com";
// $config['smtp_host'] = 'ssl://soms.scg.com';
$config['smtp_port'] = 587;
// $config['smtp_user'] = "nattidac@scg.com";
// $config['smtp_pass'] = "Year@2023";
$config['smtp_user'] = "nan_zen0003@hotmail.com";
$config['smtp_pass'] = "!Ohsehun1228"; 
$config['smtp_crypto'] = 'tls';
$config['smtp_timeout'] = 7;
$config['charset'] = "utf-8";
$config['mailtype'] = "html"; //html
$config['crlf'] = "\r\n";
$config['newline'] = "\r\n";
$config['wordwrap'] = TRUE;
