<div id="listTimelogs">

	<h1>Time Logs</h1>
			
	<div class="span-24 last">
		
		<?php if (user_can('create', 'timelogs')) : ?>
			<p><a href="<?php echo site_url('timelogs/create'); ?>">Create new</a></p>
		<?php endif; ?>
		
		<?php if ($timelogs) : ?>
			<table>
				<thead>
					<tr>
						<th class="span-2">Log Date</th>
						<th>Company</th>
						<th>Project</th>
						<th>Task</th>
						<th class="span-4">User</th>
						<th class="span-2">Time</th>
						<?php if (!in_role(ROLE_EMPLOYEE)) : ?>
							<th class="span-2">Amount</th>
						<?php endif; ?>
						<th class="span-2"></th>
					</tr>
				</thead>	
				<tfoot>
					<tr>
						<td colspan="3">
							<?php echo $pager; ?>
						</td>
					</tr>
				</tfoot>
				<tbody>
						<?php foreach($timelogs as $timelog) : ?>
							<tr>
								<td><?php echo date("m/d/Y", strtotime($timelog->log_date)); ?></td>
								<td><?php echo truncate($timelog->task->project->company->name, 40); ?></td>
								<td><?php echo truncate($timelog->task->project->name, 40); ?></td>
								<td><?php echo truncate($timelog->task->name, 40); ?></td>
								<td><?php echo $timelog->user->name; ?></td>
								<td><?php echo $timelog->hours; ?></td>
								
								<?php if (!in_role(ROLE_EMPLOYEE)) : ?>
								<td>$<?php echo number_format($timelog->convertTimeToHours($timelog->hours) * $timelog->task->project->billable_rate, 2); ?></td>
								<?php endif; ?>
								
								<td class="actions span-2">
									<?php if (user_can('view', 'timelogs')) : ?>
										<a class="icon view" href="<?php echo site_url('timelogs/view/' . $timelog->id); ?>">View</a>
									<?php endif; ?>
									<?php if (user_can('edit', 'timelogs')) : ?>
										<a class="icon edit" href="<?php echo site_url('timelogs/edit/' . $timelog->id); ?>">Edit</a>
									<?php endif; ?>
									<?php if (user_can('delete', 'timelogs')) : ?>
										<a class="icon delete popup" href="<?php echo site_url('timelogs/delete/' . $timelog->id); ?>">Delete</a>
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
				</tbody>
			</table>				
	
		<?php else : ?>
			<p>No time logs listed.</p>
		<?php endif; ?>

	</div>
	
</div>