<fieldset>
  		
  	<?php if ($errors) : ?>
  		<div class="error">
  			<?php echo $errors; ?>
  		</div>
  	<?php endif; ?>
  	
	<legend>Time Log Information</legend>
	
	<div class="span-12">
		<p>
			<label>Log Date</label><br />
			<input name="log_date" type="text" class="datepicker text" value="<?php if (postback($timelog, 'log_date')) echo date("m/d/Y", strtotime(postback($timelog, 'log_date'))); ?>" />
		</p>
		
		<p>
			<label>Time (Hours:Minutes)</label><br />
			<input name="hours" type="text" class="number text" value="<?php echo postback($timelog, 'hours'); ?>" />
		</p>
				
		<p>
			<label>Task</label><br />
			<input type="text" class="taskSearch text" value="<?php echo $task_text; ?>" />
			<input id="task_id" name="task_id" type="hidden" value="<?php echo $task_id; ?>" /><br />
			<span class="quiet">Type a project or task name.</span>
		</p>
		
		<?php if (in_role(ROLE_EMPLOYEE)) : ?>
			<input type="hidden" name="user" value="<?php echo $current_user->id; ?>" />
		<?php else : ?>
		<p>
			<label>Log User</label><br />
			<select name="user">
				<option value="">Select a user...</option>
				<?php foreach($users as $user) : ?>
					<option <?php if (postback($timelog, 'user', 'id') == $user->id) echo 'selected="selected" '; ?> value="<?php echo $user->id; ?>"><?php echo $user->name; ?></option>
				<?php endforeach; ?>
			</select>
		</p>
		<?php endif; ?>
		
	</div>
	
	<div class="span-11 last">
		
		<label>Description</label><br />
		
		<p>
			<textarea name="description" class="span-11 text" rows="5" cols="20"><?php echo $timelog->description; ?></textarea>
		</p>
		
	</div>
	
	
</fieldset>

<p>
	<button type="submit" class="button primary" name="submit">Save</button>
</p>



<script type="text/javascript">
	$(function() {
		// search tasks
		$('.taskSearch').autocomplete('/cp/tasks/ajax/search/').result(function(event, data, formatted) {
			var hidden = $('#task_id');
			hidden.val(data[1]);
		});

	});
</script>