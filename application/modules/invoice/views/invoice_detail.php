<div class="container-fluid">
    <div class="bg-white rounded shadow rounded d-flex flex-column p-5">
        <div class="text-cus">บริษัทย่อย</div>
        <form class="invoice">
            <div class="accordion" id="accordionPricing">
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
                                    <p class="ms-2 am_total d-none">
                                        <?php echo !empty($child->balance) ? $child->balance : 0; ?>
                                    </p>
                                    <div class="total-invoice">
                                        <p>ยอดหนี้คงเหลือ</p>
                                        <p class="ms-2 total-text-<?php echo $key; ?>">
                                            <?php echo !empty($child->balance) ? number_format($bill->balance, 2) : 0; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php }; ?>
            </div>
            <div class="d-flex justify-content-end mt-3">
                <button type="button" class="btn btn-primary cf_bill" data-cus_no="<?php echo $key; ?>" disabled>ยืนยันบิล</button>
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



                                    // setTimeout(function() {
                                    //     window.location = '<?php echo $http; ?>/report';
                                    // }, 2500);
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
                let type = checkTotal.find('.mdoctype-' + genVal[0]).text();
                let key = checkTotal.find('.key-id').val();
                let cf_mnetamt = checkTotal.find('.cf-mnetamt-' + genVal[0]).text();

                if ($(this).is(":checked")) {
                    let invoice = {
                        'id': key,
                        'value': genVal[0],
                        'balance': parseFloat(cf_mnetamt.trim()),
                        'mdoctype': type.trim()
                    }

                    let checkData = data.filter(o => genVal[0] == o.value && key == o.id)
                    if (checkData.length < 1) {
                        data.push(invoice)
                    }

                    checkDisable();
                    let total = calculate(key);
                    checkTotal.find('.total-text-' + key).text(addComma(total, 2))
                } else {
                    let index = data.findIndex(o => genVal[0] == o.value && key == o.id)
                    data.splice(index, 1);
                    checkDisable();
                    let total = calculate(key) == 0 ? am_total : calculate(key);
                    checkTotal.find('.total-text-' + key).text(addComma(total, 2))
                    checkTotal.find('.cf-all-' + key).prop('checked', false);
                }

            }).on('change', '.check_all', function(e) {
                let value = $(this).val();
                let check = $(this);
                let checkTotal = check.parents('.invoice-list');
                if ($(this).is(":checked")) {
                    $('.check-' + value).prop('checked', true);
                    childLists.childs[value].bills.map(o => {
                        let invoice = {
                            'id': value,
                            'value': o.macctdoc,
                            'balance': parseFloat(o.mnetamt),
                            'mdoctype': o.mdoctype
                        }

                        let checkData = data.filter(x => x.value == o.macctdoc && x.id == childLists
                            .childs[value].info.cus_no)
                        if (checkData.length < 1) {
                            data.push(invoice)
                        }
                        checkDisable();
                        let total = calculate(childLists.childs[value].info.cus_no);
                        checkTotal.find('.total-text-' + childLists.childs[value].info.cus_no).text(
                            addComma(total, 2))
                    })
                } else {
                    $('.check-' + value).prop('checked', false);
                    childLists.childs[value].bills.map(x => {
                        let index = data.findIndex(o => o.value == x.macctdoc && o.id == childLists
                            .childs[value].info.cus_no)
                        data.splice(index, 1);
                        checkDisable();
                    })
                }
            });

        $('.modal_excel').on('click', '.btn-close,._close_ex', function(e) {
            e.preventDefault();
            window.location = '<?php echo $http; ?>/report';

        })


        function openPDF(res) {
            let key = Object.keys(res.data['data'])
            console.log(key)
            key.map(o => {
                console.log(o)
                // setTimeout(function() {
                //     window.open('<?php echo WWW . $http; ?>/report/pdf/' + res.data['data'][o].uuid, '_blank')
                // }, 500);
                window.open('<?php echo WWW; ?><?php echo $http ?>/report/pdf/' + res.data['data'][o].uuid, '_blank');

            })
        }


        function calculate(key) {
            let total = 0
            data.map(o => {
                if (key == o.id && o.mdoctype == 'RA') {
                    total = total + o.balance
                }
                if (key == o.id && o.mdoctype == 'RD') {
                    total = total + o.balance
                }
                if (key == o.id && o.mdoctype == 'DC') {
                    total = total - o.balance
                }
                if (key == o.id && o.mdoctype == 'RB') {
                    total = total - o.balance
                }
                if (key == o.id && o.mdoctype == 'RC') {
                    total = total - o.balance
                }
                if (key == o.id && o.mdoctype == 'RE') {
                    total = total - o.balance
                }
            })
            return total;
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
                        'balance': x.balance,
                        'mdoctype': x.mdoctype
                    }) : '')
                })
            }
        }
    });
</script>