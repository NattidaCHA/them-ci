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
            (object)['id' => 2, 'name' => 'รหัสลูกค้า'],
            (object)['id' => 3, 'name' => 'ชื่อลูกค้า'],
            (object)['id' => 4, 'name' => 'ยอดหนี้'],
            (object)['id' => 5, 'name' => 'รีเบท'],
            (object)['id' => 6, 'name' => 'เงินเหลือ'],
            (object)['id' => 7, 'name' => 'ลดหนี้'],
            (object)['id' => 8, 'name' => 'เพิ่มหนี้'],
            (object)['id' => 9, 'name' => 'ยอดเงินเหลือในใบเสร็จ'],
            (object)['id' => 10, 'name' => 'Customer Adjustment'],
            (object)['id' => 11, 'name' => 'Customer Manual Payment'],
            // (object)['id' => 12, 'name' => '<div class="d-flex"><i class="bi bi-check-circle text-success me-1"></i><i class="bi bi-x-circle text-danger"></i></div>'],
            (object)['id' => 12, 'name' => 'Action']
        ],
    ],
    (object)[
        'id' => 2, 'page' => 'report', 'colunm' => [
            (object)['id' => 1, 'name' => 'เลขที่เอกสาร'],
            (object)['id' => 2, 'name' => 'รหัสลูกค้า'],
            (object)['id' => 3, 'name' => 'ชื่อลูกค้า'],
            (object)['id' => 4, 'name' => 'อีเมล'],
            (object)['id' => 5, 'name' => 'เบอร์โทร'],
            (object)['id' => 6, 'name' => 'โทรแจ้ง'],
            (object)['id' => 7, 'name' => 'ผู้ติดต่อ'],
            (object)['id' => 8, 'name' => 'ผู้รับสาย'],
            (object)['id' => 9, 'name' => 'สถานะ'],
            (object)['id' => 10, 'name' => 'Action']
        ]
    ],
    (object)[
        'id' => 3, 'page' => 'customer', 'colunm' => [
            (object)['id' => 1, 'name' => 'รหัสลูกค้า'],
            (object)['id' => 2, 'name' => 'ลูกค้า'],
            (object)['id' => 3, 'name' => 'รอบการแจ้ง'],
            (object)['id' => 4, 'name' => 'ผู้ติดต่อ'],
            (object)['id' => 5, 'name' => 'อีเมล'],
            (object)['id' => 6, 'name' => 'เบอร์โทร'],
            (object)['id' => 7, 'name' => 'Fax'],
            (object)['id' => 8, 'name' => 'Action']
        ]
    ],
];

