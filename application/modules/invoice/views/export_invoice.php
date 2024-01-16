<html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">

<head>
    <meta http-equiv="Content-type" content="text/html;charset=utf-8" />
</head>

<body>
    <TABLE border="1" style="border-collapse:collapse;">
        <TR>
            <?php
            foreach ($result->header as $res) {
                $type = ($res->sort == 5 ? 'DC' : ($res->sort == 6 ? 'RB' : ($res->sort == 7 ? 'RC' : ($res->sort == 8 ? 'RD' : ($res->sort == 9 ? 'RE' : ($res->sort == 10 ? 'DB' : ($res->sort == 11 ? 'DE' : '')))))))
            ?>
                <?php if (in_array($res->sort, [1, 2, 3, 4])) { ?>
                    <TD><b><?php echo $res->colunm; ?></b></TD>
                <?php } ?>

                <?php if (in_array($res->sort, [5, 6, 7, 8, 9, 10, 11]) && in_array($type, array_keys($result->doctypeLists))) { ?>
                    <TD><b><?php echo $res->colunm; ?></b></TD>
                <?php } ?>

                <!-- <?php //if ($res->sort == 12) { 
                        ?>
                    <TD><b>สร้างใบแจ้งเตือน</b></TD>
                <?php /// } 
                ?> -->
            <?php } ?>
        </TR>
        <?php
        foreach ($result->data as $invoice) {
            // Loop for Products
        ?>
            <TR>

                <?php if (in_array(1, $result->keyTable)) { ?><td>
                        <?php echo $result->types[$invoice->msaleorg]->msaleorg_des; ?></td><?php }; ?>
                <?php if (in_array(2, $result->keyTable)) { ?><td>
                        <?php echo !empty($invoice->cus_no) ? $invoice->cus_no : '-'; ?>
                    </td><?php }; ?>
                <?php if (in_array(3, $result->keyTable)) { ?><td>
                        <?php echo !empty($invoice->cus_name) ? $invoice->cus_name : '-'; ?>
                    </td><?php }; ?>
                <?php if (in_array(4, $result->keyTable)) { ?><td><?php echo number_format($invoice->balance, 2); ?>
                    </td><?php }; ?>
                <?php if (in_array(5, $result->keyTable) && in_array('DC', array_keys($result->doctypeLists))) { ?><td>
                        <?php echo !empty($invoice->DC) ? number_format($invoice->DC, 2) : 0; ?></td><?php }; ?>
                <?php if (in_array(6, $result->keyTable) && in_array('RB', array_keys($result->doctypeLists))) { ?><td>
                        <?php echo !empty($invoice->RB) ? number_format($invoice->RB, 2) : 0; ?></td><?php }; ?>
                <?php if (in_array(7, $result->keyTable) && in_array('RC', array_keys($result->doctypeLists))) { ?><td>
                        <?php echo !empty($invoice->RC) ? number_format($invoice->RC, 2) : 0; ?></td><?php }; ?>
                <?php if (in_array(8, $result->keyTable) && in_array('RD', array_keys($result->doctypeLists))) { ?><td>
                        <?php echo !empty($invoice->RD) ? number_format($invoice->RD, 2) : 0; ?></td><?php }; ?>
                <?php if (in_array(9, $result->keyTable) && in_array('RE', array_keys($result->doctypeLists))) { ?><td>
                        <?php echo !empty($invoice->RE) ? number_format($invoice->RE, 2) : 0; ?></td><?php }; ?>
                <?php if (in_array(10, $result->keyTable) && in_array('DB', array_keys($result->doctypeLists))) { ?><td>
                        <?php echo !empty($invoice->DB) ? number_format($invoice->DB, 2) : 0; ?></td><?php }; ?>
                <?php if (in_array(11, $result->keyTable) && in_array('DE', array_keys($result->doctypeLists))) { ?><td>
                        <?php echo !empty($invoice->DE) ? number_format($invoice->DE, 2) : 0; ?></td><?php }; ?>
                <!-- <?php //if (in_array(12, $result->keyTable)) { 
                        ?><td>
                        <?php //echo in_array($invoice->cus_no, $result->checkBill) ? 'ทำใบแจ้งเตือนแล้ว' : 'ยังไม่ได้ทำใบแจ้งเตือน'; 
                        ?></td><?php //}; 
                                ?> -->
            </TR>
        <?php } ?>
    </TABLE>
</body>

</html>