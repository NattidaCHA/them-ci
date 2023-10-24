<html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">

<head>
    <meta http-equiv="Content-type" content="text/html;charset=utf-8" />
</head>

<body>
    <TABLE border="1" style="border-collapse:collapse;">
        <TR>
            <?php
            foreach ($result->header as $val) if ($val->sort != 8) { ?>
                <TD><b><?php echo $val->colunm; ?></b></TD>
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
                        <?php echo !empty($invoice->cus_name) ? $invoice->cus_name . ' (' . $invoice->cus_no . ')' : '-'; ?>
                    </td><?php }; ?>
                <?php if (in_array(3, $result->keyTable)) { ?><td><?php echo number_format($invoice->balance, 2); ?>
                    </td><?php }; ?>
                <?php if (in_array(4, $result->keyTable)) { ?><td>
                        <?php echo !empty($invoice->DC) ? number_format($invoice->DC, 2) : 0; ?></td><?php }; ?>
                <?php if (in_array(5, $result->keyTable)) { ?><td>
                        <?php echo !empty($invoice->RE) ? number_format($invoice->RE, 2) : 0; ?></td><?php }; ?>
                <?php if (in_array(6, $result->keyTable)) { ?><td>
                        <?php echo !empty($invoice->RC) ? number_format($invoice->RC, 2) : 0; ?></td><?php }; ?>
                <?php if (in_array(7, $result->keyTable)) { ?><td>
                        <?php echo !empty($invoice->RD) ? number_format($invoice->RD, 2) : 0; ?></td><?php }; ?>
            </TR>
        <?php } ?>
    </TABLE>
</body>

</html>