<div class="container-fluid">
	<div class="bg-white rounded shadow rounded d-flex flex-column px-5 pt-3 pb-3 setting">
		<!-- Tab Nav -->
		<div class="nav-wrapper position-relative mb-2">
			<ul class="nav nav-pills nav-fill setting-tab" id="tabs-text" role="tablist">
				<?php if (checkPermission('แก้ไขคอลัมน์และซ่อมข้อมูล', $this->CURUSER->user[0]->dep_id, $this->role)) { ?>
					<?php if (checkPermission('เมนูย่อยแก้ไขคอลัมน์และซ่อมข้อมูล', $this->CURUSER->user[0]->dep_id, $this->role)) { ?>
						<li class="nav-item">
							<a class="nav-link mb-sm-3 mb-md-0 <?php echo $tab == 'invoice' ? 'active' : '' ?>" type="button" id="tabs-text-1-tab" href="<?php echo $http ?>/setting?tab=invoice" aria-controls="tabs-text-1">การแจ้งเตือน</a>
						</li>
						<li class="nav-item">
							<a class="nav-link mb-sm-3 mb-md-0 <?php echo $tab == 'report' ? 'active' : '' ?>" type="button" id="tabs-text-2-tab" href="<?php echo $http ?>/setting?tab=report" aria-controls="tabs-text-2">รายงาน</a>
						</li>
						<li class="nav-item">
							<a class="nav-link mb-sm-3 mb-md-0 <?php echo $tab == 'customer' ? 'active' : '' ?>" type="button" id="tabs-text-3-tab" href="<?php echo $http ?>/setting?tab=customer" aria-controls="tabs-text-3">ลูกค้า</a>
						</li>
						<!-- <li class="nav-item">
							<a class="nav-link mb-sm-3 mb-md-0 <?php //echo $tab == 'repair' ? 'active' : '' 
																?>" type="button" id="tabs-text-4-tab" href="<?php //echo $http 
																												?>/setting?tab=repair" aria-controls="tabs-text-4">ซ่อมข้อมูล</a>
						</li> -->
						<li class="nav-item">
							<a class="nav-link mb-sm-3 mb-md-0 <?php echo $tab == 'bcc' ? 'active' : '' ?>" type="button" id="tabs-text-5-tab" href="<?php echo $http ?>/setting?tab=bcc" aria-controls="tabs-text-5">Bcc Email</a>
						</li>
					<?php } ?>
					<?php if (checkPermission('doctype', $this->CURUSER->user[0]->dep_id, $this->role)) { ?>
						<li class="nav-item">
							<a class="nav-link mb-sm-3 mb-md-0 <?php echo $tab == 'doctype' ? 'active' : '' ?>" type="button" id="tabs-text-5-tab" href="<?php echo $http ?>/setting?tab=doctype" aria-controls="tabs-text-5">Doc Type</a>
						</li>
					<?php } ?>
				<?php } ?>
			</ul>
		</div>
		<!-- End of Tab Nav -->
		<!-- Tab Content -->
		<div class="card border-0">
			<div class="card-body p-0">
				<div class="tab-content" id="tabcontent1">
					<div class="tab-pane fade <?php echo $tab == 'invoice' ? 'show active' : '' ?>" id="tabs-text-1" role="tabpanel" aria-labelledby="tabs-text-1-tab">
						<form class="invoicePage">
							<div class="setting-section">
								<h4>Colunm</h4>
								<div class="setting-container">
									<?php foreach ($lists['invoice'] as $k => $val) { ?>
										<div class="form-setting">
											<div class="form-check form-switch">
												<input class="form-check-input checl-<?php echo $val->uuid; ?>" type="checkbox" name="is_show[]" role="switch" id="<?php echo $val->uuid; ?>" value="<?php echo $val->uuid; ?>" <?php echo !empty($val->is_show) ? 'checked' : ''; ?>>
												<label class="form-check-label" for="<?php echo $val->uuid; ?>"><?php echo $val->colunm; ?></label>
											</div>
										</div>
									<?php } ?>
								</div>
								<div class="d-flex justify-content-end mt-5">
									<button class="btn btn-primary loading" type="button" disabled style="display: none;">
										<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
										โหลด...
									</button>
									<button type="button" class="btn btn-primary submit">ยืนยัน</button>
								</div>
							</div>
						</form>
					</div>
					<div class="tab-pane fade <?php echo $tab == 'report' ? 'show active' : '' ?>" id="tabs-text-2" role="tabpanel" aria-labelledby="tabs-text-2-tab">
						<form class="reportPage">
							<div class="setting-section">
								<h4>Colunm</h4>
								<div class="setting-container">
									<?php foreach ($lists['report'] as $k => $val) { ?>
										<div class="form-setting">
											<div class="form-check form-switch">
												<input class="form-check-input checl-<?php echo $val->uuid; ?>" type="checkbox" name="is_show[]" role="switch" id="<?php echo $val->uuid; ?>" value="<?php echo $val->uuid; ?>" <?php echo !empty($val->is_show) ? 'checked' : ''; ?>>
												<label class="form-check-label" for="<?php echo $val->uuid; ?>"><?php echo $val->colunm; ?></label>
											</div>
										</div>
									<?php } ?>
								</div>
								<div class="d-flex justify-content-end mt-5">
									<button class="btn btn-primary loading" type="button" disabled style="display: none;">
										<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
										โหลด...
									</button>
									<button type="button" class="btn btn-primary submit">ยืนยัน</button>
								</div>
							</div>
						</form>
					</div>
					<div class="tab-pane fade <?php echo $tab == 'customer' ? 'show active' : '' ?>" id="tabs-text-3" role="tabpanel" aria-labelledby="tabs-text-3-tab">
						<form class="customerPage">
							<div class="setting-section">
								<h4>Colunm</h4>
								<div class="setting-container">
									<?php foreach ($lists['customer'] as $k => $val) { ?>
										<div class="form-setting">
											<div class="form-check form-switch">
												<input class="form-check-input checl-<?php echo $val->uuid; ?>" type="checkbox" name="is_show[]" role="switch" id="<?php echo $val->uuid; ?>" value="<?php echo $val->uuid; ?>" <?php echo !empty($val->is_show) ? 'checked' : ''; ?>>
												<label class="form-check-label" for="<?php echo $val->uuid; ?>"><?php echo $val->colunm; ?></label>
											</div>
										</div>
									<?php } ?>
								</div>
								<div class="d-flex justify-content-end aligtn mt-5">
									<button class="btn btn-primary loading" type="button" disabled style="display: none;">
										<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
										โหลด...
									</button>
									<button type="button" class="btn btn-primary submit">ยืนยัน</button>
								</div>
							</div>
						</form>
					</div>

					<div class="tab-pane fade <?php echo $tab == 'repair' ? 'show active' : '' ?>" id="tabs-text-4" role="tabpanel" aria-labelledby="tabs-text-4-tab">
						<form class="repairPage" method="get" action="<?php echo $http ?>/setting?tab=repair">
							<div class="section-filter-2">
								<div class="box-search">
									<div class="input-search-2">
										<label for="dateSelect" class="form-label">รอบการแจ้ง <span class="text-danger">*</span></label>
										<select class="form-select" id="dateSelect" name="dateSelect" required>
											<option value="">เลือก ...</option>
											<?php foreach ($selectDays as $day) { ?>
												<option value="<?php echo $day->mday; ?>"><?php echo $days[$day->mday]->name ?></option>
											<?php  } ?>
										</select>
									</div>

									<div class="input-search-2">
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
									<div class="input-search-2">
										<label for="customer" class="form-label">ลูกค้า</label>
										<div class="input-group mb-3">
											<select class="select2 form-select" name="customer" id="customer">
												<option value="">เลือกลูกค้า</option>
											</select>
										</div>
									</div>

									<div class="input-search-2">
										<label for="type" class="form-label">ประเภทธุรกิจ</label>
										<select class="form-select" id="type" name="type">
											<option value="1" selected>ทั้งหมด</option>
											<?php foreach ($types as $type) { ?>
												<option value="<?php echo $type->msaleorg; ?>" <?php echo $type->msaleorg == $typeSC ? 'selected' : ''; ?>><?php echo $type->msaleorg_des ?></option>
											<?php }; ?>
										</select>
									</div>
									<div class="box-text">
										<p class="text-form"></p>
									</div>
									<div class="input-search-2">
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
								</div>

								<div class="d-flex justify-content-end">
									<div class="mb-3 mt-4 me-3">
										<button type="button" class="btn btn-primary submit" disabled>ค้นหา</button>
										<button type="button" class="btn btn-success export" disabled>Export excel</button>
									</div>
								</div>
							</div>
						</form>

						<div class="alert alert-warning mt-3 d-none" role="alert">
							<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> โปรดรอสักครู่ ระบบกำลังซ่อมข้อมูล
						</div>
					</div>

					<div class="tab-pane fade <?php echo $tab == 'doctype' ? 'show active' : '' ?>" id="tabs-text-5" role="tabpanel" aria-labelledby="tabs-text-5-tab">
						<form class="doctypePage">
							<div class="setting-section">
								<h4>Doc Type</h4>
								<div class="setting-container">
									<?php foreach ($doctype as $k => $val) { ?>
										<div class="setting-doctype">
											<div class="form-check form-switch size-text">
												<input class="form-check-input checl-<?php echo $val->uuid; ?>" type="checkbox" name="is_show[]" role="switch" id="<?php echo $val->uuid; ?>" value="<?php echo $val->uuid; ?>" <?php echo !empty($val->is_show) ? 'checked' : ''; ?>>
												<label class="form-check-label label-width" for="<?php echo $val->uuid; ?>"><?php echo $val->type_display_th; ?></label>
											</div>

											<div class="input-search date-doctype">
												<label for="startDate" class="form-label label-duedate">แสดงวันที่ <span class="text-danger">*</span></label>
												<div class="input-group mb-3">
													<input type="text" class="form-control show-startDate show-start-<?php echo $val->uuid; ?>" id="show-startDate" name="show-startDate[]" required placeholder="เริ่มวันที่" autocomplete="off" readonly value="<?php echo !empty($val->start_date) ? $val->start_date : $startDefault; ?>">
													<span class="input-group-text"><i class="bi bi-calendar-date me-2"></i></span>
												</div>
											</div>
											<div class="box-text-date">
												<p class="text-form">ถึง</p>
											</div>
											<div class="input-search date-doctype">
												<div class="input-group mb-3">
													<input type="text" class="form-control show-endDate show-end-<?php echo $val->uuid; ?>" id="show-endDate" name="show-endDate[]" required placeholder="สิ้นสุดวันที่" autocomplete="off" readonly value="<?php echo !empty($val->end_date) ? $val->end_date : $endDefault; ?>">
													<span class="input-group-text"><i class="bi bi-calendar-date me-2"></i></span>
												</div>
											</div>
										</div>
									<?php } ?>
								</div>
								<div class="d-flex justify-content-end aligtn mt-5">
									<button class="btn btn-primary loading" type="button" disabled style="display: none;">
										<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
										โหลด...
									</button>
									<button type="button" class="btn btn-primary submit">ยืนยัน</button>
								</div>
							</div>
						</form>
					</div>

					<div class="tab-pane fade <?php echo $tab == 'bcc' ? 'show active' : '' ?>" id="tabs-text-5" role="tabpanel" aria-labelledby="tabs-text-5-tab">
						<form class="bccPage">
							<div class="setting-section">
								<h4>Bcc Email</h4>
								<div class="setting-container">
									<textarea class="form-control" id="bcc_email" name="bcc_email" rows="3" cols="20"><?php echo !empty($bccEmail) ? $bccEmail : ''; ?></textarea>
								</div>
								<div class="d-flex justify-content-end aligtn mt-5">
									<button class="btn btn-primary loading" type="button" disabled style="display: none;">
										<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
										โหลด...
									</button>
									<button type="button" class="btn btn-primary submit">ยืนยัน</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<!-- End of Tab Content -->
	</div>
