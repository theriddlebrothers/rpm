<?php foreach($fields as $field) : ?>
	<p>
		<label><?php echo $field->label; ?></label><br />
		<?php if ($field->type == 'textarea') : ?>
			<textarea title="<?php echo $field->help_text; ?>" name="<?php echo $field->name; ?>" class="text"><?php if (isset($field->value)) echo $field->value; ?></textarea>
		<?php endif; ?>
	</p>
<?php endforeach; ?>
