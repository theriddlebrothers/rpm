<div id="issueForm" class="form-table">

  	<?php if ($errors) : ?>
  		<div class="error">
  			<?php echo $errors; ?>
  		</div>
  	<?php endif; ?>
  	
	
	<div class="span-16">
		<fieldset>
			<legend>Issue Details</legend>

			<table>
				<tr>
					<th class="span-4">Title</th>
					<td><input name="title" type="text" class="text" value="<?php echo postback($issue, 'title'); ?>" /></td>
				</tr>
				<tr>
					<th>Status</th>
					<td>
						<?php echo form_dropdown('status', $statuses, $issue->status); ?>
					</td>
				</tr>
				<tr class="top">
					<th>Description</th>
					<td>
						<textarea name="description" class="text"><?php echo postback($issue, 'description'); ?></textarea>
					</td>
				</tr>
				<!--<tr>
					<th>Attachment</th>
					<td>
						<input type="file" />
					</td>
				</tr>-->
			</table>

			<a class="view-optional" href="#">View Optional Fields</a>
			<div class="optional">	
				<table>
					<tr>
						<th>Category</th>
						<td>
							<?php echo form_dropdown('category', $categories, $issue->category); ?>
						</td>
					</tr>
					<tr>
						<th>Component/Feature</th>
						<td><input name="component" type="text" class="text" value="<?php echo postback($issue, 'component'); ?>" /></td>
					</tr>
					<tr>
						<th>Priority</th>
						<td>
							<?php echo form_dropdown('priority', $priorities, $issue->priority); ?>
						</td>
					</tr>
					<tr class="top">
						<th>Steps to Reproduce</th>
						<td>
							<textarea name="steps" class="text"><?php echo postback($issue, 'steps'); ?></textarea>
						</td>
					</tr>
				</table>
			</div>
		</fieldset>	
	</div>
	
	<div id="userInfo" class="span-8 last">
		<fieldset>
			<legend>User Info</legend>
			
			<table>
				<tr>
					<th>Reported By</th>
					<td>
						<?php echo form_dropdown('reporter', $users, postback($issue, 'reporter', 'id')); ?>
					</td>
				</tr>	
				<tr>
					<th>Date Reported</th>
					<td><input name="date_reported" type="text" class="text datepicker" value="<?php if (postback($issue, 'date_reported')) echo date("m/d/Y", strtotime(postback($issue, 'date_reported'))); ?>" /></td>
				</tr>	
			</table>

			<a class="view-optional" href="#">View Optional Fields</a>
			<div class="optional">
				<table>
					<tr>
						<th>Assigned To</th>
						<td>
							<?php echo form_dropdown('assignee', $users, postback($issue, 'assignee', 'id')); ?>
						</td>
					</tr>	
					<tr>
						<th>Date Due</th>
						<td><input name="date_due" type="text" class="text datepicker" value="<?php if (postback($issue, 'date_due')) echo date("m/d/Y", strtotime(postback($issue, 'date_due'))); ?>" /></td>
					</tr>	
					<tr>
						<th>Browser</th>
						<td>
							<?php echo form_dropdown('browser', $browsers, postback($issue, 'browser')); ?>
						</td>
					</tr>	
					<tr>
						<th>Operating System</th>
						<td>
							<?php echo form_dropdown('operating_system', $operating_systems, postback($issue, 'operating_system')); ?>
						</td>
					</tr>	
					<?php if ($current_user->role != ROLE_CLIENT) : ?>
						<tr>
							<th>Visibility</th>
							<td>
									<?php echo form_dropdown('visibility', $visibilities, postback($issue, 'visibility')); ?>
							</td>
					</tr>	
					<?php endif; ?>
				</table>
			</div>
		
		</fieldset>	
	</div>
		
	<div class="span-24 last">	
                <p>
                    <label><?php echo form_checkbox('notify', 1, false); ?> Send an email notification to users alerting them of this issue.</label>
                </p>
		<p>
			<button type="submit" class="button primary" name="submit">Save</button>
		</p>
	</div>
</div>

<script type="text/javascript">
	$(function() {
		$('.view-optional').click(function() {
			$(this).siblings('.optional').slideDown();
		});
	});
</script>