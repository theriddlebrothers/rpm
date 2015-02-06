<div id="document">

	<h2><?php echo $title; ?></h2>
	
	<form action="" method="POST">
	
		<p>
			<label>Template Title</label><br />
			<input type="text" class="text title" name="title" value="<?php echo postback($doc, 'title'); ?>" />
		</p>

		
		
		
		<input type="hidden" name="template_id" value="<?php echo $template_id; ?>" />
		<input type="hidden" name="type" value="<?php echo $type; ?>" />
		
		<?php $this->load->view('cp/documents/forms/' . $type, array('fields'	=>	$fields)); ?>
		
		<p>
			<button type="submit" name="submit" class="button primary">Save</button>
		</p>
	</form>

</div>