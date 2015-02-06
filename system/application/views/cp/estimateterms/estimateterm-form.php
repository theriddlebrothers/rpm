<fieldset>
  		
  	<?php if ($errors) : ?>
  		<div class="error">
  			<?php echo $errors; ?>
  		</div>
  	<?php endif; ?>
  	
	<legend>Term Information</legend>
	
	<p>
		<label>Term Name</label><br />
		<input name="name" type="text" class="title" value="<?php echo postback($term, 'name'); ?>" />
	</p>
	
	<p>
		<label>Term Content</label><br />
		<textarea name="content"><?php echo postback($term, 'content'); ?></textarea>
	</p>
	
</fieldset>
	
<p>
	<button type="submit" class="button primary" name="submit">Save</button>
</p>
