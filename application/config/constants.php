<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') or define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  or define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') or define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   or define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  or define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           or define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     or define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       or define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  or define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   or define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              or define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            or define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       or define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        or define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          or define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         or define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   or define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  or define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') or define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     or define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       or define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      or define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      or define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code


//vw_billpay_txt02
defined('REPORT')      or define('REPORT', 'tran_report_notification');
defined('CUSTOMER')      or define('CUSTOMER', 'master_customer');
defined('VW_Customer')      or define('VW_Customer', 'vw_Customer_DWH');
defined('CUST_NOTI')      or define('CUST_NOTI', 'cust_notification');
defined('TBL_CUT')      or define('TBL_CUT', 'tbl_custtel');
defined('SALEORG')      or define('SALEORG', 'master_saleorg');
defined('SENTO_CUS')      or define('SENTO_CUS', 'master_customer_sendto');
defined('SETTING')      or define('SETTING', 'master_setting');
defined('EMAIL')      or define('EMAIL', 'master_customer_email');
defined('TEL')      or define('TEL', 'master_customer_tel');
defined('FAX')      or define('FAX', 'master_customer_fax');
defined('CFCALL')      or define('CFCALL', 'tran_confirm_call');
defined('REPORT_DETAIL')      or define('REPORT_DETAIL', 'tran_report_notification_detail');
defined('PAYMENT')      or define('PAYMENT', 'master_template_report');
defined('BILLPAY')      or define('BILLPAY', 'vw_billpay_txt02');
defined('LOG')      or define('LOG', 'log_notification');
defined('DEPARTMENT')      or define('DEPARTMENT', 'master_department');
defined('LOG_IN')      or define('LOG_IN', 'log_log_in');

defined('CLIENT_ID') or define('CLIENT_ID', '9f716aa3b7ffb2c939483195444512d8d3b6a5ea');
defined('CLIENT_SECRET') or define('CLIENT_SECRET', 'b34eac00d585e57c2bbc6528114908f53ab7f41b');
defined('OPEN_JOB_NOTIFY') OR define('OPEN_JOB_NOTIFY', TRUE);
defined('JOB_NOTIFY_TOKEN') OR define('JOB_NOTIFY_TOKEN', 'g0sJ9baxAKBiajHFzjAp4hjEoIjEv9z7tl0XkyNNyv8'); 

if (ENVIRONMENT == 'production') {
    defined('SERVERNAME') or define('SERVERNAME', '10.51.249.165');
    defined('DATABASE') or define('DATABASE', 'NpiNotification');
    defined('UID') or define('UID', 'Npinoti_usr01');
    defined('PWD') or define('PWD', 'Noti22@PRD');
    defined('HTTP')      or define('HTTP', 'invoicenotification/');
    defined('WWW') or define('WWW', 'https://npismo.scg.com');
    defined('API') or define('API', 'https://npismo.scg.com/api/');
} else {
    if (ENVIRONMENT == 'testing') {
        defined('HTTP')      or define('HTTP', 'invoicenotification/');
        defined('WWW') or define('WWW', 'https://npismodev.scg.com');
    }
    defined('SERVERNAME') or define('SERVERNAME', '10.51.249.87');
    defined('DATABASE') or define('DATABASE', 'NpiNotification_Dev');
    defined('UID') or define('UID', 'NpiNoti_usr01');
    defined('PWD') or define('PWD', 'NpiNoti01@2022');
    defined('HTTP')      or define('HTTP', '');
    defined('WWW') or define('WWW', 'http://notification.com');
    defined('API') or define('API', 'https://npismodev.scg.com/api/');
}
