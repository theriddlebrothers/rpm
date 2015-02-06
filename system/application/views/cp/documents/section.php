<p>
	<label>Section Title</label><br />
	<input type="text" class="text title" name="title" value="<?php echo postback($section, 'title'); ?>" /><br />
	<span class="quiet"><?php echo $type->name; ?></span>
</p>

<p>
	<label>Section Parent</label><br />
	<select name="parent">
		<option value="">No parent</option>
		<?php echo $section_dropdown; ?>
	</select>
</p>


<p>
	<label>Section Priority</label><br />
	<?php echo form_dropdown('priority', $priorities, $section->priority); ?>
</p>