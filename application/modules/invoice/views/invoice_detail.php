<div class="container-fluid">
	<div class="bg-white rounded shadow rounded d-flex flex-column p-5">
		<div class="text-cus">บริษัทย่อย</div>
		<form class="invoice">
			<div class="accordion mb-3" id="accordionPricing">
				<?php foreach ($lists->childs as $key => $child) { ?>
					<div class="accordion-item">
						<h2 class="accordion-header" id="<?php echo $key; ?>">
							<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?php echo $key; ?>" aria-expanded="true" aria-controls="collapse-<?php echo $key; ?>">
								<?php echo $child->info->cus_name; ?> (<?php echo $key; ?>)
							</button>
						</h2>
						<div id="collapse-<?php echo $key; ?>" class="accordion-collapse collapse" aria-labelledby="<?php echo $key; ?>" data-bs-parent="#accordionPricing">
							<div class="accordion-body">
								<div class="invoice-list">
									<div class="checkbox">
										<input class="form-check-input key-id" type="hidden" name="key[]" id="key" autocomplete="off" value="<?php echo  $key; ?>">
										<div class="form-check">
											<input class="form-check-input check_all cf-all-<?php echo $key; ?>" type="checkbox" name="check_all[]" id="check_all" value="<?php echo $key; ?>" autocomplete="off">
											<label class="form-check-label" for="check_all">
												เลือกทั้งหมด
											</label>
										</div>
									</div>
									<div class="border-bottom mb-2"></div>
									<div class="<?php echo count($child->bills) > 10 ? 'scroll mb-3' : '' ?>">
										<?php foreach ($child->bills as $val => $bill) { ?>
											<div class="group-invoice">
												<div class="list-invoice">
													<div class="d-flex">
														<div class="checkbox">
															<div class="form-check">

																<input class="form-check-input select_invoice check-<?php echo $key; ?>" type="checkbox" name="cf_invoice[]" id="cf_invoice" autocomplete="off" value="<?php echo $bill->macctdoc . '|' . $key . '|' . $bill->mduedate; ?>" <?php echo in_array($bill->mdoctype, ['RA', 'RD']) ? 'checked' : '-'; ?>>
															</div>
														</div>
														<p>ชนิดบิล : </p>
														<p class="ms-2 mdoctype-<?php echo $bill->macctdoc; ?>">
															<?php echo !empty($bill->mdoctype) ? $bill->mdoctype : '-'; ?>
														</p>
													</div>
													<div class="d-flex">
														<p>เลขที่บิล : </p>
														<p class="ms-2"><?php echo !empty($bill->mbillno) || $bill->mbillno == ' ' ? $bill->mbillno : '-'; ?>
														</p>
													</div>
													<div class="d-flex">
														<p>ยอดหนี้ : </p>
														<p class="d-none cf-mnetamt-<?php echo $bill->macctdoc; ?>">
															<?php echo !empty($bill->mnetamt) ? $bill->mnetamt : 0; ?>
														</p>
														<p class="ms-4">
															<?php echo !empty($bill->mnetamt) ? number_format($bill->mnetamt, 2) : 0; ?>
														</p>
													</div>
													<div class="d-flex">
														<p>วันที่ชำระบิล : </p>
														<p class="ms-2">
															<?php echo !empty($bill->mduedate) ? $bill->mduedate : '-'; ?>
														</p>
													</div>
												</div>
												<div class="d-flex">
													<p>รายละเอียด : </p>
													<p class="ms-2"><?php echo !empty($bill->mtext) ? $bill->mtext : '-'; ?>
													</p>
												</div>
												<div class="border-bottom mb-2"></div>
											</div>
										<?php }; ?>
									</div>

									<div class="total-invoice  _sum_RA-<?php echo $key; ?> <?php echo !empty($child->balance->total_RA) ? '' : 'd-none'; ?>">
										<p>ยอดรวม RA </p>
										<p class="ms-2 RA-text-<?php echo $key; ?>">
											<?php echo !empty($child->balance->total_RA) ? number_format($child->balance->total_RA, 2) : 0; ?>
										</p>
									</div>

									<div class="total-invoice _sum_RD-<?php echo $key; ?> <?php echo !empty($child->balance->total_RD) ? '' : 'd-none'; ?>">
										<p>ยอดรวม RD</p>
										<p class="ms-2 RD-text-<?php echo $key; ?>">
											<?php echo !empty($child->balance->total_RD) ? number_format($child->balance->total_RD, 2) : 0; ?>
										</p>
									</div>

									<div class="total-invoice _sum_RC-<?php echo $key; ?> d-none">
										<p>ยอดรวม RC</p>
										<p class="ms-2 RC-text-<?php echo $key; ?>">
											<?php echo !empty($child->balance->total_RC) ? '(' . number_format($child->balance->total_RC, 2) . ')' : 0; ?>
										</p>
									</div>

									<div class="total-invoice _sum_RB-<?php echo $key; ?> d-none">
										<p>ยอดรวม RB</p>
										<p class="ms-2 RB-text-<?php echo $key; ?>">
											<?php echo !empty($child->balance->total_RB) ? '(' . number_format($child->balance->total_RB, 2) . ')' : 0; ?>
										</p>
									</div>


									<div class="total-invoice _sum_DC-<?php echo $key; ?>  d-none">
										<p>ยอดรวม DC</p>
										<p class="ms-2 DC-text-<?php echo $key; ?>">
											<?php echo !empty($child->balance->total_DC) ? '(' . number_format($child->balance->total_DC, 2) . ')' : 0; ?>
										</p>
									</div>

									<div class="total-invoice _sum_RE-<?php echo $key; ?> d-none">
										<p>ยอดรวม RE</p>
										<p class="ms-2 RE-text-<?php echo $key; ?>">
											<?php echo !empty($child->balance->total_RE) ? '(' . number_format($child->balance->total_RE, 2) . ')' : 0; ?>
										</p>
									</div>

									<div class="total-invoice _sum_DA-<?php echo $key; ?> d-none">
										<p>ยอดรวม DA</p>
										<p class="ms-2 DA-text-<?php echo $key; ?>">
											<?php echo !empty($child->balance->total_DA) ? '(' . number_format($child->balance->total_DA, 2) . ')' : 0; ?>
										</p>
									</div>

									<div class="total-invoice _sum_DB-<?php echo $key; ?> d-none">
										<p>ยอดรวม DB</p>
										<p class="ms-2 DB-text-<?php echo $key; ?>">
											<?php echo !empty($child->balance->total_DB) ? '(' . number_format($child->balance->total_DB, 2) . ')' : 0; ?>
										</p>
									</div>

									<div class="total-invoice _sum_DE-<?php echo $key; ?> d-none">
										<p>ยอดรวม DE</p>
										<p class="ms-2 DE-text-<?php echo $key; ?>">
											<?php echo !empty($child->balance->total_DE) ? '(' . number_format($child->balance->total_DE, 2) . ')' : 0; ?>
										</p>
									</div>


									<p class="ms-2 RA_total d-none">
										<?php echo !empty($child->balance->total_RA) ? $child->balance->total_RA : 0; ?>
									</p>

									<p class="ms-2 RD_total d-none">
										<?php echo !empty($child->balance->total_RD) ? $child->balance->total_RD : 0; ?>
									</p>

									<p class="ms-2 DC_total d-none">
										<?php echo !empty($child->balance->total_DC) ? $child->balance->total_DC : 0; ?>
									</p>

									<p class="ms-2 RB_total d-none">
										<?php echo !empty($child->balance->total_RB) ? $child->balance->total_RB : 0; ?>
									</p>

									<p class="ms-2 RC_total d-none">
										<?php echo !empty($child->balance->total_RC) ? $child->balance->total_RC : 0; ?>
									</p>

									<p class="ms-2 RE_total d-none">
										<?php echo !empty($child->balance->total_RE) ? $child->balance->total_RE : 0; ?>
									</p>

									<p class="ms-2 DA_total d-none">
										<?php echo !empty($child->balance->total_DA) ? $child->balance->total_DA : 0; ?>
									</p>
									<p class="ms-2 DB_total d-none">
										<?php echo !empty($child->balance->total_DB) ? $child->balance->total_DB : 0; ?>
									</p>
									<p class="ms-2 DE_total d-none">
										<?php echo !empty($child->balance->total_DE) ? $child->balance->total_DE : 0; ?>
									</p>

									<p class="ms-2 am_total d-none">
										<?php echo !empty($child->balance->total_balance) ? $child->balance->total_balance : 0; ?>
									</p>

									<div class="total-invoice">
										<p>ยอดหนี้คงเหลือ</p>
										<p class="ms-2 total-text-<?php echo $key; ?>">
											<?php echo !empty($child->balance->total_balance) ? number_format($child->balance->total_balance, 2) : 0; ?>
										</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php }; ?>
			</div>

			<?php if (count($lists->childs) > 1) { ?>
				<div class="border-bottom mb-3 mt-2"></div>

				<?php if (!empty($lists->total_summary)) { ?>
					<div class="total-invoice  total_summary_RA <?php echo !empty($lists->total_summary->total_RA) ? '' : 'd-none'; ?>">
						<p>ยอดรวม RA ทั้งหมด</p>
						<p class="ms-2 summary_RA">
							<?php echo !empty($lists->total_summary->total_RA) ? number_format($lists->total_summary->total_RA, 2) : 0; ?>
						</p>
					</div>

					<div class="total-invoice  total_summary_RD <?php echo !empty($lists->total_summary->total_RD) ? '' : 'd-none'; ?>">
						<p>ยอดรวม RD ทั้งหมด</p>
						<p class="ms-2 summary_RD">
							<?php echo !empty($lists->total_summary->total_RD) ? number_format($lists->total_summary->total_RD, 2) : 0; ?>
						</p>
					</div>

					<div class="total-invoice  total_summary_RC d-none">
						<p>ยอดรวม RC ทั้งหมด</p>
						<p class="ms-2 summary_RC">(
							<?php echo !empty($lists->total_summary->total_RC) ? number_format($lists->total_summary->total_RC, 2) : 0; ?>)
						</p>
					</div>

					<div class="total-invoice  total_summary_RB d-none">
						<p>ยอดรวม RB ทั้งหมด</p>
						<p class="ms-2 summary_RB">
							(<?php echo !empty($lists->total_summary->total_RB) ? number_format($lists->total_summary->total_RB, 2) : 0; ?>)
						</p>
					</div>

					<div class="total-invoice  total_summary_DC d-none">
						<p>ยอดรวม DC ทั้งหมด</p>
						<p class="ms-2 summary_DC">
							(<?php echo !empty($lists->total_summary->total_DC) ? number_format($lists->total_summary->total_DC, 2) : 0; ?>)
						</p>
					</div>

					<div class="total-invoice  total_summary_RE d-none">
						<p>ยอดรวม RE ทั้งหมด</p>
						<p class="ms-2 summary_RE">
							(<?php echo !empty($lists->total_summary->total_RE) ? number_format($lists->total_summary->total_RE, 2) : 0; ?>)
						</p>
					</div>

					<div class="total-invoice  total_summary_DA d-none">
						<p>ยอดรวม DA ทั้งหมด</p>
						<p class="ms-2 summary_DA">
							(<?php echo !empty($lists->total_summary->total_DA) ? number_format($lists->total_summary->total_DA, 2) : 0; ?>)
						</p>
					</div>

					<div class="total-invoice  total_summary_DB d-none">
						<p>ยอดรวม DB ทั้งหมด</p>
						<p class="ms-2 summary_DB">
							(<?php echo !empty($lists->total_summary->total_DB) ? number_format($lists->total_summary->total_DB, 2) : 0; ?>)
						</p>
					</div>

					<div class="total-invoice  total_summary_DE d-none">
						<p>ยอดรวม DE ทั้งหมด</p>
						<p class="ms-2 summary_DE">
							(<?php echo !empty($lists->total_summary->total_DE) ? number_format($lists->total_summary->total_DE, 2) : 0; ?>)
						</p>
					</div>
				<?php } ?>

				<div class="total-invoice">
					<p>ยอดหนี้คงเหลือทั้งหมด</p>
					<p class="ms-2 _allTotal">
						<?php echo !empty($lists->total_summary->total_balance) ? number_format($lists->total_summary->total_balance, 2) : 0; ?>
					</p>
				</div>

				<div class="border-bottom mb-3"></div>
			<?php } ?>

			<div class="d-flex justify-content-end mt-3">
				<button type="button" class="btn btn-primary cf_bill" disabled>ยืนยันบิล</button>
				<button class="btn btn-primary cf_bill-loading" type="button" disabled style="display: none;">
					<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
					โหลด...
				</button>
				<a type="button" class="btn btn-danger ms-2" href="<?php echo $http . '/invoice?dateSelect=' . $send . '&startDate=' . $start . '&endDate=' . $end . '&type=' . $type; ?>">ยกเลิก</a>
			</div>

			<div class="doctype-display mt-5">
				<?php if (!empty($doctypeLists)) {
					$i = 1;
					foreach ($doctypeLists as $key => $doctype) {
						if ($doctype->msort < 7) { ?>
							<span>
								<?php echo str_replace(' ', '&nbsp;', str_replace(':', ' = ', $doctype->type_display_th));
								echo $i == 6 ? '' : ', ';
								$i++; ?></span>
						<?php } ?>
					<?php } ?>
				<?php } ?>
			</div>

			<div class="doctype-display-2 mt-2">
				<?php if (!empty($doctypeLists)) {
					foreach ($doctypeLists as $key => $doctype) {
						if ($doctype->msort > 6) { ?>
							<span>
								<?php echo str_replace(' ', '&nbsp;', str_replace(':', ' = ', $doctype->type_display_th));
								echo $doctype->msort == 9 ? '' : ', ';
								$i++; ?></span>
						<?php } ?>
					<?php } ?>
				<?php } ?>
			</div>
		</form>
	</div>
