<div class="container-fluid">
    <div class="bg-white rounded shadow rounded d-flex flex-column px-5 pt-3 pb-3">
        <form id="invoiceForm" method="get" action="/report" class="mb-4">
            <div class="section-filter-2">
                <div class="box-search">
                    <div class="input-search-2">
                        <label for="startDate" class="form-label">วันสร้างบิล</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" id="created_date" name="created_date" placeholder="วันสร้างบิล" readonly autocomplete="off" value="<?php echo $created_date; ?>">
                            <span class="input-group-text"><i class="bi bi-calendar-date me-2"></i></span>
                        </div>
                    </div>
                    <div class="input-search-2">
                        <label for="customer" class="form-label">เลขที่เอกสาร</label>
                        <div class="input-group mb-3">
                            <select class="select2 form-select" name="bill_no" id="bill_no">
                                <option value="" selected>เลือก ...</option>
                                <?php foreach ($billNos as $billNo) { ?>
                                    <option value="<?php echo $billNo->bill_no; ?>" <?php echo $bill_no == $billNo->bill_no ? 'selected' : '' ?>>
                                        <?php echo $billNo->bill_no; ?></option>
                                <?php  } ?>
                            </select>
                        </div>
                    </div>
                    <div class="input-search-2">
                        <label for="customer" class="form-label">ลูกค้า</label>
                        <div class="input-group mb-3">
                            <select class="select2 form-select" name="customer" id="customer">
                                <option value="" selected>เลือก ...</option>
                                <?php foreach ($customers as $customer) { ?>
                                    <option value="<?php echo $customer->mcustno; ?>" <?php echo $cus_no == $customer->mcustno ? 'selected' : '' ?>>
                                        <?php echo $customer->cus_name . ' (' . $customer->mcustno . ')' ?></option>
                                <?php  } ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 mt-4 me-3">
                        <button type="submit" class="btn btn-primary">ค้นหา</button>
                    </div>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table id="reportList" class="table table-centered table-striped w-100">
                <thead class="thead-light">
                    <tr>
                        <th width="10%">เลขที่เอกสาร</th>
                        <th width="15%" class="no-search no-sort">ลูกค้า</th>
                        <th width="15%" class="no-search no-sort">อีเมล</th>
                        <th width="15%" class="no-search no-sort">เบอร์โทร</th>
                        <th width="5%" class="no-search no-sort">โทรแจ้ง</th>
                        <th width="10%" class="no-search no-sort">ผู้ติดต่อ</th>
                        <th width="10%" class="no-search no-sort">ผู้รับสาย</th>
                        <th width="5%" class="no-search no-sort">สถานะ</th>
                        <th width="15%" class="no-search no-sort text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($lists)) {
                        foreach ($lists as $invoice) { ?>
                            <tr>
                                <td><?php echo $invoice->bill_no; ?></td>
                                <td><?php echo !empty($invoice->mcustname) ? $invoice->mcustname . ' (' . $invoice->cus_no . ')' : '-'; ?>
                                </td>
                                <td>
                                    <?php if (!empty($invoice->uuid)) { ?>
                                        <div id="tel_<?php echo $invoice->uuid ?>">
                                            <div class="" id="tel_heading_<?php echo $invoice->uuid ?>">
                                                <?php if (!empty($invoice->memail)) { ?>
                                                    <?php if (count(explode(',', $invoice->memail)) > 3) { ?>
                                                        <?php foreach (array_slice(explode(',', $invoice->memail), 0, 3) as $key => $value) { ?>
                                                            <?php echo (($key < 2)) ? $value . ',' : $value; ?><?php } ?>
                                            </div>
                                            <div id="tel_collapse_<?php echo $invoice->uuid ?>" class="accordion-collapse collapse" aria-labelledby="tel_heading_<?php echo $invoice->uuid ?>" data-parent="#tel_<?php echo $invoice->uuid ?>">
                                                <?php $count = count(array_slice(explode(',', $invoice->memail), 3)); ?>
                                                <?php $i = 0; ?>
                                                <?php foreach (array_slice(explode(',', $invoice->memail), 3) as $key => $value) { ?>
                                                    <?php echo ((++$i == $count)) ? $value : $value . ','; ?>
                                                <?php } ?>
                                            </div>
                                        <?php } else { ?>
                                            <?php if (count(explode(',', $invoice->memail)) > 1) { ?>
                                                <?php foreach (array_slice(explode(',', $invoice->memail), 0, 2) as $key => $value) { ?>
                                                    <?php echo (($key == 1)) ? $value : $value . ','; ?><?php } ?>
                                                <?php } else {
                                                            echo $invoice->memail;
                                                        } ?>
                                            <?php } ?>
                                            <?php if (count(explode(',', $invoice->memail)) > 3) { ?>
                                                &nbsp;&nbsp;<span data-bs-toggle="collapse" data-bs-target="#tel_collapse_<?php echo $res->uuid ?>" aria-expanded="true" style="cursor: pointer;" class="text-primary">More&nbsp;<i class="bi bi-chevron-down"></i></span>
                                            <?php } ?>
                                        <?php } else {
                                                    echo '-';
                                                } ?>
                                        </div>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if (!empty($invoice->uuid)) { ?>
                                        <div id="tel_<?php echo $invoice->uuid ?>">
                                            <div class="" id="tel_heading_<?php echo $invoice->uuid ?>">
                                                <?php if (!empty($invoice->mtel)) { ?>
                                                    <?php if (count(explode(',', $invoice->mtel)) > 3) { ?>
                                                        <?php foreach (array_slice(explode(',', $invoice->mtel), 0, 3) as $key => $value) { ?>
                                                            <?php echo (($key < 2)) ? $value . ',' : $value; ?><?php } ?>
                                            </div>
                                            <div id="tel_collapse_<?php echo $invoice->uuid ?>" class="accordion-collapse collapse" aria-labelledby="tel_heading_<?php echo $invoice->uuid ?>" data-parent="#tel_<?php echo $invoice->uuid ?>">
                                                <?php $count = count(array_slice(explode(',', $invoice->mtel), 3)); ?>
                                                <?php $i = 0; ?>
                                                <?php foreach (array_slice(explode(',', $invoice->mtel), 3) as $key => $value) { ?>
                                                    <?php echo ((++$i == $count)) ? $value : $value . ','; ?>
                                                <?php } ?>
                                            </div>
                                        <?php } else { ?>
                                            <?php if (count(explode(',', $invoice->mtel)) > 1) { ?>
                                                <?php foreach (array_slice(explode(',', $invoice->mtel), 0, 2) as $key => $value) { ?>
                                                    <?php echo (($key == 1)) ? $value : $value . ','; ?><?php } ?>
                                                <?php } else {
                                                            echo $invoice->mtel;
                                                        } ?>
                                            <?php } ?>
                                            <?php if (count(explode(',', $invoice->mtel)) > 3) { ?>
                                                &nbsp;&nbsp;<span data-bs-toggle="collapse" data-bs-target="#tel_collapse_<?php echo $res->uuid ?>" aria-expanded="true" style="cursor: pointer;" class="text-primary">More&nbsp;<i class="bi bi-chevron-down"></i></span>
                                            <?php } ?>
                                        <?php } else {
                                                    echo '-';
                                                } ?>
                                        </div>
                                    <?php } ?>
                                </td>
                                <td><?php echo !empty($invoice->is_call) ? '<i class="bi bi-check-circle text-success"></i>' : '<i class="bi bi-x-circle text-danger"></i>'; ?></td>
                                <td><?php echo !empty($invoice->mcontact) ? $invoice->mcontact : '-'; ?></td>
                                <td><?php echo !empty($invoice->receive_call) ? $invoice->receive_call : '-'; ?></td>
                                <td>
                                    <a class="btn btn-sm btn-success modalStatus" type="button" data-bs-toggle="modal" data-bs-target="#modal_status" data-uuid="<?php echo $invoice->uuid ?>">
                                        <i class="bi bi-file-earmark"></i>
                                    </a>
                                </td>
                                <td class="text-center">
                                    <a class="btn btn-sm btn-gray-700 modalAction" type="button" data-bs-toggle="modal" data-bs-target="#modal_action" data-uuid="<?php echo $invoice->uuid ?>"><i class="bi bi-pencil"></i></a>
                                    <a class="btn btn-sm btn-danger" href="/report/pdf/<?php echo $invoice->uuid; ?>" target="_blank" id="report"><i class="bi bi-file-earmark-pdf"></i></a>
                                    <a class="btn btn-sm btn-primary" href="/report/pdf/<?php echo $invoice->uuid; ?>" target="_blank" id="report"><i class="bi bi-envelope"></i></a>
                                </td>
                            </tr>
                    <?php }
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_status" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex">
                    <h5 class="modal-title text-dark header_text me-3" id="exampleModalLabel">สถานะ</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="status" class="table table-centered table-striped w-100">
                        <thead class="thead-light">
                            <tr>
                                <th width="20%">วันสร้างบิล</th>
                                <th width="25%">ลูกกค้า</th>
                                <th width="25%">ผู้สร้างบิล</th>
                                <th width="10%">สถานะ</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modalUpdate" id="modal_action" tabindex="-1" aria-labelledby="actionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex">
                    <h5 class="modal-title text-dark header_text me-3" id="exampleModalLabel">การโทรแจ้ง</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="updateAction" id="updateAction">
                <div class="modal-body">
                    <div class="form-check">
                        <input class="form-check-input uuid" type="hidden" id="uuid" name="uuid">
                        <input class="form-check-input is_call" type="checkbox" id="is_call" name="is_call">
                        <label class="form-check-label" for="is_call">
                            โทรแจ้ง
                        </label>
                    </div>
                    <div class="mb-3 row">
                        <label for="receive_call" class="col-sm-2 col-form-label">ผู้รับสาย</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control receive_call" id="receive_call" name="receive_call" required autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary loading" type="button" disabled style="display: none;">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        โหลด...
                    </button>
                    <button type="button" class="btn btn-primary submit-action">ยืนยัน</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(function() {
        var lists = <?php echo !empty($lists) ? json_encode($lists) : '[]'; ?>;
        let formUpdate = $('.updateAction').parsley();

        $('#created_date').datepicker({
            todayHighlight: true,
            format: 'yyyy-mm-dd',
            autoclose: true,
        });

        $('#customer').select2({
            theme: "bootstrap-5"
        });

        $('#bill_no').select2({
            theme: "bootstrap-5"
        });

        $('#reportList')
            .DataTable({
                "scrollX": false,
                "lengthChange": false,
                "pageLength": 20,
                "order": [
                    [0, "desc"]
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
            }).on('click', '.modalStatus', function(e) {
                e.preventDefault();
                let id = $(this).attr("data-uuid")
                let created_by = lists[id].created_by ? lists[id].created_by : '-'
                let is_bill = lists[id].is_bill_email == 1 ? 'ส่งรายงานเรียบร้อยแล้ว' : 'ยังไม่ส่งรายงาน'
                let is_receive_bill = lists[id].is_receive_bill == 1 ? 'ได้รับแล้ว' : 'ยังไม่ได้รับ'
                let html = '<tr>' +
                    '<td>' + lists[id].created_date + '</td>' +
                    '<td>' + lists[id].mcustname + '(' + lists[id].cus_no + ')' + '</td>' +
                    '<td class="text-success">' + created_by + '</td>' +
                    '<td>' + is_bill + '</td>' +
                    '</tr>'
                let html2 =
                    '<tr>' +
                    '<td>' + lists[id].created_date + '</td>' +
                    '<td>' + lists[id].mcustname + '(' + lists[id].cus_no + ')' + '</td>' +
                    '<td>' + created_by + '</td>' +
                    '<td class="text-success">' + is_bill + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td>' + lists[id].created_date + '</td>' +
                    '<td>' + lists[id].mcustname + '(' + lists[id].cus_no + ')' + '</td>' +
                    '<td>' + created_by + '</td>' +
                    '<td class="text-success">' + is_receive_bill + '</td>' +
                    '</tr>'
                let genHtml = is_receive_bill == 1 ? html2 : html
                $('#status tbody').html(genHtml)
            }).on('click', '.modalAction', function(e) {
                e.preventDefault();
                let id = $(this).attr("data-uuid")
                $('.uuid').val(id)
                let is_call = lists[id].is_call ? true : false
                let receive_call = lists[id].receive_call ? lists[id].receive_call : ''
                $('.uuid').val(id)
                $('.is_call').prop('checked', is_call)
                $('.receive_call').val(receive_call)


            });
        $('.dataTables_filter label').hide();

        $('.updateAction').on('click', '.submit-action', function(e) {
            e.preventDefault();
            readyProcess(true);
            if (formUpdate.validate() === true) {
                let formData = $('.updateAction').serializeArray();
                $.post("/report/update", formData).done(function(res) {
                    $('.modalUpdate').modal('hide')
                    if (res.status === 200) {

                        Swal.fire({
                            icon: 'success',
                            text: 'อัปเดตข้อมูลเรียบร้อย',
                            confirmButtonText: 'ตกลง'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        })
                        readyProcess();
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
                    readyProcess();
                });
            }
        });


        $('#status')
            .DataTable({
                "scrollX": false,
                "lengthChange": false,
                "pageLength": 20,
                "order": [
                    [0, "desc"]
                ],
                "columnDefs": [{
                        "targets": [1, 2, 3],
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
            });
        $('.dataTables_filter label').hide();

        function readyProcess(wait = false) {
            if (wait) {
                $('.submit-action').hide();
                $('.loading').show();
            } else {
                $('.submit-action').show();
                $('.loading').hide();
            }
        }
    });
</script>