$config['pdf_tem'] = [
    (object)[
        'id' => 1,
        'page' => 'header',
        'company' => 'บริษัท นวพลาสติกอุตสาหกรรม จํากัด',
        'address' => 'เลขที่ 1 ถนนปูนซีเมนต์ไทย แขวงบางซื่อ เขตบางซื่อ กรุงเทพมหานคร 10800',
        'tel' => '02-555-0888',
        'tel2' => '02-586-2929',
        'tax' => '0-1055-33141-54-4',
        'account_no' => NULL,
        'account_name' => NULL,
        'image_name' => NULL,
        'bank_name' => NULL,
        'branch' => NULL,
        'comp_code' => NULL,
        'due_detail' => NULL,
        'cal' => NULL,
        'contact' => NULL,
        'type' => NULL,
        'payment_title' => NULL,
        'detail_1_1' => NULL,
        'detail_1_2' => NULL,
        'detail_2' => NULL,
        'detail_2_1' => NULL,
        'detail_2_2' => NULL,
        'detail_2_3' => NULL,
        'detail_2_4' => NULL,
        'detail_2_5' => NULL,
        'detail_2_6' => NULL,
        'detail_2_7' => NULL,
        'detail_2_8' => NULL,
        'detail_3' => NULL,
        'detail_4' => NULL,
        'detail_5' => NULL,
        'sort' => 1,
        'tran_header' => NULL,
        'tran_detail_1' => NULL,
        'tran_detail_2' => NULL,
        'tran_detail_3' => NULL
    ],
    (object)[
        'id' => 2,
        'page' => 'bank',
        'company' => NULL,
        'address' => NULL,
        'tel' => NULL,
        'tel2' => NULL,
        'tax' => NULL,
        'account_no' => '099-1-22220-0 (Bill Payment)',
        'account_name' => 'บมจ.ธนาคารกสิกรไทย (Kasikorn Bank)',
        'image_name' => 'kasikorn.png',
        'bank_name' => 'บมจ.ธนาคารกสิกรไทย (Kasikorn Bank)',
        'branch' => 'สาขาพหลโยธิน',
        'comp_code' => NULL,
        'due_detail' => NULL,
        'cal' => NULL,
        'contact' => NULL,
        'type' => NULL,
        'payment_title' => NULL,
        'detail_1_1' => NULL,
        'detail_1_2' => NULL,
        'detail_2' => NULL,
        'detail_2_1' => NULL,
        'detail_2_2' => NULL,
        'detail_2_3' => NULL,
        'detail_2_4' => NULL,
        'detail_2_5' => NULL,
        'detail_2_6' => NULL,
        'detail_2_7' => NULL,
        'detail_2_8' => NULL,
        'detail_3' => NULL,
        'detail_4' => NULL,
        'detail_5' => NULL,
        'sort' => 2,
        'tran_header' => NULL,
        'tran_detail_1' => NULL,
        'tran_detail_2' => NULL,
        'tran_detail_3' => NULL
    ],
    (object)[
        'id' => 3,
        'page' => 'bank',
        'company' => NULL,
        'address' => NULL,
        'tel' => NULL,
        'tel2' => NULL,
        'tax' => NULL,
        'account_no' => '027-3-02206-9  (Bill Payment) เพื่อธุรกิจ',
        'account_name' => 'บมจ.ธนาคารไทยพาณิชย์(Siam Commercial Bank)',
        'image_name' => 'scb.png',
        'bank_name' => 'บมจ.ธนาคารไทยพาณิชย์(Siam Commercial Bank)',
        'branch' => 'สาขาบางโพ',
        'comp_code' => NULL,
        'due_detail' => NULL,
        'cal' => NULL,
        'contact' => NULL,
        'type' => NULL,
        'payment_title' => NULL,
        'detail_1_1' => NULL,
        'detail_1_2' => NULL,
        'detail_2' => NULL,
        'detail_2_1' => NULL,
        'detail_2_2' => NULL,
        'detail_2_3' => NULL,
        'detail_2_4' => NULL,
        'detail_2_5' => NULL,
        'detail_2_6' => NULL,
        'detail_2_7' => NULL,
        'detail_2_8' => NULL,
        'detail_3' => NULL,
        'detail_4' => NULL,
        'detail_5' => NULL,
        'sort' => 3,
        'tran_header' => NULL,
        'tran_detail_1' => NULL,
        'tran_detail_2' => NULL,
        'tran_detail_3' => NULL
    ],
    (object)[
        'id' => 4,
        'page' => 'bank',
        'company' => NULL,
        'address' => NULL,
        'tel' => NULL,
        'tel2' => NULL,
        'tax' => NULL,
        'account_no' => NULL,
        'account_name' => 'บมจ.ธนาคารกรุงเทพ (Bangkok Bank)',
        'image_name' => 'bangkok.png',
        'bank_name' => 'บมจ.ธนาคารกรุงเทพ (Bangkok Bank) BR No.127',
        'branch' => NULL,
        'comp_code' => '0280  BR No.127',
        'due_detail' => NULL,
        'cal' => NULL,
        'contact' => NULL,
        'type' => NULL,
        'payment_title' => NULL,
        'detail_1_1' => NULL,
        'detail_1_2' => NULL,
        'detail_2' => NULL,
        'detail_2_1' => NULL,
        'detail_2_2' => NULL,
        'detail_2_3' => NULL,
        'detail_2_4' => NULL,
        'detail_2_5' => NULL,
        'detail_2_6' => NULL,
        'detail_2_7' => NULL,
        'detail_2_8' => NULL,
        'detail_3' => NULL,
        'detail_4' => NULL,
        'detail_5' => NULL,
        'sort' => 4,
        'tran_header' => NULL,
        'tran_detail_1' => NULL,
        'tran_detail_2' => NULL,
        'tran_detail_3' => NULL
    ],
    (object)[
        'id' => 5,
        'page' => 'bank',
        'company' => NULL,
        'address' => NULL,
        'tel' => NULL,
        'tel2' => NULL,
        'tax' => NULL,
        'account_no' => NULL,
        'account_name' => 'บมจ.ธนาคารกรุงไทย (Krungthai Bank)',
        'image_name' => 'krungthail.jpg',
        'bank_name' => 'บมจ.ธนาคารกรุงไทย (Krungthai Bank)',
        'branch' => NULL,
        'comp_code' => '22288',
        'due_detail' => NULL,
        'cal' => NULL,
        'contact' => NULL,
        'type' => NULL,
        'payment_title' => NULL,
        'detail_1_1' => NULL,
        'detail_1_2' => NULL,
        'detail_2' => NULL,
        'detail_2_1' => NULL,
        'detail_2_2' => NULL,
        'detail_2_3' => NULL,
        'detail_2_4' => NULL,
        'detail_2_5' => NULL,
        'detail_2_6' => NULL,
        'detail_2_7' => NULL,
        'detail_2_8' => NULL,
        'detail_3' => NULL,
        'detail_4' => NULL,
        'detail_5' => NULL,
        'sort' => 5,
        'tran_header' => NULL,
        'tran_detail_1' => NULL,
        'tran_detail_2' => NULL,
        'tran_detail_3' => NULL
    ],
    (object)[
        'id' => 6,
        'page' => 'bank',
        'company' => NULL,
        'address' => NULL,
        'tel' => NULL,
        'tel2' => NULL,
        'tax' => NULL,
        'account_no' => '127-0-02145-6 (Bill Payment)',
        'account_name' => 'บมจ.ธนาคารกรุงศรีอยุธยา (Bank of Ayudhaya)',
        'image_name' => 'krungsri.png',
        'bank_name' => 'บมจ.ธนาคารกรุงศรีอยุธยา (Bank of Ayudhaya)',
        'branch' => NULL,
        'comp_code' => NULL,
        'due_detail' => NULL,
        'cal' => NULL,
        'contact' => NULL,
        'type' => NULL,
        'payment_title' => NULL,
        'detail_1_1' => NULL,
        'detail_1_2' => NULL,
        'detail_2' => NULL,
        'detail_2_1' => NULL,
        'detail_2_2' => NULL,
        'detail_2_3' => NULL,
        'detail_2_4' => NULL,
        'detail_2_5' => NULL,
        'detail_2_6' => NULL,
        'detail_2_7' => NULL,
        'detail_2_8' => NULL,
        'detail_3' => NULL,
        'detail_4' => NULL,
        'detail_5' => NULL,
        'sort' => 6,
        'tran_header' => NULL,
        'tran_detail_1' => NULL,
        'tran_detail_2' => NULL,
        'tran_detail_3' => NULL
    ],
    (object)[
        'id' => 7,
        'page' => 'page_footer',
        'company' => NULL,
        'address' => NULL,
        'tel' => NULL,
        'tel2' => NULL,
        'tax' => NULL,
        'account_no' => NULL,
        'account_name' => NULL,
        'image_name' => NULL,
        'bank_name' => NULL,
        'branch' => NULL,
        'comp_code' => NULL,
        'due_detail' => 'กรุณาชำระให้ตรง DUE เพื่อป้องกันการเก็บเงินล่าช้า',
        'cal' => 'ค่าล่าช้า = ยอดค้างชำระ X 18% ต่อปี X จำนวนวันที่ค้างชำระ /365',
        'contact' => 'สอบถามข้อมูลการชําระเงิน ติดต่อ หน่วยงานบริหารสินเชื่อ ติดต่อ 02-5861091, 02-5550829, 02-5861204',
        'type' => '*ประเภท RA = ยอด Invoice, RD = ยอดเพิ่มหนี้, RC = ยอดลดหนี้, RB= ยอดเงินเหลือ, DC= ยอด Rebate, RE = ยอดเงินเหลือในใบเสร็จ',
        'payment_title' => NULL,
        'detail_1_1' => NULL,
        'detail_1_2' => NULL,
        'detail_2' => NULL,
        'detail_2_1' => NULL,
        'detail_2_2' => NULL,
        'detail_2_3' => NULL,
        'detail_2_4' => NULL,
        'detail_2_5' => NULL,
        'detail_2_6' => NULL,
        'detail_2_7' => NULL,
        'detail_2_8' => NULL,
        'detail_3' => NULL,
        'detail_4' => NULL,
        'detail_5' => NULL,
        'sort' => 7,
        'tran_header' => NULL,
        'tran_detail_1' => NULL,
        'tran_detail_2' => NULL,
        'tran_detail_3' => NULL
    ],
    (object)[
        'id' => 8,
        'page' => 'footer',
        'company' => NULL,
        'address' => NULL,
        'tel' => NULL,
        'tel2' => NULL,
        'tax' => NULL,
        'account_no' => NULL,
        'account_name' => NULL,
        'image_name' => NULL,
        'bank_name' => NULL,
        'branch' => NULL,
        'comp_code' => NULL,
        'due_detail' => NULL,
        'cal' => NULL,
        'contact' => NULL,
        'type' => NULL,
        'payment_title' => 'วิธีการชำระเงิน',
        'detail_1_1' => 'ชำระเป็นเงินสด/เช็ค ผ่านธนาคารที่ระบุ ณ สาขาทั่วประเทศ โดยกรอกรายละเอียดการชำระเงินลงใน',
        'detail_1_2' => 'ใบแจ้งการขำระเงินผ่านธนาคาร (Pay - in Slip) ฉบับนี้',
        'detail_2' => 'ธนาคารที่รับชำระมี ดังนี้',
        'detail_2_1' => '2.1 บมจ. ธนาคารกสิกรไทย 2.2 บมจ. ธนาคารไทยพาณิชย์ 2.3 บมจ. ธนาคารกรุงเทพ 2.4 ธนาคารกรุงไทย 2.5 บมจ. ธนาคารกรุงศรีอยุธยา',
        'detail_2_2' => 'กรณีที่ท่านชำระโดยเช็ค',
        'detail_2_3' => '- เช็คที่นำฝากต้องไม่เป็นเช็คลงวันที่ล่วงหน้า และนำฝากให้ทันภายในเวลาเคลียร์ริ่ง ณ วันนั้น',
        'detail_2_4' => '- โปรดขีดคร่อม <strong>A/C Payee Only</strong> สั่งจ่ายในนาม "บริษัท นวพลาสติกอุตสาหกรรม จำกัด"',
        'detail_2_5' => '- จ่ายชำระด้วยเช็ค 1 ใบ ต่อ 1 ใบแจ้งการชำระเงินผ่านธนาคาร (Pay - in Slip)',
        'detail_2_6' => '- บมจ. ธนาคารกรุงเทพ และ บมจ. ธนาคารไทยพาณิชย์ ทุกสาขาทั่วประเทศจะรับชำระเงินได้ทั้งเงินสดและเช็ค โดยเช็คจะต้องเป็นเช็คในเขต',
        'detail_2_7' => 'สำนักหักบัญชีเดียวกันกับสาขาที่รับชำระ สำหรับ บมจ. ธนาคารกสิกรไทย ในเขตกรุงเทพฯ และปริมณฑลจะรับชำระด้ววยเช็ค เฉพาะสาขาในเขต',
        'detail_2_8' => 'สำนักหักบัญชีกรุงเทพมหานครเท่านั้น ส่วนภูมิภาคชำระเฉพาะเช็คของธนาคารของสาขาผู้รับชำระเท่านั้น',
        'detail_3' => 'กรุณาตรวจสอบความถูกต้องและเก็บสำเนาใบแจ้งการชำระเงิน ที่ธนาคารลงนามแล้วเก็บไว้เป็นหลักฐาน',
        'detail_4' => 'ธนาคารผู้รับชำระจะนำเงินสด/เช็คที่ท่านมาชำระเข้าบัญชีของบริษัทฯ',
        'detail_5' => 'หากมีข้อสงสัย โปรติดต่อ แผนกประสานงานขาย โทร. 0-25550888 หรือ แผนกบัญชีสินเชื่อฯ โทร. 02-5861091, 02-5550829, 02-5861204',
        'sort' => 8,
        'tran_header' => NULL,
        'tran_detail_1' => NULL,
        'tran_detail_2' => NULL,
        'tran_detail_3' => NULL
    ],
    (object)[
        'id' => 9,
        'page' => 'bank_tran',
        'company' => NULL,
        'address' => NULL,
        'tel' => NULL,
        'tel2' => NULL,
        'tax' => NULL,
        'account_no' => '020-1-06142-3',
        'account_name' => 'ธ.กสิกรไทย',
        'image_name' => 'kasikorn.png',
        'bank_name' => 'ธ.กสิกรไทย',
        'branch' => 'สาขาบางซื่อ',
        'comp_code' => NULL,
        'due_detail' => NULL,
        'cal' => NULL,
        'contact' => NULL,
        'type' => NULL,
        'payment_title' => NULL,
        'detail_1_1' => NULL,
        'detail_1_2' => NULL,
        'detail_2' => NULL,
        'detail_2_1' => NULL,
        'detail_2_2' => NULL,
        'detail_2_3' => NULL,
        'detail_2_4' => NULL,
        'detail_2_5' => NULL,
        'detail_2_6' => NULL,
        'detail_2_7' => NULL,
        'detail_2_8' => NULL,
        'detail_3' => NULL,
        'detail_4' => NULL,
        'detail_5' => NULL,
        'sort' => 9,
        'tran_header' => NULL,
        'tran_detail_1' => NULL,
        'tran_detail_2' => NULL,
        'tran_detail_3' => NULL
    ],
    (object)[
        'id' => 10,
        'page' => 'bank_tran',
        'company' => NULL,
        'address' => NULL,
        'tel' => NULL,
        'tel2' => NULL,
        'tax' => NULL,
        'account_no' => '027-3-02206-9',
        'account_name' => 'ธ.ไทยพาณิชย์',
        'image_name' => 'scb.png',
        'bank_name' => 'ธ.ไทยพาณิชย์',
        'branch' => 'สาขาบางโพ',
        'comp_code' => NULL,
        'due_detail' => NULL,
        'cal' => NULL,
        'contact' => NULL,
        'type' => NULL,
        'payment_title' => NULL,
        'detail_1_1' => NULL,
        'detail_1_2' => NULL,
        'detail_2' => NULL,
        'detail_2_1' => NULL,
        'detail_2_2' => NULL,
        'detail_2_3' => NULL,
        'detail_2_4' => NULL,
        'detail_2_5' => NULL,
        'detail_2_6' => NULL,
        'detail_2_7' => NULL,
        'detail_2_8' => NULL,
        'detail_3' => NULL,
        'detail_4' => NULL,
        'detail_5' => NULL,
        'sort' => 10,
        'tran_header' => NULL,
        'tran_detail_1' => NULL,
        'tran_detail_2' => NULL,
        'tran_detail_3' => NULL
    ],
    (object)[
        'id' => 11,
        'page' => 'bank_tran',
        'company' => NULL,
        'address' => NULL,
        'tel' => NULL,
        'tel2' => NULL,
        'tax' => NULL,
        'account_no' => '127-3-10643-3',
        'account_name' => 'ธ.กรุงเทพ',
        'image_name' => 'bangkok.png',
        'bank_name' => 'ธ.กรุงเทพ',
        'branch' => 'สาขาซอยอารี',
        'comp_code' => NULL,
        'due_detail' => NULL,
        'cal' => NULL,
        'contact' => NULL,
        'type' => NULL,
        'payment_title' => NULL,
        'detail_1_1' => NULL,
        'detail_1_2' => NULL,
        'detail_2' => NULL,
        'detail_2_1' => NULL,
        'detail_2_2' => NULL,
        'detail_2_3' => NULL,
        'detail_2_4' => NULL,
        'detail_2_5' => NULL,
        'detail_2_6' => NULL,
        'detail_2_7' => NULL,
        'detail_2_8' => NULL,
        'detail_3' => NULL,
        'detail_4' => NULL,
        'detail_5' => NULL,
        'sort' => 11,
        'tran_header' => NULL,
        'tran_detail_1' => NULL,
        'tran_detail_2' => NULL,
        'tran_detail_3' => NULL
    ],
    (object)[
        'id' => 12,
        'page' => 'bank_tran',
        'company' => NULL,
        'address' => NULL,
        'tel' => NULL,
        'tel2' => NULL,
        'tax' => NULL,
        'account_no' => '034-6-10179-4',
        'account_name' => 'ธ.กรุงไทย',
        'image_name' => 'bangkok.png',
        'bank_name' => 'krungthail.jpg',
        'branch' => 'สาขาประดิพัทธ์',
        'comp_code' => NULL,
        'due_detail' => NULL,
        'cal' => NULL,
        'contact' => NULL,
        'type' => NULL,
        'payment_title' => NULL,
        'detail_1_1' => NULL,
        'detail_1_2' => NULL,
        'detail_2' => NULL,
        'detail_2_1' => NULL,
        'detail_2_2' => NULL,
        'detail_2_3' => NULL,
        'detail_2_4' => NULL,
        'detail_2_5' => NULL,
        'detail_2_6' => NULL,
        'detail_2_7' => NULL,
        'detail_2_8' => NULL,
        'detail_3' => NULL,
        'detail_4' => NULL,
        'detail_5' => NULL,
        'sort' => 12,
        'tran_header' => NULL,
        'tran_detail_1' => NULL,
        'tran_detail_2' => NULL,
        'tran_detail_3' => NULL
    ],
    (object)[
        'id' => 13,
        'page' => 'bank_tran_detail',
        'company' => NULL,
        'address' => NULL,
        'tel' => NULL,
        'tel2' => NULL,
        'tax' => NULL,
        'account_no' => NULL,
        'account_name' => NULL,
        'image_name' => NULL,
        'bank_name' => NULL,
        'branch' => NULL,
        'comp_code' => NULL,
        'due_detail' => NULL,
        'cal' => NULL,
        'contact' => NULL,
        'type' => NULL,
        'payment_title' => NULL,
        'detail_1_1' => NULL,
        'detail_1_2' => NULL,
        'detail_2' => NULL,
        'detail_2_1' => NULL,
        'detail_2_2' => NULL,
        'detail_2_3' => NULL,
        'detail_2_4' => NULL,
        'detail_2_5' => NULL,
        'detail_2_6' => NULL,
        'detail_2_7' => NULL,
        'detail_2_8' => NULL,
        'detail_3' => NULL,
        'detail_4' => NULL,
        'detail_5' => NULL,
        'sort' => 13,
        'tran_header' => 'ท่านสามารถโอนเงินชำระค่าสินค้า ได้ดังนี',
        'tran_detail_1' => 'ใช้ใบแจ้งการชำระเงินผ่านธนาคาร (PAY-IN SLIP) ตามแบบฟอร์มของบริษัทฯ',
        'tran_detail_2' => 'ชำระโดยโอนผ่านบัญชีกระแสรายวัน (ใช้ Payin ธนาคาร) หรือ โอนผ่าน Internet',
        'tran_detail_3' => 'หากโอนชำระแล้วรบกวน Fax ใบทำารายการมาที่ เบอร์ Fax.02-5861132 หรือ 02-5861198'
    ],
];


