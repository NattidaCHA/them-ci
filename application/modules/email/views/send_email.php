<?php
echo form_open('/email/send');
echo $this->session->flashdata('email_sent');
?>
<button type="submit" class="btn">send</button>
<?php
echo form_close();
?>