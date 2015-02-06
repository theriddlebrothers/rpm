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
	
<button type="submit" class="button positive" name="submit">
	<img src="/css/blueprint/plugins/buttons/icons/tick.png" alt=""/> Save
</button>