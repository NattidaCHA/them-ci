<style>
	body {
		margin: 0;
		padding: 0;
		line-height: 1.3;
		font-size: 18px;
		font-weight: 400;
		color: #222222;
	}

	.pdf {
		height: 100%;
		/* margin: 2% 5% 2% 5%; */
		border: 2px solid #cdcdcd;
		display: flex;
		flex-direction: column;
	}

	.header {
		display: flex;
		flex-direction: row;
		padding: 5px 15px 0px 15px;
	}

	.half-10 {
		float: left;
		width: 12%;
	}

	.half-30 {
		float: left;
		width: 25%;
		/* margin-top: 2px; */
	}


	.logo {
		margin-right: 1%;
		width: 70px;
		/* margin-top: 5px; */
	}

	.logo-nawa {
		margin-right: 5%;
		width: 265px;
		/* height: 150px; */
	}

	.box-title {
		display: flex;
		flex-direction: column;
	}

	h2 {
		margin: 0;
		padding: 0;
	}


	.text-header {
		font-size: 18px;
		font-weight: bold !important;

	}

	.text-boder {
		font-size: 12px;
		font-weight: bold !important;
	}

	.border-bottom-header {
		border: 1px solid #cdcdcd;
		background-color: #cdcdcd;
	}

	.box-summary {
		padding: 5px 10px 0px 10px;
	}

	.detail-summary {
		padding-left: 30px;
		padding-right: 30px;
	}

	.row-col-subject {
		margin: 0;
		padding: 0;
	}

	.row-col-subject td {
		padding-right: 10px;
		padding-left: 0;
		vertical-align: top;
	}

	.row-col-subject td.subject {
		color: #777;
	}

	.text-right {
		text-align: right !important;
	}

	.text-center {
		text-align: center !important;
	}

	.text-left {
		text-align: left !important;
	}

	.text-gray {
		color: #999999;
	}

	.text-primary {
		color: #087df5;
	}

	.text-danger {
		color: #E11D48;
	}

	.text-success {
		color: #08b962;
	}

	.mt-1 {
		margin-top: 10px;
	}

	.mt-05 {
		margin-top: 5px;
	}

	.mt-2 {
		margin-top: 20px;
	}

	.mt-3 {
		margin-top: 30px;
	}

	h1,
	h2,
	h3,
	h4,
	h5 {
		margin: 0;
		padding: 0
	}

	.row-col-subject {
		margin: 0;
		padding: 0;
	}

	.row-col-subject td {
		padding-right: 10px;
		padding-left: 0;
		vertical-align: top;
	}

	.row-col-subject td.subject {
		color: #777;
	}

	.full-table {
		width: 100%;
		border-collapse: collapse;
		border-spacing: 0;
	}

	.full-table td,
	.full-table th {
		font-size: 14px;
	}


	.full-table tr .title {
		font-weight: bold;
		width: 85px;
		/* height: 13px; */
	}

	.full-table tr .des-bold {
		font-weight: bold;
		width: 320px;
		/* height: 13px */
	}

	.full-table tr .title-bill-no {
		width: 10px;
		font-weight: bold;
		/* height: 13px; */
	}


	.full-table tr .bill-no {
		font-weight: bold;
	}

	.full-table tr .des-bill-no {
		font-weight: bold;
		width: 124px;
		/* height: 13px; */
	}

	.full-table tr .des {
		width: 124px;
		font-weight: bold;
		/* height: 13px; */
	}

	.full-table tr .des-no {
		width: 80px;
		font-weight: bold;
		/* height: 13px; */
	}

	.full-table tr .date-send {
		width: 120px;
		font-weight: bold;
		/* height: 12px; */
	}

	.full-table tr .no {
		width: 320px;
		font-weight: bold;
		/* height: 12px; */
	}

	.full-table tr .tax {
		width: 50px;
	}

	.table-list {
		width: 100%;
		height: 55%;
	}


	.full-table-2 {
		width: 100%;
		border-collapse: collapse;
		border-spacing: 0;
		border-color: #dee2e6;
		font-size: 20px;
		font-weight: bold;
	}

	table.full-table-2 th {
		padding: 0;
		margin: 0;
		vertical-align: text-top;
	}

	table.full-table-2 th {
		border-top-width: 1px;
		border-top-color: #222;
		border-top-style: solid;
		border-bottom-width: 1px;
		border-bottom-color: #222;
		border-bottom-style: solid;
	}

	table.full-table-2 td {
		margin: 0;
		padding-top: 0;
		padding-bottom: 0px;
		vertical-align: text-top;
	}

	table.full-table-2 .amount {
		padding-right: 8px;
		text-align: right;
	}

	table.GeneratedTable {
		width: 90%;
		background-color: #ffffff;
		border-collapse: collapse;
		border-width: 1px;
		border-color: #cdcdcd;
		border-style: solid;
		color: gray;
		font-weight: bold;
		border-spacing: 0;
	}

	table.GeneratedTable td,
	table.GeneratedTable th {
		border-width: 2px;
		border-color: #cdcdcd;
		border-style: solid;
		padding: 1px;
	}

	table.GeneratedTable thead {
		background-color: #f3f3f2;
	}

	.text-total {
		margin: 0;
		padding: 0;
		font-weight: bold;
		text-align: center;
		font-size: 14px;
	}

	.border-bottom-dashed {
		border-bottom: 1px dashed #777;
		margin-bottom: 10px;
		margin-top: 10px;
	}


	.border-bottom-solid {
		border-bottom: 1px solid #777;
		margin-bottom: 10px;
		margin-top: 80px;
	}


	hr {
		text-align: right;
		width: 80%;
		height: 1px;
		color: #aaa;
		margin-top: 1px;
		margin-bottom: 0;
	}

	.border-bottom-sum {
		border-bottom: 2px solid #cdcdcd;
		margin-top: 45px;
		margin-bottom: 10px;
	}

	.payment {
		display: flex;
		flex-direction: row;
	}


	.half-50-left {
		float: left;
		width: 47%;
		margin-left: 15px;
	}

	.half-50-right {
		float: right;
		width: 48%;
		margin-top: 0;
		padding-top: 0;
	}

	.bank {
		margin: 0;
		padding: 0;
		font-size: 12px;
		font-weight: bold;
	}

	.boder-bank {
		border: 1px solid #222;
		height: 11%;
	}

	.logo-pay {
		width: 90px;
	}

	.logo-pay-nawa {
		width: 130px;
		/* margin-top: 5px; */
	}

	.half2-10 {
		float: left;
		width: 20%;
		margin-left: 25%;
	}

	.half2-30 {
		float: left;
		width: 40%;
		/* margin-top: 2px; */
	}

	.box-service {
		padding-top: 5px;
		padding-left: 5px;
	}

	.service {
		float: left;
		width: 48%;
		font-weight: bold;
	}

	.bbnpisb {
		float: right;
		width: 48%;
		font-weight: bold;
	}

	.box-border {
		margin-top: 3px;
		margin-bottom: 5px;
		border-bottom: 1px solid #222;
	}

	.pl-5 {
		padding-left: 5px;
		font-weight: bold;
		font-size: 13px;
	}

	.border-bottom {
		border-bottom: 1px dotted #cdcdcd;
	}

	.text-title-bank {
		margin: 0;
		padding: 0;
		font-weight: bold;
		font-size: 14px;
	}

	.logo-bank {
		width: 12px;
		/* margin-top: 10px; */
	}

	.pt-5 {
		padding-top: 5px;
	}

	.invoice-title {
		font-size: 16px;
		font-weight: bold;
		text-align: center;
	}

	.sumary-title {
		font-size: 20px;
		font-weight: bold;
		text-align: center;
		padding-left: 20px;
	}

	p {
		margin: 0;
		padding: 0;
	}

	.payment-table {
		width: 100%;
		border-collapse: collapse;
		border-spacing: 0;
		border-color: #222;
		font-size: 12px;
		border-width: 1px;
		border-color: #222;
		border-style: solid;
		/* font-weight: bold; */
	}

	table.payment-table td,
	table.payment-table th {
		padding: 0;
		margin: 0;
		border-width: 1px;
		border-color: #222;
		border-style: solid;
		padding: 1px;
	}

	.box-scan {
		padding: 5px 10px 0px 10px;
	}

	.text-scan {
		float: left;
		width: 28%;
		text-align: center;
		padding-top: 15px;
		font-weight: bold;
	}

	.qr-scan {
		float: left;
		width: 25%;
	}

	.bacode-scan {
		float: right;
		width: 34%;
	}

	.logo-qr-scan {
		width: 65%;
		height: 50%;
		padding-left: 15px;
		padding-right: 10px;
	}


	.logo-bacode-scan {
		padding-top: 15px;
		width: 100%;
		height: 20%;
	}

	.total-list {
		margin-left: 30px;
		margin-top: 2px;
		font-size: 16px;
	}

	.table-summary {
		margin-left: 60px;
		margin-top: 10px;
	}

	.text-amount {
		margin-top: 10px;
		font-weight: bold;
		margin-left: 29px;
		text-align: center;
		font-size: 16px;
	}

	.total-amount-in-worde {
		padding-left: 35px;
		font-weight: bold;
	}

	.text-detail-total {
		margin: 0;
		padding: 0;
		font-size: 12px;
		font-weight: bold;
		text-align: center;
	}

	.text-branch {
		font-weight: bold;
		text-align: center;
	}

	.text-bank-left {
		margin-left: 35px;
		/* margin-top: -15px; */
	}

	.text-bank-top {
		margin-top: -25px;
		margin-left: 35px;
	}

	.input-checkbox {
		/* margin-top: -25px; */
		width: 45px;
	}

	.footer {
		margin-top: 5px;
	}

	.boder-noti {
		padding-top: 5px;
		margin-top: 5px;
		border: 2px solid #66FFFF;
		width: 100%;
		height: 10px;
		text-align: center;
		font-size: 18px;
		font-weight: bold;
		margin-bottom: 5px;
	}

	.boder-bottom-red {
		border-bottom: 2px solid #66FFFF;
		margin-bottom: 5px;
	}

	.level1 {
		box-decoration-break: slice;
	}

	.detail-summary-2 {
		padding-left: 10px;
		padding-right: 20px;
	}

	.table-summary-2 {
		margin-left: 10px;
		margin-top: 5px;
	}

	table.GeneratedTable2 {
		width: 100%;
		background-color: #ffffff;
		border-collapse: collapse;
		border-width: 1px;
		border-color: #cdcdcd;
		border-style: solid;
		color: gray;
		font-weight: bold;
		border-spacing: 0;
		font-size: 16px;
	}

	table.GeneratedTable2 td,
	table.GeneratedTable2 th {
		border-width: 2px;
		border-color: #cdcdcd;
		border-style: solid;
		padding: 1px;
	}

	table.GeneratedTable2 thead {
		background-color: #f3f3f2;
	}

	.page-brack {
		height: 100%;
	}


	.boder-total-sum {
		border-top: 1px solid #cdcdcd;
		border-bottom: 1px solid #cdcdcd;
		font-weight: bold;
	}

	.total-sum-table {
		width: 100%;
		border-collapse: collapse;
		border-spacing: 0;
	}

	.total-sum td,
	.total-sum th {
		font-size: 19px;
		font-weight: bold;
	}

	.total-sum .title-sum {
		width: 600px;
	}

	.total-sum .sum {
		width: 330px;
		text-align: right;
	}

	.boder-total-sum2 {
		border: 2px solid #222;
		font-weight: bold;
	}

	.total-success-table {
		width: 100%;
		border-collapse: collapse;
		border-spacing: 0;
	}

	.total-success td,
	.total-success th {
		font-size: 19px;
		font-weight: bold;
	}

	.total-success .title-sum {
		width: 390px;
	}

	.total-success .sum {
		width: 290px;
		text-align: right;
	}

	.box-title {
		display: flex;
		flex-direction: column;
	}

	.text-page {
		font-size: 18px;
		font-weight: bold;
	}

	.half-page-10 {
		float: right;
		width: 12%;
	}

	.half-page-30 {
		float: left;
		width: 88%;
		/* margin-top: 2px; */
	}
