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
                        foreach ($lists as $key => $invoice) { ?>
                            <tr>
                                <td><?php echo $invoice->bill_no; ?></td>
                                <td><?php echo !empty($invoice->mcustname) ? $invoice->mcustname . ' (' . $invoice->cus_no . ')' : '-'; ?>
                                </td>
                                <td>
                                    <?php if (!empty($invoice->uuid)) { ?>
                                        <div id="email_<?php echo $invoice->uuid ?>">
                                            <div class="" id="email_heading_<?php echo $invoice->uuid ?>">
                                                <?php if (!empty($emails[$invoice->uuid])) { ?>
                                                    <?php if (count($emails[$invoice->uuid]) > 3) { ?>
                                                        <?php foreach (array_slice($emails[$invoice->uuid], 0, 3) as $key => $value) { ?>
                                                            <?php echo (($key < 2)) ? $value->email . ',' : $value->email; ?><?php } ?>
                                            </div>
                                            <div id="email_collapse_<?php echo $invoice->uuid ?>" class="accordion-collapse collapse" aria-labelledby="email_heading_<?php echo $invoice->uuid ?>" data-parent="#email_<?php echo $invoice->uuid ?>">
                                                <?php $count = count(array_slice($emails[$invoice->uuid], 3)); ?>
                                                <?php $i = 0; ?>
                                                <?php foreach (array_slice($emails[$invoice->uuid], 3) as $key => $value) { ?>
                                                    <?php echo ((++$i == $count)) ? $value->email : $value->email . ','; ?>
                                                <?php } ?>
                                            </div>
                                        <?php } else { ?>
                                            <?php if (count($emails[$invoice->uuid]) > 1) { ?>
                                                <?php foreach (array_slice($emails[$invoice->uuid], 0, 3) as $key => $value) { ?>
                                                    <?php echo (($key == 2)) ? $value->email  : $value->email . ','; ?><?php } ?>
                                                <?php } else {
                                                            echo $emails[$invoice->uuid]->email;
                                                        } ?>
                                            <?php } ?>
                                            <?php if (count($emails[$invoice->uuid]) > 3) { ?>
                                                &nbsp;&nbsp;<span data-bs-toggle="collapse" data-bs-target="#email_collapse_<?php echo $invoice->uuid ?>" aria-expanded="true" style="cursor: pointer;" class="text-primary">More&nbsp;<i class="bi bi-chevron-down"></i></span>
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
                                                <?php if (!empty($tels[$invoice->uuid])) { ?>
                                                    <?php if (count($tels[$invoice->uuid]) > 3) { ?>
                                                        <?php foreach (array_slice($tels[$invoice->uuid], 0, 3) as $key => $value) { ?>
                                                            <?php echo (($key < 2)) ? $value->tel . ',' : $value->tel; ?><?php } ?>
                                            </div>
                                            <div id="tel_collapse_<?php echo $invoice->uuid ?>" class="accordion-collapse collapse" aria-labelledby="tel_heading_<?php echo $invoice->uuid ?>" data-parent="#tel_<?php echo $invoice->uuid ?>">
                                                <?php $count = count(array_slice($tels[$invoice->uuid], 3)); ?>
                                                <?php $i = 0; ?>
                                                <?php foreach (array_slice($tels[$invoice->uuid], 3) as $key => $value) { ?>
                                                    <?php echo ((++$i == $count)) ? $value->tel : $value->tel . ','; ?>
                                                <?php } ?>
                                            </div>
                                        <?php } else { ?>
                                            <?php if (count($tels[$invoice->uuid]) > 1) { ?>
                                                <?php foreach (array_slice($tels[$invoice->uuid], 0, 3) as $key => $value) { ?>
                                                    <?php echo (($key == 2)) ? $value->tel : $value->tel . ','; ?><?php } ?>
                                                <?php } else {
                                                            echo $tels[$invoice->uuid]->tel;
                                                        } ?>
                                            <?php } ?>
                                            <?php if (count($tels[$invoice->uuid]) > 3) { ?>
                                                &nbsp;&nbsp;<span data-bs-toggle="collapse" data-bs-target="#tel_collapse_<?php echo $invoice->uuid ?>" aria-expanded="true" style="cursor: pointer;" class="text-primary">More&nbsp;<i class="bi bi-chevron-down"></i></span>
                                            <?php } ?>
                                        <?php } else {
                                                    echo '-';
                                                } ?>
                                        </div>
                                    <?php } ?>
                                </td>
                                <td><?php echo !empty($is_call[$invoice->uuid]) ? '<i class="bi bi-check-circle text-success"></i>' : '<i class="bi bi-x-circle text-danger"></i>'; ?></td>
                                <td>
                                    <?php if (!empty($invoice->uuid)) { ?>
                                        <div id="contact_<?php echo $invoice->uuid ?>">
                                            <div class="" id="contact_heading_<?php echo $invoice->uuid ?>">
                                                <?php if (!empty($tels[$invoice->uuid])) { ?>
                                                    <?php if (count($tels[$invoice->uuid]) > 3) { ?>
                                                        <?php foreach (array_slice($tels[$invoice->uuid], 0, 3) as $key => $value) { ?>
                                                            <?php echo (($key < 2)) ? $value->contact . ',' : $value->contact; ?><?php } ?>
                                            </div>
                                            <div id="contact_collapse_<?php echo $invoice->uuid ?>" class="accordion-collapse collapse" aria-labelledby="contact_heading_<?php echo $invoice->uuid ?>" data-parent="#contact_<?php echo $invoice->uuid ?>">
                                                <?php $count = count(array_slice($tels[$invoice->uuid], 3)); ?>
                                                <?php $i = 0; ?>
                                                <?php foreach (array_slice($tels[$invoice->uuid], 3) as $key => $value) { ?>
                                                    <?php echo ((++$i == $count)) ? $value->contact : $value->contact . ','; ?>
                                                <?php } ?>
                                            </div>
                                        <?php } else { ?>
                                            <?php if (count($tels[$invoice->uuid]) > 1) { ?>
                                                <?php foreach (array_slice($tels[$invoice->uuid], 0, 2) as $key => $value) { ?>
                                                    <?php echo (($key == 1)) ? $value->contact : $value->contact . ','; ?><?php } ?>
                                                <?php } else {
                                                            echo !empty($tels[$invoice->uuid]->contact) ? $tels[$invoice->uuid]->contact : '-';
                                                        } ?>
                                            <?php } ?>
                                            <?php if (count($tels[$invoice->uuid]) > 3) { ?>
                                                &nbsp;&nbsp;<span data-bs-toggle="collapse" data-bs-target="#contact_collapse_<?php echo $invoice->uuid ?>" aria-expanded="true" style="cursor: pointer;" class="text-primary">More&nbsp;<i class="bi bi-chevron-down"></i></span>
                                            <?php } ?>
                                        <?php } else {
                                                    echo '-';
                                                } ?>
                                        </div>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if (!empty($invoice->uuid)) { ?>
                                        <div id="receives_<?php echo $invoice->uuid ?>">
                                            <div class="" id="receives_heading_<?php echo $invoice->uuid ?>">
                                                <?php if (!empty($receives[$invoice->uuid])) { ?>
                                                    <?php if (count($receives[$invoice->uuid]) > 3) { ?>
                                                        <?php foreach (array_slice($receives[$invoice->uuid], 0, 3) as $key => $value) { ?>
                                                            <?php echo (($key < 2)) ? $value->receive_call . ',' : $value->receive_call; ?><?php
                                                                                                                                        } ?>
                                            </div>
                                            <div id="receives_collapse_<?php echo $invoice->uuid ?>" class="accordion-collapse collapse" aria-labelledby="receives_heading_<?php echo $invoice->uuid ?>" data-parent="#receives_<?php echo $invoice->uuid ?>">
                                                <?php $count = count(array_slice($receives[$invoice->uuid], 3)); ?>
                                                <?php $i = 0; ?>
                                                <?php foreach (array_slice($receives[$invoice->uuid], 3) as $key => $value) { ?>
                                                    <?php echo ((++$i == $count)) ? $value->receive_call : $value->receive_call . ','; ?>
                                                <?php } ?>
                                            </div>
                                        <?php } else { ?>
                                            <?php if (count($receives[$invoice->uuid]) > 1) { ?>
                                                <?php foreach (array_slice($receives[$invoice->uuid], 0, 2) as $key => $value) { ?>
                                                    <?php echo (($key == 1)) ? $value->receive_call : $value->receive_call . ','; ?><?php } ?>
                                                <?php } else {
                                                            echo !empty($receives[$invoice->uuid]->receive_call) ? $receives[$invoice->uuid]->receive_call : '-';
                                                        } ?>
                                            <?php } ?>
                                            <?php if (count($receives[$invoice->uuid]) > 3) { ?>
                                                &nbsp;&nbsp;<span data-bs-toggle="collapse" data-bs-target="#receives_collapse_<?php echo $invoice->uuid ?>" aria-expanded="true" style="cursor: pointer;" class="text-primary">More&nbsp;<i class="bi bi-chevron-down"></i></span>
                                            <?php } ?>
                                        <?php } else {
                                                    echo '-';
                                                } ?>
                                        </div>
                                    <?php } ?>
                                </td>
                                <td>
                                    <a class="btn btn-sm btn-success modalStatus" type="button" data-bs-toggle="modal" data-bs-target="#modal_status" data-uuid="<?php echo $invoice->uuid ?>">
                                        <i class="bi bi-file-earmark"></i>
                                    </a>
                                </td>
                                <td class="text-center">
                                    <a class="btn btn-sm btn-gray-700 modalAction" type="button" data-bs-toggle="modal" data-bs-target="#modal_action" data-uuid="<?php echo $invoice->uuid ?>"><i class="bi bi-pencil"></i></a>
                                    <a class="btn btn-sm btn-danger" href="/report/pdf/<?php echo $invoice->uuid; ?>" target="_blank" id="report"><i class="bi bi-file-earmark-pdf"></i></a>
                                    <a class="btn btn-sm btn-primary email" type="button" href="javascript:void(0);" id="email" data-uuid="<?php echo $invoice->uuid ?>" data-cus_no="<?php echo $invoice->cus_no; ?>" data-cus_main="<?php echo $invoice->cus_main; ?>" data-end_date="<?php echo $invoice->end_date; ?>" data-bill_no="<?php echo $invoice->bill_no; ?>">
                                        <i class="bi bi-envelope"></i></a>
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
            <div class="modal-body status">
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
                <div class="modal-body cfCall cf_call_list">
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
        var tels = <?php echo !empty($tels) ? json_encode($tels) : '[]'; ?>;
        var cf_call = <?php echo !empty($cf_call) ? json_encode($cf_call) : '[]'; ?>;

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
                let is_bill = lists[id].is_email == 1 ? 'ส่งรายงานเรียบร้อยแล้ว' : 'ยังไม่ส่งรายงาน'
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
                let html = '';
                if (tels[id].length > 2 && findObjectIsCall(tels[id]) == true) {
                    if (cf_call[id] && cf_call[id] != 'undefined') {
                        tels[id].map((o, i) => {
                            let receive_call = cf_call[id][o.tel] && cf_call[id][o.tel] != 'undefined' ? cf_call[id][o.tel].receive_call : ''
                            let cf_call_uuid = cf_call[id][o.tel] && cf_call[id][o.tel] != 'undefined' ? cf_call[id][o.tel].uuid : ''
                            let check = cf_call[id][o.tel] && cf_call[id][o.tel] != 'undefined' ? cf_call[id][o.tel].cf_call ? 'checked' : '' : ''
                            let lastIndex = tels[id].length - 1 == i ? 'mb-2' : 'border-bottom mb-2'
                            if (o.is_call == 1) {
                                html += '<div class="mb-3"><p class="tel_no"><span class="me-2">เบอร์โทร :</span><span>' + o.tel + '<span></p><div class="form-check"><input class="form-check-input" type="hidden" id="uuid" name="uuid[]" value="' + cf_call_uuid + '">' +
                                    '<input class="form-check-input" type="hidden" id="tel" name="tel[]" value="' + o.tel + '">' +
                                    '<input class="form-check-input" type="hidden" id="report_uuid" name="report_uuid[]" value="' + id + '">' +
                                    '<input class="form-check-input" type="hidden" id="cus_main" name="cus_main[]" value="' + lists[id].cus_no + '">' +
                                    '<div class="form-check form-switch"><input class="form-check-input" type="checkbox" ' + check + ' id="cf_call" name="cf_call[]" value="' + o.tel + '">' + '<label class="form-check-label" for="cf_call">โทรแจ้ง</label></div></div>' +
                                    '<div class="mb-3 row"><label for="receive_call" class="col-sm-2 col-form-label">ผู้รับสาย</label><div class="col-sm-10">' +
                                    '<input type="text" class="form-control receive_call" id="receive_call" value="' + receive_call + '" name="receive_call[]" autocomplete="off"></div></div><div class="' + lastIndex + '"></div></div>';
                            }
                        });
                    } else {
                        tels[id].map((o, i) => {
                            let lastIndex = tels[id].length - 1 == i ? 'mb-2' : 'border-bottom mb-2'
                            if (o.is_call == 1) {
                                html += '<div class="mb-3"><p class="tel_no"><span class="me-2">เบอร์โทร :</span><span>' + o.tel + '<span></p><div class="form-check">' +
                                    '<input class="form-check-input" type="hidden" id="tel" name="tel[]" value="' + o.tel + '">' +
                                    '<input class="form-check-input" type="hidden" id="report_uuid" name="report_uuid[]" value="' + id + '">' +
                                    '<input class="form-check-input" type="hidden" id="cus_main" name="cus_main[]" value="' + lists[id].cus_no + '">' +
                                    '<div class="form-check form-switch"><input class="form-check-input" type="checkbox" id="cf_call" name="cf_call[]" value="' + o.tel + '">' + '<label class="form-check-label" for="cf_call">โทรแจ้ง</label></div></div>' +
                                    '<div class="mb-3 row">' +
                                    '<label for="receive_call" class="col-sm-2 col-form-label">ผู้รับสาย</label><div class="col-sm-10">' +
                                    '<input type="text" class="form-control receive_call" id="receive_call" name="receive_call[]" autocomplete="off"></div></div> <div class="' + lastIndex + '">' +
                                    '</div></div>';
                            }
                        });
                    }
                }

                if (tels[id].length > 2 && findObjectIsCall(tels[id]) == true) {
                    $('.cfCall').html(html)
                } else {
                    $('.cfCall').html('<p class="text-center mt-3">ไม่พบข้อมูล</p>')
                }

            }).on('click', '.email', function(e) {
                e.preventDefault();
                let uuid = $(this).attr("data-uuid")
                let cus_no = $(this).attr("data-cus_no")
                let cus_main = $(this).attr("data-cus_main")
                let endDate = $(this).attr("data-end_date")
                let bill_no = $(this).attr("data-bill_no")
                console.log(uuid)
                let formData = [{
                        name: 'cus_no',
                        value: cus_no
                    },
                    {
                        name: 'cus_main',
                        value: cus_main
                    },
                    {
                        name: 'uuid',
                        value: uuid
                    },
                    {
                        name: 'end_date',
                        value: endDate
                    },
                    {
                        name: 'bill_no',
                        value: bill_no
                    },
                ]
                $.post('/report/email', formData).done(function(res) {
                    if (res.status == 200) {
                        console.log(res)
                        Swal.fire({
                            icon: 'success',
                            text: 'ส่งอีเมลเรียบร้อยแล้ว',
                            confirmButtonText: 'ตกลง'
                        });

                    } else if (res.status == 204) {
                        Swal.fire({
                            title: 'ไม่พบข้อมูลอีเมล?',
                            icon: 'warning',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'ยืนยัน',
                        });
                    } else {
                        if (res.error) {
                            Swal.fire("Error", res.error, "error");
                        } else {
                            Swal.fire("Error", 'Something went wrong', "error");
                        }
                    }
                });
            });
        $('.dataTables_filter label').hide();

        $('.updateAction').on('click', '.submit-action', function(e) {
            e.preventDefault();
            readyProcess(true);
            if (formUpdate.validate() === true) {
                let formData = $('.updateAction').serializeArray();
                console.log(formData)
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


        function findObjectIsCall(data) {
            for (let element of data) {
                if (element.is_call == 1) {
                    return true;
                    break;
                }
            }
            return false;
        }
    });
</script>