$config['department'] = [
    (object) [
        'department_id' => 1,
        'department_code' => 'CS',
        'department_nameLC' => 'CS',
        'department_nameEN' => 'CS',
        'department_status' => 'A',
        'menu' => '2'
    ],
    (object) [
        'department_id' => 2,
        'department_code' => 'SR',
        'department_nameLC' => 'SR',
        'department_nameEN' => 'SR',
        'department_status' => 'A',
        'menu' => '2'
    ],
    (object) [
        'department_id' => 3,
        'department_code' => 'SYSTEM',
        'department_nameLC' => 'System Admin',
        'department_nameEN' => 'System Admin',
        'department_status' => 'A',
        'menu' => 'all'
    ],
    (object) [
        'department_id' => 4,
        'department_code' => 'MKT',
        'department_nameLC' => 'MKT',
        'department_nameEN' => 'MKT',
        'department_status' => 'A',
        'menu' => '1,2,3,4,10'
    ],
    (object) [
        'department_id' => 5,
        'department_code' => 'CRT',
        'department_nameLC' => 'Credit Mgt.',
        'department_nameEN' => 'Credit Mgt.',
        'department_status' => 'A',
        'menu' => '1,2,3,4,10'
    ],
];

$config['docType'] = [
    (object) [
        'type' => "RA",
        'type_display_th' => 'RA: ยอด Invoice',
        'type_display_en' => 'SD Invoice',
        'calculateSign' => 'บวก(หนี้)',
        'msort' => 1,
        'mstatus' => 'A',
        'is_show' => 1,
        'start_date' => null,
        'end_date' => null,
    ],
    (object) [
        'type' => "RD",
        'type_display_th' => 'RD: ยอดเพิ่มหนี้',
        'type_display_en' => 'SD Debit Memo',
        'calculateSign' => 'บวก(หนี้)',
        'msort' => 2,
        'mstatus' => 'A',
        'is_show' => 1,
        'start_date' => null,
        'end_date' => null,
    ],
    (object) [
        'type' => "RC",
        'type_display_th' => 'RC: ยอดลดหนี้',
        'type_display_en' => 'SD Credit Memo',
        'calculateSign' => 'ลบ(ลดหนี้)',
        'msort' => 3,
        'mstatus' => 'A',
        'is_show' => 1,
        'start_date' => null,
        'end_date' => null,
    ],
    (object) [
        'type' => "RB",
        'type_display_th' => 'RB: ยอดเงินเหลือ',
        'type_display_en' => 'SD Receipt - Credit',
        'calculateSign' => 'ลบ(ลดหนี้)',
        'msort' => 4,
        'mstatus' => 'A',
        'is_show' => 1,
        'start_date' => null,
        'end_date' => null,
    ],
    (object) [
        'type' => "DC",
        'type_display_th' => 'DC: ยอด Rebate',
        'type_display_en' => 'Customer Credit Memo',
        'calculateSign' => 'ลบ(ลดหนี้)',
        'msort' => 5,
        'mstatus' => 'A',
        'is_show' => 1,
        'start_date' => null,
        'end_date' => null,
    ],
    (object) [
        'type' => "RE",
        'type_display_th' => 'RE: ยอดเงินเหลือในใบเสร็จ',
        'type_display_en' => 'SD Receipt-CashSales',
        'calculateSign' => 'ลบ(ลดหนี้)',
        'msort' => 6,
        'mstatus' => 'A',
        'is_show' => 1,
        'start_date' => null,
        'end_date' => null,
    ],
    (object) [
        'type' => "DA",
        'type_display_th' => 'DA: Customer Manual Inv.',
        'type_display_en' => 'Customer Manual Inv.',
        'calculateSign' => 'บวก(หนี้)',
        'msort' => 7,
        'mstatus' => 'A',
        'is_show' => 1,
        'start_date' => null,
        'end_date' => null,
    ],
    (object) [
        'type' => "DB",
        'type_display_th' => 'DB: Customer Adjustment',
        'type_display_en' => 'Customer Adjustment',
        'calculateSign' => 'ลบ(ลดหนี้)',
        'msort' => 8,
        'mstatus' => 'A',
        'is_show' => 1,
        'start_date' => null,
        'end_date' => null,
    ],
    (object) [
        'type' => "DE",
        'type_display_th' => 'DE: Customer Manual Payment',
        'type_display_en' => 'Customer Manual Payment',
        'calculateSign' => 'ลบ(ลดหนี้)',
        'msort' => 9,
        'mstatus' => 'A',
        'is_show' => 1,
        'start_date' => null,
        'end_date' => null,
    ],
];
