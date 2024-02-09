<style>
	body {
		margin: 0;
		padding: 0;
		line-height: 1.3;
		font-size: 16px;
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
		margin-top: 2px;
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
		height: 62%;
	}

	.full-table-2 {
		width: 100%;
		border-collapse: collapse;
		border-spacing: 0;
		border-color: #dee2e6;
		font-size: 12px;
	}

	table.full-table-2 td,
	table.full-table-2 th {
		padding: 0;
		margin: 0;
	}

	table.full-table-2 th {
		border-top-width: 1px;
		border-top-color: #222;
		border-top-style: solid;
		border-bottom-width: 1px;
		border-bottom-color: #222;
		border-bottom-style: solid;
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
		font-size: 12px;
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
		font-size: 14px;
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
		font-size: 15px;
	}

	.border-bottom {
		border-bottom: 1px dotted #cdcdcd;
	}

	.text-title-bank {
		margin: 0;
		padding: 0;
		font-weight: bold;
		font-size: 16px;
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
		font-size: 14px;
		font-weight: bold;
		text-align: center;
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
		font-size: 15px;
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
		width: 25%;
		text-align: center;
		padding-top: 15px;
		font-weight: bold;
	}

	.qr-scan {
		float: left;
		width: 23%;
	}

	.bacode-scan {
		float: right;
		width: 39%;
	}

	.logo-qr-scan {
		width: 65%;
		/* height: 50%; */
		padding-left: 15px;
		padding-right: 10px;
	}


	.logo-bacode-scan {
		padding-top: 10px;
		width: 100%;
		/* height:0; */
	}

	.total-list {
		margin-left: 30px;
		margin-top: 2px;
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
	}

	.total-amount-in-worde {
		padding-left: 35px;
		font-weight: bold;
	}

	.text-detail-total {
		margin: 0;
		padding: 0;
		font-size: 15px;
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
		margin-top: 10px;
	}

	.boder-noti {
		padding-top: 5px;
		margin-top: 5px;
		border: 2px solid #66FFFF;
		width: 100%;
		height: 10px;
		text-align: center;
		font-size: 16px;
		font-weight: bold;
		margin-bottom: 5px;
	}

	.box-detail-payment {
		padding: 5px;
		margin-top: 10px;
		border: 1px solid gray;
		width: 100%;
		height: 90px;
		font-size: 14px;
	}

	.box-tran {
		padding: 5px;
		width: 100%;
		font-size: 14px;
	}

	ol,
	li {
		padding-left: 10px;
		margin: 0;
	}

	.text-contact {
		font-size: 14px;
		font-weight: bold;
		text-align: center;
	}
</style>

<div class="pdf">
	<div class="content">
		<div class="footer">
			<?php echo $this->view('bank_tran', $data); ?>

			<?php if (($data->report->total_page == $data->index && $data->report->last_count < 11) || ($data->report->total_page == $data->index && $data->report->last_count < 21)) { ?>
				<?php echo $this->view('bank_des', $data); ?>
			<?php } ?>
		</div>
	</div>
</div>
