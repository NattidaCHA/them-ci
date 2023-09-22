<?php
$config['site_name'] = 'Notification system';
$config['site_thumb'] = '';
$config['site_author'] = '';


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

$config['fullSearch'] = ['0000000281', '0000000282'];

$config['page'] = [
    (object)[
        'id' => 1,
        'page' => 'invoice',
        'colunm' => [
            (object)['id' => 1, 'name' => 'ประเภทธุรกิจ'],
            (object)['id' => 2, 'name' => 'ลูกค้า'],
            (object)['id' => 3, 'name' => 'ยอดหนี้'],
            (object)['id' => 4, 'name' => 'รีเบท'],
            (object)['id' => 5, 'name' => 'เงินเหลือ'],
            (object)['id' => 6, 'name' => 'ลดหนี้'],
            (object)['id' => 7, 'name' => 'เพิ่มหนี้'],
            (object)['id' => 8, 'name' => 'Action']
        ],
    ],
    (object)[
        'id' => 2, 'page' => 'report', 'colunm' => [
            (object)['id' => 1, 'name' => 'เลขที่เอกสาร'],
            (object)['id' => 2, 'name' => 'ลูกค้า'],
            (object)['id' => 3, 'name' => 'อีเมล'],
            (object)['id' => 4, 'name' => 'เบอร์โทร'],
            (object)['id' => 5, 'name' => 'โทรแจ้ง'],
            (object)['id' => 6, 'name' => 'ผู้ติดต่อ'],
            (object)['id' => 7, 'name' => 'ผู้รับสาย'],
            (object)['id' => 8, 'name' => 'สถานะ'],
            (object)['id' => 9, 'name' => 'Action']
        ]
    ],
    (object)[
        'id' => 3, 'page' => 'customer', 'colunm' => [
            (object)['id' => 1, 'name' => 'ลูกค้า'],
            (object)['id' => 2, 'name' => 'ผู้ติดต่อ'],
            (object)['id' => 3, 'name' => 'อีเมล'],
            (object)['id' => 4, 'name' => 'เบอร์โทร'],
            (object)['id' => 5, 'name' => 'Action']
        ]
    ],
];
