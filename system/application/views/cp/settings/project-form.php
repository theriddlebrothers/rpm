<fieldset>
  		
  	<?php if ($errors) : ?>
  		<div class="error">
  			<?php echo $errors; ?>
  		</div>
  	<?php endif; ?>
  	
	<legend>Project Information</legend>
	
	<p>
		<label>Project Name</label><br />
		<input name="name" type="text" class="title" value="<?php echo $project->name; ?>" />
	</p>
	
</fieldset>
	
<p>
	<button type="submit" class="button primary" name="submit">Save</button>
</p>