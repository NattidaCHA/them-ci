<style>
	body {
		margin: 0;
		padding: 0;
		line-height: 1.3;
		font-size: 18px;
		font-weight: 400;
		color: #222222;
	}

	.detail-summary {
		padding-left: 30px;
		padding-right: 30px;
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

	.table-list {
		width: 100%;
		height: 62%;
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
		font-size: 19px;
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

	p {
		margin: 0;
		padding: 0;
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
		font-size: 18px;
	}

	.boder-bottom-red {
		border-bottom: 2px solid #66FFFF;
		margin-bottom: 5px;
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
		font-size: 19px;
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

	.boder-noti {
		padding-top: 5px;
		margin-top: 5px;
		border: 2px solid #66FFFF;
		width: 100%;
		height: 10px;
		text-align: center;
		font-size: 19px;
		font-weight: bold;
		margin-bottom: 5px;
	}
</style>



<div class="boder-noti">
	<p><?php echo  $data->tem['page_footer'][0]->due_detail; ?></p>
	<div class="boder-bottom-red"></div>
	<p><?php echo  $data->tem['page_footer'][0]->cal; ?></p>
</div>
<div class="detail-summary-2">
	<div class="table-summary-2">
		<table class="GeneratedTable2">
			<thead>
				<tr>
					<?php
					foreach ($data->doctypeLists as $key => $doctype) {
						if ($doctype->msort <= 5) {
							echo '<th class="text-center" width="15%">' . $doctype->type_display_th . '</th>';
						}
					}
					?>
				</tr>
			</thead>
			<tbody>
				<tr>
					<?php if (!empty($data->doctypeLists['RA'])) { ?>
						<td class="text-right"><?php echo !empty($data->report->total->total_RA) ? number_format($data->report->total->total_RA, 2) : 0 ?></td>
					<?php  } ?>
					<?php if (!empty($data->doctypeLists['RD'])) { ?>
						<td class="text-right">&nbsp;<?php echo !empty($data->report->total->total_RD) ? '- ' . number_format($data->report->total->total_RD, 2) : 0 ?></td>
					<?php  } ?>
					<?php if (!empty($data->doctypeLists['RC'])) { ?>
						<td class="text-right"><?php echo !empty($data->report->total->total_RC) ? number_format($data->report->total->total_RC, 2) : 0 ?></td>
					<?php  } ?>
					<?php if (!empty($data->doctypeLists['RB'])) { ?>
						<td class="text-right">&nbsp;<?php echo !empty($data->report->total->total_RB) ? '- ' . number_format($data->report->total->total_RB, 2) : 0 ?></td>
					<?php  } ?>
					<?php if (!empty($data->doctypeLists['DC'])) { ?>
						<td class="text-right"><?php echo !empty($data->report->total->total_DC) ? number_format($data->report->total->total_DC, 2) : 0 ?></td>
					<?php  } ?>
				</tr>
			</tbody>
		</table>
	</div>
</div>

<div class="detail-summary-2">
	<div class="table-summary-2">
		<table class="GeneratedTable2">
			<thead>
				<tr>
					<?php
					foreach ($data->doctypeLists as $key => $doctype) {
						if ($doctype->msort > 5) {
							echo '<th class="text-center" width="15%">' . $doctype->type_display_th . '</th>';
						}
					}
					?>
				</tr>
			</thead>
			<tbody>
				<tr>
					<?php if (!empty($data->doctypeLists['RE'])) { ?>
						<td class="text-right">&nbsp;<?php echo !empty($data->report->total->total_RE) ? '- ' . number_format($data->report->total->total_RE, 2) : 0 ?></td>
					<?php  } ?>
					<?php if (!empty($data->doctypeLists['DA'])) { ?>
						<td class="text-right">&nbsp;<?php echo !empty($data->report->total->total_DA) ? '- ' . number_format($data->report->total->total_DA, 2) : 0 ?></td>
					<?php  } ?>
					<?php if (!empty($data->doctypeLists['DB'])) { ?>
						<td class="text-right">&nbsp;<?php echo !empty($data->report->total->total_DB) ? '- ' . number_format($data->report->total->total_DB, 2) : 0 ?></td>
					<?php  } ?>
					<?php if (!empty($data->doctypeLists['DE'])) { ?>
						<td class="text-right">&nbsp;<?php echo !empty($data->report->total->total_DE) ? '- ' . number_format($data->report->total->total_DE, 2) : 0 ?></td>
					<?php  } ?>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<div class="detail-summary">
	<div class="table-summary">
		<table class="GeneratedTable">
			<thead>
				<tr>
					<th class="text-center" width="20%">ยอดรวมรายการแจ้งหนี้</th>
					<th class="text-center" width="20%">ยอดรวมรายการหักลบ</th>
					<th class="text-center" width="20%">ยอดรวมชําระทั้งสิ้น</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="text-right"><?php echo !empty($data->report->total->total_debit) ? number_format($data->report->total->total_debit, 2) : 0 ?></td>
					<td class="text-right">&nbsp;<?php echo !empty($data->report->total->total_credit) ? '- ' . number_format($data->report->total->total_credit, 2) : 0 ?></td>
					<td class="text-right text-danger"><?php echo !empty($data->report->total->total_summary) ? number_format($data->report->total->total_summary, 2) : 0 ?></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="text-amount">จํานวนเอกสารทั้งหมด&nbsp;<?php echo $data->report->total_items ?>&nbsp;รายการ</div>
	<div>
		<div class="mt-05 text-total"><?php echo  $data->tem['page_footer'][0]->contact;
										?></div>
		<!-- <div class="text-total">ประเภท*
			<?php
			//$i = 1;
			//foreach ($data->doctypeLists as $key => $doctype) {
			//echo str_replace(':', ' = ', $doctype->type_display_th);
			//echo $i == count($data->doctypeLists) ? '' : ', ';
			//$i++;
			//}
			?>
		</div>
	</div> -->
	</div>
