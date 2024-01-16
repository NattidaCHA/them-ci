<div class="container-fluid">
	<div class="section-customer bg-white rounded shadow rounded px-4 pt-3 pb-3">
		<form id="searchcForm" method="get" action="<?php echo $http ?>/customer/process/create" class="mb-4 <?php echo $action == 'create' ? 'd-block' : 'd-none' ?>">
			<div class="box-customer-search">
				<div class="input-search-2">
					<label for="customer" class="form-label">ลูกค้า</label>
					<div class="input-group mb-3">
						<select class="select2 form-select" name="customer" id="customer">
							<option value="" selected>เลือก ...</option>
							<!-- <?php //foreach ($customers as $customer) { 
									?>
								<option value="<?php //echo $customer->mcustno; 
												?>"><?php //echo $customer->cus_name . ' (' . $customer->mcustno . ')' 
													?></option>
							<?php  //} 
							?> -->
						</select>
					</div>
				</div>
				<div class="mb-3 mt-4 me-3">
					<button type="submit" class="btn btn-primary btn-search">ค้นหา</button>
				</div>
			</div>
		</form>

		<!-- <?php //if (!empty($loading)) {
				//echo '<span class="spinner-border spinner-border-lg mt-5 text-center <?php echo count((array)$info) > 0 
				?>" role="status"></span>';
        } ?> -->
		<span class="spinner-border spinner-border-lg mt-5 text-center <?php echo !empty($loading) && $action == 'create' ? 'd-flex' : 'd-none' ?>" role="status"></span>
		<?php if (!empty($info) && count((array)$info) > 0) { ?>
			<form id="customerForm" class="mb-4 <?php echo !empty($loading) ? 'd-none' : 'd-flex' ?>">
				<div class="customer-section">
					<div class="customer-page">
						<div class="bg-customer">
							<h4 class="mb-3 text-muted">ข้อมูลลูกค้า</h4>
							<div class="mb-3">
								<input class="form-check-input  is_email-<?php echo  $info->cus_no;
																			?> uncheck-is_email" type="checkbox" value="1" id="is_email" name="is_email" <?php echo (!empty($info->is_email) ? 'checked' : ''); ?> autocomplete="off">
								<label class="form-check-label" for="is_email">ส่งอีเมล</label>

								<input class="form-check-input ms-2 is_fax-<?php echo  $info->cus_no;
																			?> uncheck-is_fax" type="checkbox" value="1" id="is_fax" name="is_fax" <?php echo (!empty($info->is_fax) ? 'checked' : ''); ?> autocomplete="off">
								<label class="form-check-label" for="is_fax">ส่ง Fax</label>

							</div>
							<div class="form-customer">
								<div class="mb-3">
									<label for="exampleFormControlInput1" class="form-label">บริษัท</label>
									<input type="hidden" id="cus_name" name="cus_name" value="<?php echo $info->cus_name; ?>" autocomplete="off">
									<input type="hidden" id="type" name="type" value="<?php echo $type; ?>" autocomplete="off">
									<input type="hidden" id="cus_no" name="cus_no" value="<?php echo $info->cus_no; ?>" autocomplete="off">
									<?php if ($action == 'update') { ?>
										<input type="hidden" id="id" name="id" value="<?php echo $info->uuid; ?>" autocomplete="off">
									<?php  } ?>
									<input type="text" class="form-control input-cus" id="company" name="company" placeholder="บริษัท" value="<?php echo $info->cus_name . '(' . $info->cus_no . ')'; ?>" autocomplete="off" disabled>
								</div>
								<div class="mb-3">
									<label for="exampleFormControlInput1" class="form-label">วันที่ต้องการแจ้ง</label>
									<select class="form-select input-cus" id="send_date" name="send_date" required>
										<option value="">เลือก ...</option>
										<?php foreach ($days as $day) { ?>
											<option value="<?php echo $day->id; ?>" <?php echo $send_date == $day->id ? 'selected' : ''; ?>><?php echo $day->name ?></option>
										<?php  } ?>
									</select>
								</div>
							</div>
							<div class="boder-under"></div>
							<h4 class="mb-3 text-muted">บริษัทที่เกี่ยวข้อง</h4>
							<div class="checkbox-cus">
								<?php foreach ($select_customer as $k => $customer) { ?>
									<div class="form-check">
										<?php if ($action == 'update') { ?>
											<p class="d-none uncheck-<?php echo  $customer->cus_no; ?>"><?php echo (!empty($isCheck[$customer->cus_no]) && $isCheck[$customer->cus_no]->is_check == 1 && !empty($isCheck[$customer->cus_no]->uuid)) ? trim($isCheck[$customer->cus_no]->uuid) : ''; ?> </p>
										<?php  } ?>
										<?php if (empty($customer->type)) { ?>
											<input class="form-check-input  cf-<?php echo  $customer->cus_no; ?> uncheck" type="checkbox" value="<?php echo $customer->cus_no; ?>" id="sendto" name="sendto[]" <?php echo $action == 'create' ? 'checked' : (!empty($isCheck[$customer->cus_no]) && $isCheck[$customer->cus_no]->is_check == 1 && $action == 'update' ? 'checked' : '') ?> autocomplete="off">
										<?php } ?>
										<label class="form-check-label" for="sendto">
											<?php echo $customer->cus_name . '(' . $customer->cus_no . ')'; ?>
										</label>
									</div>
								<?php } ?>
							</div>
						</div>
						<div class="boder-right"></div>
						<div class="bg-contact">
							<div class="contact-header">
								<h4 class="text-muted">ข้อมูลติดต่อ</h4>
								<div class="d-flex">
									<button type="button" class="btn btn-success btn-sm add-email me-1">+ เพิ่มอีเมล</button>
									<button type="button" class="btn btn-gray-700 btn-sm add-tel me-1">+ เพิ่มเบอร์โทร</button>
									<button type="button" class="btn btn-primary btn-sm add-fax">+ เพิ่ม Fax</button>
								</div>
							</div>
							<div class="border-bottom mb-3"></div>
							<div class="mb-4 contactForm">
								<div class="row">
									<div class="col-12">
										<nav>
											<div class="nav nav-tabs mb-4" id="nav-tab" role="tablist">
												<a class="nav-item nav-link active" id="nav-email-tab" data-bs-toggle="tab" href="#nav-email" role="tab" aria-controls="nav-email" aria-selected="true">อีเมล</a>
												<a class="nav-item nav-link" id="nav-tel-tab" data-bs-toggle="tab" href="#nav-tel" role="tab" aria-controls="nav-tel" aria-selected="false">เบอร์โทร</a>
												<a class="nav-item nav-link" id="nav-fax-tab" data-bs-toggle="tab" href="#nav-fax" role="tab" aria-controls="nav-fax" aria-selected="false">Fax</a>
											</div>
										</nav>
										<div class="tab-content" id="nav-tabContent">
											<div class="tab-pane fade show active" id="nav-email" role="tabpanel" aria-labelledby="nav-email-tab">
												<?php if (!empty($emails)) {
													foreach ($emails as $val) { ?>
														<div class="contact-email px-3">
															<div class="contact-list">
																<div class="form-contact">
																	<label for="email" class="form-label mt-2">อีเมล</label>
																	<input type="hidden" id="uuid_email" name="uuid_email[]" value="<?php echo  !empty($val) && !empty($val->uuid) ? $val->uuid : ''; ?>" autocomplete="off">
																	<input type="text" class="form-control input-form email-contact" id="email" name="email[]" placeholder="อีเมล" autocomplete="off" value="<?php echo !empty($val->email) && !empty($val) ? $val->email : ''; ?>">
																	<div class="text-end mt-2"><a type="button" href="javascript:void(0)"><i class="bi bi-trash text-danger delete"></i></a></div>
																</div>
																<div class="text-danger mb-3 modal-contact-contact-errors"></div>
																<div class="border-bottom mt-3 mb-3"></div>
															</div>
														</div>
													<?php }; ?>
												<?php } else {
													echo '<p class="noData-email text-center mt-3 mb-3">ไม่มีข้อมูลติดต่อ</p>';
												}; ?>
											</div>
											<div class="tab-pane fade" id="nav-tel" role="tabpanel" aria-labelledby="nav-tel-tab">
												<?php if (!empty($tels)) {
													foreach ($tels as $val) { ?>
														<div class="contact-tel px-3">
															<div class="d-flex justify-content-between">
																<div class="form-check">
																	<input class="form-check-input is_call" value="1" type="checkbox" id="is_call" name="is_call[]" <?php echo !empty($val->is_call) && $val->is_call  == 1 ? 'checked' : '' ?>>
																	<label class="form-check-label" for="is_call">
																		โทรแจ้ง
																	</label>
																</div>
																<div class="text-end"><a type="button" href="javascript:void(0)"><i class="bi bi-trash text-danger delete"></i></a></div>
															</div>
															<div class="contact-list">
																<div class="form-contact">
																	<label for="tel" class="form-label mt-2">เบอร์โทร</label>
																	<input type="hidden" id="uuid_tel" name="uuid_tel[]" value="<?php echo  !empty($val) && !empty($val->uuid) ? $val->uuid : ''; ?>" autocomplete="off">
																	<input type="text" class="form-control input-form tel-contact" id="tel" name="tel[]" value="<?php echo !empty($val->tel) && !empty($val) ? $val->tel : ''; ?>" placeholder="เบอร์โทร" autocomplete="off">
																</div>
																<div class="form-contact">
																	<label for="tel" class="form-label mt-2 me-5">ผู้ติดต่อ</label>
																	<input type="text" class="form-control input-form contact" id="contact" name="contact[]" value="<?php echo !empty($val->contact) && !empty($val) && $val->contact != '-' ? $val->contact : ''; ?>" placeholder="ผู้ติดต่อ" autocomplete="off">
																</div>
																<div class="text-danger mb-3 modal-contact-contact-errors"></div>
																<div class="border-bottom mt-3 mb-3"></div>
															</div>
														</div>
													<?php } ?>
												<?php } else {
													echo ' <p class="noData-tel text-center mt-3 mb-3">ไม่มีข้อมูลติดต่อ</p>';
												} ?>
											</div>
											<div class="tab-pane fade" id="nav-fax" role="tabpanel" aria-labelledby="nav-fax-tab">
												<?php if (!empty($faxs)) {
													foreach ($faxs as $val) { ?>
														<div class="contact-fax px-3">
															<div class="contact-list">
																<div class="form-contact">
																	<label for="email" class="form-label mt-2">Fax</label>
																	<input type="hidden" id="uuid_fax" name="uuid_fax[]" value="<?php echo  !empty($val) && !empty($val->uuid) ? $val->uuid : ''; ?>" autocomplete="off">
																	<input type="text" class="form-control input-form email-contact" id="fax" name="fax[]" placeholder="Fax" autocomplete="off" value="<?php echo !empty($val->fax) && !empty($val) ? $val->fax : ''; ?>">
																	<div class="text-end mt-2"><a type="button" href="javascript:void(0)"><i class="bi bi-trash text-danger delete"></i></a></div>
																</div>
																<div class="text-danger mb-3 modal-contact-contact-errors"></div>
																<div class="border-bottom mt-3 mb-3"></div>
															</div>
														</div>
													<?php } ?>
												<?php } else {
													echo ' <p class="noData-fax text-center mt-3 mb-3">ไม่มีข้อมูลติดต่อ</p>';
												} ?>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="d-flex justify-content-end align-items-end mb-2 mt-5">
						<button class="btn btn-primary loading" type="button" disabled style="display: none;">
							<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
							โหลด...
						</button>
						<button type="button" class="btn btn-primary submit">ยืนยัน</button>
					</div>
				</div>
			</form>
		<?php }; ?>
	</div>