</style>

<div class="pdf">
	<div class="content">
		<div class="box-title">
			<div class="half-page-30">
				<div class="sumary-title">สรุปรายการแจ้งยอดหนี้ชำระ</div>
			</div>
			<div class="half-page-10">
				<div class="text-page"><?php echo  'Page : ' . $data->index . '/' . $data->report->total_page; ?></div>
			</div>
		</div>
		<div class="page-brack">
			<div class="table-list">
				<table class="full-table-2">
					<thead>
						<tr>
							<th class="text-center" width="5%">ลําดับ</th>
							<th class="text-center" width="8%">ประเภท</th>
							<th class="text-center" width="12%">เลขใบแจ้งหนี้</th>
							<th class="text-center" width="15%">วันที่ออกเอกสาร</th>
							<th class="text-center" width="15%">วันครบกําหนดชําระ</th>
							<th class="text-center" width="8%">เงื่อนไข</th>
							<th class="amount" width="14%">จํานวนเงิน</th>
						</tr>

						<div style="border-bottom: 1px solid #777;"></div>
					</thead>
					<tbody>
						<?php if (!empty($data->report->lists)) {
							foreach ($data->report->lists as $key => $item) {
								if ($key !== 'total' && in_array($item->sortType, [1, 2, 3])) {
						?>
									<tr>
										<td class="text-center"><?php if ($data->index == 1) {
																	echo $key + 1;
																} else {
																	if ($data->index == 2) {
																		echo (($data->index - 1) * $data->size) + $key + 1;
																	} else {
																		echo (($data->index - 1) * $data->size) + $key + 2 - 10;
																	}
																}; ?></td>
										<td class="text-center"><?php echo  $item->type ?></td>
										<td class="text-center"><?php echo !empty($item->mbillno) ? $item->mbillno : '-'; ?></td>
										<td class="text-center"><?php echo  !empty($item->mdocdate) ?  date('d.m.Y', strtotime($item->mdocdate)) :  date('d.m.Y', strtotime($item->mpostdate)); ?></td>
										<td class="text-center"><?php echo date('d.m.Y', strtotime($item->mduedate)); ?></td>
										<td class="text-center"><?php echo $item->mpayterm; ?></td>
										<td class="amount"><?php echo !empty($item->mnetamt) ? number_format($item->mnetamt, 2) : 0; ?></td>
									</tr>
						<?php
								}
							}
						} ?>
					</tbody>
				</table>

				<?php if ($data->report->total_page == $data->index) { ?>
					<?php if (!empty($data->report->total->total_debit)) { ?>
						<div class="<?php echo !empty($data->report->total->total_credit) ? 'boder-total-sum' : 'boder-total-sum2' ?>">
							<table class="total-sum">
								<tbody>
									<tr>
										<td class="title-sum  text-left"><?php echo !empty($data->report->total->total_credit) ? 'มูลค่ารวมทั้งสิ้น (บาท)' : 'ยอดที่ต้องชำระ (บาท)' ?></td>
										<td class="text-right">
											<?php if (!empty($data->report->total->total_credit)) { ?>
												<?php echo !empty($data->report->total->total_debit) ? number_format($data->report->total->total_debit, 2) : 0 ?>
											<?php } else { ?>
												<u><?php echo !empty($data->report->total->total_debit) ? number_format($data->report->total->total_debit, 2) : 0 ?>
												<?php } ?></u>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					<?php } ?>
					<?php if (!empty($data->report->total->total_credit)) { ?>
						<div class="boder-success-sum">
							<table class="total-success">
								<tbody>
									<?php if (!empty($data->report->lists)) {
										foreach ($data->report->lists as $key => $item) {
											if ($key !== 'total' && !in_array($item->sortType, [1, 2, 3])) {
									?>
												<tr>
													<?php if (!in_array($item->sortType, [2, 4])) { ?>
														<td class="title-sum  text-center"><?php echo !empty($item->mtext) ? $item->mtext : '-' ?></td>
													<?php } else { ?>
														<td class="title-sum  text-center"><?php echo !empty($item->mtext) ? $data->doctypeLists[$item->type]->type_display_th : '-' ?></td>
													<?php } ?>
													<td class="sum text-right"><?php echo number_format($item->mnetamt, 2); ?></td>
												</tr>
									<?php
											}
										}
									} ?>
								</tbody>
							</table>
						</div>

						<div class="boder-total-sum2">
							<table class="total-sum">
								<tbody>
									<tr>
										<td class="title-sum  text-left">ยอดที่ต้องชำระ (บาท)</td>
										<td class="text-right"><u><?php echo !empty($data->report->total->total_summary) ? number_format($data->report->total->total_summary, 2) : 0 ?></u></td>
									</tr>
								</tbody>
							</table>
						</div>
					<?php } ?>
				<?php } ?>
			</div>

			<div>
				<?php if ($data->report->total_page == $data->index && $data->report->last_count < 21) { ?>
					<?php echo $this->view('total_summary', $data); ?>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
