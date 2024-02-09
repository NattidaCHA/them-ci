<style>
	body {
		margin: 0;
		padding: 0;
		line-height: 1.3;
		font-size: 16px;
		font-weight: 400;
		color: #222222;
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

	p {
		margin: 0;
		padding: 0;
	}

	.footer {
		margin-top: 10px;
	}

	.box-detail-payment {
		padding: 5px;
		margin-top: 0px;
		border: 1px solid gray;
		width: 100%;
		height: 90px;
		font-size: 0.95rem;
	}

	.box-tran {
		padding: 5px;
		width: 100%;
		font-size: 0.95rem;
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

<div class="content">
	<div class="footer">
		<div class="box-detail-payment">
			<p><u><strong><?php echo  $data->tem['footer'][0]->payment_title; ?></strong></u></p>
			<ol>
				<li>
					<p><?php echo  $data->tem['footer'][0]->detail_1_1; ?></p>
					<p><?php echo  $data->tem['footer'][0]->detail_1_2; ?></p>
					</il>
				<li>
					<p><?php echo  $data->tem['footer'][0]->detail_2; ?></p>
					<p><?php echo  $data->tem['footer'][0]->detail_2_1; ?></p>
					<p><u><?php echo  $data->tem['footer'][0]->detail_2_2; ?></u></p>
					<p><?php echo  $data->tem['footer'][0]->detail_2_3; ?></p>
					<p><?php echo  $data->tem['footer'][0]->detail_2_4; ?></p>
					<p><?php echo  $data->tem['footer'][0]->detail_2_5; ?></p>
					<p><?php echo  $data->tem['footer'][0]->detail_2_6; ?></p>
					<p>&nbsp;&nbsp;<?php echo  $data->tem['footer'][0]->detail_2_7; ?></p>
					<p>&nbsp;&nbsp;<?php echo  $data->tem['footer'][0]->detail_2_8; ?></p>
					</il>
				<li>
					<p><?php echo  $data->tem['footer'][0]->detail_3; ?></p>
					</il>
				<li>
					<p><?php echo  $data->tem['footer'][0]->detail_4; ?></p>
					</il>
			</ol>

			<div class="text-contact"><?php echo  $data->tem['footer'][0]->detail_5; ?></div>
		</div>

		<div class="box-tran">
			<p><u><strong><?php echo  $data->tem['bank_tran_detail'][0]->tran_header; ?></strong></u></p>
			<ol>
				<li>
					<p><?php echo  $data->tem['bank_tran_detail'][0]->tran_detail_1; ?></p>
					</il>
				<li>
					<p><?php echo  $data->tem['bank_tran_detail'][0]->tran_detail_2; ?></p>
					</il>
			</ol>
			<?php foreach ($data->tem['bank_tran'] as $tran) { ?>
				<p style="margin-left: 20px;"><strong>
						<span style="margin-right: 5%;"><?php echo $tran->account_name; ?></span>
						<span style="margin-right: 150px;"><?php echo $tran->branch; ?></span>
						<span style="margin-right: 150px;"><?php echo $tran->account_no; ?></span>
					</strong> </p>
			<?php } ?>
			<p><strong><?php echo  $data->tem['bank_tran_detail'][0]->tran_detail_3; ?></strong></p>
		</div>
	</div>
</div>
