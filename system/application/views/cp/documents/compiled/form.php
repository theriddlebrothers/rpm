<?php if ($fields) : ?>
	<?php foreach($fields as $field) : ?>
		<h3><?php echo $field->label; ?></h3>
		<p><?php echo str_replace("\n", "<br />", $field->value) ?></p>
	<?php endforeach; ?>
<?php endif; ?>