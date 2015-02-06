<div id="document">

	<h1><?php echo $doc->title; ?></h1>

	<p class="actions">
		<?php if (user_can('edit', 'documents')) : ?>
			<a href="<?php echo site_url('documents/view/' . $doc->id); ?>" class="button primary printhide">Edit Document</a>
		<?php endif; ?>
		<!--<a href="<?php echo site_url('documents/export/pdf/' . $doc->id); ?>">Download as PDF</a> | -->
		<a class="button secondary printhide" href="<?php echo site_url('documents/export/word/' . $doc->id); ?>">Download as Word</a>
	</p>
	
	<?php echo $compiled; ?>
	
</div>