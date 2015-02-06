<div id="issue">

	<div class="span-20 last">
	
		<form method="post" action="">
		
	  		<div id="issueForm" class="form-table">

			  	<?php if ($errors) : ?>
			  		<div class="error">
			  			<?php echo $errors; ?>
			  		</div>
			  	<?php endif; ?>
			  	
				
				<div class="span-12">
					<fieldset>
						<legend>Issue Details</legend>
						
						<table>
							<tr>
								<th class="span-4">Title</th>
								<td><input name="title" type="text" class="text" value="<?php echo postback($issue, 'title'); ?>" /></td>
							</tr>
							<tr>
								<th>Category</th>
								<td>
									<?php echo form_dropdown('category', $categories, $issue->category); ?>
								</td>
							</tr>
							<tr>
								<th>Priority</th>
								<td>
									<?php echo form_dropdown('priority', $priorities, $issue->priority); ?>
								</td>
							</tr>
							<tr>
								<th>Status</th>
								<td>
									<?php echo form_dropdown('status', $statuses, $issue->status); ?>
								</td>
							</tr>
							<tr>
								<th>Component/Feature</th>
								<td><input name="component" type="text" class="text" value="<?php echo postback($issue, 'component'); ?>" /></td>
							</tr>
							<tr class="top">
								<th>Description</th>
								<td>
									<textarea name="description" class="text"><?php echo postback($issue, 'description'); ?></textarea>
								</td>
							</tr>
							<tr class="top">
								<th>Steps to Reproduce</th>
								<td>
									<textarea name="steps" class="text"><?php echo postback($issue, 'steps'); ?></textarea>
								</td>
							</tr>
							<!--<tr>
								<th>Attachment</th>
								<td>
									<input type="file" />
								</td>
							</tr>-->
						</table>
					</fieldset>	
				</div>
				
				<div id="userInfo" class="span-8 last">
					<fieldset>
						<legend>User Info</legend>
						
						<table>
							
							<input type="hidden" name="reporter" value="<?php echo $reporter->id; ?>" />
							<input type="hidden" name="assignee" value="" />
							<input type="hidden" name="date_due" value="" />
							<input type="hidden" name="visibility" value="" />
								
							<tr>
								<th>Date Reported</th>
								<td><input name="date_reported" type="text" class="text datepicker" value="<?php if (postback($issue, 'date_reported')) echo date("m/d/Y", strtotime(postback($issue, 'date_reported'))); ?>" /></td>
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
						</table>
					
					</fieldset>	
				</div>
					
				<div class="span-20 last">	
					<p>
						<button type="submit" class="button primary" name="submit">Save</button>
					</p>
				</div>
			
				
			</div>
		
	 		
		</form>

	</div>

</div>
