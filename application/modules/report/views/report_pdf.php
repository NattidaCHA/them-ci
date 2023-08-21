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
        height: 100%;
        margin: 2% 5% 2% 5%;
        border: 2px solid #cdcdcd;
        display: flex;
        flex-direction: column;
    }

    .header {
        display: flex;
        flex-direction: row;
        padding: 5px 15px 0px 15px;
    }

    .half-30 {
        float: left;
        width: 29%;
    }

    .logo {
        margin-right: 3%;
        width: 100px;
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
        padding-left: 23px;
        padding-right: 23px;
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
        font-size: 16px;
    }


    .full-table tr .title {
        font-weight: bold;
        width: 85px;
        /* height: 13px; */
    }

    .full-table tr .des-bold {
        font-weight: bold;
        width: 440px;
        /* height: 13px */
    }

    .full-table tr .address {
        width: 440px;
        /* height: 13px */
    }


    .full-table tr .title-bill-no {
        width: 120px;
        /* height: 13px; */
    }

    .full-table tr .des-bill-no {
        font-weight: bold;
        width: 124px;
        /* height: 13px; */
    }

    .full-table tr .des {
        width: 124px;
        /* height: 13px; */
    }

    .full-table tr .des-no {
        width: 80px;
        /* height: 13px; */
    }

    .full-table tr .date-send {
        width: 120px;
        /* height: 12px; */
    }

    .full-table tr .no {
        width: 440px;
        /* height: 12px; */
    }

    .full-table tr .tax {
        width: 50px;
        height: 12px;
    }

    .table-list {
        width: 100%;
        height: 24%;
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
        color: #000000;
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
        margin-left: 30%;
        width: 80px;
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
    }

    .border-bottom {
        min-width: 100px;
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
    }

    .pt-5 {
        padding-top: 5px;
    }

    .invoice-title {
        font-size: 18px;
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
    }

    .total-amount-in-worde {
        padding-left: 35px;
        font-weight: bold;
    }
</style>

