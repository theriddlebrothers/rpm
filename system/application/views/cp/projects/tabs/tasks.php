<?php if (user_can('view', 'tasks')) : ?>


	<div id="tab-tasks">
			
		<?php if ($tasks) : ?>
	
			<table>
				<thead>
					<tr>
						<th>Due Date</th>
						<th>Title</th>
						<th>Assigned To</th>
						<th>Status</th>
						<th></th>
					</tr>
				</thead>
				<tfoot>
				</tfoot>
				<tbody>
					<?php foreach($tasks as $task) : ?>
					<tr>
						<td><?php echo date("m/d/Y", strtotime($task->due_date)); ?></td>
						<td><?php echo truncate($task->name, 50); ?></td>
						<td><?php echo $task->user->name; ?></td>
						<td><?php echo $task->status; ?></td>
						<td>
							<?php if (user_can('view', 'tasks')) : ?>
								<a class="icon view" href="<?php echo site_url('tasks/view/' . $task->id); ?>">View</a>
							<?php endif; ?>
							
							<?php if (user_can('edit', 'tasks')) : ?>
								<a class="icon edit" href="<?php echo site_url('tasks/edit/' . $task->id); ?>">Edit</a>
							<?php endif; ?>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			
		<?php else : ?>
			<p>No tasks listed.</p>
		<?php endif; ?>
		
		
		<p class="actions">
			<?php if (user_can('view', 'tasks')) : ?>
				<a href="<?php echo site_url('tasks/index/?project=' . $project->id); ?>">View All</a>
			<?php endif; ?>
			
			<?php if (user_can('create', 'tasks')) : ?>
				<a href="<?php echo site_url('tasks/create/?project=' . $project->id); ?>">Create new</a>
			<?php endif; ?>
		</p>
		
	</div>
	
<?php endif; ?>