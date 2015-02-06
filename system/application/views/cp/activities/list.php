<div id="listActivities">

	<h1>Activities</h1>
			
	<div class="span-24 last">
		
		<?php if (user_can('create', 'activities')) : ?>
			<p><a href="<?php echo site_url('activities/create'); ?>">Create new</a></p>
		<?php endif; ?>
		
		<?php if ($activities) : ?>
			<table>
				<thead>
					<tr>
						<th class="span-4">Activity Date</th>
						<th class="span-6">Company</th>
						<th class="span-3">Type</th>
						<th>Subject</th>
						<th class="actions span-2"></th>
					</tr>
				</thead>	
				<tfoot>
					<tr>
						<td colspan="4">
							<?php echo $pager; ?>
						</td>
					</tr>
				</tfoot>
				<tbody>
						<?php foreach($activities as $activity) : ?>
							<tr>
								<td><?php echo date("m/d/Y @ h:ia", strtotime($activity->activity_date)); ?></td>
								<td><?php echo $activity->company->name; ?></td>
								<td><?php echo $activity->activity_type; ?></td>
								<td><?php echo $activity->subject; ?></td>
								<td class="actions span-3">
									<?php if (user_can('view', 'activities')) : ?>
										<a class="icon view activity" href="<?php echo site_url('activities/view/' . $activity->id); ?>">View</a>
									<?php endif; ?>
									<?php if (user_can('edit', 'activities')) : ?>
										<a class="icon edit activity" href="<?php echo site_url('activities/edit/' . $activity->id); ?>">Edit</a>
									<?php endif; ?>
									<?php if (user_can('delete', 'activities')) : ?>
										<a class="icon delete popup activity" class="popup" href="<?php echo site_url('activities/delete/' . $activity->id); ?>">Delete</a>
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
				</tbody>
			</table>				
	
		<?php else : ?>
			<p>No activities listed.</p>
		<?php endif; ?>

	</div>
	
</div>