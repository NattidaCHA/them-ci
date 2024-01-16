<html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">

<head>
    <meta http-equiv="Content-type" content="text/html;charset=utf-8" />
</head>

<div style="border-collapse:collapse; border-left: 2px solid #cdcdcd; border-right: 2px solid #cdcdcd;">
    <div style="height: 100%;
        margin: 2% 5% 2% 5%;
        display: flex;
        flex-direction: column;">
        <div style="display: flex;
        flex-direction: row;
        padding: 5px 15px 0px 15px;">
            <table>
                <thead>
                    <tr>
                        <td colspan="3">
                            <img src="<?php echo site_url(); ?>assets/img/logo-300.png" width="70" />
                            <img src="<?php echo site_url(); ?>assets/img/nawaplastic_logo.gif" width="135" />
                        </td>
                        <td colspan="4" style="">
                            <div style="display: flex;flex-direction: column;">
                                <div style="font-weight: bold !important;"><?php echo  $data->tem['header'][0]->company; ?></div>
                                <div style="font-size: 16px;font-weight: bold !important;"><?php echo  $data->tem['header'][0]->address; ?></div>
                                <div style="font-size: 16px;font-weight: bold !important;">โทร. <?php echo  $data->tem['header'][0]->tel; ?> โทรสาร <?php echo  $data->tem['header'][0]->tel2; ?></div>
                                <div style="font-size: 16px;font-weight: bold !important;">Tax-ID: <?php echo  $data->tem['header'][0]->tax; ?></div>
                            </div>
                        </td>
                    </tr>
                </thead>
            </table>
        </div>

        <table style="padding:0;margin:0">
            <tr>
                <td style="border-bottom: 2px solid #cdcdcd;" colspan="7">
                    &nbsp;
                </td>
            </tr>
        </table>

        <div class="content">
            <div>
                &nbsp;
            </div>

            <div style="padding: 15px 10px 0px 10px;">
                <div style=" font-size: 16px;
        font-weight: bold;
        text-align: center;">ใบสรุปรายการแจ้งยอดหนี้ชำระ</div>
                <div>&nbsp;</div>
                <div style="padding-left: 30px;
                margin-left:80px;
        padding-right: 30px;">
                    <table style=" width: 100%;
        border-collapse: collapse;
        border-spacing: 0;">
                        <tbody>
                            <tr>
                                <td colspan="2"></td>
                                <td style="font-weight: bold;
        width: 85px;font-size: 16px;">รหัสลูกค้า :</td>
                                <td style="font-weight: bold;
        width: 200px;font-size: 16px;"><?php echo $data->report->info->mcustno; ?> <?php echo $data->report->info->mcustname; ?></td>
                                <td style="width: 10px;
        font-weight: bold;font-size: 16px;"><span>เลขที่เอกสารอ้างอิง</span>&nbsp;:&nbsp;<span style="font-weight: bold;"><?php echo $data->report->bill_info->bill_no; ?></span></td>
                            </tr>
                            <tr>
                                <td colspan="2"></td>
                                <td style="font-weight: bold;
        width: 85px;font-size: 16px;">รหัสผู้แทนขาย :</td>
                                <td style="width: 200px;
        font-weight: bold;font-size: 16px;"> <?php echo !empty($data->report->info->msale_phone) ? $data->report->info->msalegrp . ' (' . $data->report->info->msale_phone . ')' : $data->report->info->msalegrp;
                                                ?></td>
                                <td style=" width: 120px;
        font-weight: bold;font-size: 16px;">วันที่ออกเอกสาร&nbsp;:&nbsp;<?php echo date('d.m.Y', strtotime($data->report->bill_info->created_date)); ?></td>
                            </tr>
                            <tr>
                                <td colspan="2"></td>
                                <td style="font-weight: bold;
        width: 85px;font-size: 16px;"><span>สกุลเงิน&nbsp;:&nbsp;</td>
                                <td style="width: 200px;
        font-weight: bold;font-size: 16px;">THB&nbsp;(บาท)&nbsp;รวมภาษีมูลค่าเพิ่ม</td>
                            </tr>
                            <tr>
                                <td colspan="2"></td>
                                <td style="font-weight: bold;
        width: 85px;font-size: 16px;"><span></td>
                                <td style="width: 200px;
        font-weight: bold;font-size: 16px;"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- <div class=""> -->
            <div style="font-size: 16px;
        font-weight: bold;
        text-align: center;">สรุปรายการแจ้งยอดหนี้ชำระ</div>
            <div style=" height: 100%;">
                <div style=" width: 100%;
        height: 61%;">
                    <td></td>
                    <table style="width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        border-color: #dee2e6;
        font-size: 16px;">
                        <thead>
                            <tr>
                                <th style="padding: 0;
        margin: 0;  text-align: center;border-top-width: 1px;
        border-top-color: #222;
        border-top-style: solid;
        border-bottom-width: 1px;
        border-bottom-color: #222;
        border-bottom-style: solid;" width="10%" colspan="1">ลําดับ</th>
                                <th style="padding: 0;text-align: right !important;
        margin: 0;border-top-width: 1px;
        border-top-color: #222;
        border-top-style: solid;
        border-bottom-width: 1px;
        border-bottom-color: #222;
        border-bottom-style: solid;" width="18%">ประเภท*</th>
                                <th style="padding: 0;text-align: center !important;
        margin: 0;border-top-width: 1px;
        border-top-color: #222;
        border-top-style: solid;
        border-bottom-width: 1px;
        border-bottom-color: #222;
        border-bottom-style: solid;" width="18%">รายละเอียด</th>
                                <th style="padding: 0;
        margin: 0;  text-align: center;border-top-width: 1px;
        border-top-color: #222;
        border-top-style: solid;
        border-bottom-width: 1px;
        border-bottom-color: #222;
        border-bottom-style: solid;" width="15%">เลขใบแจ้งหนี้</th>
                                <th style="padding: 0;
        margin: 0;  text-align: center;border-top-width: 1px;
        border-top-color: #222;
        border-top-style: solid;
        border-bottom-width: 1px;
        border-bottom-color: #222;
        border-bottom-style: solid;" width="15%">วันที่ออกเอกสาร</th>
                                <th style="padding: 0;text-align: left !important;
        margin: 0;border-top-width: 1px;
        border-top-color: #222;
        border-top-style: solid;
        border-bottom-width: 1px;
        border-bottom-color: #222;
        border-bottom-style: solid;" width="15%">วันครบกําหนดชําระ</th>
                                <th style="padding: 0;text-align: right !important;
        margin: 0;border-top-width: 1px;
        border-top-color: #222;
        border-top-style: solid;
        border-bottom-width: 1px;
        border-bottom-color: #222;
        border-bottom-style: solid;" width="15%">เงื่อนไข</th>
                                <th style="padding: 0;
        margin: 0;  text-align: right;border-top-width: 1px;
        border-top-color: #222;
        border-top-style: solid;
        border-bottom-width: 1px;
        border-bottom-color: #222;
        border-bottom-style: solid;" width="20%">จํานวนเงิน</th>
                            </tr>

                            <div style="border-bottom: 1px solid #777;"></div>
                        </thead>
                        <tbody>
                            <?php if (!empty($data->report->lists)) {
                                foreach ($data->report->lists as $key => $item) {
                                    if ($key !== 'total') {
                            ?>
                                        <tr>
                                            <td colspan="1" style="padding: 0; 
        margin: 0;  text-align: center;"><?php if ($data->index == 1) {
                                                echo $key + 1;
                                            } else {
                                                echo (($data->index - 1) * $data->size) + $key + 1;
                                            }; ?></td>
                                            <td style="padding: 0;text-align: right !important;
        margin: 0;"><?php echo  $item->type ?></td>
                                            <td style="padding: 0;text-align: center !important;
        margin: 0;"><?php echo  $item->mtext ?></td>
                                            <td style="padding: 0;
        margin: 0;  text-align: center;"><?php echo !empty($item->mbillno) ? $item->mbillno : '-'; ?></td>
                                            <td style="padding: 0;
        margin: 0;  text-align: center;"><?php echo  !empty($item->mdocdate) ?  date('d.m.Y', strtotime($item->mdocdate)) :  date('d.m.Y', strtotime($item->mpostdate)); ?></td>
                                            <td style="padding: 0;text-align: left !important;
        margin: 0;"><?php echo date('d.m.Y', strtotime($item->mduedate)); ?></td>
                                            <td style="padding: 0;text-align: right !important;
        margin: 0;"><?php echo $item->mpayterm; ?></td>
                                            <td style="padding: 0;
        margin: 0;  text-align: right;"><?php echo !empty($item->mnetamt) ? number_format($item->mnetamt, 2) : 0; ?></td>
                                        </tr>
                            <?php
                                    }
                                }
                            } ?>
                        </tbody>
                    </table>
                </div>
                <table>
                    <thead>
                        <tr>
                            <td style="
                border-top: 1px solid #cdcdcd;
       font-size:16px;" colspan="7">
                                <!-- <p style="
                    text-align: start;">ยอดรวมชําระต่อหน้าเอกสาร <?php //echo !empty($data->report->total->total_summary) ? number_format($data->report->total->total_summary, 2) : 0
                                                                    ?> จํานวน <?php //echo count($data->report->lists)
                                                                                ?> รายการ</p> -->
                            </td>
                        </tr>
                    </thead>
                </table>

                <table>
                    <thead>
                        <tr>
                            <td>&nbsp;
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;
                            </td>
                        </tr>
                    </thead>
                </table>

                <div>
                    <?php //if ($data->report->total_page == $data->index) { 
                    ?>
                    <table>
                        <thead>
                            <tr>
                                <th style="border-top: 2px solid #66FFFF;border-bottom: 2px solid #66FFFF;border-top: 2px solid #66FFFF; width: 100%;text-align: center;font-size: 16px;font-weight: bold;" colspan="7">
                                    <p><?php echo  $data->tem['page_footer'][0]->due_detail; ?></p>
                                </th>
                            </tr>
                            <tr>
                                <th style="border-top: 2px solid #66FFFF;border-bottom: 2px solid #66FFFF; width: 100%;text-align: center;font-size: 16px;font-weight: bold;" colspan="7">
                                    <p><?php echo  $data->tem['page_footer'][0]->cal; ?></p>
                                </th>
                            </tr>
                        </thead>
                    </table>

                    <table>
                        <thead>
                            <tr>
                                <td>&nbsp;
                                </td>
                            </tr>
                        </thead>
                    </table>

                    <div style="padding-left: 10px;padding-right: 10px;">
                        <div style="margin-left: 10px;margin-top: 10px;">
                            <table style="width: 100%;border-collapse: collapse;color: gray;font-weight: bold;border-spacing: 0;">
                                <thead style="background-color: #f3f3f2;">
                                    <tr>
                                        <?php
                                        foreach ($data->doctypeLists as $key => $doctype) {
                                            if ($doctype->msort <= 5) {
                                                echo ' <th style="border-width: 2px;
                                                border-color: #cdcdcd;
                                                border-style: solid;
                                                padding: 1px;text-align: center;" width="15%">' . $doctype->type_display_th . '</th>';
                                            }
                                        }
                                        ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <?php if (!empty($data->doctypeLists['RA'])) { ?>
                                            <td style="border-width: 2px;
        border-color: #cdcdcd;
        border-style: solid;
        padding: 1px;text-align: end;"><?php echo !empty($data->report->total->total_RA) ? number_format($data->report->total->total_RA, 2) : 0 ?></td>
                                        <?php  } ?>
                                        <?php if (!empty($data->doctypeLists['RD'])) { ?>
                                            <td style="border-width: 2px;
        border-color: #cdcdcd;
        border-style: solid;
        padding: 1px;text-align: end;"><?php echo !empty($data->report->total->total_RD) ? '- ' . number_format($data->report->total->total_RD, 2) : 0 ?></td>
                                        <?php  } ?>
                                        <?php if (!empty($data->doctypeLists['RC'])) { ?>
                                            <td style="border-width: 2px;
        border-color: #cdcdcd;
        border-style: solid;
        padding: 1px;text-align: end;"><?php echo !empty($data->report->total->total_RC) ? number_format($data->report->total->total_RC, 2) : 0 ?></td>
                                        <?php  } ?>
                                        <?php if (!empty($data->doctypeLists['RB'])) { ?>
                                            <td style="border-width: 2px;
        border-color: #cdcdcd;
        border-style: solid;
        padding: 1px;text-align: end;"><?php echo !empty($data->report->total->total_RB) ? '- ' . number_format($data->report->total->total_RB, 2) : 0 ?></td>
                                        <?php  } ?>
                                        <?php if (!empty($data->doctypeLists['DC'])) { ?>
                                            <td style="border-width: 2px;
        border-color: #cdcdcd;
        border-style: solid;
        padding: 1px;text-align: end;"><?php echo !empty($data->report->total->total_DC) ? number_format($data->report->total->total_DC, 2) : 0 ?></td>
                                        <?php  } ?>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <table>
                            <thead>
                                <tr>
                                    <td>&nbsp;
                                    </td>
                                </tr>
                            </thead>
                        </table>
                        <div style="padding-left: 10px;padding-right: 10px;">
                            <div style="margin-left: 10px;margin-top: 10px;">
                                <table style="width: 100%;border-collapse: collapse;color: gray;font-weight: bold;border-spacing: 0;">
                                    <thead style="background-color: #f3f3f2;">
                                        <tr>
                                            <?php
                                            foreach ($data->doctypeLists as $key => $doctype) {
                                                if ($doctype->msort > 5) {
                                                    echo ' <th style="border-width: 2px;
                                                border-color: #cdcdcd;
                                                border-style: solid;
                                                padding: 1px;text-align: center;" width="15%">' . $doctype->type_display_th . '</th>';
                                                }
                                            }
                                            ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <?php if (!empty($data->doctypeLists['RE'])) { ?>
                                                <td style="border-width: 2px;
        border-color: #cdcdcd;
        border-style: solid;
        padding: 1px;text-align: end;"><?php echo !empty($data->report->total->total_RE) ? '- ' . number_format($data->report->total->total_RE, 2) : 0 ?></td>
                                            <?php  } ?>
                                            <?php if (!empty($data->doctypeLists['DA'])) { ?>
                                                <td style="border-width: 2px;
        border-color: #cdcdcd;
        border-style: solid;
        padding: 1px;text-align: end;"><?php echo !empty($data->report->total->total_DA) ? '- ' . number_format($data->report->total->total_DA, 2) : 0 ?></td>
                                            <?php  } ?>
                                            <?php if (!empty($data->doctypeLists['DB'])) { ?>
                                                <td style="border-width: 2px;
        border-color: #cdcdcd;
        border-style: solid;
        padding: 1px;text-align: end;"><?php echo !empty($data->report->total->total_DB) ? '- ' . number_format($data->report->total->total_DB, 2) : 0 ?></td>
                                            <?php  } ?>
                                            <?php if (!empty($data->doctypeLists['DE'])) { ?>
                                                <td style="border-width: 2px;
        border-color: #cdcdcd;
        border-style: solid;
        padding: 1px;text-align: end;"><?php echo !empty($data->report->total->total_DE) ? '- ' . number_format($data->report->total->total_DE, 2) : 0 ?></td>
                                            <?php  } ?>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>


                            <table>
                                <thead>
                                    <tr>
                                        <td>&nbsp;
                                        </td>
                                    </tr>
                                </thead>
                            </table>


                            <div style="padding-left: 30px;padding-right: 30px;">
                                <div style="margin-left: 60px;margin-top: 10px;">
                                    <table style="width: 90%;border-collapse: collapse;color: gray;font-weight: bold;border-spacing: 0;">
                                        <thead style="background-color: #f3f3f2;">
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th style="border-width: 2px;
        border-color: #cdcdcd;
        border-style: solid;
        padding: 1px;text-align: center;" width="15%">ยอดรวมรายการแจ้งหนี้</th>
                                                <th style="border-width: 2px;
        border-color: #cdcdcd;
        border-style: solid;
        padding: 1px;text-align: center;" width="15%">ยอดรวมรายการหักลบ</th>
                                                <th style="border-width: 2px;
        border-color: #cdcdcd;
        border-style: solid;
        padding: 1px;text-align: center;" width="15%">ยอดรวมชําระทั้งสิ้น</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td style="border-width: 2px;
        border-color: #cdcdcd;
        border-style: solid;
        padding: 1px;text-align: end;"><?php echo !empty($data->report->total->total_debit) ? number_format($data->report->total->total_debit, 2) : 0 ?></td>
                                                <td style="border-width: 2px;
        border-color: #cdcdcd;
        border-style: solid;
        padding: 1px;text-align: end;">&nbsp;<?php echo !empty($data->report->total->total_credit) ? '- ' . number_format($data->report->total->total_credit, 2) : 0 ?></td>
                                                <td style="border-width: 2px;
        border-color: #cdcdcd;
        border-style: solid;
        padding: 1px;text-align: end;color:color: #E11D48;"><?php echo !empty($data->report->total->total_summary) ? number_format($data->report->total->total_summary, 2) : 0 ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div style="margin-top: 10px;
        font-weight: bold;
        margin-left: 29px;
        text-align: center;">&nbsp;&nbsp;</div>
                                <div style="margin-top: 10px;
        font-weight: bold;
        margin-left: 29px;
        text-align: center;">จํานวนเอกสารทั้งหมด&nbsp;<?php echo $data->report->total_items ?>&nbsp;รายการ</div>
                                <div>
                                    <div style="margin: 0;
        padding: 0;
        font-weight: bold;
        text-align: center;
        font-size: 16px;margin-top: 5px;"><?php echo  $data->tem['page_footer'][0]->contact; ?></div>
                                    <div style="margin: 0;
        padding: 0;
        font-weight: bold;
        text-align: center;
        font-size: 16px;margin-top: 5px;">
                                        <?php
                                        $i = 1;
                                        foreach ($data->doctypeLists as $key => $doctype) {
                                            echo str_replace(':', ' = ', $doctype->type_display_th);
                                            echo $i == count($data->doctypeLists) ? '' : ', ';
                                            $i++;
                                        }
                                        ?>

                                    </div>
                                </div>
                            </div>
                            <?php //} 
                            ?>
                        </div>
                    </div>
                </div>




                <!-- footer -->
                <div style="margin-top: 10px;">
                    <div style="  border-bottom: 1px dashed #777;
        margin-bottom: 10px;
        margin-top: 10px;"></div>
                    <div style="border-bottom: 1px solid #222;"></div>
                    <div style="   border-bottom: 1px solid #777;
        margin-bottom: 10px;
        margin-top: 80px;"></div>
                    <table>
                        <thead>
                            <tr>
                                <th colspan="7">
                                    &nbsp;
                                </th>
                            </tr>
                            <tr>
                                <th colspan="7">
                                    &nbsp;
                                </th>
                            </tr>
                            <tr>
                                <th style="border-top: 2px dashed #777;" colspan="7">
                                    &nbsp;
                                </th>
                            </tr>

                            <tr>
                                <th style="height: 100px; border-top: 2px solid #777;border-bottom: 2px solid #777;" colspan="7">
                                    &nbsp;
                                </th>
                            </tr>
                        </thead>
                    </table>

                    <!-- detail -->
                    <div>
                        &nbsp;
                    </div>
                    <div style="display: flex;
        flex-direction: row;">
                        <table>
                            <thead>
                                <tr>
                                    <td colspan="7" style="
        font-weight: bold;
        font-size: 15px;">
                                        ใบแจ้งการชําระหนี้ผ่านธนาคาร (PAY-IN-SLIP)
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="7" style="
        font-size: 14px;
        font-weight: bold;">
                                        เพื่อเข้าบัญชีบริษัทนวพลาสติกอุตสาหกรรม (สระบุรี) จํากัด
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="font-size: 14px;font-weight: bold;margin:0%;padding:0%;">

                                        <?php foreach ($data->tem['bank'] as $payment) { ?>

                                            <div>
                                                <span class="input-checkbox"><input type="checkbox"></span>

                                                <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img width="12" src="<?php echo site_url(); ?>assets/img/<?php echo $payment->image_name; ?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $payment->bank_name; ?></span>
                                                <p style="margin: 0;padding: 0;"><?php echo (!empty($payment->branch) ? $payment->branch : ($payment->image_name == 'krungsri.png' ? '' : 'Comp. Code : ')) . $payment->comp_code; ?> <?php echo !empty($payment->account_no) ? 'เลขที่บ/ช ' . $payment->account_no : ''; ?></p>
                                            </div>
                                        <?php } ?>
                                    </td>

                                    <td>
                                        &nbsp;
                                    </td>
                                    <td colspan="3" style="
        width: 48%;
        height:40px;
        margin-top: 0;
        padding-top: 0;
        ">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <td colspan="1">
                                                        <img src="<?php echo site_url(); ?>assets/img/logo-300.png" width="70" />
                                                        <img src="<?php echo site_url(); ?>assets/img/nawaplastic_logo.gif" width="135" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="1">
                                                        &nbsp;
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3">
                                                        <div style="font-size: 16px;font-weight: bold;text-align: center;margin-top:5px;">สาขา/Branch ………………………………วันที่/Date………………………………</div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3" style="border-left:1px solid #777; border-right:1px solid #777; border-top:1px solid #777; border-bottom:1px solid #777;">
                                                        <div style="padding-left: 5px;
        font-weight: bold;
        font-size: 13px;"><span>SERIVE CODE:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;BBNPI</span></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3" style="border-left:1px solid #777; border-right:1px solid #777">
                                                        <div style="padding-left: 5px;
        font-weight: bold;
        font-size: 13px;"><span>Customer Name : ชื่อลูกค้า</span><span>&nbsp;<?php echo $data->report->info->mcustname; ?></span></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3" style="border-left:1px solid #777; border-right:1px solid #777">
                                                        <div style="padding-left: 5px;
        font-weight: bold;
        font-size: 13px;"><span>Customer No./Ref. 1: รหัสลูกค้า</span><span class="">&nbsp;<?php echo $data->report->info->mcustno; ?></span></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3" style="border-left:1px solid #777; border-right:1px solid #777">
                                                        <div style="padding-left: 5px;
        font-weight: bold;
        font-size: 13px;"><span>Reference 2 : หมายเหตุ(ถ้ามี)</span>&nbsp;<span>&nbsp;<?php echo $data->report->bill_info->bill_no; ?></span></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3" style="border-top:2px solid #777; border-bottom:2px solid #777;border-left:1px solid #777; border-right:1px solid #777">
                                                        <div style="padding-left: 5px;
        font-weight: bold;
        font-size: 13px;"><span>ยอดเงินสดชําระ/Amount in Cash ……………………………………… บาท/Baht</span></div>
                                                    </td>
                                                </tr>
                                            </thead>
                                        </table>

                                    </td>
                                </tr>
                            </thead>
                        </table>

                        <div>
                            &nbsp;
                        </div>



                        <div class="">
                            <table style="width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        border-color: #777;
        font-size: 14px;
        border-width: 1px;
        border-color: #777;
        border-style: solid">
                                <tbody>
                                    <tr>
                                        <td style="width: 108px;padding: 0;
        margin: 0;
        border-width: 1px;
        border-color: #777;
        border-style: solid;
        padding: 1px;
        font-size: 14px;
        font-weight: bold;
        text-align: center;">
                                            <p>หมายเลขเช็ค</p>
                                            <p>(Cheque No.)</p>
                                        </td>
                                        <td colspan="1" style="width: 88px;padding: 0;
        margin: 0;
        border-width: 1px;
        border-color: #777;
        border-style: solid;
        padding: 1px;
        font-size: 14px;
        font-weight: bold;
        text-align: center;">
                                            <p>เช็คลงวันที่</p>
                                            <p>(Cheque Date)</p>
                                        </td>
                                        <td style="width: 133px;padding: 0;
        margin: 0;
        border-width: 1px;
        border-color: #777;
        border-style: solid;
        padding: 1px;
        font-size: 14px;
        font-weight: bold;
        text-align: center;">
                                            <p>ชื่อธนาคาร</p>
                                            <p>(Drawee Bank)</p>
                                        </td>
                                        <td style="width: 101px;padding: 0;
        margin: 0;
        border-width: 1px;
        border-color: #777;
        border-style: solid;
        padding: 1px;
        font-size: 14px;
        font-weight: bold;
        text-align: center;">
                                            <p>สาขา</p>
                                            <p>(Branch)</p>
                                        </td>
                                        <td style="width: 124.625px;padding: 0;
        margin: 0;
        border-width: 1px;
        border-color: #777;
        border-style: solid;
        padding: 1px;
        font-size: 14px;
        font-weight: bold;
        text-align: center;">
                                            <p>จํานวนเงิน</p>
                                            <p>(Amount)</p>
                                        </td>
                                        <td colspan="1" style="width: 90.375px;padding: 0;
        margin: 0;
        border-left:1px solid #777;
        border-top:1px solid #777;
        border-bottom:1px solid #777;
        padding: 1px;
        font-size: 14px;
        font-weight: bold;
        text-align: center;">สําหรับเจ้าหน้าที่ธนาคาร</td>
                                        <td colspan="1" style="width: 90.375px;padding: 0;
        margin: 0;
        border-right:1px solid #777;
        border-top:1px solid #777;
        border-bottom:1px solid #777;
        padding: 1px;
        font-size: 14px;
        font-weight: bold;
        text-align: center;"></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 108px;   border-width: 1px;
        border-color: #777;
        border-style: solid;">&nbsp;</td>
                                        <td colspan="1" style="width: 88px;   border-width: 1px;
        border-color: #777;
        border-style: solid;">&nbsp;</td>
                                        <td style="width: 133px;   border-width: 1px;
        border-color: #777;
        border-style: solid;">&nbsp;</td>
                                        <td style="width: 101px;   border-width: 1px;
        border-color: #777;
        border-style: solid;">&nbsp;</td>
                                        <td style="width: 124.625px;   border-width: 1px;
        border-color: #777;
        border-style: solid;">&nbsp;</td>
                                        <td colspan="1" style="width: 90.375px; border-left:1px solid #777;
        border-top:1px solid #777;
        border-bottom:1px solid #777;">&nbsp;</td>
                                        <td colspan="1" style="width: 90.375px; border-right:1px solid #777;
        border-top:1px solid #777;
        border-bottom:1px solid #777;">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 645px;" colspan="6">
                                            <div style="padding-left: 35px;
        font-weight: bold;">โปรดเขียนจํานวนเงินเป็นตัวอักษร (Amount in Words)</div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>


                        <div style="padding: 5px 10px 0px 10px;">
                            <div style="float: left;
        text-align: start;
        padding-top: 15px;
        font-weight: bold;font-size: 16px;">
                                <p>ผู้นำฝาก…………………………………………</p>
                                <p>โทร………………………………………………</p>
                            </div>
                            <!-- <div class="qr-scan">
                                <img src="<?php echo site_url(); ?>assets/img/qrcode/qrcode.png" class="logo-qr-scan" width="120" height="120">
                            </div>
                            <p style="font-size: 14px;"> &nbsp;</p>
                            <div class="bacode-scan">
                                <img src="<?php echo site_url(); ?>assets/img/qrcode/barcode.jpg" width="450" height="50">
                                <p style="font-size: 14px;"><?php echo $data->report->barcode->code;
                                                            ?></p>
                            </div>
                            <p style="color: #E11D48;">* หมายเหตุ QR code & Barcode สามารถสแกนได้เฉพาะธนาคารไทยพาณิชย์</p> -->
                        </div>

                        <table style="padding:0;margin:0">
                            <tr>
                                <td style="border-bottom: 2px solid #cdcdcd;" colspan="7">
                                    &nbsp;
                                </td>
                            </tr>
                        </table>

                        <div style="
        margin-top: 10px;
        width: 100%;
        height: 90px;
        font-size: 16px;">
                            <p><u><strong><?php echo  $data->tem['footer'][0]->payment_title; ?></strong></u></p>
                            <ol>
                                <li>
                                    <p><?php echo  $data->tem['footer'][0]->detail_1_1; ?></p>
                                    <p><?php echo  $data->tem['footer'][0]->detail_1_2; ?></p>
                                    </il>
                                <li>
                                    <p><?php echo  $data->tem['footer'][0]->detail_2; ?></p>
                                    <p><?php echo  $data->tem['footer'][0]->detail_2_1; ?></p>
                                    <p><u><?php echo  $data->tem['footer'][0]->detail_2_2; ?></u></p>
                                    <p><?php echo  $data->tem['footer'][0]->detail_2_3; ?></p>
                                    <p><?php echo  $data->tem['footer'][0]->detail_2_4; ?></p>
                                    <p><?php echo  $data->tem['footer'][0]->detail_2_5; ?></p>
                                    <p><?php echo  $data->tem['footer'][0]->detail_2_6; ?></p>
                                    <p>&nbsp;&nbsp;<?php echo  $data->tem['footer'][0]->detail_2_7; ?></p>
                                    <p>&nbsp;&nbsp;<?php echo  $data->tem['footer'][0]->detail_2_8; ?></p>
                                    </il>
                                <li>
                                    <p><?php echo  $data->tem['footer'][0]->detail_3; ?></p>
                                    </il>
                                <li>
                                    <p style="margin-bottom:5px"><?php echo  $data->tem['footer'][0]->detail_4; ?></p>
                                    </il>
                            </ol>

                            <div style="font-size: 16px;
        font-weight: bold;
        text-align: center;"><?php echo  $data->tem['footer'][0]->detail_5; ?></div>
                        </div>

                        <table style="padding:0;margin:0">
                            <tr>
                                <td style="border-bottom: 2px solid #cdcdcd;" colspan="7">
                                    &nbsp;
                                </td>
                            </tr>
                        </table>

                        <div style="width: 100%;font-size: 16px;">
                            <p><u><strong><?php echo  $data->tem['bank_tran_detail'][0]->tran_header; ?></strong></u></p>
                            <ol>
                                <li>
                                    <p><?php echo  $data->tem['bank_tran_detail'][0]->tran_detail_1; ?></p>
                                    </il>
                                <li>
                                    <p><?php echo  $data->tem['bank_tran_detail'][0]->tran_detail_2; ?></p>
                                    </il>
                            </ol>
                            <?php foreach ($data->tem['bank_tran'] as $tran) { ?>
                                <p style="margin-left: 20px;"><strong>
                                        <span style="margin-right: 5%;"><?php echo $tran->account_name; ?></span>
                                        <span style="margin-right: 150px;"><?php echo $tran->branch; ?></span>
                                        <span style="margin-right: 150px;"><?php echo $tran->account_no; ?></span>
                                    </strong> </p>
                            <?php } ?>
                            <p><strong><?php echo  $data->tem['bank_tran_detail'][0]->tran_detail_3; ?></strong></p>
                        </div>
                        <table style="padding:0;margin:0">
                            <tr>
                                <td style="border-bottom: 2px solid #cdcdcd;" colspan="7">
                                    &nbsp;
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
