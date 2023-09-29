
<?php

if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1') {
    // localhost
    $pdo =  (object)[
        'email' => (object)['host' => 'smtp.office365.com', 'port' => 587, 'username' => 'nan_zen0003@hotmail.com', 'password' => '!Ohsehun1228', 'SMTPSecure' => 'tls']
    ];
} else {
    // dev
    if (strpos($_SERVER['HTTP_HOST'], 'dev') !== FALSE) {
        $pdo =  (object)[

            'email' => (object)['host' => 'smtp.office365.com', 'port' => 587, 'username' => 'nan_zen0003@hotmail.com', 'password' => '!Ohsehun1228', 'SMTPSecure' => 'tls']
        ];
    } else {
        // prod
        $pdo =  (object)[
            'email' => (object)['host' => 'smtp.office365.com', 'port' => 587, 'username' => 'nan_zen0003@hotmail.com', 'password' => '!Ohsehun1228', 'SMTPSecure' => 'tls']
        ];
    }
}

?>