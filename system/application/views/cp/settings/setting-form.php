<fieldset>
  		
  	<?php if ($errors) : ?>
  		<div class="error">
  			<?php echo $errors; ?>
  		</div>
  	<?php endif; ?>
  	
	<legend>Setting Information</legend>
	
	<p>
		<label>Setting Value</label><br />
		<textarea class="span-23 last" name="value"><?php echo $setting->value; ?></textarea>
	</p>
	
</fieldset>
	
<p>
	<button type="submit" class="button primary" name="submit">Save</button>
</p>