<div class="pdf">
    <div class="header">
        <div class="half-30">
            <img src="/assets/img/logo-300.png" class="logo" />
        </div>
        <div class="box-title">
            <div class="text-header">บริษัท นวพลาสติกอุตสาหกรรม (สระบุรี) จํากัด</div>
            <div class="text-boder">เลขที่ 1 ถนนปูนซีเมนต์ไทย แขวงบางซื่อ เขตบางซื่อ กรุงเทพมหานคร 10800</div>
            <div class="text-boder">โทร. 02-555-0888 โทรสาร 02-586-2929</div>
            <div class="text-boder">Tax-ID: 0-1055-33141-54-4</div>
        </div>
    </div>
    <div class="border-bottom-header"></div>
    <div class="content">
        <div class="box-summary">
            <div class="invoice-title">ใบสรุปรายการแจ้งยอดหนี้ชำระ</div>
            <div class="detail-summary">
                <table class="full-table">
                    <tbody>
                        <tr>
                            <td class="title">รหัสลูกค้า :</td>
                            <td class="des-bold">6500108 หจก.แสงไพบลู ยเ์ชยี งราย</td>
                            <td class="title-bill-no">เลขที่เอกสารอ้างอิง&nbsp;:&nbsp;20190101xxxxxx</td>
                        </tr>
                        <tr>
                            <td class="title">ที่อยู่ลูกค้า :</td>
                            <td class="address">xx ถนน ประชาชนืѷ ซอย นมิ มานต์อําเภอเมอื ง จังหวัด เชยีงราย 10800 ggregr</td>
                            <td class="date-send">วันที่ออกเอกสาร&nbsp;:&nbsp;01.01.2019</td>
                        </tr>
                        <tr>
                            <td class="title">รหัสผู้ทนขาย :</td>
                            <td class="no">287</td>
                            <td class="tax">
                                <div style="display: flex;"><span>สกุลเงิน&nbsp;:&nbsp;THB&nbsp;(บาท)&nbsp;รวมภาษีมูลค่าเพิ่ม</span></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="">
            <div class="sumary-title">สรุปรายการแจ้งยอดหนี้ชำระ</div>
            <div class="table-list">
                <table class="full-table-2">
                    <thead>
                        <tr>
                            <th class="text-center" width="5%">ลําดับ</th>
                            <th class="text-rigth" width="10%">ประเภท*</th>
                            <th class="text-center" width="15%">เลขใบแจ้งหนี้</th>
                            <th class="text-center" width="15%">วันที่ออกเอกสาร</th>
                            <th class="text-left" width="15%">วันครบกําหนดชําระ</th>
                            <th class="text-right" width="15%">เงื่อนไข</th>
                            <th class="text-center" width="25%">จํานวนเงิน</th>
                        </tr>

                        <div style="border-bottom: 1px solid #777;"></div>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">1</td>
                            <td class="text-rigth">RT- ใบกํากับ</td>
                            <td class="text-center">164800901</td>
                            <td class="text-center">25.12.2018</td>
                            <td class="text-left">05.01.2019</td>
                            <td class="text-right">NT15</td>
                            <td class="text-center">275,268.20</td>
                        </tr>
                        <tr>
                            <td class="text-center">1</td>
                            <td class="text-rigth">RT- ใบกํากับ</td>
                            <td class="text-center">164800901</td>
                            <td class="text-center">25.12.2018</td>
                            <td class="text-left">05.01.2019</td>
                            <td class="text-right">NT15</td>
                            <td class="text-center">275,268.20</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div style="border-bottom: 1px solid #cdcdcd;"></div>
            <div class="total-list">ยอดรวมชําระตอ่ หนา้เอกสาร 1,051,045.20 จํานวน 15 รายการ</div>
            <div style="border-bottom: 1px solid #777;"></div>

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
                                <td class="text-center">Cell</td>
                                <td class="text-center">Cell</td>
                                <td class="text-center">Cell</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="text-amount">จํานวนเอกสาร ทั้งหมด 30 รายการ (เอกสารทั้งหมด 02 หน้า)</div>
                <div class="" style="display: flex; flex-direction: column;">
                    <div class="mt-1 text-total">จํานวนเอกสาร ทั้งหมด 30 รายการ (เอกสารทั้งหมด 02 หน้า)</div>
                    <div class="text-total">*ประเภท RT= เอกสารใบกํากับภาษี, RD= เอกสารเพิ่มหนี้ , RC = เอกสารลดหนี้, DC= เอกสาร Rebate, RP/RR = เงินเหลือ ใบเสร็จ, DP = เอกสารเงินเหลือ</div>
                </div>
            </div>

            <div class="border-bottom-dashed"></div>
            <div style="border-bottom: 1px solid #222;"></div>
            <div class="border-bottom-sum"></div>
            <div class="payment">
                <div class="half-50-left">
                    <div class="text-title-bank">ใบแจ้งการชําระหนี้ผ่านธนาคาร (PAY-IN-SLIP)</div>
                    <div class="text-bold">เพื่อเข้าบัญชีบริษัทนวพลาสติกอุตสาหกรรม (สระบุรี) จํากัด</div>
                    <div class="bank">
                        <input class="checkbox" type="checkbox">
                        <img src="../../../../assets/img/kasikorn.png" class="logo-bank pt-5">
                        <span> บมจ. ธนาคารกสกิ รไทย (Kasikorn Bank)</span>
                        <p> สาขาพหลโยธิน เลขที่บัญชี 099-1-22220-0 (Bill Payment)</p>
                    </div>
                    <div class="bank">
                        <input class="checkbox" type="checkbox" value="">
                        <img src="../../../../assets/img/scb.png" class="logo-bank pt-5">
                        <span> บมจ. ธนาคารกสกิ รไทย (Kasikorn Bank)</span>
                        <p> สาขาพหลโยธิน เลขที่บัญชี 099-1-22220-0 (Bill Payment)</p>
                    </div>
                    <div class="bank">
                        <input class="" type="checkbox" value="">
                        <img src="../../../../assets/img/bangkok.png" class="logo-bank pt-5">
                        <span> บมจ. ธนาคารกสกิ รไทย (Kasikorn Bank)</span>
                        <p> สาขาพหลโยธิน เลขที่บัญชี 099-1-22220-0 (Bill Payment)</p>
                    </div>
                    <div class="bank">
                        <input class="" type="checkbox" value="">
                        <img src="../../../../assets/img/krungthail.jpg" class="logo-bank pt-5">
                        <span> บมจ. ธนาคารกสกิ รไทย (Kasikorn Bank)</span>
                        <p> สาขาพหลโยธิน เลขที่บัญชี 099-1-22220-0 (Bill Payment)</p>
                    </div>
                    <div class="bank">
                        <input class="" type="checkbox" value="">
                        <img src="../../../../assets/img/krungsri.png" class="logo-bank pt-5">
                        <span> บมจ. ธนาคารกสกิ รไทย (Kasikorn Bank)</span>
                        <p> สาขาพหลโยธิน เลขที่บัญชี 099-1-22220-0 (Bill Payment)</p>
                    </div>
                </div>
                <div class="half-50-right">
                    <div>
                        <img src="/assets/img/logo-300.png" class="logo-pay">
                        <div class="text-center">สาขา/Branch ………………………………วันที่/Date………………………………</div>
                    </div>
                    <div class="boder-bank">
                        <div class="box-service">
                            <div class="service">
                                SERIVE CODE:
                            </div>
                            <div class="bbnpisb">
                                BBNPISB
                            </div>
                        </div>
                        <div class="box-border"></div>
                        <div class="pl-5"><span>Customer Name : ชื่อลูกค้า</span><span class="border-bottom">………………………………………………………………</span></div>
                        <div class="pl-5"><span>Customer No./Ref. 1: รหัสลูกค้า</span><span class=""> ………………………………………………………</span></div>
                        <div class="pl-5"><span>Reference 2 : หมายเหตุ(ถ้ามี)</span><span class=""> ……………………………………………………………</span></div>
                        <div style="border-bottom: 1px solid #777;"></div>
                        <div class="pl-5"><span>ยอดเงินสดชําระ/Amount in Cash ……………………………………… บาท/Baht</span></div>
                    </div>
                </div>
            </div>
            <div class="">
                <table class="payment-table">
                    <tbody>
                        <tr>
                            <td class="text-center" style="width: 108px;">
                                <p>หมายเลขเช็ค</p>
                                <p>(Cheque No.)</p>
                            </td>
                            <td class="text-center" style="width: 88px;">
                                <p>เช็คลงวันที่</p>
                                <p>(Cheque Date)</p>
                            </td>
                            <td class="text-center" style="width: 133px;">
                                <p>ชื่อธนาคาร</p>
                                <p>(Drawee Bank)</p>
                            </td>
                            <td class="text-center" style="width: 101px;">
                                <p>สาขา</p>
                                <p>(Branch)</p>
                            </td>
                            <td class="text-center" style="width: 124.625px;">
                                <p>จํานวนเงิน</p>
                                <p>(Amount)</p>
                            </td>
                            <td class="text-center" style="width: 90.375px;">สําหรับเจ้าหน้าที่ธนาคาร</td>
                        </tr>
                        <tr>
                            <td style="width: 108px;">&nbsp;</td>
                            <td style="width: 88px;">&nbsp;</td>
                            <td style="width: 133px;">&nbsp;</td>
                            <td style="width: 101px;">&nbsp;</td>
                            <td style="width: 124.625px;">&nbsp;</td>
                            <td style="width: 90.375px;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="width: 645px;" colspan="6">
                                <div class="total-amount-in-worde">โปรดเขียนจํานวนเงินเป็นตัวอักษร (Amount in Words)</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="box-scan">
                <div class="text-scan">
                    <p>ผู้นำฝาก………………………………………………</p>
                    <p>โทร……………………………………………………</p>
                </div>
                <div class="qr-scan">
                    <img src="../../../../assets/img/QRcode.png" class="logo-qr-scan">
                </div>
                <div class="bacode-scan">
                    <img src="../../../../assets/img/barcode.gif" class="logo-bacode-scan">
                </div>
            </div>
        </div>
    </div>
</div>