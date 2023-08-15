<div class="container-fluid">
    <div class="bg-white rounded shadow rounded d-flex flex-column px-5 pt-3">
        <form id="invoiceForm" method="get" action="/invoice" class="mb-4">
            <div class="section-filter">
                <div class="box-search">
                    <div class="input-search">
                        <label for="dateSelect" class="form-label">วันที่ต้องการแจ้ง</label>
                        <select class="form-select" id="dateSelect" name="dateSelect">
                            <option selected>เลือก ...</option>
                            <?php foreach ($selectDays as $day) { ?>
                            <option value=<?php echo $day->mday; ?>><?php echo $days[$day->mday]->name ?></option>
                            <?php  } ?>
                        </select>
                    </div>

                    <div class="input-search">
                        <label for="startDate" class="form-label">จากวันที่</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" id="startDate" name="startDate"
                                placeholder="เริ่มวันที่" readonly maxlength="10" autocomplete="off">
                            <span class="input-group-text"><i class="bi bi-calendar-date me-2"></i></span>
                        </div>
                    </div>
                    <div class="box-text">
                        <p class="text-form">ถึง</p>
                    </div>
                    <div class="input-search input-endDate">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" id="endDate" name="endDate"
                                placeholder="สิ้นสุดวันที่" readonly maxlength="10" autocomplete="off">
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
                                <option value=<?php echo $customer->mcustno; ?>>
                                    <?php echo $customer->cus_name . ' (' . $customer->mcustno . ')' ?></option>
                                <?php  } ?>
                            </select>
                        </div>
                    </div>
                    <div class="input-search">
                        <label for="type" class="form-label">ประเภทธุรกิจ</label>
                        <select class="form-select" id="type" name="type">
                            <option value="" selected>เลือก ...</option>
                            <?php foreach ($types as $types) { ?>
                            <option value=<?php echo $types->msaleorg; ?>><?php echo $types->msaleorg ?></option>
                            <?php  } ?>
                        </select>
                    </div>
                    <div class="mb-3 mt-4 me-3">
                        <button type="submit" class="btn btn-primary">Confirm</button>
                    </div>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table id="paymentList" class="table table-centered table-striped w-100">
                <thead class="thead-light">
                    <tr>
                        <th width="35%">ลูกค้า</th>
                        <th width="15%">ยอดหนี้</th>
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
                        <td><?php echo !empty($invoice->cus_name) ? $invoice->cus_name . ' (' . $invoice->cus_no . ')' : '-'; ?>
                        </td>
                        <td><?php echo number_format($invoice->balance, 2); ?></td>
                        <td><?php echo !empty($invoice->DC) ? number_format($invoice->DC, 2) : 0; ?></td>
                        <td><?php echo !empty($invoice->RE) ? number_format($invoice->RE, 2) : 0; ?></td>
                        <td><?php echo !empty($invoice->RC) ? number_format($invoice->RC, 2) : 0; ?></td>
                        <td><?php echo !empty($invoice->RD) ? number_format($invoice->RD, 2) : 0; ?></td>
                        <td class="text-center"><a class="btn btn-sm btn-gray-700" data-bs-toggle="modal"
                                data-bs-target="#exampleModal" id="modalCustomer"
                                data-cus_no="<?php echo $invoice->cus_no ?>"
                                data-cus_name="<?php echo $invoice->cus_name ?>">Update</a></td>
                    </tr>
                    <?php }
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="modal fade " id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title header_text" id="exampleModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex flex-column">
                    <div class="d-flex justify-content-between mb-3">
                        <h5 class="title">ppppp</h5>
                        <div class="d-flex">
                            <button type="button" class="btn btn-secondary ms-2">แก้ไข</button>
                            <button type="button" class="btn btn-primary">บิล</button>
                        </div>
                        <div class="border-bottom mb-3"></div>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <h5 class="title">ppppp</h5>
                        <div class="d-flex">
                            <button type="button" class="btn btn-secondary ms-2">แก้ไข</button>
                            <button type="button" class="btn btn-primary">บิล</button>
                        </div>
                        <div class="border-bottom mb-3"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<script>
$(function() {
    var childLists = <?php echo !empty($childLists) ? json_encode($childLists) : '[]'; ?>;

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
        }).on('click', '#modalCustomer', function(e) {
            e.preventDefault();
            let id = $(this).attr("data-cus_no")
            let name = $(this).attr("data-cus_name")
            $('.header_text').text(name + ' (' + id + ')');
            childLists[id].childs.map(o => {
                Object.keys(o).find(key => console.log(o[key]))
            })
            // console.log(childLists[id].childs)
            console.log(childLists[id])
        });

    $()
    $('.dataTables_filter label').hide();

});
</script>