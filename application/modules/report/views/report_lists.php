<div class="container-fluid">
    <div class="bg-white rounded shadow rounded d-flex flex-column px-5 pt-3 pb-3">
        <form id="searchForm" method="post" action="<?php echo $http ?>/report" class="mb-4">
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
                        <label for="bill_no" class="form-label">เลขที่เอกสาร</label>
                        <div class="input-group mb-3">
                            <select class="select2 form-select" name="bill_no" id="bill_no">
                                <option value="">เลือก ...</option>
                                <?php foreach ($billNos as $billNo) { ?>
                                    <option value="<?php echo $billNo->bill_no; ?>" <?php echo $bill_no == $billNo->bill_no ? 'selected' : '' ?>>
                                        <?php echo $billNo->bill_no; ?></option>
                                <?php  } ?>
                            </select>
                        </div>
                    </div>
                    <div class="input-search-2">
                        <label for="customer" class="form-label">ลูกค้า</label>
                        <div class="input-group mb-3 report-select2">
                            <select class="select2 form-select customer" name="customer[]" id="customer" multiple>
                                <option value="all" class="all">เลือกทั้งหมด</option>
                                <?php foreach ($customers as $k => $customer) { ?>
                                    <option value="<?php echo $customer->cus_no; ?>">
                                        <?php echo $customer->cus_name . ' (' . $customer->cus_no . ')' ?></option>
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
                        <?php foreach ($table as $res) { ?>
                            <?php if (!in_array($info->cus_no, $fullSearch) && !in_array($res->sort, [5, 6, 7, 8])) { ?>
                                <th width="<?php if (in_array($res->sort, [2, 3, 4])) {
                                                echo '20%';
                                            } else if (in_array($res->sort, [1, 9])) {
                                                echo '10%';
                                            }; ?>" class="no-search no-sort <?php echo $res->sort == 9 ? 'text-center' : '' ?>">
                                    <?php echo $res->colunm; ?>
                                </th>
                            <?php } else  if (in_array($info->cus_no, $fullSearch)) { ?>
                                <th width="<?php if (in_array($res->sort, [2, 3, 4, 9, 6])) {
                                                echo '10%';
                                            } else if (in_array($res->sort, [1, 7])) {
                                                echo '10%';
                                            } else if (in_array($res->sort, [5, 8])) {
                                                echo '5%';
                                            }; ?>" class="no-search no-sort <?php echo $res->sort == 9 ? 'text-center' : '' ?>">
                                    <?php echo $res->colunm; ?>
                                </th>
                            <?php } ?>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
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
                    <h5 class="modal-title text-muted header_text me-3" id="exampleModalLabel"></h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body status">
                <div class="table-responsive">
                    <table id="status" class="table table-centered table-striped w-100">
                        <thead class="thead-light">
                            <tr>
                                <th width="20%">วันที่สร้างบิล</th>
                                <th width="25%">ผู้สร้างบิล</th>
                                <th width="20%">สถานะ</th>
                                <th width="20%">วันที่อัปเดต</th>
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
                    <h5 class="modal-title text-muted me-3" id="call">การโทรแจ้ง</h5>
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
        var reportTable = false;
        let formUpdate = $('.updateAction').parsley();
        var table = <?php echo !empty($table) ? json_encode($table) : '[]'; ?>;
        var info = <?php echo !empty($info) ? json_encode($info) : '{}'; ?>;
        var search = <?php echo !empty($fullSearch) ? json_encode($fullSearch) : '[]'; ?>;
        var columns = [];

        if (table) {
            genTable();
        }

        $('#created_date').datepicker({
            todayHighlight: true,
            format: 'yyyy-mm-dd',
            autoclose: true,
        });

        $('#customer').select2({
            theme: "bootstrap-5",
            placeholder: "เลือกลูกค้า",
            closeOnSelect: false,
        });

        $('#bill_no').select2({
            theme: "bootstrap-5"
        });

        $('#customer').on("select2:unselecting", function(e) {
            let me = $(e.target)
            let label = me.parents().find('.select2-results__options')
            let all = label.find('.select2-results__option--highlighted').attr("data-select2-id").search('-all')
            let check = label.find('.select2-results__option--highlighted').attr("aria-selected")
            if (all > 0) {
                $("#customer > option").prop('selected', false).end()
            } else {
                me.find('option').each(function(index, ele) {
                    if ($(ele).hasClass('all')) {
                        $(ele).prop('selected', false)
                    }
                })
            }
        });


        $('#customer').on("select2:selecting", function(e) {
            let me = $(e.target)
            let label = me.parents().find('.select2-results__options')
            let all = label.find('.select2-results__option--highlighted').attr("data-select2-id").search('-all')
            let check = label.find('.select2-results__option--highlighted').attr("aria-selected")
            console.log(label.find('.select2-results__option--highlighted').attr("data-select2-id"))
            if (all > 0) {
                $("#customer > option").prop('selected', true).end()
            }
        });

        reportTable = $('#reportList')
            .DataTable({
                "scrollX": false,
                "lengthChange": false,
                "processing": true,
                "serverSide": true,
                "pageLength": 20,
                "order": [
                    [0, "asc"]
                ],
                "ajax": {
                    url: "<?php echo $http ?>/report/listReport",
                    'data': {
                        cus_no: '<?php echo $cus_no ?>',
                        created_date: '<?php echo $created_date ?>',
                        bill_no: '<?php echo $bill_no ?>',
                    },
                    dataFilter: function(data) {
                        let json = jQuery.parseJSON(data);
                        if (json.error) {
                            Swal.fire({
                                title: 'System not available',
                                html: json.error.remark,
                                type: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: "#3085d6",
                                cancelButtonColor: "#d33",
                                confirmButtonText: 'Try Again',
                                confirmButtonClass: 'mr-3'
                            }).then(function(result) {
                                if (result.value) {
                                    window.location.reload(true);
                                }
                            });
                            return JSON.stringify({
                                "draw": 1,
                                "recordsTotal": 0,
                                "recordsFiltered": 0,
                                "data": []
                            });
                        }
                        return JSON.stringify(json);
                    }
                },
                "columns": columns,
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
                ],
            }).on('click', '.modalStatus', function(e) {
                e.preventDefault();
                let id = $(this).attr("data-uuid")
                let cus_no = $(this).attr("data-cus_no")
                let cus_name = $(this).attr("data-cus_name")
                $('.header_text').text('สถานะ : ' + cus_name + ' (' + cus_no + ')');
                console.log(id)
                $.get('<?php echo $http ?>/report/genBill/' + id).done(function(res) {
                    console.log(res)
                    if (res.status == 200) {
                        if (res.data) {
                            let created_by = res.data.created_by ? res.data.created_by : '-'
                            let is_bill = res.data.is_email == 1 ? 'ส่งรายงานเรียบร้อยแล้ว' : 'ยังไม่ส่งรายงาน'
                            let is_receive_bill = res.data.is_receive_bill == 1 ? 'ได้รับแล้ว' : 'ยังไม่ได้รับ'
                            let html = '<tr>' +
                                '<td>' + res.data.created_date + '</td>' +
                                '<td>' + created_by + '</td>' +
                                '<td>' + is_bill + '</td>' +
                                '<td>' + res.data.updated_date + '</td>' +
                                '</tr>'

                            if (res.data.is_receive_bill == 1) {
                                html += '<tr>' +
                                    '<td>' + res.data.created_date + '</td>' +
                                    '<td>' + created_by + '</td>' +
                                    '<td>' + is_receive_bill + '</td>' +
                                    '<td>' + res.data.updated_date + '</td>' +
                                    '</tr>'
                            }

                            $('#status tbody').html(html)
                        }
                    } else if (res.status == 204) {
                        $('#status tbody').html('')
                    }
                });
            }).on('click', '.modalAction', function(e) {
                e.preventDefault();
                let id = $(this).attr("data-uuid")
                let is_check = $(this).attr("data-is_receive_bill") == 1 ? 'checked' : '';
                let cus_no = $(this).attr("data-cus_no")
                let cus_main = $(this).attr("data-cus_main")
                $('.cfCall').html('<div class="d-flex justify-content-center"><div class="spinner-border text-primary text-center mt-4 mb-3" role="status"><span class="visually-hidden">Loading...</span></div></div>')
                let html = '';
                let recive = '<div class="form-check"><input class="form-check-input is_receive_bill" type="checkbox" id="is_receive_bill" name="is_receive_bill" value="' + id + '" autocomplete="off"  ' + is_check + '><label class="form-check-label" for="is_receive_bill">ได้รับเอกสาร</label></div><div class="border-bottom mb-3"></div>';

                $.get('<?php echo $http ?>/report/genCfCall/' + id + '/' + cus_no).done(function(res) {
                    if (res.status == 200) {
                        if (res.data.tels.length > 0 && findObjectIsCall(res.data.tels) == true) {
                            if (res.data.cf_call) {
                                res.data.tels.map((o, i) => {
                                    let receive_call = res.data.cf_call[o.tel] && res.data.cf_call[o.tel] != 'undefined' ? res.data.cf_call[o.tel].receive_call : ''
                                    let cf_call_uuid = res.data.cf_call[o.tel] && res.data.cf_call[o.tel] != 'undefined' ? res.data.cf_call[o.tel].uuid : ''
                                    let check = res.data.cf_call[o.tel] && res.data.cf_call[o.tel] != 'undefined' ? res.data.cf_call[o.tel].cf_call ? 'checked' : '' : ''
                                    let lastIndex = res.data.tels.length - 1 == i ? 'mb-2' : 'border-bottom mb-2'
                                    if (o.is_call == 1) {
                                        html += '<div class="mb-3"><p class="tel_no"><span class="me-2">เบอร์โทร :</span><span>' + o.tel + '<span></p><div class="form-check"><input class="form-check-input" type="hidden" id="uuid" name="uuid[]" value="' + cf_call_uuid + '">' +
                                            '<input class="form-check-input" type="hidden" autocomplete="off" id="tel" name="tel[]" value="' + o.tel + '">' +
                                            '<input class="form-check-input" type="hidden" autocomplete="off" id="report_uuid" name="report_uuid[]" value="' + id + '">' +
                                            '<input class="form-check-input" type="hidden" autocomplete="off" id="cus_main" name="cus_main[]" value="' + cus_no + '">' +
                                            '<div class="form-check form-switch"><input class="form-check-input" type="checkbox" ' + check + ' id="cf_call" name="cf_call[]" value="' + o.tel + '">' + '<label class="form-check-label" for="cf_call">โทรแจ้ง</label></div></div>' +
                                            '<div class="mb-3 row"><label for="receive_call" class="col-sm-2 col-form-label">ผู้รับสาย</label><div class="col-sm-10">' +
                                            '<input type="text" class="form-control receive_call" id="receive_call" value="' + receive_call + '" name="receive_call[]" autocomplete="off"></div></div><div class="' + lastIndex + '"></div></div>';
                                    }
                                });
                            } else {
                                res.data.tels.map((o, i) => {
                                    let lastIndex = res.data.tels.length - 1 == i ? 'mb-2' : 'border-bottom mb-2'
                                    if (o.is_call == 1) {
                                        html += '<div class="mb-3"><p class="tel_no"><span class="me-2">เบอร์โทร :</span><span>' + o.tel + '<span></p><div class="form-check">' +
                                            '<input class="form-check-input" type="hidden" autocomplete="off" id="tel" name="tel[]" value="' + o.tel + '">' +
                                            '<input class="form-check-input" type="hidden" autocomplete="off" id="report_uuid" name="report_uuid[]" value="' + id + '">' +
                                            '<input class="form-check-input" type="hidden" autocomplete="off" id="cus_main" name="cus_main[]" value="' + cus_no + '">' +
                                            '<div class="form-check form-switch"><input class="form-check-input" type="checkbox" id="cf_call" name="cf_call[]" value="' + o.tel + '">' + '<label class="form-check-label" for="cf_call">โทรแจ้ง</label></div></div>' +
                                            '<div class="mb-3 row">' +
                                            '<label for="receive_call" class="col-sm-2 col-form-label">ผู้รับสาย</label><div class="col-sm-10">' +
                                            '<input type="text" class="form-control receive_call" id="receive_call" name="receive_call[]" autocomplete="off"></div></div> <div class="' + lastIndex + '">' +
                                            '</div></div>';
                                    }
                                });
                            }
                        }

                        if (res.data.tels.length > 0 && findObjectIsCall(res.data.tels) == true) {
                            $('.cfCall').html(recive + html)
                            $('.submit-action').prop('disabled', false)
                        } else {
                            $('.cfCall').html('<p class="text-center mt-3">ไม่พบข้อมูล</p>')
                            $('.submit-action').prop('disabled', true)
                        }

                    } else if (res.status == 204) {
                        $('.cfCall').html('<p class="text-center mt-3">ไม่พบข้อมูล</p>')
                        $('.submit-action').prop('disabled', true)
                    }
                });

            }).on('click', '.email', function(e) {
                e.preventDefault();
                let uuid = $(this).attr("data-uuid")
                let cus_no = $(this).attr("data-cus_no")
                let cus_main = $(this).attr("data-cus_main")
                let endDate = $(this).attr("data-end_date")
                let bill_no = $(this).attr("data-bill_no")
                let created_date = $(this).attr("data-created_date")
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
                    {
                        name: 'created_date',
                        value: created_date
                    },
                ]
                $.post('<?php echo $http ?>/report/email', formData).done(function(res) {
                    if (res.status == 200) {
                        console.log(res)
                        $.post('<?php echo $http ?>/api/addMainLog/update', {
                            page: 'ส่งอีเมล',
                            url: CURRENT_URL,
                            detail: JSON.stringify(formData),
                        });

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
                $.post("<?php echo $http ?>/report/update", formData).done(function(res) {
                    $('.modalUpdate').modal('hide')
                    if (res.status === 200) {

                        $.post('<?php echo $http ?>/api/addMainLog/update', {
                            page: 'การโทรแจ้ง',
                            url: CURRENT_URL,
                            detail: JSON.stringify(res.data),
                        });


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
                        "targets": [0, 1, 2],
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

        function genTable() {
            table.map(o => {
                if (o.sort == 1) {
                    columns.push({
                        data: 'bill_no',
                        render: function(data, type, full) {
                            return '<div class="tb-10">' + full.info.bill_no + '</div>';
                        }
                    })
                }
                if (o.sort == 2) {
                    columns.push({
                        data: 'cus_no',
                        render: function(data, type, full) {
                            return '<div class="tb-15">' + full.info.cus_name + '(' + full.info.cus_no + ')</div>';
                        }
                    })
                }
                if (o.sort == 3) {
                    columns.push({
                        data: 'email',
                        render: function(data, type, full) {
                            let count = full.emails.length > 0 ? full.emails.slice(3).length : 0
                            let _i = 0;
                            let move = full.emails.length > 3 ? '&nbsp;&nbsp;<span id="headingemail_' + full.info.cus_no + '" data-bs-toggle="collapse" data-bs-target="#collapseemail_' + full.info.cus_no + '" aria-expanded="true" style="cursor: pointer;" class="text-primary">More&nbsp;<i class="bi bi-chevron-down"></i></span>' : ''
                            let show3Top = full.emails.length > 0 ? full.emails.length > 3 ? full.emails.slice(0, 3).map((o, i) => i < 2 ? o.email + ' ' : o.email) : full.tels.length > 1 ? full.emails.slice(0, 3).map((x, j) => j == 1 ? x.email : x.email + ' ') : full.emails[0].email ? full.emails[0].email : '-' : ''
                            let moveShow = full.emails.slice(3).map((x, i) => _i++ == count ? x.email : x.email + ' ')

                            return full.emails.length > 0 ? '<div class="tb-15" id="email_' + full.info.cus_no + '">' +
                                '<div class="" id="email_heading_' + full.info.cus_no + '">' + show3Top + '</div>' +
                                '<div id="collapseemail_' + full.info.cus_no + '" class="accordion-collapse collapse" aria-labelledby="headingemail_' + full.info.cus_no + '" data-parent="#email_' + full.info.cus_no + '"">' + moveShow + '</div>' + move + '</div>' : '-';
                        }
                    })
                }
                if (o.sort == 4) {
                    columns.push({
                        data: 'tel',
                        render: function(data, type, full) {
                            let count = full.tels.length > 0 ? full.tels.slice(3).length : 0
                            let _i = 0;
                            let move = full.tels.length > 3 ? '&nbsp;&nbsp;<span id="headingtel_' + full.info.cus_no + '" data-bs-toggle="collapse" data-bs-target="#collapsetel_' + full.info.cus_no + '" aria-expanded="true" style="cursor: pointer;" class="text-primary">More&nbsp;<i class="bi bi-chevron-down"></i></span>' : ''
                            let show3Top = full.tels.length > 0 ? full.tels.length > 3 ? full.tels.slice(0, 3).map((o, i) => o.tel ? i < 2 ? o.tel + ' ' : o.tel : '') : full.tels.length > 1 ? full.tels.slice(0, 3).map((x, j) => x.tel ? j == 1 ? x.tel : x.tel + ' ' : '') : full.tels[0].tel ? full.tels[0].tel : '-' : ''


                            let moveShow = full.tels.slice(3).map((x, i) => x.tel ? _i++ == count ? x.tel : x.tel + ' ' : '')

                            return full.tels.length > 0 ? '<div class="tb-15" id="tel_' + full.info.cus_no + '">' +
                                '<div class="" id="tel_heading_' + full.info.cus_no + '">' + show3Top + '</div>' +
                                '<div id="collapsetel_' + full.info.cus_no + '" class="accordion-collapse collapse" aria-labelledby="headingtel_' + full.info.cus_no + '" data-parent="#tel_' + full.info.cus_no + '"">' + moveShow + '</div>' + move + '</div>' : '-';
                        }
                    })
                }
                if (o.sort == 5 && search.includes(info.cus_no)) {
                    columns.push({
                        data: 'is_call',
                        render: function(data, type, full) {
                            return findObjectIsCall(full.tels) ? '<i class="bi bi-check-circle text-success tb-5"></i>' : '<i class="bi bi-x-circle text-danger tb-5"></i>'
                        }
                    })
                }

                if (o.sort == 6 && search.includes(info.cus_no)) {
                    columns.push({
                        data: 'contact',
                        render: function(data, type, full) {
                            let count = full.tels.length > 0 ? full.tels.slice(3).length : 0
                            let _i = 0;
                            let move = full.tels.length > 3 ? '&nbsp;&nbsp;<span id="headingcontact_' + full.info.cus_no + '" data-bs-toggle="collapse" data-bs-target="#collapsecontact_' + full.info.cus_no + '" aria-expanded="true" style="cursor: pointer;" class="text-primary">More&nbsp;<i class="bi bi-chevron-down"></i></span>' : ''
                            let show3Top = full.tels.length > 0 ? full.tels.length > 3 ? full.tels.slice(0, 3).map((o, i) => o.contact ? i < 2 ? o.contact + ' ' : o.contact : ' ') : full.tels.length > 1 ? full.tels.slice(0, 3).map((x, j) => x.contact ? j == 1 ? x.contact : x.contact + ' ' : ' ') : full.tels[0].contact ? full.tels[0].contact : '-' : ''
                            let moveShow = full.tels.slice(3).map((x, i) => x.contact ? _i++ == count ? x.contact : x.contact + ' ' : '')

                            return full.tels.length > 0 ? '<div class="tb-10" id="contact_' + full.info.cus_no + '">' +
                                '<div class="" id="contact_heading_' + full.info.cus_no + '"> ' + show3Top + '</div>' +
                                '<div id="collapsecontact_' + full.info.cus_no + '" class="accordion-collapse collapse" aria-labelledby="headingcontact_' + full.info.cus_no + '" data-parent="#contact_' + full.info.cus_no + '"">' + moveShow + '</div>' + move + '</div>' : '-';
                        }
                    })
                }
                if (o.sort == 7 && search.includes(info.cus_no)) {
                    columns.push({
                        data: 'cf_call',
                        render: function(data, type, full) {
                            let count = full.cf_call.length > 0 ? full.cf_call.slice(3).length : 0
                            let _i = 0;
                            let move = full.cf_call.length > 3 ? '&nbsp;&nbsp;<span id="headingcall_' + full.info.cus_no + '" data-bs-toggle="collapse" data-bs-target="#collapsecall_' + full.info.cus_no + '" aria-expanded="true" style="cursor: pointer;" class="text-primary">More&nbsp;<i class="bi bi-chevron-down"></i></span>' : ''

                            let show3Top = full.cf_call.length > 0 ? full.cf_call.length > 3 ? full.cf_call.slice(0, 3).map((o, i) => i < 2 ? o.receive_call + ' ' : o.receive_call + ' ') : full.cf_call.length > 1 ? full.cf_call.slice(0, ).map((x, j) => j == 1 ? x.receive_call : x.receive_call + ' ') : full.cf_call[0].receive_call ? full.cf_call[0].receive_call + ' ' : '-' : ''

                            let moveShow = full.cf_call.slice(3).map((x, i) => _i++ == count ? x.receive_call : x.receive_call + ' ')

                            return full.cf_call.length > 0 ? '<div class="tb-10" id="call_' + full.info.cus_no + '">' +
                                '<div class="" id="call_heading_' + full.info.cus_no + '"> ' + show3Top + '</div>' +
                                '<div id="collapsecall_' + full.info.cus_no + '" class="accordion-collapse collapse" aria-labelledby="headingcall_' + full.info.cus_no + '" data-parent="#contact_' + full.info.cus_no + '"">' + moveShow + '</div>' + move + '</div>' : '-';
                        }
                    })
                }
                if (o.sort == 8 && search.includes(info.cus_no)) {
                    columns.push({
                        data: 'is_email',
                        render: function(data, type, full) {
                            return '<a class="btn btn-sm btn-success modalStatus tb-5" type="button" data-bs-toggle="modal" data-bs-target="#modal_status" data-uuid="' + full.info.uuid + '" data-cus_no="' + full.info.cus_no + '" data-cus_name="' + full.info.cus_name + '"><i class="bi bi-file-earmark"></i></a>'
                        }
                    })
                }
                if (o.sort == 9) {
                    columns.push({
                        data: 'uuid',
                        render: function(data, type, full) {
                            let action = search.includes(info.cus_no) ? '<a class="btn btn-sm btn-gray-700 modalAction" type="button" data-bs-toggle="modal" data-bs-target="#modal_action" data-cus_no="' + full.info.cus_no + '" data-uuid="' + full.info.uuid + '" data-cus_main="' + full.info.cus_main + '" data-is_receive_bill="' + full.info.is_receive_bill + '"><i class="bi bi-pencil"></i></a>' : ''
                            let mail = search.includes(info.cus_no) ? '<a class="btn btn-sm btn-primary email" type="button" href="javascript:void(0);" id="email" data-uuid="' + full.info.uuid + '" data-cus_no="' + full.info.cus_no + '" data-cus_main="' + full.info.cus_main + '" data-end_date="' + full.info.end_date + '" data-bill_no="' + full.info.bill_no + '" data-created_date="' + full.info.created_date + ' "><i class="bi bi-envelope"></i></a>' : ''

                            return '<div class="tb-15 d-flex justify-content-center">' +
                                action +
                                '<a class="btn btn-sm btn-danger" href="<?php echo $http ?>/report/pdf/' + full.info.uuid + '" target="_blank" id="report"><i class="bi bi-file-earmark-pdf"></i></a>' + mail + '</div>'
                        }
                    })
                }

            })
        }

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