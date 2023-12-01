<div class="container-fluid">
    <div class="bg-white rounded shadow rounded d-flex flex-column px-4 pt-3 pb-3">
        <form id="invoiceForm" method="get" action="<?php echo $http ?>/customer" class="mb-4">
            <div class="section-filter">
                <div class="box-search-2">
                    <div class="input-search">
                        <label for="customer" class="form-label">ลูกค้า</label>
                        <div class="input-group mb-3">
                            <select class="select2 form-select" name="customer" id="customer">
                                <option value="" selected>เลือก ...</option>
                            </select>
                        </div>
                    </div>

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
                    <div class="mb-3 mt-4 me-3 btn-cus">
                        <button type="submit" class="btn btn-primary btn-search">ค้นหา</button>
                        <a type="button" class="btn btn-success" href="<?php echo $http ?>/customer/process/create">+ สร้างข้อมูลลูกค้า</a>
                    </div>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            <table id="customerLists" class="table table-centered table-striped w-100">
                <thead class="thead-light">
                    <tr>
                        <?php foreach ($table as $res) { ?>
                            <th width="<?php if (in_array($res->sort, [1, 6, 3])) {
                                            echo '10%';
                                        } else if (in_array($res->sort, [8])) {
                                            echo '5%';
                                        } else if (in_array($res->sort, [2])) {
                                            echo '20%';
                                        } else if (in_array($res->sort, [4, 5, 7])) {
                                            echo '15%';
                                        }; ?>" class="align-middle no-search no-sort <?php echo $res->sort == 8 ? 'text-center' : '' ?>">
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
        var days = <?php echo !empty($days) ? json_encode($days) : '[]'; ?>;
        var columns = [];

        if (table) {
            genTable();
        }

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

        $('#customerLists')
            .DataTable({
                "scrollX": false,
                "lengthChange": false,
                "processing": true,
                "serverSide": true,
                "pageLength": 20,
                "order": [],
                "ajax": {
                    url: '<?php echo $http ?>/customer/listCustomer/<?php echo $cus_no; ?>/<?php echo $is_contact; ?>',
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
                            return full.info.cus_no;
                        }
                    })
                }
                if (o.sort == 2) {
                    columns.push({
                        data: 'cus_name',
                        render: function(data, type, full) {
                            return full.info.cus_name ? full.info.cus_name : '-';
                        }
                    })
                }
                if (o.sort == 3) {
                    columns.push({
                        data: 'send_date',
                        render: function(data, type, full) {
                            let send_day = full.info.send_date ? days[full.info.send_date.charAt(0).toUpperCase() + full.info.send_date.slice(1)].name ? days[full.info.send_date.charAt(0).toUpperCase() + full.info.send_date.slice(1)].name : '-' : '-'
                            return send_day;
                        }
                    })
                }
                if (o.sort == 4) {
                    columns.push({
                        data: 'contact',
                        render: function(data, type, full) {
                            let count = full.tels.length > 0 ? full.tels.slice(3).length : 0
                            let _i = 0;
                            let move = full.tels.length > 3 ? '&nbsp;&nbsp;<span id="headingcontact_' + full.info.cus_no + '" data-bs-toggle="collapse" data-bs-target="#collapsecontact_' + full.info.cus_no + '" aria-expanded="true" style="cursor: pointer;" class="text-primary">More&nbsp;<i class="bi bi-chevron-down"></i></span>' : ''
                            let show3Top = full.tels.length > 0 ? full.tels.length > 3 ? full.tels.slice(0, 3).map((o, i) => o.contact ? i < 2 ? o.contact ? o.contact : '' + ' ' : o.contact ? o.contact.replace(',', '') : '' : ' ') : full.tels.length > 1 ? full.tels.slice(0, 3).map((x, j) => x.contact ? j == 1 ? x.contact : x.contact + ' ' : ' ') : full.tels[0].contact ? full.tels[0].contact : '-' : ''
                            let moveShow = full.tels.slice(3).map((x, i) => x.contact ? _i++ == count ? x.contact : x.contact + ' ' : '')

                            return full.tels.length > 0 ? '<div class="tb-10" id="contact_' + full.info.cus_no + '">' +
                                '<div class="" id="contact_heading_' + full.info.cus_no + '"> ' + show3Top + '</div>' +
                                '<div id="collapsecontact_' + full.info.cus_no + '" class="accordion-collapse collapse" aria-labelledby="headingcontact_' + full.info.cus_no + '" data-parent="#contact_' + full.info.cus_no + '"">' + moveShow + '</div>' + move + '</div>' : '-';
                        }
                    })
                }
                if (o.sort == 5) {
                    columns.push({
                        data: 'email',
                        render: function(data, type, full) {
                            let count = full.emails.length > 0 ? full.emails.slice(3).length : 0
                            let _i = 0;
                            let move = full.emails.length > 3 ? '&nbsp;&nbsp;<span id="headingemail_' + full.info.cus_no + '" data-bs-toggle="collapse" data-bs-target="#collapseemail_' + full.info.cus_no + '" aria-expanded="true" style="cursor: pointer;" class="text-primary">More&nbsp;<i class="bi bi-chevron-down"></i></span>' : ''
                            let show3Top = full.emails.length > 0 ? full.emails.length > 3 ? full.emails.slice(0, 3).map((o, i) => i < 2 ? o.email + ' ' : o.email) : full.tels.length > 0 ? full.emails.slice(0, 3).map((x, j) => x.email ? x.email + ' ' : '') : full.emails[0].email ? full.emails[0].email : '-' : ' '
                            let moveShow = full.emails.slice(3).map((x, i) => _i++ == count ? x.email : x.email + ' ')

                            return full.emails.length > 0 ? '<div class="tb-15" id="email_' + full.info.cus_no + '">' +
                                '<div class="" id="email_heading_' + full.info.cus_no + '">' + show3Top + '</div>' +
                                '<div id="collapseemail_' + full.info.cus_no + '" class="accordion-collapse collapse" aria-labelledby="headingemail_' + full.info.cus_no + '" data-parent="#email_' + full.info.cus_no + '"">' + moveShow + '</div>' + move + '</div>' : '-';
                        }
                    })
                }
                if (o.sort == 6) {
                    columns.push({
                        data: 'tel',
                        render: function(data, type, full) {
                            let count = full.tels.length > 0 ? full.tels.slice(3).length : 0
                            let _i = 0;
                            let move = full.tels.length > 3 ? '&nbsp;&nbsp;<span id="headingtel_' + full.info.cus_no + '" data-bs-toggle="collapse" data-bs-target="#collapsetel_' + full.info.cus_no + '" aria-expanded="true" style="cursor: pointer;" class="text-primary">More&nbsp;<i class="bi bi-chevron-down"></i></span>' : ''
                            let show3Top = full.tels.length > 0 ? full.tels.length > 3 ? full.tels.slice(0, 3).map((o, i) => o.tel ? i < 2 ? o.tel + ' ' : o.tel : '') : full.tels.length > 0 ? full.tels.slice(0, 3).map((x, j) => x.tel ? x.tel + ' ' : '') : full.tels[0].tel ? full.tels[0].tel : '-' : ''


                            let moveShow = full.tels.slice(3).map((x, i) => x.tel ? _i++ == count ? x.tel : x.tel + ' ' : '')

                            return full.tels.length > 0 ? '<div class="tb-15" id="tel_' + full.info.cus_no + '">' +
                                '<div class="" id="tel_heading_' + full.info.cus_no + '">' + show3Top + '</div>' +
                                '<div id="collapsetel_' + full.info.cus_no + '" class="accordion-collapse collapse" aria-labelledby="headingtel_' + full.info.cus_no + '" data-parent="#tel_' + full.info.cus_no + '"">' + moveShow + '</div>' + move + '</div>' : '-';
                        }
                    })
                }
                if (o.sort == 7) {
                    columns.push({
                        data: 'fax',
                        render: function(data, type, full) {
                            let count = full.faxs.length > 0 ? full.faxs.slice(3).length : 0
                            let _i = 0;
                            let move = full.faxs.length > 3 ? '&nbsp;&nbsp;<span id="headingtel_' + full.info.cus_no + '" data-bs-toggle="collapse" data-bs-target="#collapsetel_' + full.info.cus_no + '" aria-expanded="true" style="cursor: pointer;" class="text-primary">More&nbsp;<i class="bi bi-chevron-down"></i></span>' : ''
                            let show3Top = full.faxs.length > 0 ? full.faxs.length > 3 ? full.faxs.slice(0, 3).map((o, i) => o.fax ? i < 2 ? o.fax + ' ' : o.fax : '') : full.faxs.length > 0 ? full.faxs.slice(0, 3).map((x, j) => x.fax ? x.fax + ' ' : '') : full.faxs[0].fax ? full.faxs[0].fax : '-' : ''


                            let moveShow = full.faxs.slice(3).map((x, i) => x.fax ? _i++ == count ? x.fax : x.fax + ' ' : '')

                            return full.faxs.length > 0 ? '<div class="tb-15" id="tel_' + full.info.cus_no + '">' +
                                '<div class="" id="tel_heading_' + full.info.cus_no + '">' + show3Top + '</div>' +
                                '<div id="collapsetel_' + full.info.cus_no + '" class="accordion-collapse collapse" aria-labelledby="headingtel_' + full.info.cus_no + '" data-parent="#tel_' + full.info.cus_no + '"">' + moveShow + '</div>' + move + '</div>' : '-';
                        }
                    })
                }
                if (o.sort == 8) {
                    columns.push({
                        data: 'uuid',
                        render: function(data, type, full) {
                            return '<a class="btn btn-sm btn-gray-700 text-center" href="<?php echo $http ?>/customer/process/update?customer=' + full.info.cus_no + '" target="_blank"><i class="bi bi-pencil"></i></a>'
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
                return $('<span>' + repo.cus_name + '(' + repo.cus_no + ')' + '</span>');
            }

            return repo.text;

        }
    };
</script>