<style>
    body {
        margin: 0;
        padding: 0;
        line-height: 1.3;
        font-size: 14px;
        font-weight: 400;
        color: #222222;
    }

    .pdf {
        height: 90%;
        margin: 2% 5% 2% 5%;
        /* border: 2px solid #cdcdcd; */
        display: flex;
        flex-direction: column;
    }

    .header {
        display: flex;
        flex-direction: row;
        padding: 5px 15px 0px 15px;
    }

    .half-10 {
        float: left;
        width: 12%;
    }

    .half-30 {
        float: left;
        width: 25%;
        /* margin-top: 2px; */
    }


    .logo {
        margin-right: 1%;
        width: 70px;
        /* margin-top: 5px; */
    }

    .logo-nawa {
        margin-right: 5%;
        width: 265px;
        /* height: 150px; */
    }

    .box-title {
        display: flex;
        flex-direction: column;
    }

    h2 {
        margin: 0;
        padding: 0;
    }


    .text-header {
        font-size: 18px;
        font-weight: bold !important;

    }

    .text-boder {
        font-size: 12px;
        font-weight: bold !important;
    }

    .border-bottom-header {
        border: 1px solid #cdcdcd;
        background-color: #cdcdcd;
    }

    .box-summary {
        padding: 5px 10px 0px 10px;
    }

    .detail-summary {
        padding-left: 30px;
        padding-right: 30px;
    }

    .row-col-subject {
        margin: 0;
        padding: 0;
    }

    .row-col-subject td {
        padding-right: 10px;
        padding-left: 0;
        vertical-align: top;
    }

    .row-col-subject td.subject {
        color: #777;
    }

    .text-right {
        text-align: right !important;
    }

    .text-center {
        text-align: center !important;
    }

    .text-left {
        text-align: left !important;
    }

    .text-gray {
        color: #999999;
    }

    .text-primary {
        color: #087df5;
    }

    .text-danger {
        color: #E11D48;
    }

    .text-success {
        color: #08b962;
    }

    .mt-1 {
        margin-top: 10px;
    }

    .mt-05 {
        margin-top: 5px;
    }

    .mt-2 {
        margin-top: 20px;
    }

    .mt-3 {
        margin-top: 30px;
    }

    h1,
    h2,
    h3,
    h4,
    h5 {
        margin: 0;
        padding: 0
    }

    .row-col-subject {
        margin: 0;
        padding: 0;
    }

    .row-col-subject td {
        padding-right: 10px;
        padding-left: 0;
        vertical-align: top;
    }

    .row-col-subject td.subject {
        color: #777;
    }

    .full-table {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
    }

    .full-table td,
    .full-table th {
        font-size: 14px;
    }


    .full-table tr .title {
        font-weight: bold;
        width: 85px;
        /* height: 13px; */
    }

    .full-table tr .des-bold {
        font-weight: bold;
        width: 320px;
        /* height: 13px */
    }

    .full-table tr .title-bill-no {
        width: 10px;
        font-weight: bold;
        /* height: 13px; */
    }


    .full-table tr .bill-no {
        font-weight: bold;
    }

    .full-table tr .des-bill-no {
        font-weight: bold;
        width: 124px;
        /* height: 13px; */
    }

    .full-table tr .des {
        width: 124px;
        font-weight: bold;
        /* height: 13px; */
    }

    .full-table tr .des-no {
        width: 80px;
        font-weight: bold;
        /* height: 13px; */
    }

    .full-table tr .date-send {
        width: 120px;
        font-weight: bold;
        /* height: 12px; */
    }

    .full-table tr .no {
        width: 320px;
        font-weight: bold;
        /* height: 12px; */
    }

    .full-table tr .tax {
        width: 50px;
    }

    .table-list {
        width: 100%;
        height: 70%;
    }

    .full-table-2 {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        border-color: #dee2e6;
        font-size: 12px;
    }

    table.full-table-2 td,
    table.full-table-2 th {
        padding: 0;
        margin: 0;
    }

    table.full-table-2 th {
        border-top-width: 1px;
        border-top-color: #222;
        border-top-style: solid;
        border-bottom-width: 1px;
        border-bottom-color: #222;
        border-bottom-style: solid;
    }

    table.GeneratedTable {
        width: 90%;
        background-color: #ffffff;
        border-collapse: collapse;
        border-width: 1px;
        border-color: #cdcdcd;
        border-style: solid;
        color: gray;
        font-weight: bold;
        border-spacing: 0;
    }

    table.GeneratedTable td,
    table.GeneratedTable th {
        border-width: 2px;
        border-color: #cdcdcd;
        border-style: solid;
        padding: 1px;
    }

    table.GeneratedTable thead {
        background-color: #f3f3f2;
    }

    .text-total {
        margin: 0;
        padding: 0;
        font-weight: bold;
        text-align: center;
        font-size: 12px;
    }

    .border-bottom-dashed {
        border-bottom: 1px dashed #777;
        margin-bottom: 10px;
        margin-top: 10px;
    }


    .border-bottom-solid {
        border-bottom: 1px solid #777;
        margin-bottom: 10px;
        margin-top: 80px;
    }


    hr {
        text-align: right;
        width: 80%;
        height: 1px;
        color: #aaa;
        margin-top: 1px;
        margin-bottom: 0;
    }

    .border-bottom-sum {
        border-bottom: 2px solid #cdcdcd;
        margin-top: 45px;
        margin-bottom: 10px;
    }

    .payment {
        display: flex;
        flex-direction: row;
    }


    .half-50-left {
        float: left;
        width: 47%;
        margin-left: 15px;
    }

    .half-50-right {
        float: right;
        width: 48%;
        margin-top: 0;
        padding-top: 0;
    }

    .bank {
        margin: 0;
        padding: 0;
        font-size: 12px;
        font-weight: bold;
    }

    .boder-bank {
        border: 1px solid #222;
        height: 11%;
    }

    .logo-pay {
        width: 90px;
    }

    .logo-pay-nawa {
        width: 130px;
        /* margin-top: 5px; */
    }

    .half2-10 {
        float: left;
        width: 20%;
        margin-left: 25%;
    }

    .half2-30 {
        float: left;
        width: 40%;
        /* margin-top: 2px; */
    }

    .box-service {
        padding-top: 5px;
        padding-left: 5px;
    }

    .service {
        float: left;
        width: 48%;
        font-weight: bold;
    }

    .bbnpisb {
        float: right;
        width: 48%;
        font-weight: bold;
    }

    .box-border {
        margin-top: 3px;
        margin-bottom: 5px;
        border-bottom: 1px solid #222;
    }

    .pl-5 {
        padding-left: 5px;
        font-weight: bold;
        font-size: 13px;
    }

    .border-bottom {
        border-bottom: 1px dotted #cdcdcd;
    }

    .text-title-bank {
        margin: 0;
        padding: 0;
        font-weight: bold;
        font-size: 14px;
    }

    .logo-bank {
        width: 12px;
        /* margin-top: 10px; */
    }

    .pt-5 {
        padding-top: 5px;
    }

    .invoice-title {
        font-size: 16px;
        font-weight: bold;
        text-align: center;
    }

    .sumary-title {
        font-size: 14px;
        font-weight: bold;
        text-align: center;
    }

    p {
        margin: 0;
        padding: 0;
    }

    .payment-table {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        border-color: #222;
        font-size: 12px;
        border-width: 1px;
        border-color: #222;
        border-style: solid;
        /* font-weight: bold; */
    }

    table.payment-table td,
    table.payment-table th {
        padding: 0;
        margin: 0;
        border-width: 1px;
        border-color: #222;
        border-style: solid;
        padding: 1px;
    }

    .box-scan {
        padding: 5px 10px 0px 10px;
    }

    .text-scan {
        float: left;
        width: 28%;
        text-align: center;
        padding-top: 15px;
        font-weight: bold;
    }

    .qr-scan {
        float: left;
        width: 25%;
    }

    .bacode-scan {
        float: right;
        width: 34%;
    }

    .logo-qr-scan {
        width: 65%;
        height: 50%;
        padding-left: 15px;
        padding-right: 10px;
    }


    .logo-bacode-scan {
        padding-top: 15px;
        width: 100%;
        height: 20%;
    }

    .total-list {
        margin-left: 30px;
        margin-top: 2px;
    }

    .table-summary {
        margin-left: 60px;
        margin-top: 10px;
    }

    .text-amount {
        margin-top: 10px;
        font-weight: bold;
        margin-left: 29px;
        text-align: center;
    }

    .total-amount-in-worde {
        padding-left: 35px;
        font-weight: bold;
    }

    .text-detail-total {
        margin: 0;
        padding: 0;
        font-size: 12px;
        font-weight: bold;
        text-align: center;
    }

    .text-branch {
        font-weight: bold;
        text-align: center;
    }

    .text-bank-left {
        margin-left: 35px;
        /* margin-top: -15px; */
    }

    .text-bank-top {
        margin-top: -25px;
        margin-left: 35px;
    }

    .input-checkbox {
        /* margin-top: -25px; */
        width: 45px;
    }

    .footer {
        margin-top: 5px;
    }

    .boder-noti {
        padding-top: 5px;
        margin-top: 5px;
        border: 2px solid #66FFFF;
        width: 100%;
        height: 10px;
        text-align: center;
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .boder-bottom-red {
        border-bottom: 2px solid #66FFFF;
        margin-bottom: 5px;
    }

    .level1 {
        box-decoration-break: slice;
    }

    .page-brack {
        height: 90%;
    }
</style>

<div class="pdf">
    <div class="content">
        <div class="sumary-title">สรุปรายการแจ้งยอดหนี้ชำระ</div>
        <div class="page-brack">
            <div class="table-list">
                <table class="full-table-2">
                    <thead>
                        <tr>
                            <th class="text-center" width="5%">ลําดับ</th>
                            <th class="text-right" width="18%">ประเภท*</th>
                            <th class="text-center" width="15%">เลขใบแจ้งหนี้</th>
                            <th class="text-center" width="15%">วันที่ออกเอกสาร</th>
                            <th class="text-left" width="15%">วันครบกําหนดชําระ</th>
                            <th class="text-right" width="15%">เงื่อนไข</th>
                            <th class="text-center" width="20%">จํานวนเงิน</th>
                        </tr>

                        <div style="border-bottom: 1px solid #777;"></div>
                    </thead>
                    <tbody>
                        <?php if (!empty($data->report->lists)) {
                            foreach ($data->report->lists as $key => $item) {
                                if ($key !== 'total') {
                        ?>
                                    <tr>
                                        <td class="text-center"><?php if ($data->index == 1) {
                                                                    echo $key + 1;
                                                                } else {
                                                                    echo (($data->index - 1) *  $data->size) + $key + 1;
                                                                }; ?></td>
                                        <td class="text-right"><?php echo  $item->type ?></td>
                                        <td class="text-center"><?php echo !empty($item->mbillno) ? $item->mbillno : '-'; ?></td>
                                        <td class="text-center"><?php echo date('d.m.Y', strtotime($item->mpostdate)); ?></td>
                                        <td class="text-left"><?php echo date('d.m.Y', strtotime($item->mduedate)); ?></td>
                                        <td class="text-right"><?php echo $item->mpayterm; ?></td>
                                        <td class="text-center"><?php echo !empty($item->mnetamt) ? number_format($item->mnetamt, 2) : 0; ?></td>
                                    </tr>
                        <?php
                                }
                            }
                        } ?>
                    </tbody>
                </table>
            </div>
            <div style="border-bottom: 1px solid #cdcdcd;"></div>
            <div class="total-list">ยอดรวมชําระต่อหน้าเอกสาร <?php echo !empty($data->report->lists['total']) ? number_format($data->report->lists['total'], 2) : 0
                                                                ?> จํานวน <?php echo count($data->report->lists) - 1
                                                                            ?> รายการ</div>
            <div style="border-bottom: 1px solid #777;"></div>
            <div>
                <?php if ($data->report->total_page == $data->index) { ?>
                    <div class="boder-noti">
                        <p><?php echo  $data->tem['page_footer'][0]->due_detail; ?></p>
                        <div class="boder-bottom-red"></div>
                        <p><?php echo  $data->tem['page_footer'][0]->cal; ?></p>
                    </div>
                    <div class="detail-summary">
                        <div class="table-summary">
                            <table class="GeneratedTable">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="20%">ยอดรวมรายการแจ้งหนี้</th>
                                        <th class="text-center" width="20%">ยอดรวมรายการหักลบ</th>
                                        <th class="text-center" width="20%">ยอดรวมชําระทั้งสิ้น</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center"><?php echo !empty($data->report->total->total_debit) ? number_format($data->report->total->total_debit, 2) : 0 ?></td>
                                        <td class="text-center">&nbsp;<?php echo !empty($data->report->total->total_credit) ? '- ' . number_format($data->report->total->total_credit, 2) : 0 ?></td>
                                        <td class="text-center text-danger"><?php echo !empty($data->report->total->total_summary) ? number_format($data->report->total->total_summary, 2) : 0 ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-amount">จํานวนเอกสารทั้งหมด&nbsp;<?php echo $data->report->total_items ?>&nbsp;รายการ</div>
                        <div>
                            <div class="mt-05 text-total"><?php echo  $data->tem['page_footer'][0]->contact; ?></div>
                            <div class="text-total"><?php echo  $data->tem['page_footer'][0]->type; ?></div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>