</div>
<script>
	$(function() {
		let formCreateClient = $('#customerForm').parsley();
		let uncheckChild = [];
		let uncheckChildId = [];
		let type = '<?php echo $action ?>';
		let checkValidate = false;

		// $('.select2').select2({
		// 	theme: "bootstrap-5"
		// });

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

		$('#customerForm').on('click', '.submit', function(e) {
			e.preventDefault();
			readyProcess(true)
			if (formCreateClient.validate() === true && checkValidate == false) {
				let formData = $('#customerForm').serializeArray();
				let cusNo = '<?php echo !empty($info->cus_no) ? $info->cus_no : ""; ?>'
				if (type == 'update' && uncheckChild.length > 0) {
					uncheckChild.map(o => formData.push({
						name: 'noCheckChild[]',
						value: o
					}))


					uncheckChildId.map(o => formData.push({
						name: 'noCheckChildId[]',
						value: o
					}))

				}
				$.post("<?php echo $http ?>/customer/action/" + type, formData).done(function(res) {
					if (res.status === 200) {
						Swal.fire({
							icon: 'success',
							text: '<?php echo $action == 'create' ? 'สร้าง' : 'อัปเดต' ?>ข้อมูลลูกค้าเรียบร้อย',
							confirmButtonText: 'ตกลง'
						}).then((result) => {
							if (result.isConfirmed) {
								window.location = '<?php echo $http ?>/customer/process/update?customer=' + cusNo;

								$.post('<?php echo $http ?>/api/addMainLog/<?php echo $action; ?>', {
									page: '<?php echo $action == 'create' ? 'สร้าง' : 'อัปเดต' ?>ข้อมูลลูกค้า',
									url: CURRENT_URL,
									detail: JSON.stringify(res.data),
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
					readyProcess();
				});

			} else {
				readyProcess()
			}
		}).on('change', '.uncheck', function(e) {
			let uncheck = $(this).val()
			let uuid = $('.uncheck-' + uncheck).text().trim()
			if (uuid) {
				if ($(this).is(":checked")) {
					let index = uncheckChild.findIndex(o => o == uuid);
					uncheckChild.splice(index, 1);
					let id = uncheckChildId.findIndex(x => uncheck == x);
					uncheckChildId.splice(index, 1);
				} else {
					uncheckChild.push(uuid)
					uncheckChildId.push(uncheck)
				}
			}

		});


		$('#nav-email').on('click', '.delete', function(e) {
			Swal.fire({
				title: 'คุณต้องการลบอีเมลใช่หรือไม่?',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'ยืนยัน',
				cancelButtonText: 'ยกเลิก',
			}).then((result) => {
				if (result.isConfirmed) {
					let remove = $(this).parents('.contact-email')
					let id = remove.find($('input[name="uuid_email[]"]'))
					if ('<?php echo $action ?>' == 'update' && id.val() != '1') {
						$.post("<?php echo $http ?>/customer/removeEmail/" + id.val()).done(function(res) {
							if (res.status === 200) {
								Swal.fire({
									icon: 'success',
									text: 'ลบอีเมลเรียบร้อยแล้ว',
									confirmButtonText: 'ตกลง'
								}).then((result) => {
									// if (result.isConfirmed) {
									if ($('.contact-email').length > 1) {
										remove.remove();
									} else {
										$('#nav-email').html('<p class="text-center noData-email mt-3">ไม่มีข้อมูลติดต่อ</p>')
									}

									$.post('<?php echo $http ?>/api/addMainLog/update', {
										page: 'ลบอีเมล',
										url: CURRENT_URL,
										detail: res.data,
									});
									// }
								})
							} else {
								if (res.error) {
									Swal.fire("Error", res.error, "error");
								} else {
									Swal.fire("Error", 'Something went wrong', "error");
								}
							}
						});
					} else {
						if ($('.contact-email').length > 1) {
							remove.remove()
							if ($('.contact-email').length > 7) {
								$('#nav-email').addClass('tab-email')
							} else {
								$('#nav-email').removeClass('tab-email')
							}
						} else {
							$('#nav-email').html('<p class="text-center noData-email mt-3">ไม่มีข้อมูลติดต่อ</p>')
						}
					}
				}
			});

		});

		$('#nav-tel').on('click', '.delete', function(e) {
			Swal.fire({
				title: 'คุณต้องการลบข้อมูลการติดต่อใช่หรือไม่?',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'ยืนยัน',
				cancelButtonText: 'ยกเลิก',
			}).then((result) => {
				if (result.isConfirmed) {
					let remove = $(this).parents('.contact-tel')
					let id = remove.find($('input[name="uuid_tel[]"]'))
					if ('<?php echo $action ?>' == 'update' && id.val() != '1') {
						$.post("<?php echo $http ?>/customer/removeTel/" + id.val()).done(function(res) {
							if (res.status === 200) {
								Swal.fire({
									icon: 'success',
									text: 'ลบข้อมูลติดต่อเรียบร้อยแล้ว',
									confirmButtonText: 'ตกลง'
								}).then((result) => {
									// if (result.isConfirmed) {
									if ($('.contact-tel').length > 1) {
										remove.remove();
									} else {
										$('#nav-tel').html('<p class="text-center noData-tel mt-3">ไม่มีข้อมูลติดต่อ</p>')
									}


									$.post('<?php echo $http ?>/api/addMainLog/update', {
										page: 'ลบเบอร์โทร',
										url: CURRENT_URL,
										detail: res.data,
									});
									// }
								})
							} else {
								if (res.error) {
									Swal.fire("Error", res.error, "error");
								} else {
									Swal.fire("Error", 'Something went wrong', "error");
								}
							}
						});
					} else {
						if ($('.contact-tel').length > 1) {
							remove.remove()
							if ($('.contact-tel').length > 7) {
								$('#nav-tel').addClass('tab-tel')
							} else {
								$('#nav-tel').removeClass('tab-tel')
							}
						} else {
							$('#nav-tel').html('<p class="text-center noData-tel mt-3">ไม่มีข้อมูลติดต่อ</p>')
						}
					}
				}
			});
		});
		$('#nav-fax').on('click', '.delete', function(e) {
			Swal.fire({
				title: 'คุณต้องการลบข้อมูล Fax ใช่หรือไม่?',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'ยืนยัน',
				cancelButtonText: 'ยกเลิก',
			}).then((result) => {
				if (result.isConfirmed) {
					let remove = $(this).parents('.contact-fax')
					let id = remove.find($('input[name="uuid_fax[]"]'))
					if ('<?php echo $action ?>' == 'update' && id.val() != '1') {
						$.post("<?php echo $http ?>/customer/removeFax/" + id.val()).done(function(res) {
							if (res.status === 200) {
								Swal.fire({
									icon: 'success',
									text: 'ลบข้อมูล Fax เรียบร้อยแล้ว',
									confirmButtonText: 'ตกลง'
								}).then((result) => {
									// if (result.isConfirmed) {
									if ($('.contact-fax').length > 1) {
										remove.remove();
									} else {
										$('#nav-fax').html('<p class="text-center noData-fax mt-3">ไม่มีข้อมูลติดต่อ</p>')
									}

									$.post('<?php echo $http ?>/api/addMainLog/update', {
										page: 'ลบ Fax',
										url: CURRENT_URL,
										detail: res.data,
									});
									// }
								})
							} else {
								if (res.error) {
									Swal.fire("Error", res.error, "error");
								} else {
									Swal.fire("Error", 'Something went wrong', "error");
								}
							}
						});
					} else {
						if ($('.contact-fax').length > 1) {
							remove.remove()
							if ($('.contact-fax').length > 7) {
								$('#nav-fax').addClass('tab-fax')
							} else {
								$('#nav-fax').removeClass('tab-fax')
							}
						} else {
							$('#nav-fax').html('<p class="text-center noData-fax mt-3">ไม่มีข้อมูลติดต่อ</p>')
						}
					}
				}
			});

		});

		$('#nav-tel').on('change', '.contact-tel,input[name="tel[]"]', function(e) {
			let val = $(this).val()
			let text = $(this).parents('.contact-list')
			let alert = text.find('.contact-tel,.modal-contact-contact-errors')
			let check = validate('tel', val)
			if (check) {
				alert.text('เบอร์โทรต้องน้อยกว่า 50 หลัก และเฉพาะตัวเลข หรืออักขระพิเศษ (+ . - # / , ( )) เท่านั้น')
			}
		}).on('keyup', '.contact-tel,input[name="tel[]"]', function(e) {
			let text = $(this).parents('.contact-list')
			let alert = text.find('.contact-tel,.modal-contact-contact-errors')
			alert.text('');
			checkValidate = false

		})

		$('#nav-fax').on('change', '.contact-fax,input[name="fax[]"]', function(e) {
			let val = $(this).val()
			console.log(val)
			let text = $(this).parents('.contact-list')
			let alert = text.find('.contact-fax,.modal-contact-contact-errors')
			let check = validate('tel', val)
			if (check) {
				alert.text('Fax ต้องน้อยกว่า 50 หลัก และเฉพาะตัวเลข หรืออักขระพิเศษ (+ . - # / , ( )) เท่านั้น')
			}
		}).on('keyup', '.contact-fax,input[name="fax[]"]', function(e) {
			let text = $(this).parents('.contact-list')
			let alert = text.find('.contact-fax,.modal-contact-contact-errors')
			alert.text('');
			checkValidate = false

		})


		$('#nav-email').on('change', '.contact-email,input[name="email[]"]', function(e) {
			let val = $(this).val();
			let text = $(this).parents('.contact-list')
			let alert = text.find('.modal-contact-contact-errors')
			let check = validate('email', val)
			if (check) {
				alert.text('กรุณากรอกให้ถูกหลักของอีเมล อนุญาติอักขระพิเศษ (@ . - _) เท่านั้น')
			}
		}).on('keyup', '.contact-email,input[name="email[]"]', function(e) {
			let text = $(this).parents('.contact-list')
			let alert = text.find('.modal-contact-contact-errors')
			alert.text('');
			checkValidate = false
		});

		$('.add-email').on('click', function(e) {
			let html = '<div class="contact-email px-3"><div class="contact-list"><div class="form-contact">' +
				'<label for="email" class="form-label mt-2">อีเมล</label><input type="hidden" id="uuid_email" name="uuid_email[]" value="1"  autocomplete="off">' +
				'<input type="text" class="form-control input-form" id="email" name="email[]" placeholder="อีเมล" autocomplete="off"><div class="text-end mt-2 delete"><a type="button" href="javascript:void(0)"><i class="bi bi-trash text-danger"></i></a></div></div>' +
				'<div class="text-danger mb-3 modal-contact-contact-errors"></div><div class="border-bottom mt-3 mb-3"></div></div></div>'

			if ($('#nav-email').length <= 1) {
				$('#nav-email .noData-email').remove()
			}

			if ($('.contact-email').length > 7) {
				$('#nav-email').addClass('tab-email')
			} else {
				$('#nav-email').removeClass('tab-email')
			}

			$('#nav-email').append(html)

		});

		$('.add-tel').on('click', function(e) {
			let html = '<div class="contact-tel px-3"><div class="d-flex justify-content-between">' +
				'<div class="form-check"> <input class="form-check-input is_call" value="1" type="checkbox" id="is_call" name="is_call[]">' +
				'<label class="form-check-label" for="is_call">โทรแจ้ง</label></div><div class="text-end">' +
				'<a type="button" href="javascript:void(0)"><i class="bi bi-trash text-danger delete"></i></a></div>' +
				'</div><div class="contact-list"><div class="form-contact">' +
				'<label for="tel" class="form-label mt-2">เบอร์โทร</label><input type="hidden" id="uuid_tel" name="uuid_tel[]" value="1"  autocomplete="off">' +
				'<input type="text" class="form-control input-form" id="tel" name="tel[]" placeholder="เบอร์โทร" autocomplete="off"></div>' +
				'<div class="form-contact"><label for="tel" class="form-label mt-2 me-5">ผู้ติดต่อ</label><input type="text" class="form-control input-form contact" id="contact" name="contact[]"  placeholder="ผู้ติดต่อ" autocomplete="off"></div></div>' +
				'<div class="text-danger mb-3 modal-contact-contact-errors"></div><div class="border-bottom mt-3 mb-3"></div></div>'

			if ($('#nav-tel .noData-tel').length <= 1) {
				$('#nav-tel .noData-tel').remove()
			}

			if ($('.contact-tel').length > 3) {
				$('#nav-tel').addClass('tab-tel')
			} else {
				$('#nav-tel').removeClass('tab-tel')
			}

			$('#nav-tel').append(html)

		});

		$('.add-fax').on('click', function(e) {
			let html = '<div class="contact-fax px-3"><div class="contact-list"><div class="form-contact">' +
				'<label for="fax" class="form-label mt-2">Fax</label><input type="hidden" id="uuid_fax" name="uuid_fax[]" value="1"  autocomplete="off">' +
				'<input type="text" class="form-control input-form" id="fax" name="fax[]" placeholder="Fax" autocomplete="off"><div class="text-end mt-2 delete"><a type="button" href="javascript:void(0)"><i class="bi bi-trash text-danger"></i></a></div></div>' +
				'<div class="text-danger mb-3 modal-contact-contact-errors"></div><div class="border-bottom mt-3 mb-3"></div></div></div>'

			if ($('#nav-fax').length <= 1) {
				$('#nav-fax .noData-fax').remove()
			}

			if ($('.contact-fax').length > 7) {
				$('#nav-fax').addClass('tab-fax')
			} else {
				$('#nav-fax').removeClass('tab-fax')
			}

			$('#nav-fax').append(html)

		});

		function readyProcess(wait = false) {
			if (wait) {
				$('.submit').hide();
				$('.loading').show();
			} else {
				$('.submit').show();
				$('.loading').hide();
			}
		}


		function validate(type, data) {
			if (type == 'tel') {
				let regx = /^\+?[-\/#0-9\(\)\.,\+\s]+$/i;
				if (data.length > 50 || (regx.test(data) == false && data.length > 0)) {
					console.log(8)
					checkValidate = true
					return true;
				}
			} else {
				let regx2 = /^[\-a-z0-9_\.,\s]+@[\-a-z0-9_\.,\s]+$/i;
				if (regx2.test(data) == false && data.length > 0) {
					checkValidate = true
					return true;
				}
			}
			return false;
		}


		function formatRepo(repo) {
			if (repo.loading) {
				return repo.text;
			}

			return $('<span>' + repo.cus_name + '(' + repo.cus_no + ')' + '</span>');
		}

		function formatRepoSelection(repo) {
			if (repo.id) {
				let show = '<?php echo $this->CURUSER->user[0]->user_type; ?>' == 'Emp' && repo.text == '' ? repo.cus_name + '(' + repo
					.cus_no + ')' : repo.text;
				return $('<span>' + show + '</span>');
			}
			return repo.text;
		}

	});
</script>
