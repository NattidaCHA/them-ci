<div class="container-fluid">
    <div class="bg-white rounded shadow rounded d-flex flex-column p-5">
        <div class="text-cus">บริษัทย่อย</div>
        <form class="invoice">
            <div class="accordion mb-3" id="accordionPricing">
                <?php foreach ($lists->childs as $key => $child) { ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="<?php echo $key; ?>">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?php echo $key; ?>" aria-expanded="true" aria-controls="collapse-<?php echo $key; ?>">
                                <?php echo $child->info->cus_name; ?> (<?php echo $key; ?>)
                            </button>
                        </h2>
                        <div id="collapse-<?php echo $key; ?>" class="accordion-collapse collapse" aria-labelledby="<?php echo $key; ?>" data-bs-parent="#accordionPricing">
                            <div class="accordion-body">
                                <div class="invoice-list">
                                    <div class="checkbox">
                                        <input class="form-check-input key-id" type="hidden" name="key[]" id="key" autocomplete="off" value="<?php echo  $key; ?>">
                                        <div class="form-check">
                                            <input class="form-check-input check_all cf-all-<?php echo $key; ?>" type="checkbox" name="check_all[]" id="check_all" value="<?php echo $key; ?>" autocomplete="off">
                                            <label class="form-check-label" for="check_all">
                                                เลือกทั้งหมด
                                            </label>
                                        </div>
                                    </div>
                                    <div class="border-bottom mb-2"></div>
                                    <div class="<?php echo count($child->bills) > 10 ? 'scroll' : '' ?>">
                                        <?php foreach ($child->bills as $val => $bill) { ?>
                                            <div class="group-invoice">
                                                <div class="list-invoice">
                                                    <div class="d-flex">
                                                        <div class="checkbox">
                                                            <div class="form-check">

                                                                <input class="form-check-input select_invoice check-<?php echo $key; ?>" type="checkbox" name="cf_invoice[]" id="cf_invoice" autocomplete="off" value="<?php echo $bill->macctdoc . '|' . $key; ?>" <?php echo in_array($bill->mdoctype, ['RA', 'RD']) ? 'checked' : '-'; ?>>
                                                            </div>
                                                        </div>
                                                        <p>ชนิดบิล : </p>
                                                        <p class="ms-2 mdoctype-<?php echo $bill->macctdoc; ?>">
                                                            <?php echo !empty($bill->mdoctype) ? $bill->mdoctype : '-'; ?>
                                                        </p>
                                                    </div>
                                                    <div class="d-flex">
                                                        <p>เลขที่บิล : </p>
                                                        <p class="ms-2"><?php echo !empty($bill->mbillno) || $bill->mbillno == ' ' ? $bill->mbillno : '-'; ?>
                                                        </p>
                                                    </div>
                                                    <div class="d-flex">
                                                        <p>ยอดหนี้ : </p>
                                                        <p class="d-none cf-mnetamt-<?php echo $bill->macctdoc; ?>">
                                                            <?php echo !empty($bill->mnetamt) ? $bill->mnetamt : 0; ?>
                                                        </p>
                                                        <p class="ms-2">
                                                            <?php echo !empty($bill->mnetamt) ? number_format($bill->mnetamt, 2) : 0; ?>
                                                        </p>
                                                    </div>
                                                    <div class="d-flex">
                                                        <p>วันที่ชำระบิล : </p>
                                                        <p class="ms-2">
                                                            <?php echo !empty($bill->mduedate) ? $bill->mduedate : '-'; ?>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="border-bottom mb-2"></div>
                                            </div>
                                        <?php }; ?>
                                    </div>

                                    <?php if (!empty($child->balance->total_RA)) { ?>
                                        <div class="total-invoice">
                                            <p>ยอดรวม RA </p>
                                            <p class="ms-2 RA-text-<?php echo $key; ?>">
                                                <?php echo !empty($child->balance->total_RA) ? number_format($child->balance->total_RA, 2) : 0; ?>
                                            </p>
                                        </div>
                                    <?php }; ?>

                                    <?php if (!empty($child->balance->total_RD)) { ?>
                                        <div class="total-invoice">
                                            <p>ยอดรวม RD</p>
                                            <p class="ms-2 RD-text-<?php echo $key; ?>">
                                                <?php echo !empty($child->balance->total_RD) ? number_format($child->balance->total_RD, 2) : 0; ?>
                                            </p>
                                        </div>
                                    <?php }; ?>

                                    <?php if (!empty($child->balance->total_RC)) { ?>
                                        <div class="total-invoice _sum_RC-<?php echo $key; ?>">
                                            <p>ยอดรวม RC</p>
                                            <p class="ms-2 RC-text-<?php echo $key; ?>">
                                                <?php echo !empty($child->balance->total_RC) ? '(' . number_format($child->balance->total_RC, 2) . ')' : 0; ?>
                                            </p>
                                        </div>
                                    <?php }; ?>

                                    <?php if (!empty($child->balance->total_RB)) { ?>
                                        <div class="total-invoice _sum_RB-<?php echo $key; ?>">
                                            <p>ยอดรวม RB</p>
                                            <p class="ms-2 RB-text-<?php echo $key; ?>">
                                                <?php echo !empty($child->balance->total_RB) ? '(' . number_format($child->balance->total_RB, 2) . ')' : 0; ?>
                                            </p>
                                        </div>
                                    <?php }; ?>

                                    <?php if (!empty($child->balance->total_DC)) { ?>
                                        <div class="total-invoice _sum_DC-<?php echo $key; ?>">
                                            <p>ยอดรวม DC</p>
                                            <p class="ms-2 DC-text-<?php echo $key; ?>">
                                                <?php echo !empty($child->balance->total_DC) ? '(' . number_format($child->balance->total_DC, 2) . ')' : 0; ?>
                                            </p>
                                        </div>
                                    <?php }; ?>

                                    <?php if (!empty($child->balance->total_RE)) { ?>
                                        <div class="total-invoice _sum_RE-<?php echo $key; ?>">
                                            <p>ยอดรวม RE</p>
                                            <p class="ms-2 RE-text-<?php echo $key; ?>">
                                                <?php echo !empty($child->balance->total_RE) ? '(' . number_format($child->balance->total_RE, 2) . ')' : 0; ?>
                                            </p>
                                        </div>
                                    <?php }; ?>

                                    <p class="ms-2 RA_total d-none">
                                        <?php echo !empty($child->balance->total_RA) ? $child->balance->total_RA : 0; ?>
                                    </p>

                                    <p class="ms-2 RD_total d-none">
                                        <?php echo !empty($child->balance->total_RD) ? $child->balance->total_RD : 0; ?>
                                    </p>

                                    <p class="ms-2 RD_total d-none">
                                        <?php echo !empty($child->balance->total_RD) ? $child->balance->total_RD : 0; ?>
                                    </p>

                                    <p class="ms-2 DC_total d-none">
                                        <?php echo !empty($child->balance->total_DC) ? $child->balance->total_DC : 0; ?>
                                    </p>

                                    <p class="ms-2 RB_total d-none">
                                        <?php echo !empty($child->balance->total_RB) ? $child->balance->total_RB : 0; ?>
                                    </p>

                                    <p class="ms-2 RC_total d-none">
                                        <?php echo !empty($child->balance->total_RC) ? $child->balance->total_RC : 0; ?>
                                    </p>

                                    <p class="ms-2 RE_total d-none">
                                        <?php echo !empty($child->balance->total_RE) ? $child->balance->total_RE : 0; ?>
                                    </p>

                                    <p class="ms-2 am_total d-none">
                                        <?php echo !empty($child->balance->total_balance) ? $child->balance->total_balance : 0; ?>
                                    </p>

                                    <div class="total-invoice">
                                        <p>ยอดหนี้คงเหลือ</p>
                                        <p class="ms-2 total-text-<?php echo $key; ?>">
                                            <?php echo !empty($child->balance->total_balance) ? number_format($child->balance->total_balance, 2) : 0; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php }; ?>
            </div>

            <div class="border-bottom mb-3 mt-2"></div>
            <?php if (!empty($lists->total_summary)) foreach ($lists->total_summary as $res) { { ?>
                    <div class="total-invoice summary_RA">
                        <p>ยอดรวม <?php echo $res->mdoctype; ?> ทั้งหมด</p>
                        <p class="ms-2 total-text-<?php echo $key; ?>">
                            <?php if (in_array($res->mdoctype, ['RA', 'RD'])) { ?>
                                <?php echo !empty($res->total) ? number_format($res->total, 2) : 0; ?>
                        </p>
                    <?php } else { ?>
                        <?php echo !empty($res->total) ? '(' . number_format($res->total, 2) . ')' : 0; ?>
                    <?php } ?>
                    </div>
            <?php }
            } ?>
            <div class="border-bottom mb-3"></div>
            <div class="d-flex justify-content-end mt-3">
                <button type="button" class="btn btn-primary cf_bill" data-cus_no="<?php echo $key; ?>" data-mduedate="<?php echo $child->bills[0]->mduedate; ?>" disabled>ยืนยันบิล</button>
                <button class="btn btn-primary cf_bill-loading" type="button" disabled style="display: none;">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    โหลด...
                </button>
            </div>
        </form>
    </div>
