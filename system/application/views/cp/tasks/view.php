<div id="task">

	<h1><?php echo $task->name; ?></h1>
		
	<?php if (user_can('edit', 'tasks')) : ?>
		<p><a href="<?php echo site_url('tasks/edit/' . $task->id); ?>">Edit</a></p>	
	<?php endif; ?>
	
	<div class="span-12">
	
		<fieldset>
			<legend>Task Information</legend>
			
			<table>
				<tr>
					<th>Title</th>
					<td><?php echo $task->name; ?></td>
				</tr>
				<tr>
					<th>Task Date</th>
					<td><?php echo $task->task_date; ?></td>
				</tr>
				<tr>
					<th>Due Date</th>
					<td><?php echo $task->due_date; ?></td>
				</tr>
				<tr>
					<th>Project</th>
					<td>
						<?php echo $task->project->name; ?>
					</td>
				</tr>
				<tr>
					<th>Assigned To</th>
					<td><?php echo $task->user->first_name . ' ' . $task->user->last_name; ?></td>
				</tr>
				<tr>
					<th>Status</th>
					<td><?php echo $task->status; ?></td>
				</tr>
			</table>
		
		</fieldset>
	</div>
	
	<div class="span-12 last">
		
		<fieldset>
			<legend>Task Description</legend>	
			
			<p><?php echo $task->description; ?></p>		
		</fieldset>
	
	</div>
	
	<div class="span-24">
		<fieldset>
			<legend>Recent Time Logs</legend>
			
			<?php if ($timelogs) : ?>
				<table>
					<thead>
						<tr>
							<th class="span-2">Date</th>
							<th class="span-3">User</th>
							<th>Description</th>
							<th class="span-2">Time (h:m)</th>
							<th class="span-1"></th>
						</tr>
					</thead>
					<tfoot>
					</tfoot>
					<tbody>
						<?php foreach($timelogs as $timelog) : ?>
							<tr>
								<td><?php echo date("m/d/Y", strtotime($timelog->log_date)); ?></td>
								<td><?php echo $timelog->user->name; ?></td>
								<td><?php echo $timelog->description; ?></td>
								<td><?php echo $timelog->hours; ?></td>
								<td><a class="icon view" href="<?php echo site_url('timelogs/edit/' . $timelog->id); ?>">View</a></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>

			<?php else : ?>
				<p>No time logs to list.</p>
			<?php endif; ?>
		
			<?php if (user_can('view', 'timelogs')) : ?>
				<p><a href="<?php echo site_url('timelogs/?task=' . $task->id); ?>">View All</a></p>
			<?php endif; ?>

		</fieldset>
	</div>
	
</div>
