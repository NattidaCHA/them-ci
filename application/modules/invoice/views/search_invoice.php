<div class="container-fluid">
    <div class="bg-white rounded shadow rounded d-flex flex-column px-5 pt-3 pb-3">
        <form id="invoiceForm" method="get" action="/invoice" class="mb-4">
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
                <div class="box-search-2">
                    <div class="input-search">
                        <label for="customer" class="form-label">ลูกค้า</label>
                        <div class="input-group mb-3">
                            <select class="select2 form-select" name="customer" id="customer">
                                <option value="" selected>เลือก ...</option>
                                <?php foreach ($customers as $customer) { ?>
                                    <option value="<?php echo $customer->mcustno; ?>" <?php echo $customer->mcustno == $cus_no ? 'selected' : '' ?>>
                                        <?php echo $customer->cus_name . ' (' . $customer->mcustno . ')' ?></option>
                                <?php  } ?>
                            </select>
                        </div>
                    </div>
                    <div class="input-search">
                        <label for="type" class="form-label">ประเภทธุรกิจ</label>
                        <select class="form-select" id="type" name="type">
                            <option value="" selected>เลือก ...</option>
                            <?php foreach ($types as $type) { ?>
                                <option value="<?php echo $type->msaleorg; ?>" <?php echo $type->msaleorg == $typeSC ? 'selected' : '' ?>><?php echo $type->msaleorg_des ?></option>
                            <?php  } ?>
                        </select>
                    </div>
                    <div class="input-search">
                        <label for="type" class="form-label">ทำบิล</label>
                        <select class="form-select" id="is_bill" name="is_bill">
                            <option value="0" selected>ทั้งหมด</option>
                            <option value="1">ทำบิลแล้ว</option>
                            <option value="2">ยังไม่ได้ทำบิล</option>
                        </select>
                    </div>
                    <div class="mb-3 mt-4">
                        <button type="submit" class="btn btn-primary">ค้นหา</button>
                    </div>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table id="paymentList" class="table table-centered table-striped w-100">
                <thead class="thead-light">
                    <tr>
                        <th width="10%">ประเภทธุรกิจ</th>
                        <th width="30%">ลูกค้า</th>
                        <th width="10%">ยอดหนี้</th>
                        <th width="10%">รีเบท</th>
                        <th width="10%">เงินเหลือ</th>
                        <th width="10%">ลดหนี้</th>
                        <th width="10%">เพิ่มหนี้</th>
                        <th width="10%" class="no-search no-sort text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($lists)) {
                        foreach ($lists as $invoice) { ?>
                            <tr>
                                <td><?php echo $types[$invoice->msaleorg]->msaleorg_des; ?></td>
                                <td><?php echo !empty($invoice->cus_name) ? $invoice->cus_name . ' (' . $invoice->cus_no . ')' : '-'; ?>
                                </td>
                                <td><?php echo number_format($invoice->balance, 2); ?></td>
                                <td><?php echo !empty($invoice->DC) ? number_format($invoice->DC, 2) : 0; ?></td>
                                <td><?php echo !empty($invoice->RE) ? number_format($invoice->RE, 2) : 0; ?></td>
                                <td><?php echo !empty($invoice->RC) ? number_format($invoice->RC, 2) : 0; ?></td>
                                <td><?php echo !empty($invoice->RD) ? number_format($invoice->RD, 2) : 0; ?></td>
                                <td class="text-center">
                                    <a class="btn btn-sm btn-gray-700 modalCustomer" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal" data-cus_no="<?php echo $invoice->cus_no ?>" data-cus_name="<?php echo $invoice->cus_name ?>">
                                        รายละเอียด
                                    </a>
                                </td>
                            </tr>
                    <?php }
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
            theme: "bootstrap-5"
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
                        "targets": [0, 2, 3, 4, 5, 6, 7],
                        "orderable": false
                    },
                    {
                        "targets": [1, 2, 3, 4, 5, 6],
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
                $('.btn-detail').attr("href", 'http://notification.com/invoice/detail/' + id + '?start=' + start + '&end=' + end + '&send=' + send)

                $.get('/invoice/genCustomerChild/' + id).done(function(res) {
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

    });
</script>