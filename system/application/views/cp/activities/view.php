<div id="activity">

	<h1><?php echo $activity->subject; ?></h1>
		
	<?php if (user_can('edit', 'activities')) : ?>
		<p><a href="<?php echo site_url('activities/edit/' . $activity->id); ?>">Edit</a></p>
	<?php endif; ?>	
		
	<div class="span-12 last">
	
		<fieldset>
			<legend>Activity Information</legend>
			
			<table>
				<tr>
					<th>Date</th>
					<td><?php echo date("m/d/Y @ h:ia", strtotime($activity->activity_date)); ?></td>
				</tr>
				<tr>
					<th>Company</th>
					<td><?php echo $activity->company->name; ?></td>
				</tr>
				<tr>
					<th>Activity Type</th>
					<td><?php echo $activity->activity_type; ?></td>
				</tr>
				</tr>
			</table>
			
		</fieldset>
	</div>
	
	<div class="span-24 last">
		<fieldset>
			<legend>Activity Description</legend>
			
			<?php echo markdownify($activity->description); ?>
			
		</fieldset>
	</div>
	
</div>
