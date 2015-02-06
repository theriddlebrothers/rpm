<div id="document">

	<h1><?php echo $doc->title; ?></h1>
	
	<form action="" method="POST">

		<?php $this->load->view('cp/documents/section', array('section_dropdown'=>$section_dropdown)); ?>
		
		<?php $this->load->view('cp/documents/section/requirement'); ?>

		<p>
			<button type="submit" name="submit" class="button primary">Save</button>
		</p>
	</form>	

</div>