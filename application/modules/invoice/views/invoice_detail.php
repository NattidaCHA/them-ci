<div class="container-fluid">
    <div class="bg-white rounded shadow rounded d-flex flex-column p-5">
        <form class="invoice">
            <div class="accordion" id="accordionPricing">
                <?php foreach ($lists->childs as $key => $child) { ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="<?php echo $key; ?>">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?php echo $key; ?>" aria-expanded="true" aria-controls="collapse-<?php echo $key; ?>">
                                <?php echo $child->info->cus_name; ?> (<?php echo $key; ?>)
                            </button>
                        </h2>
                        <div id="collapse-<?php echo $key; ?>" class="accordion-collapse collapse show" aria-labelledby="<?php echo $key; ?>" data-bs-parent="#accordionPricing">
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
                                    <?php foreach ($child->bills as $val => $bill) { ?>
                                        <div class="group-invoice">
                                            <div class="checkbox">
                                                <div class="form-check">
                                                    <input class="form-check-input select_invoice check-<?php echo $key; ?>" type="checkbox" name="cf_invoice[]" id="cf_invoice" autocomplete="off" value="<?php echo $bill->macctdoc . '|' . $key; ?>">
                                                </div>
                                            </div>
                                            <div class="list-invoice">
                                                <div class="d-flex">
                                                    <p>ชนิดบิล : </p>
                                                    <p class="ms-2">
                                                        <?php echo !empty($bill->mdoctype) ? $bill->mdoctype : '-'; ?>
                                                    </p>
                                                </div>
                                                <div class="d-flex">
                                                    <p>เลขที่บิล : </p>
                                                    <p class="ms-2"><?php echo !empty($bill->mnetamt) ? $bill->mnetamt : '-'; ?>
                                                    </p>
                                                </div>
                                                <div class="d-flex">
                                                    <p>ยอดหนี้ : </p>
                                                    <p class="ms-2 cf-mnetamt-<?php echo $bill->macctdoc; ?>">
                                                        <?php echo !empty($bill->mnetamt) ? $bill->mnetamt : 0; ?>
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
                                    <p class="ms-2 am_total d-none">
                                        <?php echo !empty($child->balance) ? str_replace('-', '', $child->balance) : 0; ?>
                                    </p>
                                    <div class="total-invoice">
                                        <p>ยอดหนี้คงเหลือ</p>
                                        <p class="ms-2 total-text-<?php echo $key; ?>">
                                            <?php echo !empty($child->balance) ? str_replace('-', '', $bill->balance) : 0; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php }; ?>
            </div>
            <div class="d-flex justify-content-end mt-3">
                <button type="button" class="btn btn-primary cf_bill" data-cus_no="<?php echo $key; ?>">ยืนยันบิล</button>
            </div>
        </form>
    </div>
</div>



<script>
    $(function() {
        var childLists = <?php echo !empty($lists) ? json_encode($lists) : '[]'; ?>;
        let data = []
        $('.invoice')
            .on('click', '.cf_bill', function(e) {
                let mainID = '<?php echo $main_id; ?>';
                let id = $(this).attr("data-cus_no");
                console.log(id)
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let formData = $('.invoice').serializeArray();
                        console.log(formData)
                        console.log(data)
                        $.post('/invoice/create/' + mainID, formData).done(function(res) {

                            if (res.status == 200) {
                                Swal.fire("Complete", msg + '.', "success").then(() => {
                                    window.location.href = '/invoice/report';
                                });
                            } else {
                                if (res.error) {
                                    Swal.fire("Error", res.error.message, "error");
                                } else {
                                    Swal.fire("Error", 'Something went wrong', "error");
                                }
                            }
                        });
                    }
                })

            }).on('change', '.select_invoice', function(e) {
                let value = $(this).val();
                let genVal = $(this).val().split('|');
                let check = $(this);
                let checkTotal = check.parents('.invoice-list');
                let am_total = checkTotal.find('.am_total').text();
                let key = checkTotal.find('.key-id').val();
                let cf_mnetamt = checkTotal.find('.cf-mnetamt-' + genVal[0]).text();
                if ($(this).is(":checked")) {
                    let invoice = {
                        'id': key,
                        'value': genVal[0],
                        'balance': parseFloat(cf_mnetamt)
                    }

                    let checkData = data.filter(o => genVal[0] == o.value && key == o.id)
                    if (checkData.length < 1) {
                        data.push(invoice)
                    }
                    let total = calculate(key);
                    checkTotal.find('.total-text-' + key).text(total)
                } else {
                    let index = data.findIndex(o => genVal[0] == o.value && key == o.id)
                    data.splice(index, 1);
                    let total = calculate(key) == 0 ? am_total : calculate(key);
                    checkTotal.find('.total-text-' + key).text(total)
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
                            'balance': parseFloat(o.mnetamt)
                        }

                        let checkData = data.filter(x => x.value == o.macctdoc && x.id == childLists
                            .childs[value].info.cus_no)
                        if (checkData.length < 1) {
                            data.push(invoice)
                        }
                        let total = calculate(childLists.childs[value].info.cus_no);
                        checkTotal.find('.total-text-' + childLists.childs[value].info.cus_no).text(
                            total)
                    })
                } else {
                    $('.check-' + value).prop('checked', false);
                    childLists.childs[value].bills.map(x => {
                        let index = data.findIndex(o => o.value == x.macctdoc && o.id == childLists
                            .childs[value].info.cus_no)
                        data.splice(index, 1);
                    })
                }
            });


        function calculate(key) {
            let total = 0
            data.map(o => {
                if (key == o.id) {
                    total += o.balance
                }
            })
            return total;
        }
    });
</script>