<fieldset>
  		
  	<?php if ($errors) : ?>
  		<div class="error">
  			<?php echo $errors; ?>
  		</div>
  	<?php endif; ?>
  	
	<legend>Activity Information</legend>
	
	<p>
		<label>Subject</label><br />
		<input name="subject" type="text" class="title" value="<?php echo postback($activity, 'subject'); ?>" />
	</p>
	
	<?php echo dialog_select_company($activity->company); ?>
	
	<p>
		<label>Activity Type</label><br />
		<?php echo form_dropdown('activity_type', $activity_types, postback($activity, 'activity_type')); ?>
	</p>
	
	<p>
		<label>Activity Date/Time</label><br />
		<input name="activity_date" type="text" class="datetimepicker text" value="<?php if ($activity->activity_date) echo date("m/d/Y @ h:ia", strtotime($activity->activity_date)); ?>" />
	</p>
	
	<p>
		<label>Description</label><br />
		<textarea name="description" class="text span-23"><?php echo postback($activity, 'description'); ?></textarea><br />
		<span class="quiet">Activity description content formatting can be accomplished using <a target="_blank" href="http://michelf.com/projects/php-markdown/extra/">Markdown PHP Extra</a> syntax.</span>
		
	</p>
		
</fieldset>
	
<p>
	<button type="submit" class="button primary" name="submit">Save</button>
</p>