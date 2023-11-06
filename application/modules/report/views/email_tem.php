<style>
    body {
        margin: 0;
        padding: 0;
        line-height: 1.3;
        font-size: 16px;
        font-weight: 400;
        color: #222222;
    }

    .text-center {
        text-align: center;
    }

    .detail {
        margin: 25px !important;
    }

    .ml {
        text-align: center;
    }

    p {
        padding: 0 !important;
        margin: 0 !important;
    }

    .text-header {
        font-size: 20px;
        font-weight: bold;
    }
</style>

<div class="content">
    <p class="text-header">เรียน ผู้แทนจำหน่าย</p>

    <div class="detail">
        <p>เอกสารใบแจ้งเตือนครบกำหนดชำระค่าสินค้า Due วันที่ <?php echo $data->end_date; ?> ตามไฟล์แนบ</p>
        <p>หรือ <strong>Click</strong> เพื่อดูรายละเอียดเพิ่มเติมผ่านระบบ Smart Ordering (SMO)</p>
        <p>กรุณา <strong>Click URL:</strong> <a href="<?php echo WWW . $data->http . '/report/pdf/' . $data->uuid; ?>"><strong><?php echo WWW . $data->http . '/report/pdf/' . $data->uuid; ?></strong></a>
        </p>
    </div>
    <p>ขอแสดงความนับถือ,</p>
    <p>Smart Ordering</p>
</div>