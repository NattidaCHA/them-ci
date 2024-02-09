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
</style>

<div class="pdf">
	<div class="content">
		<?php echo $this->view('bank_des', $data); ?>
	</div>
</div>
