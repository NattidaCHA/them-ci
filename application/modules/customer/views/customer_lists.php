<div class="container-fluid">
    <div class="bg-white rounded shadow rounded d-flex flex-column px-5 pt-3 pb-3">
        <form id="invoiceForm" method="get" action="/customer" class="mb-4">
            <div class="box-customer-search">
                <div class="input-search-2">
                    <label for="customer" class="form-label">ลูกค้า</label>
                    <div class="input-group mb-3">
                        <select class="select2 form-select" name="customer" id="customer">
                            <option value="" selected>เลือก ...</option>
                            <?php foreach ($customers as $customer) { ?>
                                <option value="<?php echo $customer->cus_no; ?>" <?php echo $cus_no == $customer->cus_no ? 'selected' : '' ?>>
                                    <?php echo $customer->cus_name . ' (' . $customer->cus_no . ')' ?></option>
                            <?php  } ?>
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
                        <th width="21%">ลูกค้า</th>
                        <th width="20%">ผู้ติดต่อ</th>
                        <th width="27%">อีเมล</th>
                        <th width="27%">เบอร์โทร</th>
                        <th width="5%" class="no-search no-sort text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($lists)) {
                        foreach ($lists as $k => $res) { ?>
                            <tr>
                                <td><?php echo !empty($res->cus_name) ? $res->cus_name . ' (' . $res->cus_no . ')' : '-'; ?>
                                </td>
                                <td>
                                    <?php if (!empty($res->uuid)) { ?>
                                        <div id="contact_<?php echo $res->uuid ?>">
                                            <div class="" id="contact_heading_<?php echo $res->uuid ?>">
                                                <?php if (!empty($tels[$res->cus_no])) { ?>
                                                    <?php if (count($tels[$res->cus_no]) > 3) { ?>
                                                        <?php foreach (array_slice($tels[$res->cus_no], 0, 3) as $key => $value) { ?>
                                                            <?php echo (($key < 2)) ? $value->contact . ',' : $value->contact; ?><?php } ?>
                                            </div>
                                            <div id="contact_collapse_<?php echo $res->uuid ?>" class="accordion-collapse collapse" aria-labelledby="contact_heading_<?php echo $res->uuid ?>" data-parent="#contact_<?php echo $res->uuid ?>">
                                                <?php $count = count(array_slice($tels[$res->cus_no], 3)); ?>
                                                <?php $i = 0; ?>
                                                <?php foreach (array_slice($tels[$res->cus_no], 3) as $key => $value) { ?>
                                                    <?php echo ((++$i == $count)) ? $value->contact : $value->contact . ','; ?>
                                                <?php } ?>
                                            </div>
                                        <?php } else { ?>
                                            <?php if (count($tels[$res->cus_no]) > 1) { ?>
                                                <?php foreach (array_slice($tels[$res->cus_no], 0, 2) as $key => $value) { ?>
                                                    <?php echo (($key == 1)) ? $value->contact : $value->contact . ','; ?><?php } ?>
                                                <?php } else {
                                                            echo !empty($value->contact) ?  $value->contact : '-';
                                                        } ?>
                                            <?php } ?>
                                            <?php if (count($tels[$res->cus_no]) > 3) { ?>
                                                &nbsp;&nbsp;<span data-bs-toggle="collapse" data-bs-target="#contact_collapse_<?php echo $res->uuid ?>" aria-expanded="true" style="cursor: pointer;" class="text-primary">More&nbsp;<i class="bi bi-chevron-down"></i></span>
                                            <?php } ?>
                                        <?php } else {
                                                    echo '-';
                                                } ?>
                                        </div>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if (!empty($res->uuid)) { ?>
                                        <div id="accordion_<?php echo $res->uuid ?>">
                                            <div class="" id="heading_<?php echo $res->uuid ?>">
                                                <?php if (!empty($emails[$res->cus_no])) { ?>
                                                    <?php if (count($emails[$res->cus_no]) > 3) { ?>
                                                        <?php foreach (array_slice($emails[$res->cus_no], 0, 3) as $key => $value) { ?>
                                                            <?php echo (($key < 2)) ? $value->email . ',' : $value->email; ?><?php } ?>
                                            </div>
                                            <div id="collapse_<?php echo $res->uuid ?>" class="accordion-collapse collapse" aria-labelledby="heading_<?php echo $res->uuid ?>" data-parent="#accordion_<?php echo $res->uuid ?>">
                                                <?php $count = count(array_slice($emails[$res->cus_no], 3)); ?>
                                                <?php $i = 0; ?>
                                                <?php foreach (array_slice($emails[$res->cus_no], 3) as $key => $value) { ?>
                                                    <?php echo ((++$i == $count)) ? $value->email : $value->email . ','; ?>
                                                <?php } ?>
                                            </div>
                                        <?php } else { ?>
                                            <?php if (count($emails[$res->cus_no]) > 1) { ?>
                                                <?php foreach (array_slice($emails[$res->cus_no], 0, 2) as $key => $value) { ?>
                                                    <?php echo (($key == 1)) ? $value->email : $value->email . ','; ?><?php } ?>
                                                <?php } else {
                                                            echo !empty($value->email) ? $value->email : '-';
                                                        } ?>
                                            <?php } ?>
                                            <?php if (count($emails[$res->cus_no]) > 3) { ?>
                                                &nbsp;&nbsp;<span data-bs-toggle="collapse" data-bs-target="#collapse_<?php echo $res->uuid ?>" aria-expanded="true" style="cursor: pointer;" class="text-primary">More&nbsp;<i class="bi bi-chevron-down"></i></span>
                                            <?php } ?>
                                        <?php } else {
                                                    echo '-';
                                                } ?>
                                        </div>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if (!empty($res->uuid)) { ?>
                                        <div id="tel_<?php echo $res->uuid ?>">
                                            <div class="" id="tel_heading_<?php echo $res->uuid ?>">
                                                <?php if (!empty($tels[$res->cus_no])) { ?>
                                                    <?php if (count($tels[$res->cus_no]) > 3) { ?>
                                                        <?php foreach (array_slice($tels[$res->cus_no], 0, 3) as $key => $value) { ?>
                                                            <?php echo (($key < 2)) ? $value->tel . ',' : $value->tel; ?><?php } ?>
                                            </div>
                                            <div id="tel_collapse_<?php echo $res->uuid ?>" class="accordion-collapse collapse" aria-labelledby="tel_heading_<?php echo $res->uuid ?>" data-parent="#tel_<?php echo $res->uuid ?>">
                                                <?php $count = count(array_slice($tels[$res->cus_no], 3)); ?>
                                                <?php $i = 0; ?>
                                                <?php foreach (array_slice($tels[$res->cus_no], 3) as $key => $value) { ?>
                                                    <?php echo ((++$i == $count)) ? $value->tel : $value->tel . ','; ?>
                                                <?php } ?>
                                            </div>
                                        <?php } else { ?>
                                            <?php if (count($tels[$res->cus_no]) > 1) { ?>
                                                <?php foreach (array_slice($tels[$res->cus_no], 0, 2) as $key => $value) { ?>
                                                    <?php echo (($key == 1)) ? $value->tel : $value->tel . ','; ?><?php } ?>
                                                <?php } else {
                                                            echo !empty($value->tel) ?  $value->tel : '-';
                                                        } ?>
                                            <?php } ?>
                                            <?php if (count($tels[$res->cus_no]) > 3) { ?>
                                                &nbsp;&nbsp;<span data-bs-toggle="collapse" data-bs-target="#tel_collapse_<?php echo $res->uuid ?>" aria-expanded="true" style="cursor: pointer;" class="text-primary">More&nbsp;<i class="bi bi-chevron-down"></i></span>
                                            <?php } ?>
                                        <?php } else {
                                                    echo '-';
                                                } ?>
                                        </div>
                                    <?php } ?>
                                </td>
                                <td class="text-center">
                                    <a class="btn btn-sm btn-gray-700" href="/customer/process/update?customer=<?php echo $res->cus_no; ?>" target="_blank"><i class="bi bi-pencil"></i></a>
                                </td>
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

        $('.select2').select2({
            theme: "bootstrap-5"
        });

        $('#customerLists')
            .DataTable({
                "scrollX": false,
                "lengthChange": false,
                "pageLength": 20,
                "order": [
                    [0, "desc"]
                ],
                "columnDefs": [{
                        "targets": [1, 2, 3, 4],
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