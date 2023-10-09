<div class="container-fluid">
    <div class="bg-white rounded shadow rounded d-flex flex-column px-4 pt-3 pb-3">
        <form id="invoiceForm" method="get" action="<?php echo $http ?>/invoice" class="mb-4">
            <div class="section-filter">
                <div class="box-search">
                    <div class="input-search">
                        <label for="dateSelect" class="form-label">วันที่ต้องการแจ้ง <span class="text-danger">*</span></label>
                        <select class="form-select" id="dateSelect" name="dateSelect" required>
                            <option value="">เลือก ...</option>
                            <?php foreach ($selectDays as $day) { ?>
                                <option value="<?php echo $day->mday; ?>" <?php echo $dateSelect == $day->mday ? 'selected' : '' ?>><?php echo $days[$day->mday]->name ?></option>
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
                            <select class="select2 form-select" name="customer" id="customer" <?php echo in_array($this->CURUSER->cus_no, $search) ? '' : 'disabled' ?>>
                                <?php echo in_array($this->CURUSER->cus_no, $search) ? '<option value="">เลือก ...</option>' : ''; ?>

                                <?php if (!in_array($this->CURUSER->cus_no, $search)) {
                                ?>
                                    <option value="<?php echo $this->CURUSER->cus_no;
                                                    ?>" selected><?php echo $this->CURUSER->cus_name . ' (' . $this->CURUSER->cus_no . ')'
                                                                    ?></option>
                                <?php }
                                ?>

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
                            <option value="1" <?php echo $is_bill == '1' ? 'selected' : ''; ?>>ทั้งหมด</option>
                            <option value="2" <?php echo $is_bill == '2' ? 'selected' : ''; ?>>ทำใบแจ้งเตือนแล้ว</option>
                            <option value="3" <?php echo $is_bill == '3' ? 'selected' : ''; ?>>ยังไม่ได้ทำใบแจ้งเตือน</option>
                        </select>
                    </div>
                </div>
                <div class="box-search-2">
                    <div class="input-search">
                        <label for="type" class="form-label">Fax</label>
                        <select class="form-select" id="is_fax" name="is_fax">
                            <option value="1" <?php echo $is_fax == '1' ? 'selected' : ''; ?>>ทั้งหมด</option>
                            <option value="2" <?php echo $is_fax == '2' ? 'selected' : ''; ?>>มี Fax</option>
                            <option value="3" <?php echo $is_fax == '3' ? 'selected' : ''; ?>>ไม่มี Fax</option>
                        </select>
                    </div>

                    <div class="input-search">
                        <label for="type" class="form-label">อีเมล</label>
                        <select class="form-select" id="is_email" name="is_email">
                            <option value="1" <?php echo $is_email == '1' ? 'selected' : ''; ?>>ทั้งหมด</option>
                            <option value="2" <?php echo $is_email == '2' ? 'selected' : ''; ?>>มีอีเมล</option>
                            <option value="3" <?php echo $is_email == '3' ? 'selected' : ''; ?>>ไม่มีอีเมล</option>
                        </select>
                    </div>
                    <!-- <div class="box-text">
                        <p class="text-form"></p>
                    </div> -->
                    <div class="btn-full mb-3 mt-4">
                        <button type="submit" class="btn btn-primary me-2">ค้นหา</button>
                        <!-- <button type="submit" class="btn btn-success">Export excel</button> -->
                    </div>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table id="paymentList" class="table table-centered table-striped w-100">
                <thead class="thead-light">
                    <tr>
                        <?php foreach ($table as $res) { ?>
                            <th width="<?php if (in_array($res->sort, [2])) {
                                            echo '30%';
                                        } else if (in_array($res->sort, [1, 3, 4, 5, 6, 7, 8])) {
                                            echo '10%';
                                        }; ?>" class="no-search no-sort <?php echo $res->sort == 8 ? 'text-center' : '' ?>">
                                <?php echo $res->colunm; ?>
                            </th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($lists)) {
                        foreach ($lists as $invoice) { ?>
                            <tr>
                                <?php if (in_array(1, $keyTable)) { ?><td><?php echo $types[$invoice->msaleorg]->msaleorg_des; ?></td><?php }; ?>
                                <?php if (in_array(2, $keyTable)) { ?><td><?php echo !empty($invoice->cus_name) ? $invoice->cus_name . ' (' . $invoice->cus_no . ')' : '-'; ?>
                                    </td><?php }; ?>
                                <?php if (in_array(3, $keyTable)) { ?><td><?php echo number_format($invoice->balance, 2); ?></td><?php }; ?>
                                <?php if (in_array(4, $keyTable)) { ?><td><?php echo !empty($invoice->DC) ? number_format($invoice->DC, 2) : 0; ?></td><?php }; ?>
                                <?php if (in_array(5, $keyTable)) { ?><td><?php echo !empty($invoice->RE) ? number_format($invoice->RE, 2) : 0; ?></td><?php }; ?>
                                <?php if (in_array(6, $keyTable)) { ?><td><?php echo !empty($invoice->RC) ? number_format($invoice->RC, 2) : 0; ?></td><?php }; ?>
                                <?php if (in_array(7, $keyTable)) { ?><td><?php echo !empty($invoice->RD) ? number_format($invoice->RD, 2) : 0; ?></td><?php }; ?>
                                <?php if (in_array(8, $keyTable)) { ?><td class="text-center">
                                        <a class="btn btn-sm btn-gray-700 modalCustomer" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal" data-cus_no="<?php echo $invoice->cus_no ?>" data-cus_name="<?php echo $invoice->cus_name ?>">
                                            รายละเอียด
                                        </a>
                                    </td><?php }; ?>
                            </tr>
                    <?php
                        }
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex">
                    <h5 class="modal-title text-dark header_text me-3" id="exampleModalLabel"></h5>
                    <a type="button" class="btn btn-primary btn-detail btn-sm">รายละเอียด</a>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="flex-column cs-list customer">
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        var search = <?php echo !empty($search) ? json_encode($search) : '[]'; ?>;

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

        $('#customer').select2({
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

        $('#paymentList')
            .DataTable({
                "scrollX": false,
                "lengthChange": false,
                "pageLength": 20,
                "order": [
                    [0, "asc"]
                ],
                "columnDefs": [{
                        "targets": 'no-sort',
                        "orderable": false
                    },
                    {
                        "targets": 'no-search',
                        "searchable": false
                    },
                    {
                        "defaultContent": "",
                        "targets": "_all"
                    }
                ]
            }).on('click', '.modalCustomer', function(e) {
                e.preventDefault();
                let id = $(this).attr("data-cus_no")
                let name = $(this).attr("data-cus_name")
                let start = $('#startDate').val()
                let end = $('#endDate').val()
                let send = $('#dateSelect').val()
                $('.header_text').text(name + ' (' + id + ')');
                $('.customer').html('<div class="d-flex justify-content-center"><div class="spinner-border text-primary text-center mt-4 mb-3" role="status"><span class="visually-hidden">Loading...</span></div></div>')
                $('.btn-detail').attr("href", '<?php echo WWW; ?><?php echo $http; ?>/invoice/detail/' + id + '?start=' + start + '&end=' + end + '&send=' + send)

                $.get('<?php echo $http; ?>/invoice/genCustomerChild/' + id).done(function(res) {
                    if (res.status == 200) {
                        let text = "";
                        if (res.data.length > 0) {
                            res.data.map(o => {
                                text += '<p class="text-dark">' + o.cus_name + '(' + o.cus_no + ')' + '</p>';
                            })

                            $('.customer').html(text)
                        }
                    } else if (res.status == 204) {
                        $('.customer').html('<p class="text-dark text-center">ไม่พบข้อมูลบริษัท</p>')
                    }
                });
            });

        $('.dataTables_filter label').hide();

        function formatRepo(repo) {
            if (repo.loading) {
                return repo.text;
            }

            return $('<span>' + repo.cus_name + '(' + repo.cus_no + ')' + '</span>');
        }

        function formatRepoSelection(repo) {
            if (repo.id) {
                let show = search.includes('<?php echo $this->CURUSER->cus_no; ?>') ? repo.cus_name + '(' + repo.cus_no + ')' : repo.text;
                return $('<span>' + show + '</span>');
            }
            return repo.text;
        }
    });
</script>