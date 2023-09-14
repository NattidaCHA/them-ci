<div class="container-fluid">
    <div class="bg-white rounded shadow rounded d-flex flex-column px-5 pt-3 pb-3 setting">
        <!-- Tab Nav -->
        <div class="nav-wrapper position-relative mb-2 w-50">
            <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-text" role="tablist">
                <li class="nav-item">
                    <a class="nav-link mb-sm-3 mb-md-0 <?php echo $tab == 'invoice' ? 'active' : '' ?>" type="button" id="tabs-text-1-tab" href="/setting?tab=invoice" aria-controls="tabs-text-1">Invoice</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mb-sm-3 mb-md-0 <?php echo $tab == 'report' ? 'active' : '' ?>" type="button" id="tabs-text-2-tab" href="/setting?tab=report" aria-controls="tabs-text-2">Report</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mb-sm-3 mb-md-0 <?php echo $tab == 'customer' ? 'active' : '' ?>" type="button" id="tabs-text-3-tab" href="/setting?tab=customer" aria-controls="tabs-text-3">Customer</a>
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

        function process(tab, formData) {
            $.post("/setting/process/<?php echo $tab; ?>", formData).done(function(res) {
                if (res.status === 200) {
                    Swal.fire({
                        icon: 'success',
                        text: 'อัปเดตข้อมูลเรียบร้อย',
                        confirmButtonText: 'ตกลง'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location = '/setting?tab=' + '<?php echo $tab; ?>';
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
    });
</script>