</div>

<script>
	$(function() {

		$('.show-startDate').datepicker({
			todayHighlight: true,
			format: 'yyyy-mm-dd',
			autoclose: true,
		});

		$('.show-endDate').datepicker({
			todayHighlight: true,
			format: 'yyyy-mm-dd',
			autoclose: true,
		});


		$('.invoicePage').on('click', '.submit', function(e) {
			e.preventDefault();
			readyProcess('.invoicePage', true)
			let formData = $('.invoicePage').serializeArray();
			process('.invoicePage', formData)
		});

		$('.reportPage').on('click', '.submit', function(e) {
			e.preventDefault();
			readyProcess('.reportPage', true)
			let formData = $('.reportPage').serializeArray();
			process('.reportPage', formData)
		});

		$('.customerPage').on('click', '.submit', function(e) {
			e.preventDefault();
			readyProcess('.customerPage', true)
			let formData = $('.customerPage').serializeArray();
			process('.customerPage', formData)
		});

		$('.doctypePage').on('click', '.submit', function(e) {
			e.preventDefault();
			readyProcess('.doctypePage', true)
			let formData = $('.doctypePage').serializeArray();
			$.post("<?php echo $http ?>/setting/processDoctype/<?php echo $tab; ?>", formData).done(function(res) {
				if (res.status === 200) {
					Swal.fire({
						icon: 'success',
						text: 'อัปเดตข้อมูลเรียบร้อย',
						confirmButtonText: 'ตกลง'
					}).then((result) => {
						if (result.isConfirmed) {
							window.location = '<?php echo $http ?>/setting?tab=' + '<?php echo $tab; ?>';

							$.post('<?php echo $http  ?>/api/addMainLog/update', {
								page: 'setting_<?php echo $tab ?>',
								url: CURRENT_URL,
								detail: JSON.stringify(res.data),
								source: JSON.stringify(res.source)
							});
						}
					})
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
				readyProcess('<?php echo $tab; ?>');
			});

		});

		$('.bccPage').on('click', '.submit', function(e) {
			e.preventDefault();
			readyProcess('.bccPage', true)
			let formData = $('.bccPage').serializeArray();
			$.post("<?php echo $http ?>/setting/processBccEmail/<?php echo $tab; ?>", formData).done(function(res) {
				if (res.status === 200) {
					Swal.fire({
						icon: 'success',
						text: 'อัปเดตข้อมูลเรียบร้อย',
						confirmButtonText: 'ตกลง'
					}).then((result) => {
						window.location = '<?php echo $http ?>/setting?tab=' + '<?php echo $tab; ?>';

						$.post('<?php echo $http  ?>/api/addMainLog/update', {
							page: 'setting_<?php echo $tab ?>',
							url: CURRENT_URL,
							detail: JSON.stringify(res.data),
							source: JSON.stringify(res.source)
						});
					})
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
				readyProcess('<?php echo $tab; ?>');
			});

		});

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

		$('.repairPage').on('click', '.submit', function(e) {
			e.preventDefault();
			$('.repairPage .submit').prop("disabled", true);
			$('.repairPage .export').prop("disabled", true);
			$('#tabs-text-4 .alert').removeClass('d-none').addClass('d-block')
			let formData = $('.repairPage').serializeArray();
			$.post("<?php echo $http ?>/setting/repair", formData).done(function(res) {
				if (res.status === 200 || (res.status === 204 && res.msg.search('Fax'))) {
					$('#tabs-text-4 .alert').removeClass('d-block').addClass('d-none')
					Swal.fire({
						icon: 'success',
						text: 'ซ่อมข้อมูลเรียบร้อย',
						confirmButtonText: 'ตกลง'
					})
					$('.repairPage .submit').prop("disabled", false);
					$('.repairPage .export').prop("disabled", false);

					$.post('<?php echo $http ?>/api/addMainLog/update', {
						page: 'ซ่อมข้อมูล',
						url: CURRENT_URL,
						detail: JSON.stringify(res.data),
						source: JSON.stringify(res.source)
					});
				} else {
					$('#tabs-text-4 .alert').removeClass('d-block').addClass('d-none')
					$('.repairPage .submit').prop("disabled", false);
					$('.repairPage .export').prop("disabled", false);

					if (res.status === 204) {
						Swal.fire({
							icon: 'error',
							text: res.msg,
							confirmButtonText: 'ตกลง'
						})
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

				}
			});

		}).on('click', '.export', function(e) {
			e.preventDefault();
			let formData = $('.repairPage').serializeArray();
			let path = formData[0].name + '=' + formData[0].value + '&' + formData[1].name + '=' + formData[1].value + '&' + formData[2].name + '=' + formData[2].value + '&' + formData[3].name + '=' + formData[3].value + '&' + formData[4].name + '=' + formData[4].value + '&' + formData[5].name + '=' + formData[5].value + '&' + formData[6].name + '=' + formData[6].value
			window.open("<?php echo $http ?>/invoice/genInvoiceListExcel?" + path, '_self');

		});


		$('.repairPage #dateSelect').on('change', function(e) {
			let val = $(this).val()
			if (val) {
				$('.repairPage .export').prop('disabled', false)
				$('.repairPage .submit').prop('disabled', false)
			} else {
				$('.repairPage .export').prop('disabled', true)
				$('.repairPage .submit').prop('disabled', true)
			}
		})

		function process(tab, formData) {
			$.post("<?php echo $http ?>/setting/process/<?php echo $tab; ?>", formData).done(function(res) {
				if (res.status === 200) {
					Swal.fire({
						icon: 'success',
						text: 'อัปเดตข้อมูลเรียบร้อย',
						confirmButtonText: 'ตกลง'
					}).then((result) => {
						window.location = '<?php echo $http ?>/setting?tab=' + '<?php echo $tab; ?>';

						$.post('<?php echo $http ?>/api/addMainLog/update', {
							page: 'setting_<?php echo $tab; ?>',
							url: CURRENT_URL,
							detail: JSON.stringify(res.data),
							source: JSON.stringify(res.source)
						});
					})
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
				readyProcess(tab);
			});
		}

		function readyProcess(tab, wait = false) {
			if (wait) {
				$(tab + ' .submit').hide();
				$(tab + ' .loading').show();
			} else {
				$(tab + ' .submit').show();
				$(tab + ' .loading').hide();
			}
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
	});
</script>
