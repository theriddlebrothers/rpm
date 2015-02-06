<?php if ($this->session->flashdata('notification')) : ?>
	<div class="notice">
		<?php echo $this->session->flashdata('notification'); ?>
	</div>
<?php endif; ?>

<?php if ($this->session->flashdata('error')) : ?>
	<div class="error">
		<?php echo $this->session->flashdata('error'); ?>
	</div>
<?php endif; ?>

<?php if ($this->session->flashdata('success')) : ?>
	<div class="success">
		<?php echo $this->session->flashdata('success'); ?>
	</div>
<?php endif; ?>