</div>



<script>
    $(function() {
        var childLists = <?php echo !empty($lists) ? json_encode($lists) : '[]'; ?>;
        let data = []

        if (childLists) {
            addData();
            checkDisable();
        }

        $('.invoice')
            .on('click', '.cf_bill', function(e) {
                e.preventDefault();
                let mainID = '<?php echo $main_id; ?>';
                let id = $(this).attr("data-cus_no");
                let mduedate = $(this).attr("data-mduedate");
                Swal.fire({
                    title: 'คุณต้องการทำบิลใช่หรือไม่?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'ยืนยัน',
                    cancelButtonText: 'ยกเลิก',
                }).then((result) => {
                    if (result.isConfirmed) {
                        readyProcess(true)
                        let formData = $('.invoice').serializeArray();
                        formData.push({
                            name: 'mduedate',
                            value: mduedate
                        })

                        $.post('<?php echo $http ?>/invoice/create/' + mainID + '/' + '<?php echo $start ?>' + '/' + '<?php echo $end ?>', formData).done(function(res) {
                            if (res.status == 200) {
                                let key = Object.keys(res.data['data'])
                                let html = "";
                                key.map(o => {
                                    let mail = res.data['data'][o].is_email ? '<span class="badge rounded-pill bg-primary mt-1"><i class="bi bi-envelope fs-6"></i></span>' : ''
                                    let fax = res.data['data'][o].is_fax ? '<span class="badge rounded-pill bg-success mt-1"><i class="bi bi-printer fs-6"></i></span>' : ''

                                    let pdf = '<a class="me-2 ms-2 mt-1 text-danger" href="<?php echo $http ?>/report/pdf/' + res.data['data'][o].uuid + '" target="_blank" id="report"><i class="bi bi-file-earmark-pdf fs-6"></i></a>'

                                    let excel = '<a type="button" class="text-success mt-1" href="<?php echo $http ?>/invoice/genExcel/' + res.data['data'][o].uuid + '" target="_blank" data-uuid="' + res.data['data'][o].uuid + '" id="excel"><i class="bi bi-file-earmark-excel fs-6"></i></a>'

                                    html += '<div class="d-flex mb-1"><div>' + mail + fax + '</div><span class="ms-2 mt-1 text-start">' + res.data['data'][o].cus_name + ' (' + res.data['data'][o].cus_no + ')</span>' + pdf + excel + '</div>'


                                })


                                Swal.fire({
                                    title: "ใบแจ้งเตือน",
                                    html: html,
                                    position: 'top',
                                    confirmButtonText: 'ตกลง'
                                }).then((result) => {
                                    let key = Object.keys(res.data['data'])
                                    key.forEach(o => {
                                        window.open('<?php echo WWW; ?><?php echo $http ?>/report/pdf/' + res.data['data'][o].uuid, '_blank', o)
                                    })



                                    setTimeout(function() {
                                        window.location = '<?php echo $http; ?>/report';
                                    }, 2500);
                                })

                                $.post('<?php echo $http ?>/api/addMainLog/create', {
                                    page: 'สร้างใบแจ้งเตือน',
                                    url: CURRENT_URL,
                                    detail: JSON.stringify(res.data['data']),
                                });
                            } else {
                                if (res.error) {
                                    Swal.fire("Error", res.error, "error");
                                } else {
                                    Swal.fire("Error", 'Something went wrong', "error");
                                }
                            }
                            readyProcess();
                        });
                    }
                })

            }).on('change', '.select_invoice', function(e) {
                let value = $(this).val();
                let genVal = $(this).val().split('|');
                let check = $(this);
                let checkTotal = check.parents('.invoice-list');
                let am_total = checkTotal.find('.am_total').text();
                let RA = checkTotal.find('.RA_total').text();
                let RD = checkTotal.find('.RD_total').text();
                let DC = checkTotal.find('.DC_total').text();
                let RB = checkTotal.find('.RB_total').text();
                let RC = checkTotal.find('.RC_total').text();
                let RE = checkTotal.find('.RE_total').text();
                let type = checkTotal.find('.mdoctype-' + genVal[0]).text();
                let key = checkTotal.find('.key-id').val();
                let cf_mnetamt = checkTotal.find('.cf-mnetamt-' + genVal[0]).text();

                if ($(this).is(":checked")) {
                    let invoice = {
                        'id': key,
                        'value': genVal[0],
                        'balance': parseFloat(cf_mnetamt.trim()),
                        'RA': type.trim() == 'RA' ? parseFloat(cf_mnetamt.trim()) : 0,
                        'RD': type.trim() == 'RD' ? parseFloat(cf_mnetamt.trim()) : 0,
                        'DC': type.trim() == 'DC' ? parseFloat(cf_mnetamt.trim()) : 0,
                        'RB': type.trim() == 'RB' ? parseFloat(cf_mnetamt.trim()) : 0,
                        'RC': type.trim() == 'RC' ? parseFloat(cf_mnetamt.trim()) : 0,
                        'RE': type.trim() == 'RE' ? parseFloat(cf_mnetamt.trim()) : 0,
                        'mdoctype': type.trim()
                    }

                    let checkData = data.filter(o => genVal[0] == o.value && key == o.id)
                    if (checkData.length < 1) {
                        data.push(invoice)
                    }

                    checkDisable();
                    let summary = calculate(key);

                    checkTotal.find('.total-text-' + key).text(addComma(summary.total == 0 ? am_total : summary.total, 2))

                    checkTotal.find('.RA-text-' + key).text(addComma(summary.RA == 0 ? RA : summary.RA, 2))
                    checkTotal.find('.RD-text-' + key).text(addComma(summary.RD == 0 ? RD : summary.RD, 2))

                    checkTotal.find('.DC-text-' + key).text('(' + addComma(summary.DC == 0 ? DC : summary.DC, 2) + ')')

                    checkTotal.find('.RB-text-' + key).text('(' + addComma(summary.RB == 0 ? RB : summary.RB, 2) + ')')
                    checkTotal.find('.RC-text-' + key).text('(' + addComma(summary.RC == 0 ? RC : summary.RC, 2) + ')')
                    checkTotal.find('.RE-text-' + key).text('(' + addComma(summary.RE == 0 ? RE : summary.RE, 2) + ')')

                    // let sendTotal = (type.trim() == 'DC' ? summary.DC : type.trim() == 'RB' ? summary.RB : type.trim() == 'RC' ? summary.RC : summary.RE)

                    // let find = (type.trim() == 'DC' ? checkTotal.find('._sum_DC-' + key) : type.trim() == 'RB' ? checkTotal.find('._sum_RB-' + key) : type.trim() == 'RC' ? checkTotal.find('._sum_RC-' + key) : checkTotal.find('._sum_RE-' + key))
                    // displayCal(sendTotal, find)
                } else {
                    let index = data.findIndex(o => genVal[0] == o.value && key == o.id)
                    data.splice(index, 1);
                    checkDisable();
                    let summary = calculate(key)
                    checkTotal.find('.total-text-' + key).text(addComma(summary.total == 0 ? am_total : summary.total, 2))


                    checkTotal.find('.RA-text-' + key).text(addComma(summary.RA == 0 ? RA : summary.RA, 2))
                    checkTotal.find('.RD-text-' + key).text(addComma(summary.RD == 0 ? RD : summary.RD, 2))

                    checkTotal.find('.DC-text-' + key).text('(' + addComma(summary.DC == 0 ? DC : summary.DC, 2) + ')')

                    checkTotal.find('.RB-text-' + key).text('(' + addComma(summary.RB == 0 ? RB : summary.RB, 2) + ')')
                    checkTotal.find('.RC-text-' + key).text('(' + addComma(summary.RC == 0 ? RC : summary.RC, 2) + ')')
                    checkTotal.find('.RE-text-' + key).text('(' + addComma(summary.RE == 0 ? RE : summary.RE, 2) + ')')

                    checkTotal.find('.cf-all-' + key).prop('checked', false);

                    // let sendTotal = (type.trim() == 'DC' ? summary.DC : type.trim() == 'RB' ? summary.RB : type.trim() == 'RC' ? summary.RC : summary.RE)

                    // let find = (type.trim() == 'DC' ? checkTotal.find('._sum_DC-' + key) : type.trim() == 'RB' ? checkTotal.find('._sum_RB-' + key) : type.trim() == 'RC' ? checkTotal.find('._sum_RC-' + key) : checkTotal.find('._sum_RE-' + key))
                    // displayCal(sendTotal, find)
                }

            }).on('change', '.check_all', function(e) {
                let value = $(this).val();
                let check = $(this);
                let checkTotal = check.parents('.invoice-list');
                let am_total = checkTotal.find('.am_total').text().trim() ? parseFloat(checkTotal.find('.am_total').text().trim()) : 0;
                let RA = checkTotal.find('.RA_total').text().trim() ? parseFloat(checkTotal.find('.RA_total').text().trim()) : 0;
                let RD = checkTotal.find('.RD_total').text().trim() ? parseFloat(checkTotal.find('.RD_total').text().trim()) : 0;
                let DC = checkTotal.find('.DC_total').text().trim() ? parseFloat(checkTotal.find('.DC_total').text().trim()) : 0;
                let RB = checkTotal.find('.RB_total').text().trim() ? parseFloat(checkTotal.find('.RB_total').text().trim()) : 0;
                let RC = checkTotal.find('.RC_total').text().trim() ? parseFloat(checkTotal.find('.RC_total').text().trim()) : 0;
                let RE = checkTotal.find('.RE_total').text().trim() ? parseFloat(checkTotal.find('.RE_total').text().trim()) : 0;
                if ($(this).is(":checked")) {
                    $('.check-' + value).prop('checked', true);
                    childLists.childs[value].bills.map(o => {
                        let invoice = {
                            'id': value,
                            'value': o.macctdoc,
                            'balance': parseFloat(o.mnetamt),
                            'RA': o.mdoctype == 'RA' ? parseFloat(o.mnetamt) : 0,
                            'RD': o.mdoctype == 'RD' ? parseFloat(o.mnetamt) : 0,
                            'DC': o.mdoctype == 'DC' ? parseFloat(o.mnetamt) : 0,
                            'RB': o.mdoctype == 'RB' ? parseFloat(o.mnetamt) : 0,
                            'RC': o.mdoctype == 'RC' ? parseFloat(o.mnetamt) : 0,
                            'RE': o.mdoctype == 'RE' ? parseFloat(o.mnetamt) : 0,
                            'mdoctype': o.mdoctype
                        }

                        let checkData = data.filter(x => x.value == o.macctdoc && x.id == childLists
                            .childs[value].info.cus_no)
                        if (checkData.length < 1) {
                            data.push(invoice)
                        }
                        checkDisable();
                        let summary = calculate(childLists.childs[value].info.cus_no);
                        checkTotal.find('.total-text-' + childLists.childs[value].info.cus_no).text(
                            addComma(summary.total, 2))


                        checkTotal.find('.RA-text-' + childLists.childs[value].info.cus_no).text(addComma(summary.RA == 0 ? RA : summary.RA, 2))
                        checkTotal.find('.RD-text-' + childLists.childs[value].info.cus_no).text(addComma(summary.RD == 0 ? RD : summary.RD, 2))

                        checkTotal.find('.DC-text-' + childLists.childs[value].info.cus_no).text('(' + addComma(summary.DC == 0 ? DC : summary.DC, 2) + ')')

                        checkTotal.find('.RB-text-' + childLists.childs[value].info.cus_no).text('(' + addComma(summary.RB == 0 ? RB : summary.RB, 2) + ')')
                        checkTotal.find('.RC-text-' + childLists.childs[value].info.cus_no).text('(' + addComma(summary.RC == 0 ? RC : summary.RC, 2) + ')')
                        checkTotal.find('.RE-text-' + childLists.childs[value].info.cus_no).text('(' + addComma(summary.RE == 0 ? RE : summary.RE, 2) + ')')

                        // let sendTotal = (o.mdoctype == 'DC' ? summary.DC : o.mdoctype == 'RB' ? summary.RB : o.mdoctype == 'RC' ? summary.RC : summary.RE)

                        // let find = (o.mdoctype == 'DC' ? checkTotal.find('._sum_DC-' + key) : type.trim() == 'RB' ? checkTotal.find('._sum_RB-' + key) : o.mdoctype == 'RC' ? checkTotal.find('._sum_RC-' + key) : checkTotal.find('._sum_RE-' + key))
                        // displayCal(sendTotal, find)
                    })
                } else {
                    $('.check-' + value).prop('checked', false);
                    childLists.childs[value].bills.map(x => {
                        let index = data.findIndex(o => o.value == x.macctdoc && o.id == childLists
                            .childs[value].info.cus_no)
                        data.splice(index, 1);

                        let total = am_total - DC - RB - RC - RE
                        checkTotal.find('.total-text-' + childLists.childs[value].info.cus_no).text(
                            addComma(total, 2))

                        checkTotal.find('.RA-text-' + childLists.childs[value].info.cus_no).text(addComma(RA, 2))
                        checkTotal.find('.RD-text-' + childLists.childs[value].info.cus_no).text(addComma(RD, 2))

                        checkTotal.find('.DC-text-' + childLists.childs[value].info.cus_no).text('(' + addComma(DC, 2) + ')')

                        checkTotal.find('.RB-text-' + childLists.childs[value].info.cus_no).text('(' + addComma(RB, 2) + ')')
                        checkTotal.find('.RC-text-' + childLists.childs[value].info.cus_no).text('(' + addComma(RC, 2) + ')')
                        checkTotal.find('.RE-text-' + childLists.childs[value].info.cus_no).text('(' + addComma(RE, 2) + ')')
                        checkDisable();
                    })
                }
            });


        function calculate(key) {
            let total = 0
            let RA = 0
            let RD = 0
            let DC = 0
            let RB = 0
            let RC = 0
            let RE = 0
            data.map(o => {
                if (key == o.id) {
                    if (o.mdoctype == 'RA') {
                        total = total + o.balance
                        RA += o.RA
                    }
                    if (o.mdoctype == 'RD') {
                        total = total + o.balance
                        RD += o.RD
                    }
                    if (o.mdoctype == 'DC') {
                        total = total - o.balance
                        DC += o.DC
                    }
                    if (o.mdoctype == 'RB') {
                        total = total - o.balance
                        RB += o.RB
                    }
                    if (o.mdoctype == 'RC') {
                        total = total - o.balance
                        RC += o.RC
                    }
                    if (o.mdoctype == 'RE') {
                        total = total - o.balance
                        RE += o.RE
                    }
                }
            })
            return {
                'total': total,
                'RA': RA,
                'RD': RD,
                'DC': DC,
                'RB': RB,
                'RC': RC,
                'RE': RE,
            };
        }

        function displayCal(total, findText) {
            if (total > 0) {
                findText.addClass('d-flex').removeClass('d-none');
            } else {
                findText.addClass('d-none').removeClass('d-flex');
            }

        }


        function readyProcess(wait = false) {
            if (wait) {
                $('.cf_bill').hide();
                $('.cf_bill-loading').show();
            } else {
                $('.cf_bill').show();
                $('.cf_bill-loading').hide();
            }
        }

        function checkDisable() {
            if (data.length > 0) {
                $('.invoice .cf_bill').prop('disabled', false);
            } else {
                $('.invoice .cf_bill').prop('disabled', true);
            }
        }

        function addData() {
            if (childLists.childs) {
                let key = Object.keys(childLists.childs)
                key.map(o => {
                    childLists.childs[o].bills.map(x => ['RA', 'RD'].includes(x.mdoctype) ? data.push({
                        'id': o,
                        'value': x.macctdoc,
                        'balance': parseFloat(x.mnetamt),
                        'RA': x.mdoctype == 'RA' ? parseFloat(x.mnetamt) : 0,
                        'RD': x.mdoctype == 'RD' ? parseFloat(x.mnetamt) : 0,
                        'DC': x.mdoctype == 'DC' ? parseFloat(x.mnetamt) : 0,
                        'RB': x.mdoctype == 'RB' ? parseFloat(x.mnetamt) : 0,
                        'RC': x.mdoctype == 'RC' ? parseFloat(x.mnetamt) : 0,
                        'RE': x.mdoctype == 'RE' ? parseFloat(x.mnetamt) : 0,
                        'mdoctype': x.mdoctype
                    }) : '')
                    // childLists.childs[o].bills.map(x => data.push({
                    //     'id': o,
                    //     'value': x.macctdoc,
                    //     'balance': parseFloat(x.mnetamt),
                    //     'RA': x.mdoctype == 'RA' ? parseFloat(x.mnetamt) : 0,
                    //     'RD': x.mdoctype == 'RD' ? parseFloat(x.mnetamt) : 0,
                    //     'DC': x.mdoctype == 'DC' ? parseFloat(x.mnetamt) : 0,
                    //     'RB': x.mdoctype == 'RB' ? parseFloat(x.mnetamt) : 0,
                    //     'RC': x.mdoctype == 'RC' ? parseFloat(x.mnetamt) : 0,
                    //     'RE': x.mdoctype == 'RE' ? parseFloat(x.mnetamt) : 0,
                    //     'mdoctype': x.mdoctype
                    // }))
                })
            }
        }
    });
</script>