<div class="container-fluid">
    <div class="bg-white rounded shadow rounded d-flex flex-column px-5 pt-3 pb-3 setting">
        <!-- Tab Nav -->
        <div class="nav-wrapper position-relative mb-2">
            <ul class="nav nav-pills nav-fill setting-tab" id="tabs-text" role="tablist">
                <li class="nav-item">
                    <a class="nav-link mb-sm-3 mb-md-0 <?php echo $tab == 'invoice' ? 'active' : '' ?>" type="button" id="tabs-text-1-tab" href="<?php echo $http ?>/setting?tab=invoice" aria-controls="tabs-text-1">การแจ้งเตือน</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mb-sm-3 mb-md-0 <?php echo $tab == 'report' ? 'active' : '' ?>" type="button" id="tabs-text-2-tab" href="<?php echo $http ?>/setting?tab=report" aria-controls="tabs-text-2">รายงาน</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mb-sm-3 mb-md-0 <?php echo $tab == 'customer' ? 'active' : '' ?>" type="button" id="tabs-text-3-tab" href="<?php echo $http ?>/setting?tab=customer" aria-controls="tabs-text-3">ลูกค้า</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mb-sm-3 mb-md-0 <?php echo $tab == 'repair' ? 'active' : '' ?>" type="button" id="tabs-text-4-tab" href="<?php echo $http ?>/setting?tab=repair" aria-controls="tabs-text-4">ซ่อมข้อมูล</a>
                </li>
            </ul>
        </div>
        <!-- End of Tab Nav -->
        <!-- Tab Content -->
        <div class="card border-0">
            <div class="card-body p-0">
                <div class="tab-content" id="tabcontent1">
                    <div class="tab-pane fade <?php echo $tab == 'invoice' ? 'show active' : '' ?>" id="tabs-text-1" role="tabpanel" aria-labelledby="tabs-text-1-tab">
                        <form class="invoicePage">
                            <div class="setting-section">
                                <h4>Colunm</h4>
                                <div class="setting-container">
                                    <?php foreach ($lists['invoice'] as $k => $val) { ?>
                                        <div class="form-setting">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input checl-<?php echo $val->uuid; ?>" type="checkbox" name="is_show[]" role="switch" id="<?php echo $val->uuid; ?>" value="<?php echo $val->uuid; ?>" <?php echo !empty($val->is_show) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="<?php echo $val->uuid; ?>"><?php echo $val->colunm; ?></label>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="d-flex justify-content-end mt-5">
                                    <button class="btn btn-primary loading" type="button" disabled style="display: none;">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        โหลด...
                                    </button>
                                    <button type="button" class="btn btn-primary submit">ยืนยัน</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade <?php echo $tab == 'report' ? 'show active' : '' ?>" id="tabs-text-2" role="tabpanel" aria-labelledby="tabs-text-2-tab">
                        <form class="reportPage">
                            <div class="setting-section">
                                <h4>Colunm</h4>
                                <div class="setting-container">
                                    <?php foreach ($lists['report'] as $k => $val) { ?>
                                        <div class="form-setting">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input checl-<?php echo $val->uuid; ?>" type="checkbox" name="is_show[]" role="switch" id="<?php echo $val->uuid; ?>" value="<?php echo $val->uuid; ?>" <?php echo !empty($val->is_show) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="<?php echo $val->uuid; ?>"><?php echo $val->colunm; ?></label>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="d-flex justify-content-end mt-5">
                                    <button class="btn btn-primary loading" type="button" disabled style="display: none;">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        โหลด...
                                    </button>
                                    <button type="button" class="btn btn-primary submit">ยืนยัน</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade <?php echo $tab == 'customer' ? 'show active' : '' ?>" id="tabs-text-3" role="tabpanel" aria-labelledby="tabs-text-3-tab">
                        <form class="customerPage">
                            <div class="setting-section">
                                <h4>Colunm</h4>
                                <div class="setting-container">
                                    <?php foreach ($lists['customer'] as $k => $val) { ?>
                                        <div class="form-setting">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input checl-<?php echo $val->uuid; ?>" type="checkbox" name="is_show[]" role="switch" id="<?php echo $val->uuid; ?>" value="<?php echo $val->uuid; ?>" <?php echo !empty($val->is_show) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="<?php echo $val->uuid; ?>"><?php echo $val->colunm; ?></label>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="d-flex justify-content-end aligtn mt-5">
                                    <button class="btn btn-primary loading" type="button" disabled style="display: none;">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        โหลด...
                                    </button>
                                    <button type="button" class="btn btn-primary submit">ยืนยัน</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="tab-pane fade <?php echo $tab == 'repair' ? 'show active' : '' ?>" id="tabs-text-4" role="tabpanel" aria-labelledby="tabs-text-4-tab">
                        <form class="repairPage" method="get" action="<?php echo $http ?>/setting?tab=repair">
                            <div class="section-filter">
                                <div class="box-search">
                                    <div class="input-search">
                                        <label for="dateSelect" class="form-label">วันที่ต้องการแจ้ง <span class="text-danger">*</span></label>
                                        <select class="form-select" id="dateSelect" name="dateSelect" required>
                                            <option value="">เลือก ...</option>
                                            <?php foreach ($selectDays as $day) { ?>
                                                <option value="<?php echo $day->mday; ?>"><?php echo $days[$day->mday]->name ?></option>
                                            <?php  } ?>
                                        </select>
                                    </div>

                                    <div class="input-search">
                                        <label for="startDate" class="form-label">จากวันที่ <span class="text-danger">*</span></label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" id="startDate" name="startDate" required placeholder="เริ่มวันที่" autocomplete="off" readonly value=<?php echo $startDate; ?>>
                                            <span class="input-group-text"><i class="bi bi-calendar-date me-2"></i></span>
                                        </div>
                                    </div>
                                    <div class="box-text">
                                        <p class="text-form">ถึง</p>
                                    </div>
                                    <div class="input-search input-endDate">
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" id="endDate" name="endDate" required placeholder="สิ้นสุดวันที่" autocomplete="off" readonly value=<?php echo $endDate; ?>>
                                            <span class="input-group-text"><i class="bi bi-calendar-date me-2"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-search">
                                    <div class="input-search">
                                        <label for="customer" class="form-label">ลูกค้า</label>
                                        <div class="input-group mb-3">
                                            <select class="select2 form-select" name="customer" id="customer">
                                                <option value="">เลือกลูกค้า</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="input-search">
                                        <label for="type" class="form-label">ประเภทธุรกิจ</label>
                                        <select class="form-select" id="type" name="type">
                                            <option value="" selected>เลือก ...</option>
                                            <?php foreach ($types as $type) { ?>
                                                <option value="<?php echo $type->msaleorg; ?>" <?php echo $type->msaleorg == $typeSC ? 'selected' : ''; ?>><?php echo $type->msaleorg_des ?></option>
                                            <?php }; ?>
                                        </select>
                                    </div>
                                    <div class="box-text">
                                        <p class="text-form"></p>
                                    </div>
                                    <div class="input-search">
                                        <label for="type" class="form-label">ทำบิล</label>
                                        <select class="form-select" id="is_bill" name="is_bill">
                                            <option value="1">ทั้งหมด</option>
                                            <option value="2">ทำใบแจ้งเตือนแล้ว</option>
                                            <option value="3" selected>ยังไม่ได้ทำใบแจ้งเตือน</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="box-search-2">
                                    <div class="input-search">
                                        <label for="type" class="form-label">ช่องทางการติดต่อ</label>
                                        <select class="form-select" id="is_contact" name="is_contact">
                                            <option value="1" <?php echo $is_contact == '1' ? 'selected' : ''; ?>>ทั้งหมด</option>
                                            <option value="2" <?php echo $is_contact == '2' ? 'selected' : ''; ?>>Email</option>
                                            <option value="3" <?php echo $is_contact == '3' ? 'selected' : ''; ?>>Fax</option>
                                            <option value="4" <?php echo $is_contact == '4' ? 'selected' : ''; ?>>Email & Fax</option>
                                            <option value="5" <?php echo $is_contact == '5' ? 'selected' : ''; ?>>No Fax</option>
                                            <option value="6" <?php echo $is_contact == '6' ? 'selected' : ''; ?>>No Email</option>
                                            <option value="7" <?php echo $is_contact == '7' ? 'selected' : ''; ?>>No Fax & No Email</option>
                                            <option value="8" <?php echo $is_contact == '8' ? 'selected' : ''; ?>>Email & No Fax</option>
                                            <option value="9" <?php echo $is_contact == '9' ? 'selected' : ''; ?>>No Email & Fax</option>
                                        </select>
                                    </div>

                                    <div class="btn-full mb-3 mt-4">
                                        <button type="button" class="btn btn-primary submit" disabled>ค้นหา</button>
                                        <button type="button" class="btn btn-success export" disabled>Export excel</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="alert alert-warning mt-3 d-none" role="alert">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> โปรดรอสักครู่ ระบบกำลังซ่อมข้อมูล
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Tab Content -->
    </div>
</div>

<script>
    $(function() {
        $('.invoicePage').on('click', '.submit', function(e) {
            e.preventDefault();
            readyProcess('.invoicePage', true)
            let formData = $('.invoicePage').serializeArray();
            process('.invoicePage', formData)
        });

        $('.reportPage').on('click', '.submit', function(e) {
            e.preventDefault();
            readyProcess('.reportPage', true)
            let formData = $('.reportPage').serializeArray();
            process('.reportPage', formData)
        });

        $('.customerPage').on('click', '.submit', function(e) {
            e.preventDefault();
            readyProcess('.customerPage', true)
            let formData = $('.customerPage').serializeArray();
            process('.customerPage', formData)
        });

        $('#startDate').datepicker({
            todayHighlight: true,
            format: 'yyyy-mm-dd',
            autoclose: true,
        });

        $('#endDate').datepicker({
            todayHighlight: true,
            format: 'yyyy-mm-dd',
            autoclose: true,
        });

        $('.select2').select2({
            theme: "bootstrap-5",
            allowClear: false,
            placeholder: "ลูกค้าทั้งหมด",
            ajax: {
                url: "<?php echo $http ?>/api/searchCustomerMain",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term,
                        page: 1
                    };
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.results,
                        pagination: false
                    };
                }
            },
            allowHtml: true,
            templateResult: formatRepo,
            templateSelection: formatRepoSelection
        });

        $('.repairPage').on('click', '.submit', function(e) {
            e.preventDefault();
            $('.repairPage .submit').prop("disabled", true);
            $('.repairPage .export').prop("disabled", true);
            $('#tabs-text-4 .alert').removeClass('d-none').addClass('d-block')
            let formData = $('.repairPage').serializeArray();
            $.post("<?php echo $http ?>/setting/repair", formData).done(function(res) {
                if (res.status === 200) {
                    $('#tabs-text-4 .alert').removeClass('d-block').addClass('d-none')
                    Swal.fire({
                        icon: 'success',
                        text: 'ซ่อมข้อมูลเรียบร้อย',
                        confirmButtonText: 'ตกลง'
                    })
                    $('.repairPage .submit').prop("disabled", false);
                    $('.repairPage .export').prop("disabled", false);

                    $.post('<?php echo $http ?>/api/addMainLog/update', {
                        page: 'ซ่อมข้อมูล',
                        url: CURRENT_URL,
                        detail: JSON.stringify(res.data),
                    });
                } else {
                    $('#tabs-text-4 .alert').removeClass('d-block').addClass('d-none')
                    $('.repairPage .submit').prop("disabled", false);
                    $('.repairPage .export').prop("disabled", false);
                    if (res.error) {
                        Swal.fire({
                            icon: 'error',
                            text: res.error,
                            confirmButtonText: 'ตกลง'
                        })
                    } else {
                        Swal.fire("Error", 'Something went wrong', "error");
                    }
                }
            });

        }).on('click', '.export', function(e) {
            e.preventDefault();
            let formData = $('.repairPage').serializeArray();
            let path = formData[0].name + '=' + formData[0].value + '&' + formData[1].name + '=' + formData[1].value + '&' + formData[2].name + '=' + formData[2].value + '&' + formData[3].name + '=' + formData[3].value + '&' + formData[4].name + '=' + formData[4].value + '&' + formData[5].name + '=' + formData[5].value + '&' + formData[6].name + '=' + formData[6].value
            window.open("<?php echo $http ?>/invoice/genInvoiceListExcel?" + path, '_self');

        });


        $('.repairPage #dateSelect').on('change', function(e) {
            let val = $(this).val()
            if (val) {
                $('.repairPage .export').prop('disabled', false)
                $('.repairPage .submit').prop('disabled', false)
            } else {
                $('.repairPage .export').prop('disabled', true)
                $('.repairPage .submit').prop('disabled', true)
            }
        })

        function process(tab, formData) {
            $.post("<?php echo $http ?>/setting/process/<?php echo $tab; ?>", formData).done(function(res) {
                if (res.status === 200) {
                    Swal.fire({
                        icon: 'success',
                        text: 'อัปเดตข้อมูลเรียบร้อย',
                        confirmButtonText: 'ตกลง'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location = '<?php echo $http ?>/setting?tab=' + '<?php echo $tab; ?>';

                            $.post('<?php echo $http ?>/api/addMainLog/update', {
                                page: 'setting_<?php echo $tab; ?>',
                                url: CURRENT_URL,
                                detail: JSON.stringify(res.data),
                            });
                        }
                    })
                } else {
                    if (res.error) {
                        Swal.fire({
                            icon: 'error',
                            text: res.error,
                            confirmButtonText: 'ตกลง'
                        })
                    } else {
                        Swal.fire("Error", 'Something went wrong', "error");
                    }
                }
                readyProcess(tab);
            });
        }

        function readyProcess(tab, wait = false) {
            if (wait) {
                $(tab + ' .submit').hide();
                $(tab + ' .loading').show();
            } else {
                $(tab + ' .submit').show();
                $(tab + ' .loading').hide();
            }
        }

        function formatRepo(repo) {
            if (repo.loading) {
                return repo.text;
            }

            return $('<span>' + repo.cus_name + '(' + repo.cus_no + ')' + '</span>');
        }

        function formatRepoSelection(repo) {
            if (repo.id) {
                return $('<span>' + repo.cus_name + '(' + repo.cus_no + ')' + '</span>');
            }
            return repo.text;
        }
    });
</script>