<?php

if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1') {
    // localhost
    $pdo =  (object)[
        'db' => (object)['SERVERNAME' => '10.51.249.87', 'DATABASE' => 'NpiNotification_Dev', 'UID' => 'NpiNoti_usr01', 'PWD' => 'NpiNoti01@2022'],
    ];
} else {
    // dev
    if (strpos($_SERVER['HTTP_HOST'], 'dev') !== FALSE) {
        $pdo =  (object)[
            'db' => (object)['SERVERNAME' => '10.51.249.87', 'DATABASE' => 'NpiNotification_Dev', 'UID' => 'NpiNoti_usr01', 'PWD' => 'NpiNoti01@2022'],
        ];
    } else {
        // prod
        $pdo =  (object)[
            'db' => (object)['SERVERNAME' => '10.51.249.165', 'DATABASE' => 'NpiNotification', 'UID' => 'Npinoti_usr01', 'PWD' => 'Noti22@PRD'],
        ];
    }
}