</div>



<script>
	$(function() {
		var childLists = <?php echo !empty($lists) ? json_encode($lists) : '[]'; ?>;
		var user_type = '<?php echo !empty($this->CURUSER->user[0]->user_type) ? $this->CURUSER->user[0]->user_type : ''; ?>';

		let data = []

		if (childLists) {
			addData();
			checkDisable();
		}

		$('.invoice')
			.on('click', '.cf_bill', function(e) {
				e.preventDefault();
				let mainID = '<?php echo $main_id; ?>';
				// let mduedate = $(this).attr("data-mduedate");
				Swal.fire({
					title: 'คุณต้องการทำบิลใช่หรือไม่?',
					icon: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'ยืนยัน',
					cancelButtonText: 'ยกเลิก',
				}).then((result) => {
					if (result.isConfirmed) {
						readyProcess(true)
						let formData = $('.invoice').serializeArray();
						
						$.post('<?php echo $http ?>/invoice/create/' + mainID + '/' + '<?php echo $start ?>' + '/' + '<?php echo $end ?>' + '/' + '<?php echo $send ?>', formData).done(function(res) {
							if (res.status == 200 || res.status == 204) {
								let key = Object.keys(res.data['data'])
								let html = "";
								let userType = user_type == 'Emp' ? '<div class="text-danger mt-4 d-flex" style="font-size: 12px;"><span>** หมายเหตุ กรณีมีการขอแจ้งส่งใบแจ้งเตือนแยกอีกบริษัท ระบบจะส่งให้อัตโนมัติ</span></div>' : '';
								key.map(o => {
									let mail = res.data['data'][o].is_email ? '<span class="badge rounded-pill bg-primary mt-1"><i class="bi bi-envelope fs-6"></i></span>' : ''
									let fax = res.data['data'][o].is_fax ? '<span class="badge rounded-pill bg-success mt-1"><i class="bi bi-printer fs-6"></i></span>' : ''

									let pdf = '<a class="me-2 ms-2 mt-1 text-danger" href="<?php echo $http ?>/report/pdf/' + res.data['data'][o].uuid + '" target="_blank" id="report"><i class="bi bi-file-earmark-pdf fs-6"></i></a>'

									let excel = '<a type="button" class="text-success mt-1" href="<?php echo $http ?>/invoice/genExcel/' + res.data['data'][o].uuid + '" target="_blank" data-uuid="' + res.data['data'][o].uuid + '" id="excel"><i class="bi bi-file-earmark-excel fs-6"></i></a>'

									html += '<div class="d-flex mb-1"><div>' + mail + fax + '</div><span class="ms-2 mt-1 text-start">' + res.data['data'][o].cus_name + ' (' + res.data['data'][o].cus_no + ')</span>' + pdf + excel + '</div>'
								})

								Swal.fire({
									title: "ใบแจ้งเตือน",
									html: html + userType,
									position: 'top',
									confirmButtonText: 'ตกลง'
								}).then((result) => {
									let key = Object.keys(res.data['data'])
									key.forEach(o => {
										window.open('<?php echo WWW; ?><?php echo $http ?>/report/pdf/' + res.data['data'][o].uuid, '_blank', o)
									})

									setTimeout(function() {
										window.location = '<?php echo $http; ?>/report';
									}, 2500);
								})

								$.post('<?php echo $http ?>/api/addMainLog/create', {
									page: 'สร้างใบแจ้งเตือน',
									url: CURRENT_URL,
									detail: JSON.stringify({
										data: res.data['data']
									}),
									source: JSON.stringify(res.source)
								});
							} else {
								if (res.error) {
									Swal.fire("Error", res.error, "error");
								} else {
									Swal.fire("Error", 'Something went wrong', "error");
								}
							}
							readyProcess();
						});
					}
				})

			}).on('change', '.select_invoice', function(e) {
				let value = $(this).val();
				let genVal = $(this).val().split('|');
				let check = $(this);
				let checkTotal = check.parents('.invoice-list');
				let am_total = checkTotal.find('.am_total').text();
				let type = checkTotal.find('.mdoctype-' + genVal[0]).text();
				let key = checkTotal.find('.key-id').val();
				let cf_mnetamt = checkTotal.find('.cf-mnetamt-' + genVal[0]).text();

				if ($(this).is(":checked")) {
					let invoice = {
						'id': key,
						'value': genVal[0],
						'balance': parseFloat(cf_mnetamt.trim()),
						'RA': type.trim() == 'RA' ? parseFloat(cf_mnetamt.trim()) : 0,
						'RD': type.trim() == 'RD' ? parseFloat(cf_mnetamt.trim()) : 0,
						'DC': type.trim() == 'DC' ? parseFloat(cf_mnetamt.trim()) : 0,
						'RB': type.trim() == 'RB' ? parseFloat(cf_mnetamt.trim()) : 0,
						'RC': type.trim() == 'RC' ? parseFloat(cf_mnetamt.trim()) : 0,
						'RE': type.trim() == 'RE' ? parseFloat(cf_mnetamt.trim()) : 0,
						'DA': type.trim() == 'DA' ? parseFloat(cf_mnetamt.trim()) : 0,
						'DB': type.trim() == 'DB' ? parseFloat(cf_mnetamt.trim()) : 0,
						'DE': type.trim() == 'DE' ? parseFloat(cf_mnetamt.trim()) : 0,
						'mdoctype': type.trim()
					}

					let checkData = data.filter(o => genVal[0] == o.value && key == o.id)
					if (checkData.length < 1) {
						data.push(invoice)
					}

					checkDisable();
					let summary = calculate(key);
					let sendTotal = (type.trim() == 'DC' ? summary.DC : type.trim() == 'RB' ? summary.RB : type.trim() == 'RC' ? summary.RC : type.trim() == 'RA' ? summary.RA : type.trim() == 'RD' ? summary.RD : type.trim() == 'RE' ? summary.RE : type.trim() == 'DA' ? summary.DA : type.trim() == 'DB' ? summary.DB : summary.DE)

					checkTotal.find('.total-text-' + key).text(addComma(summary.total, 2))
					let text = ['RA', 'RD'].includes(type.trim()) ? addComma(sendTotal, 2) : '(' + addComma(sendTotal, 2) + ')'

					checkTotal.find('.' + type.trim() + '-text-' + key).text(text)
					let find = checkTotal.find('._sum_' + type.trim() + '-' + key)
					let total_summary = calculate(false);
					let total_sendTo = (type.trim() == 'DC' ? total_summary.DC : type.trim() == 'RB' ? total_summary.RB : type.trim() == 'RC' ? total_summary.RC : type.trim() == 'RA' ? total_summary.RA : type.trim() == 'RD' ? total_summary.RD : type.trim() == 'RE' ? total_summary.RE : type.trim() == 'DA' ? total_summary.DA : type.trim() == 'DB' ? total_summary.DB : total_summary.DE)

					let sum_text = ['RA', 'RD'].includes(type.trim()) ? addComma(total_sendTo, 2) : '(' + addComma(total_sendTo, 2) + ')'

					$('.summary_' + type.trim()).text(sum_text)
					$('._allTotal').text(addComma(total_summary.total, 2))
					displayCal(sendTotal, find)
					displayCal(total_sendTo, $('.total_summary_' + type.trim()))


				} else {
					let index = data.findIndex(o => genVal[0] == o.value && key == o.id)
					data.splice(index, 1);
					checkDisable();
					let summary = calculate(key)
					let sendTotal = (type.trim() == 'DC' ? summary.DC : type.trim() == 'RB' ? summary.RB : type.trim() == 'RC' ? summary.RC : type.trim() == 'RA' ? summary.RA : type.trim() == 'RD' ? summary.RD : summary.RE)

					checkTotal.find('.total-text-' + key).text(addComma(summary.total, 2))

					let text = ['RA', 'RD'].includes(type.trim()) ? addComma(sendTotal, 2) : '(' + addComma(sendTotal, 2) + ')'

					checkTotal.find('.' + type.trim() + '-text-' + key).text(text)
					checkTotal.find('.cf-all-' + key).prop('checked', false);
					let find = checkTotal.find('._sum_' + type.trim() + '-' + key)
					displayCal(sendTotal, find)

					let total_find = $('.summary_' + type.trim())

					let total_summary = calculate(false);
					let total_sendTo = (type.trim() == 'DC' ? total_summary.DC : type.trim() == 'RB' ? total_summary.RB : type.trim() == 'RC' ? total_summary.RC : type.trim() == 'RA' ? total_summary.RA : type.trim() == 'RD' ? total_summary.RD : type.trim() == 'RE' ? total_summary.RE : type.trim() == 'DA' ? total_summary.DA : type.trim() == 'DB' ? total_summary.DB : total_summary.DE)

					let sum_text = ['RA', 'RD'].includes(type.trim()) ? addComma(total_sendTo, 2) : '(' + addComma(total_sendTo, 2) + ')'
					$('._allTotal').text(addComma(total_summary.total, 2))
					$('.summary_' + type.trim()).text(sum_text)
					displayCal(total_sendTo, $('.total_summary_' + type.trim()))
				}

			}).on('change', '.check_all', function(e) {
				let value = $(this).val();
				let check = $(this);
				let checkTotal = check.parents('.invoice-list');
				let am_total = checkTotal.find('.am_total').text().trim() ? parseFloat(checkTotal.find('.am_total').text().trim()) : 0;
				if ($(this).is(":checked")) {
					$('.check-' + value).prop('checked', true);
					childLists.childs[value].bills.map(o => {
						let invoice = {
							'id': value,
							'value': o.macctdoc,
							'balance': parseFloat(o.mnetamt),
							'RA': o.mdoctype == 'RA' ? parseFloat(o.mnetamt) : 0,
							'RD': o.mdoctype == 'RD' ? parseFloat(o.mnetamt) : 0,
							'DC': o.mdoctype == 'DC' ? parseFloat(o.mnetamt) : 0,
							'RB': o.mdoctype == 'RB' ? parseFloat(o.mnetamt) : 0,
							'RC': o.mdoctype == 'RC' ? parseFloat(o.mnetamt) : 0,
							'RE': o.mdoctype == 'RE' ? parseFloat(o.mnetamt) : 0,
							'DA': o.mdoctype == 'DA' ? parseFloat(o.mnetamt) : 0,
							'DB': o.mdoctype == 'DB' ? parseFloat(o.mnetamt) : 0,
							'DE': o.mdoctype == 'DE' ? parseFloat(o.mnetamt) : 0,
							'mdoctype': o.mdoctype
						}

						let checkData = data.filter(x => x.value == o.macctdoc && x.id == childLists
							.childs[value].info.cus_no)
						if (checkData.length < 1) {
							data.push(invoice)
						}
						checkDisable();
						let summary = calculate(childLists.childs[value].info.cus_no);

						let sendTotal = (o.mdoctype == 'DC' ? summary.DC : o.mdoctype == 'RB' ? summary.RB : o.mdoctype == 'RC' ? summary.RC : o.mdoctype == 'RA' ? summary.RA : o.mdoctype == 'RD' ? summary.RD : o.mdoctype == 'RE' ? summary.RE : o.mdoctype == 'DA' ? summary.DA : o.mdoctype == 'DB' ? summary.DB : summary.DE)

						checkTotal.find('.total-text-' + childLists.childs[value].info.cus_no).text(
							addComma(summary.total, 2))

						let text = ['RA', 'RD'].includes(o.mdoctype) ? addComma(sendTotal, 2) : '(' + addComma(sendTotal, 2) + ')'

						checkTotal.find('.' + o.mdoctype + '-text-' + childLists.childs[value].info.cus_no).text(text)

						let find = checkTotal.find('._sum_' + o.mdoctype + '-' + childLists.childs[value].info.cus_no)
						displayCal(sendTotal, find)

						let total_summary = calculate(false);
						let total_sendTo = (o.mdoctype == 'DC' ? total_summary.DC : o.mdoctype == 'RB' ? total_summary.RB : o.mdoctype == 'RC' ? total_summary.RC : o.mdoctype == 'RA' ? total_summary.RA : o.mdoctype == 'RD' ? total_summary.RD : o.mdoctype == 'RE' ? total_summary.RE : o.mdoctype == 'DA' ? total_summary.DA : o.mdoctype == 'DB' ? total_summary.DB : total_summary.DE)

						let sum_text = ['RA', 'RD'].includes(o.mdoctype) ? addComma(total_sendTo, 2) : '(' + addComma(total_sendTo, 2) + ')'
						$('.summary_' + o.mdoctype).text(sum_text)
						$('._allTotal').text(addComma(total_summary.total, 2))
						displayCal(total_sendTo, $('.total_summary_' + o.mdoctype))
					})
				} else {
					$('.check-' + value).prop('checked', false);
					childLists.childs[value].bills.map(o => {
						let index = data.findIndex(x => x.value == o.macctdoc && x.id == childLists
							.childs[value].info.cus_no)
						data.splice(index, 1);

						checkTotal.find('.total-text-' + childLists.childs[value].info.cus_no).text('0.00')
						checkDisable();
						let find = checkTotal.find('._sum_' + o.mdoctype + '-' + childLists.childs[value].info.cus_no)
						displayCal(0, find)

						let total_summary = calculate(false);
						let total_sendTo = (o.mdoctype == 'DC' ? total_summary.DC : o.mdoctype == 'RB' ? total_summary.RB : o.mdoctype == 'RC' ? total_summary.RC : o.mdoctype == 'RA' ? total_summary.RA : o.mdoctype == 'RD' ? total_summary.RD : o.mdoctype == 'RE' ? total_summary.RE : o.mdoctype == 'DA' ? total_summary.DA : o.mdoctype == 'DB' ? total_summary.DB : total_summary.DE)

						let sum_text = ['RA', 'RD'].includes(o.mdoctype) ? addComma(total_sendTo, 2) : '(' + addComma(total_sendTo, 2) + ')'
						$('.summary_' + o.mdoctype).text(sum_text)
						$('._allTotal').text(addComma(total_summary.total, 2))
						displayCal(total_sendTo, $('.total_summary_' + o.mdoctype))
					})
				}
			});


		function calculate(key) {
			let total = 0
			let RA = 0
			let RD = 0
			let DC = 0
			let RB = 0
			let RC = 0
			let RE = 0
			let DA = 0
			let DB = 0
			let DE = 0
			data.map(o => {
				if (key) {
					if (key == o.id) {
						if (o.mdoctype == 'RA') {
							total = total + o.balance
							RA += o.RA
						}
						if (o.mdoctype == 'RD') {
							total = total + o.balance
							RD += o.RD
						}
						if (o.mdoctype == 'DC') {
							total = total - o.balance
							DC += o.DC
						}
						if (o.mdoctype == 'RB') {
							total = total - o.balance
							RB += o.RB
						}
						if (o.mdoctype == 'RC') {
							total = total - o.balance
							RC += o.RC
						}
						if (o.mdoctype == 'RE') {
							total = total - o.balance
							RE += o.RE
						}
						if (o.mdoctype == 'DA') {
							total = total + o.balance
							DA += o.DA
						}
						if (o.mdoctype == 'DB') {
							total = total - o.balance
							DB += o.DB
						}
						if (o.mdoctype == 'DE') {
							total = total - o.balance
							DE += o.DE
						}
					}
				} else {
					if (o.mdoctype == 'RA') {
						total = total + o.balance
						RA += o.RA
					}
					if (o.mdoctype == 'RD') {
						total = total + o.balance
						RD += o.RD
					}
					if (o.mdoctype == 'DC') {
						total = total - o.balance
						DC += o.DC
					}
					if (o.mdoctype == 'RB') {
						total = total - o.balance
						RB += o.RB
					}
					if (o.mdoctype == 'RC') {
						total = total - o.balance
						RC += o.RC
					}
					if (o.mdoctype == 'RE') {
						total = total - o.balance
						RE += o.RE
					}
					if (o.mdoctype == 'DA') {
						total = total + o.balance
						DA += o.DA
					}
					if (o.mdoctype == 'DB') {
						total = total - o.balance
						DB += o.DB
					}
					if (o.mdoctype == 'DE') {
						total = total - o.balance
						DE += o.DE
					}
				}
			})
			return {
				'total': total,
				'RA': RA,
				'RD': RD,
				'DC': DC,
				'RB': RB,
				'RC': RC,
				'RE': RE,
				'DA': DA,
				'DB': DB,
				'DE': DE
			};
		}

		function displayCal(total, findText) {
			if (total > 0) {
				findText.addClass('d-flex').removeClass('d-none');
			} else {
				findText.addClass('d-none').removeClass('d-flex');
			}

		}


		function readyProcess(wait = false) {
			if (wait) {
				$('.cf_bill').hide();
				$('.cf_bill-loading').show();
			} else {
				$('.cf_bill').show();
				$('.cf_bill-loading').hide();
			}
		}

		function checkDisable() {
			if (data.length > 0) {
				$('.invoice .cf_bill').prop('disabled', false);
			} else {
				$('.invoice .cf_bill').prop('disabled', true);
			}
		}

		function addData() {
			if (childLists.childs) {
				let key = Object.keys(childLists.childs)
				key.map(o => {
					childLists.childs[o].bills.map(x => ['RA', 'RD'].includes(x.mdoctype) ? data.push({
						'id': o,
						'value': x.macctdoc,
						'balance': parseFloat(x.mnetamt),
						'RA': x.mdoctype == 'RA' ? parseFloat(x.mnetamt) : 0,
						'RD': x.mdoctype == 'RD' ? parseFloat(x.mnetamt) : 0,
						'DC': x.mdoctype == 'DC' ? parseFloat(x.mnetamt) : 0,
						'RB': x.mdoctype == 'RB' ? parseFloat(x.mnetamt) : 0,
						'RC': x.mdoctype == 'RC' ? parseFloat(x.mnetamt) : 0,
						'RE': x.mdoctype == 'RE' ? parseFloat(x.mnetamt) : 0,
						'DA': x.mdoctype == 'DA' ? parseFloat(x.mnetamt) : 0,
						'DB': x.mdoctype == 'DB' ? parseFloat(x.mnetamt) : 0,
						'DE': x.mdoctype == 'DE' ? parseFloat(x.mnetamt) : 0,
						'mdoctype': x.mdoctype
					}) : '')
				})
			}
		}
	});
</script>
