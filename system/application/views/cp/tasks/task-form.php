
<fieldset>
  		
  	<?php if ($errors) : ?>
  		<div class="error">
  			<?php echo $errors; ?>
  		</div>
  	<?php endif; ?>
  	
	<legend>Task Information</legend>

	<div class="span-12">
		<p>
			<label>Task Name</label><br />
			<input name="name" type="text" class="title" value="<?php echo $task->name; ?>" />
		</p>
		
		
		<p>
			<label>Task Date</label><br />
			<input name="created_date" type="text" class="datepicker text" value="<?php if (postback($task, 'created_date')) echo date("m/d/Y", strtotime(postback($task, 'created_date'))); ?>" />
		</p>

		
		<p>
			<label>Due Date</label><br />
			<input name="due_date" type="text" class="datepicker text" value="<?php if (postback($task, 'due_date')) echo date("m/d/Y", strtotime(postback($task, 'due_date'))); ?>" />
		</p>
				
		<?php echo dialog_select_project($task->project); ?>
		
		<?php echo dialog_select_user($task->user); ?>
					
		<p>
			<label>Status</label><br />
			<select name="status">
				<option value="">Select a status...</option>
				<option <?php if ($task->status == 'Pending') echo ' selected="selected"'; ?> value="Pending">Pending</option>
				<option <?php if ($task->status == 'In Progress') echo ' selected="selected"'; ?> value="In Progress">In Progress</option>
				<option <?php if ($task->status == 'Complete') echo ' selected="selected"'; ?> value="Complete">Complete</option>
				<option <?php if ($task->status == 'Closed') echo ' selected="selected"'; ?> value="Closed">Closed</option>
			</select>
		</p>
		
	</div>
	
	<div class="span-11 last">
		
		<label>Description</label><br />
		
		<p>
			<textarea name="description" class="span-11 text" rows="5" cols="20"><?php echo $task->description; ?></textarea>
		</p>
		
	</div>
	
</fieldset>
	
<p>
	<button type="submit" class="button primary" name="submit">Save</button>
</p>