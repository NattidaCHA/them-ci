<div class="container-fluid">
    <div class="bg-white rounded shadow rounded d-flex flex-column px-5 pt-3 pb-3">
        <form id="invoiceForm" method="get" action="/customer" class="mb-4">
            <div class="box-customer-search">
                <div class="input-search-2">
                    <label for="customer" class="form-label">ลูกค้า</label>
                    <div class="input-group mb-3">
                        <select class="select2 form-select" name="customer" id="customer">
                            <option value="" selected>เลือก ...</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3 mt-4 me-3">
                    <button type="submit" class="btn btn-primary btn-search">ค้นหา</button>
                </div>
            </div>
            <div class="d-flex justify-content-end mb-3 mt-4 me-3">
                <a type="button" class="btn btn-success" href="/customer/process/create">+ สร้างข้อมูลลูกค้า</a>
            </div>
        </form>
        <div class="table-responsive">
            <table id="customerLists" class="table table-centered table-striped w-100">
                <thead class="thead-light">
                    <tr>
                        <?php foreach ($table as $res) { ?>
                            <th width="<?php if (in_array($res->sort, [1])) {
                                            echo '21%';
                                        } else if (in_array($res->sort, [2])) {
                                            echo '20%';
                                        } else if (in_array($res->sort, [5])) {
                                            echo '5%';
                                        } else if (in_array($res->sort, [3, 4])) {
                                            echo '27%';
                                        }; ?>" class="no-search no-sort <?php echo $res->sort == 5 ? 'text-center' : '' ?>">
                                <?php echo $res->colunm; ?>
                            </th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>


<script>
    window.onload = function() {
        var table = <?php echo !empty($table) ? json_encode($table) : '[]'; ?>;
        var columns = [];

        if (table) {
            genTable();
        }

        $('.select2').select2({
            theme: "bootstrap-5",
            allowClear: false,
            placeholder: "พิมพ์ชื่อลูกค้าหรือรหัสลูกค้าบางส่วนเพื่อค้นหา",
            ajax: {
                url: "/api/searchCustomer",
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

        $('#customerLists')
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
                    url: '/customer/listCustomer/<?php echo $cus_no; ?>',
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
                ]
            });
        $('.dataTables_filter label').hide();


        function genTable() {
            table.map(o => {
                if (o.sort == 1) {
                    columns.push({
                        data: 'cus_no',
                        render: function(data, type, full) {
                            return full.info.cus_name + '(' + full.info.cus_no + ')';
                        }
                    })
                }
                if (o.sort == 2) {
                    columns.push({
                        data: 'contact',
                        render: function(data, type, full) {
                            let count = full.tels.length > 0 ? full.tels.slice(3).length : 0
                            let _i = 0;
                            let move = full.tels.length > 3 ? '&nbsp;&nbsp;<span id="headingcontact_' + full.info.cus_no + '" data-bs-toggle="collapse" data-bs-target="#collapsecontact_' + full.info.cus_no + '" aria-expanded="true" style="cursor: pointer;" class="text-primary">More&nbsp;<i class="bi bi-chevron-down"></i></span>' : ''
                            let show3Top = full.tels.length > 0 ? full.tels.length > 3 ? full.tels.slice(0, 3).map((o, i) => i < 2 ? o.contact + '' : o.contact) : full.tels.length > 1 ? full.tels.slice(0, 3).map((x, j) => j == 1 ? x.contact : x.contact + '') : full.tels[0].contact ? full.tels[0].contact : '-' : ''
                            let moveShow = full.tels.slice(3).map((x, i) => _i++ == count ? x.contact : x.contact + '')

                            return full.tels.length > 0 ? '<div id="contact_' + full.info.cus_no + '">' +
                                '<div class="" id="contact_heading_' + full.info.cus_no + '"> ' + show3Top + '</div>' +
                                '<div id="collapsecontact_' + full.info.cus_no + '" class="accordion-collapse collapse" aria-labelledby="headingcontact_' + full.info.cus_no + '" data-parent="#contact_' + full.info.cus_no + '"">' + moveShow + '</div>' + move + '</div>' : '-';
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
                            let show3Top = full.emails.length > 0 ? full.emails.length > 3 ? full.emails.slice(0, 3).map((o, i) => i < 2 ? o.email + '' : o.email) : full.tels.length > 1 ? full.emails.slice(0, 3).map((x, j) => j == 1 ? x.email : x.email + '') : full.emails[0].email ? full.emails[0].email : '-' : ''
                            let moveShow = full.emails.slice(3).map((x, i) => _i++ == count ? x.email : x.email + '')

                            return full.emails.length > 0 ? '<div id="email_' + full.info.cus_no + '">' +
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
                            let show3Top = full.tels.length > 0 ? full.tels.length > 3 ? full.tels.slice(0, 3).map((o, i) => i < 2 ? o.tel + '' : o.tel) : full.tels.length > 1 ? full.tels.slice(0, 3).map((x, j) => j == 1 ? x.tel : x.tel + '') : full.tels[0].tel ? full.tels[0].tel : '-' : ''
                            let moveShow = full.tels.slice(3).map((x, i) => _i++ == count ? x.tel : x.tel + '')

                            return full.tels.length > 0 ? '<div id="tel_' + full.info.cus_no + '">' +
                                '<div class="" id="tel_heading_' + full.info.cus_no + '">' + show3Top + '</div>' +
                                '<div id="collapsetel_' + full.info.cus_no + '" class="accordion-collapse collapse" aria-labelledby="headingtel_' + full.info.cus_no + '" data-parent="#tel_' + full.info.cus_no + '"">' + moveShow + '</div>' + move + '</div>' : '-';
                        }
                    })
                }
                if (o.sort == 5) {
                    columns.push({
                        data: 'uuid',
                        render: function(data, type, full) {
                            return '<a class="btn btn-sm btn-gray-700 text-center" href="/customer/process/update?customer=' + full.info.cus_no + '" target="_blank"><i class="bi bi-pencil"></i></a>'
                        }
                    })
                }
            })
        }


        function formatRepo(repo) {
            if (repo.loading) {
                return repo.text;
            }

            return $('<span>' + repo.cus_name + '(' + repo.cus_no + ')' + '</span>');
        }

        function formatRepoSelection(repo) {
            if (repo.id) {
                return $('<span>' + repo.text.trim() + '</span>');
            }

            return repo.text;

        }

    };
</script>