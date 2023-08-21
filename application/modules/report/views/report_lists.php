<div class="container-fluid">
    <div class="bg-white rounded shadow rounded d-flex flex-column px-5 pt-3 pb-3">
        <form id="invoiceForm" method="get" action="/invoice/report" class="mb-4">
            <div class="section-filter-2">
                <div class="box-search">
                    <div class="input-search-2">
                        <label for="startDate" class="form-label">วันสร้างบิล</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" id="created_date" name="created_date" placeholder="วันสร้างบิล" readonly autocomplete="off">
                            <span class="input-group-text"><i class="bi bi-calendar-date me-2"></i></span>
                        </div>
                    </div>
                    <div class="input-search-2">
                        <label for="customer" class="form-label">เลขที่เอกสาร</label>
                        <div class="input-group mb-3">
                            <select class="select2 form-select" name="bill_no" id="bill_no">
                                <option value="" selected>เลือก ...</option>
                                <?php foreach ($billNos as $billNo) { ?>
                                    <option value=<?php echo $billNo->bill_no; ?>>
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
                                    <option value=<?php echo $customer->mcustno; ?>>
                                        <?php echo $customer->cus_name . ' (' . $customer->mcustno . ')' ?></option>
                                <?php  } ?>
                            </select>
                        </div>
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
                        <th width="10%" >วันสร้างบิล</th>
                        <th width="15%">เลขที่เอกสาร</th>
                        <th width="20%">ลูกค้า</th>
                        <th width="15%">อีเมล</th>
                        <th width="15%">Fax</th>
                        <th width="10%">สถานะ</th>
                        <th width="10%" class="no-search no-sort text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($lists)) {
                        foreach ($lists as $invoice) { ?>
                            <tr>
                                <td><?php echo $invoice->created_date; ?></td>
                                <td><?php echo $invoice->bill_no; ?></td>
                                <td><?php echo !empty($invoice->mcustname) ? $invoice->mcustname . ' (' . $invoice->cus_no . ')' : '-'; ?>
                                </td>
                                <td><?php echo !empty($invoice->memail) ? $invoice->memail : '-'; ?></td>
                                <td><?php echo !empty($invoice->mfax) ? $invoice->mfax : '-'; ?></td>
                                <td>
                                    <?php if (!empty($invoice->is_email)) {
                                        echo '<i class="bi bi-check-lg text-success"></i>';
                                    }else {
                                        echo '<i class="bi bi-x-lg text-danger"></i>';
                                    } ?>
                                </td>
                                <td class="text-center"><a class="btn btn-sm btn-primary" id="report">Report</a></td>
                            </tr>
                    <?php }
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<script>
    $(function() {
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
            });
        $('.dataTables_filter label').hide();

    });
</script>