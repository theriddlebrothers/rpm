<div id="estimateterm">

	<h1><?php echo $term->name; ?></h1>
		
	<?php if (user_can('edit', 'estimateterms')) : ?>
		<p><a href="<?php echo site_url('estimateterms/edit/' . $term->id); ?>">Edit</a></p>	
	<?php endif; ?>
	
	<fieldset>
	
		<legend>Terms Content</legend>
	
		<p><?php echo $term->content; ?></p>
		
	</fieldset>
	
</div>
